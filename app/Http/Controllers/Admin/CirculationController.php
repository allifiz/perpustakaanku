<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Http\Request;

class CirculationController extends Controller
{
    public function index(Request $request)
    {
        $member = null;
        $activeBorrowings = collect();
        $pendingBorrowings = collect();

        if ($request->filled('member_id')) {
            // Try to find by ID or Member Card Number
            $search = $request->input('member_id');
            $member = User::where('id', $search)
                        ->orWhere('member_card_number', $search)
                        ->first();

            if ($member) {
                $activeBorrowings = Borrowing::with('book')
                    ->where('user_id', $member->id)
                    ->where('status', 'approved')
                    ->latest()
                    ->get();
                
                $pendingBorrowings = Borrowing::with('book')
                    ->where('user_id', $member->id)
                    ->where('status', 'pending')
                    ->latest()
                    ->get();
            } else {
                return redirect()->route('admin.sirkulasi.index')->with('error', 'Anggota tidak ditemukan.');
            }
        }

        return view('admin.circulation.index', compact('member', 'activeBorrowings', 'pendingBorrowings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:users,id',
            'book_barcode' => 'required|string',
        ]);

        $memberId = $request->input('member_id');
        $barcode = $request->input('book_barcode');

        // Cari buku berdasarkan barcode atau ID
        $book = Book::where('barcode', $barcode)
                    ->orWhere('id', $barcode) // Fallback for testing without barcodes
                    ->first();

        if (!$book) {
            return redirect()->route('admin.sirkulasi.index', ['member_id' => $memberId])
                             ->with('error', 'Buku tidak ditemukan.');
        }

        // Cek apakah buku sedang dipinjam oleh user ini (untuk pengembalian/selesai)
        // Note: User hanya minta flow peminjaman, tapi good practice check duplicate
        $existingLoan = Borrowing::where('user_id', $memberId)
                                 ->where('book_id', $book->id)
                                 ->where('status', 'approved')
                                 ->first();

        if ($existingLoan) {
            // Optional: Auto Return? Or just Error?
            // User request is about "Approve Peminjaman". If already borrowed, warn.
            return redirect()->route('admin.sirkulasi.index', ['member_id' => $memberId])
                             ->with('error', 'Buku ini sedang dipinjam oleh anggota ini.');
        }

        // Cek apakah ada pending request (Online Booking)
        $pendingRequest = Borrowing::where('user_id', $memberId)
                                   ->where('book_id', $book->id)
                                   ->where('status', 'pending')
                                   ->first();

        if ($pendingRequest) {
            // APPROVE FLOW (Sesuai request user: Scan kartu -> Scan buku -> Approve)
            // Cek ketersediaan (walaupun pending, harusnya stok aman, tapi double check)
            if ($book->available_copies > 0) {
                 $pendingRequest->update(['status' => 'approved']);
                 $book->decrement('available_copies');
                 $msg = 'Peminjaman disetujui (Dari Reservasi).';
            } else {
                 return redirect()->route('admin.sirkulasi.index', ['member_id' => $memberId])
                             ->with('error', 'Stok buku habis.');
            }
        } else {
            // WALK-IN FLOW (Langsung pinjam tanpa reservasi)
            if ($book->available_copies > 0) {
                Borrowing::create([
                    'user_id' => $memberId,
                    'book_id' => $book->id,
                    'borrow_date' => now(),
                    'due_date' => now()->addDays(14), // Default 2 weeks
                    'status' => 'approved',
                ]);
                $book->decrement('available_copies');
                $msg = 'Peminjaman berhasil (Walk-in).';
            } else {
                return redirect()->route('admin.sirkulasi.index', ['member_id' => $memberId])
                             ->with('error', 'Stok buku habis.');
            }
        }

        return redirect()->route('admin.sirkulasi.index', ['member_id' => $memberId])
                         ->with('success', $msg);
    }
}
