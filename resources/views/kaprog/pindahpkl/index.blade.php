@extends('layout.main')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Verifikasi Pengajuan Pindah PKL</h1>
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
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Tempat PKL</th>
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
                                <span class="badge bg-info">{{ $item->kelas_id }}</span>
                            </td>
                            <td>{{ $item->nama_iduka }}</td>
                            <td>
                                @switch($item->status)
                                    @case('menunggu')
                                        <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                                        @break
                                    @case('menunggu_surat')
                                        <span class="badge bg-info">Menunggu Persuratan</span>
                                        @break
                                    @case('siap_kirim')
                                        <span class="badge bg-success">Siap Dikirim</span>
                                        @break
                                    @case('ditolak_persuratan')
                                        <span class="badge bg-danger">Ditolak Persuratan</span>
                                        @break
                                    @case('ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $item->status }}</span>
                                @endswitch
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <form action="{{ route('kaprog.pindah_pkl.verifikasi', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" name="status" value="diterima" class="btn btn-success btn-sm"
                                                onclick="return confirm('Apakah Anda yakin menerima pengajuan ini?')">
                                            <i class="fas fa-check"></i> Terima
                                        </button>
                                    </form>
                                    <form action="{{ route('kaprog.pindah_pkl.verifikasi', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" name="status" value="ditolak" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Apakah Anda yakin menolak pengajuan ini?')">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    Tidak ada pengajuan pindah PKL yang menunggu verifikasi
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
}
</style>
@endpush

