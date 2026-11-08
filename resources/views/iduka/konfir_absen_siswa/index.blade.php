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
                            {{-- Desktop: vertical pills | Mobile: wrap pills (semua kelihatan) --}}
                            <div class="nav nav-pills gap-2 flex-row flex-lg-column flex-wrap flex-lg-nowrap overflow-visible"
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
                                    <div class="table-scroll-hint">
                                        <i class="bi bi-arrow-left-right"></i> Geser tabel ke kiri/kanan untuk melihat semua kolom
                                    </div>
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
                                                                    <div class="text-muted small text-truncate hide-on-xs">{{ $absensi->user->email }}</div>
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
                                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-2 mb-3 bulk-actions-wrap">
                                            <div class="form-check">
                                                <input type="checkbox" id="check-all" class="form-check-input">
                                                <label for="check-all" class="form-check-label ms-1">Pilih Semua</label>
                                            </div>
                                            <div class="d-flex flex-column flex-sm-row gap-2">
                                                <button type="submit" name="status" value="disetujui"
                                                        class="btn btn-sm btn-success rounded-pill px-3 shadow-sm btn-bulk-submit">
                                                    <i class="bi bi-check-lg"></i> <span class="d-none d-sm-inline">Setujui yang Dipilih</span>
                                                    <span class="d-inline d-sm-none">Setujui</span>
                                                </button>
                                                <button type="submit" name="status" value="ditolak"
                                                        class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm btn-bulk-submit">
                                                    <i class="bi bi-x-lg"></i> <span class="d-none d-sm-inline">Tolak yang Dipilih</span>
                                                    <span class="d-inline d-sm-none">Tolak</span>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="table-scroll-hint">
                                            <i class="bi bi-arrow-left-right"></i> Geser tabel ke kiri/kanan untuk melihat semua kolom
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
                                                                <div class="d-flex gap-1 justify-content-center">
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

                                    <div class="table-scroll-hint">
                                        <i class="bi bi-arrow-left-right"></i> Geser tabel ke kiri/kanan untuk melihat semua kolom
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead>
                                                <tr>
                                                    <th>Nama Siswa</th>
                                                    <th>Tanggal</th>
                                                    <th>Jenis Dinas</th>
                                                    <th>Alasan</th>
                                                    <th>Status</th>
                                                    <th class="text-center">Aksi</th>
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
                                                                    <div class="text-muted small hide-on-xs">{{ $dinas->created_at->format('d-m-Y H:i') }} WIB</div>
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
                                                            <div class="d-flex gap-1 justify-content-center">
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
                                    <div class="table-scroll-hint">
                                        <i class="bi bi-arrow-left-right"></i> Geser tabel ke kiri/kanan untuk melihat semua kolom
                                    </div>
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
                                                                    <div class="text-muted small hide-on-xs">{{ $izin->created_at->format('d-m-Y H:i') }} WIB</div>
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
                                                            <div class="d-flex gap-1 justify-content-center flex-wrap">
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
                                    <div class="table-scroll-hint">
                                        <i class="bi bi-arrow-left-right"></i> Geser tabel ke kiri/kanan untuk melihat semua kolom
                                    </div>
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
                                                    <div class="card text-center border-0 rounded-4 shadow-sm card-hover h-100 stat-card">
                                                        <div class="card-body p-3">
                                                            <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                                                            <h4 class="mt-2 mb-1">{{ $statistik['persentase_kehadiran'] }}%</h4>
                                                            <p class="text-muted mb-0 small">Rata-rata Kehadiran</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <div class="card text-center border-0 rounded-4 shadow-sm card-hover h-100 stat-card">
                                                        <div class="card-body p-3">
                                                            <i class="bi bi-clock text-warning" style="font-size: 2rem;"></i>
                                                            <h4 class="mt-2 mb-1">{{ $statistik['terlambat'] }}</h4>
                                                            <p class="text-muted mb-0 small">Total Keterlambatan</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <div class="card text-center border-0 rounded-4 shadow-sm card-hover h-100 stat-card">
                                                        <div class="card-body p-3">
                                                            <i class="bi bi-x-circle text-danger" style="font-size: 2rem;"></i>
                                                            <h4 class="mt-2 mb-1">{{ $statistik['alpha'] }}</h4>
                                                            <p class="text-muted mb-0 small">Total Alpha</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <div class="card text-center border-0 rounded-4 shadow-sm card-hover h-100 stat-card">
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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

