@extends('layout.main')

@section('content')
@php
    $activeRecord = null;
    $instansi = null;

    if ($usulanIduka) {
        $activeRecord = $usulanIduka;
        $instansi = [
            'jenis'          => 'Usulan Institusi / Perusahaan Baru',
            'nama_label'     => 'Nama Institusi / Perusahaan',
            'alamat_label'   => 'Alamat Lengkap Institusi / Perusahaan',
            'nama'           => $usulanIduka->nama,
            'nama_pimpinan'  => $usulanIduka->nama_pimpinan,
            'nip_pimpinan'   => $usulanIduka->nip_pimpinan,
            'jabatan'        => $usulanIduka->jabatan,
            'alamat'         => $usulanIduka->alamat,
            'kode_pos'       => $usulanIduka->kode_pos,
            'telepon'        => $usulanIduka->telepon,
            'email'          => $usulanIduka->email,
            'bidang_industri'=> $usulanIduka->bidang_industri,
            'kerjasama'      => $usulanIduka->kerjasama,
        ];
    } elseif ($pengajuanUsulan && $pengajuanUsulan->iduka) {
        $activeRecord = $pengajuanUsulan;
        $idukaTarget = $pengajuanUsulan->iduka;
        $instansi = [
            'jenis'          => 'Pengajuan IDUKA Terdaftar',
            'nama_label'     => 'Nama IDUKA',
            'alamat_label'   => 'Alamat Lengkap IDUKA',
            'nama'           => $idukaTarget->nama,
            'nama_pimpinan'  => $idukaTarget->nama_pimpinan,
            'nip_pimpinan'   => $idukaTarget->nip_pimpinan ?? null,
            'jabatan'        => $idukaTarget->jabatan,
            'alamat'         => $idukaTarget->alamat,
            'kode_pos'       => $idukaTarget->kode_pos,
            'telepon'        => $idukaTarget->telepon,
            'email'          => $idukaTarget->email,
            'bidang_industri'=> $idukaTarget->bidang_industri,
            'kerjasama'      => $idukaTarget->kerjasama,
        ];
    }

    $rawStatus = $activeRecord->status ?? null;
    $statusMap = [
        'diterima' => [
            'label' => 'Diterima',
            'badge' => 'du-badge-success',
            'box'   => 'du-status-box-success',
            'desc'  => 'Usulan tempat PKL Anda telah disetujui.',
        ],
        'proses' => [
            'label' => 'Menunggu Verifikasi',
            'badge' => 'du-badge-warning',
            'box'   => 'du-status-box-warning',
            'desc'  => 'Pengajuan sedang dalam tahap peninjauan dan verifikasi.',
        ],
        'ditolak' => [
            'label' => 'Ditolak',
            'badge' => 'du-badge-danger',
            'box'   => 'du-status-box-danger',
            'desc'  => 'Pengajuan tidak disetujui. Silakan ajukan pilihan tempat PKL lainnya.',
        ],
        'menunggu' => [
            'label' => 'Menunggu Pembatalan',
            'badge' => 'du-badge-neutral',
            'box'   => 'du-status-box-neutral',
            'desc'  => 'Permintaan pembatalan pengajuan sedang menunggu konfirmasi.',
        ],
        'batal' => [
            'label' => 'Dibatalkan',
            'badge' => 'du-badge-neutral',
            'box'   => 'du-status-box-neutral',
            'desc'  => 'Pengajuan telah dibatalkan.',
        ],
    ];

    $statusMeta = $rawStatus && isset($statusMap[$rawStatus])
        ? $statusMap[$rawStatus]
        : [
            'label' => $rawStatus ? ucfirst((string) $rawStatus) : 'Belum Ada Pengajuan',
            'badge' => 'du-badge-neutral',
            'box'   => 'du-status-box-neutral',
            'desc'  => 'Belum ada catatan status usulan pada akun ini.',
        ];

    $kelasDisplay = '-';
    if ($dataPribadi && $dataPribadi->kelas) {
        $kelasText = trim(($dataPribadi->kelas->kelas ?? '') . ' ' . ($dataPribadi->kelas->name_kelas ?? ''));
        $kelasDisplay = $kelasText !== '' ? $kelasText : '-';
    }

    $tglLahirDisplay = null;
    if ($dataPribadi && !empty($dataPribadi->tgl_lahir)) {
        try {
            $tglLahirDisplay = \Carbon\Carbon::parse($dataPribadi->tgl_lahir)->translatedFormat('d F Y');
        } catch (\Throwable $e) {
            $tglLahirDisplay = $dataPribadi->tgl_lahir;
        }
    }
    $tempatLhrText = ($dataPribadi && !empty($dataPribadi->tempat_lhr)) ? $dataPribadi->tempat_lhr : null;
    if ($tempatLhrText && $tglLahirDisplay) {
        $ttlDisplay = $tempatLhrText . ', ' . $tglLahirDisplay;
    } elseif ($tempatLhrText || $tglLahirDisplay) {
        $ttlDisplay = $tempatLhrText ?: $tglLahirDisplay;
    } else {
        $ttlDisplay = '-';
    }

    $createdAtDisplay = null;
    $updatedAtDisplay = null;
    if ($activeRecord && $activeRecord->created_at) {
        try {
            $createdAtDisplay = \Carbon\Carbon::parse($activeRecord->created_at)->translatedFormat('d M Y, H:i');
        } catch (\Throwable $e) {
            $createdAtDisplay = (string) $activeRecord->created_at;
        }
    }
    if ($activeRecord && $activeRecord->updated_at) {
        try {
            $updatedAtDisplay = \Carbon\Carbon::parse($activeRecord->updated_at)->translatedFormat('d M Y, H:i');
        } catch (\Throwable $e) {
            $updatedAtDisplay = (string) $activeRecord->updated_at;
        }
    }

    $suratIzinRaw = $activeRecord->surat_izin ?? null;
    $isSuratIzinReady = $suratIzinRaw === 'sudah';

    $canDownloadPdf = $dataPribadi
        && !empty($dataPribadi->konke_id)
        && $activeRecord
        && ($rawStatus === 'diterima')
        && !empty($activeRecord->iduka_id);

    $siswaInitial = 'S';
    if ($dataPribadi && !empty($dataPribadi->name)) {
        $siswaInitial = strtoupper(mb_substr(trim($dataPribadi->name), 0, 1));
    }
