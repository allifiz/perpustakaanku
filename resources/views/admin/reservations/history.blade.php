@extends('layouts.admin')

@section('title', 'Riwayat Reservasi')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="fas fa-history me-2 text-primary"></i>Riwayat Reservasi
            </h2>
            <p class="text-muted mb-0">Daftar reservasi yang sudah selesai, dibatalkan, atau kadaluarsa</p>
        </div>
        <div>
            <a href="{{ route('admin.reservasi.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- History Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">
                <i class="fas fa-list me-2"></i>Daftar Riwayat
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
                            <th>Status</th>
                            <th>Catatan</th>
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
                                <div class="fw-semibold">{{ $reservation->user->name }}</div>
                                <small class="text-muted">{{ $reservation->user->email }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ Str::limit($reservation->book->title, 40) }}</div>
                                <small class="text-muted">{{ $reservation->book->author }}</small>
                            </td>
                            <td>
                                <i class="fas fa-calendar me-1 text-muted"></i>
                                {{ $reservation->reserved_date->format('d/m/Y') }}
                            </td>
                            <td>
                                @switch($reservation->status)
                                    @case('borrowed')
                                        <span class="badge bg-success">
                                            <i class="fas fa-book-reader me-1"></i>Dipinjam
                                        </span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-ban me-1"></i>Dibatalkan
                                        </span>
                                        @break
                                    @case('expired')
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-hourglass-end me-1"></i>Kadaluarsa
                                        </span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($reservation->status) }}</span>
                                @endswitch
                            </td>
                            <td>
                                @if($reservation->notes)
                                    <small class="text-muted">{{ Str::limit($reservation->notes, 50) }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.reservasi.destroy', $reservation->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
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
                <p class="text-muted">Belum ada riwayat reservasi</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
