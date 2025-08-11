{{-- resources/views/member/dashboard.blade.php --}}
@extends('layouts.member')

@section('title', 'Member Dashboard')

@section('content')
<!-- Hero Section -->
<section class="hero-section mb-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold">Welcome, {{ auth()->user()->name }}!</h1>
                <p class="lead">Manage your library experience with ease.</p>
                @if(!auth()->user()->isActive())
                    <div class="alert alert-warning d-inline-block">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Your account is pending approval
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<div class="row mb-4">
    <div class="col-12">
        <h2>Your Library Dashboard</h2>
        <p class="text-muted">Quick overview of your borrowing activities</p>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="dashboard-card">
            <div class="card-icon bg-primary bg-opacity-10 text-primary">
                <i class="fas fa-book"></i>
            </div>
            <h3>{{ auth()->user()->borrowings()->where('status', 'approved')->count() }}</h3>
            <div class="card-title">Books Borrowed</div>
            <a href="{{ route('member.borrowings.index') }}" class="btn btn-primary btn-sm">View Details</a>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="dashboard-card">
            <div class="card-icon bg-info bg-opacity-10 text-info">
                <i class="fas fa-history"></i>
            </div>
            <h3>{{ auth()->user()->borrowings()->where('status', 'returned')->count() }}</h3>
            <div class="card-title">Books Returned</div>
            <a href="{{ route('member.borrowings.history') }}" class="btn btn-info btn-sm">View History</a>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="dashboard-card">
            <div class="card-icon bg-warning bg-opacity-10 text-warning">
                <i class="fas fa-clock"></i>
            </div>
            <h3>{{ auth()->user()->borrowings()->where('status', 'pending')->count() }}</h3>
            <div class="card-title">Pending Requests</div>
            <a href="{{ route('member.borrowings.index') }}" class="btn btn-warning btn-sm">View Requests</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>Recent Borrowings</h5>
            </div>
            <div class="card-body">
                @forelse(auth()->user()->borrowings()->with('book')->latest()->limit(5)->get() as $borrowing)
                    <div class="borrowing-item {{ $borrowing->status }}">
                        <h6>{{ $borrowing->book->title }}</h6>
                        <div class="borrowing-meta">
                            <span>
                                @if($borrowing->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($borrowing->status == 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($borrowing->status == 'returned')
                                    <span class="badge bg-info">Returned</span>
                                @else
                                    <span class="badge bg-danger">Overdue</span>
                                @endif
                            </span>
                            <span>{{ $borrowing->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center">No borrowing records found.</p>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Available Books</h5>
            </div>
            <div class="card-body">
                @forelse(\App\Models\Book::where('available_copies', '>', 0)->where('status', 'available')->limit(3)->get() as $book)
                    <div class="book-card mb-3">
                        <div class="book-cover">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div class="book-info">
                            <h5>{{ $book->title }}</h5>
                            <div class="book-meta">
                                <div><i class="fas fa-user me-1"></i> {{ $book->author }}</div>
                                <div><i class="fas fa-tag me-1"></i> {{ $book->category }}</div>
                            </div>
                            <div class="available-copies">
                                <i class="fas fa-check-circle me-1"></i> {{ $book->available_copies }} copies available
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center">No books available.</p>
                @endforelse
                <a href="{{ route('member.borrowings.create') }}" class="btn btn-primary w-100">
                    <i class="fas fa-plus me-2"></i>Borrow a Book
                </a>
            </div>
        </div>
    </div>
</div>
@endsection