@extends('layouts.member')

@section('title', 'Profil Saya - Perpustakaan Digital')

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="fas fa-user-circle me-2" style="color: var(--primary-color);"></i>
                    Profil Saya
                </h2>
                <p class="text-muted mb-0">Kelola informasi pribadi Anda</p>
            </div>
            <a href="{{ route('member.profile.edit') }}" class="btn btn-primary-modern btn-modern">
                <i class="fas fa-edit me-2"></i>Ubah Profil
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Profile Card -->
    <div class="col-lg-4">
        <div class="card-modern">
            <div class="card-body text-center">
                <!-- Avatar -->
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center" 
                         style="width: 120px; height: 120px; background: linear-gradient(135deg, var(--primary-color), var(--primary-light)); border-radius: 50%; box-shadow: 0 8px 24px rgba(79, 70, 229, 0.3); position: relative;">
                        @if(auth()->user()->photo)
                            <img src="{{ asset('storage/' . auth()->user()->photo) }}" 
                                 style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                        @else
                            <i class="fas fa-user fa-4x text-white"></i>
                        @endif
                        
                        <!-- Status Badge -->
                        <div style="position: absolute; bottom: 5px; right: 5px;">
                            @if(auth()->user()->status == 'active')
                                <span style="width: 24px; height: 24px; background: #10B981; border: 3px solid white; border-radius: 50%; display: block;"></span>
                            @elseif(auth()->user()->status == 'pending')
                                <span style="width: 24px; height: 24px; background: #F59E0B; border: 3px solid white; border-radius: 50%; display: block;"></span>
                            @else
                                <span style="width: 24px; height: 24px; background: #EF4444; border: 3px solid white; border-radius: 50%; display: block;"></span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Name & Role -->
                <h4 class="fw-bold mb-1">{{ auth()->user()->name }}</h4>
                <p class="text-muted mb-3">Anggota Perpustakaan</p>
                
                <!-- Status Badge -->
                @if(auth()->user()->status == 'pending')
                    <span class="badge badge-modern bg-warning mb-3">
                        <i class="fas fa-clock me-1"></i>Menunggu Persetujuan
                    </span>
                @elseif(auth()->user()->status == 'active')
                    <span class="badge badge-modern bg-success mb-3">
                        <i class="fas fa-check-circle me-1"></i>Anggota Aktif
                    </span>
                @else
                    <span class="badge badge-modern bg-danger mb-3">
                        <i class="fas fa-times-circle me-1"></i>Tidak Aktif
                    </span>
                @endif
                
                <!-- Member Card -->
                @if(auth()->user()->member_card_number)
                    <div class="p-3 mb-3" style="background: linear-gradient(135deg, #1F2937, #374151); border-radius: 12px; color: white;">
                        <small class="d-block text-white-50 mb-1">ID Anggota</small>
                        <h5 class="mb-0 fw-bold">{{ auth()->user()->member_card_number }}</h5>
                    </div>
                @endif
                
                <!-- Quick Stats -->
                <div class="row g-2 mt-3">
                    <div class="col-6">
                        <div class="p-3" style="background: rgba(79, 70, 229, 0.1); border-radius: 12px;">
                            <h4 class="fw-bold mb-0 text-primary">{{ auth()->user()->borrowings()->where('status', 'approved')->count() }}</h4>
                            <small class="text-muted">Aktif</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3" style="background: rgba(16, 185, 129, 0.1); border-radius: 12px;">
                            <h4 class="fw-bold mb-0 text-success">{{ auth()->user()->borrowings()->where('status', 'returned')->count() }}</h4>
                            <small class="text-muted">Dikembalikan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Member Since Card -->
        <div class="card-modern mt-4">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #10B981, #34D399); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-calendar-check text-white fa-lg"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0 fw-bold">Anggota Sejak</h6>
                        <p class="text-muted small mb-0">{{ auth()->user()->created_at->format('F d, Y') }}</p>
                    </div>
                </div>
                <small class="text-muted">
                    You've been with us for <strong>{{ auth()->user()->created_at->diffForHumans(null, true) }}</strong>
                </small>
            </div>
        </div>
    </div>
    
    <!-- Information Tabs -->
    <div class="col-lg-8">
        <div class="card-modern">
            <div class="card-header-modern">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Pribadi</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <!-- Contact Information -->
                    <div class="col-12">
                        <h6 class="fw-bold mb-3 pb-2 border-bottom">
                            <i class="fas fa-address-card me-2 text-primary"></i>Detail Kontak
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="small text-muted mb-1">Alamat Email</label>
                                <div class="p-2 bg-light rounded d-flex align-items-center">
                                    <i class="fas fa-envelope text-primary me-2"></i>
                                    <strong>{{ auth()->user()->email }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted mb-1">Nomor Telepon</label>
                                <div class="p-2 bg-light rounded d-flex align-items-center">
                                    <i class="fas fa-phone text-success me-2"></i>
                                    <strong>{{ auth()->user()->phone ?: 'Tidak diberikan' }}</strong>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="small text-muted mb-1">Alamat</label>
                                <div class="p-2 bg-light rounded d-flex align-items-start">
                                    <i class="fas fa-map-marker-alt text-danger me-2 mt-1"></i>
                                    <strong>{{ auth()->user()->address ?: 'Tidak diberikan' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Information -->
                    @if(auth()->user()->institution || auth()->user()->student_id)
                        <div class="col-12">
                            <h6 class="fw-bold mb-3 pb-2 border-bottom">
                                <i class="fas fa-school me-2 text-primary"></i>Informasi Akademik
                            </h6>
                            <div class="row g-3">
                                @if(auth()->user()->institution)
                                    <div class="col-md-6">
                                        <label class="small text-muted mb-1">Institusi</label>
                                        <div class="p-2 bg-light rounded d-flex align-items-center">
                                            <i class="fas fa-university text-primary me-2"></i>
                                            <strong>{{ auth()->user()->institution }}</strong>
                                        </div>
                                    </div>
                                @endif
                                @if(auth()->user()->student_id)
                                    <div class="col-md-6">
                                        <label class="small text-muted mb-1">ID Mahasiswa/Siswa</label>
                                        <div class="p-2 bg-light rounded d-flex align-items-center">
                                            <i class="fas fa-id-card text-success me-2"></i>
                                            <strong>{{ auth()->user()->student_id }}</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    <!-- Account Information -->
                    <div class="col-12">
                        <h6 class="fw-bold mb-3 pb-2 border-bottom">
                            <i class="fas fa-user-cog me-2 text-primary"></i>Detail Akun
                        </h6>
                        <div class="row g-3">
                            @if(auth()->user()->member_type)
                                <div class="col-md-4">
                                    <label class="small text-muted mb-1">Tipe Anggota</label>
                                    <div class="p-2 bg-light rounded text-center">
                                        <span class="badge badge-modern bg-primary">{{ ucfirst(auth()->user()->member_type) }}</span>
                                    </div>
                                </div>
                            @endif
                            @if(auth()->user()->max_loan)
                                <div class="col-md-4">
                                    <label class="small text-muted mb-1">Batas Pinjam</label>
                                    <div class="p-2 bg-light rounded text-center">
                                        <strong>{{ auth()->user()->max_loan }} buku</strong>
                                    </div>
                                </div>
                            @endif
                            @if(auth()->user()->loan_period_days)
                                <div class="col-md-4">
                                    <label class="small text-muted mb-1">Periode Pinjam</label>
                                    <div class="p-2 bg-light rounded text-center">
                                        <strong>{{ auth()->user()->loan_period_days }} hari</strong>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Membership Status -->
                    @if(auth()->user()->member_expired_at)
                        <div class="col-12">
                            <div class="alert {{ auth()->user()->member_expired_at > now() ? 'alert-success' : 'alert-danger' }} mb-0" style="border-radius: 12px;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-{{ auth()->user()->member_expired_at > now() ? 'check-circle' : 'exclamation-triangle' }} fa-2x me-3"></i>
                                    <div>
                                        <h6 class="mb-1 fw-bold">Kadaluarsa Keanggotaan</h6>
                                        <p class="mb-0">
                                            @if(auth()->user()->member_expired_at > now())
                                                Keanggotaan Anda valid sampai <strong>{{ auth()->user()->member_expired_at->format('d F Y') }}</strong>
                                                ({{ auth()->user()->member_expired_at->diffForHumans() }})
                                            @else
                                                Keanggotaan Anda telah kadaluarsa pada <strong>{{ auth()->user()->member_expired_at->format('d F Y') }}</strong>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Activity Summary -->
        <div class="card-modern mt-4">
            <div class="card-header-modern">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Ringkasan Aktivitas</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="text-center p-3" style="background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(129, 140, 248, 0.1)); border-radius: 12px;">
                            <i class="fas fa-book fa-2x mb-2 text-primary"></i>
                            <h4 class="fw-bold mb-0">{{ auth()->user()->borrowings()->count() }}</h4>
                            <small class="text-muted">Total Dipinjam</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(52, 211, 153, 0.1)); border-radius: 12px;">
                            <i class="fas fa-undo fa-2x mb-2 text-success"></i>
                            <h4 class="fw-bold mb-0">{{ auth()->user()->borrowings()->where('status', 'returned')->count() }}</h4>
                            <small class="text-muted">Dikembalikan</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(251, 191, 36, 0.1)); border-radius: 12px;">
                            <i class="fas fa-clock fa-2x mb-2 text-warning"></i>
                            <h4 class="fw-bold mb-0">{{ auth()->user()->borrowings()->where('status', 'pending')->count() }}</h4>
                            <small class="text-muted">Menunggu</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
