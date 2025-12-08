@extends('layouts.app')

@section('title', 'Katalog Perpustakaan - OPAC')

@section('content')
<div class="container py-5">
    <!-- Hero Search Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, var(--primary-color, #4F46E5), var(--primary-light, #818CF8)); border-radius: 20px; overflow: hidden;">
                <div class="card-body p-5 position-relative">
                    <!-- Decorative Elements -->
                    <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                    <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                    
                    <div class="position-relative">
                        <div class="text-center text-white mb-4">
                            <i class="fas fa-book-open fa-4x mb-3" style="opacity: 0.9;"></i>
                            <h1 class="fw-bold mb-2">Katalog Akses Publik Online</h1>
                            <p class="lead mb-0">Temukan ribuan buku di perpustakaan digital kami</p>
                        </div>
                        
                        <form method="GET" action="{{ route('catalog.index') }}">
                            <div class="row g-3 justify-content-center">
                                <div class="col-lg-8">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-search text-primary"></i>
                                        </span>
                                        <input type="text" 
                                               name="q" 
                                               class="form-control border-start-0 bg-white" 
                                               style="border-left: none !important; box-shadow: none;"
                                               placeholder="Cari berdasarkan judul, penulis, ISBN, atau kata kunci..." 
                                               value="{{ request('q') }}">
                                        <button type="submit" class="btn btn-light fw-bold px-5" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px;">
                                            <i class="fas fa-search me-2"></i>Cari
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Advanced Search Toggle -->
                            <div class="text-center mt-3">
                                <a href="#advancedSearch" 
                                   data-bs-toggle="collapse" 
                                   class="text-white text-decoration-none fw-semibold">
                                    <i class="fas fa-sliders-h me-2"></i>Opsi Pencarian Lanjutan
                                    <i class="fas fa-chevron-down ms-2"></i>
                                </a>
                            </div>

                            <!-- Advanced Search Form -->
                            <div class="collapse mt-4" id="advancedSearch">
                                <div class="card bg-white bg-opacity-95" style="border-radius: 12px;">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-3"><i class="fas fa-filter me-2"></i>Filter Pencarian Anda</h6>
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label small fw-semibold">Kategori</label>
                                                <select name="category_id" class="form-select">
                                                    <option value="">Semua Kategori</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                            {{ $category->code }} - {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label small fw-semibold">Penerbit</label>
                                                <select name="publisher_id" class="form-select">
                                                    <option value="">Semua Penerbit</option>
                                                    @foreach($publishers as $publisher)
                                                        <option value="{{ $publisher->id }}" {{ request('publisher_id') == $publisher->id ? 'selected' : '' }}>
                                                            {{ $publisher->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label small fw-semibold">Bahasa</label>
                                                <select name="language" class="form-select">
                                                    <option value="">Semua Bahasa</option>
                                                    @foreach($languages as $lang)
                                                        <option value="{{ $lang }}" {{ request('language') == $lang ? 'selected' : '' }}>
                                                            {{ $lang }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label small fw-semibold">Tahun Dari</label>
                                                <input type="number" name="year_from" class="form-control" 
                                                       value="{{ request('year_from') }}" placeholder="1900">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label small fw-semibold">Tahun Sampai</label>
                                                <input type="number" name="year_to" class="form-control" 
                                                       value="{{ request('year_to') }}" placeholder="{{ date('Y') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h4 class="mb-0">
                <i class="fas fa-books me-2" style="color: var(--primary-color, #4F46E5);"></i>
                <span class="fw-bold">{{ $books->total() }}</span> 
                <span class="text-muted">{{ $books->total() == 1 ? 'Buku' : 'Buku' }} Ditemukan</span>
            </h4>
            @if(request('q'))
                <small class="text-muted">
                    Hasil pencarian untuk: <strong>"{{ request('q') }}"</strong>
                </small>
            @endif
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-end gap-2">
                <div class="btn-group" role="group">
                    <a href="{{ route('catalog.index', array_merge(request()->except('sort', 'order'), ['sort' => 'title', 'order' => 'asc'])) }}" 
                       class="btn btn-sm {{ request('sort') == 'title' ? 'btn-primary' : 'btn-outline-secondary' }}">
                        <i class="fas fa-sort-alpha-down me-1"></i>Judul
                    </a>
                    <a href="{{ route('catalog.index', array_merge(request()->except('sort', 'order'), ['sort' => 'publication_year', 'order' => 'desc'])) }}" 
                       class="btn btn-sm {{ request('sort') == 'publication_year' ? 'btn-primary' : 'btn-outline-secondary' }}">
                        <i class="fas fa-calendar me-1"></i>Terbaru
                    </a>
                    <a href="{{ route('catalog.index', array_merge(request()->except('sort', 'order'), ['sort' => 'author', 'order' => 'asc'])) }}" 
                       class="btn btn-sm {{ request('sort') == 'author' ? 'btn-primary' : 'btn-outline-secondary' }}">
                        <i class="fas fa-user me-1"></i>Penulis
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Books Grid -->
    <div class="row g-4">
        @forelse($books as $book)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card h-100 border-0 shadow-sm book-card" style="border-radius: 12px; overflow: hidden; transition: all 0.3s ease;">
                    <a href="{{ route('catalog.show', $book->id) }}" class="text-decoration-none">
                        <!-- Book Cover -->
                        <div class="position-relative" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            @if($book->cover_image)
                                <img src="{{ Storage::url($book->cover_image) }}" 
                                     class="card-img-top" 
                                     alt="{{ $book->title }}" 
                                     style="height: 280px; object-fit: cover; width: 100%;">
                            @else
                                <div class="d-flex align-items-center justify-content-center" style="height: 280px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <i class="fas fa-book fa-5x text-white opacity-50"></i>
                                </div>
                            @endif
                            
                            <!-- Availability Badge -->
                            <div class="position-absolute top-0 end-0 m-2">
                                @if($book->available_copies > 0)
                                    <span class="badge bg-success" style="border-radius: 20px; padding: 6px 12px; font-size: 11px;">
                                        <i class="fas fa-check-circle me-1"></i>{{ $book->available_copies }} Tersedia
                                    </span>
                                @else
                                    <span class="badge bg-danger" style="border-radius: 20px; padding: 6px 12px; font-size: 11px;">
                                        <i class="fas fa-times-circle me-1"></i>Dipinjam
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Book Info -->
                        <div class="card-body p-3">
                            <h6 class="card-title fw-bold mb-2 text-dark" style="height: 44px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;" title="{{ $book->title }}">
                                {{ $book->title }}
                            </h6>
                            
                            <div class="mb-2">
                                <small class="text-muted d-flex align-items-center mb-1">
                                    <i class="fas fa-user me-2" style="width: 14px;"></i>
                                    <span class="text-truncate">{{ $book->author }}</span>
                                </small>
                                <small class="text-muted d-flex align-items-center">
                                    <i class="fas fa-calendar me-2" style="width: 14px;"></i>
                                    <span>{{ $book->publication_year }}</span>
                                </small>
                            </div>
                            
                            <!-- Category Badge -->
                            <div class="mt-2">
                                @if($book->categoryModel)
                                    <span class="badge" style="background: rgba(79, 70, 229, 0.1); color: var(--primary-color, #4F46E5); border-radius: 6px; font-size: 10px; padding: 4px 8px;">
                                        {{ $book->categoryModel->code }}
                                    </span>
                                @endif
                                @if($book->collection_type)
                                    <span class="badge bg-secondary" style="border-radius: 6px; font-size: 10px; padding: 4px 8px;">
                                        {{ ucfirst($book->collection_type) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Footer -->
                        <div class="card-footer bg-transparent border-top p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <small class="text-muted">
                                    <i class="fas fa-bookmark me-1"></i>
                                    {{ $book->isbn ? 'ISBN: ' . substr($book->isbn, -6) : 'No ISBN' }}
                                </small>
                                <span class="text-primary small fw-semibold">
                                    Lihat Detail <i class="fas fa-arrow-right ms-1"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-search fa-5x text-muted opacity-50"></i>
                    </div>
                    <h4 class="fw-bold mb-2">Tidak Ada Buku Ditemukan</h4>
                    <p class="text-muted mb-4">Kami tidak dapat menemukan buku yang sesuai dengan kriteria pencarian Anda.</p>
                    <a href="{{ route('catalog.index') }}" class="btn btn-primary">
                        <i class="fas fa-redo me-2"></i>Hapus Filter & Jelajahi Semua
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($books->hasPages())
        <div class="row mt-5">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $books->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
:root {
    --primary-color: #4F46E5;
    --primary-light: #818CF8;
}

.book-card {
    cursor: pointer;
}

.book-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.15) !important;
}

.book-card:hover .card-footer .text-primary {
    text-decoration: underline;
}

.form-select:focus,
.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25);
}

/* Smooth animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.book-card {
    animation: fadeInUp 0.4s ease-out;
}

.book-card:nth-child(1) { animation-delay: 0.05s; }
.book-card:nth-child(2) { animation-delay: 0.1s; }
.book-card:nth-child(3) { animation-delay: 0.15s; }
.book-card:nth-child(4) { animation-delay: 0.2s; }
</style>
@endpush
@endsection
