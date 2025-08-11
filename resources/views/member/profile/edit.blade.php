{{-- resources/views/member/profile/edit.blade.php --}}
@extends('layouts.member')

@section('title', 'Edit Profile')

@section('content')
<div class="row">
    <div class="col-12">
        <h1><i class="fas fa-edit"></i> Edit Profile</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('member.profile.show') }}">Profile</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('member.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required readonly>
                                <div class="form-text">Email cannot be changed</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone *</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="address" class="form-label">Address *</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="4" required>{{ old('address', auth()->user()->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="id_card" class="form-label">ID Card (KTP/KTM)</label>
                                <input type="file" class="form-control @error('id_card') is-invalid @enderror" 
                                       id="id_card" name="id_card">
                                <div class="form-text">Allowed file types: JPG, PNG, GIF. Max size: 2MB</div>
                                @if(auth()->user()->id_card)
                                    <div class="mt-2">
                                        <small>Current ID card:</small><br>
                                        <img src="{{ Storage::url(auth()->user()->id_card) }}" alt="Current ID Card" width="100">
                                    </div>
                                @endif
                                @error('id_card')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('member.profile.show') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection