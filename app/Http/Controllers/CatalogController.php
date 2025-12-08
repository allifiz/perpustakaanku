<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\Location;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with(['categoryModel', 'publisherModel', 'location'])
            ->where('status', 'available');

        // Search
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%")
                  ->orWhere('subjects', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Advanced filters
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('publisher_id')) {
            $query->where('publisher_id', $request->publisher_id);
        }

        if ($request->filled('language')) {
            $query->where('language', $request->language);
        }

        if ($request->filled('year_from')) {
            $query->where('publication_year', '>=', $request->year_from);
        }

        if ($request->filled('year_to')) {
            $query->where('publication_year', '<=', $request->year_to);
        }

        // Sorting
        $sortBy = $request->get('sort', 'title');
        $sortOrder = $request->get('order', 'asc');
        
        if (in_array($sortBy, ['title', 'author', 'publication_year', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $books = $query->paginate(12)->appends($request->except('page'));
        
        $categories = Category::active()->root()->orderBy('name')->get();
        $publishers = Publisher::active()->orderBy('name')->get();
        $languages = Book::distinct()->pluck('language')->filter();

        return view('catalog.index', compact('books', 'categories', 'publishers', 'languages'));
    }

    public function show($id)
    {
        $book = Book::with(['categoryModel', 'publisherModel', 'location', 'borrowings' => function($q) {
            $q->latest()->limit(5);
        }])->findOrFail($id);

        $relatedBooks = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->where('status', 'available')
            ->limit(6)
            ->get();

        $userReservation = null;
        if (Auth::check()) {
            $userReservation = Reservation::where('user_id', Auth::id())
                ->where('book_id', $book->id)
                ->whereIn('status', ['pending', 'ready'])
                ->first();
        }

        return view('catalog.show', compact('book', 'relatedBooks', 'userReservation'));
    }

    public function reserve(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu untuk reservasi buku.');
        }

        // Validasi input tanggal
        $request->validate([
            'reserved_date' => 'required|date|after_or_equal:today',
            'expired_date' => 'required|date|after:reserved_date',
        ], [
            'reserved_date.required' => 'Tanggal mulai peminjaman harus diisi',
            'reserved_date.after_or_equal' => 'Tanggal mulai tidak boleh sebelum hari ini',
            'expired_date.required' => 'Tanggal selesai peminjaman harus diisi',
            'expired_date.after' => 'Tanggal selesai harus setelah tanggal mulai',
        ]);

        $user = Auth::user();
        $book = Book::findOrFail($id);

        // Check if user can borrow
        if (!$user->canBorrow()) {
            return redirect()->back()
                ->with('error', 'Anda telah mencapai batas peminjaman atau keanggotaan Anda telah berakhir.');
        }

        // Check if book is available
        if (!$book->isAvailable()) {
            return redirect()->back()
                ->with('error', 'Buku ini saat ini tidak tersedia.');
        }

        // Check if user already has active reservation
        $existingReservation = Reservation::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->whereIn('status', ['pending', 'ready'])
            ->first();

        if ($existingReservation) {
            return redirect()->back()
                ->with('error', 'Anda sudah memiliki reservasi aktif untuk buku ini.');
        }

        // Create reservation
        Reservation::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'reserved_date' => $request->reserved_date,
            'expired_date' => $request->expired_date,
            'status' => 'pending',
        ]);

        return redirect()->back()
            ->with('success', 'Buku berhasil direservasi. Silakan tunggu persetujuan admin.');
    }
}
