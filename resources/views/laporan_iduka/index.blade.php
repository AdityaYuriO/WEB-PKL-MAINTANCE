@extends('layout.main')

@section('content')
    <div class="container py-4">

        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="{{ route('laporan.iduka.index') }}">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold text-muted small">Nama Iduka</label>
                                            <input type="text" name="nama_iduka" value="{{ request('nama_iduka') }}"
                                                class="form-control" placeholder="Cari Nama Iduka">
                                        </div>
                                        <div class="col-md-3 d-flex align-items-end">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fas fa-search me-2"></i>Search
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-bold mb-0"> Daftar Laporan Iduka</h5>
                            <div class="col-lg-4">
                                <div class="d-flex gap-2 justify-content-lg-end mt-3 mt-lg-0">
                                    <a href="{{ route('laporan.iduka.export.all') }}" class="btn btn-outline-success">
                                        <i class="fas fa-file-excel me-1"></i>
                                        Export Semua
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-hover mb-0" id="idukaTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 fw-semibold text-muted small px-4 py-3">#</th>
                                        <th class="border-0 fw-semibold text-muted small py-3">NAMA IDUKA</th>
                                        <th class="border-0 fw-semibold text-muted small py-3">BIDANG</th>
                                        <th class="border-0 fw-semibold text-muted small py-3">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($idukas as $index => $iduka)
                                        <tr class="border-bottom border-light">
                                            <td class="px-4 py-3 fw-semibold">{{ $idukas->firstItem() + $index }}</td>
                                            <td class="py-3">
                                                <div class="fw-semibold">{{ $iduka->nama }}</div>
                                                <small class="text-muted">Pimpinan:
                                                    {{ $iduka->nama_pimpinan ?? '-' }}</small>

                                                @if ($iduka->siswa->count() > 0)
                                                    <ul class="mt-2 small text-muted">
                                                        @foreach ($iduka->siswa as $siswa)
                                                                @if ($siswa->status == "aktif")
                                                            <li>{{ $siswa->name }} ({{ $siswa->email }})</li>
                                                                @endif
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <small class="text-muted">Belum ada siswa diterima</small>
                                                @endif
                                            </td>
                                            <td class="py-3">
                                                <span class="badge bg-primary-subtle text-primary">
                                                    {{ $iduka->bidang_industri ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="py-3">
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('laporan.iduka.siswa', $iduka->id) }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fas fa-users"></i> Lihat Siswa
                                                    </a>

                                                    <a href="{{ route('laporan.iduka.export.excel', $iduka->id) }}"
                                                        class="btn btn-outline-success btn-sm">
                                                        <i class="fas fa-file-excel me-2"></i> Export
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                        <div class="card-footer bg-transparent border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    Menampilkan {{ $idukas->firstItem() }} - {{ $idukas->lastItem() }} dari
                                    {{ $idukas->total() }} data
                                </small>

                                <nav>
                                    {{ $idukas->links('pagination::bootstrap-5') }}
                                </nav>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .card-hover {
        transition: all 0.3s ease;
    }

    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.03);
    }

    .animate-counter {
        transition: all 0.6s ease;
    }

    .badge {
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .card {
        border-radius: 12px;
        backdrop-filter: blur(10px);
    }

    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .form-select,
    .form-control {
        border-radius: 8px;
        border: 1px solid #e3e6f0;
        transition: all 0.3s ease;
    }

    .form-select:focus,
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }


    canvas {
        border-radius: 8px;
    }

    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    @keyframes pulse {
        0% {
            opacity: 0.8;
        }

        50% {
            opacity: 1;
        }

        100% {
            opacity: 0.8;
        }
    }

    .pulse-animation {
        animation: pulse 2s infinite;
    }
</style>

<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs


