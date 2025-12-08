
{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Dasbor Admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2>Ringkasan Dasbor</h2>
        <p class="text-muted">Selamat datang kembali, {{ Auth::user()->name }}!</p>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="stats-card bg-primary text-white">
            <div class="icon-wrapper">
                <i class="fas fa-book"></i>
            </div>
            <div class="card-title-light">Total Buku</div>
            <h3>{{ $totalBooks }}</h3>
            <small>{{ $availableBooks }} tersedia</small>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="stats-card bg-success text-white">
            <div class="icon-wrapper">
                <i class="fas fa-users"></i>
            </div>
            <div class="card-title-light">Total Anggota</div>
            <h3>{{ $totalMembers }}</h3>
            <small>Pengguna terdaftar</small>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="stats-card bg-warning text-white">
            <div class="icon-wrapper">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <div class="card-title-light">Pinjaman Aktif</div>
            <h3>{{ $activeLoans }}</h3>
            <small>Sedang dipinjam</small>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="stats-card bg-danger text-white">
            <div class="icon-wrapper">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="card-title-light">Terlambat</div>
            <h3>{{ $overdueLoans }}</h3>
            <small>Perlu perhatian</small>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Borrowings -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-history"></i> Peminjaman Terbaru</h5>
                <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Anggota</th>
                                <th>Buku</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBorrowings as $borrowing)
                            <tr>
                                <td>{{ $borrowing->user->name }}</td>
                                <td>{{ Str::limit($borrowing->book->title, 30) }}</td>
                                <td>{{ $borrowing->borrow_date->format('d M Y') }}</td>
                                <td>
                                    @if($borrowing->status == 'pending')
                                        <span class="badge bg-warning">Menunggu</span>
                                    @elseif($borrowing->status == 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @else
                                        <span class="badge bg-info">Dikembalikan</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Tidak ada peminjaman terbaru</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Books -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-star"></i> Buku Populer</h5>
                <a href="{{ route('admin.buku.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th class="text-center">Kali Dipinjam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($popularBooks as $book)
                            <tr>
                                <td>{{ Str::limit($book->title, 30) }}</td>
                                <td>{{ Str::limit($book->author, 20) }}</td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $book->borrowings_count }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Belum ada data peminjaman</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Active Members -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-user-check"></i> Anggota Paling Aktif</h5>
                <a href="{{ route('admin.anggota.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th class="text-center">Buku Dipinjam</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeMembers as $member)
                            <tr>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->email }}</td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $member->borrowings_count }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Belum ada anggota aktif</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Statistik Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="stat-box">
                            <i class="fas fa-tags fa-2x text-primary mb-2"></i>
                            <h4>{{ $totalCategories }}</h4>
                            <small class="text-muted">Kategori</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="stat-box">
                            <i class="fas fa-building fa-2x text-success mb-2"></i>
                            <h4>{{ \App\Models\Publisher::count() }}</h4>
                            <small class="text-muted">Penerbit</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="stat-box">
                            <i class="fas fa-map-marker-alt fa-2x text-warning mb-2"></i>
                            <h4>{{ \App\Models\Location::count() }}</h4>
                            <small class="text-muted">Lokasi</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="stat-box">
                            <i class="fas fa-bookmark fa-2x text-info mb-2"></i>
                            <h4>{{ \App\Models\Reservation::whereIn('status', ['pending', 'ready'])->count() }}</h4>
                            <small class="text-muted">Reservasi Aktif</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-box {
    padding: 15px;
    border-radius: 8px;
    background: #f8f9fa;
}
.stat-box:hover {
    background: #e9ecef;
    transition: all 0.3s;
}
</style>

@endsection