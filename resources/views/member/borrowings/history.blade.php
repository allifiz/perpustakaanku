{{-- resources/views/member/borrowings/history.blade.php --}}
@extends('layouts.member')

@section('title', 'Borrowing History')

@section('content')
<div class="row">
    <div class="col-12">
        <h1><i class="fas fa-history"></i> Borrowing History</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">History</li>
            </ol>
        </nav>
        <hr>
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
                                <th>Return Date</th>
                                <th>Days Borrowed</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($borrowings as $borrowing)
                            <tr>
                                <td>{{ $borrowing->book->title }}</td>
                                <td>{{ $borrowing->borrow_date->format('d M Y') }}</td>
                                <td>{{ $borrowing->due_date->format('d M Y') }}</td>
                                <td>
                                    @if($borrowing->return_date)
                                        {{ $borrowing->return_date->format('d M Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($borrowing->return_date)
                                        {{ $borrowing->borrow_date->diffInDays($borrowing->return_date) }} days
                                    @else
                                        {{ $borrowing->borrow_date->diffInDays(now()) }} days (ongoing)
                                    @endif
                                </td>
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
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">You have no borrowing history.</td>
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