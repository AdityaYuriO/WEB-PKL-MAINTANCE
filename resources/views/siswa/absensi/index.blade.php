@extends('layout.main')

@section('content')
<style>
    /* ============================================================ */
    /* BASE (desktop) */
    /* ============================================================ */
    .btn-absensi {
        height: 120px !important;
        padding: 12px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        border-radius: 8px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .btn-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
    }
    .btn-absensi i {
        font-size: 1.8rem;
        margin-bottom: 8px;
        line-height: 1;
    }
    .btn-title {
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 4px;
        line-height: 1.2;
    }
    .btn-subtitle {
        font-size: 0.75rem;
        line-height: 1.2;
        max-width: 100%;
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }
    .btn-absensi:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .btn-absensi:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
    #riwayatAbsensiTable tr { cursor: pointer; }
    #riwayatAbsensiTable tr:hover { background-color: rgba(0, 123, 255, 0.1); }
    #riwayatContainer, #detailAbsensiContainer { transition: all 0.3s ease; }
    .izin-status-alert, .dinas-status-alert { margin-bottom: 1rem; }

    /* Hint scroll (default hidden) */
    .table-scroll-hint {
        display: none;
        font-size: .72rem;
        color: #64748b;
        background: #f1f5f9;
        border-radius: 8px;
        padding: 6px 10px;
        margin: 10px 0 8px;
    }

    /* ============================================================ */
    /* MOBILE & TABLET (≤ 991px)                                     */
    /* ============================================================ */
    @media (max-width: 991.98px) {

        /* 1) HALAMAN tidak bisa scroll horizontal (safety net) */
        html, body {
            overflow-x: hidden !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        /* 2) SEMUA container tidak boleh melebar ke samping */
        .container-fluid,
        .content-wrapper,
        .container-xxl,
        .container-p-y {
            overflow-x: hidden !important;
            max-width: 100% !important;
        }

        /* 3) Card CLIP konten di batasnya — apapun yang bocor, dipotong di sini */
        .card {
            overflow: hidden !important;
            max-width: 100% !important;
        }

        /* 4) Card-body HARUS visible, biar table-responsive bisa scroll di dalamnya */
        .card-body {
            overflow: visible !important;
        }

        /* 5) Hint muncul */
        .table-scroll-hint { display: block; }

        /* 6) Table-responsive = SATU-SATUNYA scroll container */
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

        /* 7) ⚡ KUNCI: tabel pakai max-content — otomatis selebar isinya */
        .table-responsive > .table {
            width: max-content !important;
            min-width: 100% !important;
            max-width: none !important;
            margin-bottom: 0 !important;
        }

        /* 8) Cell tidak wrap — pastikan tidak ada teks terpotong */
        .table-responsive > .table th,
        .table-responsive > .table td {
            white-space: nowrap !important;
        }

        /* 9) Scrollbar jelas */
        .table-responsive::-webkit-scrollbar { height: 6px; }
        .table-responsive::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }
        .table-responsive::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        .table-responsive::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* 10) Sembunyikan gambar welcome di HP */
        .welcome-image-col { display: none !important; }

        /* 11) Pagination center */
        .pagination-wrap {
            width: 100%;
            display: flex;
            justify-content: center;
        }
    }

    /* ============================================================ */
    /* MOBILE (≤ 767px) */
    /* ============================================================ */
    @media (max-width: 767.98px) {
        .card-body   { padding: .9rem !important; }
        .card-header { padding: .7rem 1rem !important; }
        .card-title  { font-size: .95rem !important; }

        .btn-absensi { height: 90px !important; padding: 6px !important; }
        .btn-absensi i { font-size: 1.4rem !important; margin-bottom: 4px !important; }
        .btn-title { font-size: .85rem !important; margin-bottom: 2px !important; }
        .btn-subtitle { font-size: .62rem !important; line-height: 1.15 !important; }

        .table { font-size: .8rem; }
        .table > :not(caption) > * > * { padding: .5rem .4rem; }
        .badge { font-size: .68rem; }

        .form-check.form-switch { padding-left: 2.6em; }
        #lokasiSelect { font-size: .9rem; }
    }

    /* ============================================================ */
    /* EXTRA SMALL (≤ 575px) */
    /* ============================================================ */
    @media (max-width: 575.98px) {
        .container-fluid { padding-left: .4rem; padding-right: .4rem; }
        .card-body { padding: .75rem !important; }

        .btn-absensi { height: 82px !important; padding: 5px !important; }
        .btn-absensi i { font-size: 1.2rem !important; margin-bottom: 3px !important; }
        .btn-title { font-size: .75rem !important; margin-bottom: 1px !important; }
        .btn-subtitle { font-size: .58rem !important; line-height: 1.1 !important; }

        .table { font-size: .75rem; }
        .table > :not(caption) > * > * { padding: .45rem .35rem; }
        .badge { font-size: .62rem; padding: .3em .5em; }

        .alert { font-size: .82rem; padding: .65rem .8rem; }
        .alert-heading { font-size: .88rem; }
    }
</style>

