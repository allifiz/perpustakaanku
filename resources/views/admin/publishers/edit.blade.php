@extends('layouts.admin')

@section('title', 'Edit Publisher')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.penerbit.index') }}">Publishers</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
        <h2>Edit Publisher: {{ $publisher->name }}</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.penerbit.update', $publisher) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Publisher Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $publisher->name) }}" 
                               placeholder="e.g., Penguin Random House" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('country') is-invalid @enderror" 
                               id="country" name="country" value="{{ old('country', $publisher->country) }}" 
                               placeholder="e.g., United States, Indonesia" required>
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $publisher->email) }}" 
                                       placeholder="publisher@example.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $publisher->phone) }}" 
                                       placeholder="+62 21 1234567">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="website" class="form-label">Website</label>
                        <input type="url" class="form-control @error('website') is-invalid @enderror" 
                               id="website" name="website" value="{{ old('website', $publisher->website) }}" 
                               placeholder="https://www.publisher.com">
                        @error('website')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3" 
                                  placeholder="Full address of the publisher">{{ old('address', $publisher->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" 
                                   name="is_active" value="1" {{ old('is_active', $publisher->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active (Available for use)
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Publisher
                        </button>
                        <a href="{{ route('admin.penerbit.index') }}" class="btn btn-secondary">
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
                <h5 class="card-title"><i class="fas fa-info-circle"></i> Publisher Info</h5>
                <table class="table table-sm">
                    <tr>
                        <th>Books Count:</th>
                        <td><span class="badge bg-info">{{ $publisher->books()->count() }}</span></td>
                    </tr>
                    <tr>
                        <th>Created:</th>
                        <td>{{ $publisher->created_at->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Updated:</th>
                        <td>{{ $publisher->updated_at->format('d M Y') }}</td>
                    </tr>
                </table>
                
                @if($publisher->books()->count() > 0)
                <div class="alert alert-warning small">
                    <i class="fas fa-exclamation-triangle"></i> 
                    This publisher has {{ $publisher->books()->count() }} book(s). 
                    You cannot delete it until all books are moved to another publisher.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
