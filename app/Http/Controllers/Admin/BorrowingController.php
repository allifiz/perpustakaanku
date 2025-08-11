<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function index()
    {
        $borrowings = Borrowing::with(['user', 'book'])->latest()->paginate(10);
        return view('admin.borrowings.index', compact('borrowings'));
    }

    public function create()
    {
        $users = User::where('status', 'active')->get();
        $books = Book::where('available_copies', '>', 0)->where('status', 'available')->get();
        return view('admin.borrowings.create', compact('users', 'books'));
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
            return redirect()->back()->with('error', 'Book is not available.')->withInput();
        }

        Borrowing::create([
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'borrow_date' => $request->borrow_date,
            'due_date' => $request->due_date,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.borrowings.index')->with('success', 'Borrowing request created successfully.');
    }

    public function approve(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'pending') {
            return redirect()->back()->with('error', 'Borrowing request cannot be approved.');
        }

        $book = $borrowing->book;
        if ($book->available_copies <= 0) {
            return redirect()->back()->with('error', 'Book is not available.');
        }

        $borrowing->update([
            'status' => 'approved',
        ]);

        $book->decrement('available_copies');

        return redirect()->back()->with('success', 'Borrowing request approved successfully.');
    }

    public function returnBook(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'approved') {
            return redirect()->back()->with('error', 'Book cannot be returned.');
        }

        $borrowing->update([
            'status' => 'returned',
            'return_date' => now(),
        ]);

        $borrowing->book->increment('available_copies');

        return redirect()->back()->with('success', 'Book returned successfully.');
    }

    public function destroy(Borrowing $borrowing)
    {
        $borrowing->delete();
        return redirect()->route('admin.borrowings.index')->with('success', 'Borrowing record deleted successfully.');
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

        return view('admin.borrowings.history', compact('borrowings'));
    }
}