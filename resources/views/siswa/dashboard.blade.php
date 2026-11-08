@extends('layout.main')

@section('content')
<style>
    /* ============================================================ */
    /* TABLE SCROLL HINT */
    /* ============================================================ */
    .table-scroll-hint {
        display: none;
        font-size: .72rem;
        color: #64748b;
        background: #f1f5f9;
        border-radius: 8px;
        padding: 6px 10px;
        margin: 10px 12px 8px;
    }

    /* ============================================================ */
    /* TABLE CARD — paksa bisa scroll di HP                          */
    /* ============================================================ */
    @media (max-width: 991.98px) {

        /* 1) HAPUS overflow:hidden dari card (biang kerok tabel gak bisa digeser) */
        .card { overflow: visible !important; }

        /* 2) Balikin rounded corner */
        .card > .card-header:first-child {
            border-top-left-radius: .5rem !important;
            border-top-right-radius: .5rem !important;
        }
        .card > .card-body:last-child,
        .card > .card-body + .table-responsive:last-child {
            border-bottom-left-radius: .5rem !important;
            border-bottom-right-radius: .5rem !important;
        }

        /* 3) Hint scroll muncul */
        .table-scroll-hint { display: block; }

        /* 4) Paksa table-responsive jadi scroll container */
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

        /* 5) Tabel harus lebih lebar dari container */
        .table-responsive > .table {
            min-width: 640px !important;
            margin-bottom: 0 !important;
        }

        /* 6) Scrollbar tipis petunjuk bisa digeser */
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

        /* 7) Sembunyikan image welcome di HP */
        .welcome-image-col { display: none !important; }

        /* 8) Card welcome info jadi full width */
        .welcome-info-col { width: 100% !important; flex: 0 0 100% !important; }
    }

    /* ============================================================ */
    /* MOBILE (≤ 767px) */
    /* ============================================================ */
    @media (max-width: 767.98px) {
        /* Alert card */
        .alert-card-body {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 12px;
            text-align: left;
        }
        .alert-card-icon { margin-bottom: 0; }

        /* Action buttons */
        .action-buttons-wrap {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 8px !important;
            width: 100%;
        }
        .action-buttons-wrap .btn,
        .action-buttons-wrap .btn-group {
            width: 100%;
        }
        .action-buttons-wrap .btn-group .btn {
            width: 100%;
            border-radius: .5rem !important;
        }
        .action-buttons-wrap .dropdown-menu {
            width: 100%;
        }

        /* Header card info */
        .action-header-wrap {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 12px !important;
        }

        /* Padding card */
        .card-body { padding: 1rem !important; }
        .card-header { padding: .75rem 1rem !important; }
        .card-header h6 { font-size: .85rem !important; }

        /* Table font size */
        .table { font-size: .82rem; }
        .table > :not(caption) > * > * { padding: .55rem .5rem; }
        .badge { font-size: .68rem; }

        /* Welcome card */
        .welcome-card-body { padding: 1rem !important; }
        .welcome-card-body h5 { font-size: 1rem !important; }
        .welcome-card-body p { font-size: .82rem !important; }

        /* Info pembimbing pill */
        .info-pembimbing {
            font-size: .78rem !important;
            padding: 6px 10px !important;
        }

        /* Tombol Lengkapi Data */
        .btn-lengkapi {
            width: 100% !important;
            justify-content: center;
        }
    }

    /* ============================================================ */
    /* EXTRA SMALL (≤ 575px) */
    /* ============================================================ */
    @media (max-width: 575.98px) {
        .card-body { padding: .85rem !important; }
        .card-header { padding: .65rem .85rem !important; }

        .table { font-size: .78rem; }
        .table > :not(caption) > * > * { padding: .5rem .4rem; }
        .badge { font-size: .62rem; padding: .35em .55em; }

        /* Icon dalam table aksi lebih kecil */
        .table .btn-icon { width: 32px; height: 32px; padding: 0 !important; }

        /* Alert icon kecilkan */
        .alert-card-icon-inner { width: 40px !important; height: 40px !important; }
        .alert-card-icon-inner i { font-size: 16px !important; }
        .alert-card-body h6 { font-size: .85rem !important; }
        .alert-card-body p { font-size: .78rem !important; }
    }
</style>

