@extends('layout.main')

@section('content')
    <style>
        /* .btn-back, .btn-reset {
            background-color: #7e7dfb;
            color: white;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            border: none;
            padding: 7px 14px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out, background-color 0.2s ease-in-out;
        }

        .btn-back:hover, .btn-reset:hover {
            background-color: #6b6bfa;
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.25);
        }

        .btn-reset {
            font-size: 14px;
            margin-bottom: 0.5rem;
        } */
    </style>

    <div class="container-fluid">
        <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
                <div class="row">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Riwayat Pengajuan Diterima</h5>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('pengajuan.review') }}" class="btn btn-primary btn-back btn-sm shadow-sm">
                                        <i class="bi bi-arrow-left-circle"></i>
                                        <span class="d-none d-md-inline">Kembali</span>
                                    </a>
                                    <button class="btn btn-danger btn-reset btn-sm shadow-sm">
                                        <i class="bi bi-arrow-clockwise"></i>
                                        <span class="d-none d-md-inline">Reset Data</span>
                                    </button>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow-sm p-3">
                        @if(session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif   
                        <div class="table-responsive text-nowrap">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Nama Institusi</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pengajuans as $key => $pengajuan)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $pengajuan->dataPribadi->user->name ?? 'Tidak Tersedia' }}</td>
                                            <td>{{ $pengajuan->dataPribadi->kelas->name_kelas ?? '-' }}</td>
                                            <td>{{ $pengajuan->iduka->nama ?? '-' }}</td>
                                            <td>{{ $pengajuan->created_at->format('d/m/Y') }}</td>
                                            <td><span class="badge bg-success">Diterima</span></td>
                                            <!-- <td>
                                                <form action="#" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td> -->
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data tersedia</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

