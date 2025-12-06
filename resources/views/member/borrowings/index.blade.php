@extends('layouts.member')

@section('title', 'My Books - Digital Library')

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="fas fa-bookmark me-2" style="color: var(--primary-color);"></i>
                    My Books
                </h2>
                <p class="text-muted mb-0">Manage your borrowed books</p>
            </div>
            <a href="{{ route('catalog.index') }}" class="btn btn-primary-modern btn-modern">
                <i class="fas fa-plus-circle me-2"></i>Borrow New Book
            </a>
        </div>
    </div>
</div>

<!-- Filter Tabs -->
<div class="row mb-4">
    <div class="col-12">
        <ul class="nav nav-pills" style="background: white; padding: 0.5rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            <li class="nav-item">
                <a class="nav-link active" href="#active" data-bs-toggle="pill" style="border-radius: 8px; font-weight: 500;">
                    <i class="fas fa-book-open me-2"></i>Active ({{ $borrowings->where('status', 'approved')->count() }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#pending" data-bs-toggle="pill" style="border-radius: 8px; font-weight: 500;">
                    <i class="fas fa-clock me-2"></i>Pending ({{ $borrowings->where('status', 'pending')->count() }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#returned" data-bs-toggle="pill" style="border-radius: 8px; font-weight: 500;">
                    <i class="fas fa-check-circle me-2"></i>Returned ({{ $borrowings->where('status', 'returned')->count() }})
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="tab-content">
    <!-- Active Books -->
    <div class="tab-pane fade show active" id="active">
        <div class="row g-4">
            @forelse($borrowings->where('status', 'approved') as $borrowing)
                <div class="col-md-6">
                    <div class="card-modern h-100">
                        <div class="card-body">
                            <div class="d-flex gap-3">
                                <!-- Book Cover -->
                                <div style="width: 100px; min-width: 100px; height: 140px; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                                    @if($borrowing->book->cover_image)
                                        <img src="{{ asset('storage/' . $borrowing->book->cover_image) }}" 
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-book text-white fa-3x"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Book Info -->
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold mb-2">{{ $borrowing->book->title }}</h5>
                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-user me-1"></i>{{ $borrowing->book->author }}
                                    </p>
                                    
                                    <div class="mb-3">
                                        <div class="d-flex gap-2 mb-2">
                                            <span class="badge badge-modern bg-primary">
                                                <i class="fas fa-calendar me-1"></i>
                                                Borrowed: {{ $borrowing->borrow_date->format('d M Y') }}
                                            </span>
                                        </div>
                                        <div class="d-flex gap-2 align-items-center">
                                            @if($borrowing->isOverdue())
                                                <span class="badge badge-modern bg-danger">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                                    Overdue since {{ $borrowing->due_date->format('d M Y') }}
                                                </span>
                                            @else
                                                <span class="badge badge-modern bg-success">
                                                    <i class="fas fa-clock me-1"></i>
                                                    Due: {{ $borrowing->due_date->format('d M Y') }}
                                                </span>
                                                <small class="text-muted">
                                                    ({{ $borrowing->due_date->diffForHumans() }})
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Progress Bar -->
                                    @php
                                        $total_days = $borrowing->borrow_date->diffInDays($borrowing->due_date);
                                        $days_passed = $borrowing->borrow_date->diffInDays(now());
                                        $percentage = min(100, ($days_passed / max($total_days, 1)) * 100);
                                    @endphp
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between small mb-1">
                                            <span class="text-muted">Loan Period Progress</span>
                                            <span class="fw-bold">{{ number_format($percentage, 0) }}%</span>
                                        </div>
                                        <div class="progress" style="height: 8px; border-radius: 50px;">
                                            <div class="progress-bar {{ $percentage > 75 ? 'bg-danger' : ($percentage > 50 ? 'bg-warning' : 'bg-success') }}" 
                                                 style="width: {{ $percentage }}%; border-radius: 50px;"></div>
                                        </div>
                                    </div>
                                    
                                    @if($borrowing->isOverdue())
                                        <div class="alert alert-danger small mb-0 mt-2" style="border-radius: 8px;">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Please return this book as soon as possible to avoid fines.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card-modern text-center py-5">
                        <i class="fas fa-book-open fa-4x text-muted mb-3" style="opacity: 0.3;"></i>
                        <h5 class="text-muted mb-2">No Active Books</h5>
                        <p class="text-muted mb-3">You don't have any borrowed books at the moment</p>
                        <a href="{{ route('catalog.index') }}" class="btn btn-primary-modern btn-modern">
                            <i class="fas fa-search me-2"></i>Browse Catalog
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
    
    <!-- Pending Books -->
    <div class="tab-pane fade" id="pending">
        <div class="row g-4">
            @forelse($borrowings->where('status', 'pending') as $borrowing)
                <div class="col-md-6">
                    <div class="card-modern h-100">
                        <div class="card-body">
                            <div class="d-flex gap-3">
                                <div style="width: 100px; min-width: 100px; height: 140px; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                                    @if($borrowing->book->cover_image)
                                        <img src="{{ asset('storage/' . $borrowing->book->cover_image) }}" 
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-book text-white fa-3x"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold mb-2">{{ $borrowing->book->title }}</h5>
                                    <p class="text-muted small mb-3">
                                        <i class="fas fa-user me-1"></i>{{ $borrowing->book->author }}
                                    </p>
                                    
                                    <div class="alert alert-warning mb-0" style="border-radius: 8px;">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-hourglass-half fa-2x me-3"></i>
                                            <div>
                                                <strong>Waiting for Approval</strong>
                                                <p class="small mb-0">Requested on {{ $borrowing->created_at->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card-modern text-center py-5">
                        <i class="fas fa-clock fa-4x text-muted mb-3" style="opacity: 0.3;"></i>
                        <h5 class="text-muted mb-2">No Pending Requests</h5>
                        <p class="text-muted">You don't have any pending borrowing requests</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
    
    <!-- Returned Books -->
    <div class="tab-pane fade" id="returned">
        <div class="row g-4">
            @forelse($borrowings->where('status', 'returned')->take(6) as $borrowing)
                <div class="col-md-6">
                    <div class="card-modern h-100" style="opacity: 0.9;">
                        <div class="card-body">
                            <div class="d-flex gap-3">
                                <div style="width: 80px; min-width: 80px; height: 110px; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                    @if($borrowing->book->cover_image)
                                        <img src="{{ asset('storage/' . $borrowing->book->cover_image) }}" 
                                             style="width: 100%; height: 100%; object-fit: cover; filter: grayscale(30%);">
                                    @else
                                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #9CA3AF, #6B7280); display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-book text-white fa-2x"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1">{{ $borrowing->book->title }}</h6>
                                    <p class="text-muted small mb-2">{{ $borrowing->book->author }}</p>
                                    
                                    <div class="small text-muted">
                                        <div class="mb-1">
                                            <i class="fas fa-calendar-check me-1 text-success"></i>
                                            Returned: {{ $borrowing->return_date ? $borrowing->return_date->format('d M Y') : 'N/A' }}
                                        </div>
                                        <div>
                                            <i class="fas fa-clock me-1"></i>
                                            Borrowed: {{ $borrowing->borrow_date->format('d M Y') }} - {{ $borrowing->due_date->format('d M Y') }}
                                        </div>
                                    </div>
                                    
                                    <span class="badge badge-modern bg-info mt-2">
                                        <i class="fas fa-check-circle me-1"></i>Completed
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card-modern text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3" style="opacity: 0.3;"></i>
                        <h5 class="text-muted mb-2">No Return History</h5>
                        <p class="text-muted">You haven't returned any books yet</p>
                    </div>
                </div>
            @endforelse
        </div>
        
        @if($borrowings->where('status', 'returned')->count() > 6)
            <div class="text-center mt-4">
                <a href="{{ route('member.borrowings.history') }}" class="btn btn-outline-primary btn-modern">
                    <i class="fas fa-history me-2"></i>View Full History
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Activate pill based on hash
    document.addEventListener('DOMContentLoaded', function() {
        const hash = window.location.hash;
        if (hash) {
            const pill = document.querySelector(`a[href="${hash}"]`);
            if (pill) {
                new bootstrap.Tab(pill).show();
            }
        }
        
        // Update hash on pill change
        document.querySelectorAll('a[data-bs-toggle="pill"]').forEach(pill => {
            pill.addEventListener('shown.bs.tab', function (e) {
                window.location.hash = e.target.getAttribute('href');
            });
        });
    });
</script>
@endpush
@endsection
