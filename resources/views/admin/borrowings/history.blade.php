{{-- resources/views/admin/borrowings/history.blade.php --}}
@extends('layouts.admin')

@section('title', 'Borrowing History')

@section('content')
<div class="row">
    <div class="col-12">
        <h1><i class="fas fa-history"></i> Borrowing History</h1>
        <hr>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.borrowings.history') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Search by member or book" 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="start_date" class="form-control" 
                                   value="{{ request('start_date') }}" placeholder="Start Date">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="end_date" class="form-control" 
                                   value="{{ request('end_date') }}" placeholder="End Date">
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
                                <th>Member</th>
                                <th>Book</th>
                                <th>Borrow Date</th>
                                <th>Due Date</th>
                                <th>Return Date</th>
                                <th>Days Borrowed</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($borrowings as $borrowing)
                            <tr>
                                <td>{{ $borrowing->user->name }}</td>
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
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No borrowing history found.</td>
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