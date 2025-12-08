@extends('layouts.admin')

@section('title', 'Kelola Lokasi')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Storage Locations</h2>
        <p class="text-muted">Manage library shelves, rooms, and storage areas</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('admin.lokasi.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Location
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="80">Code</th>
                        <th>Name</th>
                        <th width="100">Type</th>
                        <th>Description</th>
                        <th width="100">Capacity</th>
                        <th width="80">Status</th>
                        <th width="100">Books</th>
                        <th width="150" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($locations as $location)
                    <tr>
                        <td><code>{{ $location->code }}</code></td>
                        <td><strong>{{ $location->name }}</strong></td>
                        <td>
                            @if($location->type == 'room')
                                <span class="badge bg-primary"><i class="fas fa-door-open"></i> Room</span>
                            @elseif($location->type == 'shelf')
                                <span class="badge bg-info"><i class="fas fa-book"></i> Shelf</span>
                            @else
                                <span class="badge bg-secondary"><i class="fas fa-archive"></i> Cabinet</span>
                            @endif
                        </td>
                        <td>
                            @if($location->description)
                                <small>{{ Str::limit($location->description, 40) }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($location->capacity)
                                <span class="badge bg-light text-dark">{{ $location->capacity }}</span>
                            @else
                                <span class="text-muted">∞</span>
                            @endif
                        </td>
                        <td>
                            @if($location->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $booksCount = $location->books_count ?? 0;
                                $capacity = $location->capacity ?? 0;
                                $percentage = $capacity > 0 ? ($booksCount / $capacity) * 100 : 0;
                            @endphp
                            <span class="badge bg-info">{{ $booksCount }}</span>
                            @if($capacity > 0)
                                <br><small class="text-muted">{{ number_format($percentage, 0) }}% full</small>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.lokasi.edit', $location) }}" 
                                   class="btn btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" 
                                        onclick="deleteLocation({{ $location->id }})" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <form id="delete-form-{{ $location->id }}" 
                                  action="{{ route('admin.lokasi.destroy', $location) }}" 
                                  method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No locations found.</p>
                            <a href="{{ route('admin.lokasi.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add First Location
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($locations->hasPages())
        <div class="mt-4">
            {{ $locations->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function deleteLocation(id) {
    if (confirm('Apakah Anda yakin ingin menghapus lokasi ini? Tindakan tidak dapat dibatalkan.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endsection
