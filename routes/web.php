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
use App\Http\Controllers\Member\BorrowingController as MemberBorrowingController;
use App\Http\Controllers\Member\ProfileController;
use App\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\DB;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Public Catalog (OPAC)
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{book}', [CatalogController::class, 'show'])->name('catalog.show');
Route::post('/catalog/{book}/reserve', [CatalogController::class, 'reserve'])->name('catalog.reserve')->middleware('auth');


// Admin Routes
// routes/web.php
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
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
    })->name('dashboard');

    // Member Management
    Route::resource('members', MemberController::class)->except(['create', 'store', 'edit', 'update']);
    Route::post('/members/{member}/status', [MemberController::class, 'updateStatus'])->name('members.status');

    // Book Management
    Route::resource('books', BookController::class);

    // Master Data Management
    Route::resource('categories', CategoryController::class);
    Route::resource('publishers', PublisherController::class);
    Route::resource('locations', LocationController::class);

    // Borrowing Management
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
});

// Member Routes
Route::middleware(['auth', 'member'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', function () {
        return view('member.dashboard');
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Borrowings
    Route::resource('borrowings', MemberBorrowingController::class)->except(['show', 'edit', 'update']);
    Route::get('/borrowings/history', [MemberBorrowingController::class, 'history'])->name('borrowings.history');
});

Route::get('/', function () {
    return view('welcome');
})->middleware('track') // <-- pasang di sini
  ->name('home');