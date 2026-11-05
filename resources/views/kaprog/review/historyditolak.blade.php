@extends('layout.main')

@section('content')
    <style>
        .btn-back {
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
                background-color: #7e7dfb;
                color: white;
                transform: translateY(-3px);
                box-shadow: 0 12px 24px rgba(0, 0, 0, 0.25);
            }

            .btn-back:active, .btn-reset:hover {
                color: white;
                background-color: #6b6bfa !important;
                transform: translateY( 3px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }

            .btn-reset {
                background-color: #7e7dfb;
                color: white;
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
                border: none;
                padding: 6px 12px;
                border-radius: 5px;
                font-size: 14px;
                margin-bottom: 0.5rem;
                cursor: pointer;
                transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out, background-color 0.2s ease-in-out;
            }
    </style>
    <div class="container-fluid">
        <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="col-md-12 mt-3 d-flex justify-content-between align-items-center">
                                    <h4 class="mb-3">History Pengajuan Diterima</h4>
                                    <button class="btn btn-reset shadow-sm">Reset Data</button>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow-sm" style="padding: 20px;">
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
                                        @forelse ($paginated as $index => $item)
                                            <tr>
                                                <td>{{ $paginated->firstItem() + $index }}</td>
                                                <td>{{ $item->user->name }}</td>
                                                <td> {{ $item->user->dataPribadi->kelas->name_kelas ?? '-' }}</td>
                                                <td>
                                                    {{ $item->nama ?? ($item->iduka->nama ?? '-') }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                                                <td><span class="badge bg-danger">Ditolak</span></td>
                                                <td>
                                                    <form action="#" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">Belum ada pengajuan ditolak.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <div class="d-flex justify-content-end mt-3">
                                    {{ $paginated->links('pagination::bootstrap-5') }}
                                </div>

                                <div class="d-flex justify-content-start mt-3">
                                    <a href="{{ route('review.usulan') }}" class="btn btn-back shadow-sm">Kembali</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

