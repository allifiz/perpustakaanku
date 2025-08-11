{{-- resources/views/member/profile/show.blade.php --}}
@extends('layouts.member')

@section('title', 'My Profile')

@section('content')
<div class="row">
    <div class="col-12">
        <h1><i class="fas fa-user"></i> My Profile</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Profile</li>
            </ol>
        </nav>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Profile Information</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="30%">Name</th>
                        <td>{{ auth()->user()->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ auth()->user()->email }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ auth()->user()->phone }}</td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>{{ auth()->user()->address }}</td>
                    </tr>
                    <tr>
                        <th>Account Status</th>
                        <td>
                            @if(auth()->user()->status == 'pending')
                                <span class="badge bg-warning">Pending Approval</span>
                            @elseif(auth()->user()->status == 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Member Since</th>
                        <td>{{ auth()->user()->created_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
                
                <a href="{{ route('member.profile.edit') }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Profile
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">ID Card</h5>
            </div>
            <div class="card-body text-center">
                @if(auth()->user()->id_card)
                    <img src="{{ Storage::url(auth()->user()->id_card) }}" alt="ID Card" class="img-fluid rounded">
                @else
                    <p class="text-muted">No ID card uploaded</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection