{{-- resources/views/admin/books/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Book')

@section('content')
<div class="row">
    <div class="col-12">
        <h1><i class="fas fa-edit"></i> Edit Book</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.books.index') }}">Books</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.books.update', $book) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $book->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="author" class="form-label">Author *</label>
                                <input type="text" class="form-control @error('author') is-invalid @enderror" 
                                       id="author" name="author" value="{{ old('author', $book->author) }}" required>
                                @error('author')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="isbn" class="form-label">ISBN *</label>
                                <input type="text" class="form-control @error('isbn') is-invalid @enderror" 
                                       id="isbn" name="isbn" value="{{ old('isbn', $book->isbn) }}" required>
                                @error('isbn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="publisher" class="form-label">Publisher *</label>
                                <input type="text" class="form-control @error('publisher') is-invalid @enderror" 
                                       id="publisher" name="publisher" value="{{ old('publisher', $book->publisher) }}" required>
                                @error('publisher')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="publication_year" class="form-label">Publication Year *</label>
                                <input type="number" class="form-control @error('publication_year') is-invalid @enderror" 
                                       id="publication_year" name="publication_year" 
                                       value="{{ old('publication_year', $book->publication_year) }}" min="1900" max="{{ date('Y') + 1 }}" required>
                                @error('publication_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="category" class="form-label">Category *</label>
                                <input type="text" class="form-control @error('category') is-invalid @enderror" 
                                       id="category" name="category" value="{{ old('category', $book->category) }}" required>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="total_copies" class="form-label">Total Copies *</label>
                                <input type="number" class="form-control @error('total_copies') is-invalid @enderror" 
                                       id="total_copies" name="total_copies" value="{{ old('total_copies', $book->total_copies) }}" min="1" required>
                                @error('total_copies')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="cover_image" class="form-label">Cover Image</label>
                                <input type="file" class="form-control @error('cover_image') is-invalid @enderror" 
                                       id="cover_image" name="cover_image">
                                <div class="form-text">Allowed file types: JPG, PNG, GIF. Max size: 2MB</div>
                                @if($book->cover_image)
                                    <div class="mt-2">
                                        <small>Current cover:</small><br>
                                        <img src="{{ Storage::url($book->cover_image) }}" alt="Current Cover" width="100">
                                    </div>
                                @endif
                                @error('cover_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4">{{ old('description', $book->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Book
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection