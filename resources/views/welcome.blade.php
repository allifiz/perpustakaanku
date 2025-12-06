{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/welcome.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Modern Navbar -->
    <nav class="modern-navbar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <i class="fas fa-book me-2"></i>Perpustakaan umum garut
                </a>
                
                <div class="d-flex align-items-center">
                    <ul class="navbar-nav flex-row me-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                                <i class="fas fa-home me-1"></i>Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('catalog.*') ? 'active' : '' }}" href="{{ route('catalog.index') }}">
                                <i class="fas fa-book-open me-1"></i>Catalog
                            </a>
                        </li>
                        @auth
                            @if(auth()->user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                    </a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('member.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                    </a>
                                </li>
                            @endif
                        @endauth
                    </ul>
                    
                    <div class="auth-buttons">
                        @auth
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-login">
                                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-login">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-register">
                                <i class="fas fa-user-plus me-1"></i>Register
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Animated Gradient Hero Section -->
    <section class="animated-hero">
        <div class="gradient-bg"></div>
        
        <!-- Floating Elements -->
        <div class="floating-element floating-1"></div>
        <div class="floating-element floating-2"></div>
        <div class="floating-element floating-3"></div>
        
        <div class="container">
            <div class="hero-content">
                <h1>Sistem informasi Perpustakaan umum garut</h1>
                <p>Sistem manajemen perpustakaan modern yang memudahkan Anda meminjam dan mengelola buku secara efisien dengan pengalaman terbaik.</p>
                <div class="hero-buttons">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="hero-btn btn-hero-primary">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard Admin
                            </a>
                        @else
                            <a href="{{ route('member.dashboard') }}" class="hero-btn btn-hero-primary">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard Member
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="hero-btn btn-hero-primary">
                            <i class="fas fa-sign-in-alt me-2"></i>Masuk Sekarang
                        </a>
                        <a href="{{ route('register') }}" class="hero-btn btn-hero-outline">
                            <i class="fas fa-user-plus me-2"></i>Daftar Gratis
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    @php
        $totalBooks = \App\Models\Book::count();
        $totalMembers = \App\Models\User::where('role', 'member')->count();
        $activeBorrowings = \App\Models\Borrowing::where('status', 'approved')->count();
        $returnedBooks = \App\Models\Borrowing::where('status', 'returned')->count();
        $categories = \App\Models\Book::select('category')->distinct()->count();
    @endphp

    <section class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="fw-bold">Statistik Perpustakaan</h2>
                    <p class="text-muted">Data terkini dari sistem perpustakaan kami</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="stats-card bg-primary text-white">
                        <div class="icon-wrapper">
                            <i class="fas fa-book"></i>
                        </div>
                        <h3>{{ number_format($totalBooks) }}+</h3>
                        <div class="card-title">Buku Tersedia</div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="stats-card bg-success text-white">
                        <div class="icon-wrapper">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>{{ number_format($totalMembers) }}+</h3>
                        <div class="card-title">Anggota Aktif</div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="stats-card bg-info text-white">
                        <div class="icon-wrapper">
                            <i class="fas fa-tags"></i>
                        </div>
                        <h3>{{ $categories }}</h3>
                        <div class="card-title">Kategori Buku</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="stats-card bg-warning text-white">
                        <div class="icon-wrapper">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <h3>{{ number_format($activeBorrowings) }}+</h3>
                        <div class="card-title">Buku Dipinjam</div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="stats-card bg-secondary text-white">
                        <div class="icon-wrapper">
                            <i class="fas fa-history"></i>
                        </div>
                        <h3>{{ number_format($returnedBooks) }}+</h3>
                        <div class="card-title">Buku Dikembalikan</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="fw-bold">Fitur Unggulan Kami</h2>
                    <p class="text-muted">Pengalaman perpustakaan digital yang modern dan efisien</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-book"></i>
                        </div>
                        <h5 class="card-title fw-bold">Koleksi Lengkap</h5>
                        <p class="card-text text-muted">Akses ribuan buku dari berbagai kategori dan genre yang terus diperbarui.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon bg-success bg-opacity-10 text-success">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h5 class="card-title fw-bold">Akses Mudah</h5>
                        <p class="card-text text-muted">Pinjam dan kelola buku kapan saja dan di mana saja melalui sistem online.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5 class="card-title fw-bold">Keamanan Terjamin</h5>
                        <p class="card-text text-muted">Data Anda aman dengan sistem keamanan yang terpercaya dan terenkripsi.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Recent Books Section -->
    @php
        $recentBooks = \App\Models\Book::where('available_copies', '>', 0)
            ->where('status', 'available')
            ->latest()
            ->limit(6)
            ->get();
    @endphp

    @if($recentBooks->count() > 0)
    <section class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="fw-bold">Buku Terbaru</h2>
                    <p class="text-muted">Koleksi buku terbaru yang tersedia untuk dipinjam</p>
                </div>
            </div>
            <div class="row">
                @foreach($recentBooks as $book)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="book-card">
                        <div class="book-cover">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div class="book-info">
                            <h5>{{ Str::limit($book->title, 40) }}</h5>
                            <div class="book-meta">
                                <div><i class="fas fa-user me-1"></i> {{ Str::limit($book->author, 25) }}</div>
                                <div><i class="fas fa-tag me-1"></i> {{ $book->category }}</div>
                                <div><i class="fas fa-calendar me-1"></i> {{ $book->publication_year }}</div>
                            </div>
                            <div class="available-copies">
                                <i class="fas fa-check-circle me-1"></i> {{ $book->available_copies }} tersedia
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-4">
                @auth
                    @if(!auth()->user()->isAdmin())
                        <a href="{{ route('member.borrowings.create') }}" class="btn btn-primary" style="border-radius: 30px; padding: 12px 30px; font-weight: 600;">
                            <i class="fas fa-plus me-2"></i>Lihat Semua Buku
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary" style="border-radius: 30px; padding: 12px 30px; font-weight: 600;">
                        <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                    </a>
                @endauth
            </div>
        </div>
    </section>
    @endif

    <!-- Categories Section -->
    @php
        $categoriesList = \App\Models\Book::select('category')
            ->distinct()
            ->limit(8)
            ->get();
    @endphp

    @if($categoriesList->count() > 0)
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="fw-bold">Kategori Populer</h2>
                    <p class="text-muted">Jelajahi berbagai kategori buku yang tersedia</p>
                </div>
            </div>
            <div class="row">
                @foreach($categoriesList as $category)
                    @php
                        $bookCount = \App\Models\Book::where('category', $category->category)->count();
                    @endphp
                    <div class="col-lg-3 col-md-4 col-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body text-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-folder"></i>
                                </div>
                                <h6 class="card-title fw-bold">{{ $category->category }}</h6>
                                <p class="text-muted small mb-0">{{ $bookCount }} buku</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Footer -->
    <footer class="bg-dark text-white">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="footer-heading">Perpustakaan Digital</h5>
                    <p>Sistem manajemen perpustakaan modern untuk kemudahan akses informasi dan pengelolaan koleksi buku secara efisien.</p>
                    <div class="mt-3">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5 class="footer-heading">Statistik Cepat</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-book me-2"></i> {{ number_format($totalBooks) }}+ Buku</li>
                        <li class="mb-2"><i class="fas fa-users me-2"></i> {{ number_format($totalMembers) }}+ Anggota</li>
                        <li class="mb-2"><i class="fas fa-tags me-2"></i> {{ $categories }} Kategori</li>
                        <li class="mb-2"><i class="fas fa-exchange-alt me-2"></i> {{ number_format($activeBorrowings) }}+ Dipinjam</li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5 class="footer-heading">Kontak Kami</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> Jl. Perpustakaan No. 123</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> (021) 123-4567</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> info@perpustakaandigital.com</li>
                        <li class="mb-2"><i class="fas fa-clock me-2"></i> Senin - Jumat: 08:00 - 17:00</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0">&copy; {{ date('Y') }} Perpustakaan Digital. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>