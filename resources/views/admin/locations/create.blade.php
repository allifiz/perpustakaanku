@extends('layouts.admin')

@section('title', 'Add New Location')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.locations.index') }}">Locations</a></li>
                <li class="breadcrumb-item active">Add New</li>
            </ol>
        </nav>
        <h2>Add New Location</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.locations.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="code" class="form-label">Location Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       id="code" name="code" value="{{ old('code') }}" 
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
                                    <option value="room" {{ old('type') == 'room' ? 'selected' : '' }}>Room</option>
                                    <option value="shelf" {{ old('type') == 'shelf' ? 'selected' : '' }}>Shelf</option>
                                    <option value="cabinet" {{ old('type') == 'cabinet' ? 'selected' : '' }}>Cabinet</option>
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
                               id="name" name="name" value="{{ old('name') }}" 
                               placeholder="e.g., Reading Room A, Science Shelf 1" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Brief description of this location">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="capacity" class="form-label">Capacity</label>
                        <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                               id="capacity" name="capacity" value="{{ old('capacity') }}" 
                               min="0" placeholder="Maximum number of books">
                        <small class="form-text text-muted">Leave empty for unlimited capacity</small>
                        @error('capacity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" 
                                   name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active (Available for use)
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Location
                        </button>
                        <a href="{{ route('admin.locations.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-info-circle"></i> Location Types</h5>
                <dl class="small">
                    <dt><i class="fas fa-door-open text-primary"></i> Room</dt>
                    <dd>A physical room in the library (e.g., Reading Room, Storage Room)</dd>
                    
                    <dt><i class="fas fa-book text-info"></i> Shelf</dt>
                    <dd>Bookshelf or rack for storing books</dd>
                    
                    <dt><i class="fas fa-archive text-secondary"></i> Cabinet</dt>
                    <dd>Enclosed storage unit or locker</dd>
                </dl>
                <hr>
                <h6 class="small">Code Examples:</h6>
                <ul class="small">
                    <li><code>A1</code> - Shelf A, Section 1</li>
                    <li><code>RR01</code> - Reading Room 01</li>
                    <li><code>CAB-05</code> - Cabinet 05</li>
                    <li><code>STOR-1</code> - Storage 1</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
