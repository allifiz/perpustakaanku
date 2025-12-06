@extends('layouts.member')

@section('title', 'Edit Profile - Digital Library')

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="fas fa-edit me-2" style="color: var(--primary-color);"></i>
                    Edit Profile
                </h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('member.profile.show') }}">Profile</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('member.profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="row g-4">
        <!-- Left Column: Profile Photo -->
        <div class="col-lg-4">
            <div class="card-modern sticky-top" style="top: 20px;">
                <div class="card-header-modern">
                    <h5 class="mb-0"><i class="fas fa-camera me-2"></i>Profile Photo</h5>
                </div>
                <div class="card-body text-center">
                    <!-- Current Photo -->
                    <div class="mb-4">
                        <div id="photoPreview" class="d-inline-flex align-items-center justify-content-center" 
                             style="width: 180px; height: 180px; background: linear-gradient(135deg, var(--primary-color), var(--primary-light)); border-radius: 50%; box-shadow: 0 8px 24px rgba(79, 70, 229, 0.3); overflow: hidden; position: relative;">
                            @if(auth()->user()->photo)
                                <img src="{{ asset('storage/' . auth()->user()->photo) }}" 
                                     id="currentPhoto"
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <i class="fas fa-user fa-5x text-white" id="defaultIcon"></i>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Upload Button -->
                    <div class="mb-3">
                        <label for="photo" class="btn btn-primary-modern btn-modern w-100">
                            <i class="fas fa-upload me-2"></i>Upload New Photo
                        </label>
                        <input type="file" class="d-none @error('photo') is-invalid @enderror" 
                               id="photo" name="photo" accept="image/*" onchange="previewPhoto(event)">
                        <small class="text-muted d-block mt-2">JPG, PNG or GIF. Max 2MB.</small>
                        @error('photo')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Info -->
                    <div class="alert alert-info-modern" style="background: rgba(79, 70, 229, 0.1); border: 1px solid rgba(79, 70, 229, 0.2); border-radius: 12px;">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>A good profile photo helps librarians identify you.</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column: Form Fields -->
        <div class="col-lg-8">
            <!-- Personal Information -->
            <div class="card-modern mb-4">
                <div class="card-header-modern">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Personal Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">
                                Full Name <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-user text-primary"></i>
                                </span>
                                <input type="text" 
                                       class="form-control border-start-0 @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', auth()->user()->name) }}" 
                                       required
                                       placeholder="Enter your full name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">
                                Email Address <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-envelope text-primary"></i>
                                </span>
                                <input type="email" 
                                       class="form-control border-start-0 bg-light" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', auth()->user()->email) }}" 
                                       readonly>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-lock me-1"></i>Email cannot be changed
                            </small>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold">
                                Phone Number <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-phone text-success"></i>
                                </span>
                                <input type="text" 
                                       class="form-control border-start-0 @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', auth()->user()->phone) }}" 
                                       required
                                       placeholder="e.g., 08123456789">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="student_id" class="form-label fw-semibold">
                                Student/Employee ID
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-id-card text-info"></i>
                                </span>
                                <input type="text" 
                                       class="form-control border-start-0 @error('student_id') is-invalid @enderror" 
                                       id="student_id" 
                                       name="student_id" 
                                       value="{{ old('student_id', auth()->user()->student_id) }}"
                                       placeholder="Optional">
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="institution" class="form-label fw-semibold">
                                Institution/Organization
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-university text-primary"></i>
                                </span>
                                <input type="text" 
                                       class="form-control border-start-0 @error('institution') is-invalid @enderror" 
                                       id="institution" 
                                       name="institution" 
                                       value="{{ old('institution', auth()->user()->institution) }}"
                                       placeholder="Optional">
                                @error('institution')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label for="address" class="form-label fw-semibold">
                                Address <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 align-items-start pt-2">
                                    <i class="fas fa-map-marker-alt text-danger"></i>
                                </span>
                                <textarea class="form-control border-start-0 @error('address') is-invalid @enderror" 
                                          id="address" 
                                          name="address" 
                                          rows="3" 
                                          required
                                          placeholder="Enter your complete address">{{ old('address', auth()->user()->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Identity Document -->
            <div class="card-modern mb-4">
                <div class="card-header-modern">
                    <h5 class="mb-0"><i class="fas fa-id-card-alt me-2"></i>Identity Document</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="id_card" class="form-label fw-semibold">
                                ID Card (KTP/KTM/SIM)
                            </label>
                            <input type="file" 
                                   class="form-control @error('id_card') is-invalid @enderror" 
                                   id="id_card" 
                                   name="id_card"
                                   accept="image/*">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Allowed formats: JPG, PNG, GIF. Maximum size: 2MB
                            </small>
                            @error('id_card')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            
                            @if(auth()->user()->id_card)
                                <div class="mt-3">
                                    <div class="p-3 bg-light rounded border">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <i class="fas fa-file-image text-primary me-2"></i>
                                                <small class="fw-semibold">Current ID Card:</small>
                                            </div>
                                            <a href="{{ Storage::url(auth()->user()->id_card) }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i>View
                                            </a>
                                        </div>
                                        <div class="mt-2">
                                            <img src="{{ Storage::url(auth()->user()->id_card) }}" 
                                                 alt="Current ID Card" 
                                                 class="img-fluid rounded"
                                                 style="max-height: 200px;">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Change Password (Optional Section) -->
            <div class="card-modern mb-4">
                <div class="card-header-modern">
                    <h5 class="mb-0"><i class="fas fa-lock me-2"></i>Change Password (Optional)</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning-modern mb-3" style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2); border-radius: 12px;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <small>Leave blank if you don't want to change your password</small>
                    </div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="current_password" class="form-label fw-semibold">
                                Current Password
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-key text-warning"></i>
                                </span>
                                <input type="password" 
                                       class="form-control border-start-0 @error('current_password') is-invalid @enderror" 
                                       id="current_password" 
                                       name="current_password"
                                       placeholder="Enter current password">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="new_password" class="form-label fw-semibold">
                                New Password
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-lock text-success"></i>
                                </span>
                                <input type="password" 
                                       class="form-control border-start-0 @error('new_password') is-invalid @enderror" 
                                       id="new_password" 
                                       name="new_password"
                                       placeholder="Enter new password">
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="new_password_confirmation" class="form-label fw-semibold">
                                Confirm New Password
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-lock text-success"></i>
                                </span>
                                <input type="password" 
                                       class="form-control border-start-0" 
                                       id="new_password_confirmation" 
                                       name="new_password_confirmation"
                                       placeholder="Confirm new password">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="card-modern">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('member.profile.show') }}" class="btn btn-outline-secondary btn-modern">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary-modern btn-modern">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
function previewPhoto(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('photoPreview');
            const currentPhoto = document.getElementById('currentPhoto');
            const defaultIcon = document.getElementById('defaultIcon');
            
            if (currentPhoto) {
                currentPhoto.src = e.target.result;
            } else if (defaultIcon) {
                defaultIcon.remove();
                const img = document.createElement('img');
                img.id = 'currentPhoto';
                img.src = e.target.result;
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.objectFit = 'cover';
                preview.appendChild(img);
            }
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
@endsection
