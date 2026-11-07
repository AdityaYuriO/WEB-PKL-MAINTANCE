@extends('layout.main')

@section('content')
<div class="container-fluid py-3 px-2 px-md-3">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">

            {{-- Session Notifications --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-3">

                {{-- Sidebar / Mobile Top Nav --}}
                <div class="col-12 col-lg-3">
                    <div class="card border-0 rounded-4 shadow-sm card-hover mb-3 mb-lg-4">
                        <div class="card-header py-2 py-md-3">
                            <h5 class="card-title mb-0 fs-6 fs-md-5">Menu Konfirmasi</h5>
                        </div>
                        <div class="card-body p-2 p-md-3">
                            {{-- Desktop: vertical pills | Mobile: horizontal scroll --}}
                            <div class="nav nav-pills gap-2 flex-row flex-lg-column flex-nowrap overflow-auto nav-scroll"
                                 id="v-pills-tab" role="tablist" aria-orientation="vertical">

                                <button class="nav-link active rounded-pill text-start px-3 px-md-4 flex-shrink-0 text-nowrap"
                                        id="v-pills-hari-ini-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-hari-ini" type="button" role="tab"
                                        aria-controls="v-pills-hari-ini" aria-selected="true">
                                    <i class="fas fa-calendar-day me-2"></i>Absensi Hari Ini
                                </button>

                                <button class="nav-link rounded-pill text-start px-3 px-md-4 flex-shrink-0 text-nowrap d-flex justify-content-between align-items-center gap-2"
                                        id="v-pills-absen-pending-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-absen-pending" type="button" role="tab"
                                        aria-controls="v-pills-absen-pending" aria-selected="false">
                                    <span><i class="fas fa-clock me-2"></i>Absensi Pending</span>
                                    <span class="badge bg-warning rounded-pill" id="badge-absen-pending">{{ $absensiPending->count() }}</span>
                                </button>

                                <button class="nav-link rounded-pill text-start px-3 px-md-4 flex-shrink-0 text-nowrap d-flex justify-content-between align-items-center gap-2"
                                        id="v-pills-perlu-konfirmasi-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-perlu-konfirmasi" type="button" role="tab"
                                        aria-controls="v-pills-perlu-konfirmasi" aria-selected="false">
                                    <span><i class="fas fa-exclamation-circle me-2"></i>Izin Menunggu</span>
                                    <span class="badge bg-danger rounded-pill" id="badge-pending">{{ $izinPending->count() }}</span>
                                </button>

                                <button class="nav-link rounded-pill text-start px-3 px-md-4 flex-shrink-0 text-nowrap d-flex justify-content-between align-items-center gap-2"
                                        id="v-pills-dinas-pending-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-dinas-pending" type="button" role="tab"
                                        aria-controls="v-pills-dinas-pending" aria-selected="false">
                                    <span><i class="fas fa-briefcase me-2"></i>Dinas Luar</span>
                                    <span class="badge bg-primary rounded-pill" id="badge-dinas-pending">{{ $dinasPending->count() }}</span>
                                </button>

                                <button class="nav-link rounded-pill text-start px-3 px-md-4 flex-shrink-0 text-nowrap"
                                        id="v-pills-riwayat-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-riwayat" type="button" role="tab"
                                        aria-controls="v-pills-riwayat" aria-selected="false">
                                    <i class="fas fa-history me-2"></i>Riwayat Absen
                                </button>

                                <button class="nav-link rounded-pill text-start px-3 px-md-4 flex-shrink-0 text-nowrap"
                                        id="v-pills-statistik-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-statistik" type="button" role="tab"
                                        aria-controls="v-pills-statistik" aria-selected="false">
                                    <i class="fas fa-chart-pie me-2"></i>Statistik Kehadiran
                                </button>

                                <button class="nav-link rounded-pill text-start px-3 px-md-4 flex-shrink-0 text-nowrap"
                                        id="v-pills-kordinat-tab" data-bs-toggle="pill"
                                        data-bs-target="#v-pills-kordinat" type="button" role="tab"
                                        aria-controls="v-pills-kordinat" aria-selected="false">
                                    <i class="fas fa-map-marker-alt me-2 text-primary"></i>Koordinat / Lokasi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Main Content --}}
                <div class="col-12 col-lg-9">
                    <div class="tab-content" id="v-pills-tabContent">

                        {{-- ============ TAB: ABSEN HARI INI ============ --}}
                        <div class="tab-pane fade show active" id="v-pills-hari-ini" role="tabpanel" aria-labelledby="v-pills-hari-ini-tab">
                            <div class="card border-0 rounded-4 shadow-sm card-hover mb-4">
                                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                                    <h5 class="card-title mb-0 fs-6 fs-md-5">Absensi Hari Ini</h5>
                                    <span class="badge bg-primary" id="tanggal-hari-ini">{{ date('d-m-Y') }}</span>
                                </div>
                                <div class="card-body p-2 p-md-3">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle" id="tabel-absen-hari-ini">
                                            <thead>
                                                <tr>
                                                    <th>Nama Siswa</th>
                                                    <th>Masuk</th>
                                                    <th>Pulang</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($absensiHariIni as $absensi)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <img src="{{ asset('snet/assets/img/avatars/1.png') }}"
                                                                     alt="Avatar" class="rounded-circle me-2 flex-shrink-0"
                                                                     width="32" height="32">
                                                                <div class="ms-1 ms-md-2 text-truncate">
                                                                    <div class="fw-semibold text-truncate">{{ $absensi->user->name }}</div>
                                                                    <div class="text-muted small text-truncate">{{ $absensi->user->email }}</div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if ($absensi->jam_masuk)
                                                                <span class="badge bg-success">{{ \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') }} WIB</span>
                                                            @elseif ($absensi->status_dinas === 'disetujui')
                                                                <span class="badge bg-primary">{{ \Carbon\Carbon::parse($absensi->created_at)->format('d-m-Y H:i') }} WIB</span>
                                                            @else
                                                                <span class="badge bg-secondary">-</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($absensi->jam_pulang)
                                                                <span class="badge bg-info">{{ \Carbon\Carbon::parse($absensi->jam_pulang)->format('H:i') }} WIB</span>
                                                            @else
                                                                <span class="badge bg-secondary">-</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge {{ $absensi->status === 'tepat_waktu' ? 'bg-success' : ($absensi->status === 'terlambat' ? 'bg-warning' : ($absensi->status === 'izin' ? 'bg-info' : ($absensi->status === 'ditolak' ? 'bg-danger' : 'bg-secondary'))) }}">
                                                                {{ ucfirst(str_replace('_', ' ', $absensi->status)) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">Belum ada data absensi hari ini</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ============ TAB: ABSENSI PENDING ============ --}}
                        <div class="tab-pane fade" id="v-pills-absen-pending" role="tabpanel" aria-labelledby="v-pills-absen-pending-tab">
                            <div class="card border-0 rounded-4 shadow-sm card-hover mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0 fs-6 fs-md-5">Absensi Menunggu Konfirmasi</h5>
                                </div>
                                <div class="card-body p-2 p-md-3">
                                    <form method="POST" action="{{ route('iduka.konfirmasi-banyak-absen') }}" id="formKonfirmasiBanyak">
                                        @csrf
                                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                                            <div class="form-check">
                                                <input type="checkbox" id="check-all" class="form-check-input">
                                                <label for="check-all" class="form-check-label ms-1">Pilih Semua</label>
                                            </div>
                                            <div class="d-flex flex-wrap gap-2">
                                                <button type="submit" name="status" value="disetujui"
                                                        class="btn btn-sm btn-success rounded-pill px-3 shadow-sm">
                                                    <i class="bi bi-check-lg"></i> <span class="d-none d-sm-inline">Setujui yang Dipilih</span>
                                                    <span class="d-inline d-sm-none">Setujui</span>
                                                </button>
                                                <button type="submit" name="status" value="ditolak"
                                                        class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm">
                                                    <i class="bi bi-x-lg"></i> <span class="d-none d-sm-inline">Tolak yang Dipilih</span>
                                                    <span class="d-inline d-sm-none">Tolak</span>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle">
                                                <thead>
                                                    <tr>
                                                        <th width="50">
                                                            <input type="checkbox" id="check-all-header" class="form-check-input">
                                                        </th>
                                                        <th>Nama Siswa</th>
                                                        <th>Tanggal</th>
                                                        <th>Jenis</th>
                                                        <th>Waktu</th>
                                                        <th>Status</th>
                                                        <th width="120" class="text-center">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody-absensi-pending">
                                                    @forelse($absensiPending as $absensi)
                                                        <tr id="row-pending-{{ $absensi->id }}">
                                                            <td>
                                                                <input type="checkbox" name="absen_ids[]" value="{{ $absensi->id }}"
                                                                       class="form-check-input absen-check" data-id="{{ $absensi->id }}">
                                                            </td>
                                                            <td class="text-nowrap">{{ $absensi->user->name }}</td>
                                                            <td class="text-nowrap">{{ \Carbon\Carbon::parse($absensi->tanggal)->format('d-m-Y') }}</td>
                                                            <td>
                                                                <span class="badge {{ $absensi->jenis === 'masuk' ? 'bg-success' : 'bg-info' }}">
                                                                    {{ ucfirst($absensi->jenis) }}
                                                                </span>
                                                            </td>
                                                            <td class="text-nowrap">{{ \Carbon\Carbon::parse($absensi->jam)->format('H:i') }} WIB</td>
                                                            <td>
                                                                @if ($absensi->dikonfirmasi_oleh)
                                                                    <span class="badge bg-info">
                                                                        {{ $absensi->dikonfirmasi_oleh === 'keduanya' ? 'IDUKA & Guru' : ucfirst($absensi->dikonfirmasi_oleh) }}
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-warning">Belum dikonfirmasi</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="d-flex gap-2 justify-content-center">
                                                                    <button type="button" class="btn btn-sm btn-success rounded-pill px-2 px-md-3 shadow-sm"
                                                                            onclick="konfirmasiAbsensi({{ $absensi->id }}, 'disetujui')" title="Setujui">
                                                                        <i class="bi bi-check-lg"></i>
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm btn-danger rounded-pill px-2 px-md-3 shadow-sm"
                                                                            onclick="konfirmasiAbsensi({{ $absensi->id }}, 'ditolak')" title="Tolak">
                                                                        <i class="bi bi-x-lg"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr id="no-data-pending">
                                                            <td colspan="7" class="text-center text-muted">Tidak ada absensi yang perlu dikonfirmasi</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

{{-- ============ TAB: DINAS LUAR ============ --}}
<div class="tab-pane fade" id="v-pills-dinas-pending" role="tabpanel" aria-labelledby="v-pills-dinas-pending-tab">
    <div class="card border-0 rounded-4 shadow-sm card-hover mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0 fs-6 fs-md-5">Dinas Luar Perlu Konfirmasi</h5>
        </div>
        <div class="card-body p-2 p-md-3">

            {{-- Info scroll untuk mobile --}}
            <div class="d-block d-md-none text-muted small mb-2">
                <i class="bi bi-arrow-left-right"></i> Geser tabel ke kiri/kanan untuk melihat semua kolom
            </div>

            <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                <table class="table table-hover align-middle" style="min-width: 720px;">
                    <thead>
                        <tr>
                            <th style="min-width: 180px;">Nama Siswa</th>
                            <th style="min-width: 110px;">Tanggal</th>
                            <th style="min-width: 110px;">Jenis Dinas</th>
                            <th style="min-width: 180px;">Alasan</th>
                            <th style="min-width: 90px;">Status</th>
                            <th style="min-width: 100px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dinasPending as $dinas)
                            <tr data-dinas-id="{{ $dinas->id }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('snet/assets/img/avatars/1.png') }}"
                                             alt="Avatar" class="rounded-circle me-2 flex-shrink-0" width="32" height="32">
                                        <div class="ms-1 ms-md-2">
                                            <div class="fw-semibold">{{ $dinas->user->name }}</div>
                                            <div class="text-muted small">{{ $dinas->created_at->format('d-m-Y H:i') }} WIB</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-nowrap">{{ $dinas->tanggal->format('d-m-Y') }}</td>
                                <td>
                                    <span class="badge bg-primary text-nowrap">
                                        {{ ucfirst(str_replace('_', ' ', $dinas->jenis_dinas)) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 220px;" title="{{ $dinas->keterangan }}">
                                        {{ $dinas->keterangan }}
                                    </div>
                                </td>
                                <td><span class="badge bg-warning">Pending</span></td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <button type="button" class="btn btn-sm btn-success rounded-pill px-2 px-md-3 shadow-sm"
                                                onclick="konfirmasiDinas({{ $dinas->id }}, 'disetujui')" title="Setujui">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger rounded-pill px-2 px-md-3 shadow-sm"
                                                onclick="konfirmasiDinas({{ $dinas->id }}, 'ditolak')" title="Tolak">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Tidak ada dinas luar yang perlu dikonfirmasi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

                        {{-- ============ TAB: IZIN ============ --}}
                        <div class="tab-pane fade" id="v-pills-perlu-konfirmasi" role="tabpanel" aria-labelledby="v-pills-perlu-konfirmasi-tab">
                            <div class="card border-0 rounded-4 shadow-sm card-hover mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0 fs-6 fs-md-5">Izin Perlu Konfirmasi</h5>
                                </div>
                                <div class="card-body p-2 p-md-3">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead>
                                                <tr>
                                                    <th>Nama Siswa</th>
                                                    <th>Tanggal</th>
                                                    <th>Jenis</th>
                                                    <th>Alasan</th>
                                                    <th>File</th>
                                                    <th>Status</th>
                                                    <th class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($izinPending as $izin)
                                                    <tr data-izin-id="{{ $izin->id }}">
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <img src="{{ asset('snet/assets/img/avatars/1.png') }}"
                                                                     alt="Avatar" class="rounded-circle me-2 flex-shrink-0" width="32" height="32">
                                                                <div class="ms-1 ms-md-2">
                                                                    <div class="fw-semibold text-truncate">{{ $izin->user->name }}</div>
                                                                    <div class="text-muted small">{{ $izin->created_at->format('d-m-Y H:i') }} WIB</div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-nowrap">
                                                            {{ $izin->tanggal_izin ? $izin->tanggal_izin->format('d-m-Y') : $izin->created_at->format('d-m-Y') }}
                                                        </td>
                                                        <td>
                                                            <span class="badge {{ $izin->jenis_izin === 'sakit' ? 'bg-danger' : ($izin->jenis_izin === 'izin' ? 'bg-info' : 'bg-warning') }}">
                                                                {{ ucfirst($izin->jenis_izin) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="text-truncate d-inline-block" style="max-width: 200px;"
                                                                  title="{{ $izin->alasan ?? $izin->keterangan }}">
                                                                {{ $izin->alasan ?? $izin->keterangan }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @if ($izin->file_pendukung)
                                                                <a href="{{ Storage::url($izin->file_pendukung) }}" target="_blank"
                                                                   class="btn btn-sm btn-outline-secondary rounded-pill px-2 px-md-3">
                                                                    <i class="bi bi-file-earmark"></i>
                                                                </a>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-warning">
                                                                {{ isset($izin->status_label) ? $izin->status_label : 'Pending' }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex gap-2 justify-content-center flex-wrap">
                                                                <button type="button" class="btn btn-sm btn-success rounded-pill px-2 px-md-3 shadow-sm"
                                                                        onclick="konfirmasiIzin({{ $izin->id }}, 'disetujui')" title="Setujui">
                                                                    <i class="bi bi-check-lg"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-danger rounded-pill px-2 px-md-3 shadow-sm"
                                                                        onclick="konfirmasiIzin({{ $izin->id }}, 'ditolak')" title="Tolak">
                                                                    <i class="bi bi-x-lg"></i>
                                                                </button>
                                                                <a href="{{ route('iduka.detail-izin', $izin->id) }}"
                                                                   class="btn btn-sm btn-outline-primary rounded-pill px-2 px-md-3" title="Detail">
                                                                    <i class="bi bi-eye"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center text-muted">Tidak ada izin yang perlu dikonfirmasi</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ============ TAB: RIWAYAT ============ --}}
                        <div class="tab-pane fade" id="v-pills-riwayat" role="tabpanel" aria-labelledby="v-pills-riwayat-tab">
                            <div class="card border-0 rounded-4 shadow-sm card-hover mb-4">
                                <div class="card-header">
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                                        <h5 class="card-title mb-0 fs-6 fs-md-5">Riwayat Absensi</h5>
                                        <form id="filterRiwayatForm" class="d-flex flex-column flex-sm-row gap-2 w-100 w-md-auto">
                                            <input type="date" class="form-control form-control-sm" name="tanggal_dari" id="tanggal_dari">
                                            <input type="date" class="form-control form-control-sm" name="tanggal_sampai" id="tanggal_sampai">
                                            <select class="form-select form-select-sm" name="filter_siswa_riwayat" id="filter_siswa_riwayat" style="min-width: 140px;">
                                                <option value="">Semua Siswa</option>
                                                @foreach ($siswaList as $siswa)
                                                    <option value="{{ $siswa->id }}">{{ $siswa->name }}</option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-outline-secondary btn-sm" type="submit">
                                                <i class="bi bi-funnel"></i> Filter
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body p-2 p-md-3">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead>
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Nama Siswa</th>
                                                    <th>Masuk</th>
                                                    <th>Pulang</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody id="riwayatBody">
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">Gunakan filter untuk melihat data</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ============ TAB: STATISTIK ============ --}}
                        <div class="tab-pane fade" id="v-pills-statistik" role="tabpanel" aria-labelledby="v-pills-statistik-tab">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="card border-0 rounded-4 shadow-sm card-hover mb-4">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0 fs-6 fs-md-5">Statistik Kehadiran Bulan Ini</h5>
                                        </div>
                                        <div class="card-body p-2 p-md-3">
                                            <div class="row g-2 g-md-3 mb-4">
                                                <div class="col-6 col-md-3">
                                                    <div class="card text-center border-0 rounded-4 shadow-sm card-hover h-100">
                                                        <div class="card-body p-3">
                                                            <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                                                            <h4 class="mt-2 mb-1">{{ $statistik['persentase_kehadiran'] }}%</h4>
                                                            <p class="text-muted mb-0 small">Rata-rata Kehadiran</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <div class="card text-center border-0 rounded-4 shadow-sm card-hover h-100">
                                                        <div class="card-body p-3">
                                                            <i class="bi bi-clock text-warning" style="font-size: 2rem;"></i>
                                                            <h4 class="mt-2 mb-1">{{ $statistik['terlambat'] }}</h4>
                                                            <p class="text-muted mb-0 small">Total Keterlambatan</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <div class="card text-center border-0 rounded-4 shadow-sm card-hover h-100">
                                                        <div class="card-body p-3">
                                                            <i class="bi bi-x-circle text-danger" style="font-size: 2rem;"></i>
                                                            <h4 class="mt-2 mb-1">{{ $statistik['alpha'] }}</h4>
                                                            <p class="text-muted mb-0 small">Total Alpha</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <div class="card text-center border-0 rounded-4 shadow-sm card-hover h-100">
                                                        <div class="card-body p-3">
                                                            <i class="bi bi-file-earmark-text text-info" style="font-size: 2rem;"></i>
                                                            <h4 class="mt-2 mb-1">{{ $statistik['izin'] }}</h4>
                                                            <p class="text-muted mb-0 small">Total Izin</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-12 col-md-6">
                                                    <div class="card border-0 rounded-4 shadow-sm card-hover h-100">
                                                        <div class="card-header">
                                                            <h5 class="card-title mb-0 fs-6">Statistik per Siswa</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="chart-container" style="position: relative; height: 250px;">
                                                                <canvas id="chartStatistikSiswa"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="card border-0 rounded-4 shadow-sm card-hover h-100">
                                                        <div class="card-header">
                                                            <h5 class="card-title mb-0 fs-6">Persentase Kehadiran</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="chart-container" style="position: relative; height: 250px;">
                                                                <canvas id="chartPieKehadiran"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ============ TAB: KOORDINAT ============ --}}
                        <div class="tab-pane fade" id="v-pills-kordinat" role="tabpanel" aria-labelledby="v-pills-kordinat-tab">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="card border-0 rounded-4 shadow-sm card-hover mb-4">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0 fs-6 fs-md-5">Tentukan Titik Koordinat</h5>
                                        </div>
                                        <div class="card-body p-2 p-md-3">
                                            <form action="{{ route('iduka.tambah.kordinat') }}" method="POST">
                                                @csrf
                                                <div class="row g-3">
                                                    <div class="col-12 col-md-6">
                                                        <label for="iduka_id" class="form-label">Pilih IDUKA</label>
                                                        <select name="iduka_id" id="iduka_id" class="form-select select2">
                                                            <option value="">-- Pilih IDUKA --</option>
                                                            @foreach ($idukas as $item)
                                                                <option value="{{ $item->id }}"
                                                                    {{ isset($iduka) && $iduka && $iduka->id == $item->id ? 'selected' : '' }}>
                                                                    {{ $item->nama }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label">Radius (meter)</label>
                                                        <input type="text" name="radius" class="form-control"
                                                               value="{{ old('radius', $iduka->radius ?? '') }}">
                                                    </div>

                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label">Latitude</label>
                                                        <input type="text" name="latitude" class="form-control"
                                                               value="{{ old('latitude', $iduka->latitude ?? '') }}">
                                                    </div>

                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label">Longitude</label>
                                                        <input type="text" name="longitude" class="form-control"
                                                               value="{{ old('longitude', $iduka->longitude ?? '') }}">
                                                    </div>

                                                    <div class="col-12 col-md-6">
                                                        <label for="jam_masuk" class="form-label">Jam Masuk (WIB)</label>
                                                        <div class="input-group">
                                                            <input type="time" class="form-control" id="jam_masuk" name="jam_masuk"
                                                                   value="{{ old('jam_masuk', $iduka->jam_masuk ?? '08:00') }}">
                                                            <span class="input-group-text">WIB</span>
                                                        </div>
                                                        <div class="form-text">Default: 08:00</div>
                                                    </div>

                                                    <div class="col-12 col-md-6">
                                                        <label for="jam_pulang" class="form-label">Jam Pulang (WIB)</label>
                                                        <div class="input-group">
                                                            <input type="time" class="form-control" id="jam_pulang" name="jam_pulang"
                                                                   value="{{ old('jam_pulang', $iduka->jam_pulang ?? '15:00') }}">
                                                            <span class="input-group-text">WIB</span>
                                                        </div>
                                                        <div class="form-text">Default: 15:00</div>
                                                    </div>

                                                    <div class="col-12">
                                                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                                                            Simpan
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Holiday Settings --}}
                            <div class="row g-3 mt-1">
                                <div class="col-12">
                                    <div class="card border-0 rounded-4 shadow-sm card-hover mb-4">
                                        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                                            <h5 class="card-title mb-0 fs-6 fs-md-5">Pengaturan Hari Libur</h5>
                                            <small class="text-muted">Setiap IDUKA memiliki hari libur sendiri</small>
                                        </div>
                                        <div class="card-body p-2 p-md-3">
                                            <div class="row g-3 mb-3">
                                                <div class="col-12 col-md-6">
                                                    <label for="holiday_iduka_id" class="form-label">Pilih IDUKA</label>
                                                    <select id="holiday_iduka_id" class="form-select select2">
                                                        <option value="">-- Pilih IDUKA --</option>
                                                        @foreach ($idukas as $item)
                                                            <option value="{{ $item->id }}" {{ isset($iduka) && $iduka && $iduka->id == $item->id ? 'selected' : '' }}>
                                                                {{ $item->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12 col-md-6 d-flex align-items-end">
                                                    <button id="btnLoadHolidays" class="btn btn-outline-secondary w-100 w-md-auto">
                                                        Muat Hari Libur
                                                    </button>
                                                </div>
                                            </div>

                                            <div id="holidayFormContainer" class="mb-4" style="display:none">
                                                <form id="holidayForm">
                                                    @csrf
                                                    <input type="hidden" name="iduka_id" id="holiday_form_iduka_id">
                                                    <div class="row g-2">
                                                        <div class="col-12 col-md-4">
                                                            <label class="form-label">Tanggal</label>
                                                            <input type="date" name="date" class="form-control" required>
                                                        </div>
                                                        <div class="col-12 col-md-5">
                                                            <label class="form-label">Nama (opsional)</label>
                                                            <input type="text" name="name" class="form-control" placeholder="Contoh: Hari Raya">
                                                        </div>
                                                        <div class="col-12 col-md-3 d-flex align-items-end">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="recurring" id="recurringCheckbox">
                                                                <label class="form-check-label" for="recurringCheckbox">Ulang setiap tahun</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3 text-end">
                                                        <button type="submit" class="btn btn-primary">Tambah Hari Libur</button>
                                                    </div>
                                                </form>
                                            </div>

                                            <div id="holidaysList">
                                                <div class="text-center text-muted">Klik "Muat Hari Libur" untuk menampilkan daftar.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Detail Izin --}}
<div class="modal fade" id="modalDetailIzin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Permohonan Izin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalDetailIzinContent"></div>
            <div class="modal-footer">
                {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button> --}}
            </div>
        </div>
    </div>
</div>

{{-- Toast --}}
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto" id="toast-title">Notifikasi</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toast-message">Pesan notifikasi</div>
    </div>
</div>

<script>
    // ============ KEEP ALL ORIGINAL JS FUNCTIONS ============
    function konfirmasiAbsensi(pendingId, status) {
        if (status === 'ditolak') { konfirmasiTolakAbsensi(pendingId); return; }
        if (!confirm(`Apakah Anda yakin ingin menyetujui absensi ini?`)) return;
        const row = document.getElementById(`row-pending-${pendingId}`);
        if (row) row.style.opacity = '0.5';
        fetch(`{{ route('iduka.konfirmasi-absen.proses', ['id' => ':id']) }}`.replace(':id', pendingId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => { if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`); return response.json(); })
        .then(data => {
            if (data.success) {
                showToast('Berhasil!', data.message, 'success');
                setTimeout(() => {
                    const currentUrl = window.location.href.split('#')[0];
                    window.location.href = currentUrl + '#v-pills-hari-ini';
                }, 1500);
            } else {
                showToast('Gagal!', data.message || 'Terjadi kesalahan', 'danger');
                if (row) row.style.opacity = '1';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error!', 'Terjadi kesalahan koneksi: ' + error.message, 'danger');
            if (row) row.style.opacity = '1';
        });
    }

    function konfirmasiIzin(izinId, status) {
        if (!izinId) { showToast('Error!', 'ID izin tidak ditemukan', 'danger'); return; }
        const actionText = status === 'disetujui' ? 'menyetujui' : 'menolak';
        if (!confirm(`Apakah Anda yakin ingin ${actionText} izin ini?`)) return;
        let catatan = null;
        if (status === 'ditolak') {
            catatan = prompt('Masukkan alasan penolakan:');
            if (catatan === null) return;
            if (catatan.trim() === '') { showToast('Peringatan', 'Alasan penolakan harus diisi!', 'warning'); return; }
        }
        const row = document.querySelector(`tr[data-izin-id="${izinId}"]`);
        if (row) {
            row.style.opacity = '0.5';
            row.querySelectorAll('button').forEach(btn => btn.disabled = true);
        }
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        if (!csrfToken) {
            showToast('Error!', 'CSRF token tidak ditemukan. Silakan refresh halaman.', 'danger');
            if (row) { row.style.opacity = '1'; row.querySelectorAll('button').forEach(btn => btn.disabled = false); }
            return;
        }
        const url = `{{ route('iduka.konfirmasi-izin', ['id' => 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', izinId);
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ status: status, catatan: catatan })
        })
        .then(response => {
            if (response.redirected) { window.location.href = response.url; return Promise.reject('Redirecting'); }
            return response.json().then(data => { if (!response.ok) throw new Error(data.message || 'Terjadi kesalahan'); return data; });
        })
        .then(data => {
            showToast('Berhasil!', data.message, 'success');
            setTimeout(() => {
                const currentUrl = window.location.href.split('#')[0];
                window.location.href = currentUrl + '#v-pills-hari-ini';
            }, 1500);
        })
        .catch(error => {
            if (error === 'Redirecting') return;
            console.error('Error:', error);
            showToast('Error!', 'Terjadi kesalahan koneksi: ' + error.message, 'danger');
            if (row) { row.style.opacity = '1'; row.querySelectorAll('button').forEach(btn => btn.disabled = false); }
        });
    }

    function konfirmasiDinas(dinasId, status) {
        if (!dinasId) { showToast('Error!', 'ID dinas tidak ditemukan', 'danger'); return; }
        const actionText = status === 'disetujui' ? 'menyetujui' : 'menolak';
        if (!confirm(`Apakah Anda yakin ingin ${actionText} dinas luar ini?`)) return;
        let catatan = null;
        if (status === 'ditolak') {
            catatan = prompt('Masukkan alasan penolakan:');
            if (catatan === null) return;
            if (catatan.trim() === '') { showToast('Peringatan', 'Alasan penolakan harus diisi!', 'warning'); return; }
        }
        const row = document.querySelector(`tr[data-dinas-id="${dinasId}"]`);
        if (row) {
            row.style.opacity = '0.5';
            row.querySelectorAll('button').forEach(btn => btn.disabled = true);
        }
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        if (!csrfToken) {
            showToast('Error!', 'CSRF token tidak ditemukan. Silakan refresh halaman.', 'danger');
            if (row) { row.style.opacity = '1'; row.querySelectorAll('button').forEach(btn => btn.disabled = false); }
            return;
        }
        const url = `{{ route('iduka.konfirmasi-dinas', ['id' => 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', dinasId);
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ status: status, catatan: catatan })
        })
        .then(response => {
            if (response.redirected) { window.location.href = response.url; return Promise.reject('Redirecting'); }
            return response.json().then(data => { if (!response.ok) throw new Error(data.message || 'Terjadi kesalahan'); return data; });
        })
        .then(data => {
            showToast('Berhasil!', data.message, 'success');
            setTimeout(() => {
                const currentUrl = window.location.href.split('#')[0];
                window.location.href = currentUrl + '#v-pills-hari-ini';
            }, 1500);
        })
        .catch(error => {
            if (error === 'Redirecting') return;
            console.error('Error:', error);
            showToast('Error!', 'Terjadi kesalahan koneksi: ' + error.message, 'danger');
            if (row) { row.style.opacity = '1'; row.querySelectorAll('button').forEach(btn => btn.disabled = false); }
        });
    }

    function konfirmasiTolakAbsensi(pendingId) {
        const catatan = prompt('Masukkan alasan penolakan:');
        if (catatan === null) return;
        if (catatan.trim() === '') { showToast('Peringatan', 'Alasan penolakan harus diisi!', 'warning'); return; }
        const row = document.getElementById(`row-pending-${pendingId}`);
        if (row) row.style.opacity = '0.5';
        fetch(`{{ route('iduka.tolak-absen', ['id' => ':id']) }}`.replace(':id', pendingId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ catatan: catatan })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Berhasil!', data.message, 'success');
                setTimeout(() => {
                    const currentUrl = window.location.href.split('#')[0];
                    window.location.href = currentUrl + '#v-pills-hari-ini';
                }, 1500);
            } else {
                showToast('Gagal!', data.message, 'danger');
                if (row) row.style.opacity = '1';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error!', 'Terjadi kesalahan koneksi: ' + error.message, 'danger');
            if (row) row.style.opacity = '1';
        });
    }

    function showToast(title, message, type = 'success') {
        const toastEl = document.getElementById('liveToast');
        const toastTitle = document.getElementById('toast-title');
        const toastMessage = document.getElementById('toast-message');
        toastTitle.textContent = title;
        toastMessage.textContent = message;
        toastEl.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0`;
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
        fetch("{{ route('clear.session') }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        });
    }

    // ============ BULK CONFIRM ============
    document.getElementById('formKonfirmasiBanyak')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const checkedBoxes = document.querySelectorAll('.absen-check:checked');
        if (checkedBoxes.length === 0) {
            showToast('Peringatan', 'Silakan pilih minimal satu absensi untuk dikonfirmasi', 'warning');
            return false;
        }
        const formData = new FormData();
        const status = document.querySelector('button[type="submit"][clicked]')?.value ||
                       document.querySelector('button[type="submit"]').value;
        formData.append('status', status);
        checkedBoxes.forEach(checkbox => formData.append('absen_ids[]', checkbox.value));
        if (!confirm(`Apakah Anda yakin ingin ${status === 'disetujui' ? 'menyetujui' : 'menolak'} ${checkedBoxes.length} absensi yang dipilih?`)) return false;
        const submitButtons = this.querySelectorAll('button[type="submit"]');
        submitButtons.forEach(btn => {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <span class="d-none d-sm-inline">Memproses...</span>';
        });
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw new Error(err.message || `HTTP error! status: ${response.status}`); });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast('Berhasil!', data.message, 'success');
                setTimeout(() => {
                    const currentUrl = window.location.href.split('#')[0];
                    window.location.href = currentUrl + '#v-pills-hari-ini';
                }, 1500);
            } else {
                showToast('Gagal!', data.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error!', 'Terjadi kesalahan: ' + error.message, 'danger');
        })
        .finally(() => {
            submitButtons.forEach(btn => {
                btn.disabled = false;
                if (btn.name === 'status' && btn.value === 'disetujui') {
                    btn.innerHTML = '<i class="bi bi-check-lg"></i> <span class="d-none d-sm-inline">Setujui yang Dipilih</span><span class="d-inline d-sm-none">Setujui</span>';
                } else if (btn.name === 'status' && btn.value === 'ditolak') {
                    btn.innerHTML = '<i class="bi bi-x-lg"></i> <span class="d-none d-sm-inline">Tolak yang Dipilih</span><span class="d-inline d-sm-none">Tolak</span>';
                }
            });
        });
    });

    document.querySelectorAll('#formKonfirmasiBanyak button[type="submit"]').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('#formKonfirmasiBanyak button[type="submit"]').forEach(btn => {
                btn.removeAttribute('clicked');
            });
            this.setAttribute('clicked', 'true');
        });
    });

    // ============ RIWAYAT ============
    function loadRiwayatData() {
        const tanggalDari = document.getElementById('tanggal_dari').value;
        const tanggalSampai = document.getElementById('tanggal_sampai').value;
        const filterSiswa = document.getElementById('filter_siswa_riwayat').value;
        if (!tanggalDari) { showToast('Peringatan', 'Silakan pilih tanggal awal', 'warning'); return; }
        const riwayatBody = document.getElementById('riwayatBody');
        riwayatBody.innerHTML = `<tr><td colspan="5" class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>`;
        fetch(`{{ route('iduka.get-riwayat-absen') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ tanggal_dari: tanggalDari, tanggal_sampai: tanggalSampai, siswa_id: filterSiswa })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) { renderRiwayatTable(data.data); }
            else { riwayatBody.innerHTML = `<tr><td colspan="5" class="text-center text-muted">${data.message || 'Tidak ada data'}</td></tr>`; }
        })
        .catch(error => {
            console.error('Error:', error);
            riwayatBody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Terjadi kesalahan: ${error.message}</td></tr>`;
        });
    }

    function renderRiwayatTable(data) {
        const riwayatBody = document.getElementById('riwayatBody');
        if (!data || data.length === 0) {
            riwayatBody.innerHTML = `<tr><td colspan="5" class="text-center text-muted">Tidak ada data riwayat untuk periode yang dipilih</td></tr>`;
            return;
        }
        let html = '';
        data.forEach(item => {
            html += `
                <tr>
                    <td class="text-nowrap">${item.tanggal}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('snet/assets/img/avatars/1.png') }}" alt="Avatar" class="rounded-circle me-2 flex-shrink-0" width="32" height="32">
                            <div class="ms-1 ms-md-2">
                                <div class="fw-semibold">${item.nama_siswa}</div>
                                <div class="text-muted small">${item.email}</div>
                            </div>
                        </div>
                    </td>
                    <td>${item.jam_masuk !== '-' ? `<span class="badge bg-success">${item.jam_masuk}</span>` : '<span class="badge bg-secondary">-</span>'}</td>
                    <td>${item.jam_pulang !== '-' ? `<span class="badge bg-info">${item.jam_pulang}</span>` : '<span class="badge bg-secondary">-</span>'}</td>
                    <td><span class="badge ${getStatusBadgeClass(item.status)}">${ucfirst(item.status.replace('_', ' '))}</span></td>
                </tr>
            `;
        });
        riwayatBody.innerHTML = html;
    }

    function getStatusBadgeClass(status) {
        switch(status) {
            case 'tepat_waktu': return 'bg-success';
            case 'terlambat': return 'bg-warning';
            case 'izin': return 'bg-info';
            case 'sakit': return 'bg-danger';
            case 'alpha': return 'bg-danger';
            case 'ditolak': return 'bg-danger';
            default: return 'bg-secondary';
        }
    }
    function ucfirst(str) { return str.charAt(0).toUpperCase() + str.slice(1); }

    // ============ DOM READY ============
    document.addEventListener('DOMContentLoaded', function() {
        function toggleAll(source) {
            document.querySelectorAll('.absen-check').forEach(checkbox => { checkbox.checked = source.checked; });
        }

        const checkAllHeader = document.getElementById('check-all-header');
        if (checkAllHeader) {
            checkAllHeader.addEventListener('change', function() {
                toggleAll(this);
                const checkAllOutside = document.getElementById('check-all');
                if (checkAllOutside) checkAllOutside.checked = this.checked;
            });
        }

        const checkAllOutside = document.getElementById('check-all');
        if (checkAllOutside) {
            checkAllOutside.addEventListener('change', function() {
                toggleAll(this);
                const checkAllHeader = document.getElementById('check-all-header');
                if (checkAllHeader) checkAllHeader.checked = this.checked;
            });
        }

        document.querySelectorAll('.absen-check').forEach(checkbox => {
            checkbox.addEventListener('change', updateCheckAllState);
        });

        function updateCheckAllState() {
            const allCheckboxes = document.querySelectorAll('.absen-check');
            const checkedCheckboxes = document.querySelectorAll('.absen-check:checked');
            const checkAllHeader = document.getElementById('check-all-header');
            const checkAllOutside = document.getElementById('check-all');
            if (allCheckboxes.length === 0) return;
            const allChecked = allCheckboxes.length === checkedCheckboxes.length;
            const someChecked = checkedCheckboxes.length > 0;
            if (checkAllHeader) { checkAllHeader.checked = allChecked; checkAllHeader.indeterminate = someChecked && !allChecked; }
            if (checkAllOutside) { checkAllOutside.checked = allChecked; checkAllOutside.indeterminate = someChecked && !allChecked; }
        }

        function activateTabFromHash() {
            const hash = window.location.hash;
            if (hash) {
                const tabElement = document.querySelector(`[data-bs-target="${hash}"]`);
                const tabPane = document.querySelector(hash);
                if (tabElement && tabPane) {
                    document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
                    document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('show', 'active'));
                    tabElement.classList.add('active');
                    tabPane.classList.add('show', 'active');
                }
            }
        }
        activateTabFromHash();
        window.addEventListener('hashchange', activateTabFromHash);

        const successMessage = "{{ session('success') }}";
        const errorMessage = "{{ session('error') }}";
        if (successMessage) showToast('Berhasil!', successMessage, 'success');
        if (errorMessage) showToast('Error!', errorMessage, 'danger');

        updateCheckAllState();

        const filterRiwayatForm = document.getElementById('filterRiwayatForm');
        if (filterRiwayatForm) {
            filterRiwayatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                loadRiwayatData();
            });
        }
    });
