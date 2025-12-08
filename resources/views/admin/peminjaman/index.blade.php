{{-- resources/views/admin/borrowings/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kelola Peminjaman')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1><i class="fas fa-exchange-alt"></i> Kelola Peminjaman</h1>
            <a href="{{ route('admin.peminjaman.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Peminjaman Baru
            </a>
        </div>
        <hr>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
<form method="GET" action="{{ route('admin.peminjaman.index') }}">
  <div class="row g-2">
    {{-- Date range (dipakai utk borrow_date ATAU due_date) --}}
    <div class="col-md-2">
      <input type="date" name="from" class="form-control" value="{{ request('from') }}">
    </div>
    <div class="col-md-2">
      <input type="date" name="to" class="form-control" value="{{ request('to') }}">
    </div>

      {{-- Export PDF pakai GET + bawa semua parameter --}}
      <div class="col-md-2">
      <button type="submit"
              formaction="{{ route('admin.peminjaman.export.pdf') }}"
              formmethod="GET"
              class="btn btn-outline-danger w-100">
        <i class="fas fa-file-pdf"></i> Export PDF
      </button>
      </div>

    </div>
  </div>
</form>


            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Anggota</th>
                                <th>Buku</th>
                                <th>Tanggal Pinjam</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($borrowings as $borrowing)
                            <tr>
                                <td>{{ $borrowing->user->name }}</td>
                                <td>{{ $borrowing->book->title }}</td>
                                <td>{{ $borrowing->borrow_date->format('d M Y') }}</td>
                                <td>{{ $borrowing->due_date->format('d M Y') }}</td>
                                <td>
                                    @if($borrowing->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($borrowing->status == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($borrowing->status == 'returned')
                                        <span class="badge bg-info">Returned</span>
                                    @else
                                        <span class="badge bg-danger">Overdue</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if($borrowing->status == 'pending')
                                            <form action="{{ route('admin.peminjaman.approve', $borrowing) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" 
                                                        onclick="return confirm('Approve this borrowing request?')">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                            </form>
                                        @elseif($borrowing->status == 'approved')
                                            <form action="{{ route('admin.peminjaman.return', $borrowing) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-info btn-sm" 
                                                        onclick="return confirm('Mark this book as returned?')">
                                                    <i class="fas fa-undo"></i> Return
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form action="{{ route('admin.peminjaman.destroy', $borrowing) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Delete this borrowing record? This cannot be undone.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No borrowing records found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    {{ $borrowings->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection