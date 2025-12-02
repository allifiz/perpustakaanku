<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowingController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $borrowings = $user->borrowings()->with('book')->latest()->paginate(10);
        return view('member.borrowings.index', compact('borrowings'));
    }

     public function create(Request $request)
    {
        $q = trim($request->input('q', ''));

        // WAJIB paginate(), jangan get()
        $bookList = Book::query()
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->where('title', 'like', "%{$q}%")
                      ->orWhere('author', 'like', "%{$q}%");
                });
            })
            ->orderBy('title')
            ->paginate(12)                         // <-- paginate
            ->appends($request->query());          // bawa ?q= ke pagination

        return view('member.borrowings.create', compact('bookList', 'q'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Gunakan pengecekan langsung karena method isActive mungkin tidak dikenali
        if ($user->status !== 'active') {
            return redirect()->back()->with('error', 'Your account is not yet approved.')->withInput();
        }

        $book = Book::findOrFail($request->book_id);

        // Gunakan pengecekan langsung untuk ketersediaan buku
        if (!($book->available_copies > 0 && $book->status === 'available')) {
            return redirect()->back()->with('error', 'Book is not available.')->withInput();
        }

        Borrowing::create([
            'user_id' => $user->id,
            'book_id' => $request->book_id,
            'borrow_date' => now(),
            'due_date' => now()->addDays(14), // 2 weeks loan period
            'status' => 'pending',
        ]);

        return redirect()->route('member.borrowings.index')->with('success', 'Borrowing request submitted successfully.');
    }

    public function history()
    {
        /** @var User $user */
        $user = Auth::user();
        $borrowings = $user->borrowings()->with('book')->latest()->paginate(10);
        return view('member.borrowings.history', compact('borrowings'));
    }
}