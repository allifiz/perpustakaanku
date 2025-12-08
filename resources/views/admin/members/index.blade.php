{{-- resources/views/admin/members/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Kelola Anggota')

@section('content')
<div class="row">
    <div class="col-12">
        <h1><i class="fas fa-users"></i> Kelola Anggota</h1>
        <hr>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.anggota.index') }}">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nama atau email" 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-control">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
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
                                <th>ID Card / QR Code</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($members as $member)
                            <tr>
                                <td>
                                    @if($member->member_card_number || $member->id)
                                    <div class="qrcode" 
                                         data-value="{{ $member->member_card_number ?? $member->id }}"
                                         data-width="50"
                                         data-height="50"
                                         style="cursor: pointer;"
                                         onclick="showQrModal('{{ $member->member_card_number ?? $member->id }}', '{{ $member->name }}')">
                                    </div>
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->email }}</td>
                                <td>{{ $member->phone }}</td>
                                <td>
                                    @if($member->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($member->status == 'active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>{{ $member->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.anggota.show', $member) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($member->status == 'pending')
                                            <form action="{{ route('admin.anggota.status', $member) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="active">
                                                <button type="submit" class="btn btn-success btn-sm" 
                                                        onclick="return confirm('Approve this member?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('admin.anggota.status', $member) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        onclick="return confirm('Reject this member?')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form action="{{ route('admin.anggota.destroy', $member) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Delete this member? This cannot be undone.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No members found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    {{ $members->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Zoom QR Code -->
<div class="modal fade" id="qrZoomModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-header">
                <h5 class="modal-title" id="qrModalTitle">QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="qrModalContent" class="d-flex justify-content-center my-3"></div>
                <p id="qrModalValue" class="fw-bold text-break"></p>
            </div>
        </div>
    </div>
</div>

<script>
function showQrModal(value, name) {
    document.getElementById('qrModalTitle').innerText = name || 'QR Code';
    document.getElementById('qrModalValue').innerText = value;
    
    var container = document.getElementById('qrModalContent');
    container.innerHTML = ''; // Clear previous
    
    new QRCode(container, {
        text: value,
        width: 250,
        height: 250,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H
    });
    
    var modal = new bootstrap.Modal(document.getElementById('qrZoomModal'));
    modal.show();
}
</script>
@endsection