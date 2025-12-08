{{-- resources/views/admin/books/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kelola Buku')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1><i class="fas fa-book"></i> Kelola Buku</h1>
            <a href="{{ route('admin.buku.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Buku Baru
            </a>
        </div>
        <hr>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.buku.index') }}">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan judul atau penulis" 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="category" class="form-control">
                                <option value="">All Categories</option>
                                @foreach(\App\Models\Book::select('category')->distinct()->get() as $book)
                                    <option value="{{ $book->category }}" {{ request('category') == $book->category ? 'selected' : '' }}>
                                        {{ $book->category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                                <th>Cover</th>
                                <th>QR Code</th>
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th>Kategori</th>
                                <th>Tersedia</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($books as $book)
                            <tr>
                                <td>
                                    @if($book->cover_image)
                                        <img src="{{ Storage::url($book->cover_image) }}" alt="Cover" width="50">
                                    @else
                                        <div class="bg-light" style="width: 50px; height: 70px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-book"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="qrcode" 
                                         data-value="{{ $book->barcode ?? $book->id }}"
                                         data-width="50"
                                         data-height="50"
                                         style="cursor: pointer;"
                                         onclick="showQrModal('{{ $book->barcode ?? $book->id }}', '{{ $book->title }}')">
                                    </div>
                                </td>
                                <td>{{ $book->title }}</td>
                                <td>{{ $book->author }}</td>
                                <td>{{ $book->category }}</td>
                                <td>{{ $book->available_copies }} / {{ $book->total_copies }}</td>
                                <td>
                                    @if($book->status == 'available')
                                        <span class="badge bg-success">Tersedia</span>
                                    @elseif($book->status == 'borrowed')
                                        <span class="badge bg-warning">Dipinjam</span>
                                    @else
                                        <span class="badge bg-secondary">Perawatan</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.buku.edit', $book) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.buku.destroy', $book) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Hapus buku ini? Tindakan tidak dapat dibatalkan.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada buku ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    {{ $books->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Zoom QR Code -->
<div class="modal fade" id="qrZoomModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-header">
                <h5 class="modal-title" id="qrModalTitle">QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="qrModalContent" class="d-flex justify-content-center my-3"></div>
                <p id="qrModalValue" class="fw-bold text-break"></p>
            </div>
        </div>
    </div>
</div>

<script>
function showQrModal(value, title) {
    document.getElementById('qrModalTitle').innerText = title || 'QR Code';
    document.getElementById('qrModalValue').innerText = value;
    
    var container = document.getElementById('qrModalContent');
    container.innerHTML = ''; // Clear previous
    
    new QRCode(container, {
        text: value,
        width: 250,
        height: 250,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H
    });
    
    var modal = new bootstrap.Modal(document.getElementById('qrZoomModal'));
    modal.show();
}
</script>
@endsection