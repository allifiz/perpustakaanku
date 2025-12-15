<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;


class BorrowingController extends Controller
{

public function index(Request $request)
{
    $q = Borrowing::with(['user','book']);

    // (opsional) filter lain
    if ($request->filled('search')) {
        $s = $request->input('search');
        $q->where(function($x) use ($s) {
            $x->whereHas('user', fn($u) => $u->where('name','like',"%$s%"))
              ->orWhereHas('book', fn($b) => $b->where('title','like',"%$s%"));
        });
    }
    if ($request->filled('status')) {
        $q->where('status', $request->input('status'));
    }

    // 2 input tanggal (OR: borrow_date ATAU due_date di dalam range)
    if ($request->filled('from') || $request->filled('to')) {
        $from = $request->input('from') ?: '0001-01-01';
        $to   = $request->input('to')   ?: '9999-12-31';

        $q->where(function($x) use ($from, $to) {
            $x->whereBetween(DB::raw('DATE(borrow_date)'), [$from, $to])
              ->orWhereBetween(DB::raw('DATE(due_date)'),   [$from, $to]);
        });
    }

    $borrowings = $q->latest()->paginate(10)->appends($request->query());

    return view('admin.peminjaman.index', compact('borrowings'));
}



    public function create()
    {
        $users = User::where('status', 'active')->get();
        $books = Book::where('available_copies', '>', 0)->where('status', 'available')->get();
        return view('admin.peminjaman.create', compact('users', 'books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'borrow_date' => 'required|date',
            'due_date' => 'required|date|after:borrow_date',
        ]);

        $book = Book::findOrFail($request->book_id);

        if ($book->available_copies <= 0) {
            return redirect()->back()->with('error', 'Buku tidak tersedia.')->withInput();
        }

        Borrowing::create([
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'borrow_date' => $request->borrow_date,
            'due_date' => $request->due_date,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.peminjaman.index')->with('success', 'Permintaan peminjaman berhasil dibuat.');
    }

    public function approve(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'pending') {
            return redirect()->back()->with('error', 'Permintaan peminjaman tidak dapat disetujui.');
        }

        $book = $borrowing->book;
        if ($book->available_copies <= 0) {
            return redirect()->back()->with('error', 'Buku tidak tersedia.');
        }

        $borrowing->update([
            'status' => 'approved',
        ]);

        $book->decrement('available_copies');

        return redirect()->back()->with('success', 'Permintaan peminjaman berhasil disetujui.');
    }

    public function returnBook(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->status !== 'approved') {
            return redirect()->back()->with('error', 'Buku tidak dapat dikembalikan.');
        }

        // Logic Check Overdue & Denda
        $isOverdue = $borrowing->isOverdue();
        
        // Jika terlambat & belum dikonfirmasi bayar denda
        if ($isOverdue && !$request->has('confirm_return')) {
            $daysOverdue = $borrowing->days_overdue;
            $finePerDay = 1000; // Bisa dibuat dinamis / ambil dari config
            $fineAmount = $daysOverdue * $finePerDay;

            return view('admin.peminjaman.return_confirm', compact(
                'borrowing', 'daysOverdue', 'finePerDay', 'fineAmount'
            ));
        }

        // Simpan Data Pengembalian
        $data = [
            'status' => 'returned',
            'return_date' => now(),
            'returned_to' => auth()->id(), // Admin yang memproses
        ];

        // Jika ada denda yang dibayar
        if ($request->has('confirm_return')) {
            $data['fine_amount'] = $request->input('fine_amount', 0);
            $data['fine_paid'] = true;
            $data['notes'] = $request->input('notes');
        }

        $borrowing->update($data);

        $borrowing->book->increment('available_copies');

        $message = 'Buku berhasil dikembalikan.';
        if(isset($data['fine_amount']) && $data['fine_amount'] > 0) {
            $message .= ' Pembayaran denda sebesar Rp ' . number_format($data['fine_amount'], 0, ',', '.') . ' telah dicatat.';
        }

        return redirect()->route('admin.peminjaman.index')->with('success', $message);
    }

    public function destroy(Borrowing $borrowing)
    {
        $borrowing->delete();
        return redirect()->route('admin.peminjaman.index')->with('success', 'Data peminjaman berhasil dihapus.');
    }

  public function history()
    {
        // Tambahkan filter dan pencarian
        $query = Borrowing::with(['user', 'book'])
            ->where('status', 'returned')
            ->latest();

        // Filter pencarian
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('book', function($q2) use ($search) {
                    $q2->where('title', 'like', "%{$search}%");
                });
            });
        }

        // Filter tanggal
        if (request('start_date')) {
            $query->whereDate('borrow_date', '>=', request('start_date'));
        }

        if (request('end_date')) {
            $query->whereDate('borrow_date', '<=', request('end_date'));
        }

        $borrowings = $query->paginate(10)->appends(request()->except('page'));

        return view('admin.peminjaman.history', compact('borrowings'));
    }
public function exportPdf(Request $request)
{
    $q = Borrowing::with(['user','book']);

    if ($request->filled('search')) {
        $s = $request->input('search');
        $q->where(function($x) use ($s) {
            $x->whereHas('user', fn($u) => $u->where('name','like',"%$s%"))
              ->orWhereHas('book', fn($b) => $b->where('title','like',"%$s%"));
        });
    }
    if ($request->filled('status')) {
        $q->where('status', $request->input('status'));
    }

    if ($request->filled('from') || $request->filled('to')) {
        $from = $request->input('from') ?: '0001-01-01';
        $to   = $request->input('to')   ?: '9999-12-31';

        $q->where(function($x) use ($from, $to) {
            $x->whereBetween(DB::raw('DATE(borrow_date)'), [$from, $to])
              ->orWhereBetween(DB::raw('DATE(due_date)'),   [$from, $to]);
        });
    }

    $borrowings = $q->orderBy('borrow_date')->get();

    $pdf = Pdf::loadView('admin.peminjaman.pdf', [
        'borrowings' => $borrowings,
        'from'       => $request->input('from'),
        'to'         => $request->input('to'),
    ])->setPaper('A4', 'landscape');

    return $pdf->download('borrowings_'.now()->format('Y-m-d_His').'.pdf');
}
    // (opsional) index() kalau kamu ingin pakai controller, bukan closure
}