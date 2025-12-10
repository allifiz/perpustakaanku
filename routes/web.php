<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\BorrowingController as AdminBorrowingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PublisherController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Member\BorrowingController as MemberBorrowingController;
use App\Http\Controllers\Member\ProfileController;
use App\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\DB;

// Rute Autentikasi
Route::get('/masuk', [LoginController::class, 'showLoginForm'])->name('login')->middleware(['guest', 'prevent-back-history']);
Route::post('/masuk', [LoginController::class, 'login']);
Route::post('/keluar', [LoginController::class, 'logout'])->name('logout');
Route::get('/daftar', [RegisterController::class, 'showRegistrationForm'])->name('register')->middleware(['guest', 'prevent-back-history']);
Route::post('/daftar', [RegisterController::class, 'register']);

// Katalog Publik (OPAC)
Route::get('/katalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/katalog/{book}', [CatalogController::class, 'show'])->name('catalog.show');
Route::post('/katalog/{book}/reservasi', [CatalogController::class, 'reserve'])->name('catalog.reserve')->middleware('auth');


// Rute Admin
// routes/web.php
Route::middleware(['auth', 'admin', 'prevent-back-history'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dasbor', function () {
        $totalBooks = \App\Models\Book::count();
        $totalMembers = \App\Models\User::where('role', 'member')->count();
        $activeLoans = \App\Models\Borrowing::whereIn('status', ['pending', 'approved'])->count();
        $overdueLoans = \App\Models\Borrowing::overdue()->count();
        $availableBooks = \App\Models\Book::where('available_copies', '>', 0)->count();
        $totalCategories = \App\Models\Category::count();
        
        // Recent activities
        $recentBorrowings = \App\Models\Borrowing::with(['user', 'book'])
            ->latest()
            ->limit(5)
            ->get();
        
        $popularBooks = \App\Models\Book::withCount('borrowings')
            ->orderBy('borrowings_count', 'desc')
            ->limit(5)
            ->get();
        
        $activeMembers = \App\Models\User::where('role', 'member')
            ->where('status', 'active')
            ->withCount('borrowings')
            ->orderBy('borrowings_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalBooks', 'totalMembers', 'activeLoans', 'overdueLoans',
            'availableBooks', 'totalCategories', 'recentBorrowings', 
            'popularBooks', 'activeMembers'
        ));
    })->name('dasbor');

    // Manajemen Anggota
    Route::resource('anggota', MemberController::class)
        ->parameters(['anggota' => 'member']) // <-- Explicitly set parameter name to 'member'
        ->except(['create', 'store', 'edit', 'update']);
    
    Route::post('/anggota/{member}/status', [MemberController::class, 'updateStatus'])->name('anggota.status');

    // Manajemen Buku
    Route::resource('buku', BookController::class);

    // Manajemen Data Master
    Route::resource('kategori', CategoryController::class)
        ->parameters(['kategori' => 'category']);
        
    Route::resource('penerbit', PublisherController::class);
    Route::resource('lokasi', LocationController::class)
        ->parameters(['lokasi' => 'location']);

    // Manajemen Peminjaman
    Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
        Route::get('/', [AdminBorrowingController::class, 'index'])->name('index');
        Route::get('/create', [AdminBorrowingController::class, 'create'])->name('create');
        Route::post('/', [AdminBorrowingController::class, 'store'])->name('store');
        Route::get('/history', [AdminBorrowingController::class, 'history'])->name('history');
        Route::post('/{borrowing}/approve', [AdminBorrowingController::class, 'approve'])->name('approve');
        Route::post('/{borrowing}/return', [AdminBorrowingController::class, 'returnBook'])->name('return');
        Route::delete('/{borrowing}', [AdminBorrowingController::class, 'destroy'])->name('destroy');
        Route::get('/export/pdf', [AdminBorrowingController::class, 'exportPdf'])->name('export.pdf');
    });

    // Manajemen Reservasi
    Route::prefix('reservasi')->name('reservasi.')->group(function () {
        Route::get('/', [ReservationController::class, 'index'])->name('index');
        Route::get('/history', [ReservationController::class, 'history'])->name('history');
        Route::post('/{reservation}/approve', [ReservationController::class, 'approve'])->name('approve');
        Route::post('/{reservation}/convert', [ReservationController::class, 'convert'])->name('convert');
        Route::post('/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('cancel');
        Route::delete('/{reservation}', [ReservationController::class, 'destroy'])->name('destroy');
    });

    // Sirkulasi (SLiMS Style)
    Route::get('/sirkulasi', [App\Http\Controllers\Admin\CirculationController::class, 'index'])->name('sirkulasi.index');
    Route::post('/sirkulasi', [App\Http\Controllers\Admin\CirculationController::class, 'store'])->name('sirkulasi.store');

});

// Rute Anggota
Route::middleware(['auth', 'member', 'prevent-back-history'])->prefix('anggota')->name('member.')->group(function () {
    Route::get('/dasbor', function () {
        return view('member.dashboard');
    })->name('dashboard');

    // Profil
    Route::get('/profil', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profil/ubah', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');

    // Peminjaman
    Route::resource('peminjaman', MemberBorrowingController::class)->except(['show', 'edit', 'update']);
    Route::get('/peminjaman/riwayat', [MemberBorrowingController::class, 'history'])->name('peminjaman.history');
});

Route::get('/', function () {
    return view('welcome');
})->middleware('track') // <-- pasang di sini
  ->name('home');