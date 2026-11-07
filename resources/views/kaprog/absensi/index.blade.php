@extends('layout.main')

@section('content')
    <div class="container-fluid">
        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- Simple Header Card -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 bg-white shadow-sm">
                        <div class="card-body p-4">
                            <div
                                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                                <div>
                                    <h4 class="mb-1 text-dark fw-bold">TATA RIKSA K-ONE</h4>
                                    <p class="text-secondary mb-0">Absensi - data siswa PKL Per Jurusan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Notifikasi Hari Libur untuk hari ini (jika ada) --}}
            @if (!empty($holidaysByIduka) && count($holidaysByIduka) > 0)
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-warning d-flex align-items-start" role="alert">
                            <div class="me-3">
                                <i class="fas fa-calendar-day fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="alert-heading mb-1">Perhatian — Hari Libur Hari Ini</h5>
                                <p class="mb-1">Beberapa IDUKA memiliki hari libur hari ini. Data absensi untuk IDUKA
                                    tersebut mungkin tidak lengkap atau dinonaktifkan.</p>
                                <ul class="mb-0">
                                    @foreach ($holidaysByIduka as $h)
                                        <li><strong>{{ $h['iduka_nama'] }}</strong>: {{ implode(', ', $h['holidays']) }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Stats Overview Cards -->
            <div class="row g-4 mb-5">
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-4 p-3 me-3">
                                    <i class="fas fa-user-check fa-2x" style="color: #16a085;"></i>
                                </div>
                                <div>
                                    <h3 class="mb-1 fw-bold text-dark">{{ $hadirHariIni }}</h3>
                                    <p class="text-secondary mb-0 small">Hadir Hari Ini</p>
                                    @if (!empty($holidaysByIduka) && count($holidaysByIduka) > 0)
                                        <p class="text-warning mt-2 mb-0 small">
                                            <i class="fas fa-calendar-day"></i>
                                            Beberapa IDUKA libur hari ini
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 cursor-pointer" data-bs-toggle="modal"
                        data-bs-target="#modalBelumDikonfirmasi">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-4 p-3 me-3">
                                    <i class="fas fa-user-clock fa-2x" style="color: #f39c12;"></i>
                                </div>
                                <div>
                                    <h3 class="mb-1 fw-bold text-dark">{{ $belumDikonfirmasi }}</h3>
                                    <p class="text-secondary mb-0 small">Belum Dikonfirmasi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 cursor-pointer" data-bs-toggle="modal"
                        data-bs-target="#modalBelumAbsen">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-4 p-3 me-3">
                                    <i class="fas fa-user-times fa-2x" style="color: #e67e22;"></i>
                                </div>
                                <div>
                                    <h3 class="mb-1 fw-bold text-dark">{{ $tidakHadir }}</h3>
                                    <p class="text-secondary mb-0 small">Belum Absen</p>
                                    @if (!empty($holidaysByIduka) && count($holidaysByIduka) > 0)
                                        <p class="text-warning mt-2 mb-0 small">
                                            <i class="fas fa-info-circle"></i>
                                            Siswa di IDUKA yang libur tidak perlu absen
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row g-4 mb-5">
                <!-- Grafik Kehadiran -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 py-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-semibold text-dark">Grafik Kehadiran</h5>
                                <div class="dropdown">
                                    <button id="chartFilterBtn" class="btn btn-sm btn-light border dropdown-toggle"
                                        type="button" data-bs-toggle="dropdown">
                                        7 Hari Terakhir
                                    </button>
                                    <ul class="dropdown-menu shadow-sm">
                                        <li><a class="dropdown-item chart-filter" href="#" data-filter="today">Hari
                                                Ini</a></li>
                                        <li><a class="dropdown-item chart-filter" href="#" data-filter="7">7 Hari
                                                Terakhir</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <canvas id="attendanceChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Distribusi per Kelas -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 py-4">
                            <h5 class="mb-0 fw-semibold text-dark">Distribusi Kelas</h5>
                        </div>
                        <div class="card-body p-4">
                            <canvas id="majorChart" width="200" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analisis Kehadiran per Kelas -->
            <div class="row g-4 mb-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0 fw-semibold text-dark">Analisis Kehadiran per Kelas</h5>
                                    <p class="text-secondary mb-0 small">Tingkat kehadiran siswa per kelas hari ini</p>
                                    @if (!empty($holidaysByIduka) && count($holidaysByIduka) > 0)
                                        <p class="text-warning mb-0 small mt-1">
                                            <i class="fas fa-info-circle"></i>
                                            Beberapa siswa di IDUKA yang libur tidak perlu melakukan absensi hari ini
                                        </p>
                                    @endif
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-outline-primary" id="refreshAnalisis">
                                        <i class="bx bx-refresh"></i> Refresh
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4" id="analisisKehadiranContainer">
                            @if (count($kelasAnalisis) > 0)
                                <div class="row g-4">
                                    @foreach ($kelasAnalisis as $index => $kelas)
                                        <div class="col-lg-2 col-md-4 col-6">
                                            <div class="kelas-card h-100 p-3 bg-light rounded-4 shadow-sm transition-all cursor-pointer"
                                                data-bs-toggle="modal" data-bs-target="#modalDetailKelas"
                                                data-kelas="{{ $kelas['kelas'] }}"
                                                data-total="{{ $kelas['total_siswa'] }}"
                                                data-sudah-absen="{{ $kelas['sudah_absen'] }}"
                                                data-belum-absen="{{ $kelas['belum_absen'] }}"
                                                data-belum-divalidasi="{{ $kelas['belum_divalidasi'] }}"
                                                data-hadir="{{ $kelas['hadir_count'] }}"
                                                data-ijin="{{ $kelas['ijin_count'] }}"
                                                data-sakit="{{ $kelas['sakit_count'] }}"
                                                data-dinas="{{ $kelas['dinas_count'] }}"
                                                data-persentase="{{ $kelas['persentase'] }}">
                                                <div class="text-center">
                                                    <h6 class="mb-2 fw-semibold text-dark">{{ $kelas['kelas'] }}</h6>

                                                    <!-- Progress Circle -->
                                                    <div class="position-relative d-inline-block mb-2">
                                                        <svg class="progress-circle" width="80" height="80">
                                                            <circle class="progress-circle-bg" cx="40"
                                                                cy="40" r="35" fill="none" stroke="#e9ecef"
                                                                stroke-width="8"></circle>
                                                            <circle class="progress-circle-fill" cx="40"
                                                                cy="40" r="35" fill="none"
                                                                stroke="{{ $kelas['persentase'] >= 90 ? '#27ae60' : ($kelas['persentase'] >= 85 ? '#f39c12' : '#e74c3c') }}"
                                                                stroke-width="8"
                                                                stroke-dasharray="{{ 2 * 3.14159 * 35 }}"
                                                                stroke-dashoffset="{{ 2 * 3.14159 * 35 * (1 - $kelas['persentase'] / 100) }}"
                                                                stroke-linecap="round" transform="rotate(-90 40 40)">
                                                            </circle>
                                                        </svg>
                                                        <div class="position-absolute top-50 start-50 translate-middle">
                                                            <span class="fw-bold"
                                                                style="font-size: 0.9rem;">{{ $kelas['persentase'] }}%</span>
                                                        </div>
                                                    </div>

                                                    <!-- Stats Summary -->
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <div class="text-start">
                                                            <div class="small text-muted">Total</div>
                                                            <div class="fw-bold">{{ $kelas['total_siswa'] }}</div>
                                                        </div>
                                                        <div class="text-center">
                                                            <div class="small text-muted">Hadir</div>
                                                            <div class="fw-bold text-success">{{ $kelas['hadir_count'] }}
                                                            </div>
                                                        </div>
                                                        <div class="text-end">
                                                            <div class="small text-muted">Pending</div>
                                                            <div class="fw-bold text-warning">
                                                                {{ $kelas['belum_divalidasi'] }}</div>
                                                        </div>
                                                    </div>

                                                    <div class="progress mt-2" style="height: 4px;">
                                                        <div class="progress-bar bg-success" role="progressbar"
                                                            style="width: {{ ($kelas['hadir_count'] / max($kelas['total_siswa'], 1)) * 100 }}%;"
                                                            aria-valuenow="{{ $kelas['hadir_count'] }}" aria-valuemin="0"
                                                            aria-valuemax="{{ $kelas['total_siswa'] }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    Tidak ada data kelas untuk ditampilkan.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Absensi per Kelas -->
            <div class="row g-4 mb-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0 fw-semibold text-dark">Detail Absensi per Kelas</h5>
                                    <p class="text-secondary mb-0 small">Data kehadiran siswa per kelas hari ini</p>
                                    @if (!empty($holidaysByIduka) && count($holidaysByIduka) > 0)
                                        <div class="alert alert-warning py-2 px-3 mt-2 mb-0">
                                            <i class="fas fa-calendar-day me-2"></i>
                                            <span class="small">IDUKA yang libur hari ini:</span>
                                            <ul class="mb-0 mt-1 small">
                                                @foreach ($holidaysByIduka as $h)
                                                    <li><strong>{{ $h['iduka_nama'] }}</strong>:
                                                        {{ implode(', ', $h['holidays']) }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-primary" id="refreshBtn">
                                        <i class="bx bx-refresh"></i> Refresh Data
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <!-- Tab untuk setiap kelas -->
                            <ul class="nav nav-tabs mb-4" id="kelasTabs" role="tablist">
                                @foreach ($detailAbsensiPerKelas as $index => $kelas)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{ $index == 0 ? 'active' : '' }}"
                                            id="kelas-{{ $index }}-tab" data-bs-toggle="tab"
                                            data-bs-target="#kelas-{{ $index }}" type="button" role="tab">
                                            {{ $kelas['kelas'] }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>

                            <!-- Tab content -->
                            <div class="tab-content" id="kelasTabsContent">
                                @foreach ($detailAbsensiPerKelas as $index => $kelas)
                                    <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}"
                                        id="kelas-{{ $index }}" role="tabpanel">
                                        <div class="table-responsive text-nowrap">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>NIS</th>
                                                        <th>Nama Siswa</th>
                                                        <th>Status</th>
                                                        <th>Jam Masuk</th>
                                                        <th>Jam Pulang</th>
                                                        <th>Keterangan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($kelas['siswa'] as $key => $siswa)
                                                        <tr>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td>{{ $siswa['nis'] }}</td>
                                                            <td>{{ $siswa['nama'] }}</td>
                                                            <td>
                                                                @if ($siswa['status'] == 'hadir')
                                                                    <span class="badge bg-success">Hadir</span>
                                                                @elseif($siswa['status'] == 'tepat_waktu')
                                                                    <span class="badge bg-success">Tepat Waktu</span>
                                                                @elseif($siswa['status'] == 'terlambat')
                                                                    <span class="badge bg-warning">Terlambat</span>
                                                                @elseif($siswa['status'] == 'izin')
                                                                    <span class="badge bg-info">Izin</span>
                                                                @elseif($siswa['status'] == 'sakit')
                                                                    <span class="badge bg-warning">Sakit</span>
                                                                @elseif($siswa['status'] == 'alfa')
                                                                    <span class="badge bg-danger">Alfa</span>
                                                                @elseif($siswa['status'] == 'dinas')
                                                                    <span class="badge bg-primary">Dinas</span>
                                                                @else
                                                                    <span class="badge bg-secondary">Belum Absen</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $siswa['jam_masuk'] ? $siswa['jam_masuk']->format('H:i') : '-' }}
                                                            </td>
                                                            <td>{{ $siswa['jam_pulang'] ? $siswa['jam_pulang']->format('H:i') : '-' }}
                                                            </td>
                                                            <td>{{ $siswa['keterangan'] }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DETAIL KELAS -->
    <div class="modal fade" id="modalDetailKelas" tabindex="-1" aria-labelledby="modalDetailKelasLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalDetailKelasLabel">
                        Detail Kehadiran Kelas: <span id="modalKelasNama"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Ringkasan Statistik -->
                    <div class="row mb-4">
                        <div class="col-md-3 col-6 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="text-primary mb-2">
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                    <h5 class="mb-0 fw-bold" id="modalTotalSiswa">0</h5>
                                    <p class="text-muted mb-0 small">Total Siswa</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="text-success mb-2">
                                        <i class="fas fa-user-check fa-2x"></i>
                                    </div>
                                    <h5 class="mb-0 fw-bold" id="modalSudahAbsen">0</h5>
                                    <p class="text-muted mb-0 small">Sudah Absen</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="text-danger mb-2">
                                        <i class="fas fa-user-times fa-2x"></i>
                                    </div>
                                    <h5 class="mb-0 fw-bold" id="modalBelumAbsenCount">0</h5>
                                    <p class="text-muted mb-0 small">Belum Absen</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="text-warning mb-2">
                                        <i class="fas fa-user-clock fa-2x"></i>
                                    </div>
                                    <h5 class="mb-0 fw-bold" id="modalBelumDivalidasi">0</h5>
                                    <p class="text-muted mb-0 small">Belum Divalidasi</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Kategori -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Detail Kategori Kehadiran</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas fa-check text-success"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">Hadir</div>
                                            <div class="text-muted small" id="modalHadirCount">0 siswa</div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas fa-file-alt text-info"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">Izin</div>
                                            <div class="text-muted small" id="modalIjinCount">0 siswa</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas fa-thermometer-half text-warning"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">Sakit</div>
                                            <div class="text-muted small" id="modalSakitCount">0 siswa</div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas fa-briefcase text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">Dinas Luar</div>
                                            <div class="text-muted small" id="modalDinasCount">0 siswa</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="fw-semibold mb-3">Tingkat Kehadiran</h6>
                            <div class="d-flex justify-content-between mb-1">
                                <span id="modalPersentaseLabel">0%</span>
                                <span class="text-muted">Target: 90%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar" id="modalPersentaseBar" role="progressbar" style="width: 0%;"
                                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL UNTUK SISWA BELUM DIKONFIRMASI -->
    <div class="modal fade" id="modalBelumDikonfirmasi" tabindex="-1" aria-labelledby="modalBelumDikonfirmasiLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="modalBelumDikonfirmasiLabel">
                        Daftar Siswa Belum Dikonfirmasi Hari Ini
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <span class="badge bg-warning fs-5" id="countBelumDikonfirmasi">0 SISWA</span>
                    </div>

                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>NAMA</th>
                                    <th>IDUKA</th>
                                    <th>PEMBIMBING</th>
                                    <th>JENIS</th>
                                    <th>KETERANGAN</th>
                                    <th>WAKTU ABSEN</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyBelumDikonfirmasi">
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="spinner-border text-warning" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2">Memuat data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="errorMessageBelumDikonfirmasi" class="text-center py-4 d-none">
                        <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                        <p class="mb-0">Gagal memuat data. Silakan coba lagi.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL UNTUK SISWA BELUM ABSEN -->
    <div class="modal fade" id="modalBelumAbsen" tabindex="-1" aria-labelledby="modalBelumAbsenLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalBelumAbsenLabel">
                        Daftar Siswa Tidak Hadir Hari Ini
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <span class="badge bg-danger fs-5" id="countBelumAbsen">0 SISWA</span>
                    </div>

                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>NAMA</th>
                                    <th>IDUKA</th>
                                    <th>PEMBIMBING</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyBelumAbsen">
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <div class="spinner-border text-danger" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2">Memuat data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="errorMessage" class="text-center py-4 d-none">
                        <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                        <p class="mb-0">Gagal memuat data. Silakan coba lagi.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS -->
    <style>
        .card {
            border-radius: 16px;
            transition: all 0.3s ease;
            border: none !important;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12) !important;
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }

        .text-secondary {
            color: #6c757d !important;
        }

        .rounded-4 {
            border-radius: 12px !important;
        }

        .btn {
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-dark {
            background-color: #212529;
            border-color: #212529;
        }

        .btn-light {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: #495057;
        }

        .badge {
            font-weight: 500;
            padding: 0.5em 1em;
        }

        .bg-dark {
            background-color: #212529 !important;
        }

        .dropdown-menu {
            border-radius: 12px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.15);
            border: none;
        }

        .list-group-item {
            transition: background-color 0.2s ease;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        h6 {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .small {
            font-size: 0.85rem;
        }

        .shadow-sm {
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08) !important;
        }

        .fw-bold {
            font-weight: 700 !important;
        }

        .fw-semibold {
            font-weight: 600 !important;
        }

        /* Tambahan CSS untuk tab dan tabel */
        .nav-tabs .nav-link {
            color: #6c757d;
            font-weight: 500;
            border: none;
            border-bottom: 2px solid transparent;
            border-radius: 0;
            padding: 0.75rem 1rem;
        }

        .nav-tabs .nav-link:hover {
            border-color: transparent;
            color: #212529;
        }

        .nav-tabs .nav-link.active {
            color: #212529;
            background-color: transparent;
            border-bottom: 2px solid #212529;
        }

        .table thead th {
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.03);
        }

        .badge {
            font-size: 0.75em;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 8px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            border-bottom: none;
            padding: 1rem;
        }

        .modal-footer {
            border-top: none;
            padding: 1rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: #495057;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background-color: #f8f9fa;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.03);
            cursor: pointer;
        }

        .btn-close:focus {
            box-shadow: none;
        }

        /* Animasi untuk modal */
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
            transform: translate(0, -50px);
        }

        .modal.show .modal-dialog {
            transform: translate(0, 0);
        }

        /* Spinner styling */
        .spinner-border {
            width: 3rem;
            height: 3rem;
        }

        /* Kelas Card Styles */
        .kelas-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .kelas-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }

        .progress-circle {
            transform: rotate(-90deg);
        }

        .progress-circle-bg {
            fill: none;
            stroke-width: 8;
        }

        .progress-circle-fill {
            fill: none;
            stroke-width: 8;
            stroke-linecap: round;
            transition: stroke-dashoffset 1s ease-in-out;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .cursor-pointer {
            cursor: pointer;
        }
    </style>

    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let attendanceChart;
            let majorChart;

            // ====== Grafik Absensi Harian (Line Chart) ======
            function loadAttendanceChart(filter = '7') {
                fetch(`/absensi/chart-data?filter=${filter}`)
                    .then(res => res.json())
                    .then(data => {
                        const ctx = document.getElementById('attendanceChart').getContext('2d');

                        // Hapus chart lama kalau ada
                        if (attendanceChart) {
                            attendanceChart.destroy();
                        }

                        attendanceChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: data.labels,
                                datasets: [{
                                    label: 'Hadir',
                                    data: data.values,
                                    borderColor: '#212529',
                                    backgroundColor: 'rgba(33, 37, 41, 0.1)',
                                    borderWidth: 3,
                                    fill: true,
                                    tension: 0.4,
                                    pointBackgroundColor: '#212529',
                                    pointBorderWidth: 0,
                                    pointRadius: 6
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: {
                                            display: false
                                        },
                                        border: {
                                            display: false
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        grid: {
                                            color: '#f1f3f4',
                                            drawBorder: false
                                        },
                                        border: {
                                            display: false
                                        },
                                        ticks: {
                                            color: '#6c757d'
                                        }
                                    }
                                },
                                elements: {
                                    point: {
                                        hoverRadius: 8
                                    }
                                }
                            }
                        });
                    });
            }

            // Default tampil "7 Hari Terakhir"
            loadAttendanceChart('7');

            // Klik dropdown filter
            document.querySelectorAll('.chart-filter').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const filter = this.dataset.filter;
                    document.getElementById('chartFilterBtn').innerText = this.innerText;
                    loadAttendanceChart(filter);
                });
            });

            // ====== Grafik Distribusi per Kelas (Doughnut Chart) ======
            function loadKelasChart() {
                const data = {
                    labels: @json($kelasLabels),
                    values: @json($kelasValues)
                };

                const ctx = document.getElementById('majorChart').getContext('2d');

                // Hapus chart lama kalau ada
                if (majorChart) {
                    majorChart.destroy();
                }

                majorChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            data: data.values,
                            backgroundColor: [
                                '#3498db', '#16a085', '#e67e22', '#8e44ad',
                                '#27ae60', '#f39c12', '#e74c3c', '#9b59b6'
                            ],
                            borderWidth: 0,
                            cutout: '65%'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 15,
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });
            }

            loadKelasChart();

            // ====== Animasi Kartu Dashboard ======
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // ====== Refresh Data ======
            document.getElementById('refreshBtn').addEventListener('click', function() {
                location.reload();
            });

            // Refresh data analisis
            document.getElementById('refreshAnalisis')?.addEventListener('click', function() {
                fetch(window.location.href)
                    .then(response => response.text())
                    .then(html => {
                        // Buat DOM parser
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        // Ambil elemen analisis kehadiran dari response
                        const newAnalisis = doc.querySelector('#analisisKehadiranContainer');

                        // Ganti elemen saat ini dengan yang baru
                        if (newAnalisis) {
                            document.getElementById('analisisKehadiranContainer').innerHTML =
                                newAnalisis.innerHTML;

                            // Reinitialize event listeners for kelas cards
                            initializeKelasCards();
                        }
                    })
                    .catch(error => {
                        console.error('Error refreshing data:', error);
                        // Alternatif: reload halaman
                        window.location.reload();
                    });
            });

            // ====== Modal Detail Kelas ======
            const modalDetailKelas = document.getElementById('modalDetailKelas');

            // Event saat modal ditampilkan
            modalDetailKelas.addEventListener('show.bs.modal', function(event) {
                // Button that triggered the modal
                const button = event.relatedTarget;

                // Extract info from data-bs-* attributes


                const total = button.getAttribute('data-total');
                const sudahAbsen = button.getAttribute('data-sudah-absen');
                const belumAbsen = button.getAttribute('data-belum-absen');
                const belumDivalidasi = button.getAttribute('data-belum-divalidasi');
                const hadir = button.getAttribute('data-hadir');
                const ijin = button.getAttribute('data-ijin');
                const sakit = button.getAttribute('data-sakit');
                const dinas = button.getAttribute('data-dinas');
                const persentase = button.getAttribute('data-persentase');

                // Update the modal's content
                document.getElementById('modalKelasNama').textContent = kelas;
                document.getElementById('modalTotalSiswa').textContent = total;
                document.getElementById('modalSudahAbsen').textContent = sudahAbsen;
                document.getElementById('modalBelumAbsenCount').textContent = belumAbsen;
                document.getElementById('modalBelumDivalidasi').textContent = belumDivalidasi;
                document.getElementById('modalHadirCount').textContent = hadir + ' siswa';
                document.getElementById('modalIjinCount').textContent = ijin + ' siswa';
                document.getElementById('modalSakitCount').textContent = sakit + ' siswa';
                document.getElementById('modalDinasCount').textContent = dinas + ' siswa';
                document.getElementById('modalPersentaseLabel').textContent = persentase + '%';

                // Update progress bar
                const progressBar = document.getElementById('modalPersentaseBar');
                progressBar.style.width = persentase + '%';
                progressBar.setAttribute('aria-valuenow', persentase);

                // Set progress bar color based on percentage
                if (persentase >= 90) {
                    progressBar.classList.remove('bg-warning', 'bg-danger');
                    progressBar.classList.add('bg-success');
                } else if (persentase >= 85) {
                    progressBar.classList.remove('bg-success', 'bg-danger');
                    progressBar.classList.add('bg-warning');
                } else {
                    progressBar.classList.remove('bg-success', 'bg-warning');
                    progressBar.classList.add('bg-danger');
                }
            });

            // Initialize kelas cards event listeners
            function initializeKelasCards() {
                // This function will be called after refresh to reinitialize event listeners
                // The event listeners are already set up with data-bs-toggle and data-bs-target attributes
                console.log('Kelas cards initialized');
            }

            // Initialize on page load
            initializeKelasCards();

            // Fungsi untuk memuat data siswa belum dikonfirmasi
            function loadSiswaBelumDikonfirmasi() {
                // Tampilkan loading
                document.getElementById('tbodyBelumDikonfirmasi').innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="spinner-border text-warning" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat data...</p>
                    </td>
                </tr>
            `;

                // Sembunyikan pesan error
                document.getElementById('errorMessageBelumDikonfirmasi').classList.add('d-none');

                fetch("{{ route('kaprog.siswa-belum-dikonfirmasi') }}")
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        let tbody = document.getElementById('tbodyBelumDikonfirmasi');
                        let countElement = document.getElementById('countBelumDikonfirmasi');

                        // Update count
                        countElement.textContent = `${data.length} SISWA`;

                        // Kosongkan tbody
                        tbody.innerHTML = '';

                        if (data.length === 0) {
                            tbody.innerHTML = `
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-check-circle text-success fa-3x mb-3 d-block"></i>
                                    <p class="mb-0">Tidak ada siswa yang perlu dikonfirmasi hari ini</p>
                                </td>
                            </tr>
                        `;
                            return;
                        }

                        // Tambahkan data ke tabel
                        data.forEach(siswa => {
                            let row = `
                            <tr>
                                <td>${siswa.no}</td>
                                <td>${siswa.name}</td>
                                <td>${siswa.iduka}</td>
                                <td>${siswa.pembimbing}</td>
                                <td>
                                    <span class="badge ${siswa.jenis === 'Absensi' ? 'bg-warning' : (siswa.jenis === 'Izin' ? 'bg-info' : 'bg-primary')}">${siswa.jenis}</span>
                                </td>
                                <td>${siswa.keterangan}</td>
                                <td>${siswa.waktu_absen}</td>
                            </tr>
                        `;
                            tbody.innerHTML += row;
                        });

                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Tampilkan pesan error
                        document.getElementById('tbodyBelumDikonfirmasi').innerHTML = '';
                        document.getElementById('errorMessageBelumDikonfirmasi').classList.remove('d-none');

                        // Update count
                        document.getElementById('countBelumDikonfirmasi').textContent = '0 SISWA';
                    });
            }

            // Fungsi untuk memuat data siswa belum absen
            function loadSiswaBelumAbsen() {
                console.log('Memulai loadSiswaBelumAbsen');

                // Tampilkan loading
                document.getElementById('tbodyBelumAbsen').innerHTML = `
                <tr>
                    <td colspan="4" class="text-center py-4">
                        <div class="spinner-border text-danger" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat data...</p>
                    </td>
                </tr>
            `;

                // Sembunyikan pesan error
                document.getElementById('errorMessage').classList.add('d-none');

                // Menggunakan URL yang benar untuk route
                fetch("{{ route('kaprog.siswa-belum-absen') }}")
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error('Network response was not ok: ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Data received:', data);

                        let tbody = document.getElementById('tbodyBelumAbsen');
                        let countElement = document.getElementById('countBelumAbsen');

                        // Update count
                        countElement.textContent = `${data.length} SISWA`;

                        // Kosongkan tbody
                        tbody.innerHTML = '';

                        if (data.length === 0) {
                            tbody.innerHTML = `
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <i class="fas fa-check-circle text-success fa-3x mb-3 d-block"></i>
                                    <p class="mb-0">Semua siswa sudah melakukan absensi hari ini</p>
                                </td>
                            </tr>
                        `;
                            return;
                        }

                        // Tambahkan data ke tabel
                        data.forEach(siswa => {
                            let row = `
                            <tr>
                                <td>${siswa.no}</td>
                                <td>${siswa.name}</td>
                                <td>${siswa.iduka || '-'}</td>
                                <td>${siswa.pembimbing || '-'}</td>
                            </tr>
                        `;
                            tbody.innerHTML += row;
                        });

                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Tampilkan pesan error
                        document.getElementById('tbodyBelumAbsen').innerHTML = '';
                        document.getElementById('errorMessage').classList.remove('d-none');

                        // Update count
                        document.getElementById('countBelumAbsen').textContent = '0 SISWA';
                    });
            }

            // Event saat modal dibuka - Siswa Belum Dikonfirmasi
            const modalBelumDikonfirmasi = document.getElementById('modalBelumDikonfirmasi');
            if (modalBelumDikonfirmasi) {
                modalBelumDikonfirmasi.addEventListener('show.bs.modal', function(event) {
                    loadSiswaBelumDikonfirmasi();
                });
            }

            // Event saat modal dibuka - Siswa Belum Absen
            const modalBelumAbsen = document.getElementById('modalBelumAbsen');
            if (modalBelumAbsen) {
                modalBelumAbsen.addEventListener('show.bs.modal', function(event) {
                    loadSiswaBelumAbsen();
                });
            }
        });
    </script>
@endsection
