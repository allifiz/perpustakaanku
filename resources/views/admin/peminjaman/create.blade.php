{{-- resources/views/admin/borrowings/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'New Borrowing')

@section('content')
<div class="row">
    <div class="col-12">
        <h1><i class="fas fa-plus"></i> New Borrowing</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.peminjaman.index') }}">Borrowings</a></li>
                <li class="breadcrumb-item active">New</li>
            </ol>
        </nav>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.peminjaman.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="user_id" class="form-label">Member *</label>
                                <select class="form-control @error('user_id') is-invalid @enderror" 
                                        id="user_id" name="user_id" required>
                                    <option value="">Select Member</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="book_id" class="form-label">Book *</label>
                                <select class="form-control @error('book_id') is-invalid @enderror" 
                                        id="book_id" name="book_id" required>
                                    <option value="">Select Book</option>
                                    @foreach($books as $book)
                                        <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                            {{ $book->title }} by {{ $book->author }} 
                                            (Available: {{ $book->available_copies }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('book_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="borrow_date" class="form-label">Borrow Date *</label>
                                <input type="date" class="form-control @error('borrow_date') is-invalid @enderror" 
                                       id="borrow_date" name="borrow_date" value="{{ old('borrow_date', date('Y-m-d')) }}" required>
                                @error('borrow_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="due_date" class="form-label">Due Date *</label>
                                <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                       id="due_date" name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+14 days'))) }}" required>
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Borrowing
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection