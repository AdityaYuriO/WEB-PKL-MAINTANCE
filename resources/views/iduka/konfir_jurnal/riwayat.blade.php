@extends('layout.main')

@section('content')

    <div class="container-fluid py-4">
        <div class="container-xxl flex-grow-1 container-p-y">

            <!-- Header Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h3 fw-bold text-gradient-primary mb-1">Riwayat Konfirmasi Jurnal</h1>
                            <p class="text-muted mb-0">
                                @if (auth()->user()->role === 'guru')
                                    Daftar jurnal yang telah dikonfirmasi sebagai Pembimbing
                                @elseif(auth()->user()->role === 'iduka')
                                    Daftar jurnal yang telah dikonfirmasi sebagai IDUKA
                                @else
                                    Daftar jurnal yang telah dikonfirmasi
                                @endif
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('approval.index') }}" class="btn btn-outline-primary rounded-pill shadow-sm">
                                <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if ($jurnals->isEmpty())
                <!-- Empty State -->
                <div class="row">
                    <div class="col-12">
                        <div class="card card-glass border-0 overflow-hidden">
                            <div class="card-body text-center py-7">
                                <div class="empty-state-icon mb-4">
                                    <i class="bi bi-journal-x text-muted opacity-25"></i>
                                </div>
                                <h3 class="text-muted mb-3 fw-light">Belum Ada Riwayat Konfirmasi</h3>
                                <p class="text-muted mb-4">
                                    @if (auth()->user()->role === 'guru')
                                        Belum ada jurnal yang dikonfirmasi sebagai pembimbing.
                                    @elseif(auth()->user()->role === 'iduka')
                                        Belum ada jurnal yang dikonfirmasi sebagai IDUKA.
                                    @else
                                        Belum ada jurnal yang telah dikonfirmasi.
                                    @endif
                                </p>
                                <a href="{{ route('approval.index') }}" class="btn btn-primary rounded-pill px-4">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Approval
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Stats Cards -->
                <div class="row g-4 mb-5">
                    <div class="col-xl-3 col-md-6">
                        <div class="card stats-card stats-card-success border-0 shadow-sm overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon me-3">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="fw-bold mb-0">
                                            {{ $jurnals->where('status', 'approved')->count() }}
                                        </h3>
                                        <span class="text-muted small">Disetujui</span>
                                    </div>
                                    <div class="stats-arrow">
                                        <i class="bi bi-arrow-up-short"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card stats-card stats-card-danger border-0 shadow-sm overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon me-3">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="fw-bold mb-0">
                                            {{ $jurnals->where('status', 'rejected')->count() }}
                                        </h3>
                                        <span class="text-muted small">Ditolak</span>
                                    </div>
                                    <div class="stats-arrow">
                                        <i class="bi bi-arrow-down-short"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card stats-card stats-card-warning border-0 shadow-sm overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon me-3">
                                        <i class="bi bi-clock-fill"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="fw-bold mb-0">0</h3>
                                        <span class="text-muted small">Menunggu</span>
                                    </div>
                                    <div class="stats-arrow">
                                        <i class="bi bi-arrow-right-short"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="card stats-card stats-card-primary border-0 shadow-sm overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="stats-icon me-3">
                                        <i class="bi bi-journal-text"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h3 class="fw-bold mb-0">{{ $jurnals->total() }}</h3>
                                        <span class="text-muted small">Total Riwayat</span>
                                    </div>
                                    <div class="stats-arrow">
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
                        <div class="card border-0 shadow-sm overflow-hidden">
                            <div class="card-header bg-transparent border-0 p-4">
                                <div class="d-flex justify-content-between align-items-center">
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
                                    <span
                                        class="badge bg-primary bg-opacity-10 text-white py-2 px-3 rounded-pill fw-medium">
                                        <i class="bi bi-list-check me-1 text-white"></i>
                                        {{ $jurnals->count() }} dari {{ $jurnals->total() }} jurnal
                                    </span>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive text-nowrap">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="ps-4 py-3 text-uppercase fw-medium small text-muted border-0">No</th>
                                                <th class="py-3 text-uppercase fw-medium small text-muted border-0">Tanggal</th>
                                                <th class="py-3 text-uppercase fw-medium small text-muted border-0">Nama Siswa</th>
                                                <th class="py-3 text-uppercase fw-medium small text-muted border-0">Waktu</th>
                                                <th class="py-3 text-uppercase fw-medium small text-muted border-0">Status</th>
                                                <th class="py-3 text-uppercase fw-medium small text-muted border-0">Diperbarui</th>
                                                <th class="pe-4 py-3 text-uppercase fw-medium small text-muted border-0 text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($jurnals as $journal)
                                                <tr class="journal-row border-bottom">
                                                    <td class="ps-4 py-3">
                                                        <span class="fw-medium text-dark">{{ ($jurnals->currentPage() - 1) * $jurnals->perPage() + $loop->iteration }}</span>
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
                                                            <!-- PERUBAHAN: Tampilkan status yang sesuai dengan sistem baru -->
                                                            @if ($journal->status === 'rejected')
                                                                <span class="badge bg-danger bg-opacity-15 text-danger border-0 px-3 py-2 d-inline-flex align-items-center">
                                                                    <i class="bi bi-x-circle-fill me-1 small"></i>Ditolak
                                                                </span>
                                                            @elseif ($journal->status === 'approved')
                                                                <span class="badge bg-success bg-opacity-15 text-success border-0 px-3 py-2 d-inline-flex align-items-center">
                                                                    <i class="bi bi-check-circle-fill me-1 small"></i>Disetujui
                                                                </span>
                                                                <!-- PERUBAHAN: Tampilkan siapa yang menyetujui -->
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
                                                    <td class="pe-4 text-end py-3">
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
                                    <div class="card-footer bg-transparent border-0 p-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-muted small">
                                                Menampilkan <span class="fw-semibold">{{ $jurnals->firstItem() }}</span> -
                                                <span class="fw-semibold">{{ $jurnals->lastItem() }}</span> dari
                                                <span class="fw-semibold">{{ $jurnals->total() }}</span> hasil
                                            </div>
                                            <div>
                                                {{ $jurnals->links() }}
                                            </div>
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
            <div class="modal-dialog modal-lg modal-dialog-centered">
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

    @push('styles')
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
                --shadow-card: 0 4px 20px rgba(0, 0, 0, 0.08);
            }

            /* Base Styles */
            body {
                background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                min-height: 100vh;
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
                font-weight: 400;
                line-height: 1.6;
            }

            .container-fluid {
                max-width: 1400px;
            }

            /* Text Gradients */
            .text-gradient-primary {
                background: var(--primary-gradient);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            /* Card Styles */
            .card {
                backdrop-filter: blur(10px);
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                border: 1px solid var(--glass-border);
            }

            .card-glass {
                background: var(--glass-bg);
                border: 1px solid var(--glass-border);
            }

            .card:hover {
                transform: translateY(-5px);
                box-shadow: var(--shadow-primary) !important;
            }

            .card-header {
                background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            }

            /* Stats Cards */
            .stats-card {
                position: relative;
                overflow: hidden;
            }

            .stats-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
            }

            .stats-card-success::before {
                background: var(--success-gradient);
            }

            .stats-card-danger::before {
                background: var(--danger-gradient);
            }

            .stats-card-warning::before {
                background: var(--warning-gradient);
            }

            .stats-card-primary::before {
                background: var(--primary-gradient);
            }

            .stats-icon {
                width: 50px;
                height: 50px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
            }

            .stats-card-success .stats-icon {
                background: linear-gradient(135deg, rgba(79, 172, 254, 0.1) 0%, rgba(0, 242, 254, 0.1) 100%);
                color: #4facfe;
            }

            .stats-card-danger .stats-icon {
                background: linear-gradient(135deg, rgba(240, 147, 251, 0.1) 0%, rgba(245, 87, 108, 0.1) 100%);
                color: #f5576c;
            }

            .stats-card-warning .stats-icon {
                background: linear-gradient(135deg, rgba(67, 233, 123, 0.1) 0%, rgba(56, 249, 215, 0.1) 100%);
                color: #43e97b;
            }

            .stats-card-primary .stats-icon {
                background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
                color: #667eea;
            }

            .stats-arrow {
                font-size: 1.5rem;
                opacity: 0.7;
            }

            /* Table Styles */
            .table {
                --bs-table-bg: transparent;
                margin-bottom: 0;
            }

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
            }

            .table thead th::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                height: 1px;
                background: rgba(255, 255, 255, 0.3);
            }

            .table tbody tr {
                transition: all 0.3s ease;
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            }

            .table tbody tr:hover {
                background: linear-gradient(135deg, rgba(102, 126, 234, 0.03) 0%, rgba(118, 75, 162, 0.03) 100%);
                transform: translateX(8px);
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            }

            .table tbody tr:last-child {
                border-bottom: none;
            }

            .table tbody td {
                vertical-align: middle;
                border: none;
                padding: 1rem 1.5rem;
            }

            /* Journal Row Animation */
            .journal-row {
                animation: slideInRight 0.5s ease forwards;
                opacity: 0;
                transform: translateX(-20px);
            }

            @keyframes slideInRight {
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            /* Badge Styles */
            .badge {
                font-weight: 500;
                border-radius: 8px;
                padding: 0.5rem 1rem;
                font-size: 0.75rem;
                letter-spacing: 0.3px;
                border: 1px solid transparent;
            }

            .badge.bg-success {
                background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
                color: white;
            }

            .badge.bg-danger {
                background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
                color: white;
            }

            .badge.bg-warning {
                background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%) !important;
                color: white;
            }

            .badge.bg-primary {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
                color: white;
            }

            /* Avatar Styles */
            .avatar-sm {
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 10px;
            }

            .avatar-title {
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1rem;
            }

            /* Button Styles */
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

            .btn-outline-light {
                border-color: rgba(255, 255, 255, 0.3);
                color: rgba(255, 255, 255, 0.9);
            }

            .btn-outline-light:hover {
                background: rgba(255, 255, 255, 0.1);
                border-color: rgba(255, 255, 255, 0.5);
                color: white;
            }

            /* Modal Styles */
            .modal-content {
                border: none;
                backdrop-filter: blur(20px);
                background: rgba(255, 255, 255, 0.95);
                box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            }

            .modal-header.bg-gradient-primary {
                background: var(--primary-gradient) !important;
            }

            /* Empty State */
            .empty-state-icon {
                font-size: 4rem;
                opacity: 0.5;
            }

            /* Pagination Styles */
            .pagination .page-link {
                border: none;
                color: #667eea;
                margin: 0 2px;
                border-radius: 8px;
                padding: 0.5rem 1rem;
                font-weight: 500;
                transition: all 0.3s ease;
            }

            .pagination .page-item.active .page-link {
                background: var(--primary-gradient);
                border: none;
                color: white;
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            }

            .pagination .page-link:hover {
                background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
                transform: translateY(-1px);
            }

            /* Loading Animation */
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Staggered Animation for Table Rows */
            .journal-row:nth-child(1) {
                animation-delay: 0.1s;
            }

            .journal-row:nth-child(2) {
                animation-delay: 0.2s;
            }

            .journal-row:nth-child(3) {
                animation-delay: 0.3s;
            }

            .journal-row:nth-child(4) {
                animation-delay: 0.4s;
            }

            .journal-row:nth-child(5) {
                animation-delay: 0.5s;
            }

            .journal-row:nth-child(6) {
                animation-delay: 0.6s;
            }

            .journal-row:nth-child(7) {
                animation-delay: 0.7s;
            }

            .journal-row:nth-child(8) {
                animation-delay: 0.8s;
            }

            .journal-row:nth-child(9) {
                animation-delay: 0.9s;
            }

            .journal-row:nth-child(10) {
                animation-delay: 1s;
            }

            /* Scrollbar Styling */
            ::-webkit-scrollbar {
                width: 6px;
            }

            ::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.1);
                border-radius: 3px;
            }

            ::-webkit-scrollbar-thumb {
                background: var(--primary-gradient);
                border-radius: 3px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .container-fluid {
                    padding-left: 1rem;
                    padding-right: 1rem;
                }

                .table-responsive {
                    font-size: 0.875rem;
                }

                .modal-dialog {
                    margin: 1rem;
                }

                .card:hover {
                    transform: none;
                }

                .table tbody tr:hover {
                    transform: none;
                }

                .stats-card {
                    margin-bottom: 1rem;
                }
            }

            /* Print Styles */
            @media print {

                .btn,
                .pagination,
                .modal-footer {
                    display: none !important;
                }

                .card {
                    box-shadow: none !important;
                    border: 1px solid #ddd !important;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            function showJournalDetail(id) {
                // Reset modal content with loading indicator
                document.getElementById('journalDetailContent').innerHTML = `
                <div class="d-flex justify-content-center align-items-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `;

                // Fetch journal detail
                fetch(`/approval/${id}/detail`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
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

            // Add smooth animations on load
            document.addEventListener('DOMContentLoaded', function() {
                // Animate stats cards
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

                // Add intersection observer for table rows
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.animationPlayState = 'running';
                        }
                    });
                }, observerOptions);

                document.querySelectorAll('.journal-row').forEach(row => {
                    row.style.animationPlayState = 'paused';
                    observer.observe(row);
                });
            });

            // Auto refresh setiap 30 detik jika ada data
            @if (!$jurnals->isEmpty())
                setInterval(function() {
                    // Optional: Uncomment to enable auto refresh
                    // window.location.reload();
                }, 30000);
            @endif
        </script>
    @endpush
@endsection


