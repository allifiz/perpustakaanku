@extends('layouts.admin')

@section('title', 'Kelola Kategori')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Kategori Buku</h2>
        <p class="text-muted">Kelola kategori DDC (Dewey Decimal Classification)</p>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Kategori
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
                        <th width="80">Kode</th>
                        <th>Nama</th>
                        <th>Induk</th>
                        <th width="100">Urutan</th>
                        <th width="80">Status</th>
                        <th width="100">Buku</th>
                        <th width="150" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td><code>{{ $category->code }}</code></td>
                        <td>
                            <strong>{{ $category->name }}</strong>
                            @if($category->description)
                                <br><small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                            @endif
                        </td>
                        <td>
                            @if($category->parent)
                                <span class="badge bg-secondary">{{ $category->parent->name }}</span>
                            @else
                                <span class="text-muted">Root</span>
                            @endif
                        </td>
                        <td>{{ $category->order ?? '-' }}</td>
                        <td>
                            @if($category->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $category->books_count ?? 0 }}</span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.kategori.edit', $category) }}" 
                                   class="btn btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" 
                                        onclick="deleteCategory({{ $category->id }})" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <form id="delete-form-{{ $category->id }}" 
                                  action="{{ route('admin.kategori.destroy', $category) }}" 
                                  method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada kategori yang ditemukan.</p>
                            <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Kategori Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
        <div class="mt-4">
            {{ $categories->links() }}
        </div>
        @endif
    </div>
</div>

<script>
function deleteCategory(id) {
    if (confirm('Apakah Anda yakin ingin menghapus kategori ini? Tindakan tidak dapat dibatalkan.')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endsection
