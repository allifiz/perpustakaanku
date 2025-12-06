@extends('layouts.admin')

@section('title', 'Add New Book')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.books.index') }}">Books</a></li>
                <li class="breadcrumb-item active">Add New</li>
            </ol>
        </nav>
        <h2><i class="fas fa-plus"></i> Add New Book</h2>
    </div>
</div>

<form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data">
    @csrf
    
    <div class="row">
        <div class="col-md-8">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-book"></i> Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="author" class="form-label">Author <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('author') is-invalid @enderror" 
                                       id="author" name="author" value="{{ old('author') }}" required>
                                @error('author')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="isbn" class="form-label">ISBN <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('isbn') is-invalid @enderror" 
                                       id="isbn" name="isbn" value="{{ old('isbn') }}" required>
                                @error('isbn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="barcode" class="form-label">Barcode</label>
                                <input type="text" class="form-control @error('barcode') is-invalid @enderror" 
                                       id="barcode" name="barcode" value="{{ old('barcode') }}" 
                                       placeholder="Auto-generated if empty">
                                <small class="text-muted">Leave empty for auto-generate</small>
                                @error('barcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="publication_year" class="form-label">Publication Year <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('publication_year') is-invalid @enderror" 
                                       id="publication_year" name="publication_year" 
                                       value="{{ old('publication_year', date('Y')) }}" min="1900" max="{{ date('Y') + 1 }}" required>
                                @error('publication_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Classification & Location -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-sitemap"></i> Classification & Location</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                        id="category_id" name="category_id" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->code }} - {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="publisher_id" class="form-label">Publisher <span class="text-danger">*</span></label>
                                <select class="form-select @error('publisher_id') is-invalid @enderror" 
                                        id="publisher_id" name="publisher_id" required>
                                    <option value="">-- Select Publisher --</option>
                                    @foreach($publishers as $publisher)
                                        <option value="{{ $publisher->id }}" {{ old('publisher_id') == $publisher->id ? 'selected' : '' }}>
                                            {{ $publisher->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('publisher_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="ddc" class="form-label">DDC Number</label>
                                <input type="text" class="form-control @error('ddc') is-invalid @enderror" 
                                       id="ddc" name="ddc" value="{{ old('ddc') }}" 
                                       placeholder="e.g., 005.133">
                                <small class="text-muted">Dewey Decimal</small>
                                @error('ddc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="call_number" class="form-label">Call Number</label>
                                <input type="text" class="form-control @error('call_number') is-invalid @enderror" 
                                       id="call_number" name="call_number" value="{{ old('call_number') }}" 
                                       placeholder="e.g., 005.1 SMI">
                                @error('call_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="location_id" class="form-label">Location <span class="text-danger">*</span></label>
                                <select class="form-select @error('location_id') is-invalid @enderror" 
                                        id="location_id" name="location_id" required>
                                    <option value="">-- Select Location --</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                            {{ $location->code }} - {{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('location_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="shelf_position" class="form-label">Shelf Position</label>
                        <input type="text" class="form-control @error('shelf_position') is-invalid @enderror" 
                               id="shelf_position" name="shelf_position" value="{{ old('shelf_position') }}" 
                               placeholder="e.g., Row 3, Level 2">
                        @error('shelf_position')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Additional Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Additional Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="language" class="form-label">Language</label>
                                <input type="text" class="form-control @error('language') is-invalid @enderror" 
                                       id="language" name="language" value="{{ old('language', 'Indonesian') }}" 
                                       placeholder="e.g., Indonesian, English">
                                @error('language')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edition" class="form-label">Edition</label>
                                <input type="text" class="form-control @error('edition') is-invalid @enderror" 
                                       id="edition" name="edition" value="{{ old('edition') }}" 
                                       placeholder="e.g., 1st, 2nd, Revised">
                                @error('edition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="pages" class="form-label">Pages</label>
                                <input type="number" class="form-control @error('pages') is-invalid @enderror" 
                                       id="pages" name="pages" value="{{ old('pages') }}" min="1">
                                @error('pages')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="series" class="form-label">Series</label>
                                <input type="text" class="form-control @error('series') is-invalid @enderror" 
                                       id="series" name="series" value="{{ old('series') }}" 
                                       placeholder="e.g., Harry Potter Series">
                                @error('series')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="collection_type" class="form-label">Collection Type</label>
                                <select class="form-select @error('collection_type') is-invalid @enderror" 
                                        id="collection_type" name="collection_type">
                                    <option value="general" {{ old('collection_type') == 'general' ? 'selected' : '' }}>General Collection</option>
                                    <option value="reference" {{ old('collection_type') == 'reference' ? 'selected' : '' }}>Reference</option>
                                    <option value="reserve" {{ old('collection_type') == 'reserve' ? 'selected' : '' }}>Reserve</option>
                                    <option value="special" {{ old('collection_type') == 'special' ? 'selected' : '' }}>Special Collection</option>
                                </select>
                                @error('collection_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="subjects" class="form-label">Subjects</label>
                        <input type="text" class="form-control @error('subjects') is-invalid @enderror" 
                               id="subjects" name="subjects" value="{{ old('subjects') }}" 
                               placeholder="e.g., Programming, Java, Web Development (comma separated)">
                        <small class="text-muted">Separate multiple subjects with commas</small>
                        @error('subjects')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Acquisition Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Acquisition Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Price (Rp)</label>
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price') }}" min="0" step="0.01">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="acquisition_date" class="form-label">Acquisition Date</label>
                                <input type="date" class="form-control @error('acquisition_date') is-invalid @enderror" 
                                       id="acquisition_date" name="acquisition_date" value="{{ old('acquisition_date', date('Y-m-d')) }}">
                                @error('acquisition_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="total_copies" class="form-label">Total Copies <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('total_copies') is-invalid @enderror" 
                               id="total_copies" name="total_copies" value="{{ old('total_copies', 1) }}" min="1" required>
                        @error('total_copies')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <!-- Cover Image -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-image"></i> Cover Image</h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img id="cover-preview" src="https://via.placeholder.com/300x400?text=No+Cover" 
                             class="img-fluid rounded" style="max-height: 400px;">
                    </div>
                    <div class="mb-3">
                        <label for="cover_image" class="form-label">Upload Cover</label>
                        <input type="file" class="form-control @error('cover_image') is-invalid @enderror" 
                               id="cover_image" name="cover_image" accept="image/*" onchange="previewCover(event)">
                        <small class="text-muted">Max 2MB</small>
                                @error('cover_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                        @error('cover_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Quick Info -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-lightbulb"></i> Tips</h5>
                </div>
                <div class="card-body">
                    <ul class="small">
                        <li>Fields marked with <span class="text-danger">*</span> are required</li>
                        <li>Barcode will be auto-generated if left empty</li>
                        <li>Use DDC number for proper classification</li>
                        <li>Call number format: DDC + Author's last name initial</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Book
                </button>
            </div>
        </div>
    </div>
</form>

<script>
function previewCover(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('cover-preview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection