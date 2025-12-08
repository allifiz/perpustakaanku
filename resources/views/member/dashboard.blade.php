@extends('layouts.member')

@section('title', 'Beranda - Perpustakaan Digital')

@section('content')
<!-- Hero Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="welcome-hero" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-light)); color: white; padding: 3rem; border-radius: 20px; position: relative; overflow: hidden;">
            <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="position: absolute; bottom: -30px; left: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
            <div style="position: relative; z-index: 2;">
                <h1 class="display-4 fw-bold mb-3">
                    <i class="fas fa-hand-wave me-3"></i>
                    Selamat datang kembali, {{ auth()->user()->name }}!
                </h1>
                <p class="lead mb-4">Siap menjelajahi dunia pengetahuan?</p>
                
                @if(!auth()->user()->isActive())
                    <div class="alert alert-warning d-inline-flex align-items-center" style="background: rgba(245, 158, 11, 0.2); border: 2px solid rgba(245, 158, 11, 0.5); backdrop-filter: blur(10px);">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <span>Akun Anda menunggu persetujuan admin</span>
                    </div>
                @else
                    <div class="d-flex gap-3">
                        <a href="{{ route('catalog.index') }}" class="btn btn-light btn-lg btn-modern">
                            <i class="fas fa-search me-2"></i>Jelajahi Buku
                        </a>
                        <a href="{{ route('member.peminjaman.index') }}" class="btn btn-outline-light btn-lg" style="border: 2px solid white;">
                            <i class="fas fa-bookmark me-2"></i>Buku Saya
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="stats-card-member position-relative">
            <i class="fas fa-book-open icon"></i>
            <div class="small text-white-50 mb-1">Sedang Dipinjam</div>
            <h3>{{ auth()->user()->borrowings()->where('status', 'approved')->count() }}</h3>
            <a href="{{ route('member.peminjaman.index') }}" class="btn btn-sm btn-light mt-2">
                Lihat Buku <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card-member success position-relative">
            <i class="fas fa-check-circle icon"></i>
            <div class="small text-white-50 mb-1">Total Dikembalikan</div>
            <h3>{{ auth()->user()->borrowings()->where('status', 'returned')->count() }}</h3>
            <a href="{{ route('member.peminjaman.history') }}" class="btn btn-sm btn-light mt-2">
                Lihat Riwayat <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card-member warning position-relative">
            <i class="fas fa-clock icon"></i>
            <div class="small text-white-50 mb-1">Menunggu Persetujuan</div>
            <h3>{{ auth()->user()->borrowings()->where('status', 'pending')->count() }}</h3>
            <div class="btn btn-sm btn-light mt-2 disabled">
                Menunggu... <i class="fas fa-hourglass-half ms-1"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="stats-card-member danger position-relative">
            <i class="fas fa-exclamation-triangle icon"></i>
            <div class="small text-white-50 mb-1">Buku Terlambat</div>
            <h3>{{ auth()->user()->borrowings()->whereDate('due_date', '<', now())->where('status', 'approved')->count() }}</h3>
            <div class="small mt-2">
                @if(auth()->user()->borrowings()->whereDate('due_date', '<', now())->where('status', 'approved')->count() > 0)
                    <i class="fas fa-info-circle me-1"></i>Harap segera dikembalikan!
                @else
                    <i class="fas fa-check me-1"></i>Bagus! Tidak ada yang terlambat
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="row g-4">
    <!-- Recent Borrowings -->
    <div class="col-lg-8">
        <div class="card-modern">
            <div class="card-header-modern">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-bookmark me-2"></i>Buku Aktif Saya</h5>
                    <a href="{{ route('member.peminjaman.index') }}" class="btn btn-sm btn-light">Lihat Semua</a>
                </div>
            </div>
            <div class="card-body p-0">
                @php
                    $activeBooks = auth()->user()->borrowings()
                        ->with('book')
                        ->where('status', 'approved')
                        ->latest()
                        ->limit(5)
                        ->get();
                @endphp
                
                @forelse($activeBooks as $borrowing)
                    <div class="p-4 border-bottom" style="transition: all 0.3s ease;" onmouseover="this.style.background='#F9FAFB'" onmouseout="this.style.background='white'">
                        <div class="d-flex gap-3">
                            <div style="width: 60px; height: 80px; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                @if($borrowing->book->cover_image)
                                    <img src="{{ asset('storage/' . $borrowing->book->cover_image) }}" 
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-book text-white fa-2x"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-bold">{{ $borrowing->book->title }}</h6>
                                <p class="text-muted small mb-2">by {{ $borrowing->book->author }}</p>
                                <div class="d-flex gap-3 align-items-center">
                                    <span class="badge badge-modern bg-primary">
                                        <i class="fas fa-calendar me-1"></i>
                                        Jatuh Tempo: {{ $borrowing->due_date->format('d M Y') }}
                                    </span>
                                    @if($borrowing->due_date < now())
                                        <span class="badge badge-modern bg-danger">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Terlambat
                                        </span>
                                    @else
                                        <span class="text-muted small">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $borrowing->due_date->diffForHumans() }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-5 text-center">
                        <i class="fas fa-book-open fa-4x text-muted mb-3" style="opacity: 0.3;"></i>
                        <h5 class="text-muted mb-2">Tidak Ada Peminjaman Aktif</h5>
                        <p class="text-muted">Mulai jelajahi koleksi kami!</p>
                        <a href="{{ route('catalog.index') }}" class="btn btn-primary-modern btn-modern">
                            <i class="fas fa-search me-2"></i>Jelajahi Buku
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Quick Actions & Profile Info -->
    <div class="col-lg-4">
        <!-- Profile Card -->
        <div class="card-modern mb-4">
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="d-inline-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--primary-color), var(--primary-light)); border-radius: 50%; box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3);">
                        <i class="fas fa-user fa-2x text-white"></i>
                    </div>
                </div>
                <h5 class="fw-bold mb-1">{{ auth()->user()->name }}</h5>
                <p class="text-muted small mb-3">{{ auth()->user()->email }}</p>
                
                @if(auth()->user()->member_card_number)
                    <div class="p-2 bg-light rounded mb-3">
                        <small class="text-muted d-block">ID Anggota</small>
                        <strong class="text-primary">{{ auth()->user()->member_card_number }}</strong>
                    </div>
                @endif
                
                <div class="d-grid gap-2">
                    <a href="{{ route('member.profile.show') }}" class="btn btn-primary-modern btn-modern">
                        <i class="fas fa-user-edit me-2"></i>Ubah Profil
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i>Statistik Saya</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <i class="fas fa-book text-primary me-2"></i>
                        <span>Total Dipinjam</span>
                    </div>
                    <strong class="text-primary">{{ auth()->user()->borrowings()->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <i class="fas fa-redo text-success me-2"></i>
                        <span>Dikembalikan</span>
                    </div>
                    <strong class="text-success">{{ auth()->user()->borrowings()->where('status', 'returned')->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <i class="fas fa-calendar text-info me-2"></i>
                        <span>Anggota Sejak</span>
                    </div>
                    <strong class="text-info">{{ auth()->user()->created_at->format('M Y') }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-star text-warning me-2"></i>
                        <span>Status</span>
                    </div>
                    @if(auth()->user()->status == 'active')
                        <span class="badge badge-modern bg-success">Aktif</span>
                    @elseif(auth()->user()->status == 'pending')
                        <span class="badge badge-modern bg-warning">Menunggu</span>
                    @else
                        <span class="badge badge-modern bg-danger">Tidak Aktif</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card-modern">
            <div class="card-header-modern">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Aktivitas Terkini</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-modern mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Buku</th>
                                <th>Aksi</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $recentActivity = auth()->user()->borrowings()
                                    ->with('book')
                                    ->latest()
                                    ->limit(5)
                                    ->get();
                            @endphp
                            
                            @forelse($recentActivity as $activity)
                                <tr>
                                    <td>
                                        <strong>{{ $activity->book->title }}</strong>
                                        <br><small class="text-muted">{{ $activity->book->author }}</small>
                                    </td>
                                    <td>
                                        @if($activity->status == 'returned')
                                            <i class="fas fa-undo text-success me-1"></i>Dikembalikan
                                        @else
                                            <i class="fas fa-download text-primary me-1"></i>Dipinjam
                                        @endif
                                    </td>
                                    <td>{{ $activity->created_at->format('d M Y') }}</td>
                                    <td>
                                        @if($activity->status == 'pending')
                                            <span class="badge badge-modern bg-warning">Menunggu</span>
                                        @elseif($activity->status == 'approved')
                                            <span class="badge badge-modern bg-success">Aktif</span>
                                        @elseif($activity->status == 'returned')
                                            <span class="badge badge-modern bg-info">Dikembalikan</span>
                                        @else
                                            <span class="badge badge-modern bg-danger">Terlambat</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3" style="opacity: 0.3;"></i>
                                        <p class="text-muted">Belum ada aktivitas</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Add smooth animations
    document.addEventListener('DOMContentLoaded', function() {
        // Animate stats cards on load
        const statsCards = document.querySelectorAll('.stats-card-member');
        statsCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endpush
@endsection
