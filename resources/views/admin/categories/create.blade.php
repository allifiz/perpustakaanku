@extends('layouts.admin')

@section('title', 'Tambah Kategori Baru')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dasbor</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.kategori.index') }}">Kategori</a></li>
                <li class="breadcrumb-item active">Tambah Baru</li>
            </ol>
        </nav>
        <h2>Tambah Kategori Baru</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.kategori.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="code" class="form-label">Kode DDC <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                               id="code" name="code" value="{{ old('code') }}" 
                               placeholder="cth., 000, 100, 200" maxlength="10" required>
                        <small class="form-text text-muted">Kode Klasifikasi Desimal Dewey</small>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" 
                               placeholder="cth., Ilmu Komputer, Informasi & Karya Umum" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Induk Kategori</label>
                        <select class="form-select @error('parent_id') is-invalid @enderror" 
                                id="parent_id" name="parent_id">
                            <option value="">-- Kategori Utama (Root) --</option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->code }} - {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Biarkan kosong jika ini kategori utama</small>
                        @error('parent_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Deskripsi singkat kategori ini">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="order" class="form-label">Urutan Tampilan</label>
                        <input type="number" class="form-control @error('order') is-invalid @enderror" 
                               id="order" name="order" value="{{ old('order', 0) }}" min="0">
                        <small class="form-text text-muted">Angka lebih kecil tampil duluan</small>
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" 
                                   name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Aktif (Dapat digunakan)
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Kategori
                        </button>
                        <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-info-circle"></i> Sistem DDC</h5>
                <p class="small">Sistem Klasifikasi Desimal Dewey membagi ilmu pengetahuan menjadi 10 kelas utama:</p>
                <ul class="small">
                    <li><code>000</code> - Ilmu Komputer, Informasi</li>
                    <li><code>100</code> - Filsafat & Psikologi</li>
                    <li><code>200</code> - Agama</li>
                    <li><code>300</code> - Ilmu Sosial</li>
                    <li><code>400</code> - Bahasa</li>
                    <li><code>500</code> - Sains</li>
                    <li><code>600</code> - Teknologi</li>
                    <li><code>700</code> - Kesenian & Rekreasi</li>
                    <li><code>800</code> - Kesusastraan</li>
                    <li><code>900</code> - Sejarah & Geografi</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