<style>
    /* ===== Base tweaks ===== */
    .card-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .card-hover:hover { transform: translateY(-2px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.08)!important; }

    .form-check-input { width: 1.2em; height: 1.2em; margin-top: 0.3em; cursor: pointer; }
    tbody tr:hover { background-color: rgba(0, 0, 0, 0.03); }
    th:first-child { cursor: pointer; }
    .form-check-input:checked { background-color: #0d6efd; border-color: #0d6efd; }
    .form-check-input:indeterminate { background-color: #6c757d; border-color: #6c757d; }
    .spinner-border-sm { width: 1rem; height: 1rem; }

    /* Mobile nav scrollbar */
    .nav-scroll::-webkit-scrollbar { height: 4px; }
    .nav-scroll::-webkit-scrollbar-thumb { background: rgba(0,0,0,.15); border-radius: 4px; }
    .nav-scroll { scrollbar-width: thin; -webkit-overflow-scrolling: touch; }

    /* Desktop: force vertical sidebar nav */
    @media (min-width: 992px) {
        #v-pills-tab { flex-direction: column !important; overflow: visible !important; }
        #v-pills-tab .nav-link { width: 100%; }
    }

    /* Chart containers */
    .chart-container canvas { max-width: 100%; }

    /* ============================================================ */
    /* ===== MOBILE FIXES ===== */
    /* ============================================================ */

    .table-scroll-hint {
        display: none;
        font-size: .72rem;
        color: #64748b;
        background: #f1f5f9;
        border-radius: 8px;
        padding: 6px 10px;
        margin-bottom: 8px;
    }

    /* === Mobile (≤ 767px) === */
    @media (max-width: 767.98px) {
        .table-scroll-hint { display: block; }

        .card-body   { padding: .85rem !important; }
        .card-header { padding: .75rem 1rem !important; }

        .bulk-actions-wrap {
            flex-direction: column;
            align-items: stretch !important;
            gap: 8px !important;
        }
        .bulk-actions-wrap > * { width: 100%; }
        .bulk-actions-wrap .btn { width: 100%; justify-content: center; }

        .stat-card .card-body { padding: .6rem .4rem !important; }
        .stat-card i      { font-size: 1.6rem !important; }
        .stat-card h4     { font-size: 1.15rem !important; }
        .stat-card p      { font-size: .7rem !important; line-height: 1.2; }

        .chart-container { height: 230px !important; }

        .position-fixed.bottom-0.end-0 {
            left: 0 !important;
            right: 0 !important;
            padding: .75rem !important;
            display: flex;
            justify-content: center;
        }
        .position-fixed.bottom-0.end-0 .toast {
            width: 100%;
            max-width: 100%;
        }

        table .btn-sm { padding: .35rem .6rem; font-size: .75rem; }
    }

    /* === Extra small (≤ 575px) === */
    @media (max-width: 575.98px) {
        .table { font-size: .82rem; }
        .table > :not(caption) > * > * { padding: .5rem .4rem; }
        .badge { font-size: .68rem; }
        .card-body   { padding: .7rem !important; }
        .btn-sm { padding: .3rem .55rem; font-size: .72rem; }

        .stat-card i  { font-size: 1.3rem !important; }
        .stat-card h4 { font-size: 1rem !important; }
        .stat-card p  { font-size: .62rem !important; }

        .chart-container { height: 200px !important; }

        .card-title.fs-6 { font-size: .9rem !important; }

        #filterRiwayatForm,
        #filterRiwayatForm input,
        #filterRiwayatForm select,
        #filterRiwayatForm button { width: 100%; }

        .hide-on-xs { display: none !important; }
    }

    /* ============================================================ */
    /* Menu Konfirmasi: wrap pills biar semua kelihatan */
    /* ============================================================ */
    @media (max-width: 991.98px) {
        #v-pills-tab {
            flex-direction: row !important;
            flex-wrap: wrap !important;
            overflow: visible !important;
            gap: 6px !important;
            min-height: 40px;
        }
        #v-pills-tab .nav-link {
            flex: 0 0 auto !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 4px !important;
            font-size: .74rem !important;
            padding: .38rem .7rem !important;
            line-height: 1.25 !important;
            white-space: normal !important;
        }
        #v-pills-tab .nav-link i {
            font-size: .75rem !important;
            margin-right: 2px !important;
        }
        #v-pills-tab .nav-link .badge {
            font-size: .62rem !important;
            padding: 2px 6px !important;
            margin-left: 4px !important;
        }
    }

    @media (max-width: 575.98px) {
        #v-pills-tab { gap: 4px !important; }
        #v-pills-tab .nav-link {
            font-size: .68rem !important;
            padding: .3rem .55rem !important;
        }
        #v-pills-tab .nav-link i {
            font-size: .7rem !important;
            margin-right: 0 !important;
        }
    }

    /* ============================================================ */
    /* ⚡ CRITICAL FIX: Bikin tabel bisa di-scroll di HP             */
    /* ============================================================ */
    @media (max-width: 991.98px) {

        /* 1) HAPUS overflow:hidden dari card (ini biang keroknya!) */
        .card {
            overflow: visible !important;
        }

        /* 2) Balikin rounded corners biar card tetap cantik */
        .card > .card-header:first-child {
            border-top-left-radius: .6rem !important;
            border-top-right-radius: .6rem !important;
        }
        .card > .card-body:last-child {
            border-bottom-left-radius: .6rem !important;
            border-bottom-right-radius: .6rem !important;
        }

        /* 3) Paksa .table-responsive jadi scroll container beneran */
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

        /* 4) Tabel harus lebih lebar dari container supaya bisa di-scroll */
        .table-responsive > .table { margin-bottom: 0 !important; }

        #tabel-absen-hari-ini {
            min-width: 560px !important;
        }
        #v-pills-absen-pending .table-responsive > .table {
            min-width: 880px !important;
        }
        #v-pills-perlu-konfirmasi .table-responsive > .table {
            min-width: 880px !important;
        }
        #v-pills-dinas-pending .table-responsive > .table {
            min-width: 720px !important;
        }
        #v-pills-riwayat .table-responsive > .table {
            min-width: 640px !important;
        }

        /* 5) Scrollbar tipis biar kelihatan bisa digeser */
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
    }
