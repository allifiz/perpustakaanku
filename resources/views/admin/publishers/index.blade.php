@extends('layouts.admin')

@section('title', 'Manage Publishers')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Publishers</h2>
        <p class="text-muted">Manage book publishers and imprints</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('admin.publishers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Publisher
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
                        <th>Name</th>
                        <th>Country</th>
                        <th>Contact</th>
                        <th width="80">Status</th>
                        <th width="100">Books</th>
                        <th width="150" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($publishers as $publisher)
                    <tr>
                        <td>
                            <strong>{{ $publisher->name }}</strong>
                            @if($publisher->website)
                                <br><small><a href="{{ $publisher->website }}" target="_blank" class="text-muted">
                                    <i class="fas fa-external-link-alt"></i> {{ Str::limit($publisher->website, 40) }}
                                </a></small>
                            @endif
                        </td>
                        <td>
                            <i class="fas fa-globe"></i> {{ $publisher->country }}
                        </td>
                        <td>
                            @if($publisher->email)
                                <small><i class="fas fa-envelope"></i> {{ $publisher->email }}</small><br>
                            @endif
                            @if($publisher->phone)
                                <small><i class="fas fa-phone"></i> {{ $publisher->phone }}</small>
                            @endif
                            @if(!$publisher->email && !$publisher->phone)
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($publisher->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $publisher->books_count ?? 0 }}</span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.publishers.edit', $publisher) }}" 
                                   class="btn btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" 
                                        onclick="deletePublisher({{ $publisher->id }})" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <form id="delete-form-{{ $publisher->id }}" 
                                  action="{{ route('admin.publishers.destroy', $publisher) }}" 
                                  method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-building fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No publishers found.</p>
                            <a href="{{ route('admin.publishers.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add First Publisher
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($publishers->hasPages())
        <div class="mt-4">
            {{ $publishers->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function deletePublisher(id) {
    if (confirm('Are you sure you want to delete this publisher? This cannot be undone.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endsection