<div class="container-fluid py-3 px-2 px-md-4">
<div class="container-xxl flex-grow-1 container-p-y">

    {{-- Alert Status Pindah PKL --}}
    @if(isset($statusPindahPkl))
        @php
            $alertContent = '';

            if($statusPindahPkl->status == 'menunggu') {
                $alertContent = [
                    'bg' => 'linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%)',
                    'border' => '#f59e0b',
                    'icon_bg' => '#f59e0b',
                    'icon' => 'fas fa-hourglass-half',
                    'title' => 'Menunggu Verifikasi',
                    'text' => 'Pengajuan pindah PKL Anda sedang dalam proses verifikasi. Silakan hubungi Kaprog jurusan Anda untuk mempercepat proses.'
                ];
            } elseif($statusPindahPkl->status == 'menunggu_surat') {
                $alertContent = [
                    'bg' => 'linear-gradient(135deg, #ecfeff 0%, #cffafe 100%)',
                    'border' => '#06b6d4',
                    'icon_bg' => '#06b6d4',
                    'icon' => 'fas fa-print',
                    'title' => 'Menunggu Surat Dicetak',
                    'text' => 'Pengajuan pindah PKL Anda telah diterima, selanjutnya menunggu surat dicetak.'
                ];
            } elseif($statusPindahPkl->status == 'siap_kirim') {
                $alertContent = [
                    'bg' => 'linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%)',
                    'border' => '#3b82f6',
                    'icon_bg' => '#3b82f6',
                    'icon' => 'fas fa-paper-plane',
                    'title' => 'Surat Telah Dicetak',
                    'text' => 'Surat pengajuan pindah tempat PKL kamu telah dicetak dan dikirim ke IDUKA. Silahkan tunggu verifikasi dari IDUKA.'
                ];
            } elseif($statusPindahPkl->status == 'diterima_iduka') {
                $alertContent = [
                    'bg' => 'linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%)',
                    'border' => '#22c55e',
                    'icon_bg' => '#22c55e',
                    'icon' => 'fas fa-check-circle',
                    'title' => 'Pindah PKL Diterima',
                    'text' => 'Pengajuan pindah PKL Anda telah diterima. Silahkan buat pengajuan baru untuk mengajukan tempat PKL kamu.'
                ];
            } elseif($statusPindahPkl->status == 'ditolak_iduka') {
                $alertContent = [
                    'bg' => 'linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%)',
                    'border' => '#ef4444',
                    'icon_bg' => '#ef4444',
                    'icon' => 'fas fa-times-circle',
                    'title' => 'Pindah PKL Ditolak',
                    'text' => 'Pengajuan pindah PKL Anda ditolak. Silahkan hubungi Kaprog jurusan kamu atau IDUKA kamu untuk alasan lebih lanjut.'
                ];
            }
        @endphp

        @if($alertContent)
            <div class="card mb-4 shadow-sm border-0" style="background: {{ $alertContent['bg'] }}; border-left: 4px solid {{ $alertContent['border'] }} !important;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center alert-card-body">
                        <div class="flex-shrink-0 alert-card-icon">
                            <div class="alert-card-icon-inner" style="width: 48px; height: 48px; background: {{ $alertContent['icon_bg'] }}; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                                <i class="{{ $alertContent['icon'] }} text-white" style="font-size:20px;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 fw-bold" style="color: #0f172a;">{{ $alertContent['title'] }}</h6>
                            <p class="mb-0" style="font-size: 0.9rem; color: #475569;">{{ $alertContent['text'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

    {{-- Welcome Card --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="border-left:4px solid #3b82f6;">
                <div class="d-flex align-items-center row g-0">
                    <div class="col-12 col-sm-7 welcome-info-col">
                        <div class="card-body welcome-card-body">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div style="width:44px;height:44px;background:#eff6ff;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-user-graduate" style="font-size:20px;color:#3b82f6;"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-0">Halo, {{ Auth::user()->name }}!</h5>
                                    <small style="color:#64748b;">Siswa PKL</small>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2 mb-3 mt-3 px-3 py-2 info-pembimbing" style="background:#f8fafc; border-radius:8px; display:inline-flex; flex-wrap: wrap;">
                                <i class="fas fa-chalkboard-teacher text-primary"></i>
                                <span style="font-size:14px; color:#475569;">Pembimbing:</span>
                                <span style="font-size:14px; font-weight:600; color:#0f172a;">
                                    {{ $user->pembimbing->nama ?? 'Belum ada pembimbing' }}
                                </span>
                            </div>

                            <p style="color:#64748b;font-size:14px;margin-bottom:16px;">
                                Data kamu belum terisi sepenuhnya nih. Ayo lengkapi terlebih dahulu untuk kelancaran proses PKL!
                            </p>

                            <div class="d-flex gap-2 flex-wrap">
                                @if (auth()->user()->role == 'siswa')
                                    <a href="{{ route('siswa.data_pribadi.create') }}" class="btn btn-primary btn-sm px-3 btn-lengkapi">
                                        <i class="fas fa-user-edit me-1"></i> Lengkapi Data
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center welcome-image-col">
                        <div class="card-body pb-0 px-md-4">
                            <img src="{{ asset('snet/assets/img/illustrations/man-with-laptop-light.png') }}"
                                height="130" alt="Siswa" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (auth()->user()->role == 'siswa')
        {{-- Pengajuan Actions --}}
        <div class="card mb-4" style="overflow: visible;">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 action-header-wrap">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:40px;height:40px;background:#f0fdf4;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-file-signature" style="font-size:18px;color:#22c55e;"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Aksi Pengajuan PKL</h6>
                            <small class="text-muted">Kelola pengajuan dan mutasi tempat PKL Anda</small>
                        </div>
                    </div>

                    <div class="d-flex gap-2 action-buttons-wrap">
                        {{-- Dropdown Buat Pengajuan --}}
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-plus-circle me-1"></i> Buat Pengajuan
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li>
                                    @php
                                        $bisaAjukan = (!$sudahAjukan || ($statusPindahPkl && $statusPindahPkl->status == 'diterima_iduka'));
                                    @endphp

                                    @if ($bisaAjukan)
                                        <a href="{{ route('iduka.usulan') }}" class="dropdown-item py-2">
                                            <i class="fas fa-paper-plane me-2 text-primary"></i> Ajukan Tempat Baru
                                        </a>
                                    @else
                                        @if ($statusAjukan === 'proses')
                                            <a href="#" class="dropdown-item py-2" onclick="showInfo('Pengajuan Sedang Diproses', 'Kamu sudah mengajukan tempat PKL dan sedang diproses.')">
                                                <i class="fas fa-info-circle me-2 text-warning"></i> Sedang Diproses
                                            </a>
                                        @elseif($statusAjukan === 'diterima')
                                            <a href="#" class="dropdown-item py-2" onclick="showInfo('Tidak dapat mengajukan!', 'Kamu sudah mengajukan dan pengajuan telah diterima.')">
                                                <i class="fas fa-check-circle me-2 text-success"></i> Pengajuan Diterima
                                            </a>
                                        @else
                                            <a href="#" class="dropdown-item py-2" onclick="showInfo('Pengajuan Tidak Diterima', 'Pengajuan sedang diproses atau telah diterima.')">
                                                <i class="fas fa-ban me-2 text-danger"></i> Tidak Bisa Mengajukan
                                            </a>
                                        @endif
                                    @endif
                                </li>
                            </ul>
                        </div>

                        {{-- Pindah Tempat PKL --}}
                        @if ($sudahDiterima)
                            @php
                                $pengajuanAktif = isset($statusPindahPkl) && in_array($statusPindahPkl->status, ['menunggu', 'menunggu_surat', 'diterima_iduka', 'siap_kirim', 'menunggu_konfirmasi_iduka']);
                            @endphp

                            @if(!$pengajuanAktif)
                                <form action="{{ route('pindah-pkl.ajukan') }}" method="POST" class="m-0 w-100 w-md-auto">
                                    @csrf
                                    <button type="submit" class="btn btn-warning w-100" onclick="return confirmAjukanPindah()">
                                        <i class="fas fa-exchange-alt me-1"></i> Pindah Tempat
                                    </button>
                                </form>
                            @else
                                <button type="button" class="btn btn-outline-secondary" onclick="showInfo('Pengajuan Aktif', 'Kamu sudah memiliki pengajuan pindah tempat PKL yang aktif.')">
                                    <i class="fas fa-exchange-alt me-1"></i> Pindah Tempat
                                </button>
                            @endif
                        @else
                            <button type="button" class="btn btn-outline-secondary" onclick="showInfo('Belum Bisa Pindah', 'Kamu hanya bisa mengajukan pindah jika sudah diterima di tempat PKL.')">
                                <i class="fas fa-exchange-alt me-1"></i> Pindah Tempat
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- RIWAYAT PINDAH PKL --}}
        @if(isset($riwayatPindahPkl) && $riwayatPindahPkl->count() > 0)
            <div class="card mb-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0 fw-bold"><i class="fas fa-history text-primary me-2"></i>Riwayat Permintaan Pindah Tempat PKL</h6>
                </div>
                <div class="table-scroll-hint">
                    <i class="bi bi-arrow-left-right"></i> Geser tabel ke kiri/kanan untuk melihat semua kolom
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center px-3">#</th>
                                <th>Tempat PKL Lama</th>
                                <th>Tempat PKL Baru</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($riwayatPindahPkl as $index => $pindah)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="fw-semibold text-dark">{{ $pindah->idukaLama->nama ?? 'Tidak Diketahui' }}</td>
                                    <td class="fw-semibold text-primary">{{ $pindah->iduka_baru_nama }}</td>
                                    <td>{{ \Carbon\Carbon::parse($pindah->created_at)->format('d/m/Y') }}</td>
                                    <td>
                                        @if($pindah->status == 'menunggu')
                                            <span class="badge bg-label-warning text-dark"><i class="fas fa-clock me-1"></i> Menunggu Verifikasi</span>
                                        @elseif($pindah->status == 'diterima_iduka')
                                            <span class="badge bg-label-success"><i class="fas fa-check-circle me-1"></i> Diterima</span>
                                        @elseif($pindah->status == 'ditolak_iduka')
                                            <span class="badge bg-label-danger"><i class="fas fa-times-circle me-1"></i> Ditolak</span>
                                        @elseif($pindah->status == 'menunggu_surat')
                                            <span class="badge bg-label-info"><i class="fas fa-print me-1"></i> Menunggu Surat</span>
                                        @elseif($pindah->status == 'siap_kirim')
                                            <span class="badge bg-label-primary"><i class="fas fa-paper-plane me-1"></i> Menunggu Iduka</span>
                                        @elseif($pindah->status == 'menunggu_konfirmasi_iduka')
                                            <span class="badge bg-label-info"><i class="fas fa-user-check me-1"></i> Menunggu Konfirmasi</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $pindah->status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-icon btn-outline-primary" style="border-radius:8px;" onclick='showDetailPindah(@json($pindah))'>
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- RIWAYAT USULAN --}}
        <div class="card mb-4">
            <div class="card-header border-bottom">
                <h6 class="m-0 fw-bold"><i class="fas fa-list-alt text-primary me-2"></i>Riwayat Pengajuan Tempat PKL</h6>
            </div>
            <div class="table-scroll-hint">
                <i class="bi bi-arrow-left-right"></i> Geser tabel ke kiri/kanan untuk melihat semua kolom
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center px-3">#</th>
                            <th>Nama Institusi</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usulanSiswa as $index => $usulan)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="fw-semibold text-dark">{{ $usulan->nama }}</td>
                                <td>{{ \Carbon\Carbon::parse($usulan->created_at)->format('d/m/Y') }}</td>
                                <td>
                                    @if ($usulan->status == 'proses')
                                        <span class="badge bg-label-warning text-dark"><i class="fas fa-clock me-1"></i> Menunggu Verifikasi</span>
                                    @elseif($usulan->status == 'diterima')
                                        <span class="badge bg-label-success"><i class="fas fa-check-circle me-1"></i> Diterima</span>
                                    @else
                                        <span class="badge bg-label-danger"><i class="fas fa-times-circle me-1"></i> Ditolak</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-1 flex-wrap justify-content-center">
                                        <a href="{{ route('detail.usulan', $usulan->id) }}" class="btn btn-sm btn-icon btn-outline-info" style="border-radius:8px;" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if ($usulan->status == 'diterima')
                                            <a href="{{ route('usulan.pdf', $usulan->id) }}" class="btn btn-sm btn-icon btn-outline-danger" style="border-radius:8px;" title="Unduh PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        @endif
                                        @if ($usulan->status == 'diterima' || $usulan->status == 'proses')
                                            <form action="{{ route('siswa.pengajuan.ajukanPembatalan', $usulan->id) }}" method="POST" class="m-0 d-inline" onsubmit="return confirm('Yakin ingin mengajukan pembatalan?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-icon btn-outline-warning" style="border-radius:8px;" title="Batal Pengajuan">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            @if(count($usulanPkl) == 0)
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada riwayat pengajuan tempat PKL.</td>
                                </tr>
                            @endif
                        @endforelse

                        @forelse($usulanPkl as $index => $usul)
                            <tr>
                                <td class="text-center">{{ count($usulanSiswa) + $index + 1 }}</td>
                                <td class="fw-semibold text-dark">{{ $usul->iduka->nama }}</td>
                                <td>{{ \Carbon\Carbon::parse($usul->created_at)->format('d/m/Y') }}</td>
                                <td>
                                    @if ($usul->status == 'proses')
                                        <span class="badge bg-label-warning text-dark"><i class="fas fa-clock me-1"></i> Menunggu Verifikasi</span>
                                    @elseif($usul->status == 'diterima')
                                        <span class="badge bg-label-success"><i class="fas fa-check-circle me-1"></i> Usulan Diterima</span>
                                    @elseif($usul->status == 'menunggu')
                                        <span class="badge bg-label-secondary text-dark"><i class="fas fa-hourglass-half me-1"></i> Menunggu Pembatalan</span>
                                    @elseif($usul->status == 'batal')
                                        <span class="badge bg-label-secondary"><i class="fas fa-ban me-1"></i> Pengajuan Dibatalkan</span>
                                    @else
                                        <span class="badge bg-label-danger"><i class="fas fa-times-circle me-1"></i> Ditolak</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-1 flex-wrap justify-content-center">
                                        <a href="{{ route('detail.usulan', $usul->id) }}" class="btn btn-sm btn-icon btn-outline-info" style="border-radius:8px;" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if ($usul->status == 'diterima')
                                            <a href="{{ route('usulan.pdf', $usul->id) }}" class="btn btn-sm btn-icon btn-outline-danger" style="border-radius:8px;" title="Unduh PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        @endif
                                        @if ($usul->status == 'diterima' || $usul->status == 'proses')
                                            <form action="{{ route('siswa.pengajuan.ajukanPembatalan', $usul->id) }}" method="POST" class="m-0 d-inline" onsubmit="return confirm('Yakin ingin mengajukan pembatalan?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-icon btn-outline-warning" style="border-radius:8px;" title="Batal Pengajuan">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- RIWAYAT PENGAJUAN (DARI SISTEM) --}}
        <div class="card mb-4">
            <div class="card-header border-bottom">
                <h6 class="m-0 fw-bold"><i class="fas fa-folder-open text-primary me-2"></i>Riwayat Status Penetapan PKL</h6>
            </div>
            <div class="table-scroll-hint">
                <i class="bi bi-arrow-left-right"></i> Geser tabel ke kiri/kanan untuk melihat semua kolom
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center px-3">#</th>
                            <th>Nama Institusi</th>
                            <th>Tanggal Penetapan</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuanSiswa as $index => $pengajuan)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="fw-semibold text-dark">{{ $pengajuan->iduka->nama }}</td>
                                <td>{{ \Carbon\Carbon::parse($pengajuan->created_at)->format('d/m/Y') }}</td>
                                <td>
                                    @if ($pengajuan->status == 'proses')
                                        <span class="badge bg-label-warning text-dark"><i class="fas fa-clock me-1"></i> Menunggu Verifikasi</span>
                                    @elseif($pengajuan->status == 'diterima')
                                        <span class="badge bg-label-success"><i class="fas fa-check-circle me-1"></i> Diterima</span>
                                    @else
                                        <span class="badge bg-label-danger"><i class="fas fa-times-circle me-1"></i> Ditolak</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('pengajuan.detail', $pengajuan->id) }}" class="btn btn-sm btn-icon btn-outline-primary" style="border-radius:8px;" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada riwayat penetapan PKL dari sekolah.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
