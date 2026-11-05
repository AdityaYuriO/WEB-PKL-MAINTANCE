@extends('layout.main')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Pengajuan Pindah PKL Selesai</h1>
    </div>

    <div class="card shadow">
        <div class="card-header bg-white">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('persuratan.pindah_pkl.index') }}">Menunggu Konfirmasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#">Selesai</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Institusi/Perusahaan</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pindah as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div>
                                    <span class="fw-bold">{{ $item->nama_iduka }}</span>
                                    <div class="small text-muted">
                                        {{ $item->jumlah_siswa ?? 1 }} siswa mengajukan ke sini
                                    </div>
                                </div>
                            </td>
                            <td>{{ date('d M Y', strtotime($item->updated_at)) }}</td>
                            <td>
                                @switch($item->status)
                                    @case('siap_kirim')
                                        <span class="badge bg-success">Siap Dikirim ke IDUKA</span>
                                        @break
                                    @case('ditolak_persuratan')
                                        <span class="badge bg-danger">Ditolak</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $item->status }}</span>
                                @endswitch
                            </td>
                            <td>
                                @if (in_array($item->status, ['siap_kirim', 'diterima_iduka']))
                                    <a href="{{ route('pindah-pkl.download-surat', $item->id) }}"
                                        class="btn btn-primary btn-sm" target="_blank">
                                        <i class="fas fa-file-pdf"></i> Cetak Surat
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-check-circle fa-2x mb-2 d-block"></i>
                                    Belum ada pengajuan pindah PKL yang selesai
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

