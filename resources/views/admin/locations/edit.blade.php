@extends('layouts.admin')

@section('title', 'Edit Location')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.lokasi.index') }}">Locations</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
        <h2>Edit Location: {{ $location->name }}</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.lokasi.update', $location) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code" class="form-label">Location Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       id="code" name="code" value="{{ old('code', $location->code) }}" 
                                       placeholder="e.g., A1, B2, RM01" maxlength="10" required>
                                <small class="form-text text-muted">Unique identifier for this location</small>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" 
                                        id="type" name="type" required>
                                    <option value="">-- Select Type --</option>
                                    <option value="room" {{ old('type', $location->type) == 'room' ? 'selected' : '' }}>Room</option>
                                    <option value="shelf" {{ old('type', $location->type) == 'shelf' ? 'selected' : '' }}>Shelf</option>
                                    <option value="cabinet" {{ old('type', $location->type) == 'cabinet' ? 'selected' : '' }}>Cabinet</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Location Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $location->name) }}" 
                               placeholder="e.g., Reading Room A, Science Shelf 1" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Brief description of this location">{{ old('description', $location->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="capacity" class="form-label">Capacity</label>
                        <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                               id="capacity" name="capacity" value="{{ old('capacity', $location->capacity) }}" 
                               min="0" placeholder="Maximum number of books">
                        <small class="form-text text-muted">Leave empty for unlimited capacity</small>
                        @error('capacity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" 
                                   name="is_active" value="1" {{ old('is_active', $location->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active (Available for use)
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Location
                        </button>
                        <a href="{{ route('admin.lokasi.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-info-circle"></i> Location Info</h5>
                <table class="table table-sm">
                    <tr>
                        <th>Books Count:</th>
                        <td><span class="badge bg-info">{{ $location->books()->count() }}</span></td>
                    </tr>
                    @if($location->capacity)
                    <tr>
                        <th>Capacity:</th>
                        <td>{{ $location->capacity }} books</td>
                    </tr>
                    <tr>
                        <th>Usage:</th>
                        <td>
                            @php
                                $percentage = ($location->books()->count() / $location->capacity) * 100;
                            @endphp
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar {{ $percentage > 90 ? 'bg-danger' : ($percentage > 70 ? 'bg-warning' : 'bg-success') }}" 
                                     style="width: {{ min($percentage, 100) }}%">
                                    {{ number_format($percentage, 0) }}%
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th>Created:</th>
                        <td>{{ $location->created_at->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Updated:</th>
                        <td>{{ $location->updated_at->format('d M Y') }}</td>
                    </tr>
                </table>
                
                @if($location->books()->count() > 0)
                <div class="alert alert-warning small">
                    <i class="fas fa-exclamation-triangle"></i> 
                    This location has {{ $location->books()->count() }} book(s). 
                    You cannot delete it until all books are moved to another location.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
