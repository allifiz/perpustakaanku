@extends('layouts.admin')

@section('title', 'Sirkulasi (Peminjaman & Pengembalian)')

@section('content')
<div class="row">
    <!-- Area Scan Anggota -->
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h5 class="card-title mb-3"><i class="fas fa-users me-2"></i>Mulai Transaksi</h5>
                <form action="{{ route('admin.sirkulasi.index') }}" method="GET">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-primary text-white"><i class="fas fa-barcode"></i></span>
                        <input type="text" id="member_id_input" name="member_id" class="form-control" placeholder="Scan Kartu Anggota / Masukkan ID Anggota..." autofocus value="{{ request('member_id') }}" autocomplete="off">
                        <button class="btn btn-outline-secondary" type="button" onclick="startScanner('member_id_input')">
                            <i class="fas fa-camera"></i> Scan Kamera
                        </button>
                        <button class="btn btn-primary" type="submit">Cari Anggota</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($member)
    <!-- Dashboard Sirkulasi Anggota -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body text-center">
                <div class="mb-3">
                    @if($member->photo)
                        <img src="{{ asset('storage/'.$member->photo) }}" class="rounded-circle img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto text-white" style="width: 150px; height: 150px; font-size: 3rem;">
                            {{ strtoupper(substr($member->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <h4>{{ $member->name }}</h4>
                <p class="text-muted">{{ $member->member_card_number ?? 'ID: ' . $member->id }}</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.anggota.show', $member->id) }}" class="btn btn-outline-info btn-sm">Lihat Profil Lengkap</a>
                    <a href="{{ route('admin.sirkulasi.index') }}" class="btn btn-outline-danger btn-sm">Selesai / Ganti Anggota</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Area Scan Buku -->
        <div class="card shadow-sm border-0 mb-4 bg-light">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-book me-2"></i>Pinjam Buku</h5>
                <form id="book-loan-form" action="{{ route('admin.sirkulasi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="member_id" value="{{ $member->id }}">
                    <div class="input-group">
                        <input type="text" id="book_barcode_input" name="book_barcode" class="form-control" placeholder="Scan Barcode Buku..." autofocus autocomplete="off">
                        <button class="btn btn-outline-secondary" type="button" onclick="startScanner('book_barcode_input', true)">
                            <i class="fas fa-camera"></i> Scan Kamera
                        </button>
                        <button class="btn btn-success" type="submit"><i class="fas fa-plus me-1"></i> Proses</button>
                    </div>
                    <small class="text-muted">Scan buku untuk menyetujui request pending atau membuat pinjaman baru.</small>
                </form>
            </div>
        </div>

        <!-- Tabel Pending Request -->
        @if($pendingBorrowings->count() > 0)
        <div class="card shadow-sm border-0 mb-4 border-start border-warning border-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 text-warning"><i class="fas fa-clock me-2"></i>Permintaan Peminjaman (Pending Request)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Buku</th>
                                <th>Tgl Request</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingBorrowings as $loan)
                            <tr>
                                <td>
                                    <strong>{{ $loan->book->title }}</strong><br>
                                    <small class="text-muted">{{ $loan->book->barcode ?? $loan->book->id }}</small>
                                </td>
                                <td>{{ $loan->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <!-- Form approve manual jika barcode susah -->
                                    <form action="{{ route('admin.peminjaman.approve', $loan->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fas fa-check"></i> Setujui
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Tabel Sedang Dipinjam -->
        <div class="card shadow-sm border-0 mb-4">
             <div class="card-header bg-white">
                <h6 class="mb-0 text-primary"><i class="fas fa-book-reader me-2"></i>Sedang Dipinjam</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Buku</th>
                                <th>Tgl Pinjam</th>
                                <th>Jatuh Tempo</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeBorrowings as $loan)
                            <tr>
                                <td>
                                    <strong>{{ $loan->book->title }}</strong><br>
                                    <small class="text-muted">{{ $loan->book->barcode ?? $loan->book->id }}</small>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($loan->borrow_date)->format('d/m/Y') }}</td>
                                <td class="{{ \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($loan->due_date)) ? 'text-danger fw-bold' : '' }}">
                                    {{ \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') }}
                                </td>
                                <td>
                                     <form action="{{ route('admin.peminjaman.return', $loan->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-undo"></i> Kembali
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-3 text-muted">Tidak ada buku yang sedang dipinjam.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    @endif
</div>

<!-- Modal Scanner -->
<div class="modal fade" id="scannerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Barcode / QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="stopScanner()"></button>
            </div>
            <div class="modal-body">
                <div id="reader" style="width: 100%"></div>
            </div>
        </div>
    </div>
</div>

<!-- Library HTML5-QRCode -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let html5QrcodeScanner = null;
    let targetInputId = null;
    let autoSubmit = false;

    function startScanner(inputId, submit = false) {
        targetInputId = inputId;
        autoSubmit = submit;
        
        // Buka modal
        const modal = new bootstrap.Modal(document.getElementById('scannerModal'));
        modal.show();

        // Inisialisasi Scanner jika belum
        if (html5QrcodeScanner === null) {
            html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", 
                { fps: 10, qrbox: {width: 250, height: 250} },
                /* verbose= */ false
            );
            
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        }
    }

    function onScanSuccess(decodedText, decodedResult) {
        // Handle on success condition with the decoded text or result.
        console.log(`Scan result: ${decodedText}`, decodedResult);
        
        // Isi input field value
        if (targetInputId) {
            document.getElementById(targetInputId).value = decodedText;
        }

        // Stop scanner dan tutup modal
        stopScanner();
        
        // Tutup modal secara programmatik
        const modalEl = document.getElementById('scannerModal');
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) {
            modal.hide();
        }

        // Proses submit dengan fake loading
        if (autoSubmit && targetInputId === 'book_barcode_input') {
            handleBookLoanSubmit();
        } else if (targetInputId === 'member_id_input') {
            // Untuk member search, kita bisa auto submit juga form parent nya
            showLoading("Mencari Anggota...");
            setTimeout(function() {
                document.getElementById(targetInputId).closest('form').submit();
            }, 2000);
        }
    }

    function onScanFailure(error) {
        // handle scan failure, usually better to ignore and keep scanning.
    }

    function stopScanner() {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.clear().then(_ => {
                html5QrcodeScanner = null;
            }).catch(error => {
                console.error("Failed to clear html5QrcodeScanner.", error);
            });
        }
    }
    
    // Pastikan scanner mati saat modal ditutup paksa
    document.getElementById('scannerModal').addEventListener('hidden.bs.modal', function () {
        stopScanner();
    });

    // Function untuk menangani submit peminjaman manual (via tombol enter/submit)
    document.getElementById('book-loan-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        handleBookLoanSubmit();
    });

    function handleBookLoanSubmit() {
        showLoading("Sedang Memproses Peminjaman...");
        
        // Delay 2 detik sebelum submit form sesungguhnya
        setTimeout(function() {
            document.getElementById('book-loan-form').submit();
        }, 2000);
    }

    function showLoading(title) {
        Swal.fire({
            title: title,
            html: 'Mohon tunggu sebentar...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    // Cek flash message dari session Laravel untuk menampilkan SweetAlert sukses/error
    document.addEventListener("DOMContentLoaded", function() {
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
            });
        @endif
    });
</script>
@endsection