@endphp

<style>
#detailUsulanPage {
    --du-bg: #ffffff;
    --du-muted: #f8fafc;
    --du-border: #e2e8f0;
    --du-border-strong: #cbd5e1;
    --du-text: #0f172a;
    --du-text-muted: #64748b;
    --du-primary: #2563eb;
    --du-primary-hover: #1d4ed8;
    --du-primary-soft: #eff6ff;
    --du-primary-border: #bfdbfe;
    color: var(--du-text);
    padding-bottom: 24px;
}

#detailUsulanPage *,
#detailUsulanPage *::before,
#detailUsulanPage *::after {
    box-sizing: border-box;
}

#detailUsulanPage .du-shell {
    max-width: 1180px;
    margin: 0 auto;
}

/* Breadcrumb */
#detailUsulanPage .du-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 14px;
    font-size: 13px;
    color: var(--du-text-muted);
}
#detailUsulanPage .du-breadcrumb a {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: var(--du-text-muted);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.15s ease;
}
#detailUsulanPage .du-breadcrumb a:hover {
    color: var(--du-primary);
}
#detailUsulanPage .du-breadcrumb .du-bc-sep {
    color: #94a3b8;
    display: inline-flex;
    align-items: center;
}
#detailUsulanPage .du-breadcrumb .du-bc-current {
    color: var(--du-text);
    font-weight: 600;
}

/* Header Card */
#detailUsulanPage .du-header-card {
    background-color: var(--du-bg);
    border: 1px solid var(--du-border);
    border-radius: 16px;
    padding: 22px 24px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    margin-bottom: 20px;
}
#detailUsulanPage .du-header-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}
#detailUsulanPage .du-header-main {
    flex: 1 1 360px;
    min-width: 0;
}
#detailUsulanPage .du-title-line {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 6px;
}
#detailUsulanPage .du-title {
    margin: 0;
    font-size: 21px;
    font-weight: 700;
    letter-spacing: -0.02em;
    color: var(--du-text);
    line-height: 1.25;
}
#detailUsulanPage .du-subtitle {
    margin: 0;
    font-size: 13.5px;
    color: var(--du-text-muted);
    line-height: 1.5;
}
#detailUsulanPage .du-header-chips {
    margin-top: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
#detailUsulanPage .du-meta-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 10px;
    border-radius: 9999px;
    background-color: var(--du-muted);
    border: 1px solid var(--du-border);
    color: #334155;
    font-size: 12px;
    font-weight: 500;
    line-height: 1.2;
}
#detailUsulanPage .du-meta-chip svg {
    width: 13px;
    height: 13px;
    color: var(--du-text-muted);
    flex-shrink: 0;
}
#detailUsulanPage .du-header-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    flex-shrink: 0;
}

