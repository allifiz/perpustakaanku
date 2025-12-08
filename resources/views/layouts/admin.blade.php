{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Perpustakaan Digital')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <a href="{{ route('admin.dasbor') }}">
                <i class="fas fa-book me-2"></i>
                <span>Perpustakaan Admin</span>
            </a>
        </div>
        <ul class="nav-links">
            <li>
                <a href="{{ route('admin.dasbor') }}" class="{{ request()->routeIs('admin.dasbor') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Dasbor</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.anggota.index') }}" class="{{ request()->routeIs('admin.anggota.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Anggota</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.buku.index') }}" class="{{ request()->routeIs('admin.buku.*') ? 'active' : '' }}">
                    <i class="fas fa-book"></i>
                    <span>Buku</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.kategori.index') }}" class="{{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i>
                    <span>Kategori</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.penerbit.index') }}" class="{{ request()->routeIs('admin.penerbit.*') ? 'active' : '' }}">
                    <i class="fas fa-building"></i>
                    <span>Penerbit</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.lokasi.index') }}" class="{{ request()->routeIs('admin.lokasi.*') ? 'active' : '' }}">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Lokasi</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.peminjaman.index') }}" class="{{ request()->routeIs('admin.peminjaman.index') ? 'active' : '' }}">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Peminjaman</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.sirkulasi.index') }}" class="{{ request()->routeIs('admin.sirkulasi.*') ? 'active' : '' }}">
                    <i class="fas fa-bolt"></i>
                    <span>Sirkulasi Kilat</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.reservasi.index') }}" class="{{ request()->routeIs('admin.reservasi.*') ? 'active' : '' }}">
                    <i class="fas fa-bookmark"></i>
                    <span>Reservasi</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.peminjaman.history') }}" class="{{ request()->routeIs('admin.peminjaman.history') ? 'active' : '' }}">
                    <i class="fas fa-history"></i>
                    <span>Riwayat</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <h1>@yield('title', 'Dasbor')</h1>
            <div class="user-dropdown">
                <div class="dropdown">
                    <button class="dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-2"></i>
                        {{ Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('admin.dasbor') }}">Dasbor</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i>Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- QRCode Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize QRCodes on load
        document.addEventListener("DOMContentLoaded", function() {
            var qrcodes = document.querySelectorAll('.qrcode');
            qrcodes.forEach(function(el) {
                var value = el.getAttribute('data-value');
                if (value) {
                    new QRCode(el, {
                        text: value,
                        width: el.getAttribute('data-width') || 64,
                        height: el.getAttribute('data-height') || 64,
                        colorDark : "#000000",
                        colorLight : "#ffffff",
                        correctLevel : QRCode.CorrectLevel.H
                    });
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>