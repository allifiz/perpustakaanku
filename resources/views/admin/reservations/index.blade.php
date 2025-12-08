@extends('layouts.admin')

@section('title', 'Manajemen Reservasi')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="fas fa-bookmark me-2 text-primary"></i>Manajemen Reservasi
            </h2>
            <p class="text-muted mb-0">Kelola reservasi buku dari anggota</p>
        </div>
        <div>
            <a href="{{ route('admin.reservasi.history') }}" class="btn btn-outline-primary">
                <i class="fas fa-history me-2"></i>Riwayat Reservasi
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-warning">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Pending</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['pending'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Siap Diambil</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['ready'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-danger bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-danger">
                                <i class="fas fa-times-circle"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Kadaluarsa</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['expired'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="stat-icon bg-primary">
                                <i class="fas fa-book-reader"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Dipinjam</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['borrowed'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reservations Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-list me-2"></i>Daftar Reservasi Aktif
            </h5>
        </div>
        <div class="card-body p-0">
            @if($reservations->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4">ID</th>
                            <th>Anggota</th>
                            <th>Buku</th>
                            <th>Tanggal Reservasi</th>
                            <th>Kadaluarsa</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $reservation)
                        <tr>
                            <td class="px-4">
                                <span class="badge bg-secondary">#{{ $reservation->id }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary text-white me-2">
                                        {{ substr($reservation->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $reservation->user->name }}</div>
                                        <small class="text-muted">{{ $reservation->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ Str::limit($reservation->book->title, 40) }}</div>
                                <small class="text-muted">{{ $reservation->book->author }}</small>
                            </td>
                            <td>
                                <i class="fas fa-calendar me-1 text-muted"></i>
                                {{ $reservation->reserved_date->format('d/m/Y') }}
                                <br>
                                <small class="text-muted">{{ $reservation->reserved_date->diffForHumans() }}</small>
                            </td>
                            <td>
                                @if($reservation->expired_date)
                                    <i class="fas fa-hourglass-end me-1 text-muted"></i>
                                    {{ $reservation->expired_date->format('d/m/Y') }}
                                    @if($reservation->expired_date->isPast())
                                        <br><small class="text-danger fw-semibold">Sudah lewat!</small>
                                    @else
                                        <br><small class="text-muted">{{ $reservation->expired_date->diffForHumans() }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @switch($reservation->status)
                                    @case('pending')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock me-1"></i>Pending
                                        </span>
                                        @break
                                    @case('ready')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Siap Diambil
                                        </span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($reservation->status) }}</span>
                                @endswitch
                            </td>
                            <td>
                                <div class="d-flex gap-1 justify-content-center">
                                    @if($reservation->status === 'pending')
                                        <form action="{{ route('admin.reservasi.approve', $reservation->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="Setujui Reservasi">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if($reservation->status === 'ready')
                                        <button type="button" class="btn btn-sm btn-primary btn-convert-modal" 
                                                data-reservation-id="{{ $reservation->id }}"
                                                title="Konversi ke Peminjaman">
                                            <i class="fas fa-exchange-alt"></i>
                                        </button>
                                    @endif

                                    <button type="button" class="btn btn-sm btn-danger btn-cancel-modal" 
                                            data-reservation-id="{{ $reservation->id }}"
                                            title="Batalkan">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-3">
                {{ $reservations->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">Tidak ada reservasi aktif saat ini</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Convert Modal (Single) -->
<div class="modal fade" id="convertModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="convertForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Konversi ke Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">Konversi reservasi ini menjadi peminjaman?</p>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Jatuh Tempo *</label>
                        <input type="date" name="due_date" class="form-control" 
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               value="{{ date('Y-m-d', strtotime('+7 days')) }}" required>
                    </div>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Stok buku akan berkurang otomatis
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Konfirmasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel Modal (Single) -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="cancelForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Batalkan Reservasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Alasan Pembatalan *</label>
                        <textarea name="cancel_reason" class="form-control" rows="3" required placeholder="Masukkan alasan pembatalan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-danger">Batalkan Reservasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const convertModal = new bootstrap.Modal(document.getElementById('convertModal'));
    const cancelModal = new bootstrap.Modal(document.getElementById('cancelModal'));
    const convertForm = document.getElementById('convertForm');
    const cancelForm = document.getElementById('cancelForm');
    
    // Handle convert button clicks
    document.querySelectorAll('.btn-convert-modal').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const reservationId = this.dataset.reservationId;
            convertForm.action = `/admin/reservasi/${reservationId}/convert`;
            convertModal.show();
        });
    });
    
    // Handle cancel button clicks
    document.querySelectorAll('.btn-cancel-modal').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const reservationId = this.dataset.reservationId;
            cancelForm.action = `/admin/reservasi/${reservationId}/cancel`;
            // Reset textarea
            cancelForm.querySelector('textarea').value = '';
            cancelModal.show();
        });
    });
});
</script>

<style>
.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
}

.avatar-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
}
</style>
@endsection