</div>

{{-- Scripts & SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function showInfo(title, text) {
        Swal.fire({
            icon: 'info',
            title: title,
            text: text,
            showConfirmButton: true,
            confirmButtonColor: '#3b82f6',
            customClass: { popup: 'rounded-4' }
        });
    }

    function confirmAjukanPindah() {
        Swal.fire({
            title: 'Konfirmasi Mutasi',
            text: 'Yakin ingin mengajukan pindah tempat PKL?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fas fa-check me-1"></i> Ya, ajukan',
            cancelButtonText: 'Batal',
            customClass: { popup: 'rounded-4' }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Pengajuan Dikirim!',
                    text: 'Permintaan pindah dijadwalkan. Tunggu verifikasi Kaprog.',
                    showConfirmButton: false,
                    timer: 2000,
                    customClass: { popup: 'rounded-4' }
                }).then(() => {
                    document.querySelector('form[action="{{ route('pindah-pkl.ajukan') }}"]').submit();
                });
            }
        });
        return false;
    }

    function showDetailPindah(pindah) {
        let statusText = '', statusIcon = '', statusColor = '';
        switch(pindah.status) {
            case 'menunggu': statusText = 'Menunggu Verifikasi'; statusIcon = 'fa-clock'; statusColor = '#f59e0b'; break;
            case 'diterima_iduka': statusText = 'Diterima'; statusIcon = 'fa-check-circle'; statusColor = '#22c55e'; break;
            case 'ditolak_iduka': statusText = 'Ditolak'; statusIcon = 'fa-times-circle'; statusColor = '#ef4444'; break;
            case 'menunggu_surat': statusText = 'Menunggu Surat'; statusIcon = 'fa-print'; statusColor = '#06b6d4'; break;
            case 'siap_kirim': statusText = 'Menunggu Iduka'; statusIcon = 'fa-paper-plane'; statusColor = '#3b82f6'; break;
            case 'menunggu_konfirmasi_iduka': statusText = 'Menunggu Konfirmasi IDUKA'; statusIcon = 'fa-user-check'; statusColor = '#06b6d4'; break;
            default: statusText = pindah.status; statusIcon = 'fa-info-circle'; statusColor = '#64748b';
        }

        const idukaLamaNama = pindah.iduka_lama ? pindah.iduka_lama.nama : (pindah.idukaLama ? pindah.idukaLama.nama : 'Tidak Diketahui');

        Swal.fire({
            title: '<strong>Detail Pengajuan Mutasi</strong>',
            html: `
                <div class="text-start p-3 bg-light rounded-3 mt-3 shadow-sm border">
                    <div class="mb-2"><strong class="text-muted" style="font-size:12px;">Tempat PKL Lama:</strong><br><span class="fw-semibold text-dark">${idukaLamaNama}</span></div>
                    <div class="mb-2"><strong class="text-muted" style="font-size:12px;">Tempat PKL Baru:</strong><br><span class="fw-bold text-primary">${pindah.iduka_baru_nama || '-'}</span></div>
                    <div class="mb-2"><strong class="text-muted" style="font-size:12px;">Tanggal Pengajuan:</strong><br><span class="fw-semibold text-dark">${new Date(pindah.created_at).toLocaleDateString('id-ID')}</span></div>
                    <div class="mb-2"><strong class="text-muted" style="font-size:12px;">Status Mutasi:</strong><br><span style="color:${statusColor};" class="fw-bold"><i class="fas ${statusIcon} me-1"></i> ${statusText}</span></div>
                    ${pindah.alasan ? `<div class="mb-2"><strong class="text-muted" style="font-size:12px;">Alasan Mutasi:</strong><br><span class="text-dark">${pindah.alasan}</span></div>` : ''}
                    ${pindah.catatan ? `<div class="mb-2"><strong class="text-muted" style="font-size:12px;">Catatan Validator:</strong><br><span class="text-dark">${pindah.catatan}</span></div>` : ''}
                </div>
            `,
            icon: 'info',
            iconColor: '#3b82f6',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#3b82f6',
            customClass: { popup: 'rounded-4' }
        });
    }

    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000,
            customClass: { popup: 'rounded-4' }
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Hanya Sebentar!',
            text: "{{ session('error') }}",
            showConfirmButton: false,
            timer: 2500,
            customClass: { popup: 'rounded-4' }
        });
    @endif
</script>
@endsection
