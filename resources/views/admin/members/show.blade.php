{{-- resources/views/admin/members/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Member Details')

@section('content')
<div class="row">
    <div class="col-12">
        <h1><i class="fas fa-user"></i> Member Details</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.members.index') }}">Members</a></li>
                <li class="breadcrumb-item active">Details</li>
            </ol>
        </nav>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Member Information</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="30%">Name</th>
                        <td>{{ $member->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $member->email }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $member->phone }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{ $member->address }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($member->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($member->status == 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Registered</th>
                        <td>{{ $member->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Last Updated</th>
                        <td>{{ $member->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">ID Card</h5>
            </div>
            <div class="card-body text-center">
                @if($member->id_card)
                    <img src="{{ Storage::url($member->id_card) }}" alt="ID Card" class="img-fluid rounded">
                @else
                    <p class="text-muted">No ID card uploaded</p>
                @endif
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Actions</h5>
            </div>
            <div class="card-body">
                @if($member->status == 'pending')
                    <form action="{{ route('admin.members.status', $member) }}" method="POST" class="mb-2">
                        @csrf
                        <input type="hidden" name="status" value="active">
                        <button type="submit" class="btn btn-success btn-sm w-100" 
                                onclick="return confirm('Approve this member?')">
                            <i class="fas fa-check"></i> Approve Member
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.members.status', $member) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="btn btn-danger btn-sm w-100" 
                                onclick="return confirm('Reject this member?')">
                            <i class="fas fa-times"></i> Reject Member
                        </button>
                    </form>
                @endif
                
                <form action="{{ route('admin.members.destroy', $member) }}" method="POST" class="mt-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm w-100" 
                            onclick="return confirm('Delete this member? This cannot be undone.')">
                        <i class="fas fa-trash"></i> Delete Member
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history"></i> Borrowing History</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Borrow Date</th>
                                <th>Due Date</th>
                                <th>Return Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($member->borrowings as $borrowing)
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
                                <td colspan="5" class="text-center">No borrowing history.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection