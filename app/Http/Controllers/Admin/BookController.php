<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with(['categoryModel', 'publisherModel', 'location']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        $books = $query->latest()->paginate(15);
        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        $categories = Category::active()->orderBy('name')->get();
        $publishers = Publisher::active()->orderBy('name')->get();
        $locations = Location::active()->orderBy('name')->get();
        
        return view('admin.books.create', compact('categories', 'publishers', 'locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'nullable|unique:books,barcode',
            'call_number' => 'nullable|max:50',
            'ddc' => 'nullable|max:20',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|unique:books,isbn',
            'publisher' => 'nullable|string|max:255',
            'publisher_id' => 'nullable|exists:publishers,id',
            'publication_year' => 'required|integer|min:1800|max:' . (date('Y') + 1),
            'language' => 'nullable|max:50',
            'edition' => 'nullable|max:50',
            'pages' => 'nullable|integer|min:1',
            'series' => 'nullable|max:255',
            'subjects' => 'nullable',
            'physical_description' => 'nullable|max:255',
            'category' => 'nullable|string|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'location_id' => 'nullable|exists:locations,id',
            'shelf_position' => 'nullable|integer',
            'collection_type' => 'nullable|in:reference,circulation,reserve,digital',
            'total_copies' => 'required|integer|min:1',
            'available_copies' => 'required|integer|min:0',
            'status' => 'required|in:available,unavailable,maintenance',
            'description' => 'nullable',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'nullable|numeric|min:0',
            'source' => 'nullable|max:255',
            'acquisition_date' => 'nullable|date',
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        // Auto-generate barcode if not provided
        if (empty($validated['barcode'])) {
            $validated['barcode'] = 'BK' . date('Ymd') . str_pad(Book::count() + 1, 5, '0', STR_PAD_LEFT);
        }

        Book::create($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Book created successfully.');
    }

    public function edit(Book $book)
    {
        $categories = Category::active()->orderBy('name')->get();
        $publishers = Publisher::active()->orderBy('name')->get();
        $locations = Location::active()->orderBy('name')->get();
        
        return view('admin.books.edit', compact('book', 'categories', 'publishers', 'locations'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'barcode' => 'nullable|unique:books,barcode,' . $book->id,
            'call_number' => 'nullable|max:50',
            'ddc' => 'nullable|max:20',
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'nullable|string|unique:books,isbn,' . $book->id,
            'publisher' => 'nullable|string|max:255',
            'publisher_id' => 'nullable|exists:publishers,id',
            'publication_year' => 'required|integer|min:1800|max:' . (date('Y') + 1),
            'language' => 'nullable|max:50',
            'edition' => 'nullable|max:50',
            'pages' => 'nullable|integer|min:1',
            'series' => 'nullable|max:255',
            'subjects' => 'nullable',
            'physical_description' => 'nullable|max:255',
            'category' => 'nullable|string|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'location_id' => 'nullable|exists:locations,id',
            'shelf_position' => 'nullable|integer',
            'collection_type' => 'nullable|in:reference,circulation,reserve,digital',
            'total_copies' => 'required|integer|min:1',
            'available_copies' => 'required|integer|min:0',
            'status' => 'required|in:available,unavailable,maintenance',
            'description' => 'nullable',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'nullable|numeric|min:0',
            'source' => 'nullable|max:255',
            'acquisition_date' => 'nullable|date',
        ]);

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        // Update available copies based on total copies change
        $copiesDifference = $validated['total_copies'] - $book->total_copies;
        $validated['available_copies'] = max(0, $book->available_copies + $copiesDifference);

        $book->update($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book)
    {
        if ($book->borrowings()->whereIn('status', ['pending', 'approved'])->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete book with active borrowings.');
        }

        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }
        
        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Book deleted successfully.');
    }
}