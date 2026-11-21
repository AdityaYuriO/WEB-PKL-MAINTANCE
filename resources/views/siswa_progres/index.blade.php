@extends('layout.main')

@section('content')
    <div class="container">
        <h5 class="fw-bold mt-5">
            <i class="bx bx-bar-chart-alt-2 text-primary"></i>
            Statistik Usulan & Pengajuan
        </h5>

        {{-- Kartu Statistik --}}
        <div class="row mb-4 g-3">
            <div class="col-md-3">
                <div class="card text-center border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="bx bx-file text-primary fs-2 mb-2"></i>
                        <h6 class="text-muted">Jumlah Usulan</h6>
                        <h3 class="fw-bold text-primary">{{ $totalUsulan }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="bx bx-check-circle text-success fs-2 mb-2"></i>
                        <h6 class="text-muted">Usulan Diterima</h6>
                        <h3 class="fw-bold text-success">{{ $totalUsulanDiterima }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="bx bx-user-x text-warning fs-2 mb-2"></i>
                        <h6 class="text-muted">Belum Diterima PKL</h6>
                        <h3 class="fw-bold text-warning">{{ $totalBelumPKL }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card text-center border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="bx bx-x-circle text-danger fs-2 mb-2"></i>
                        <h6 class="text-muted">Usulan Ditolak</h6>
                        <h3 class="fw-bold text-danger">{{ $totalUsulanDitolak }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4 g-3">
            <div class="col-md-3">
                <div class="card text-center border-0 shadow-sm h-100">
                    <div class="card-body">
                        <i class="bx bx-send text-info fs-2 mb-2"></i>
                        <h6 class="text-muted">Jumlah Pengajuan</h6>
                        <h3 class="fw-bold text-info">{{ $totalPengajuan }}</h3>
                    </div>
                </div>
            </div>
        </div>


        {{-- Grafik Statistik --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white fw-bold">
                <i class="bx bx-pie-chart-alt-2 text-primary"></i> Grafik Usulan & Pengajuan
            </div>
            <div class="card-body">
                <canvas id="statistikChart" style="height:20px"></canvas>
            </div>
        </div>

        {{-- Filter --}}
        <div class="card border-0 shadow-sm mb-4" id="progres-filter">
            <div class="card-body">
                <form method="GET" action="{{ route('progres.siswa.index') }}#progres-filter">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <input type="text" name="nama_iduka" value="{{ request('nama_iduka') }}" class="form-control"
                                placeholder="Cari Siswa / Iduka">
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-2"></i> Search
                            </button>
                            <a href="{{ route('progres.siswa.index') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-sync-alt me-2"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel Daftar Siswa --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="card-title fw-bold mb-0">Daftar Siswa</h5>
                    </div>
                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <a href="{{ route('progres.siswa.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                            class="btn btn-outline-success">
                            <i class="fas fa-file-excel me-1"></i>
                            Export Semua
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover mb-0" id="siswaTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 fw-semibold text-muted small px-4 py-3">NO</th>
                                <th class="border-0 fw-semibold text-muted small py-3">NAMA SISWA</th>
                                <th class="border-0 fw-semibold text-muted small py-3">KELAS</th>
                                <th class="border-0 fw-semibold text-muted small py-3">JURUSAN</th>
                                <th class="border-0 fw-semibold text-muted small py-3">NAMA IDUKA</th>
                                <th class="border-0 fw-semibold text-muted small py-3">STATUS USULAN</th>
                                <th class="border-0 fw-semibold text-muted small py-3">STATUS SURAT USULAN</th>
                                <th class="border-0 fw-semibold text-muted small py-3">STATUS PENGAJUAN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($siswa as $index => $row)
                                <tr>
                                    <td class="px-4">{{ $siswa->firstItem() + $index }}</td>
                                    <td>{{ $row->nama_siswa }}</td>
                                    <td>{{ $row->name_kelas }}</td>
                                    <td>{{ $row->jurusan }}</td>
                                    <td>{{ $row->nama_iduka ?? '-' }}</td>
                                    <td class="text-center">
                                        @if ($row->status_usulan == 'diterima')
                                            <span class="badge bg-success">{{ $row->status_usulan }}</span>
                                        @elseif($row->status_usulan == 'ditolak')
                                            <span class="badge bg-danger">{{ $row->status_usulan }}</span>
                                        @elseif($row->status_usulan == 'proses')
                                            <span class="badge bg-warning">{{ $row->status_usulan }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $row->status_usulan ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($row->status_surat_usulan == 'sudah')
                                            <span class="badge bg-success">{{ $row->status_surat_usulan }}</span>
                                        @elseif($row->status_surat_usulan == 'belum')
                                            <span class="badge bg-danger">{{ $row->status_surat_usulan }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $row->status_surat_usulan ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($row->status_pengajuan == 'diterima')
                                            <span class="badge bg-success">{{ $row->status_pengajuan }}</span>
                                        @elseif($row->status_pengajuan == 'ditolak')
                                            <span class="badge bg-danger">{{ $row->status_pengajuan }}</span>
                                        @elseif($row->status_pengajuan == 'proses')
                                            <span class="badge bg-warning">{{ $row->status_pengajuan }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $row->status_pengajuan ?? '-' }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-transparent border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Menampilkan {{ $siswa->firstItem() }} - {{ $siswa->lastItem() }} dari {{ $siswa->total() }} data
                    </small>
                    <nav>
                        {{ $siswa->links('pagination::bootstrap-5') }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Load Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('statistikChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [
                            'Jumlah Usulan',
                            'Diterima',
                            'Ditolak',
                            'Pengajuan',
                            'Belum Diterima PKL'
                        ],
                        datasets: [{
                            label: 'Jumlah',
                            data: [
                                {{ $totalUsulan }},
                                {{ $totalUsulanDiterima }},
                                {{ $totalUsulanDitolak }},
                                {{ $totalPengajuan }},
                                {{ $totalBelumPKL }}
                            ],
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.7)',
                                'rgba(75, 192, 192, 0.7)',
                                'rgba(255, 99, 132, 0.7)',
                                'rgba(255, 206, 86, 0.7)',
                                'rgba(153, 102, 255, 0.7)' // warna baru
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(255, 99, 132, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(153, 102, 255, 1)' // warna baru
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { stepSize: 1 } }
                        }
                    }
                });
            }
        });
    </script>

@endpush
