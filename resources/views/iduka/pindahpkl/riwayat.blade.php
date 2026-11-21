@extends('layout.main')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Riwayat Pengajuan Pindah PKL</h1>
        <a href="{{ route('iduka.pindah_pkl.index') }}" class="btn btn-primary">
            <i class="fas fa-list"></i> Pengajuan Masuk
        </a>
    </div>

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
                            <th>Tanggal Pengajuan</th>
                            <th>Tanggal Konfirmasi</th>
                            <th>Status</th>
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
                            <td>{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                            <td>{{ date('d/m/Y', strtotime($item->updated_at)) }}</td>
                            <td>
                                @switch($item->status)
                                    @case('diterima_iduka')
                                        <span class="badge bg-success">Diterima</span>
                                        @break
                                    @case('ditolak_iduka')
                                        <span class="badge bg-danger">Ditolak</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $item->status }}</span>
                                @endswitch
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    Tidak ada riwayat pengajuan pindah PKL
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

