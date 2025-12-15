@extends('layouts.admin')

@section('title', 'Konfirmasi Pengembalian & Denda')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Keterlambatan Pengembalian</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <h4 class="alert-heading">Buku ini Terlambat!</h4>
                    <p>Peminjaman ini telah melewati batas waktu pengembalian. Harap memproses pembayaran denda sebelum menyelesaikan pengembalian.</p>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted">Informasi Peminjaman</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="120">Anggota</td>
                                <td>: <strong>{{ $borrowing->user->name }}</strong></td>
                            </tr>
                            <tr>
                                <td>Buku</td>
                                <td>: {{ $borrowing->book->title }}</td>
                            </tr>
                            <tr>
                                <td>Tgl Pinjam</td>
                                <td>: {{ $borrowing->borrow_date->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td>Jatuh Tempo</td>
                                <td>: {{ $borrowing->due_date->format('d M Y') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted">Rincian Denda</h6>
                        <div class="card bg-light border-0">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Terlambat</span>
                                    <span class="fw-bold text-danger">{{ $daysOverdue }} Hari</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tarif Denda</span>
                                    <span>Rp {{ number_format($finePerDay, 0, ',', '.') }} / hari</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span class="h5 mb-0">Total Denda</span>
                                    <span class="h5 mb-0 text-danger fw-bold">Rp {{ number_format($fineAmount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.peminjaman.return', $borrowing->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="confirm_return" value="1">
                    <input type="hidden" name="fine_amount" value="{{ $fineAmount }}">
                    
                    <div class="mb-3">
                        <label class="form-label">Catatan Tambahan (Opsional)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Contoh: Denda dibayar tunai"></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-money-bill-wave me-2"></i>Bayar Denda & Kembalikan Buku
                        </button>
                        <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
