{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')

@section('title', 'Register - Perpustakaan Digital')

@section('content')
<div class="auth-container">
    <div class="auth-image">
        <h2>Bergabung dengan Kami</h2>
        <p>Jadilah bagian dari komunitas pembaca digital yang berkembang</p>
        <div style="font-size: 5rem; margin: 20px 0;">
            <i class="fas fa-user-plus"></i>
        </div>
        <p>Perpustakaan Digital</p>
    </div>
    
    <div class="auth-form">
        <div class="auth-header">
            <h1><i class="fas fa-user-plus me-2"></i>Registrasi</h1>
            <p>Buat akun baru untuk mengakses layanan kami</p>
        </div>

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                       name="name" value="{{ old('name') }}" required autocomplete="name" autofocus 
                       placeholder="Masukkan nama lengkap Anda">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                       name="email" value="{{ old('email') }}" required autocomplete="email" 
                       placeholder="Masukkan email Anda">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                               name="password" required autocomplete="new-password" 
                               placeholder="Buat password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password-confirm" class="form-label">Konfirmasi Password</label>
                        <input id="password-confirm" type="password" class="form-control" 
                               name="password_confirmation" required autocomplete="new-password" 
                               placeholder="Konfirmasi password">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Nomor Telepon</label>
                <input id="phone" type="number" class="form-control @error('phone') is-invalid @enderror" 
                       name="phone" value="{{ old('phone') }}" required 
                       placeholder="Masukkan nomor telepon">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="address" class="form-label">Alamat</label>
                <textarea id="address" class="form-control @error('address') is-invalid @enderror" 
                          name="address" rows="3" required 
                          placeholder="Masukkan alamat lengkap Anda">{{ old('address') }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="id_card" class="form-label">Kartu Identitas (KTP/KTM)</label>
                <input id="id_card" type="file" class="form-control @error('id_card') is-invalid @enderror" 
                       name="id_card" required>
                <div class="form-text">Format yang diterima: JPG, PNG, GIF. Maksimal 2MB</div>
                @error('id_card')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-auth btn-register">
                <i class="fas fa-user-plus"></i> Daftar Sekarang
            </button>

            <div class="auth-footer">
                <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk sekarang</a></p>
                <p><a href="{{ route('home') }}"><i class="fas fa-arrow-left me-1"></i>Kembali ke beranda</a></p>
            </div>
        </form>
    </div>
</div>
@endsection