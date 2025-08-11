{{-- resources/views/admin/books/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manage Books')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1><i class="fas fa-book"></i> Manage Books</h1>
            <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Book
            </a>
        </div>
        <hr>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.books.index') }}">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search by title or author" 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="category" class="form-control">
                                <option value="">All Categories</option>
                                @foreach(\App\Models\Book::select('category')->distinct()->get() as $book)
                                    <option value="{{ $book->category }}" {{ request('category') == $book->category ? 'selected' : '' }}>
                                        {{ $book->category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Cover</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>Available</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($books as $book)
                            <tr>
                                <td>
                                    @if($book->cover_image)
                                        <img src="{{ Storage::url($book->cover_image) }}" alt="Cover" width="50">
                                    @else
                                        <div class="bg-light" style="width: 50px; height: 70px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-book"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $book->title }}</td>
                                <td>{{ $book->author }}</td>
                                <td>{{ $book->category }}</td>
                                <td>{{ $book->available_copies }} / {{ $book->total_copies }}</td>
                                <td>
                                    @if($book->status == 'available')
                                        <span class="badge bg-success">Available</span>
                                    @elseif($book->status == 'borrowed')
                                        <span class="badge bg-warning">Borrowed</span>
                                    @else
                                        <span class="badge bg-secondary">Maintenance</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Delete this book? This cannot be undone.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No books found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    {{ $books->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection