{{-- resources/views/member/borrowings/create.blade.php --}}
@extends('layouts.member')

@section('title', 'Borrow Book')

@section('content')
@php
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Storage;

    // Guard aman: cek apakah paginator
    $canPaginate = is_object($bookList) && method_exists($bookList, 'links');
@endphp

<div class="row">
    <div class="col-12">
        <h1><i class="fas fa-plus"></i> Borrow Book</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('member.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('member.borrowings.index') }}">Borrowings</a></li>
                <li class="breadcrumb-item active">Borrow New</li>
            </ol>
        </nav>
        <hr>
    </div>
</div>

@if(!auth()->user()->isActive())
    <div class="row">
        <div class="col-12">
            <div class="alert alert-warning">
                <h4><i class="fas fa-exclamation-triangle"></i> Account Not Approved</h4>
                <p>Your account is not yet approved by the administrator. You cannot borrow books until your account is approved.</p>
            </div>
        </div>
    </div>
@else
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    {{-- Search (GET) --}}
                    <div class="mb-3 d-flex align-items-center gap-2">
                        <form method="GET" action="{{ route('member.borrowings.create') }}" class="d-flex w-100" role="search">
                            <input name="q" value="{{ $q ?? '' }}" class="form-control me-2" placeholder="Cari judul / penulis...">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>

                    {{-- Grid kartu --}}
                    <div class="row g-3">
                        @forelse($bookList as $book)
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="card h-100 shadow-sm {{ !$book->isAvailable() ? 'border-danger' : '' }}">
                                    {{-- Cover --}}
                                    <div class="book-cover">
                                        @if($book->cover_image)
                                            <img
                                                src="{{ Storage::url($book->cover_image) }}"
                                                alt="{{ $book->title }}"
                                                onerror="this.remove()"
                                            >
                                        @endif
                                    </div>

                                    <div class="card-body d-flex flex-column">
                                        <h6 class="mb-1" title="{{ $book->title }}">{{ Str::limit($book->title, 60) }}</h6>
                                        <div class="text-muted small mb-2">{{ Str::limit($book->author, 48) }}</div>

                                        <div class="mb-2">
                                            @if($book->isAvailable())
                                                <span class="badge bg-success">Available: {{ $book->available_copies }}</span>
                                            @else
                                                <span class="badge bg-danger">Unavailable</span>
                                            @endif
                                            <span class="badge bg-secondary">{{ $book->category }}</span>
                                            <span class="badge bg-light text-dark">ISBN: {{ $book->isbn }}</span>
                                        </div>

                                        {{-- Submit per kartu --}}
                                        <form method="POST" action="{{ route('member.borrowings.store') }}" class="mt-auto">
                                            @csrf
                                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                                            <button type="submit"
                                                    class="btn btn-primary w-100"
                                                    {{ $book->isAvailable() ? '' : 'disabled' }}>
                                                <i class="fas fa-book-reader"></i>
                                                Borrow this book
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info mb-0">Belum ada buku yang bisa ditampilkan.</div>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination (hanya jika benar-benar paginator) --}}
                    @if($canPaginate)
                        <div class="mt-3">
                            {{ $bookList->appends(request()->query())->links() }}
                        </div>
                    @endif

                    <div class="alert alert-info mt-4">
                        <h5><i class="fas fa-info-circle"></i> Borrowing Information</h5>
                        <p>Standard loan period is 14 days. You can borrow one book at a time.</p>
                        <p>Your borrowing request will need administrator approval before it's processed.</p>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('member.borrowings.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endif

{{-- CSS cover responsif --}}
<style>
    .book-cover{
        position: relative;
        width: 100%;
        background: #f8f9fa;
        overflow: hidden;
    }
    @supports (aspect-ratio: 3 / 4) {
        .book-cover { aspect-ratio: 3 / 4; }
    }
    @supports not (aspect-ratio: 3 / 4) {
        .book-cover::before{
            content: "";
            display: block;
            padding-top: 133.333%;
        }
    }
    .book-cover > img{
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
</style>
@endsection