<div class="container-fluid py-3 px-2 px-md-4">

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Peringatan!</strong> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Informasi:</strong> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Validasi Error!</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y mb-6">

            {{-- Welcome Card --}}
            <div class="row">
                <div class="col-lg-12 mb-3 order-0">
                    <div class="card shadow-sm border-0 mb-3 card-hover" style="border-left: 4px solid var(--accent) !important;">
                        <div class="d-flex align-items-end row g-0">
                            <div class="col-12 col-sm-7">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold" style="color: var(--text-primary);">
                                        <i class="fas fa-satellite-dish text-primary me-2"></i>Absensi PKL
                                    </h5>
                                    <p class="mb-4 text-muted">Jangan lupa untuk melakukan absensi setiap hari. Pastikan Anda berada tepat di radius IDUKA.</p>

                                    @auth
                                        @if (Auth::user()->idukaDiterima)
                                            {{-- ✅ UPDATE: Tampilan Info IDUKA diubah jadi Grid Vertikal biar rapi di HP --}}
                                            <div class="alert alert-info mt-3 mb-0 p-3">
                                                <div class="d-flex flex-column gap-2">
                                                    <div>
                                                        <span class="text-muted d-block" style="font-size: 0.8rem;">IDUKA Anda:</span>
                                                        <span class="fw-bold text-dark">{{ Auth::user()->idukaDiterima->nama }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-muted d-block" style="font-size: 0.8rem;">Lokasi:</span>
                                                        <span class="fw-bold text-dark">{{ Auth::user()->idukaDiterima->alamat }}</span>
                                                    </div>
                                                    <div class="row g-2 mt-1">
                                                        <div class="col-6">
                                                            <span class="text-muted d-block" style="font-size: 0.8rem;">Radius:</span>
                                                            <span class="fw-bold text-dark">{{ Auth::user()->idukaDiterima->radius ?? '-' }} meter</span>
                                                        </div>
                                                        <div class="col-6">
                                                            <span class="text-muted d-block" style="font-size: 0.8rem;">Jam Masuk:</span>
                                                            <span class="fw-bold text-dark">
                                                                {{ Auth::user()->idukaDiterima->jam_masuk ? date('H:i', strtotime(Auth::user()->idukaDiterima->jam_masuk)) : '-' }}
                                                            </span>
                                                        </div>
                                                        <div class="col-6">
                                                            <span class="text-muted d-block" style="font-size: 0.8rem;">Jam Pulang:</span>
                                                            <span class="fw-bold text-dark">
                                                                {{ Auth::user()->idukaDiterima->jam_pulang ? date('H:i', strtotime(Auth::user()->idukaDiterima->jam_pulang)) : '-' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-warning mb-0">
                                                Anda belum terdaftar di IDUKA manapun. Silakan hubungi administrator.
                                            </div>
                                        @endif

                                        @if(isset($pkEnded) && $pkEnded)
                                            <div class="alert alert-warning mt-3 mb-0">
                                                <i class="fas fa-info-circle me-2"></i>
                                                <strong>Periode PKL telah selesai.</strong> Absensi tidak lagi dibuka.
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                            <div class="col-sm-5 text-center welcome-image-col">
                                <div class="card-body pb-0 px-md-4">
                                    <img src="{{ asset('snet/assets/img/illustrations/man-with-laptop-light.png') }}"
                                        height="140" alt="Absensi Siswa"
                                        data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                        data-app-light-img="illustrations/man-with-laptop-light.png" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Alert hari libur --}}
            @if ($isHoliday)
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="card shadow-sm border-0 mb-3" style="border-left: 4px solid #ef4444 !important;">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="card-title text-danger mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>Hari Libur
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-danger mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Peringatan!</strong> Hari ini adalah hari libur ({{ $holidayLabel }}). Absensi ditutup.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Status Absensi Hari Ini --}}
            @if ($absensiHariIni)
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="card shadow-sm border-0 mb-3 card-hover" style="border-left: 4px solid #22c55e !important;">
                            <div class="card-header bg-white border-bottom pb-3">
                                <h5 class="card-title text-success mb-0 fw-bold">
                                    <i class="fas fa-check-circle me-2"></i>Status Absensi Hari Ini
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    @if ($absensiHariIni->status === 'izin')
                                        <div class="col-12">
                                            <div class="alert alert-info mb-0">
                                                <i class="fas fa-info-circle me-2"></i>
                                                <strong>Anda sedang izin hari ini</strong><br>
                                                Alasan: {{ $absensiHariIni->keterangan_izin }}<br>
                                                Waktu pengajuan: {{ $absensiHariIni->created_at->format('d/m/Y H:i') }}
                                            </div>
                                        </div>
                                    @elseif($absensiHariIni && $absensiHariIni->status_dinas === 'disetujui')
                                        <div class="col-12">
                                            <div class="alert alert-primary mb-0">
                                                <i class="bi bi-briefcase me-2"></i>
                                                <strong>Anda sedang dinas luar hari ini</strong><br>
                                                Jenis: {{ ucfirst(str_replace('_', ' ', $absensiHariIni->jenis_dinas)) }}<br>
                                                Alasan: {{ $absensiHariIni->keterangan_dinas }}<br>
                                                <small class="text-muted">Anda bisa langsung absen pulang tanpa harus absen masuk terlebih dahulu. Absen pulang dapat dilakukan dari mana saja setelah jam 12.00 siang.</small>
                                            </div>
                                        </div>
                                    @else
                                        @if ($absensiHariIni->jam_masuk)
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start mb-3">
                                                    <div class="flex-shrink-0">
                                                        <i class="fas fa-clock text-success" style="font-size: 24px;"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-1">Absen Masuk</h6>
                                                        <p class="mb-0 text-success fw-bold">
                                                            {{ $absensiHariIni->jam_masuk->format('H:i') }}
                                                        </p>
                                                        <small class="text-muted d-block">
                                                            Status:
                                                            @if ($absensiHariIni->status === 'tepat_waktu')
                                                                <span class="badge bg-success">Tepat Waktu</span>
                                                            @else
                                                                <span class="badge bg-warning">Terlambat</span>
                                                            @endif
                                                            @if ($absensiHariIni->status_dinas === 'disetujui')
                                                                <span class="badge bg-primary ms-1">Dinas Luar</span>
                                                            @endif
                                                        </small>
                                                        <small class="text-muted">
                                                            Batas: {{ Auth::user()->idukaDiterima->jam_masuk ? Auth::user()->idukaDiterima->jam_masuk->format('H:i') : '08:00' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($absensiHariIni->jam_pulang)
                                            <div class="col-12 col-md-6">
                                                <div class="d-flex align-items-start mb-3">
                                                    <div class="flex-shrink-0">
                                                        <i class="fas fa-history text-warning" style="font-size: 24px;"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-1">Absen Pulang</h6>
                                                        <p class="mb-0 text-warning fw-bold">
                                                            {{ $absensiHariIni->jam_pulang->format('H:i') }}
                                                        </p>
                                                        <small class="text-muted">
                                                            @if ($absensiHariIni->status_dinas === 'disetujui')
                                                                <span class="badge bg-primary">Dinas Luar</span>
                                                            @endif
                                                            Minimal: {{ Auth::user()->idukaDiterima->jam_pulang ? Auth::user()->idukaDiterima->jam_pulang->format('H:i') : '15:00' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif ($hasPendingIzin || $hasPendingDinas || $hasPendingMasuk || $hasPendingPulang)
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="card shadow-sm border-0 mb-3 card-hover" style="border-left: 4px solid #f59e0b !important;">
                            <div class="card-header bg-white border-bottom pb-3">
                                <h5 class="card-title text-warning mb-0 fw-bold">
                                    <i class="fas fa-history me-2"></i>Status Menunggu Konfirmasi
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    @if ($hasPendingMasuk)
                                        <div class="col-12 col-md-6">
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-clock text-warning" style="font-size: 24px;"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1">Absen Masuk</h6>
                                                    <p class="mb-0 text-warning fw-bold">Menunggu Konfirmasi</p>
                                                    <small class="text-muted">
                                                        <span class="badge bg-warning">Pending</span>
                                                        <span class="ms-2">
                                                            Diajukan: {{ $absensiPending->where('jenis', 'masuk')->first()->jam->format('H:i') }}
                                                        </span>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($hasPendingPulang)
                                        <div class="col-12 col-md-6">
                                            <div class="d-flex align-items-start mb-3">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-history text-warning" style="font-size: 24px;"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-1">Absen Pulang</h6>
                                                    <p class="mb-0 text-warning fw-bold">Menunggu Konfirmasi</p>
                                                    <small class="text-muted">
                                                        <span class="badge bg-warning">Pending</span>
                                                        <span class="ms-2">
                                                            Diajukan: {{ $absensiPending->where('jenis', 'pulang')->first()->jam->format('H:i') }}
                                                        </span>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($hasPendingIzin)
                                        <div class="col-12">
                                            <div class="alert alert-info mb-0">
                                                <i class="fas fa-info-circle me-2"></i>
                                                <strong>Izin menunggu konfirmasi</strong><br>
                                                Jenis: {{ ucfirst(str_replace('_', ' ', $izinPending->jenis_izin)) }}<br>
                                                Alasan: {{ $izinPending->keterangan }}<br>
                                                <small class="text-muted">Diajukan: {{ $izinPending->created_at->format('d/m/Y H:i') }}</small>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($hasPendingDinas)
                                        <div class="col-12">
                                            <div class="alert alert-primary mb-0">
                                                <i class="bi bi-briefcase me-2"></i>
                                                <strong>Dinas luar menunggu konfirmasi</strong><br>
                                                Jenis: {{ ucfirst(str_replace('_', ' ', $dinasPending->jenis_dinas)) }}<br>
                                                Alasan: {{ $dinasPending->keterangan }}<br>
                                                <small class="text-muted">Diajukan: {{ $dinasPending->created_at->format('d/m/Y H:i') }}</small>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Tombol Absensi, Izin, dan Dinas Luar --}}
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0 mb-3 card-hover">
                        <div class="card-header border-bottom mb-3 pb-3">
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="fas fa-street-view text-primary me-2"></i>Panel Tindakan Absensi
                            </h5>
                            <small class="text-muted">Pastikan GPS aktif dan Anda berada dalam radius target</small>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="locationSwitch"
                                    {{ $absensiHariIni && ($absensiHariIni->status === 'izin' || ($absensiHariIni->jam_masuk && $absensiHariIni->jam_pulang)) ? 'disabled' : '' }}
                                    {{ $isHoliday ? 'disabled' : '' }}>
                                <label class="form-check-label" for="locationSwitch">Akses Lokasi</label>
                            </div>
                            <small id="locationStatus" class="text-muted d-block mt-1">
                                @if ($isHoliday)
                                    Hari ini adalah hari libur ({{ $holidayLabel }}). Absensi ditutup.
                                @elseif ($absensiHariIni && $absensiHariIni->status === 'izin')
                                    Anda sedang izin hari ini
                                @elseif ($absensiHariIni && $absensiHariIni->status_dinas === 'disetujui')
                                    Anda sedang dinas luar hari ini - wajib absensi masuk dan pulang
                                @elseif ($absensiHariIni && $absensiHariIni->jam_masuk && $absensiHariIni->jam_pulang)
                                    Absensi hari ini sudah lengkap
                                @else
                                    Menunggu akses lokasi...
                                @endif
                            </small>
                        </div>
                        <div class="card-body">
                            {{-- Pemilihan Lokasi --}}
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="lokasiSelect" class="form-label">Pilih Lokasi Absensi:</label>
                                    <select class="form-select" id="lokasiSelect" {{ $isHoliday ? 'disabled' : '' }}>
                                        <option value="pusat">Lokasi Pusat</option>
                                        @if (Auth::user()->idukaDiterima && Auth::user()->idukaDiterima->is_pusat)
                                            @foreach (Auth::user()->idukaDiterima->cabangs as $cabang)
                                                <option value="cabang_{{ $cabang->id }}">{{ $cabang->nama }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            {{-- Tombol-tombol Absensi --}}
                            <div class="row g-2 g-md-3">
                                {{-- Tombol Absen Masuk --}}
                                <div class="col-6 col-md-3">
                                    <form method="POST" action="{{ route('absensi.masuk') }}" id="formMasuk">
                                        @csrf
                                        <input type="hidden" name="latitude" id="latitudeMasuk">
                                        <input type="hidden" name="longitude" id="longitudeMasuk">
                                        <input type="hidden" name="accuracy" id="accuracyMasuk">
                                        <input type="hidden" name="lokasi_id" id="lokasiIdMasuk" value="pusat">

                                        <button type="submit" class="btn btn-success w-100 btn-absensi" id="btnMasuk"
                                            {{ $absensiHariIni && ($absensiHariIni->jam_masuk || $hasApprovedIzin || $hasApprovedDinas) ? 'disabled' : '' }}
                                            {{ $hasPendingIzin || $hasPendingDinas || $hasPendingMasuk ? 'disabled' : '' }}
                                            {{ $isHoliday ? 'disabled' : '' }}>
                                            <div class="btn-content">
                                                <i class="fas fa-clock"></i>
                                                <span class="btn-title">Absen Masuk</span>
                                                <span class="btn-subtitle">
                                                    @if ($isHoliday)
                                                        Hari libur
                                                    @elseif ($absensiHariIni && $absensiHariIni->jam_masuk)
                                                        Sudah absen: {{ $absensiHariIni->jam_masuk->format('H:i') }}
                                                    @elseif ($hasApprovedIzin)
                                                        Sedang izin
                                                    @elseif ($hasApprovedDinas)
                                                        Sedang dinas luar
                                                    @elseif ($hasPendingIzin)
                                                        Izin menunggu konfirmasi
                                                    @elseif ($hasPendingDinas)
                                                        Dinas menunggu konfirmasi
                                                    @elseif ($hasPendingMasuk)
                                                        Absen masuk menunggu konfirmasi
                                                    @else
                                                        Klik untuk absen masuk
                                                    @endif
                                                </span>
                                            </div>
                                        </button>
                                    </form>
                                </div>

                                {{-- Tombol Absen Pulang --}}
                                <div class="col-6 col-md-3">
                                    <form method="POST" action="{{ route('absensi.pulang.siswa') }}" id="formPulang">
                                        @csrf
                                        <input type="hidden" name="latitude" id="latitudePulang" value="0">
                                        <input type="hidden" name="longitude" id="longitudePulang" value="0">
                                        <input type="hidden" name="accuracy" id="accuracyPulang" value="0">
                                        <input type="hidden" name="lokasi_id" id="lokasiIdPulang" value="pusat">

                                        <button type="submit" class="btn btn-warning w-100 btn-absensi" id="btnPulang"
                                            {{ !$absensiHariIni || !$absensiHariIni->jam_masuk || ($absensiHariIni && $absensiHariIni->jam_pulang) || $hasApprovedIzin ? 'disabled' : '' }}
                                            {{ $hasPendingIzin || $hasPendingPulang ? 'disabled' : '' }}
                                            {{ (!$hasApprovedDinas && $hasPendingDinas) ? 'disabled' : '' }}
                                            {{ $isHoliday ? 'disabled' : '' }}>
                                            <div class="btn-content">
                                                <i class="fas fa-clock-rotate-left"></i>
                                                <span class="btn-title">Absen Pulang</span>
                                                <span class="btn-subtitle">
                                                    @if ($isHoliday)
                                                        Hari libur
                                                    @elseif ($absensiHariIni && $absensiHariIni->jam_pulang)
                                                        Sudah absen: {{ $absensiHariIni->jam_pulang->format('H:i') }}
                                                    @elseif ($hasApprovedIzin)
                                                        Sedang izin
                                                    @elseif ($hasApprovedDinas)
                                                        @if ($absensiHariIni && $absensiHariIni->jam_pulang)
                                                            Sudah absen: {{ $absensiHariIni->jam_pulang->format('H:i') }}
                                                        @else
                                                            Klik untuk absen pulang
                                                        @endif
                                                    @elseif ($hasPendingIzin)
                                                        Izin menunggu konfirmasi
                                                    @elseif ($hasPendingDinas)
                                                        Dinas menunggu konfirmasi
                                                    @elseif ($hasPendingPulang)
                                                        Absen pulang menunggu konfirmasi
                                                    @elseif (!$absensiHariIni || !$absensiHariIni->jam_masuk)
                                                        Harus absen masuk dulu
                                                    @else
                                                        Klik untuk absen pulang
                                                    @endif
                                                </span>
                                            </div>
                                        </button>
                                    </form>
                                </div>

                                {{-- Tombol Izin --}}
                                <div class="col-6 col-md-3">
                                    <button type="button" class="btn btn-info w-100 btn-absensi" id="btnIzin"
                                        {{ $absensiHariIni ? 'disabled' : '' }}
                                        {{ $hasPendingIzin || $hasApprovedIzin ? 'disabled' : '' }}
                                        {{ $hasPendingDinas || $hasApprovedDinas ? 'disabled' : '' }}
                                        {{ $hasPendingMasuk || $hasPendingPulang ? 'disabled' : '' }}
                                        {{ $isHoliday ? 'disabled' : '' }}>
                                        <div class="btn-content">
                                            <i class="fas fa-file-alt"></i>
                                            <span class="btn-title">Izin</span>
                                            <span class="btn-subtitle">
                                                @if ($isHoliday)
                                                    Hari libur
                                                @elseif ($hasApprovedIzin)
                                                    Sudah izin
                                                @elseif ($hasPendingIzin)
                                                    Izin menunggu konfirmasi
                                                @elseif ($absensiHariIni)
                                                    Ada absensi
                                                @elseif ($hasPendingDinas || $hasApprovedDinas)
                                                    Sedang dinas
                                                @elseif ($hasPendingMasuk)
                                                    Ada absen masuk pending
                                                @elseif ($hasPendingPulang)
                                                    Ada absen pulang pending
                                                @else
                                                    Tidak masuk
                                                @endif
                                            </span>
                                        </div>
                                    </button>
                                </div>

                                {{-- Tombol Dinas Luar --}}
                                <div class="col-6 col-md-3">
                                    <button type="button" class="btn btn-primary w-100 btn-absensi" id="btnDinas"
                                        {{ $absensiHariIni ? 'disabled' : '' }}
                                        {{ $hasPendingDinas || $hasApprovedDinas ? 'disabled' : '' }}
                                        {{ $hasPendingIzin || $hasApprovedIzin ? 'disabled' : '' }}
                                        {{ $hasPendingMasuk || $hasPendingPulang ? 'disabled' : '' }}
                                        {{ $isHoliday ? 'disabled' : '' }}>
                                        <div class="btn-content">
                                            <i class="fas fa-briefcase"></i>
                                            <span class="btn-title">Dinas Luar</span>
                                            <span class="btn-subtitle">
                                                @if ($isHoliday)
                                                    Hari libur
                                                @elseif ($hasApprovedDinas)
                                                    Sedang dinas
                                                @elseif ($hasPendingDinas)
                                                    Dinas menunggu konfirmasi
                                                @elseif ($absensiHariIni)
                                                    Ada absensi
                                                @elseif ($hasPendingIzin || $hasApprovedIzin)
                                                    Sedang izin
                                                @elseif ($hasPendingMasuk)
                                                    Ada absen masuk pending
                                                @elseif ($hasPendingPulang)
                                                    Ada absen pulang pending
                                                @else
                                                    Tugas luar
                                                @endif
                                            </span>
                                        </div>
                                    </button>
                                </div>
                            </div>

                            {{-- Info Lokasi --}}
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="alert alert-info mb-0">
                                        <h6 class="alert-heading mb-2">Info Lokasi:</h6>
                                        <div id="locationInfo">
                                            @if ($isHoliday)
                                                <p class="mb-1">Status: <span id="distanceStatus" class="text-danger">Hari libur ({{ $holidayLabel }})</span></p>
                                                <p class="mb-1">Jarak: <span id="distanceValue">-</span></p>
                                                <p class="mb-0">Lokasi Anda: <span id="userLocation">-</span></p>
                                            @else
                                                <p class="mb-1">Status: <span id="distanceStatus">Menunggu akses lokasi</span></p>
                                                <p class="mb-1">Jarak: <span id="distanceValue">-</span></p>
                                                <p class="mb-0">Lokasi Anda: <span id="userLocation">-</span></p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Izin --}}
            <div class="row mb-4" id="formIzinContainer" style="display: none;">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0 mb-3 card-hover" style="border-left: 4px solid #06b6d4 !important;">
                        <div class="card-header bg-white border-bottom pb-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title text-info mb-0 fw-bold">
                                <i class="fas fa-envelope-open-text me-2"></i>Form Pengajuan Izin
                            </h5>
                            <button type="button" class="btn-close" id="closeIzinForm"></button>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('absensi.izin') }}" id="formIzin">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="jenisIzin" class="form-label">Jenis Izin</label>
                                            <select class="form-select" id="jenisIzin" name="jenis_izin" required>
                                                <option value="">Pilih jenis izin</option>
                                                <option value="sakit">Sakit</option>
                                                <option value="keperluan_keluarga">Keperluan Keluarga</option>
                                                <option value="keperluan_sekolah">Keperluan Sekolah</option>
                                                <option value="lainnya">Lainnya</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="keteranganIzin" class="form-label">Keterangan/Alasan</label>
                                            <textarea class="form-control" id="keteranganIzin" name="keterangan" rows="2"
                                                placeholder="Jelaskan alasan izin Anda..." required></textarea>
                                            <div class="form-text">Minimal 10 karakter, maksimal 500 karakter</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Perhatian:</strong> Setelah mengajukan izin, Anda tidak bisa melakukan absensi masuk/pulang hari ini.
                                </div>
                                <div class="d-flex justify-content-end gap-2 flex-wrap">
                                    <button type="button" class="btn btn-secondary" id="cancelIzin">Batal</button>
                                    <button type="submit" class="btn btn-info">Ajukan Izin</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Dinas Luar --}}
            <div class="row mb-4" id="formDinasContainer" style="display: none;">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0 mb-3 card-hover" style="border-left: 4px solid #3b82f6 !important;">
                        <div class="card-header bg-white border-bottom pb-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title text-primary mb-0 fw-bold">
                                <i class="fas fa-briefcase me-2"></i>Form Pengajuan Dinas Luar
                            </h5>
                            <button type="button" class="btn-close" id="closeDinasForm"></button>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('absensi.dinas-luar') }}" id="formDinas">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="jenisDinas" class="form-label">Jenis Dinas</label>
                                            <select class="form-select" id="jenisDinas" name="jenis_dinas" required>
                                                <option value="">Pilih jenis dinas</option>
                                                <option value="perusahaan">Perusahaan</option>
                                                <option value="sekolah">Sekolah</option>
                                                <option value="instansi_pemerintah">Instansi Pemerintah</option>
                                                <option value="lainnya">Lainnya</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="keteranganDinas" class="form-label">Keterangan/Alasan</label>
                                            <textarea class="form-control" id="keteranganDinas" name="keterangan" rows="2"
                                                placeholder="Jelaskan alasan dinas luar Anda..." required></textarea>
                                            <div class="form-text">Minimal 10 karakter, maksimal 500 karakter</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Informasi:</strong> Setelah mengajukan dinas luar dan disetujui, Anda tetap wajib melakukan absensi pulang seperti biasa.
                                </div>
                                <div class="d-flex justify-content-end gap-2 flex-wrap">
                                    <button type="button" class="btn btn-secondary" id="cancelDinas">Batal</button>
                                    <button type="submit" class="btn btn-primary">Ajukan Dinas Luar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol untuk menampilkan riwayat --}}
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0 mb-3 card-hover">
                        <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 pb-3">
                            <h5 class="card-title mb-0 fw-bold">
                                <i class="fas fa-list-alt text-primary me-2"></i>Riwayat Absensi
                            </h5>
                            <button type="button" class="btn btn-primary px-3" id="toggleRiwayat">
                                <i class="fas fa-eye me-2"></i>Lihat Riwayat
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Riwayat Absensi --}}
            <div class="row mb-4" id="riwayatContainer" style="display: none;">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h5 class="card-title mb-0">Daftar Riwayat Absensi</h5>
                            <button type="button" class="btn btn-secondary btn-sm" id="hideRiwayat">
                                <i class="fas fa-eye-slash me-2"></i>Sembunyikan
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-scroll-hint">
                                <i class="bi bi-arrow-left-right"></i> Geser tabel ke kiri/kanan untuk melihat semua kolom
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jam Masuk</th>
                                            <th>Jam Pulang</th>
                                            <th>Status</th>
                                            <th>Lokasi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="riwayatAbsensiTable">
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                <div class="spinner-border spinner-border-sm me-2" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                Memuat data riwayat absensi...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 mt-3">
                                <div class="small text-muted">
                                    Menampilkan <span id="showingCount">0</span> dari <span id="totalCount">0</span> data
                                </div>
                                <nav aria-label="Page navigation" class="pagination-wrap">
                                    <ul class="pagination pagination-sm mb-0" id="paginationRiwayat">
                                        <!-- Pagination will be inserted here -->
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail Absensi --}}
            <div class="row mb-4" id="detailAbsensiContainer" style="display: none;">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0 mb-3" style="border-left: 4px solid #3b82f6 !important;">
                        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                            <h5 class="card-title text-primary mb-0">
                                <i class="fas fa-info-circle me-2"></i>Detail Absensi
                            </h5>
                            <button type="button" class="btn-close" id="closeDetailAbsensi"></button>
                        </div>
                        <div class="card-body" id="detailAbsensiContent">
                            <!-- Detail content will be inserted here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .izin-status-alert,
    .dinas-status-alert {
        margin-bottom: 1rem;
    }

    .btn-absensi {
        height: 120px !important;
        padding: 12px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        border-radius: 8px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
    }

    .btn-absensi i {
        font-size: 1.8rem;
        margin-bottom: 8px;
        line-height: 1;
    }

    .btn-title {
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .btn-subtitle {
        font-size: 0.75rem;
        line-height: 1.2;
        max-width: 100%;
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal;
    }

    .btn-absensi:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .btn-absensi:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    #riwayatAbsensiTable tr {
        cursor: pointer;
    }

    #riwayatAbsensiTable tr:hover {
        background-color: rgba(0, 123, 255, 0.1);
    }

    #riwayatContainer,
    #detailAbsensiContainer {
        transition: all 0.3s ease;
    }