</script>

<style>
    /* ===== Responsive tweaks ===== */
    .card-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .card-hover:hover { transform: translateY(-2px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.08)!important; }

    .form-check-input { width: 1.2em; height: 1.2em; margin-top: 0.3em; cursor: pointer; }
    tbody tr:hover { background-color: rgba(0, 0, 0, 0.03); }
    th:first-child { cursor: pointer; }
    .form-check-input:checked { background-color: #0d6efd; border-color: #0d6efd; }
    .form-check-input:indeterminate { background-color: #6c757d; border-color: #6c757d; }
    .spinner-border-sm { width: 1rem; height: 1rem; }

    /* Hide scrollbar on mobile nav but keep scrollable */
    .nav-scroll::-webkit-scrollbar { height: 4px; }
    .nav-scroll::-webkit-scrollbar-thumb { background: rgba(0,0,0,.15); border-radius: 4px; }
    .nav-scroll { scrollbar-width: thin; -webkit-overflow-scrolling: touch; }

    /* Tables: comfortable on mobile */
    @media (max-width: 575.98px) {
        .table { font-size: 0.85rem; }
        .table > :not(caption) > * > * { padding: 0.5rem 0.4rem; }
        .badge { font-size: 0.7rem; }
        .card-body { padding: 0.75rem; }
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
        .nav-link { font-size: 0.85rem; padding-top: 0.4rem; padding-bottom: 0.4rem; }
    }

    /* Desktop: force vertical sidebar nav */
    @media (min-width: 992px) {
        #v-pills-tab { flex-direction: column !important; overflow: visible !important; }
        #v-pills-tab .nav-link { width: 100%; }
    }

    /* Ensure tabs fill nicely on mobile horizontally */
    @media (max-width: 991.98px) {
        #v-pills-tab { padding-bottom: 4px; }
        #v-pills-tab .nav-link { font-size: 0.85rem; }
    }

    /* Chart containers responsive */
    .chart-container canvas { max-width: 100%; }

    /* Make filter form stack on very small screens */
    @media (max-width: 575.98px) {
        #filterRiwayatForm { width: 100%; }
        #filterRiwayatForm input, #filterRiwayatForm select, #filterRiwayatForm button { width: 100%; }
    }
</style>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Select2 init
        $('.select2').each(function() {
            $(this).select2({
                dropdownParent: $(this).closest('.modal').length ? $(this).closest('.modal') : $(this).parent(),
                placeholder: "-- Pilih IDUKA --",
                allowClear: true,
                width: '100%',
                dropdownPosition: 'below'
            });
        });

        // IDUKA data fetch
        $('#iduka_id').on('change', function() {
            const idukaId = $(this).val();
            if (idukaId) {
                $.ajax({
                    url: `/iduka/get-data/${idukaId}`,
                    method: 'GET',
                    success: function(data) {
                        if (data.error) { alert(data.error); }
                        else {
                            $('input[name="latitude"]').val(data.latitude || '');
                            $('input[name="longitude"]').val(data.longitude || '');
                            $('input[name="radius"]').val(data.radius || '');
                            $('input[name="jam_masuk"]').val(data.jam_masuk || '08:00');
                            $('input[name="jam_pulang"]').val(data.jam_pulang || '15:00');
                        }
                    },
                    error: function() { console.error('Gagal mengambil data IDUKA.'); }
                });
            } else {
                $('input[name="latitude"]').val('');
                $('input[name="longitude"]').val('');
                $('input[name="radius"]').val('');
                $('input[name="jam_masuk"]').val('08:00');
                $('input[name="jam_pulang"]').val('15:00');
            }
        });

        // Holiday management
        function loadHolidaysFor(idukaId) {
            if (!idukaId) {
                $('#holidaysList').html('<div class="text-danger">Pilih IDUKA terlebih dahulu.</div>');
                return;
            }
            $('#holidaysList').html('<div class="text-center"><div class="spinner-border" role="status"></div></div>');
            $.get(`/iduka/holidays/${idukaId}`, function(res) {
                if (!res.success) {
                    $('#holidaysList').html('<div class="text-danger">Gagal memuat data.</div>');
                    return;
                }
                const data = res.data;
                if (!data || data.length === 0) {
                    $('#holidaysList').html('<div class="text-muted">Belum ada hari libur untuk IDUKA ini.</div>');
                    return;
                }
                let html = '<div class="table-responsive"><table class="table table-sm align-middle"><thead><tr><th>Tanggal</th><th>Nama</th><th>Ulang</th><th>Aksi</th></tr></thead><tbody>';
                data.forEach(item => {
                    const tanggal = new Date(item.date).toLocaleDateString('id-ID');
                    html += `<tr><td class="text-nowrap">${tanggal}</td><td>${item.name || '-'}</td><td>${item.recurring ? 'Ya' : 'Tidak'}</td><td><button class="btn btn-sm btn-danger btn-delete-holiday" data-id="${item.id}">Hapus</button></td></tr>`;
                });
                html += '</tbody></table></div>';
                $('#holidaysList').html(html);
            }).fail(function() {
                $('#holidaysList').html('<div class="text-danger">Gagal memuat data (network).</div>');
            });
        }

        $('#btnLoadHolidays').on('click', function(e) {
            e.preventDefault();
            const idukaId = $('#holiday_iduka_id').val() || $('#iduka_id').val();
            if (!idukaId) { alert('Pilih IDUKA terlebih dahulu'); return; }
            $('#holiday_form_iduka_id').val(idukaId);
            $('#holidayFormContainer').show();
            loadHolidaysFor(idukaId);
        });

        $('#holidayForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const payload = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                iduka_id: $('#holiday_form_iduka_id').val(),
                date: form.find('input[name="date"]').val(),
                name: form.find('input[name="name"]').val(),
                recurring: form.find('input[name="recurring"]')[0].checked ? 1 : 0
            };
            $.post('/iduka/holidays', payload, function() {
                loadHolidaysFor(payload.iduka_id);
                form[0].reset();
            }).fail(function(xhr) {
                alert('Gagal menyimpan hari libur: ' + (xhr.responseJSON?.message || 'error'));
            });
        });

        $(document).on('click', '.btn-delete-holiday', function() {
            if (!confirm('Hapus hari libur ini?')) return;
            const id = $(this).data('id');
            const token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '/iduka/holidays/' + id,
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': token },
                success: function() {
                    const idukaId = $('#holiday_iduka_id').val() || $('#iduka_id').val();
                    loadHolidaysFor(idukaId);
                },
                error: function() { alert('Gagal menghapus hari libur'); }
            });
        });
    });
</script>
@endpush