/* Badges & Buttons */
#detailUsulanPage .du-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 11px;
    border-radius: 9999px;
    font-size: 12px;
    font-weight: 600;
    line-height: 1.2;
    border: 1px solid transparent;
    white-space: nowrap;
}
#detailUsulanPage .du-badge svg {
    width: 13px;
    height: 13px;
    flex-shrink: 0;
}
#detailUsulanPage .du-badge-success {
    background-color: #f0fdf4;
    border-color: #bbf7d0;
    color: #15803d;
}
#detailUsulanPage .du-badge-warning {
    background-color: #fffbeb;
    border-color: #fde68a;
    color: #b45309;
}
#detailUsulanPage .du-badge-danger {
    background-color: #fef2f2;
    border-color: #fecaca;
    color: #b91c1c;
}
#detailUsulanPage .du-badge-neutral {
    background-color: #f1f5f9;
    border-color: #cbd5e1;
    color: #475569;
}
#detailUsulanPage .du-badge-blue {
    background-color: var(--du-primary-soft);
    border-color: var(--du-primary-border);
    color: #1d4ed8;
}

#detailUsulanPage .du-btn {
    height: 38px;
    padding: 0 15px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    border: 1px solid transparent;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
    white-space: nowrap;
}
#detailUsulanPage .du-btn svg {
    width: 15px;
    height: 15px;
    flex-shrink: 0;
}
#detailUsulanPage .du-btn-outline {
    background-color: #ffffff;
    border-color: var(--du-border-strong);
    color: #334155;
}
#detailUsulanPage .du-btn-outline:hover {
    background-color: #f8fafc;
    border-color: #94a3b8;
    color: var(--du-text);
}
#detailUsulanPage .du-btn-primary {
    background-color: var(--du-primary);
    border-color: var(--du-primary);
    color: #ffffff;
}
#detailUsulanPage .du-btn-primary:hover {
    background-color: var(--du-primary-hover);
    border-color: var(--du-primary-hover);
    color: #ffffff;
}
#detailUsulanPage .du-btn-full {
    width: 100%;
}

/* Main 2-Column Layout */
#detailUsulanPage .du-layout {
    display: grid;
    grid-template-columns: minmax(0, 1.6fr) minmax(300px, 1fr);
    gap: 20px;
    align-items: start;
}
#detailUsulanPage .du-col-stack {
    display: flex;
    flex-direction: column;
    gap: 20px;
    min-width: 0;
}
#detailUsulanPage .du-col-sticky {
    position: sticky;
    top: 84px;
}

/* Section Card */
#detailUsulanPage .du-card {
    background-color: var(--du-bg);
    border: 1px solid var(--du-border);
    border-radius: 16px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    overflow: hidden;
}
#detailUsulanPage .du-card-header {
    padding: 16px 22px;
    border-bottom: 1px solid var(--du-border);
    background-color: var(--du-bg);
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
}
#detailUsulanPage .du-card-head-left {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    min-width: 0;
}
#detailUsulanPage .du-card-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background-color: var(--du-primary-soft);
    border: 1px solid var(--du-primary-border);
    color: var(--du-primary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
#detailUsulanPage .du-card-icon svg {
    width: 17px;
    height: 17px;
}
#detailUsulanPage .du-card-title {
    margin: 0;
    font-size: 15px;
    font-weight: 700;
    color: var(--du-text);
    line-height: 1.3;
}
#detailUsulanPage .du-card-desc {
    margin: 2px 0 0;
    font-size: 12.5px;
    color: var(--du-text-muted);
    line-height: 1.4;
}
#detailUsulanPage .du-card-body {
    padding: 20px 22px;
}

/* Highlight Subpanels inside Cards */
#detailUsulanPage .du-highlight-box {
    padding: 14px 16px;
    border-radius: 12px;
    background-color: var(--du-muted);
    border: 1px solid var(--du-border);
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}
#detailUsulanPage .du-highlight-title {
    margin: 0 0 3px;
    font-size: 15.5px;
    font-weight: 700;
    color: var(--du-text);
    overflow-wrap: anywhere;
}
#detailUsulanPage .du-highlight-sub {
    margin: 0;
    font-size: 12.5px;
    color: var(--du-text-muted);
    overflow-wrap: anywhere;
}
#detailUsulanPage .du-siswa-strip {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}
#detailUsulanPage .du-siswa-avatar {
    width: 42px;
    height: 42px;
    border-radius: 9999px;
    background-color: var(--du-primary-soft);
    border: 1px solid var(--du-primary-border);
    color: #1d4ed8;
    font-size: 15px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* Key-Value Responsive Definition Grid */
#detailUsulanPage .du-kv-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px 20px;
}
#detailUsulanPage .du-kv-item {
    padding: 10px 12px;
    border-radius: 10px;
    background-color: #ffffff;
    border: 1px solid #f1f5f9;
    min-width: 0;
}
#detailUsulanPage .du-kv-item-full {
    grid-column: 1 / -1;
}
#detailUsulanPage .du-kv-label {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    font-weight: 600;
    color: var(--du-text-muted);
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}
#detailUsulanPage .du-kv-label svg {
    width: 13px;
    height: 13px;
    color: #94a3b8;
    flex-shrink: 0;
}
#detailUsulanPage .du-kv-value {
    font-size: 13.5px;
    font-weight: 600;
    color: var(--du-text);
    line-height: 1.45;
    overflow-wrap: anywhere;
    word-break: break-word;
}
#detailUsulanPage .du-kv-value.is-muted {
    color: #94a3b8;
    font-weight: 500;
}

/* Status Summary & Timeline Card */
#detailUsulanPage .du-status-box {
    padding: 13px 14px;
    border-radius: 12px;
    border: 1px solid transparent;
    margin-bottom: 16px;
}
#detailUsulanPage .du-status-box-success {
    background-color: #f0fdf4;
    border-color: #bbf7d0;
    color: #166534;
}
#detailUsulanPage .du-status-box-warning {
    background-color: #fffbeb;
    border-color: #fde68a;
    color: #92400e;
}
#detailUsulanPage .du-status-box-danger {
    background-color: #fef2f2;
    border-color: #fecaca;
    color: #991b1b;
}
#detailUsulanPage .du-status-box-neutral {
    background-color: #f8fafc;
    border-color: #e2e8f0;
    color: #334155;
}
#detailUsulanPage .du-status-box-title {
    margin: 0 0 3px;
    font-size: 13.5px;
    font-weight: 700;
}
#detailUsulanPage .du-status-box-desc {
    margin: 0;
    font-size: 12.5px;
    line-height: 1.45;
}

/* Clean Timeline */
#detailUsulanPage .du-timeline {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 14px;
}
#detailUsulanPage .du-tl-item {
    position: relative;
    padding-left: 26px;
}
#detailUsulanPage .du-tl-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 7px;
    top: 18px;
    bottom: -16px;
    width: 2px;
    background-color: var(--du-border);
}
#detailUsulanPage .du-tl-dot {
    position: absolute;
    left: 1px;
    top: 3px;
    width: 14px;
    height: 14px;
    border-radius: 9999px;
    background-color: #ffffff;
    border: 3px solid #cbd5e1;
}
#detailUsulanPage .du-tl-item.is-primary .du-tl-dot {
    border-color: var(--du-primary);
}
#detailUsulanPage .du-tl-item.is-success .du-tl-dot {
    border-color: #16a34a;
}
#detailUsulanPage .du-tl-item.is-warning .du-tl-dot {
    border-color: #d97706;
}
#detailUsulanPage .du-tl-item.is-danger .du-tl-dot {
    border-color: #dc2626;
}
#detailUsulanPage .du-tl-title {
    margin: 0;
    font-size: 13px;
    font-weight: 600;
    color: var(--du-text);
    line-height: 1.35;
}
#detailUsulanPage .du-tl-time {
    margin: 2px 0 0;
    font-size: 11.5px;
    color: var(--du-text-muted);
}

/* Document Card */
#detailUsulanPage .du-doc-item {
    padding: 14px;
    border-radius: 12px;
    background-color: var(--du-muted);
    border: 1px solid var(--du-border);
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 14px;
}
#detailUsulanPage .du-doc-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background-color: #ffffff;
    border: 1px solid var(--du-border);
    color: var(--du-primary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
#detailUsulanPage .du-doc-name {
    margin: 0 0 2px;
    font-size: 13px;
    font-weight: 700;
    color: var(--du-text);
    overflow-wrap: anywhere;
}
#detailUsulanPage .du-doc-meta {
    margin: 0;
    font-size: 12px;
    color: var(--du-text-muted);
    line-height: 1.4;
}
#detailUsulanPage .du-side-actions {
    display: flex;
    flex-direction: column;
    gap: 9px;
}

/* Empty State */
#detailUsulanPage .du-empty {
    padding: 32px 20px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}
#detailUsulanPage .du-empty-icon {
    width: 46px;
    height: 46px;
    border-radius: 9999px;
    background-color: #f1f5f9;
    border: 1px solid var(--du-border);
    color: #94a3b8;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 2px;
}
#detailUsulanPage .du-empty-title {
    margin: 0;
    font-size: 14px;
    font-weight: 700;
    color: #334155;
}
#detailUsulanPage .du-empty-desc {
    margin: 0;
    font-size: 12.5px;
    color: var(--du-text-muted);
    max-width: 340px;
    line-height: 1.45;
}

/* Responsive Breakpoints */
@media (max-width: 991.98px) {
    #detailUsulanPage .du-layout {
        grid-template-columns: 1fr;
    }
    #detailUsulanPage .du-col-sticky {
        position: static;
    }
}

@media (max-width: 767.98px) {
    #detailUsulanPage .du-header-card {
        padding: 18px 16px;
        border-radius: 14px;
    }
    #detailUsulanPage .du-title {
        font-size: 18.5px;
    }
    #detailUsulanPage .du-header-actions {
        width: 100%;
    }
    #detailUsulanPage .du-header-actions .du-btn {
        flex: 1 1 100%;
    }
    #detailUsulanPage .du-card-header {
        padding: 14px 16px;
    }
    #detailUsulanPage .du-card-body {
        padding: 16px;
    }
    #detailUsulanPage .du-kv-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }
}
</style>

<div id="detailUsulanPage" class="container-xxl flex-grow-1 container-p-y">
    <div class="du-shell">
        {{-- Breadcrumb --}}
        <nav class="du-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('siswa.dashboard') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" aria-hidden="true">
                    <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                    <path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                </svg>
                <span>Dashboard</span>
            </a>
            <span class="du-bc-sep" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </span>
            <span class="du-bc-current">Detail Surat Usulan</span>
        </nav>

        {{-- Header Card --}}
        <header class="du-header-card">
            <div class="du-header-row">
                <div class="du-header-main">
                    <div class="du-title-line">
                        <h1 class="du-title">Detail Surat Usulan</h1>
                        <span class="du-badge {{ $statusMeta['badge'] }}">
                            @if ($rawStatus === 'diterima')
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                            @elseif ($rawStatus === 'proses')
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            @elseif ($rawStatus === 'ditolak')
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                            @endif
                            <span>{{ $statusMeta['label'] }}</span>
                        </span>
                    </div>

                    <p class="du-subtitle">
                        Rincian pengajuan institusi/perusahaan PKL serta data siswa yang tercatat pada sistem.
                    </p>

                    @if ($activeRecord)
                        <div class="du-header-chips">
                            <span class="du-meta-chip">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                                <span>{{ $instansi['jenis'] }}</span>
                            </span>

                            @if ($createdAtDisplay)
                                <span class="du-meta-chip">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                                    <span>Diajukan: {{ $createdAtDisplay }}</span>
                                </span>
                            @endif

                            @if ($suratIzinRaw)
                                <span class="du-meta-chip">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                                    <span>Surat Izin: {{ $isSuratIzinReady ? 'Sudah Diproses' : 'Belum Diproses' }}</span>
                                </span>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="du-header-actions">
                    @if ($canDownloadPdf)
                        <a href="{{ route('usulan.pdf') }}" class="du-btn du-btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                            <span>Unduh Surat (PDF)</span>
                        </a>
                    @endif

                    <a href="{{ route('siswa.dashboard') }}" class="du-btn du-btn-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>
        </header>

        {{-- Responsive 2-Column Content --}}
        <div class="du-layout">
            {{-- Left Column: Informasi Tempat PKL & Informasi Siswa --}}
            <div class="du-col-stack">
                {{-- Card: Informasi Institusi / Perusahaan --}}
                <section class="du-card">
                    <div class="du-card-header">
                        <div class="du-card-head-left">
                            <div class="du-card-icon" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                            </div>
                            <div>
                                <h2 class="du-card-title">Detail Data Institusi / Perusahaan</h2>
                                <p class="du-card-desc">
                                    {{ $instansi ? $instansi['jenis'] : 'Informasi tempat pelaksanaan PKL yang diajukan.' }}
                                </p>
                            </div>
                        </div>

                        @if ($instansi)
                            <span class="du-badge du-badge-blue d-none d-sm-inline-flex">
                                {{ $instansi['bidang_industri'] ?: 'Institusi' }}
                            </span>
                        @endif
                    </div>

                    <div class="du-card-body">
                        @if ($instansi)
                            <div class="du-highlight-box">
                                <div>
                                    <h3 class="du-highlight-title">{{ $instansi['nama'] ?: '-' }}</h3>
                                    <p class="du-highlight-sub">
                                        {{ $instansi['bidang_industri'] ? 'Bidang Industri: ' . $instansi['bidang_industri'] : 'Data bidang industri belum diisi' }}
                                    </p>
                                </div>
                                @if (!empty($instansi['kerjasama']))
                                    <span class="du-badge du-badge-neutral">
                                        Kerja Sama: {{ $instansi['kerjasama'] }}
                                    </span>
                                @endif
                            </div>

                            <div class="du-kv-grid">
                                <div class="du-kv-item">
                                    <div class="du-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/></svg>
                                        <span>{{ $instansi['nama_label'] }}</span>
                                    </div>
                                    <div class="du-kv-value {{ empty($instansi['nama']) ? 'is-muted' : '' }}">
                                        {{ $instansi['nama'] ?? '-' }}
                                    </div>
                                </div>

                                <div class="du-kv-item">
                                    <div class="du-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        <span>Nama Pimpinan</span>
                                    </div>
                                    <div class="du-kv-value {{ empty($instansi['nama_pimpinan']) ? 'is-muted' : '' }}">
                                        {{ $instansi['nama_pimpinan'] ?? '-' }}
                                    </div>
                                </div>

                                @if ($usulanIduka || !empty($instansi['nip_pimpinan']))
                                    <div class="du-kv-item">
                                        <div class="du-kv-label">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="14" x="3" y="5" rx="2"/><line x1="7" x2="11" y1="10" y2="10"/><line x1="7" x2="17" y1="14" y2="14"/></svg>
                                            <span>NIP / ID Pimpinan</span>
                                        </div>
                                        <div class="du-kv-value {{ empty($instansi['nip_pimpinan']) ? 'is-muted' : '' }}">
                                            {{ $instansi['nip_pimpinan'] ?? '-' }}
                                        </div>
                                    </div>
                                @endif

                                <div class="du-kv-item">
                                    <div class="du-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="14" x="2" y="7" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                                        <span>Jabatan</span>
                                    </div>
                                    <div class="du-kv-value {{ empty($instansi['jabatan']) ? 'is-muted' : '' }}">
                                        {{ $instansi['jabatan'] ?? '-' }}
                                    </div>
                                </div>

                                <div class="du-kv-item">
                                    <div class="du-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                        <span>No. Telepon Kantor</span>
                                    </div>
                                    <div class="du-kv-value {{ empty($instansi['telepon']) ? 'is-muted' : '' }}">
                                        {{ $instansi['telepon'] ?? '-' }}
                                    </div>
                                </div>

                                <div class="du-kv-item">
                                    <div class="du-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                        <span>Email Kantor</span>
                                    </div>
                                    <div class="du-kv-value {{ empty($instansi['email']) ? 'is-muted' : '' }}">
                                        {{ $instansi['email'] ?? '-' }}
                                    </div>
                                </div>

                                <div class="du-kv-item">
                                    <div class="du-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/></svg>
                                        <span>Bidang Industri</span>
                                    </div>
                                    <div class="du-kv-value {{ empty($instansi['bidang_industri']) ? 'is-muted' : '' }}">
                                        {{ $instansi['bidang_industri'] ?? '-' }}
                                    </div>
                                </div>

                                <div class="du-kv-item">
                                    <div class="du-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m11 17 2 2a1 1 0 1 0 3-3"/><path d="m14 14 2.5 2.5a1 1 0 1 0 3-3l-3.88-3.88a3 3 0 0 0-4.24 0l-.88.88a1 1 0 1 1-3-3l2.81-2.81a5.79 5.79 0 0 1 7.06-.87l.47.28a2 2 0 0 0 1.42.25L21 4"/><path d="m21 3 1 11h-2"/><path d="M3 3 2 14l6.5 6.5a1 1 0 1 0 3-3"/><path d="M3 4h8"/></svg>
                                        <span>Kerja Sama</span>
                                    </div>
                                    <div class="du-kv-value {{ empty($instansi['kerjasama']) ? 'is-muted' : '' }}">
                                        {{ $instansi['kerjasama'] ?? '-' }}
                                    </div>
                                </div>

                                <div class="du-kv-item">
                                    <div class="du-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="4" x2="20" y1="9" y2="9"/><line x1="4" x2="20" y1="15" y2="15"/><line x1="10" x2="8" y1="3" y2="21"/><line x1="16" x2="14" y1="3" y2="21"/></svg>
                                        <span>Kode Pos</span>
                                    </div>
                                    <div class="du-kv-value {{ empty($instansi['kode_pos']) ? 'is-muted' : '' }}">
                                        {{ $instansi['kode_pos'] ?? '-' }}
                                    </div>
                                </div>

                                <div class="du-kv-item du-kv-item-full">
                                    <div class="du-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                                        <span>{{ $instansi['alamat_label'] }}</span>
                                    </div>
                                    <div class="du-kv-value {{ empty($instansi['alamat']) ? 'is-muted' : '' }}">
                                        {{ $instansi['alamat'] ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="du-empty">
                                <div class="du-empty-icon" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/></svg>
                                </div>
                                <p class="du-empty-title">Belum ada data IDUKA yang diusulkan atau diajukan</p>
                                <p class="du-empty-desc">Data perusahaan atau institusi tujuan PKL akan tampil di sini setelah Anda mengirim usulan atau pengajuan PKL.</p>
                            </div>
                        @endif
                    </div>
                </section>

                {{-- Card: Detail Data Siswa --}}
                <section class="du-card">
                    <div class="du-card-header">
                        <div class="du-card-head-left">
                            <div class="du-card-icon" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <div>
                                <h2 class="du-card-title">Detail Data Siswa</h2>
                                <p class="du-card-desc">Identitas pemohon PKL yang terdaftar pada data pribadi siswa.</p>
                            </div>
                        </div>
                    </div>

                    <div class="du-card-body">
                        @if ($dataPribadi)
                            <div class="du-highlight-box">
                                <div class="du-siswa-strip">
                                    <div class="du-siswa-avatar" aria-hidden="true">{{ $siswaInitial }}</div>
                                    <div style="min-width: 0;">
                                        <h3 class="du-highlight-title">{{ $dataPribadi->name ?? '-' }}</h3>
                                        <p class="du-highlight-sub">
                                            NIS: {{ $dataPribadi->nip ?? '-' }}
                                            @if ($kelasDisplay !== '-')
                                                &middot; Kelas {{ $kelasDisplay }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                @if ($dataPribadi->konkes && !empty($dataPribadi->konkes->name_konke))
                                    <span class="du-badge du-badge-blue">
                                        {{ $dataPribadi->konkes->name_konke }}
                                    </span>
                                @endif
                            </div>

                            <div class="du-kv-grid">
                                <div class="du-kv-item">
                                    <div class="du-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        <span>Nama Siswa</span>
                                    </div>
                                    <div class="du-kv-value {{ empty($dataPribadi->name) ? 'is-muted' : '' }}">
                                        {{ $dataPribadi->name ?? '-' }}
                                    </div>
                                </div>

                                <div class="du-kv-item">
                                    <div class="du-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="4" x2="20" y1="9" y2="9"/><line x1="4" x2="20" y1="15" y2="15"/><line x1="10" x2="8" y1="3" y2="21"/><line x1="16" x2="14" y1="3" y2="21"/></svg>
                                        <span>NIS</span>
                                    </div>
                                    <div class="du-kv-value {{ empty($dataPribadi->nip) ? 'is-muted' : '' }}">
                                        {{ $dataPribadi->nip ?? '-' }}
                                    </div>
                                </div>

                                <div class="du-kv-item">
                                    <div class="du-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                                        <span>Konsentrasi Keahlian</span>
                                    </div>
                                    <div class="du-kv-value {{ empty($dataPribadi->konkes->name_konke) ? 'is-muted' : '' }}">
                                        {{ $dataPribadi->konkes->name_konke ?? '-' }}
                                    </div>
                                </div>

                                <div class="du-kv-item">
                                    <div class="du-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/></svg>
                                        <span>Kelas</span>
                                    </div>
                                    <div class="du-kv-value {{ $kelasDisplay === '-' ? 'is-muted' : '' }}">
                                        {{ $kelasDisplay }}
                                    </div>
                                </div>

                                <div class="du-kv-item">
                                    <div class="du-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                                        <span>Tempat, Tanggal Lahir</span>
                                    </div>
                                    <div class="du-kv-value {{ $ttlDisplay === '-' ? 'is-muted' : '' }}">
                                        {{ $ttlDisplay }}
                                    </div>
                                </div>

                                <div class="du-kv-item">
                                    <div class="du-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                                        <span>Jenis Kelamin</span>
                                    </div>
                                    <div class="du-kv-value {{ empty($dataPribadi->jk) ? 'is-muted' : '' }}">
                                        {{ $dataPribadi->jk ?? '-' }}
                                    </div>
                                </div>

                                <div class="du-kv-item">
                                    <div class="du-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/></svg>
                                        <span>Agama</span>
                                    </div>
                                    <div class="du-kv-value {{ empty($dataPribadi->agama) ? 'is-muted' : '' }}">
                                        {{ $dataPribadi->agama ?? '-' }}
                                    </div>
                                </div>

                                <div class="du-kv-item">
                                    <div class="du-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                        <span>No. HP</span>
                                    </div>
                                    <div class="du-kv-value {{ empty($dataPribadi->no_hp) ? 'is-muted' : '' }}">
                                        {{ $dataPribadi->no_hp ?? '-' }}
                                    </div>
                                </div>

                                <div class="du-kv-item">
                                    <div class="du-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                        <span>Email</span>
                                    </div>
                                    <div class="du-kv-value {{ empty($dataPribadi->email) ? 'is-muted' : '' }}">
                                        {{ $dataPribadi->email ?? '-' }}
                                    </div>
                                </div>

                                <div class="du-kv-item du-kv-item-full">
                                    <div class="du-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                                        <span>Alamat Siswa</span>
                                    </div>
                                    <div class="du-kv-value {{ empty($dataPribadi->alamat_siswa) ? 'is-muted' : '' }}">
                                        {{ $dataPribadi->alamat_siswa ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="du-empty">
                                <div class="du-empty-icon" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </div>
                                <p class="du-empty-title">Data pribadi siswa belum tersedia</p>
                                <p class="du-empty-desc">Lengkapi biodata pribadi Anda terlebih dahulu pada menu Data Pribadi agar informasi tampil lengkap.</p>
                            </div>
                        @endif
                    </div>
                </section>
            </div>

            {{-- Right Column: Status, Timeline Tercatat, Dokumen Surat & Aksi --}}
            <aside class="du-col-stack du-col-sticky">
                {{-- Card: Status & Progres Tercatat --}}
                <section class="du-card">
                    <div class="du-card-header">
                        <div class="du-card-head-left">
                            <div class="du-card-icon" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <div>
                                <h2 class="du-card-title">Status &amp; Progres</h2>
                                <p class="du-card-desc">Ringkasan status pengajuan berdasarkan data sistem.</p>
                            </div>
                        </div>
                    </div>

                    <div class="du-card-body">
                        <div class="du-status-box {{ $statusMeta['box'] }}">
                            <p class="du-status-box-title">{{ $statusMeta['label'] }}</p>
                            <p class="du-status-box-desc">{{ $statusMeta['desc'] }}</p>
                        </div>

                        @if ($activeRecord)
                            <ul class="du-timeline">
                                <li class="du-tl-item is-primary">
                                    <span class="du-tl-dot" aria-hidden="true"></span>
                                    <p class="du-tl-title">Pengajuan Usulan Dibuat</p>
                                    <p class="du-tl-time">
                                        {{ $createdAtDisplay ? $createdAtDisplay . ' WIB' : 'Waktu pembuatan tidak tersedia' }}
                                    </p>
                                </li>

                                @if ($suratIzinRaw)
                                    <li class="du-tl-item {{ $isSuratIzinReady ? 'is-success' : 'is-warning' }}">
                                        <span class="du-tl-dot" aria-hidden="true"></span>
                                        <p class="du-tl-title">
                                            Surat Izin: {{ $isSuratIzinReady ? 'Sudah Diproses' : 'Belum Diproses' }}
                                        </p>
                                        <p class="du-tl-time">
                                            {{ $isSuratIzinReady ? 'Administrasi surat izin telah ditandai selesai.' : 'Menunggu proses administrasi surat izin.' }}
                                        </p>
                                    </li>
                                @endif

                                @php
                                    $tlClass = match ($rawStatus) {
                                        'diterima' => 'is-success',
                                        'proses'   => 'is-warning',
                                        'ditolak'  => 'is-danger',
                                        default    => '',
                                    };
                                @endphp
                                <li class="du-tl-item {{ $tlClass }}">
                                    <span class="du-tl-dot" aria-hidden="true"></span>
                                    <p class="du-tl-title">Status Saat Ini: {{ $statusMeta['label'] }}</p>
                                    <p class="du-tl-time">
                                        {{ $updatedAtDisplay ? 'Pembaruan terakhir: ' . $updatedAtDisplay . ' WIB' : 'Mengacu pada status data terbaru' }}
                                    </p>
                                </li>
                            </ul>
                        @else
                            <p class="du-empty-desc" style="text-align:left; max-width:none;">
                                Belum ada aktivitas pengajuan yang tercatat.
                            </p>
                        @endif
                    </div>
                </section>

                {{-- Card: Dokumen Surat & Navigasi --}}
                <section class="du-card">
                    <div class="du-card-header">
                        <div class="du-card-head-left">
                            <div class="du-card-icon" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                            </div>
                            <div>
                                <h2 class="du-card-title">Dokumen &amp; Aksi</h2>
                                <p class="du-card-desc">Akses surat usulan PDF dan navigasi halaman.</p>
                            </div>
                        </div>
                    </div>

                    <div class="du-card-body">
                        @if ($canDownloadPdf)
                            <div class="du-doc-item">
                                <div class="du-doc-icon" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                                </div>
                                <div style="min-width: 0;">
                                    <p class="du-doc-name">surat_usulan_iduka_baru.pdf</p>
                                    <p class="du-doc-meta">Dokumen PDF surat usulan siap diunduh.</p>
                                </div>
                            </div>
                        @else
                            <div class="du-doc-item">
                                <div class="du-doc-icon" style="color:#64748b;" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="9" x2="15" y1="15" y2="15"/></svg>
                                </div>
                                <div style="min-width: 0;">
                                    <p class="du-doc-name">Surat PDF Belum Tersedia</p>
                                    <p class="du-doc-meta">
                                        @if (!$activeRecord)
                                            Belum terdapat data usulan untuk dibuatkan surat PDF.
                                        @elseif ($rawStatus !== 'diterima')
                                            Dokumen PDF tersedia setelah status pengajuan diterima dan IDUKA terhubung.
                                        @else
                                            Dokumen PDF belum terhubung dengan data IDUKA terdaftar.
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endif

                        <div class="du-side-actions">
                            @if ($canDownloadPdf)
                                <a href="{{ route('usulan.pdf') }}" class="du-btn du-btn-primary du-btn-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                                    <span>Unduh Surat Usulan (PDF)</span>
                                </a>
                            @endif

                            <a href="{{ route('siswa.dashboard') }}" class="du-btn du-btn-outline du-btn-full">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                                <span>Kembali ke Dashboard</span>
                            </a>
                        </div>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</div>
@endsection
