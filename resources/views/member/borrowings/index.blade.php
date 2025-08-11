{{-- resources/views/member/borrowings/index.blade.php --}}
@extends('layouts.member')

@section('title', 'My Borrowings')

@section('content')
<div class="row">
    <div class="col-12">
        <h1><i class="fas fa-exchange-alt"></i> My Borrowings</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Borrowings</li>
            </ol>
        </nav>
        <hr>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('member.borrowings.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Borrow New Book
        </a>
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
                                <th>Book</th>
                                <th>Borrow Date</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($borrowings as $borrowing)
                            <tr>
                                <td>{{ $borrowing->book->title }}</td>
                                <td>{{ $borrowing->borrow_date->format('d M Y') }}</td>
                                <td>{{ $borrowing->due_date->format('d M Y') }}</td>
                                <td>
                                    @if($borrowing->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($borrowing->status == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                        @if($borrowing->isOverdue())
                                            <span class="badge bg-danger">Overdue</span>
                                        @endif
                                    @elseif($borrowing->status == 'returned')
                                        <span class="badge bg-info">Returned</span>
                                    @else
                                        <span class="badge bg-danger">Overdue</span>
                                    @endif
                                </td>
                                <td>
                                    @if($borrowing->status == 'approved' && !$borrowing->return_date)
                                        <span class="text-muted">Waiting for return</span>
                                    @elseif($borrowing->status == 'pending')
                                        <span class="text-muted">Waiting for approval</span>
                                    @else
                                        <span class="text-muted">Completed</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">You have no borrowing records.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    {{ $borrowings->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection