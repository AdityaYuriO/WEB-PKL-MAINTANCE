@extends('layout.main')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Verifikasi Pengajuan Pindah PKL</h1>
        <a href="{{ route('iduka.pindah_pkl.riwayat') }}" class="btn btn-outline-primary">
            <i class="fas fa-history"></i> Riwayat
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">Daftar Pengajuan Pindah PKL</h5>
                </div>
                <div class="col-md-6 text-md-end">
                    <span class="badge bg-warning text-dark">Menunggu Verifikasi: {{ $pindah->count() }}</span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Tempat PKL</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pindah as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <span class="avatar-title rounded-circle bg-primary text-white">
                                            {{ strtoupper(substr($item->nama_siswa, 0, 1)) }}
                                        </span>
                                    </div>
                                    <span>{{ $item->nama_siswa }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $item->kelas_id ?? '-' }}</span>
                            </td>
                            <td>{{ $item->nama_iduka }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <form action="{{ route('iduka.pindah_pkl.konfirmasi', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="diterima_iduka">
                                        <button type="submit" class="btn btn-success btn-sm"
                                                onclick="return confirm('Apakah Anda yakin menerima pengajuan pindah PKL dari {{ $item->nama_siswa }}?')">
                                            <i class="fas fa-check"></i> Terima
                                        </button>
                                    </form>
                                    <form action="{{ route('iduka.pindah_pkl.konfirmasi', $item->id) }}" method="POST" class="d-inline ms-1">
                                        @csrf
                                        <input type="hidden" name="status" value="ditolak_iduka">
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Apakah Anda yakin menolak pengajuan pindah PKL dari {{ $item->nama_siswa }}?')">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    <h5>Tidak ada pengajuan pindah PKL</h5>
                                    <p>Saat ini tidak ada siswa yang mengajukan pindah PKL ke institusi Anda.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    font-weight: 500;
    font-size: 14px;
}

.avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.table > :not(caption) > * > * {
    vertical-align: middle;
}

.btn-group .btn {
    white-space: nowrap;
}
</style>
@endpush

@push('scripts')
<script>
// Auto hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>
@endpush

