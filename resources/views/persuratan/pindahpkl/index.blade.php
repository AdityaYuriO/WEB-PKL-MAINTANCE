@extends('layout.main')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Review Pengajuan Institusional / Perusahaan</h1>
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

    <div class="card shadow">
        <div class="card-header bg-white">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Menunggu Konfirmasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('persuratan.pindah_pkl.selesai') }}">Selesai</a>
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
                            <th>Status</th>
                            <th width="20%">Aksi</th>
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
                            <td>
                                <span class="badge bg-warning text-dark">Menunggu Konfirmasi</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('persuratan.pindah_pkl.detail', $item->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                    <form action="{{ route('persuratan.pindah_pkl.konfirmasi', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" name="status" value="diterima" class="btn btn-success btn-sm"
                                                onclick="return confirm('Apakah Anda yakin mengirim pengajuan ini?')">
                                            <i class="fas fa-paper-plane"></i> Kirim
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                    Tidak ada pengajuan institusi/perusahaan yang menunggu konfirmasi
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