</style>

<script>
    let currentPosition = null;
    let watchId = null;
    let riwayatData = [];
    let currentPage = 1;
    let perPage = 10;
    let riwayatVisible = false;

    const absensiHariIni = @json($absensiHariIni);
    const sudahAbsenMasuk = absensiHariIni && absensiHariIni.jam_masuk;
    const sudahAbsenPulang = absensiHariIni && absensiHariIni.jam_pulang;
    const sedangIzin = absensiHariIni && absensiHariIni.status === 'izin';
    const sedangDinas = absensiHariIni && absensiHariIni.status_dinas === 'disetujui';

    const hasPendingIzin = @json($hasPendingIzin ?? false);
    const hasPendingDinas = @json($hasPendingDinas ?? false);
    const hasApprovedIzin = @json($hasApprovedIzin ?? false);
    const hasApprovedDinas = @json($hasApprovedDinas ?? false);

    const hasPendingMasuk = @json($absensiPending && $absensiPending->where('jenis', 'masuk')->count() > 0);
    const hasPendingPulang = @json($absensiPending && $absensiPending->where('jenis', 'pulang')->count() > 0);

    const isHoliday = @json($isHoliday ?? false);
    const holidayLabel = @json($holidayLabel ?? '');
    const pkEnded = @json($pkEnded ?? false);

    const locationSwitch = document.getElementById('locationSwitch');
    const locationStatus = document.getElementById('locationStatus');
    const distanceStatus = document.getElementById('distanceStatus');
    const distanceValue = document.getElementById('distanceValue');
    const userLocation = document.getElementById('userLocation');
    const btnMasuk = document.getElementById('btnMasuk');
    const btnPulang = document.getElementById('btnPulang');
    const btnIzin = document.getElementById('btnIzin');
    const btnDinas = document.getElementById('btnDinas');
    const latitudeMasuk = document.getElementById('latitudeMasuk');
    const longitudeMasuk = document.getElementById('longitudeMasuk');
    const accuracyMasuk = document.getElementById('accuracyMasuk');
    const latitudePulang = document.getElementById('latitudePulang');
    const longitudePulang = document.getElementById('longitudePulang');
    const accuracyPulang = document.getElementById('accuracyPulang');
    const lokasiSelect = document.getElementById('lokasiSelect');
    const lokasiIdMasuk = document.getElementById('lokasiIdMasuk');
    const lokasiIdPulang = document.getElementById('lokasiIdPulang');
    const formIzinContainer = document.getElementById('formIzinContainer');
    const closeIzinForm = document.getElementById('closeIzinForm');
    const cancelIzin = document.getElementById('cancelIzin');
    const formDinasContainer = document.getElementById('formDinasContainer');
    const closeDinasForm = document.getElementById('closeDinasForm');
    const cancelDinas = document.getElementById('cancelDinas');
    const toggleRiwayat = document.getElementById('toggleRiwayat');
    const hideRiwayat = document.getElementById('hideRiwayat');
    const riwayatContainer = document.getElementById('riwayatContainer');
    const riwayatAbsensiTable = document.getElementById('riwayatAbsensiTable');
    const showingCount = document.getElementById('showingCount');
    const totalCount = document.getElementById('totalCount');
    const paginationRiwayat = document.getElementById('paginationRiwayat');
    const detailAbsensiContainer = document.getElementById('detailAbsensiContainer');
    const closeDetailAbsensi = document.getElementById('closeDetailAbsensi');
    const detailAbsensiContent = document.getElementById('detailAbsensiContent');

    @auth
        @if (Auth::user()->idukaDiterima && Auth::user()->idukaDiterima->latitude && Auth::user()->idukaDiterima->longitude)
            const idukaLat = {{ Auth::user()->idukaDiterima->latitude }};
            const idukaLng = {{ Auth::user()->idukaDiterima->longitude }};
            const allowedRadius = {{ Auth::user()->idukaDiterima->radius ?? 100 }};
            const hasValidIduka = true;
            const jamMasukIduka = "{{ Auth::user()->idukaDiterima->jam_masuk ? Auth::user()->idukaDiterima->jam_masuk->format('H:i') : '08:00' }}";
            const jamPulangIduka = "{{ Auth::user()->idukaDiterima->jam_pulang ? Auth::user()->idukaDiterima->jam_pulang->format('H:i') : '15:00' }}";
        @else
            const idukaLat = null;
            const idukaLng = null;
            const allowedRadius = 100;
            const hasValidIduka = false;
            const jamMasukIduka = "08:00";
            const jamPulangIduka = "15:00";
        @endif
    @else
        const idukaLat = null;
        const idukaLng = null;
        const allowedRadius = 100;
        const hasValidIduka = false;
        const jamMasukIduka = "08:00";
        const jamPulangIduka = "15:00";
    @endauth

    const jamMasukAllowStart = (function() {
        try {
            const [h, m] = jamMasukIduka.split(':').map(Number);
            const d = new Date();
            d.setHours(h, m, 0, 0);
            d.setTime(d.getTime() - 60 * 60 * 1000);
            return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        } catch (e) {
            return jamMasukIduka;
        }
    })();

    const cabangs = @json(Auth::user()->idukaDiterima && Auth::user()->idukaDiterima->is_pusat ? Auth::user()->idukaDiterima->cabangs : []);

    toggleRiwayat.addEventListener('click', function() { showRiwayat(); });
    hideRiwayat.addEventListener('click', function() { hideRiwayatSection(); });

    lokasiSelect.addEventListener('change', function () {
        const selectedValue = this.value;
        lokasiIdMasuk.value = selectedValue;
        lokasiIdPulang.value = selectedValue;
        updateLocationInfo(currentPosition);
    });

    btnIzin.addEventListener('click', function() {
        if (this.disabled) return;
        if (isHoliday) { alert('Hari ini adalah hari libur (' + holidayLabel + '). Tidak bisa mengajukan izin.'); return; }
        formDinasContainer.style.display = 'none';
        if (formIzinContainer.style.display === 'none' || formIzinContainer.style.display === '') {
            formIzinContainer.style.display = 'block';
            document.getElementById('formIzin').reset();
            cekStatusIzin();
            formIzinContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } else {
            formIzinContainer.style.display = 'none';
        }
    });

    btnDinas.addEventListener('click', function() {
        if (this.disabled) return;
        if (isHoliday) { alert('Hari ini adalah hari libur (' + holidayLabel + '). Tidak bisa mengajukan dinas luar.'); return; }
        formIzinContainer.style.display = 'none';
        if (formDinasContainer.style.display === 'none' || formDinasContainer.style.display === '') {
            formDinasContainer.style.display = 'block';
            document.getElementById('formDinas').reset();
            cekStatusDinas();
            formDinasContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } else {
            formDinasContainer.style.display = 'none';
        }
    });

    closeIzinForm.addEventListener('click', function() { formIzinContainer.style.display = 'none'; });
    cancelIzin.addEventListener('click', function() { formIzinContainer.style.display = 'none'; });
    closeDinasForm.addEventListener('click', function() { formDinasContainer.style.display = 'none'; });
    cancelDinas.addEventListener('click', function() { formDinasContainer.style.display = 'none'; });
    closeDetailAbsensi.addEventListener('click', function() { hideDetailAbsensi(); });

    function showRiwayat() {
        riwayatContainer.style.display = 'block';
        riwayatVisible = true;
        if (riwayatData.length === 0) { loadRiwayatAbsensi(); }
        riwayatContainer.scrollIntoView({ behavior: 'smooth' });
    }

    function hideRiwayatSection() {
        riwayatContainer.style.display = 'none';
        riwayatVisible = false;
    }

    function showDetailAbsensi(id) {
        if (!riwayatVisible) { showRiwayat(); }
        const absensi = riwayatData.find(a => a.id == id);
        if (!absensi) return;
        formIzinContainer.style.display = 'none';
        formDinasContainer.style.display = 'none';
        detailAbsensiContainer.style.display = 'block';

        let html = `
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0" style="min-width: 300px;">
                            <tr><th width="40%">Tanggal</th><td>${formatDate(absensi.tanggal)}</td></tr>
                            <tr><th>Hari</th><td>${formatDay(absensi.tanggal)}</td></tr>
                            <tr><th>Status</th><td>${getStatusBadge(absensi)}</td></tr>
                            <tr><th>Lokasi</th><td>${absensi.lokasiIduka ? absensi.lokasiIduka.nama : '-'}</td></tr>
                        </table>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0" style="min-width: 300px;">
                            <tr><th width="40%">Jam Masuk</th><td>${absensi.jam_masuk ? formatTime(absensi.jam_masuk) : '-'}</td></tr>
                            <tr><th>Jam Pulang</th><td>${absensi.jam_pulang ? formatTime(absensi.jam_pulang) : '-'}</td></tr>
                            <tr><th>Durasi Kerja</th><td>${calculateDuration(absensi.jam_masuk, absensi.jam_pulang)}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
        `;

        if (absensi.status === 'izin') {
            html += `
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card card-info">
                            <div class="card-header"><h6 class="card-title mb-0">Informasi Izin</h6></div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0" style="min-width: 300px;">
                                        <tr><th width="40%">Jenis</th><td>${formatJenisIzin(absensi.jenis_izin)}</td></tr>
                                        <tr><th>Keterangan</th><td>${absensi.keterangan_izin || '-'}</td></tr>
                                        <tr><th>Disetujui Oleh</th><td>${absensi.approved_by ? absensi.approvedBy.name : '-'}</td></tr>
                                        <tr><th>Tanggal Disetujui</th><td>${absensi.approved_at ? formatDateTime(absensi.approved_at) : '-'}</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        if (absensi.status_dinas === 'disetujui') {
            html += `
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card card-primary">
                            <div class="card-header"><h6 class="card-title mb-0">Informasi Dinas Luar</h6></div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0" style="min-width: 300px;">
                                        <tr><th width="40%">Jenis</th><td>${formatJenisDinas(absensi.jenis_dinas)}</td></tr>
                                        <tr><th>Keterangan</th><td>${absensi.keterangan_dinas || '-'}</td></tr>
                                        <tr><th>Disetujui Oleh</th><td>${absensi.approved_by ? absensi.approvedBy.name : '-'}</td></tr>
                                        <tr><th>Tanggal Disetujui</th><td>${absensi.approved_at ? formatDateTime(absensi.approved_at) : '-'}</td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        if (absensi.latitude && absensi.longitude) {
            html += `
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card card-primary">
                            <div class="card-header"><h6 class="card-title mb-0">Lokasi Absensi</h6></div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-borderless mb-0" style="min-width: 300px;">
                                                <tr><th width="40%">Koordinat Masuk</th><td>Lat: ${absensi.latitude}, Lng: ${absensi.longitude}</td></tr>
                                                ${absensi.latitude_pulang && absensi.longitude_pulang ? `
                                                <tr><th>Koordinat Pulang</th><td>Lat: ${absensi.latitude_pulang}, Lng: ${absensi.longitude_pulang}</td></tr>` : ''}
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div id="map-${absensi.id}" style="height: 300px; width: 100%; border-radius: 8px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            setTimeout(() => { initMap(absensi); }, 100);
        }

        detailAbsensiContent.innerHTML = html;
        detailAbsensiContainer.scrollIntoView({ behavior: 'smooth' });
    }

    function hideDetailAbsensi() {
        detailAbsensiContainer.style.display = 'none';
    }

    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000;
        const dLat = deg2rad(lat2 - lat1);
        const dLon = deg2rad(lon2 - lon1);
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) + Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    function deg2rad(deg) { return deg * (Math.PI / 180); }

    function validateJamPulang() {
        const now = new Date();
        const [jam, menit] = jamPulangIduka.split(':');
        const jamPulangDate = new Date();
        jamPulangDate.setHours(parseInt(jam), parseInt(menit), 0, 0);
        if (sedangDinas) { return { valid: true, message: '' }; }
        if (now < jamPulangDate) {
            return { valid: false, message: `Belum waktunya absen pulang. Absen pulang dibuka pukul ${jamPulangIduka}.` };
        }
        return { valid: true, message: '' };
    }

    function validateJamMasuk() {
        const now = new Date();
        const [jam, menit] = jamMasukIduka.split(':').map(Number);
        const jamMasukDate = new Date();
        jamMasukDate.setHours(jam, menit, 0, 0);
        const jamMasukStart = new Date(jamMasukDate.getTime() - 60 * 60 * 1000);
        if (now < jamMasukStart) {
            return { valid: false, message: `Absen masuk hanya dapat dilakukan mulai pukul ${jamMasukAllowStart}.` };
        }
        return { valid: true, message: '' };
    }

    function getLocation() {
        if ((sudahAbsenMasuk && sudahAbsenPulang) || sedangIzin) {
            locationStatus.textContent = sedangIzin ? "Sedang izin hari ini" : "Absensi hari ini sudah lengkap";
            return;
        }
        if (isHoliday) {
            locationStatus.textContent = "Hari ini adalah hari libur (" + holidayLabel + "). Absensi ditutup.";
            return;
        }
        if (navigator.geolocation) {
            locationStatus.textContent = sedangDinas ? "Sedang dinas luar, siap untuk absen pulang..." : "Mengambil lokasi...";
            navigator.geolocation.getCurrentPosition(showPosition, showError, { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 });
            watchId = navigator.geolocation.watchPosition(watchPosition, showError, { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 });
        } else {
            locationStatus.textContent = "Geolocation tidak didukung oleh browser ini.";
        }
    }

    function watchPosition(position) { currentPosition = position; updateLocationInfo(position); }
    function showPosition(position) { currentPosition = position; updateLocationInfo(position); }

    function updateLocationInfo(position) {
        if (!position) return;
        const userLat = position.coords.latitude;
        const userLng = position.coords.longitude;
        const accuracy = position.coords.accuracy;

        userLocation.textContent = userLat.toFixed(6) + ", " + userLng.toFixed(6);
        locationStatus.textContent = "Lokasi terdeteksi dengan akurasi ±" + Math.round(accuracy) + "m";

        if (sedangDinas) {
            distanceStatus.textContent = "✓ Anda sedang dinas luar, dapat absen pulang dari mana saja setelah jam 12.00";
            distanceStatus.className = "text-success";
            distanceValue.textContent = "Tidak dibatasi lokasi (dinas luar)";
            const now = new Date();
            const jam12 = new Date();
            jam12.setHours(12, 0, 0, 0);
            if (now >= jam12) { btnPulang.disabled = false; btnPulang.title = ''; }
            else { btnPulang.disabled = true; btnPulang.title = 'Absen pulang untuk dinas luar hanya dapat dilakukan setelah jam 12.00 siang.'; }
            return;
        }

        const selectedLokasiId = lokasiSelect.value;
        let targetLat, targetLng, targetRadius, lokasiName;

        if (selectedLokasiId === 'pusat') {
            targetLat = idukaLat; targetLng = idukaLng; targetRadius = allowedRadius; lokasiName = "Pusat";
        } else {
            const cabangId = parseInt(selectedLokasiId.replace('cabang_', ''));
            const selectedCabang = cabangs.find(c => c.id === cabangId);
            if (selectedCabang) {
                targetLat = parseFloat(selectedCabang.latitude);
                targetLng = parseFloat(selectedCabang.longitude);
                targetRadius = parseFloat(selectedCabang.radius) || 100;
                lokasiName = selectedCabang.nama;
            } else {
                targetLat = idukaLat; targetLng = idukaLng; targetRadius = allowedRadius; lokasiName = "Pusat";
            }
        }

        if (!hasValidIduka || targetLat === null || targetLng === null || targetLat === 0 || targetLng === 0) {
            distanceStatus.textContent = `Koordinat ${lokasiName} belum diatur`;
            distanceStatus.className = "text-warning";
            distanceValue.textContent = "Tidak dapat menghitung jarak";
            if (!sudahAbsenMasuk && !sedangIzin) btnMasuk.disabled = true;
            if (!sudahAbsenPulang && !sedangIzin && sudahAbsenMasuk) btnPulang.disabled = true;
            return;
        }

        const distance = calculateDistance(userLat, userLng, targetLat, targetLng);
        distanceValue.textContent = Math.round(distance) + " meter dari " + lokasiName + " (akurasi: ±" + Math.round(accuracy) + "m)";

        const effectiveRadius = targetRadius + accuracy;
        const isWithinRadius = distance <= effectiveRadius;

        if (isWithinRadius) {
            distanceStatus.textContent = `✓ Anda berada dalam radius yang diizinkan (${lokasiName})`;
            distanceStatus.className = "text-success";
            const jamValidation = validateJamMasuk();
            if (!sudahAbsenMasuk && !sedangIzin) {
                btnMasuk.disabled = !jamValidation.valid;
                btnMasuk.title = jamValidation.valid ? "" : jamValidation.message;
            }
            if (sudahAbsenMasuk && !sudahAbsenPulang && !sedangIzin) {
                const jamValidationPulang = validateJamPulang();
                btnPulang.disabled = !jamValidationPulang.valid;
                btnPulang.title = jamValidationPulang.valid ? "" : jamValidationPulang.message;
            }
        } else {
            distanceStatus.textContent = `✗ Anda berada di luar radius yang diizinkan (${lokasiName})`;
            distanceStatus.className = "text-danger";
            if (!sudahAbsenMasuk && !sedangIzin) btnMasuk.disabled = true;
            if (!sudahAbsenPulang && !sedangIzin) btnPulang.disabled = true;
        }

        if (latitudeMasuk) latitudeMasuk.value = userLat;
        if (longitudeMasuk) longitudeMasuk.value = userLng;
        if (accuracyMasuk) accuracyMasuk.value = accuracy;
        if (latitudePulang) latitudePulang.value = userLat;
        if (longitudePulang) longitudePulang.value = userLng;
        if (accuracyPulang) accuracyPulang.value = accuracy;
    }

    function showError(error) {
        let errorMessage = "";
        switch (error.code) {
            case error.PERMISSION_DENIED: errorMessage = "Pengguna menolak permintaan geolocation."; break;
            case error.POSITION_UNAVAILABLE: errorMessage = "Informasi lokasi tidak tersedia."; break;
            case error.TIMEOUT: errorMessage = "Permintaan lokasi sudah timeout."; break;
            case error.UNKNOWN_ERROR: errorMessage = "Error tidak diketahui: " + error.message; break;
        }
        locationStatus.textContent = errorMessage;
        distanceStatus.textContent = "Error lokasi";
        distanceValue.textContent = "-";
        userLocation.textContent = "-";
        if (!sudahAbsenMasuk && !sedangIzin) btnMasuk.disabled = true;
        if (!sudahAbsenPulang && !sedangIzin) btnPulang.disabled = true;
    }

    locationSwitch.addEventListener('change', function () {
        if (isHoliday) { this.checked = false; return; }
        if (this.checked) {
            getLocation();
            updateButtonStates();
        } else {
            if (watchId !== null) { navigator.geolocation.clearWatch(watchId); watchId = null; }
            locationStatus.textContent = "Akses lokasi dimatikan";
            distanceStatus.textContent = "Akses lokasi dimatikan";
            distanceValue.textContent = "-";
            userLocation.textContent = "-";
            if (!sudahAbsenMasuk && !sedangIzin) btnMasuk.disabled = true;
            if (!sudahAbsenPulang && !sedangIzin) btnPulang.disabled = true;
            updateButtonStates();
        }
    });

    document.getElementById('formMasuk').addEventListener('submit', function (e) {
        if (isHoliday) { e.preventDefault(); alert('Hari ini adalah hari libur (' + holidayLabel + '). Absensi ditutup.'); return false; }
        if (!locationSwitch.checked) { e.preventDefault(); alert('Silakan aktifkan akses lokasi terlebih dahulu.'); return false; }
        if (hasPendingIzin || hasApprovedIzin) { e.preventDefault(); alert(hasApprovedIzin ? 'Anda sedang izin hari ini.' : 'Anda sudah mengajukan izin. Menunggu konfirmasi.'); return false; }
        if (hasPendingDinas || hasApprovedDinas) { e.preventDefault(); alert(hasApprovedDinas ? 'Anda sedang dinas luar hari ini.' : 'Anda sudah mengajukan dinas luar. Menunggu konfirmasi.'); return false; }
        if (sudahAbsenMasuk || sedangIzin) { e.preventDefault(); alert(sedangIzin ? 'Anda sedang izin hari ini.' : 'Anda sudah melakukan absen masuk hari ini.'); return false; }
        if (!currentPosition) { e.preventDefault(); alert('Lokasi tidak terdeteksi. Silakan aktifkan akses lokasi.'); return false; }
        const jamValidation = validateJamMasuk();
        if (!jamValidation.valid) { e.preventDefault(); alert(jamValidation.message); return false; }

        const selectedLokasiId = lokasiSelect.value;
        let targetLat, targetLng, targetRadius, lokasiName;
        if (selectedLokasiId === 'pusat') { targetLat = idukaLat; targetLng = idukaLng; targetRadius = allowedRadius; lokasiName = "Pusat"; }
        else {
            const cabangId = parseInt(selectedLokasiId.replace('cabang_', ''));
            const selectedCabang = cabangs.find(c => c.id === cabangId);
            if (selectedCabang) { targetLat = parseFloat(selectedCabang.latitude); targetLng = parseFloat(selectedCabang.longitude); targetRadius = parseFloat(selectedCabang.radius) || 100; lokasiName = selectedCabang.nama; }
            else { targetLat = idukaLat; targetLng = idukaLng; targetRadius = allowedRadius; lokasiName = "Pusat"; }
        }
        if (!hasValidIduka || targetLat === null || targetLng === null || targetLat === 0 || targetLng === 0) { e.preventDefault(); alert(`Koordinat ${lokasiName} belum diatur. Silakan hubungi administrator.`); return false; }

        const userLat = currentPosition.coords.latitude;
        const userLng = currentPosition.coords.longitude;
        const accuracy = currentPosition.coords.accuracy;
        const distance = calculateDistance(userLat, userLng, targetLat, targetLng);
        if (distance > (targetRadius + accuracy)) {
            e.preventDefault();
            alert(`Anda berada di luar radius yang diizinkan untuk absensi di ${lokasiName}.\nJarak Anda: ${Math.round(distance)} meter\nRadius maksimal: ${targetRadius} meter\nAkurasi GPS: ±${Math.round(accuracy)}m\nTotal toleransi: ${Math.round(targetRadius + accuracy)}m`);
            return false;
        }
    });

    document.getElementById('formPulang').addEventListener('submit', function (e) {
        if (isHoliday) { e.preventDefault(); alert('Hari ini adalah hari libur (' + holidayLabel + '). Absensi ditutup.'); return false; }
        if (!locationSwitch.checked) { e.preventDefault(); alert('Silakan aktifkan akses lokasi terlebih dahulu.'); return false; }
        if (hasPendingIzin || hasApprovedIzin) { e.preventDefault(); alert(hasApprovedIzin ? 'Anda sedang izin hari ini.' : 'Anda sudah mengajukan izin. Menunggu konfirmasi.'); return false; }
        if (hasPendingDinas && !hasApprovedDinas) { e.preventDefault(); alert('Dinas luar Anda masih menunggu konfirmasi.'); return false; }

        if (sedangDinas) {
            const now = new Date();
            const jam12 = new Date();
            jam12.setHours(12, 0, 0, 0);
            if (now < jam12) { e.preventDefault(); alert('Absen pulang untuk dinas luar hanya dapat dilakukan setelah jam 12.00 siang.'); return false; }
            if (latitudePulang) latitudePulang.value = "0";
            if (longitudePulang) longitudePulang.value = "0";
            if (accuracyPulang) accuracyPulang.value = "0";
        } else {
            if (!sudahAbsenMasuk) { e.preventDefault(); alert('Anda belum melakukan absen masuk hari ini.'); return false; }
            if (sudahAbsenPulang || sedangIzin) { e.preventDefault(); alert(sedangIzin ? 'Anda sedang izin hari ini.' : 'Anda sudah melakukan absensi pulang hari ini.'); return false; }
            const jamValidation = validateJamPulang();
            if (!jamValidation.valid) { e.preventDefault(); alert(jamValidation.message); return false; }
            if (!currentPosition) { e.preventDefault(); alert('Lokasi tidak terdeteksi. Silakan aktifkan akses lokasi.'); return false; }

            const selectedLokasiId = lokasiSelect.value;
            let targetLat, targetLng, targetRadius, lokasiName;
            if (selectedLokasiId === 'pusat') { targetLat = idukaLat; targetLng = idukaLng; targetRadius = allowedRadius; lokasiName = "Pusat"; }
            else {
                const cabangId = parseInt(selectedLokasiId.replace('cabang_', ''));
                const selectedCabang = cabangs.find(c => c.id === cabangId);
                if (selectedCabang) { targetLat = parseFloat(selectedCabang.latitude); targetLng = parseFloat(selectedCabang.longitude); targetRadius = parseFloat(selectedCabang.radius) || 100; lokasiName = selectedCabang.nama; }
                else { targetLat = idukaLat; targetLng = idukaLng; targetRadius = allowedRadius; lokasiName = "Pusat"; }
            }
            if (!hasValidIduka || targetLat === null || targetLng === null || targetLat === 0 || targetLng === 0) { e.preventDefault(); alert(`Koordinat ${lokasiName} belum diatur. Silakan hubungi administrator.`); return false; }

            const userLat = currentPosition.coords.latitude;
            const userLng = currentPosition.coords.longitude;
            const accuracy = currentPosition.coords.accuracy;
            const distance = calculateDistance(userLat, userLng, targetLat, targetLng);
            if (distance > (targetRadius + accuracy)) {
                e.preventDefault();
                alert(`Anda berada di luar radius yang diizinkan untuk absensi di ${lokasiName}.\nJarak Anda: ${Math.round(distance)} meter\nRadius maksimal: ${targetRadius} meter\nAkurasi GPS: ±${Math.round(accuracy)}m\nTotal toleransi: ${Math.round(targetRadius + accuracy)}m`);
                return false;
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const formIzin = document.getElementById('formIzin');
        const formDinas = document.getElementById('formDinas');
        let absensiHariIniFlag = false;

        document.getElementById('btnIzin').addEventListener('click', function() { cekStatusIzin(); });
        document.getElementById('btnDinas').addEventListener('click', function() { cekStatusDinas(); });

        function cekStatusIzin() {
            fetch('{{ route('absensi.cek-izin') }}', { headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                .then(response => response.json())
                .then(data => {
                    absensiHariIniFlag = data.has_attendance || data.has_approved_izin || data.has_pending_masuk || data.has_pending_pulang;
                    if (data.has_attendance) disableIzinForm('Anda sudah memiliki record absensi hari ini. Tidak bisa mengajukan izin.');
                    else if (data.has_approved_izin) disableIzinForm('Anda sudah mengajukan izin hari ini.');
                    else if (data.has_pending_izin) disableIzinForm('Anda sudah mengajukan izin. Menunggu konfirmasi IDUKA.');
                    else if (data.has_pending_masuk) disableIzinForm('Anda memiliki absensi masuk yang menunggu konfirmasi. Tidak bisa mengajukan izin.');
                    else if (data.has_pending_pulang) disableIzinForm('Anda memiliki absensi pulang yang menunggu konfirmasi. Tidak bisa mengajukan izin.');
                    else enableIzinForm();
                })
                .catch(error => console.error('Error:', error));
        }

        function cekStatusDinas() {
            fetch('{{ route('absensi.cek-dinas') }}', { headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                .then(response => response.json())
                .then(data => {
                    absensiHariIniFlag = data.has_attendance || data.has_approved_dinas || data.has_pending_masuk || data.has_pending_pulang;
                    if (data.has_attendance) disableDinasForm('Anda sudah memiliki record absensi hari ini. Tidak bisa mengajukan dinas luar.');
                    else if (data.has_approved_dinas) disableDinasForm('Anda sudah mengajukan dinas luar hari ini.');
                    else if (data.has_pending_dinas) disableDinasForm('Anda sudah mengajukan dinas luar. Menunggu konfirmasi IDUKA.');
                    else if (data.has_pending_masuk) disableDinasForm('Anda memiliki absensi masuk yang menunggu konfirmasi. Tidak bisa mengajukan dinas luar.');
                    else if (data.has_pending_pulang) disableDinasForm('Anda memiliki absensi pulang yang menunggu konfirmasi. Tidak bisa mengajukan dinas luar.');
                    else enableDinasForm();
                })
                .catch(error => console.error('Error:', error));
        }

        function disableIzinForm(message) {
            formIzin.querySelectorAll('select, textarea, button[type="submit"]').forEach(el => { el.disabled = true; });
            let alertDiv = formIzin.querySelector('.izin-status-alert');
            if (!alertDiv) {
                alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-info mt-3 izin-status-alert';
                formIzin.querySelector('.card-body').appendChild(alertDiv);
            }
            alertDiv.innerHTML = `<i class="fas fa-info-circle me-2"></i> ${message}`;
        }

        function enableIzinForm() {
            formIzin.querySelectorAll('select, textarea, button[type="submit"]').forEach(el => { el.disabled = false; });
            const alertDiv = formIzin.querySelector('.izin-status-alert');
            if (alertDiv) alertDiv.remove();
        }

        function disableDinasForm(message) {
            formDinas.querySelectorAll('select, textarea, button[type="submit"]').forEach(el => { el.disabled = true; });
            let alertDiv = formDinas.querySelector('.dinas-status-alert');
            if (!alertDiv) {
                alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-info mt-3 dinas-status-alert';
                formDinas.querySelector('.card-body').appendChild(alertDiv);
            }
            alertDiv.innerHTML = `<i class="fas fa-info-circle me-2"></i> ${message}`;
        }

        function enableDinasForm() {
            formDinas.querySelectorAll('select, textarea, button[type="submit"]').forEach(el => { el.disabled = false; });
            const alertDiv = formDinas.querySelector('.dinas-status-alert');
            if (alertDiv) alertDiv.remove();
        }

        document.getElementById('formIzin').addEventListener('submit', function (e) {
            if (absensiHariIniFlag) { e.preventDefault(); alert('Anda sudah memiliki record absensi hari ini. Tidak bisa mengajukan izin.'); return false; }
            if (hasPendingDinas || hasApprovedDinas) { e.preventDefault(); alert(hasApprovedDinas ? 'Anda sedang dinas luar hari ini.' : 'Anda sudah mengajukan dinas luar. Menunggu konfirmasi.'); return false; }
            if (hasPendingMasuk) { e.preventDefault(); alert('Anda memiliki absensi masuk yang menunggu konfirmasi. Tidak bisa mengajukan izin.'); return false; }
            if (hasPendingPulang) { e.preventDefault(); alert('Anda memiliki absensi pulang yang menunggu konfirmasi. Tidak bisa mengajukan izin.'); return false; }
            const keterangan = document.getElementById('keteranganIzin').value.trim();
            if (keterangan.length < 10) { e.preventDefault(); alert('Keterangan izin minimal 10 karakter.'); return false; }
            if (!confirm('Apakah Anda yakin ingin mengajukan izin? Setelah izin diajukan, Anda tidak bisa melakukan absensi hari ini.')) { e.preventDefault(); return false; }
        });

        document.getElementById('formDinas').addEventListener('submit', function (e) {
            if (absensiHariIniFlag) { e.preventDefault(); alert('Anda sudah memiliki record absensi hari ini. Tidak bisa mengajukan dinas luar.'); return false; }
            if (hasPendingIzin || hasApprovedIzin) { e.preventDefault(); alert(hasApprovedIzin ? 'Anda sedang izin hari ini.' : 'Anda sudah mengajukan izin. Menunggu konfirmasi.'); return false; }
            if (hasPendingMasuk) { e.preventDefault(); alert('Anda memiliki absensi masuk yang menunggu konfirmasi. Tidak bisa mengajukan dinas luar.'); return false; }
            if (hasPendingPulang) { e.preventDefault(); alert('Anda memiliki absensi pulang yang menunggu konfirmasi. Tidak bisa mengajukan dinas luar.'); return false; }
            const keterangan = document.getElementById('keteranganDinas').value.trim();
            if (keterangan.length < 10) { e.preventDefault(); alert('Keterangan dinas minimal 10 karakter.'); return false; }
            if (!confirm('Apakah Anda yakin ingin mengajukan dinas luar? Setelah dinas diajukan dan disetujui, Anda tetap wajib melakukan absensi pulang seperti biasa.')) { e.preventDefault(); return false; }
        });

        function updateButtonStatusBasedOnTime() {
            if (!sudahAbsenMasuk && !sedangIzin && !sedangDinas && !hasPendingIzin && !hasPendingDinas && !hasApprovedIzin && !hasApprovedDinas && !hasPendingMasuk) {
                const jamValidation = validateJamMasuk();
                if (locationSwitch.checked) {
                    btnMasuk.disabled = !jamValidation.valid;
                    btnMasuk.title = jamValidation.valid ? "" : jamValidation.message;
                } else {
                    btnMasuk.disabled = true;
                    btnMasuk.title = "Silakan aktifkan akses lokasi";
                }
            }
            if (sudahAbsenMasuk && !sudahAbsenPulang && !sedangIzin && !sedangDinas) {
                const jamValidation = validateJamPulang();
                btnPulang.disabled = !jamValidation.valid || !locationSwitch.checked;
                btnPulang.title = jamValidation.valid ? "" : jamValidation.message;
            }
        }

        updateButtonStatusBasedOnTime();
        setInterval(updateButtonStatusBasedOnTime, 60000);
    });

    document.addEventListener('DOMContentLoaded', function () {
        if (!hasValidIduka) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger mt-3';
            alertDiv.innerHTML = '<strong>Peringatan:</strong> Koordinat IDUKA belum diatur. Silakan hubungi administrator untuk mengatur koordinat IDUKA.';
            document.querySelector('.card-body').prepend(alertDiv);
        }
        if ((sudahAbsenMasuk && sudahAbsenPulang) || sedangIzin) {
            locationSwitch.disabled = true;
        }
        updateButtonStates();
    });

    function loadRiwayatAbsensi() {
        riwayatAbsensiTable.innerHTML = `<tr><td colspan="6" class="text-center"><div class="spinner-border spinner-border-sm me-2" role="status"><span class="visually-hidden">Loading...</span></div>Memuat data riwayat absensi...</td></tr>`;
        fetch(`{{ route('absensi.riwayat.data') }}?page=${currentPage}`, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
            .then(response => response.json())
            .then(data => { riwayatData = data.data; renderRiwayatTable(data); renderPagination(data); })
            .catch(error => {
                console.error('Error:', error);
                riwayatAbsensiTable.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Gagal memuat data riwayat absensi. Silakan coba lagi.</td></tr>`;
            });
    }

    function renderRiwayatTable(data) {
        if (data.data.length === 0) {
            riwayatAbsensiTable.innerHTML = `<tr><td colspan="6" class="text-center">Tidak ada data riwayat absensi</td></tr>`;
            showingCount.textContent = '0'; totalCount.textContent = '0';
            return;
        }
        let html = '';
        data.data.forEach(absensi => {
            const statusBadge = getStatusBadge(absensi);
            const jamMasuk = absensi.jam_masuk ? formatTime(absensi.jam_masuk) : '-';
            const jamPulang = absensi.jam_pulang ? formatTime(absensi.jam_pulang) : '-';
            const lokasi = absensi.lokasiIduka ? absensi.lokasiIduka.nama : '-';
            html += `<tr data-id="${absensi.id}"><td>${formatDate(absensi.tanggal)}</td><td>${jamMasuk}</td><td>${jamPulang}</td><td>${statusBadge}</td><td>${lokasi}</td><td><button class="btn btn-sm btn-info view-detail" data-id="${absensi.id}"><i class="fas fa-eye"></i> Detail</button></td></tr>`;
        });
        riwayatAbsensiTable.innerHTML = html;
        const start = (data.current_page - 1) * data.per_page + 1;
        const end = Math.min(data.current_page * data.per_page, data.total);
        showingCount.textContent = `${start}-${end}`;
        totalCount.textContent = data.total;
        document.querySelectorAll('.view-detail').forEach(button => {
            button.addEventListener('click', function(e) { e.stopPropagation(); showDetailAbsensi(this.getAttribute('data-id')); });
        });
        document.querySelectorAll('#riwayatAbsensiTable tr[data-id]').forEach(row => {
            row.addEventListener('click', function() { showDetailAbsensi(this.getAttribute('data-id')); });
        });
    }

    function renderPagination(data) {
        let html = '';
        if (data.current_page > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${data.current_page - 1}" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>`;
        } else {
            html += `<li class="page-item disabled"><a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>`;
        }
        for (let i = 1; i <= data.last_page; i++) {
            if (i === data.current_page) {
                html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
            } else {
                html += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
        }
        if (data.current_page < data.last_page) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${data.current_page + 1}" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>`;
        } else {
            html += `<li class="page-item disabled"><a class="page-link" href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>`;
        }
        paginationRiwayat.innerHTML = html;
        document.querySelectorAll('#paginationRiwayat .page-link[data-page]').forEach(link => {
            link.addEventListener('click', function(e) { e.preventDefault(); currentPage = parseInt(this.getAttribute('data-page')); loadRiwayatAbsensi(); });
        });
    }

    function initMap(absensi) {
        const masukLocation = { lat: parseFloat(absensi.latitude), lng: parseFloat(absensi.longitude) };
        let pulangLocation = null;
        if (absensi.latitude_pulang && absensi.longitude_pulang) {
            pulangLocation = { lat: parseFloat(absensi.latitude_pulang), lng: parseFloat(absensi.longitude_pulang) };
        }
        const map = new google.maps.Map(document.getElementById(`map-${absensi.id}`), { zoom: 15, center: masukLocation });
        new google.maps.Marker({ position: masukLocation, map: map, title: "Lokasi Absen Masuk", icon: { url: "http://maps.google.com/mapfiles/ms/icons/green-dot.png" } });
        if (pulangLocation) {
            new google.maps.Marker({ position: pulangLocation, map: map, title: "Lokasi Absen Pulang", icon: { url: "http://maps.google.com/mapfiles/ms/icons/red-dot.png" } });
            new google.maps.Polyline({ path: [masukLocation, pulangLocation], geodesic: true, strokeColor: '#FF0000', strokeOpacity: 1.0, strokeWeight: 2 }).setMap(map);
        }
    }

    function formatDate(dateString) { const date = new Date(dateString); return date.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' }); }
    function formatDay(dateString) { const date = new Date(dateString); return date.toLocaleDateString('id-ID', { weekday: 'long' }); }
    function formatTime(timeString) { const date = new Date(timeString); return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }); }
    function formatDateTime(dateTimeString) { const date = new Date(dateTimeString); return date.toLocaleString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' }); }
    function calculateDuration(jamMasuk, jamPulang) {
        if (!jamMasuk || !jamPulang) return '-';
        const start = new Date(jamMasuk); const end = new Date(jamPulang);
        const diffMs = end - start;
        const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
        const diffMinutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));
        return `${diffHours} jam ${diffMinutes} menit`;
    }
    function getStatusBadge(absensi) {
        if (absensi.status === 'izin') return '<span class="badge bg-info">Izin</span>';
        else if (absensi.status_dinas === 'disetujui') return '<span class="badge bg-primary">Dinas Luar</span>';
        else if (absensi.status === 'tepat_waktu') return '<span class="badge bg-success">Tepat Waktu</span>';
        else if (absensi.status === 'terlambat') return '<span class="badge bg-warning">Terlambat</span>';
        else return `<span class="badge bg-secondary">${absensi.status}</span>`;
    }
    function formatJenisIzin(jenis) {
        const map = { 'sakit': 'Sakit', 'keperluan_keluarga': 'Keperluan Keluarga', 'keperluan_sekolah': 'Keperluan Sekolah', 'lainnya': 'Lainnya' };
        return map[jenis] || jenis;
    }
    function formatJenisDinas(jenis) {
        const map = { 'perusahaan': 'Perusahaan', 'sekolah': 'Sekolah', 'instansi_pemerintah': 'Instansi Pemerintah', 'lainnya': 'Lainnya' };
        return map[jenis] || jenis;
    }

    function updateButtonStates() {
        if (pkEnded) {
            btnMasuk.disabled = true; btnPulang.disabled = true; btnIzin.disabled = true; btnDinas.disabled = true;
            btnMasuk.classList.remove('btn-success'); btnMasuk.classList.add('btn-secondary');
            btnPulang.classList.remove('btn-warning'); btnPulang.classList.add('btn-secondary');
            btnIzin.classList.remove('btn-info'); btnIzin.classList.add('btn-secondary');
            btnDinas.classList.remove('btn-primary'); btnDinas.classList.add('btn-secondary');
            locationStatus.textContent = 'Periode PKL telah selesai. Tidak dapat melakukan absensi.';
            distanceStatus.textContent = ''; distanceValue.textContent = '-'; userLocation.textContent = '-';
            return;
        }
        if (isHoliday) {
            btnMasuk.disabled = true; btnMasuk.title = 'Hari ini adalah hari libur (' + holidayLabel + '). Absensi ditutup.';
            btnMasuk.classList.remove('btn-success'); btnMasuk.classList.add('btn-secondary');
            btnPulang.disabled = true; btnPulang.title = 'Hari ini adalah hari libur (' + holidayLabel + '). Absensi ditutup.';
            btnPulang.classList.remove('btn-warning'); btnPulang.classList.add('btn-secondary');
            btnIzin.disabled = true; btnIzin.title = 'Hari ini adalah hari libur (' + holidayLabel + '). Tidak bisa mengajukan izin.';
            btnIzin.classList.remove('btn-info'); btnIzin.classList.add('btn-secondary');
            btnDinas.disabled = true; btnDinas.title = 'Hari ini adalah hari libur (' + holidayLabel + '). Tidak bisa mengajukan dinas luar.';
            btnDinas.classList.remove('btn-primary'); btnDinas.classList.add('btn-secondary');
            locationStatus.textContent = "Hari ini adalah hari libur (" + holidayLabel + "). Absensi ditutup.";
            distanceStatus.textContent = "Hari libur"; distanceStatus.className = "text-danger";
            distanceValue.textContent = "-"; userLocation.textContent = "-";
            locationSwitch.disabled = true; locationSwitch.checked = false;
            return;
        }
        if (sudahAbsenMasuk || sedangIzin || sedangDinas || hasPendingIzin || hasPendingDinas || hasApprovedIzin || hasApprovedDinas || hasPendingMasuk) {
            btnMasuk.disabled = true; btnMasuk.classList.remove('btn-success'); btnMasuk.classList.add('btn-secondary');
        } else if (!locationSwitch.checked) {
            btnMasuk.disabled = true; btnMasuk.classList.remove('btn-success'); btnMasuk.classList.add('btn-secondary');
        } else {
            const jamValidation = validateJamMasuk();
            if (!jamValidation.valid) { btnMasuk.disabled = true; btnMasuk.title = jamValidation.message; }
            else { btnMasuk.disabled = false; btnMasuk.title = ""; }
            btnMasuk.classList.add('btn-success'); btnMasuk.classList.remove('btn-secondary');
        }
        if (sedangDinas && !sudahAbsenPulang) {
            const now = new Date(); const jam12 = new Date(); jam12.setHours(12, 0, 0, 0);
            if (now < jam12) { btnPulang.disabled = true; btnPulang.title = 'Absen pulang untuk dinas luar hanya dapat dilakukan setelah jam 12.00 siang.'; }
            else { btnPulang.disabled = !locationSwitch.checked; btnPulang.title = ''; }
            if (locationSwitch.checked && now >= jam12) { btnPulang.classList.add('btn-warning'); btnPulang.classList.remove('btn-secondary'); }
            else { btnPulang.classList.remove('btn-warning'); btnPulang.classList.add('btn-secondary'); }
        } else if (!sudahAbsenMasuk || sudahAbsenPulang || sedangIzin || hasPendingIzin || hasApprovedIzin || hasPendingMasuk || hasPendingPulang) {
            btnPulang.disabled = true;
            if (sudahAbsenPulang) { btnPulang.classList.remove('btn-warning'); btnPulang.classList.add('btn-secondary'); }
        } else if (hasPendingDinas && !hasApprovedDinas) {
            btnPulang.disabled = true; btnPulang.classList.remove('btn-warning'); btnPulang.classList.add('btn-secondary');
        } else if (sudahAbsenMasuk && !sudahAbsenPulang && !sedangIzin) {
            const jamValidation = validateJamPulang();
            btnPulang.disabled = !jamValidation.valid || !locationSwitch.checked;
            btnPulang.title = jamValidation.valid ? "" : jamValidation.message;
            if (jamValidation.valid && locationSwitch.checked) { btnPulang.classList.add('btn-warning'); btnPulang.classList.remove('btn-secondary'); }
            else { btnPulang.classList.remove('btn-warning'); btnPulang.classList.add('btn-secondary'); }
        }
        if (absensiHariIni || hasPendingIzin || hasApprovedIzin || hasPendingDinas || hasApprovedDinas || hasPendingMasuk || hasPendingPulang) {
            btnIzin.disabled = true; btnIzin.classList.remove('btn-info'); btnIzin.classList.add('btn-secondary');
        } else {
            btnIzin.disabled = false; btnIzin.classList.add('btn-info'); btnIzin.classList.remove('btn-secondary');
        }
        if (absensiHariIni || hasPendingDinas || hasApprovedDinas || hasPendingIzin || hasApprovedIzin || hasPendingMasuk || hasPendingPulang) {
            btnDinas.disabled = true; btnDinas.classList.remove('btn-primary'); btnDinas.classList.add('btn-secondary');
        } else {
            btnDinas.disabled = false; btnDinas.classList.add('btn-primary'); btnDinas.classList.remove('btn-secondary');
        }
    }
    updateButtonStates();
</script>
@endsection
