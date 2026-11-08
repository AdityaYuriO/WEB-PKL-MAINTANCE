@extends('layout.main')

@section('content')

    {{-- ⚡ CRITICAL FIX: Inline style dengan override agresif --}}
    <style>
        /* Modern CSS Variables */
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --danger-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --warning-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --glass-bg: rgba(255, 255, 255, 0.25);
            --glass-border: rgba(255, 255, 255, 0.18);
            --shadow-primary: 0 8px 32px rgba(102, 126, 234, 0.15);
        }

        .container-fluid { max-width: 1400px; }

        .text-gradient-primary {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid var(--glass-border);
        }

        .card-glass {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
        }

        @media (min-width: 992px) {
            .card {
                backdrop-filter: blur(10px);
            }
            .card:hover {
                transform: translateY(-5px);
                box-shadow: var(--shadow-primary) !important;
            }
        }

        .card-header {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        }

        .badge-custom-count {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #ffffff;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        /* Stats Cards */
        .stats-card {
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
        }

        .stats-card-success::before { background: var(--success-gradient); }
        .stats-card-danger::before  { background: var(--danger-gradient); }
        .stats-card-warning::before { background: var(--warning-gradient); }
        .stats-card-primary::before { background: var(--primary-gradient); }

        .stats-icon {
            width: 50px; height: 50px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; flex-shrink: 0;
        }

        .stats-card-success .stats-icon {
            background: linear-gradient(135deg, rgba(79, 172, 254, 0.1), rgba(0, 242, 254, 0.1));
            color: #4facfe;
        }
        .stats-card-danger .stats-icon {
            background: linear-gradient(135deg, rgba(240, 147, 251, 0.1), rgba(245, 87, 108, 0.1));
            color: #f5576c;
        }
        .stats-card-warning .stats-icon {
            background: linear-gradient(135deg, rgba(67, 233, 123, 0.1), rgba(56, 249, 215, 0.1));
            color: #43e97b;
        }
        .stats-card-primary .stats-icon {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            color: #667eea;
        }

        .stats-arrow { font-size: 1.5rem; opacity: 0.7; }

        /* Table Styles */
        .table { --bs-table-bg: transparent; margin-bottom: 0; }

        .table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 1rem 1.5rem;
            position: relative;
            white-space: nowrap;
        }

        .table thead th::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 1px;
            background: rgba(255, 255, 255, 0.3);
        }

        .table tbody tr {
            transition: background 0.2s ease;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        @media (min-width: 992px) {
            .table tbody tr:hover {
                background: linear-gradient(135deg, rgba(102, 126, 234, 0.03), rgba(118, 75, 162, 0.03));
                transform: translateX(8px);
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            }
        }

        .table tbody tr:last-child { border-bottom: none; }

        .table tbody td {
            vertical-align: middle;
            border: none;
            padding: 1rem 1.5rem;
        }

        .journal-row {
            animation: slideInRight 0.5s ease forwards;
        }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .badge {
            font-weight: 500;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
            letter-spacing: 0.3px;
            border: 1px solid transparent;
        }

        .btn {
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
            padding: 0.5rem 1.25rem;
        }

        .btn-outline-primary {
            border-color: #667eea;
            color: #667eea;
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--primary-gradient);
            border-color: transparent;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .modal-content {
            border: none;
            background: rgba(255, 255, 255, 0.98);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .modal-header.bg-gradient-primary {
            background: var(--primary-gradient) !important;
        }

        .empty-state-icon { font-size: 4rem; opacity: 0.5; }

        /* Pagination */
        .pagination-wrap .pagination {
            margin-bottom: 0;
            flex-wrap: wrap;
            gap: 4px;
        }
        .pagination-wrap .page-link {
            border-radius: 8px !important;
            border: 1px solid #e2e8f0;
            color: #667eea;
            font-weight: 500;
            font-size: 13px;
            padding: 6px 12px;
            transition: all .15s;
            min-width: 36px;
            text-align: center;
            background: #fff;
        }
        .pagination-wrap .page-link:hover {
            background: linear-gradient(135deg, rgba(102,126,234,0.1), rgba(118,75,162,0.1));
            border-color: #c4b5fd;
            color: #667eea;
        }
        .pagination-wrap .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-color: #667eea;
            color: #fff;
            box-shadow: 0 2px 8px rgba(102, 126, 234, .35);
        }
        .pagination-wrap .page-item.disabled .page-link {
            color: #cbd5e1;
            background: #f8fafc;
            border-color: #e2e8f0;
        }

        /* Hint scroll */
        .table-scroll-hint {
            display: none;
            font-size: .72rem;
            color: #64748b;
            background: #f1f5f9;
            border-radius: 8px;
            padding: 6px 10px;
            margin: 12px 12px 8px;
        }

        /* ============================================================ */
        /* ⚡ NUCLEAR OPTION: Override semua parent overflow di HP       */
        /* ============================================================ */
        @media (max-width: 991.98px) {

            /* 1) Matikan backdrop-filter yang bikin scroll gagal */
            .card, .card-glass, .stats-card, .table-card {
                backdrop-filter: none !important;
                -webkit-backdrop-filter: none !important;
            }

            /* 2) Paksa overflow visible di SEMUA parent yang mungkin nge-clip */
            .layout-wrapper,
            .layout-container,
            .content-wrapper,
            .container-fluid,
            .container-xxl,
            .container-p-y,
            .row,
            .col-12,
            .table-card,
            .table-card > .card-header,
            .table-card > .card-body,
            .table-card > .card-body.p-0,
            .table-card > .card-footer,
            .table-card > .card-body > .table-responsive {
                overflow: visible !important;
                min-width: 0 !important;
            }

            /* 3) Hint scroll muncul */
            .table-scroll-hint { display: block; }

            /* 4) FORCE table-responsive jadi scroll container yg beneran */
            .table-card .table-responsive,
            .table-card > .card-body > .table-responsive {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                overflow-x: scroll !important;   /* SELALU tampil scrollbar */
                overflow-y: hidden !important;
                -webkit-overflow-scrolling: touch !important;
                overscroll-behavior-x: contain !important;
                touch-action: pan-x pan-y !important;
                position: relative !important;
            }

            /* 5) FORCE table lebih lebar dari container */
            #tabel-riwayat-jurnal {
                min-width: 900px !important;
                width: 900px !important;
                max-width: none !important;
                margin-bottom: 0 !important;
            }

            /* 6) Scrollbar jelas terlihat */
            .table-responsive::-webkit-scrollbar {
                height: 8px !important;
                display: block !important;
            }
            .table-responsive::-webkit-scrollbar-track {
                background: #e2e8f0 !important;
                border-radius: 4px !important;
            }
            .table-responsive::-webkit-scrollbar-thumb {
                background: #94a3b8 !important;
                border-radius: 4px !important;
            }
            .table-responsive::-webkit-scrollbar-thumb:hover {
                background: #64748b !important;
            }

            /* 7) Matikan transform hover di HP */
            .card:hover {
                transform: none !important;
            }
            .table tbody tr:hover {
                transform: none !important;
                box-shadow: none !important;
            }

            /* 8) Pagination center */
            .pagination-wrap {
                width: 100%;
                display: flex;
                justify-content: center;
            }
            .pagination-wrap .pagination { justify-content: center; }
        }

        @media (max-width: 767.98px) {
            .page-title { font-size: 18px; }

            .stats-icon {
                width: 40px;
                height: 40px;
                font-size: 1.15rem;
            }
            .stats-card h3 { font-size: 1.15rem; }
            .stats-card span.small { font-size: .65rem; }

            .modal-dialog { margin: .5rem; }
            .modal-header { padding: .85rem 1rem; }
            .modal-body { padding: .9rem 1rem; }
            .modal-footer { padding: .75rem 1rem; gap: 6px; }
            .modal-footer .btn { flex: 1; }

            .pagination-wrap .page-link {
                padding: 5px 10px;
                font-size: 12px;
                min-width: 32px;
            }
        }

        @media (max-width: 575.98px) {
            .page-title { font-size: 16px; }

            .stats-card .card-body { padding: .65rem .5rem !important; }
            .stats-icon {
                width: 34px;
                height: 34px;
                font-size: 1rem;
                margin-right: .4rem !important;
            }
            .stats-card h3 { font-size: 1rem; }
            .stats-card span.small { font-size: .6rem; }

            .pagination-wrap .pagination .page-item:not(.active):not(:first-child):not(:last-child) {
                display: none;
            }
            .pagination-wrap .pagination .page-item:first-child,
            .pagination-wrap .pagination .page-item:last-child,
            .pagination-wrap .pagination .page-item.active {
                display: inline-block;
            }

            .empty-state-icon { font-size: 3rem; }
        }

        @media print {
            .btn, .pagination, .modal-footer { display: none !important; }
            .card { box-shadow: none !important; border: 1px solid #ddd !important; }
        }
    </style>

    <div class="container-fluid py-3 px-2 px-md-4">
        <div class="container-xxl flex-grow-1 container-p-y">

            <!-- Header Section -->
            <div class="row mb-3 mb-md-4">
                <div class="col-12">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <div>
                            <h1 class="h3 fw-bold text-gradient-primary mb-1 page-title">Riwayat Konfirmasi Jurnal</h1>
                            <p class="text-muted mb-0 small">
                                @if (auth()->user()->role === 'guru')
                                    Daftar jurnal yang telah dikonfirmasi sebagai Pembimbing
                                @elseif(auth()->user()->role === 'iduka')
                                    Daftar jurnal yang telah dikonfirmasi sebagai IDUKA
                                @else
                                    Daftar jurnal yang telah dikonfirmasi
                                @endif
                            </p>
                        </div>
                        <div class="w-100 w-md-auto">
                            <a href="{{ route('approval.index') }}" class="btn btn-outline-primary rounded-pill shadow-sm w-100 w-md-auto">
                                <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if ($jurnals->isEmpty())
                <div class="row">
                    <div class="col-12">
                        <div class="card card-glass border-0">
                            <div class="card-body text-center py-7">
                                <div class="empty-state-icon mb-4">
                                    <i class="bi bi-journal-x text-muted opacity-25"></i>
                                </div>
                                <h3 class="text-muted mb-3 fw-light">Belum Ada Riwayat Konfirmasi</h3>
                                <p class="text-muted mb-4">Belum ada jurnal yang telah dikonfirmasi.</p>
                                <a href="{{ route('approval.index') }}" class="btn btn-primary rounded-pill px-4">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Approval
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Stats Cards -->
                <div class="row g-2 g-md-4 mb-4 mb-md-5">
                    <div class="col-6 col-md-6 col-xl-3">
                        <div class="card stats-card stats-card-success border-0 shadow-sm overflow-hidden h-100">
                            <div class="card-body p-2 p-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon me-2 me-md-3">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="fw-bold mb-0">{{ $jurnals->where('status', 'approved')->count() }}</h3>
                                        <span class="text-muted small">Disetujui</span>
                                    </div>
                                    <div class="stats-arrow d-none d-md-block">
                                        <i class="bi bi-arrow-up-short"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-6 col-xl-3">
                        <div class="card stats-card stats-card-danger border-0 shadow-sm overflow-hidden h-100">
                            <div class="card-body p-2 p-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon me-2 me-md-3">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="fw-bold mb-0">{{ $jurnals->where('status', 'rejected')->count() }}</h3>
                                        <span class="text-muted small">Ditolak</span>
                                    </div>
                                    <div class="stats-arrow d-none d-md-block">
                                        <i class="bi bi-arrow-down-short"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-6 col-xl-3">
                        <div class="card stats-card stats-card-warning border-0 shadow-sm overflow-hidden h-100">
                            <div class="card-body p-2 p-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon me-2 me-md-3">
                                        <i class="bi bi-clock-fill"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="fw-bold mb-0">0</h3>
                                        <span class="text-muted small">Menunggu</span>
                                    </div>
                                    <div class="stats-arrow d-none d-md-block">
                                        <i class="bi bi-arrow-right-short"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-6 col-xl-3">
                        <div class="card stats-card stats-card-primary border-0 shadow-sm overflow-hidden h-100">
                            <div class="card-body p-2 p-md-3">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon me-2 me-md-3">
                                        <i class="bi bi-journal-text"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="fw-bold mb-0">{{ $jurnals->total() }}</h3>
                                        <span class="text-muted small">Total Riwayat</span>
                                    </div>
                                    <div class="stats-arrow d-none d-md-block">
                                        <i class="bi bi-journal-plus"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm table-card">
                            <div class="card-header bg-transparent border-0 p-3 p-md-4">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                                    <div>
                                        <h5 class="card-title mb-0 fw-bold">
                                            <i class="bi bi-clock-history me-2 text-primary"></i>
                                            @if (auth()->user()->role === 'guru')
                                                Riwayat Konfirmasi sebagai Pembimbing
                                            @elseif(auth()->user()->role === 'iduka')
                                                Riwayat Konfirmasi sebagai IDUKA
                                            @else
                                                Daftar Riwayat Jurnal
                                            @endif
                                        </h5>
                                        <p class="text-muted mb-0 small">Riwayat lengkap konfirmasi jurnal</p>
                                    </div>
                                    <span class="badge-custom-count">
                                        <i class="bi bi-list-check me-1"></i>
                                        {{ $jurnals->count() }} dari {{ $jurnals->total() }} jurnal
                                    </span>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-scroll-hint">
                                    <i class="bi bi-arrow-left-right"></i> Geser tabel ke kiri/kanan untuk melihat semua kolom
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0" id="tabel-riwayat-jurnal">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-3 ps-md-4 py-3 text-uppercase fw-medium small text-muted border-0">No</th>
                                                <th class="py-3 text-uppercase fw-medium small text-muted border-0">Tanggal</th>
                                                <th class="py-3 text-uppercase fw-medium small text-muted border-0">Nama Siswa</th>
                                                <th class="py-3 text-uppercase fw-medium small text-muted border-0">Waktu</th>
                                                <th class="py-3 text-uppercase fw-medium small text-muted border-0">Status</th>
                                                <th class="py-3 text-uppercase fw-medium small text-muted border-0">Diperbarui</th>
                                                <th class="pe-3 pe-md-4 py-3 text-uppercase fw-medium small text-muted border-0 text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($jurnals as $journal)
                                                <tr class="journal-row border-bottom">
                                                    <td class="ps-3 ps-md-4 py-3">
                                                        <span class="fw-medium text-dark">
                                                            {{ ($jurnals->currentPage() - 1) * $jurnals->perPage() + $loop->iteration }}
                                                        </span>
                                                    </td>
                                                    <td class="py-3">
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-semibold text-dark">
                                                                {{ \Carbon\Carbon::parse($journal->tgl)->locale('id')->isoFormat('D MMM YYYY') }}
                                                            </span>
                                                            <small class="text-muted">
                                                                {{ \Carbon\Carbon::parse($journal->tgl)->locale('id')->isoFormat('dddd') }}
                                                            </small>
                                                        </div>
                                                    </td>
                                                    <td class="py-3">
                                                        <div class="d-flex align-items-center">
                                                            <div>
                                                                <span class="fw-semibold text-dark d-block">
                                                                    {{ $journal->user->name ?? 'Tidak diketahui' }}
                                                                </span>
                                                                <small class="text-muted">Siswa</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="py-3">
                                                        <span class="badge bg-light text-dark fw-medium px-3 py-2 border">
                                                            <i class="bi bi-clock me-1"></i>
                                                            {{ $journal->jam_mulai }} - {{ $journal->jam_selesai }}
                                                        </span>
                                                    </td>
                                                    <td class="py-3">
                                                        <div class="d-flex flex-column gap-2">
                                                            @if ($journal->status === 'rejected')
                                                                <span class="badge bg-danger bg-opacity-15 text-danger border-0 px-3 py-2 d-inline-flex align-items-center">
                                                                    <i class="bi bi-x-circle-fill me-1 small"></i>Ditolak
                                                                </span>
                                                            @elseif ($journal->status === 'approved')
                                                                <span class="badge bg-success bg-opacity-15 text-success border-0 px-3 py-2 d-inline-flex align-items-center">
                                                                    <i class="bi bi-check-circle-fill me-1 small"></i>Disetujui
                                                                </span>
                                                                @if ($journal->approved_by)
                                                                    <small class="text-success d-flex align-items-center">
                                                                        <i class="bi bi-check-circle-fill me-1 small"></i>Disetujui oleh: {{ $journal->approved_by }}
                                                                    </small>
                                                                @endif
                                                            @else
                                                                <span class="badge bg-warning bg-opacity-15 text-warning border-0 px-3 py-2 d-inline-flex align-items-center">
                                                                    <i class="bi bi-clock-fill me-1 small"></i>Menunggu
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="py-3">
                                                        <div class="d-flex flex-column">
                                                            <span class="small text-dark fw-medium">
                                                                {{ \Carbon\Carbon::parse($journal->updated_at)->locale('id')->isoFormat('D MMM YYYY') }}
                                                            </span>
                                                            <small class="text-muted">
                                                                {{ \Carbon\Carbon::parse($journal->updated_at)->diffForHumans() }}
                                                            </small>
                                                        </div>
                                                    </td>
                                                    <td class="pe-3 pe-md-4 text-end py-3">
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-primary rounded-pill shadow-sm"
                                                            onclick="showJournalDetail('{{ $journal->id }}')"
                                                            data-bs-toggle="modal" data-bs-target="#journalDetailModal"
                                                            data-journal-id="{{ $journal->id }}"
                                                            title="Lihat Detail Jurnal">
                                                            <i class="bi bi-eye me-1"></i> Detail
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @if ($jurnals->hasPages())
                                    <div class="card-footer bg-transparent border-0 p-3 p-md-4">
                                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                                            <div class="text-muted small text-center text-md-start">
                                                Menampilkan <span class="fw-semibold">{{ $jurnals->firstItem() }}</span> -
                                                <span class="fw-semibold">{{ $jurnals->lastItem() }}</span> dari
                                                <span class="fw-semibold">{{ $jurnals->total() }}</span> hasil
                                            </div>
                                            <nav aria-label="Pagination jurnal" class="pagination-wrap">
                                                {{ $jurnals->onEachSide(1)->links('pagination::bootstrap-5') }}
                                            </nav>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Modal Detail Jurnal -->
        <div class="modal fade" id="journalDetailModal" tabindex="-1" aria-labelledby="journalDetailModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 rounded-4 shadow-lg">
                    <div class="modal-header bg-gradient-primary text-white rounded-top-4 border-0 pb-3">
                        <h5 class="modal-title fw-bold" id="journalDetailModalLabel">
                            <i class="bi bi-journal-text me-2"></i>Detail Jurnal
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div id="journalDetailContent">
                            <div class="d-flex justify-content-center align-items-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light rounded-bottom-4 border-0 pt-3">
                        <button type="button" class="btn btn-light rounded-pill px-4 border" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i>Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showJournalDetail(id) {
            document.getElementById('journalDetailContent').innerHTML = `
                <div class="d-flex justify-content-center align-items-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `;

            fetch(`/approval/${id}/detail`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        document.getElementById('journalDetailContent').innerHTML = data.data;
                    } else {
                        throw new Error(data.message || 'Gagal memuat detail jurnal');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('journalDetailContent').innerHTML = `
                        <div class="alert alert-danger border-0 rounded-3 m-4" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                                <div>
                                    <h6 class="alert-heading mb-1">Terjadi Kesalahan</h6>
                                    <p class="mb-0">${error.message}</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill"
                                        onclick="showJournalDetail('${id}')">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Coba Lagi
                                </button>
                            </div>
                        </div>
                    `;
                });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.stats-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 150);
            });
        });
    </script>
@endsection
