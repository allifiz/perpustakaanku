@extends('layouts.member')

@section('title', $book->title . ' - Detail Buku')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb bg-white rounded shadow-sm p-3">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none"><i class="fas fa-home me-1"></i>Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}" class="text-decoration-none">Katalog</a></li>
        <li class="breadcrumb-item active">{{ Str::limit($book->title, 50) }}</li>
    </ol>
</nav>

    <div class="row">
        <!-- Book Cover & Actions -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                <div class="card-body text-center p-4">
                    @if($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}" 
                             alt="{{ $book->title }}" 
                             class="img-fluid rounded shadow mb-3" 
                             style="max-height: 500px; object-fit: cover;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" 
                             style="height: 500px;">
                            <div class="text-center text-muted">
                                <i class="fas fa-book fa-5x mb-3"></i>
                                <p class="mb-0">Tidak Ada Sampul</p>
                            </div>
                        </div>
                    @endif

                    <!-- Availability Status -->
                    <div class="mb-3">
                        @if($book->status === 'available' && $book->available_copies > 0)
                            <span class="badge bg-success fs-6 py-2 px-3">
                                <i class="fas fa-check-circle me-2"></i>Tersedia
                            </span>
                            <p class="text-muted small mt-2 mb-0">{{ $book->available_copies }} dari {{ $book->total_copies }} salinan tersedia</p>
                        @else
                            <span class="badge bg-danger fs-6 py-2 px-3">
                                <i class="fas fa-times-circle me-2"></i>Tidak Tersedia
                            </span>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    @auth
                        @if($book->status === 'available' && $book->available_copies > 0)
                            @if($userReservation)
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Anda sudah mereservasi buku ini
                                </div>
                            @else
                                <button type="button" class="btn btn-primary btn-lg w-100" data-bs-toggle="modal" data-bs-target="#reserveModal">
                                    <i class="fas fa-bookmark me-2"></i>Reservasi Buku
                                </button>
                            @endif
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg w-100">
                            <i class="fas fa-sign-in-alt me-2"></i>Login untuk Meminjam
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Book Details -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h1 class="h2 fw-bold mb-3">{{ $book->title }}</h1>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <i class="fas fa-user text-primary me-2"></i>
                                <strong>Penulis:</strong> {{ $book->author }}
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-building text-primary me-2"></i>
                                <strong>Penerbit:</strong> {{ $book->publisherModel->name ?? $book->publisher }}
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-calendar text-primary me-2"></i>
                                <strong>Tahun Terbit:</strong> {{ $book->publication_year }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <i class="fas fa-barcode text-primary me-2"></i>
                                <strong>ISBN:</strong> {{ $book->isbn }}
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-tag text-primary me-2"></i>
                                <strong>Kategori:</strong> {{ $book->categoryModel->name ?? $book->category }}
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-language text-primary me-2"></i>
                                <strong>Bahasa:</strong> {{ $book->language }}
                            </p>
                        </div>
                    </div>

                    @if($book->description)
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Deskripsi</h5>
                        <p class="text-muted">{{ $book->description }}</p>
                    </div>
                    @endif

                    <!-- Additional Details -->
                    <div class="border-top pt-4">
                        <h5 class="fw-bold mb-3">Informasi Tambahan</h5>
                        <div class="row">
                            @if($book->edition)
                            <div class="col-md-6 mb-3">
                                <strong>Edisi:</strong> {{ $book->edition }}
                            </div>
                            @endif
                            @if($book->pages)
                            <div class="col-md-6 mb-3">
                                <strong>Halaman:</strong> {{ $book->pages }}
                            </div>
                            @endif
                            @if($book->series)
                            <div class="col-md-6 mb-3">
                                <strong>Seri:</strong> {{ $book->series }}
                            </div>
                            @endif
                            @if($book->ddc)
                            <div class="col-md-6 mb-3">
                                <strong>Nomor DDC:</strong> {{ $book->ddc }}
                            </div>
                            @endif
                            @if($book->call_number)
                            <div class="col-md-6 mb-3">
                                <strong>Nomor Panggil:</strong> {{ $book->call_number }}
                            </div>
                            @endif
                            @if($book->location)
                            <div class="col-md-6 mb-3">
                                <strong>Lokasi:</strong> {{ $book->location->name ?? '-' }}
                            </div>
                            @endif
                            @if($book->shelf_position)
                            <div class="col-md-6 mb-3">
                                <strong>Posisi Rak:</strong> {{ $book->shelf_position }}
                            </div>
                            @endif
                            @if($book->collection_type)
                            <div class="col-md-6 mb-3">
                                <strong>Tipe Koleksi:</strong> 
                                @switch($book->collection_type)
                                    @case('reference')
                                        Referensi
                                        @break
                                    @case('circulation')
                                        Sirkulasi
                                        @break
                                    @case('reserve')
                                        Cadangan
                                        @break
                                    @case('digital')
                                        Digital
                                        @break
                                    @default
                                        {{ ucfirst($book->collection_type) }}
                                @endswitch
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($book->subjects)
                    <div class="border-top pt-4 mt-4">
                        <h5 class="fw-bold mb-3">Subjek</h5>
                        <div>
                            @foreach(explode(',', $book->subjects) as $subject)
                                <span class="badge bg-light text-dark me-2 mb-2">{{ trim($subject) }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Related Books -->
    @if($relatedBooks->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="fw-bold mb-4">Buku Terkait</h3>
        </div>
        @foreach($relatedBooks as $related)
        <div class="col-md-2 mb-4">
            <div class="card h-100 border-0 shadow-sm hover-lift">
                <a href="{{ route('catalog.show', $related->id) }}" class="text-decoration-none">
                    @if($related->cover_image)
                        <img src="{{ asset('storage/' . $related->cover_image) }}" 
                             class="card-img-top" 
                             alt="{{ $related->title }}"
                             style="height: 250px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                            <i class="fas fa-book fa-3x text-muted"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h6 class="card-title text-dark mb-1" style="font-size: 0.9rem;">{{ Str::limit($related->title, 40) }}</h6>
                        <p class="card-text text-muted small mb-0">{{ $related->author }}</p>
                    </div>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<!-- Reservation Modal -->
@auth
<div class="modal fade" id="reserveModal" tabindex="-1" aria-labelledby="reserveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('catalog.reserve', $book->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="reserveModalLabel">
                        <i class="fas fa-bookmark me-2"></i>Reservasi Buku
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Buku yang Direservasi</label>
                        <div class="p-3 bg-light rounded">
                            <div class="fw-bold text-dark">{{ $book->title }}</div>
                            <small class="text-muted">{{ $book->author }}</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="reserved_date" class="form-label fw-semibold">
                            <i class="fas fa-calendar-plus me-1 text-primary"></i>Tanggal Mulai Peminjaman *
                        </label>
                        <input type="date" 
                               class="form-control @error('reserved_date') is-invalid @enderror" 
                               id="reserved_date" 
                               name="reserved_date" 
                               min="{{ date('Y-m-d') }}"
                               value="{{ old('reserved_date', date('Y-m-d')) }}"
                               required>
                        @error('reserved_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Pilih kapan Anda ingin mulai meminjam buku ini</small>
                    </div>

                    <div class="mb-3">
                        <label for="expired_date" class="form-label fw-semibold">
                            <i class="fas fa-calendar-check me-1 text-primary"></i>Tanggal Selesai Peminjaman *
                        </label>
                        <input type="date" 
                               class="form-control @error('expired_date') is-invalid @enderror" 
                               id="expired_date" 
                               name="expired_date" 
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               value="{{ old('expired_date', date('Y-m-d', strtotime('+7 days'))) }}"
                               required>
                        @error('expired_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Pilih kapan Anda akan mengembalikan buku ini</small>
                    </div>

                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Catatan:</strong> Reservasi Anda akan menunggu persetujuan dari admin. Setelah disetujui, silakan ambil buku sesuai jadwal yang Anda tentukan.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check me-2"></i>Konfirmasi Reservasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endauth

<script>
// Update minimum date for expired_date when reserved_date changes
document.addEventListener('DOMContentLoaded', function() {
    const reservedDateInput = document.getElementById('reserved_date');
    const expiredDateInput = document.getElementById('expired_date');
    
    if (reservedDateInput && expiredDateInput) {
        reservedDateInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            selectedDate.setDate(selectedDate.getDate() + 1);
            const minExpiredDate = selectedDate.toISOString().split('T')[0];
            expiredDateInput.min = minExpiredDate;
            
            // If current expired_date is before the new minimum, update it
            if (expiredDateInput.value && expiredDateInput.value < minExpiredDate) {
                const recommendedDate = new Date(selectedDate);
                recommendedDate.setDate(recommendedDate.getDate() + 6); // +7 days from reserved_date
                expiredDateInput.value = recommendedDate.toISOString().split('T')[0];
            }
        });
    }
});

// Auto-show modal if there are validation errors
@if($errors->any() && old('reserved_date'))
    document.addEventListener('DOMContentLoaded', function() {
        const modal = new bootstrap.Modal(document.getElementById('reserveModal'));
        modal.show();
    });
@endif
</script>

<style>
.hover-lift {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

.sticky-top {
    position: sticky;
}
</style>
@endsection
