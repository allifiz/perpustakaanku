
{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Dashboard Overview</h2>
        <p class="text-muted">Welcome back, {{ Auth::user()->name }}!</p>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="stats-card bg-primary text-white">
            <div class="icon-wrapper">
                <i class="fas fa-users"></i>
            </div>
            <div class="card-title-light">Total Members</div>
            <h3>{{ \App\Models\User::where('role', 'member')->count() }}</h3>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="stats-card bg-success text-white">
            <div class="icon-wrapper">
                <i class="fas fa-book"></i>
            </div>
            <div class="card-title-light">Total Books</div>
            <h3>{{ \App\Models\Book::count() }}</h3>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="stats-card bg-warning text-white">
            <div class="icon-wrapper">
                <i class="fas fa-clock"></i>
            </div>
            <div class="card-title-light">Pending Borrowings</div>
            <h3>{{ \App\Models\Borrowing::where('status', 'pending')->count() }}</h3>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="stats-card bg-info text-white">
            <div class="icon-wrapper">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="card-title-light">Active Borrowings</div>
            <h3>{{ \App\Models\Borrowing::where('status', 'approved')->count() }}</h3>
        </div>
    </div>
    <div class="col-md-3 mb-4">
    <div class="stats-card bg-dark text-white">
        <div class="icon-wrapper">
            <i class="fas fa-eye"></i>
        </div>
        <div class="card-title-light">Visitors Today</div>
        <h3>{{ $todayVisitors }}</h3>
        <small>{{ $onlineNow }} online • {{ $todayHits }} hits</small>
    </div>
</div>

</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Recent Members</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Registered</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\User::where('role', 'member')->latest()->limit(5)->get() as $member)
                            <tr>
                                <td>{{ $member->name }}</td>
                                <td>
                                    @if($member->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($member->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>{{ $member->created_at->format('d M Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-exchange-alt me-2"></i>Recent Borrowings</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Book</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\Borrowing::with(['user', 'book'])->latest()->limit(5)->get() as $borrowing)
                            <tr>
                                <td>{{ $borrowing->user->name }}</td>
                                <td>{{ $borrowing->book->title }}</td>
                                <td>
                                    @if($borrowing->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($borrowing->status == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($borrowing->status == 'returned')
                                        <span class="badge bg-info">Returned</span>
                                    @else
                                        <span class="badge bg-danger">Overdue</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection