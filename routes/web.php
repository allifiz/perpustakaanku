<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\BorrowingController as AdminBorrowingController;
use App\Http\Controllers\Member\BorrowingController as MemberBorrowingController;
use App\Http\Controllers\Member\ProfileController;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Admin Routes
// routes/web.php
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Member Management
    Route::resource('members', MemberController::class)->except(['create', 'store', 'edit', 'update']);
    Route::post('/members/{member}/status', [MemberController::class, 'updateStatus'])->name('members.status');

    // Book Management
    Route::resource('books', BookController::class);

    // Borrowing Management - Pisahkan route history
    Route::get('/borrowings/history', [AdminBorrowingController::class, 'history'])->name('borrowings.history');
    Route::resource('borrowings', AdminBorrowingController::class)->except(['show']);
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

// Home Route
Route::get('/', function () {
    return view('welcome');
})->name('home');