@extends('layouts.admin')

@section('title', 'Ubah Kategori')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dasbor</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.kategori.index') }}">Kategori</a></li>
                <li class="breadcrumb-item active">Ubah</li>
            </ol>
        </nav>
        <h2>Ubah Kategori: {{ $category->name }}</h2>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.kategori.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="code" class="form-label">Kode DDC <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                               id="code" name="code" value="{{ old('code', $category->code) }}" 
                               placeholder="cth., 000, 100, 200" maxlength="10" required>
                        <small class="form-text text-muted">Kode Klasifikasi Desimal Dewey</small>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $category->name) }}" 
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
                                <option value="{{ $parent->id }}" 
                                    {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
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
                                  placeholder="Deskripsi singkat kategori ini">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="order" class="form-label">Urutan Tampilan</label>
                        <input type="number" class="form-control @error('order') is-invalid @enderror" 
                               id="order" name="order" value="{{ old('order', $category->order) }}" min="0">
                        <small class="form-text text-muted">Angka lebih kecil tampil duluan</small>
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" 
                                   name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Aktif (Dapat digunakan)
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Perbarui Kategori
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
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-info-circle"></i> Info Kategori</h5>
                <table class="table table-sm">
                    <tr>
                        <th>Jumlah Buku:</th>
                        <td><span class="badge bg-info">{{ $category->books()->count() }}</span></td>
                    </tr>
                    <tr>
                        <th>Dibuat:</th>
                        <td>{{ $category->created_at->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Diperbarui:</th>
                        <td>{{ $category->updated_at->format('d M Y') }}</td>
                    </tr>
                </table>
                
                @if($category->books()->count() > 0)
                <div class="alert alert-warning small">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Kategori ini memiliki {{ $category->books()->count() }} buku. 
                    Anda tidak dapat menghapusnya sampai semua buku dipindahkan ke kategori lain.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