</style>
@endsection

@push('scripts')
<script>
(function() {
    'use strict';

    // ============================================================
    // UTILITY
    // ============================================================
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.content : null;
    }

    window.showToast = function(title, message, type = 'success') {
        if (typeof bootstrap === 'undefined' || !bootstrap.Toast) {
            alert(`${title}: ${message}`);
            return;
        }
        const toastEl = document.getElementById('liveToast');
        const toastTitle = document.getElementById('toast-title');
        const toastMessage = document.getElementById('toast-message');
        if (!toastEl) return;
        toastTitle.textContent = title;
        toastMessage.textContent = message;
        toastEl.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0`;
        const toast = bootstrap.Toast.getOrCreateInstance(toastEl);
        toast.show();
        const csrf = getCsrfToken();
        if (csrf) {
            fetch("{{ route('clear.session') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf }
            }).catch(function() {});
        }
    };

    // ============================================================
    // TAB MANAGEMENT
    // ============================================================
    function activateTabFromHash() {
        const hash = window.location.hash;
        if (!hash) return;

        const tabTrigger = document.querySelector(`[data-bs-target="${hash}"]`);
        const tabPane = document.querySelector(hash);
        if (!tabTrigger || !tabPane) return;

        if (typeof bootstrap === 'undefined' || !bootstrap.Tab) {
            document.querySelectorAll('#v-pills-tab .nav-link').forEach(function(link) {
                link.classList.remove('active');
                link.setAttribute('aria-selected', 'false');
            });
            document.querySelectorAll('#v-pills-tabContent .tab-pane').forEach(function(pane) {
                pane.classList.remove('show', 'active');
            });
            tabTrigger.classList.add('active');
            tabTrigger.setAttribute('aria-selected', 'true');
            tabPane.classList.add('show', 'active');
            return;
        }

        const tab = bootstrap.Tab.getOrCreateInstance(tabTrigger);
        tab.show();
    }

    // ============================================================
    // KONFIRMASI FUNCTIONS
    // ============================================================
    window.konfirmasiAbsensi = function(pendingId, status) {
        if (status === 'ditolak') { konfirmasiTolakAbsensi(pendingId); return; }
        if (!confirm('Apakah Anda yakin ingin menyetujui absensi ini?')) return;
        const row = document.getElementById(`row-pending-${pendingId}`);
        if (row) row.style.opacity = '0.5';
        const csrf = getCsrfToken();
        if (!csrf) { showToast('Error!', 'CSRF token tidak ditemukan. Refresh halaman.', 'danger'); return; }
        fetch(`{{ route('iduka.konfirmasi-absen.proses', ['id' => ':id']) }}`.replace(':id', pendingId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: status })
        })
        .then(function(response) {
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                showToast('Berhasil!', data.message, 'success');
                setTimeout(function() {
                    window.location.href = window.location.pathname + '#v-pills-hari-ini';
                }, 1500);
            } else {
                showToast('Gagal!', data.message || 'Terjadi kesalahan', 'danger');
                if (row) row.style.opacity = '1';
            }
        })
        .catch(function(error) {
            console.error('Error:', error);
            showToast('Error!', 'Terjadi kesalahan koneksi: ' + error.message, 'danger');
            if (row) row.style.opacity = '1';
        });
    };

    window.konfirmasiIzin = function(izinId, status) {
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
            row.querySelectorAll('button').forEach(function(btn) { btn.disabled = true; });
        }
        const csrf = getCsrfToken();
        if (!csrf) {
            showToast('Error!', 'CSRF token tidak ditemukan. Refresh halaman.', 'danger');
            if (row) { row.style.opacity = '1'; row.querySelectorAll('button').forEach(function(btn){ btn.disabled = false; }); }
            return;
        }
        const url = `{{ route('iduka.konfirmasi-izin', ['id' => 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', izinId);
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ status: status, catatan: catatan })
        })
        .then(function(response) {
            if (response.redirected) { window.location.href = response.url; return Promise.reject('Redirecting'); }
            return response.json().then(function(data) {
                if (!response.ok) throw new Error(data.message || 'Terjadi kesalahan');
                return data;
            });
        })
        .then(function(data) {
            showToast('Berhasil!', data.message, 'success');
            setTimeout(function() {
                window.location.href = window.location.pathname + '#v-pills-perlu-konfirmasi';
            }, 1500);
        })
        .catch(function(error) {
            if (error === 'Redirecting') return;
            console.error('Error:', error);
            showToast('Error!', 'Terjadi kesalahan: ' + error.message, 'danger');
            if (row) { row.style.opacity = '1'; row.querySelectorAll('button').forEach(function(btn){ btn.disabled = false; }); }
        });
    };

    window.konfirmasiDinas = function(dinasId, status) {
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
            row.querySelectorAll('button').forEach(function(btn) { btn.disabled = true; });
        }
        const csrf = getCsrfToken();
        if (!csrf) {
            showToast('Error!', 'CSRF token tidak ditemukan. Refresh halaman.', 'danger');
            if (row) { row.style.opacity = '1'; row.querySelectorAll('button').forEach(function(btn){ btn.disabled = false; }); }
            return;
        }
        const url = `{{ route('iduka.konfirmasi-dinas', ['id' => 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', dinasId);
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ status: status, catatan: catatan })
        })
        .then(function(response) {
            if (response.redirected) { window.location.href = response.url; return Promise.reject('Redirecting'); }
            return response.json().then(function(data) {
                if (!response.ok) throw new Error(data.message || 'Terjadi kesalahan');
                return data;
            });
        })
        .then(function(data) {
            showToast('Berhasil!', data.message, 'success');
            setTimeout(function() {
                window.location.href = window.location.pathname + '#v-pills-dinas-pending';
            }, 1500);
        })
        .catch(function(error) {
            if (error === 'Redirecting') return;
            console.error('Error:', error);
            showToast('Error!', 'Terjadi kesalahan: ' + error.message, 'danger');
            if (row) { row.style.opacity = '1'; row.querySelectorAll('button').forEach(function(btn){ btn.disabled = false; }); }
        });
    };

    function konfirmasiTolakAbsensi(pendingId) {
        const catatan = prompt('Masukkan alasan penolakan:');
        if (catatan === null) return;
        if (catatan.trim() === '') { showToast('Peringatan', 'Alasan penolakan harus diisi!', 'warning'); return; }
        const row = document.getElementById(`row-pending-${pendingId}`);
        if (row) row.style.opacity = '0.5';
        const csrf = getCsrfToken();
        if (!csrf) { showToast('Error!', 'CSRF token tidak ditemukan.', 'danger'); return; }
        fetch(`{{ route('iduka.tolak-absen', ['id' => ':id']) }}`.replace(':id', pendingId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ catatan: catatan })
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.success) {
                showToast('Berhasil!', data.message, 'success');
                setTimeout(function() {
                    window.location.href = window.location.pathname + '#v-pills-hari-ini';
                }, 1500);
            } else {
                showToast('Gagal!', data.message, 'danger');
                if (row) row.style.opacity = '1';
            }
        })
        .catch(function(error) {
            console.error('Error:', error);
            showToast('Error!', 'Terjadi kesalahan koneksi: ' + error.message, 'danger');
            if (row) row.style.opacity = '1';
        });
    }

    // ============================================================
    // RIWAYAT
    // ============================================================
    function getStatusBadgeClass(status) {
        switch (status) {
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

    function renderRiwayatTable(data) {
        const riwayatBody = document.getElementById('riwayatBody');
        if (!data || data.length === 0) {
            riwayatBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Tidak ada data riwayat untuk periode yang dipilih</td></tr>';
            return;
        }
        let html = '';
        const avatarUrl = "{{ asset('snet/assets/img/avatars/1.png') }}";
        data.forEach(function(item) {
            html += `
                <tr>
                    <td class="text-nowrap">${item.tanggal}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="${avatarUrl}" alt="Avatar" class="rounded-circle me-2 flex-shrink-0" width="32" height="32">
                            <div class="ms-1 ms-md-2">
                                <div class="fw-semibold">${item.nama_siswa}</div>
                                <div class="text-muted small hide-on-xs">${item.email}</div>
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

    function loadRiwayatData() {
        const tanggalDari = document.getElementById('tanggal_dari').value;
        const tanggalSampai = document.getElementById('tanggal_sampai').value;
        const filterSiswa = document.getElementById('filter_siswa_riwayat').value;
        if (!tanggalDari) { showToast('Peringatan', 'Silakan pilih tanggal awal', 'warning'); return; }
        const riwayatBody = document.getElementById('riwayatBody');
        riwayatBody.innerHTML = '<tr><td colspan="5" class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';
        const csrf = getCsrfToken();
        fetch("{{ route('iduka.get-riwayat-absen') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ tanggal_dari: tanggalDari, tanggal_sampai: tanggalSampai, siswa_id: filterSiswa })
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.success) { renderRiwayatTable(data.data); }
            else { riwayatBody.innerHTML = `<tr><td colspan="5" class="text-center text-muted">${data.message || 'Tidak ada data'}</td></tr>`; }
        })
        .catch(function(error) {
            console.error('Error:', error);
            riwayatBody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">Terjadi kesalahan: ${error.message}</td></tr>`;
        });
    }

    // ============================================================
    // DOM READY
    // ============================================================
    document.addEventListener('DOMContentLoaded', function() {

        // ---------- Tab: sync hash ----------
        document.querySelectorAll('#v-pills-tab [data-bs-toggle="pill"]').forEach(function(tabEl) {
            tabEl.addEventListener('shown.bs.tab', function(e) {
                const target = e.target.getAttribute('data-bs-target');
                if (target && history.replaceState) {
                    history.replaceState(null, null, target);
                }
            });
        });

        // ---------- Tab: back/forward ----------
        window.addEventListener('hashchange', function() {
            const ae = document.activeElement;
            if (ae && ae.matches && ae.matches('[data-bs-toggle="pill"]')) return;
            activateTabFromHash();
        });

        // ---------- Tab: aktivasi dari hash ----------
        if (typeof bootstrap !== 'undefined' && bootstrap.Tab) {
            activateTabFromHash();
        } else {
            let tries = 0;
            const t = setInterval(function() {
                if (typeof bootstrap !== 'undefined' && bootstrap.Tab) {
                    clearInterval(t);
                    activateTabFromHash();
                } else if (++tries > 30) {
                    clearInterval(t);
                    activateTabFromHash();
                }
            }, 100);
        }

        // ---------- Checkbox: select all ----------
        function toggleAll(source) {
            document.querySelectorAll('.absen-check').forEach(function(cb) { cb.checked = source.checked; });
        }
        const checkAllHeader = document.getElementById('check-all-header');
        const checkAllOutside = document.getElementById('check-all');

        if (checkAllHeader) {
            checkAllHeader.addEventListener('change', function() {
                toggleAll(this);
                if (checkAllOutside) checkAllOutside.checked = this.checked;
            });
        }
        if (checkAllOutside) {
            checkAllOutside.addEventListener('change', function() {
                toggleAll(this);
                if (checkAllHeader) checkAllHeader.checked = this.checked;
            });
        }
        document.querySelectorAll('.absen-check').forEach(function(cb) {
            cb.addEventListener('change', updateCheckAllState);
        });

        function updateCheckAllState() {
            const all = document.querySelectorAll('.absen-check');
            const checked = document.querySelectorAll('.absen-check:checked');
            if (all.length === 0) return;
            const allChecked = all.length === checked.length;
            const someChecked = checked.length > 0;
            [checkAllHeader, checkAllOutside].forEach(function(el) {
                if (!el) return;
                el.checked = allChecked;
                el.indeterminate = someChecked && !allChecked;
            });
        }
        updateCheckAllState();

        // ---------- Bulk submit ----------
        let selectedBulkStatus = null;
        document.querySelectorAll('#formKonfirmasiBanyak .btn-bulk-submit').forEach(function(btn) {
            btn.addEventListener('click', function() {
                selectedBulkStatus = this.value;
            });
        });

        const formBulk = document.getElementById('formKonfirmasiBanyak');
        if (formBulk) {
            formBulk.addEventListener('submit', function(e) {
                e.preventDefault();
                const checkedBoxes = document.querySelectorAll('.absen-check:checked');
                if (checkedBoxes.length === 0) {
                    showToast('Peringatan', 'Silakan pilih minimal satu absensi untuk dikonfirmasi', 'warning');
                    return false;
                }
                if (!selectedBulkStatus) {
                    showToast('Peringatan', 'Pilih aksi (Setujui/Tolak) terlebih dahulu', 'warning');
                    return false;
                }
                if (!confirm(`Apakah Anda yakin ingin ${selectedBulkStatus === 'disetujui' ? 'menyetujui' : 'menolak'} ${checkedBoxes.length} absensi?`)) {
                    return false;
                }

                const formData = new FormData();
                formData.append('status', selectedBulkStatus);
                checkedBoxes.forEach(function(cb) { formData.append('absen_ids[]', cb.value); });

                const submitButtons = formBulk.querySelectorAll('button[type="submit"]');
                submitButtons.forEach(function(btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <span class="d-none d-sm-inline">Memproses...</span>';
                });

                const csrf = getCsrfToken();
                fetch(formBulk.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrf
                    }
                })
                .then(function(response) {
                    if (!response.ok) {
                        return response.json().then(function(err) {
                            throw new Error(err.message || `HTTP error! status: ${response.status}`);
                        });
                    }
                    return response.json();
                })
                .then(function(data) {
                    if (data.success) {
                        showToast('Berhasil!', data.message, 'success');
                        setTimeout(function() {
                            window.location.href = window.location.pathname + '#v-pills-hari-ini';
                        }, 1500);
                    } else {
                        showToast('Gagal!', data.message, 'danger');
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                    showToast('Error!', 'Terjadi kesalahan: ' + error.message, 'danger');
                })
                .finally(function() {
                    submitButtons.forEach(function(btn) {
                        btn.disabled = false;
                        if (btn.value === 'disetujui') {
                            btn.innerHTML = '<i class="bi bi-check-lg"></i> <span class="d-none d-sm-inline">Setujui yang Dipilih</span><span class="d-inline d-sm-none">Setujui</span>';
                        } else if (btn.value === 'ditolak') {
                            btn.innerHTML = '<i class="bi bi-x-lg"></i> <span class="d-none d-sm-inline">Tolak yang Dipilih</span><span class="d-inline d-sm-none">Tolak</span>';
                        }
                    });
                });
            });
        }

        // ---------- Filter Riwayat ----------
        const filterRiwayatForm = document.getElementById('filterRiwayatForm');
        if (filterRiwayatForm) {
            filterRiwayatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                loadRiwayatData();
            });
        }

        // ---------- Session toast ----------
        const successMessage = @json(session('success'));
        const errorMessage = @json(session('error'));
        if (successMessage) showToast('Berhasil!', successMessage, 'success');
        if (errorMessage) showToast('Error!', errorMessage, 'danger');
    });
})();
</script>

{{-- jQuery + Select2 & Holiday --}}
<script>
$(document).ready(function() {
    $('.select2').each(function() {
        $(this).select2({
            dropdownParent: $(this).closest('.modal').length ? $(this).closest('.modal') : $(this).parent(),
            placeholder: "-- Pilih IDUKA --",
            allowClear: true,
            width: '100%',
            dropdownPosition: 'below'
        });
    });

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
            data.forEach(function(item) {
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
