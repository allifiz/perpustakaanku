{{-- resources/views/member/borrowings/create.blade.php --}}
@extends('layouts.member')

@section('title', 'Borrow Book')

@section('content')
<div class="row">
    <div class="col-12">
        <h1><i class="fas fa-plus"></i> Borrow Book</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('member.borrowings.index') }}">Borrowings</a></li>
                <li class="breadcrumb-item active">Borrow New</li>
            </ol>
        </nav>
        <hr>
    </div>
</div>

@if(!auth()->user()->isActive())
    <div class="row">
        <div class="col-12">
            <div class="alert alert-warning">
                <h4><i class="fas fa-exclamation-triangle"></i> Account Not Approved</h4>
                <p>Your account is not yet approved by the administrator. You cannot borrow books until your account is approved.</p>
            </div>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('member.borrowings.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="book_id" class="form-label">Select Book *</label>
                            <select class="form-control @error('book_id') is-invalid @enderror" 
                                    id="book_id" name="book_id" required>
                                <option value="">Select a book to borrow</option>
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
                        
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle"></i> Borrowing Information</h5>
                            <p>Standard loan period is 14 days. You can borrow one book at a time.</p>
                            <p>Your borrowing request will need administrator approval before it's processed.</p>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('member.borrowings.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Submit Borrowing Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection