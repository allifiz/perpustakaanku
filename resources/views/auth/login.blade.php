{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Login - Perpustakaan Digital')

@section('content')
<div class="auth-container">
    <div class="auth-image">
        <h2>Selamat Datang Kembali</h2>
        <p>Akses koleksi buku dan layanan perpustakaan digital kami dengan mudah</p>
        <div style="font-size: 5rem; margin: 20px 0;">
            <i class="fas fa-book-reader"></i>
        </div>
        <p>Perpustakaan Digital</p>
    </div>
    
    <div class="auth-form">
        <div class="auth-header">
            <h1><i class="fas fa-sign-in-alt me-2"></i>Login</h1>
            <p>Masuk ke akun Anda untuk melanjutkan</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email') }}" required autocomplete="email" autofocus 
                       placeholder="Masukkan email Anda">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Kata Sandi</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                       name="password" required autocomplete="current-password" 
                       placeholder="Masukkan password Anda">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" 
                       {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">
                    Ingat saya
                </label>
            </div>

            <button type="submit" class="btn-auth btn-login">
                <i class="fas fa-sign-in-alt"></i> Masuk Sekarang
            </button>

            <div class="auth-footer">
                <p>Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a></p>
                <p><a href="{{ route('home') }}"><i class="fas fa-arrow-left me-1"></i>Kembali ke beranda</a></p>
            </div>
        </form>
    </div>
</div>
@endsection