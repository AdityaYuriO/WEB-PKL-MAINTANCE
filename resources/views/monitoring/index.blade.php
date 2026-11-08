@extends('layout.main')

@section('content')
    <div class="container-fluid py-3 px-2 px-md-3">
        <div class="container-xxl flex-grow-1 container-p-y">

            @if (in_array(auth()->user()->role, ['kepsek', 'hubin']))
                <h5 class="fw-bold mt-3 mt-md-5">
                    <i class="bx bx-desktop text-primary"></i>
                    Monitoring IDUKA
                </h5>

                {{-- Alert Messages --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bx bx-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Kartu Statistik --}}
                <div class="row mb-4 g-2 g-md-3">
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card text-center border-0 shadow-sm h-100 stat-card">
                            <div class="card-body">
                                <i class="bx bx-desktop text-primary fs-2 mb-2"></i>
                                <h6 class="text-muted">Total Monitoring</h6>
                                <h3 class="fw-bold text-primary mb-0">{{ $totalMonitoring }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card text-center border-0 shadow-sm h-100 stat-card">
                            <div class="card-body">
                                <i class="bx bx-buildings text-info fs-2 mb-2"></i>
                                <h6 class="text-muted">Total IDUKA</h6>
                                <h3 class="fw-bold text-info mb-0">{{ $totalIduka }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card text-center border-0 shadow-sm h-100 stat-card">
                            <div class="card-body">
                                <i class="bx bx-check-circle text-success fs-2 mb-2"></i>
                                <h6 class="text-muted">IDUKA Termonitor</h6>
                                <h3 class="fw-bold text-success mb-0">{{ $totalIdukaWithMonitoring }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card text-center border-0 shadow-sm h-100 stat-card">
                            <div class="card-body">
                                <i class="bx bx-x-circle text-warning fs-2 mb-2"></i>
                                <h6 class="text-muted">Belum Termonitor</h6>
                                <h3 class="fw-bold text-warning mb-0">{{ $totalIdukaWithoutMonitoring }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card text-center border-0 shadow-sm h-100 stat-card">
                            <div class="card-body">
                                <i class="bx bx-user-check text-success fs-2 mb-2"></i>
                                <h6 class="text-muted">Perkiraan Siswa Diterima</h6>
                                <h3 class="fw-bold text-success mb-0">{{ $totalPerkiraanSiswa }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card text-center border-0 shadow-sm h-100 stat-card">
                            <div class="card-body">
                                <i class="bx bx-user-x text-danger fs-2 mb-2"></i>
                                <h6 class="text-muted">Petugas Belum Monitoring</h6>
                                <h3 class="fw-bold text-danger mb-0">{{ $guruBelumMonitoring }}</h3>
                                <button class="btn btn-outline-danger btn-sm mt-2" data-bs-toggle="modal"
                                    data-bs-target="#modalBelumMonitoring">
                                    Lihat Daftar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Filter dan Tambah Data --}}
            <div class="card border-0 shadow-sm mb-4 my-4">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-8">
                            <form method="GET" action="{{ route('monitoring.index') }}">
                                <div class="row g-2 g-md-3 align-items-end">
                                    <div class="col-12 col-md-6">
                                        <input type="text" name="nama_iduka" value="{{ request('nama_iduka') }}"
                                            class="form-control" placeholder="Cari IDUKA...">
                                    </div>
                                    <div class="col-12 col-md-6 d-flex gap-2">
                                        <button type="submit" class="btn btn-primary flex-fill flex-md-grow-0">
                                            <i class="fas fa-search me-2"></i> Cari
                                        </button>
                                        <a href="{{ route('monitoring.index') }}" class="btn btn-secondary flex-fill flex-md-grow-0">
                                            <i class="fas fa-sync-alt me-2"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        {{-- Tombol Tambah hanya untuk selain kaprog --}}
                        <div class="col-12 col-md-4 text-md-end">
                            @if (in_array(auth()->user()->role, ['kaprog', 'guru']))
                                <a href="{{ route('monitoring.create') }}" class="btn btn-success w-100 w-md-auto">
                                    <i class="fas fa-plus me-2"></i> Tambah Monitoring
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel Monitoring --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title fw-bold mb-0">
                        <i class="bx bx-list-ul text-primary me-2"></i>
                        Data Monitoring
                    </h5>
                </div>

                <div class="card-body p-0">
                    <div class="table-scroll-hint">
                        <i class="bi bi-arrow-left-right"></i> Geser tabel ke kiri/kanan untuk melihat semua kolom
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="tabel-monitoring">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 fw-semibold text-muted small px-4 py-3">NO</th>
                                    <th class="border-0 fw-semibold text-muted small py-3">PEMONITORING</th>
                                    <th class="border-0 fw-semibold text-muted small py-3">NAMA IDUKA</th>
                                    <th class="border-0 fw-semibold text-muted small py-3">SARAN / CATATAN</th>
                                    <th class="border-0 fw-semibold text-muted small py-3 text-center">PERKIRAAN SISWA DITERIMA</th>
                                    <th class="border-0 fw-semibold text-muted small py-3 text-center">FOTO</th>
                                    <th class="border-0 fw-semibold text-muted small py-3 text-center">TANGGAL</th>
                                    <th class="border-0 fw-semibold text-muted small py-3 text-center">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($monitoring as $index => $item)
                                    <tr>
                                        <td class="px-4 align-middle">{{ $monitoring->firstItem() + $index }}</td>

                                        <td class="align-middle">
                                            <div class="fw-semibold">{{ $item->guru->nama ?? '-' }}</div>
                                            <small class="text-muted">
                                                {{ $item->guru->konke->name_konke ?? '-' }}
                                            </small>
                                        </td>

                                        <td class="align-middle">
                                            <div class="fw-semibold">{{ $item->iduka->nama ?? '-' }}</div>
                                            <small class="text-muted">{{ $item->iduka->alamat ?? '-' }}</small>
                                            <p class="mb-0 mt-1">
                                                <small>{{ optional($item->tgl)->format('d-m-Y') ?? '-' }}</small>
                                            </p>
                                        </td>

                                        <td class="align-middle" style="max-width: 250px;">
                                            {{ Str::limit($item->saran, 100) ?: '-' }}
                                        </td>

                                        <td class="text-center align-middle">
                                            <span class="badge bg-info fs-6">
                                                {{ $item->perikiraan_siswa_diterima ?? '-' }} siswa
                                            </span>
                                        </td>

                                        <td class="text-center align-middle">
                                            @if ($item->foto)
                                                @foreach (json_decode($item->foto, true) as $foto)
                                                    <button class="btn btn-outline-primary btn-sm mb-1"
                                                        onclick="showImage('{{ asset($foto) }}', '{{ $item->iduka->nama }}')">
                                                        <i class="fas fa-image"></i> Lihat
                                                    </button>
                                                @endforeach
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>

                                        <td class="text-center align-middle">
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($item->tgl)->format('d-m-Y') }}
                                            </small>
                                        </td>

                                        <td class="text-center align-middle">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('monitoring.show', $item->id) }}"
                                                    class="btn btn-outline-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                @if (in_array(auth()->user()->role, ['guru', 'kaprog']))
                                                    <a href="{{ route('monitoring.edit', $item->id) }}"
                                                        class="btn btn-outline-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button class="btn btn-outline-danger btn-sm"
                                                        onclick="confirmDelete({{ $item->id }}, '{{ $item->iduka->nama }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bx bx-folder-open fs-1"></i>
                                                <div class="mt-2">Belum ada data monitoring</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($monitoring->hasPages())
                    <div class="card-footer bg-transparent border-0">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                            <small class="text-muted text-center text-sm-start">
                                Menampilkan {{ $monitoring->firstItem() }} - {{ $monitoring->lastItem() }}
                                dari {{ $monitoring->total() }} data
                            </small>
                            <nav>
                                {{ $monitoring->links('pagination::bootstrap-5') }}
                            </nav>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal untuk menampilkan gambar --}}
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Foto Monitoring</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Belum Monitoring --}}
    <div class="modal fade" id="modalBelumMonitoring" tabindex="-1" aria-labelledby="modalBelumMonitoringLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBelumMonitoringLabel">Pembimbing Belum Monitoring</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    @if ($guruBelumMonitoringData->isNotEmpty())
                        <div class="table-scroll-hint">
                            <i class="bi bi-arrow-left-right"></i> Geser tabel ke kiri/kanan untuk melihat semua kolom
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0" id="tabel-belum-monitoring">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>NIP</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($guruBelumMonitoringData as $i => $guru)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $guru->name ?? '-' }}</td>
                                            <td>{{ $guru->nip ?? '-' }}</td>
                                            <td>{{ $guru->email ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">
                            <i class="bx bx-check-circle text-success"></i>
                            Semua guru/kaprog sudah membuat monitoring.
                        </p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal konfirmasi hapus --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data monitoring untuk <strong id="deleteItemName"></strong>?</p>
                    <p class="text-danger mb-0"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- ⚡ CSS FIX: bikin tabel bisa di-scroll di HP                  --}}
    {{-- ============================================================ --}}
    <style>
        /* Hint scroll — hanya tampil di HP */
        .table-scroll-hint {
            display: none;
            font-size: .72rem;
            color: #64748b;
            background: #f1f5f9;
            border-radius: 8px;
            padding: 6px 10px;
            margin: 12px 12px 8px;
        }

        /* === Mobile & Tablet (≤ 991px) === */
        @media (max-width: 991.98px) {

            /* 1) HAPUS overflow:hidden dari card (biang keroknya!) */
            .card {
                overflow: visible !important;
            }

            /* 2) Balikin rounded corner biar card tetap rapi */
            .card > .card-header:first-child {
                border-top-left-radius: .5rem !important;
                border-top-right-radius: .5rem !important;
            }
            .card > .card-body:last-child,
            .card > .card-footer:last-child {
                border-bottom-left-radius: .5rem !important;
                border-bottom-right-radius: .5rem !important;
            }

            /* 3) Hint muncul */
            .table-scroll-hint { display: block; }

            /* 4) Paksa .table-responsive jadi scroll container beneran */
            .table-responsive {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                overflow-x: auto !important;
                overflow-y: hidden !important;
                -webkit-overflow-scrolling: touch !important;
                overscroll-behavior-x: contain !important;
                touch-action: pan-x pan-y !important;
            }

            /* 5) Tabel harus lebih lebar dari container supaya bisa di-scroll */
            #tabel-monitoring         { min-width: 1100px !important; margin-bottom: 0 !important; }
            #tabel-belum-monitoring   { min-width: 620px !important; margin-bottom: 0 !important; }

            /* 6) Scrollbar tipis sebagai petunjuk bisa digeser */
            .table-responsive::-webkit-scrollbar { height: 6px; }
            .table-responsive::-webkit-scrollbar-track {
                background: #f1f5f9;
                border-radius: 3px;
            }
            .table-responsive::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 3px;
            }
            .table-responsive::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }

            /* 7) Statistik cards lebih compact */
            .stat-card .card-body { padding: .85rem .5rem !important; }
            .stat-card i      { font-size: 1.6rem !important; }
            .stat-card h6     { font-size: .72rem !important; margin-bottom: .25rem !important; }
            .stat-card h3     { font-size: 1.1rem !important; }
            .stat-card .btn   { font-size: .7rem !important; padding: .25rem .5rem !important; }

            /* 8) Modal table scroll */
            .modal-body .table-responsive { overflow-x: auto !important; }
        }

        /* === Extra small (≤ 575px) === */
        @media (max-width: 575.98px) {
            .stat-card i  { font-size: 1.3rem !important; }
            .stat-card h6 { font-size: .65rem !important; }
            .stat-card h3 { font-size: .95rem !important; }
            .stat-card .card-body { padding: .65rem .35rem !important; }

            .card-body { padding: .75rem !important; }
            .card-header h5 { font-size: .95rem !important; }

            .table { font-size: .82rem; }
            .table > :not(caption) > * > * { padding: .5rem .5rem; }

            /* Tombol group aksi lebih rapat */
            .btn-group > .btn { padding: .3rem .5rem !important; font-size: .72rem !important; }
        }
    </style>
@endsection

@push('scripts')
    <script>
        function showImage(imageUrl, title) {
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('imageModalLabel').textContent = 'Foto Monitoring - ' + title;
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        }

        function confirmDelete(id, name) {
            document.getElementById('deleteItemName').textContent = name;
            document.getElementById('deleteForm').action = '/monitoring/' + id;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
@endpush
