@extends('layouts.member')

@section('title', 'Borrowing History - Digital Library')

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="fas fa-history me-2" style="color: var(--primary-color);"></i>
                    Borrowing History
                </h2>
                <p class="text-muted mb-0">Complete history of your library activities</p>
            </div>
            <a href="{{ route('member.dashboard') }}" class="btn btn-outline-primary btn-modern">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card-modern" style="background: linear-gradient(135deg, #10B981, #34D399); color: white;">
            <div class="card-body text-center">
                <i class="fas fa-book-open fa-2x mb-2" style="opacity: 0.8;"></i>
                <h3 class="fw-bold mb-0">{{ $borrowings->count() }}</h3>
                <small>Total Books Borrowed</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-modern" style="background: linear-gradient(135deg, #3B82F6, #60A5FA); color: white;">
            <div class="card-body text-center">
                <i class="fas fa-check-circle fa-2x mb-2" style="opacity: 0.8;"></i>
                <h3 class="fw-bold mb-0">{{ $borrowings->where('status', 'returned')->count() }}</h3>
                <small>Successfully Returned</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-modern" style="background: linear-gradient(135deg, #8B5CF6, #A78BFA); color: white;">
            <div class="card-body text-center">
                <i class="fas fa-bookmark fa-2x mb-2" style="opacity: 0.8;"></i>
                <h3 class="fw-bold mb-0">{{ $borrowings->where('status', 'approved')->count() }}</h3>
                <small>Currently Borrowed</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-modern" style="background: linear-gradient(135deg, #F59E0B, #FBBF24); color: white;">
            <div class="card-body text-center">
                <i class="fas fa-star fa-2x mb-2" style="opacity: 0.8;"></i>
                <h3 class="fw-bold mb-0">
                    @php
                        $onTimeReturns = $borrowings->where('status', 'returned')->filter(function($b) {
                            return $b->return_date && $b->return_date <= $b->due_date;
                        })->count();
                        $totalReturned = $borrowings->where('status', 'returned')->count();
                        $rate = $totalReturned > 0 ? round(($onTimeReturns / $totalReturned) * 100) : 0;
                    @endphp
                    {{ $rate }}%
                </h3>
                <small>On-Time Return Rate</small>
            </div>
        </div>
    </div>
</div>

<!-- Timeline View -->
<div class="card-modern">
    <div class="card-header-modern">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-stream me-2"></i>Activity Timeline</h5>
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-light active" onclick="showAll()">All</button>
                <button type="button" class="btn btn-light" onclick="filterByStatus('returned')">Returned</button>
                <button type="button" class="btn btn-light" onclick="filterByStatus('approved')">Active</button>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @forelse($borrowings->groupBy(function($item) {
            return $item->created_at->format('F Y');
        }) as $month => $monthBorrowings)
            <!-- Month Header -->
            <div class="px-4 py-3" style="background: linear-gradient(90deg, rgba(79, 70, 229, 0.1), transparent); border-left: 4px solid var(--primary-color);">
                <h6 class="mb-0 fw-bold">
                    <i class="fas fa-calendar-alt me-2"></i>{{ $month }}
                    <span class="badge badge-modern bg-primary ms-2">{{ $monthBorrowings->count() }} books</span>
                </h6>
            </div>
            
            <!-- Timeline Items -->
            <div class="timeline-container p-4">
                @foreach($monthBorrowings as $borrowing)
                    <div class="timeline-item borrowing-item" data-status="{{ $borrowing->status }}" style="position: relative; padding-left: 3rem; padding-bottom: 2rem;">
                        <!-- Timeline Dot -->
                        <div style="position: absolute; left: 0; top: 0; width: 40px; height: 40px; border-radius: 50%; 
                                    background: {{ $borrowing->status == 'returned' ? 'linear-gradient(135deg, #10B981, #34D399)' : ($borrowing->status == 'approved' ? 'linear-gradient(135deg, #4F46E5, #818CF8)' : 'linear-gradient(135deg, #F59E0B, #FBBF24)') }};
                                    display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);">
                            @if($borrowing->status == 'returned')
                                <i class="fas fa-check text-white"></i>
                            @elseif($borrowing->status == 'approved')
                                <i class="fas fa-book-open text-white"></i>
                            @else
                                <i class="fas fa-clock text-white"></i>
                            @endif
                        </div>
                        
                        <!-- Timeline Line -->
                        @if(!$loop->last)
                            <div style="position: absolute; left: 19px; top: 40px; bottom: -2rem; width: 2px; background: linear-gradient(180deg, #E5E7EB, transparent);"></div>
                        @endif
                        
                        <!-- Content Card -->
                        <div class="card-modern mb-0" style="transition: all 0.3s ease;" 
                             onmouseover="this.style.transform='translateX(5px)'; this.style.boxShadow='0 8px 24px rgba(0,0,0,0.12)'"
                             onmouseout="this.style.transform='translateX(0)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.08)'">
                            <div class="card-body p-3">
                                <div class="d-flex gap-3">
                                    <!-- Book Cover -->
                                    <div style="width: 60px; min-width: 60px; height: 80px; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                        @if($borrowing->book->cover_image)
                                            <img src="{{ asset('storage/' . $borrowing->book->cover_image) }}" 
                                                 style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-book text-white"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Book Info -->
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="fw-bold mb-1">{{ $borrowing->book->title }}</h6>
                                                <p class="text-muted small mb-0">by {{ $borrowing->book->author }}</p>
                                            </div>
                                            @if($borrowing->status == 'returned')
                                                <span class="badge badge-modern bg-success">
                                                    <i class="fas fa-check-circle me-1"></i>Returned
                                                </span>
                                            @elseif($borrowing->status == 'approved')
                                                @if($borrowing->isOverdue())
                                                    <span class="badge badge-modern bg-danger">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>Overdue
                                                    </span>
                                                @else
                                                    <span class="badge badge-modern bg-primary">
                                                        <i class="fas fa-book-open me-1"></i>Active
                                                    </span>
                                                @endif
                                            @else
                                                <span class="badge badge-modern bg-warning">
                                                    <i class="fas fa-clock me-1"></i>Pending
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="row g-2 small">
                                            <div class="col-md-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-calendar-plus me-1 text-primary"></i>
                                                    Borrowed: <strong>{{ $borrowing->borrow_date->format('d M Y') }}</strong>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-calendar-check me-1 text-success"></i>
                                                    Due: <strong>{{ $borrowing->due_date->format('d M Y') }}</strong>
                                                </div>
                                            </div>
                                            @if($borrowing->return_date)
                                                <div class="col-md-4">
                                                    <div class="text-muted">
                                                        <i class="fas fa-undo me-1 text-info"></i>
                                                        Returned: <strong>{{ $borrowing->return_date->format('d M Y') }}</strong>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        @if($borrowing->status == 'returned')
                                            @php
                                                $duration = $borrowing->borrow_date->diffInDays($borrowing->return_date);
                                                $wasOnTime = $borrowing->return_date <= $borrowing->due_date;
                                            @endphp
                                            <div class="mt-2 p-2 rounded" style="background: {{ $wasOnTime ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)' }};">
                                                <small class="text-{{ $wasOnTime ? 'success' : 'danger' }}">
                                                    <i class="fas fa-{{ $wasOnTime ? 'check-circle' : 'exclamation-circle' }} me-1"></i>
                                                    {{ $wasOnTime ? 'Returned on time' : 'Returned late' }} ({{ $duration }} days)
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @empty
            <div class="p-5 text-center">
                <i class="fas fa-inbox fa-4x text-muted mb-3" style="opacity: 0.3;"></i>
                <h5 class="text-muted mb-2">No History Found</h5>
                <p class="text-muted mb-3">You haven't borrowed any books yet</p>
                <a href="{{ route('catalog.index') }}" class="btn btn-primary-modern btn-modern">
                    <i class="fas fa-search me-2"></i>Browse Catalog
                </a>
            </div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
    function showAll() {
        document.querySelectorAll('.borrowing-item').forEach(item => {
            item.style.display = 'block';
        });
        updateActiveButton(event.target);
    }
    
    function filterByStatus(status) {
        document.querySelectorAll('.borrowing-item').forEach(item => {
            if (item.dataset.status === status) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
        updateActiveButton(event.target);
    }
    
    function updateActiveButton(button) {
        document.querySelectorAll('.btn-group button').forEach(btn => {
            btn.classList.remove('active');
        });
        button.classList.add('active');
    }
</script>
@endpush
@endsection
