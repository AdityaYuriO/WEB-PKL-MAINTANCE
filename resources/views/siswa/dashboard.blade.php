@extends('layout.main')

@section('content')
<style>
.pkl-status-alert {
    border-radius: 14px;
    border: none;
    border-left: 4px solid;
    padding: 16px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 20px;
    background: #fff;
    box-shadow: 0 1px 4px rgba(15,23,42,0.07);
}
.pkl-status-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.pkl-status-title {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 2px;
}
.pkl-status-text {
    font-size: 13px;
    color: #64748b;
    margin: 0;
    line-height: 1.45;
}

/* ---- Merged Info + Aksi Card ---- */
.info-action-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 10px -2px rgba(15,23,42,0.06);
    overflow: visible;
    position: relative;
    z-index: 5;
    margin-bottom: 16px;
}

/* Top section: pembimbing info */
.info-action-top {
    padding: 18px 20px 16px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    gap: 14px;
}
.info-action-top:last-child {
    border-bottom: none;
}
.info-action-teacher-icon {
    width: 44px;
    height: 44px;
    background: #eff6ff;
    border: 1px solid #dbeafe;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.info-action-label {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    letter-spacing: 0.65px;
    text-transform: uppercase;
    margin: 0 0 3px;
}
.info-action-teacher-name {
    font-size: 16px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
    line-height: 1.35;
    overflow-wrap: break-word;
    word-break: break-word;
}
.info-action-teacher-name.is-empty {
    color: #64748b;
    font-weight: 600;
}

.info-action-bottom {
    padding: 16px;
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
    align-items: stretch;
}
.action-tile-form {
    display: flex;
    width: 100%;
    min-width: 0;
    margin: 0;
}

/* Icon tile button */
.action-tile {
    width: 100%;
    min-width: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 18px 12px 15px;
    border-radius: 14px;
    border: 1px solid;
    cursor: pointer;
    text-decoration: none;
    transition: transform 0.16s ease, box-shadow 0.16s ease, background-color 0.16s ease, border-color 0.16s ease;
    -webkit-tap-highlight-color: transparent;
    user-select: none;
    position: relative;
    appearance: none;
}
.action-tile:hover {
    transform: translateY(-2px);
    text-decoration: none;
}
.action-tile:active {
    transform: translateY(0) scale(0.985);
    box-shadow: none;
}

.action-tile-primary {
    background: #eff6ff;
    border-color: #bfdbfe;
    color: #1e40af;
}
.action-tile-primary:hover {
    background: #dbeafe;
    border-color: #93c5fd;
    box-shadow: 0 8px 18px -6px rgba(37,99,235,0.24);
}

.action-tile-secondary {
    background: #fffbeb;
    border-color: #fde68a;
    color: #92400e;
    --pindah-shadow: #fde68a;
    --pindah-dark: #b45309;
    --pindah-mid: #d97706;
    --pindah-bright: #f59e0b;
    --pindah-face: #fffbeb;
    --pindah-accent: #fbbf24;
    --pindah-deep: #92400e;
}
.action-tile-secondary:hover {
    background: #fef3c7;
    border-color: #fcd34d;
    box-shadow: 0 8px 18px -6px rgba(217,119,6,0.22);
}

/* Tile disabled / unavailable state */
.action-tile-disabled {
    background: #f8fafc;
    border-color: #e2e8f0;
    color: #64748b;
    --pindah-shadow: #e2e8f0;
    --pindah-dark: #64748b;
    --pindah-mid: #94a3b8;
    --pindah-bright: #cbd5e1;
    --pindah-face: #f8fafc;
    --pindah-accent: #cbd5e1;
    --pindah-deep: #475569;
}
.action-tile-disabled:hover {
    transform: translateY(-1px);
    background: #f1f5f9;
    border-color: #cbd5e1;
    box-shadow: 0 4px 12px -4px rgba(15,23,42,0.08);
}

/* 3D icon container */
.action-tile-icon {
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: transform 0.16s ease;
}
.action-tile:hover .action-tile-icon {
    transform: translateY(-2px) scale(1.04);
}
.action-tile-icon svg {
    display: block;
    width: 54px;
    height: 54px;
}

.action-tile-label {
    font-size: 13px;
    font-weight: 700;
    line-height: 1.25;
    text-align: center;
    letter-spacing: -0.1px;
}

/* Dropdown trigger wrapper */
.action-tile-dropdown-wrap {
    position: relative;
    display: flex;
    width: 100%;
    min-width: 0;
}
.action-tile-caret {
    position: absolute;
    top: 9px;
    right: 9px;
    width: 20px;
    height: 20px;
    background: rgba(37,99,235,0.12);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.18s ease, background-color 0.15s ease;
}
.action-tile[aria-expanded="true"] .action-tile-caret {
    transform: rotate(180deg);
    background: rgba(37,99,235,0.2);
}
.action-tile-dropdown-wrap .dropdown-menu {
    position: absolute;
    top: calc(100% + 8px) !important;
    left: 0 !important;
    right: auto !important;
    transform: none !important;
    margin: 0 !important;
    min-width: 215px;
    max-width: calc(100vw - 48px);
    border-radius: 13px;
    border: 1px solid #e2e8f0;
    background: #fff;
    box-shadow: 0 12px 28px -6px rgba(15,23,42,0.16);
    padding: 6px;
    z-index: 1030;
    animation: none;
}
.action-tile-dropdown-wrap .dropdown-item {
    border-radius: 9px;
    font-size: 13.5px;
    font-weight: 500;
    padding: 9px 12px;
    display: flex;
    align-items: center;
    gap: 8px;
    color: #334155;
    white-space: normal;
}
.action-tile-dropdown-wrap .dropdown-item:hover,
.action-tile-dropdown-wrap .dropdown-item:focus {
    background: #f1f5f9;
    color: #0f172a;
}

@media (max-width: 575.98px) {
    .info-action-top { padding: 15px 16px 14px; gap: 12px; }
    .info-action-teacher-icon { width: 40px; height: 40px; border-radius: 11px; }
    .info-action-teacher-name { font-size: 14.5px; }
    .info-action-bottom { padding: 12px 14px 14px; gap: 10px; }
    .action-tile { padding: 15px 8px 13px; gap: 8px; border-radius: 13px; }
    .action-tile-icon { width: 48px; height: 48px; }
    .action-tile-icon svg { width: 46px; height: 46px; }
    .action-tile-label { font-size: 12px; }
    .action-tile-caret { top: 7px; right: 7px; width: 18px; height: 18px; }
}

/* Alert data belum lengkap */
.data-alert {
    margin: 14px 16px 0;
    padding: 12px 14px;
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-radius: 11px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}
.data-alert-text {
    font-size: 13px;
    color: #92400e;
    line-height: 1.45;
    flex: 1;
    min-width: 0;
}
.data-alert-actions {
    margin-top: 8px;
}
.data-alert-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 14px;
    background: #2563eb;
    color: #fff;
    border-radius: 8px;
    font-size: 12.5px;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.15s;
}
.data-alert-btn:hover { background: #1d4ed8; color: #fff; text-decoration: none; }
@media (max-width: 575.98px) {
    .data-alert { margin: 12px 14px 0; }
}

/* ---- Riwayat Section ---- */
.riwayat-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 4px rgba(15,23,42,0.06);
    margin-bottom: 16px;
    overflow: hidden;
}
.riwayat-card-header {
    padding: 14px 18px 13px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    gap: 8px;
}
.riwayat-card-header .riwayat-title {
    font-size: 13.5px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
}
.riwayat-card-header svg {
    color: #3b82f6;
    flex-shrink: 0;
}

.riwayat-table-wrap {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.riwayat-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13.5px;
}
.riwayat-table thead th {
    background: #f8fafc;
    color: #64748b;
    font-size: 12px;
    font-weight: 600;
    padding: 10px 14px;
    white-space: nowrap;
    border-bottom: 1px solid #e2e8f0;
    letter-spacing: 0.3px;
    text-transform: uppercase;
}
.riwayat-table tbody td {
    padding: 11px 14px;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}
.riwayat-table tbody tr:last-child td {
    border-bottom: none;
}
.riwayat-table tbody tr:hover td {
    background: #f8fafc;
}
.riwayat-table .fw-name {
    font-weight: 600;
    color: #0f172a;
}
.riwayat-table .num-col {
    color: #94a3b8;
    font-size: 12px;
    text-align: center;
    white-space: nowrap;
    width: 36px;
}
.empty-row td {
    color: #94a3b8;
    font-size: 13px;
    text-align: center;
    padding: 28px 14px;
}

/* ---- Mobile Card List ---- */
.riwayat-mobile-list {
    display: none;
    flex-direction: column;
    gap: 1px;
}
.riwayat-item {
    padding: 13px 16px;
    border-bottom: 1px solid #f1f5f9;
    background: #fff;
}
.riwayat-item:last-child {
    border-bottom: none;
}
.riwayat-item-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 6px;
}
.riwayat-item-num {
    font-size: 11px;
    font-weight: 600;
    color: #94a3b8;
    white-space: nowrap;
    padding-top: 1px;
}
.riwayat-item-nama {
    font-size: 13.5px;
    font-weight: 700;
    color: #0f172a;
    flex: 1;
    line-height: 1.35;
}
.riwayat-item-nama-sec {
    font-size: 12px;
    color: #3b82f6;
    font-weight: 600;
    margin-top: 2px;
}
.riwayat-item-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    flex-wrap: wrap;
}
.riwayat-item-date {
    font-size: 12px;
    color: #94a3b8;
    display: flex;
    align-items: center;
    gap: 4px;
}
.riwayat-item-actions {
    display: flex;
    gap: 6px;
    align-items: center;
    flex-wrap: wrap;
}
.riwayat-item-actions .btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 5px 10px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    gap: 4px;
    border: 1px solid;
    text-decoration: none;
    cursor: pointer;
    background: transparent;
    white-space: nowrap;
}
.btn-action-detail {
    color: #0ea5e9;
    border-color: #bae6fd;
}
.btn-action-pdf {
    color: #ef4444;
    border-color: #fecaca;
}
.btn-action-cancel {
    color: #f59e0b;
    border-color: #fde68a;
}
.riwayat-mobile-empty {
    color: #94a3b8;
    font-size: 13px;
    text-align: center;
    padding: 24px 16px;
}

/* Responsive Toggle */
@media (max-width: 767.98px) {
    .riwayat-table-wrap { display: none; }
    .riwayat-mobile-list { display: flex; }
}

/* ---- Status Badges ---- */
.badge-status {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11.5px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 999px;
    white-space: nowrap;
    line-height: 1.3;
}
.badge-warning  { background: #fffbeb; color: #d97706; }
.badge-success  { background: #f0fdf4; color: #16a34a; }
.badge-danger   { background: #fef2f2; color: #dc2626; }
.badge-info     { background: #ecfeff; color: #0891b2; }
.badge-primary  { background: #eff6ff; color: #2563eb; }
.badge-secondary{ background: #f8fafc; color: #64748b; }

.ui-hist-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05), 0 1px 2px rgba(15, 23, 42, 0.03);
    margin-bottom: 16px;
    overflow: hidden;
}
.ui-hist-header {
    padding: 14px 18px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    background: #ffffff;
}
.ui-hist-header-main {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}
.ui-hist-header-icon {
    width: 34px;
    height: 34px;
    border-radius: 9px;
    background: #eff6ff;
    border: 1px solid #dbeafe;
    color: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.ui-hist-title {
    font-size: 14px;
    font-weight: 600;
    color: #0f172a;
    margin: 0;
    letter-spacing: -0.015em;
    line-height: 1.35;
}

/* List Container */
.ui-hist-body {
    padding: 12px 14px;
    background: #ffffff;
}
.ui-hist-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.ui-hist-item {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    transition: background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
}
.ui-hist-item:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    box-shadow: 0 2px 6px -2px rgba(15, 23, 42, 0.06);
}
.ui-hist-item-main {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
    flex: 1;
}
.ui-hist-num {
    min-width: 28px;
    height: 28px;
    padding: 0 7px;
    border-radius: 8px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #64748b;
    font-size: 11.5px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-variant-numeric: tabular-nums;
}
.ui-hist-info {
    min-width: 0;
    flex: 1;
}
.ui-hist-name {
    font-size: 14px;
    font-weight: 600;
    color: #0f172a;
    line-height: 1.4;
    overflow-wrap: break-word;
    word-break: break-word;
}
.ui-hist-meta {
    margin-top: 3px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.ui-hist-date {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 12.5px;
    font-weight: 500;
    color: #64748b;
    line-height: 1.3;
    font-variant-numeric: tabular-nums;
}
.ui-hist-date svg {
    color: #94a3b8;
    flex-shrink: 0;
}

/* Right Zone (Status Badge + Actions) */
.ui-hist-right {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-shrink: 0;
}
.ui-hist-status {
    display: flex;
    align-items: center;
}
.ui-hist-actions {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
    flex-wrap: wrap;
}

.ui-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 500;
    padding: 4px 10px 4px 7px;
    border-radius: 9999px;
    border: 1px solid;
    white-space: nowrap;
    line-height: 1.35;
}
.ui-badge-icon {
    width: 17px;
    height: 17px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.ui-badge-icon svg {
    display: block;
    width: 17px;
    height: 17px;
}
.ui-badge-warning {
    background: #fffbeb;
    border-color: #fde68a;
    color: #b45309;
}

.ui-badge-success {
    background: #f0fdf4;
    border-color: #bbf7d0;
    color: #15803d;
}

.ui-badge-danger {
    background: #fef2f2;
    border-color: #fecaca;
    color: #b91c1c;
}

.ui-badge-secondary {
    background: #f8fafc;
    border-color: #e2e8f0;
    color: #475569;
}

/* Shadcn Action Buttons */
.ui-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    height: 30px;
    padding: 0 10px;
    border-radius: 8px;
    border: 1px solid;
    font-size: 12px;
    font-weight: 600;
    line-height: 1;
    text-decoration: none;
    cursor: pointer;
    white-space: nowrap;
    transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease, transform 0.12s ease;
    -webkit-tap-highlight-color: transparent;
}
.ui-btn:hover { text-decoration: none; }
.ui-btn:active { transform: scale(0.97); }
.ui-btn svg {
    width: 13px;
    height: 13px;
    flex-shrink: 0;
}

.ui-btn-detail {
    background: #ffffff;
    border-color: #e2e8f0;
    color: #334155;
}
.ui-btn-detail:hover,
.ui-btn-detail:focus {
    background: #f1f5f9;
    border-color: #93c5fd;
    color: #1d4ed8;
}

.ui-btn-pdf {
    background: #fff1f2;
    border-color: #fecdd3;
    color: #be123c;
}
.ui-btn-pdf:hover,
.ui-btn-pdf:focus {
    background: #ffe4e6;
    border-color: #fda4af;
    color: #9f1239;
}

.ui-btn-cancel {
    background: #fffbeb;
    border-color: #fde68a;
    color: #b45309;
}
.ui-btn-cancel:hover,
.ui-btn-cancel:focus {
    background: #fef3c7;
    border-color: #fcd34d;
    color: #92400e;
}

/* Empty State */
.ui-hist-empty {
    padding: 28px 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    gap: 8px;
}
.ui-hist-empty-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
}
.ui-hist-empty-text {
    font-size: 13px;
    font-weight: 500;
    color: #64748b;
    margin: 0;
}

/* Mobile Layout (< 768px) */
@media (max-width: 767.98px) {
    .ui-hist-header {
        padding: 13px 15px;
    }
    .ui-hist-body {
        padding: 10px 12px;
    }
    .ui-hist-item {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
        padding: 12px 13px;
    }
    .ui-hist-item-main {
        align-items: flex-start;
        gap: 10px;
    }
    .ui-hist-num {
        min-width: 24px;
        height: 24px;
        padding: 0 6px;
        font-size: 11px;
        margin-top: 1px;
    }
    .ui-hist-name {
        font-size: 13.5px;
    }
    .ui-hist-date {
        font-size: 12px;
    }
    .ui-hist-right {
        width: 100%;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
        padding-top: 9px;
        border-top: 1px solid #f1f5f9;
    }
    .ui-hist-actions {
        flex: 1 1 auto;
        justify-content: flex-end;
    }
}
</style>

<div class="container-xxl flex-grow-1 container-p-y">

    @if(isset($statusPindahPkl))
        @php
            $alertMap = [
                'menunggu'                     => ['border'=>'#f59e0b','icon_bg'=>'#fff7ed','icon_color'=>'#f59e0b','title'=>'Menunggu Verifikasi','text'=>'Pengajuan pindah PKL Anda sedang dalam proses verifikasi. Silakan hubungi Kaprog jurusan Anda untuk mempercepat proses.','icon_path'=>'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z'],
                'menunggu_surat'               => ['border'=>'#06b6d4','icon_bg'=>'#ecfeff','icon_color'=>'#06b6d4','title'=>'Menunggu Surat Dicetak','text'=>'Pengajuan pindah PKL Anda telah diterima, selanjutnya menunggu surat dicetak.','icon_path'=>'M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z'],
                'siap_kirim'                   => ['border'=>'#3b82f6','icon_bg'=>'#eff6ff','icon_color'=>'#3b82f6','title'=>'Surat Telah Dicetak','text'=>'Surat pengajuan pindah tempat PKL kamu telah dicetak dan dikirim ke IDUKA. Silahkan tunggu verifikasi dari IDUKA.','icon_path'=>'M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5'],
                'diterima_iduka'               => ['border'=>'#22c55e','icon_bg'=>'#f0fdf4','icon_color'=>'#22c55e','title'=>'Pindah PKL Diterima','text'=>'Pengajuan pindah PKL Anda telah diterima. Silahkan buat pengajuan baru untuk mengajukan tempat PKL kamu.','icon_path'=>'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
                'ditolak_iduka'                => ['border'=>'#ef4444','icon_bg'=>'#fef2f2','icon_color'=>'#ef4444','title'=>'Pindah PKL Ditolak','text'=>'Pengajuan pindah PKL Anda ditolak. Silahkan hubungi Kaprog jurusan kamu atau IDUKA kamu untuk alasan lebih lanjut.','icon_path'=>'m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
            ];
            $ac = $alertMap[$statusPindahPkl->status] ?? null;
        @endphp

        @if($ac)
            <div class="pkl-status-alert" style="border-left-color: {{ $ac['border'] }};">
                <div class="pkl-status-icon" style="background: {{ $ac['icon_bg'] }};">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                         fill="none" stroke="{{ $ac['icon_color'] }}" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="{{ $ac['icon_path'] }}"/>
                    </svg>
                </div>
                <div>
                    <p class="pkl-status-title">{{ $ac['title'] }}</p>
                    <p class="pkl-status-text">{{ $ac['text'] }}</p>
                </div>
            </div>
        @endif
    @endif

    @php
        $lengkap = isset($isDataLengkap) ? $isDataLengkap : (auth()->check() && auth()->user()->isDataPribadiLengkap());
        $unfilled = isset($missingFields) ? $missingFields : (auth()->check() ? auth()->user()->getMissingDataPribadiFields() : []);
    @endphp

    <div class="info-action-card mb-4">

        {{-- TOP: Nama Pembimbing --}}
        <div class="info-action-top">
            <div class="info-action-teacher-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24"
                     fill="none" stroke="#2563eb" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"
                     class="lucide lucide-graduation-cap">
                    <path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/>
                    <path d="M22 10v6"/>
                    <path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/>
                </svg>
            </div>
            <div style="min-width:0;flex:1;">
                <p class="info-action-label">PEMBIMBING PKL</p>
                <p class="info-action-teacher-name {{ $user->pembimbing ? '' : 'is-empty' }}">
                    {{ $user->pembimbing->nama ?? 'Belum ada pembimbing' }}
                </p>
            </div>
        </div>

        @if (!$lengkap)
            <div class="data-alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                     fill="none" stroke="#d97706" stroke-width="2.2" stroke-linecap="round"
                     stroke-linejoin="round" style="flex-shrink:0;margin-top:2px;" class="lucide lucide-alert-circle">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" x2="12" y1="8" y2="12"/>
                    <line x1="12" x2="12.01" y1="16" y2="16"/>
                </svg>
                <div class="data-alert-text">
                    @if (!empty($unfilled) && count($unfilled) > 0 && count($unfilled) <= 4)
                        Data belum lengkap Ã¢â‚¬â€ belum terisi: <strong>{{ implode(', ', $unfilled) }}</strong>.
                    @else
                        Data kamu belum terisi sepenuhnya.
                    @endif
                    Ayo lengkapi!
                    @if (auth()->user()->role == 'siswa')
                        <div class="data-alert-actions">
                            <a href="{{ route('siswa.data_pribadi.create') }}" class="data-alert-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                                     class="lucide lucide-square-pen">
                                    <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/>
                                </svg>
                                Lengkapi Data
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if (auth()->user()->role == 'siswa')
        <div class="info-action-bottom">

            @php
                $bisaAjukan = (!$sudahAjukan || ($statusPindahPkl && $statusPindahPkl->status == 'diterima_iduka'));
            @endphp
            <div class="action-tile-dropdown-wrap dropdown">
                <button type="button"
                        class="action-tile action-tile-primary"
                        data-bs-toggle="dropdown"
                        data-bs-display="static"
                        aria-expanded="false">
                    <span class="action-tile-caret" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24"
                             fill="none" stroke="#2563eb" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </span>
                    <span class="action-tile-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="none">
                            <ellipse cx="30" cy="57.5" rx="20" ry="3.5" fill="#bfdbfe"/>
                            <rect x="11" y="9" width="33" height="42" rx="6" transform="rotate(-7 27 30)" fill="#1d4ed8"/>
                            <rect x="15" y="10" width="33" height="42" rx="6" fill="#3b82f6"/>
                            <path d="M18 11h19l10 10v26a5 5 0 0 1-5 5H18a5 5 0 0 1-5-5V16a5 5 0 0 1 5-5Z" fill="#93c5fd"/>
                            <path d="M18 8h19l10 10v26a5 5 0 0 1-5 5H18a5 5 0 0 1-5-5V13a5 5 0 0 1 5-5Z" fill="#ffffff" stroke="#dbeafe" stroke-width="1"/>
                            <path d="M37 8v7a3 3 0 0 0 3 3h7Z" fill="#bfdbfe"/>
                            <rect x="19" y="21" width="15" height="3.2" rx="1.6" fill="#2563eb"/>
                            <rect x="19" y="27.5" width="21" height="3" rx="1.5" fill="#93c5fd"/>
                            <rect x="19" y="34" width="13" height="3" rx="1.5" fill="#bfdbfe"/>
                            <circle cx="46" cy="46.5" r="11" fill="#1e3a8a"/>
                            <circle cx="46" cy="44" r="11" fill="#2563eb"/>
                            <circle cx="46" cy="44" r="9.5" stroke="#60a5fa" stroke-width="1"/>
                            <path d="M46 39.2v9.6M41.2 44h9.6" stroke="#ffffff" stroke-width="2.8" stroke-linecap="round"/>
                        </svg>
                    </span>
                    <span class="action-tile-label">Buat Pengajuan</span>
                </button>

                <ul class="dropdown-menu">
                    <li>
                        @if ($bisaAjukan)
                            <a href="{{ route('iduka.usulan') }}" class="dropdown-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                                     fill="none" stroke="#2563eb" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/>
                                </svg>
                                Ajukan Tempat Baru
                            </a>
                        @else
                            @if ($statusAjukan === 'proses')
                                <a href="#" class="dropdown-item"
                                   onclick="event.preventDefault(); showInfo('Pengajuan Sedang Diproses', 'Kamu sudah mengajukan tempat PKL dan sedang diproses.')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                                         fill="none" stroke="#f59e0b" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
                                    </svg>
                                    Sedang Diproses
                                </a>
                            @elseif($statusAjukan === 'diterima')
                                <a href="#" class="dropdown-item"
                                   onclick="event.preventDefault(); showInfo('Tidak dapat mengajukan!', 'Kamu sudah mengajukan dan pengajuan telah diterima.')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                                         fill="none" stroke="#22c55e" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                    Pengajuan Diterima
                                </a>
                            @else
                                <a href="#" class="dropdown-item"
                                   onclick="event.preventDefault(); showInfo('Pengajuan Tidak Diterima', 'Pengajuan sedang diproses atau telah diterima.')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                                         fill="none" stroke="#ef4444" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/>
                                    </svg>
                                    Tidak Bisa Mengajukan
                                </a>
                            @endif
                        @endif
                    </li>
                </ul>
            </div>

            @php
                $pengajuanAktif = isset($statusPindahPkl) && in_array($statusPindahPkl->status, ['menunggu','menunggu_surat','diterima_iduka','siap_kirim','menunggu_konfirmasi_iduka']);
                $bisaPindah = $sudahDiterima && !$pengajuanAktif;
            @endphp

            @if ($bisaPindah)
                <form action="{{ route('pindah-pkl.ajukan') }}" method="POST" class="action-tile-form">
                    @csrf
                    <button type="submit" onclick="return confirmAjukanPindah()" class="action-tile action-tile-secondary">
            @elseif ($sudahDiterima && $pengajuanAktif)
                <button type="button"
                        onclick="showInfo('Pengajuan Aktif', 'Kamu sudah memiliki pengajuan pindah tempat PKL yang aktif.')"
                        class="action-tile action-tile-disabled">
            @else
                <button type="button"
                        onclick="showInfo('Belum Bisa Pindah', 'Kamu hanya bisa mengajukan pindah jika sudah diterima di tempat PKL.')"
                        class="action-tile action-tile-disabled">
            @endif
                        <span class="action-tile-icon" aria-hidden="true">
                            {{-- 3D Layered Place Transfer Icon (Origin Building + Transfer Arrow + Destination Pin) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" fill="none">
                                <ellipse cx="32" cy="57.5" rx="22" ry="3.5" fill="var(--pindah-shadow, #fde68a)"/>
                                {{-- Back 3D origin place block --}}
                                <rect x="7" y="13" width="24" height="24" rx="6" fill="var(--pindah-dark, #b45309)"/>
                                <rect x="7" y="10" width="24" height="24" rx="6" fill="var(--pindah-accent, #fbbf24)"/>
                                <rect x="11.5" y="14.5" width="15" height="15" rx="3" fill="var(--pindah-face, #fffbeb)"/>
                                <rect x="14.5" y="17.5" width="3.8" height="3.8" rx="0.9" fill="var(--pindah-mid, #d97706)"/>
                                <rect x="19.7" y="17.5" width="3.8" height="3.8" rx="0.9" fill="var(--pindah-mid, #d97706)"/>
                                <rect x="17" y="23.2" width="4" height="6.3" rx="0.9" fill="var(--pindah-dark, #b45309)"/>
                                {{-- 3D curved transfer arrow --}}
                                <path d="M15 40c0 6.5 4.8 10.5 13 10.5h2v3l6-5-6-5v3h-2c-5 0-8-2.4-8-6.5h-5Z" transform="translate(0, 2.2)" fill="var(--pindah-deep, #92400e)"/>
                                <path d="M15 40c0 6.5 4.8 10.5 13 10.5h2v3l6-5-6-5v3h-2c-5 0-8-2.4-8-6.5h-5Z" fill="var(--pindah-bright, #f59e0b)"/>
                                {{-- Foreground 3D destination pin --}}
                                <path d="M45 19c-6.6 0-12 5.2-12 11.7 0 7.7 9.8 16.5 11.3 17.8a1.1 1.1 0 0 0 1.4 0C47.2 47.2 57 38.4 57 30.7 57 24.2 51.6 19 45 19Z" transform="translate(0, 3)" fill="var(--pindah-deep, #92400e)"/>
                                <path d="M45 19c-6.6 0-12 5.2-12 11.7 0 7.7 9.8 16.5 11.3 17.8a1.1 1.1 0 0 0 1.4 0C47.2 47.2 57 38.4 57 30.7 57 24.2 51.6 19 45 19Z" fill="var(--pindah-mid, #d97706)"/>
                                <circle cx="45" cy="30.5" r="7" fill="var(--pindah-face, #fffbeb)"/>
                                <circle cx="45" cy="30.5" r="3.6" fill="var(--pindah-bright, #f59e0b)"/>
                            </svg>
                        </span>
                        <span class="action-tile-label">Pindah PKL</span>
                    </button>
            @if ($bisaPindah)
                </form>
            @endif

        </div>
        @endif {{-- end role siswa action tiles --}}
    </div>

    @if (auth()->user()->role == 'siswa')

    {{-- Riwayat Permintaan Pindah Tempat PKL --}}
    @if(isset($riwayatPindahPkl) && $riwayatPindahPkl->count() > 0)
    <div class="riwayat-card">
        <div class="riwayat-card-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                 fill="none" stroke="#3b82f6" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m17 1 4 4-4 4"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/>
                <path d="m7 23-4-4 4-4"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/>
            </svg>
            <h6 class="riwayat-title">Riwayat Permintaan Pindah Tempat PKL</h6>
        </div>

        {{-- Desktop Table --}}
        <div class="riwayat-table-wrap">
            <table class="riwayat-table">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Tempat Lama</th>
                        <th>Tempat Baru</th>
                        <th>Tgl. Pengajuan</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayatPindahPkl as $index => $pindah)
                    <tr>
                        <td class="num-col">{{ $index + 1 }}</td>
                        <td class="fw-name">{{ $pindah->idukaLama->nama ?? 'Tidak Diketahui' }}</td>
                        <td style="color:#2563eb;font-weight:600;">{{ $pindah->iduka_baru_nama }}</td>
                        <td>{{ \Carbon\Carbon::parse($pindah->created_at)->format('d/m/Y') }}</td>
                        <td>
                            @if($pindah->status == 'menunggu')
                                <span class="badge-status badge-warning">Menunggu Verifikasi</span>
                            @elseif($pindah->status == 'diterima_iduka')
                                <span class="badge-status badge-success">Diterima</span>
                            @elseif($pindah->status == 'ditolak_iduka')
                                <span class="badge-status badge-danger">Ditolak</span>
                            @elseif($pindah->status == 'menunggu_surat')
                                <span class="badge-status badge-info">Menunggu Surat</span>
                            @elseif($pindah->status == 'siap_kirim')
                                <span class="badge-status badge-primary">Menunggu IDUKA</span>
                            @elseif($pindah->status == 'menunggu_konfirmasi_iduka')
                                <span class="badge-status badge-info">Menunggu Konfirmasi</span>
                            @else
                                <span class="badge-status badge-secondary">{{ $pindah->status }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-icon btn-outline-primary"
                                    style="border-radius:8px;width:32px;height:32px;padding:0;display:inline-flex;align-items:center;justify-content:center;"
                                    onclick="showDetailPindah({{ $pindah }})" title="Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile Card List --}}
        <div class="riwayat-mobile-list">
            @foreach($riwayatPindahPkl as $index => $pindah)
            <div class="riwayat-item">
                <div class="riwayat-item-top">
                    <span class="riwayat-item-num">#{{ $index + 1 }}</span>
                    <div class="flex-grow-1">
                        <div class="riwayat-item-nama">{{ $pindah->idukaLama->nama ?? 'Tidak Diketahui' }}</div>
                        <div class="riwayat-item-nama-sec">Ã¢â€ â€™ {{ $pindah->iduka_baru_nama }}</div>
                    </div>
                </div>
                <div class="riwayat-item-meta">
                    <span class="riwayat-item-date">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                        </svg>
                        {{ \Carbon\Carbon::parse($pindah->created_at)->format('d/m/Y') }}
                    </span>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        @if($pindah->status == 'menunggu')
                            <span class="badge-status badge-warning">Menunggu Verifikasi</span>
                        @elseif($pindah->status == 'diterima_iduka')
                            <span class="badge-status badge-success">Diterima</span>
                        @elseif($pindah->status == 'ditolak_iduka')
                            <span class="badge-status badge-danger">Ditolak</span>
                        @elseif($pindah->status == 'menunggu_surat')
                            <span class="badge-status badge-info">Menunggu Surat</span>
                        @elseif($pindah->status == 'siap_kirim')
                            <span class="badge-status badge-primary">Menunggu IDUKA</span>
                        @elseif($pindah->status == 'menunggu_konfirmasi_iduka')
                            <span class="badge-status badge-info">Menunggu Konfirmasi</span>
                        @else
                            <span class="badge-status badge-secondary">{{ $pindah->status }}</span>
                        @endif
                        <button class="btn-action btn-action-detail"
                                onclick="showDetailPindah({{ $pindah }})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            Detail
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Riwayat Pengajuan Tempat PKL --}}
    @php
        $statusIcons = [
            'verified' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"><path transform="translate(12 13.2) scale(0.9) translate(-12 -12)" d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z" fill="#15803d"/><path transform="translate(12 11.2) scale(0.9) translate(-12 -12)" d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z" fill="#22c55e"/><circle cx="12" cy="11.2" r="5.8" stroke="#86efac" stroke-width="0.9"/><path d="m8.8 11.4 2.1 2.1 4.4-4.4" stroke="#ffffff" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'pending' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="13.1" r="9.2" fill="#b45309"/><circle cx="12" cy="11.1" r="9.2" fill="#f59e0b"/><circle cx="12" cy="11.1" r="7.4" stroke="#fde68a" stroke-width="1"/><path d="M12 7.4v3.9l2.6 1.5" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'rejected' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="13.1" r="9.2" fill="#b91c1c"/><circle cx="12" cy="11.1" r="9.2" fill="#ef4444"/><circle cx="12" cy="11.1" r="7.4" stroke="#fca5a5" stroke-width="1"/><path d="m9.2 8.3 5.6 5.6m0-5.6-5.6 5.6" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round"/></svg>',
            'cancel_pending' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="13.1" r="9.2" fill="#475569"/><circle cx="12" cy="11.1" r="9.2" fill="#64748b"/><circle cx="12" cy="11.1" r="7.4" stroke="#cbd5e1" stroke-width="1"/><path d="M12 7.4v3.9l2.6 1.5" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            'cancelled' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="13.1" r="9.2" fill="#475569"/><circle cx="12" cy="11.1" r="9.2" fill="#64748b"/><circle cx="12" cy="11.1" r="7.4" stroke="#cbd5e1" stroke-width="1"/><path d="M8.5 11.1h7" stroke="#ffffff" stroke-width="2.4" stroke-linecap="round"/></svg>',
        ];

        $daftarPengajuan = collect();
        foreach ($usulanSiswa as $usulan) {
            $badge = match($usulan->status) {
                'proses'   => ['class' => 'ui-badge-warning', 'icon' => 'pending',  'label' => 'Menunggu Verifikasi'],
                'diterima' => ['class' => 'ui-badge-success', 'icon' => 'verified', 'label' => 'Diterima'],
                default    => ['class' => 'ui-badge-danger',  'icon' => 'rejected', 'label' => 'Ditolak'],
            };
            $daftarPengajuan->push([
                'id'      => $usulan->id,
                'nama'    => $usulan->nama,
                'tanggal' => \Carbon\Carbon::parse($usulan->created_at)->format('d/m/Y'),
                'status'  => $usulan->status,
                'badge'   => $badge,
            ]);
        }
        foreach ($usulanPkl as $usul) {
            $badge = match($usul->status) {
                'proses'   => ['class' => 'ui-badge-warning',   'icon' => 'pending',        'label' => 'Menunggu Verifikasi'],
                'diterima' => ['class' => 'ui-badge-success',   'icon' => 'verified',       'label' => 'Usulan Diterima'],
                'menunggu' => ['class' => 'ui-badge-secondary', 'icon' => 'cancel_pending', 'label' => 'Menunggu Pembatalan'],
                'batal'    => ['class' => 'ui-badge-secondary', 'icon' => 'cancelled',      'label' => 'Dibatalkan'],
                default    => ['class' => 'ui-badge-danger',    'icon' => 'rejected',       'label' => 'Ditolak'],
            };
            $daftarPengajuan->push([
                'id'      => $usul->id,
                'nama'    => $usul->iduka->nama ?? '-',
                'tanggal' => \Carbon\Carbon::parse($usul->created_at)->format('d/m/Y'),
                'status'  => $usul->status,
                'badge'   => $badge,
            ]);
        }
    @endphp

    <div class="ui-hist-card">
        <div class="ui-hist-header">
            <div class="ui-hist-header-main">
                <div class="ui-hist-header-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-clipboard-list">
                        <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                        <path d="M12 11h4"/><path d="M12 16h4"/>
                        <path d="M8 11h.01"/><path d="M8 16h.01"/>
                    </svg>
                </div>
                <h6 class="ui-hist-title">Riwayat Pengajuan Tempat PKL</h6>
            </div>
        </div>

        @if ($daftarPengajuan->count() > 0)
            <div class="ui-hist-body">
                <div class="ui-hist-list">
                    @foreach($daftarPengajuan as $idx => $item)
                    <div class="ui-hist-item">
                        <div class="ui-hist-item-main">
                            <span class="ui-hist-num">#{{ $idx + 1 }}</span>
                            <div class="ui-hist-info">
                                <div class="ui-hist-name">{{ $item['nama'] }}</div>
                                <div class="ui-hist-meta">
                                    <span class="ui-hist-date">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                             class="lucide lucide-calendar" aria-hidden="true">
                                            <path d="M8 2v4"/><path d="M16 2v4"/>
                                            <rect width="18" height="18" x="3" y="4" rx="2"/>
                                            <path d="M3 10h18"/>
                                        </svg>
                                        <span>{{ $item['tanggal'] }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="ui-hist-right">
                            <div class="ui-hist-status">
                                <span class="ui-badge {{ $item['badge']['class'] }}">
                                    <span class="ui-badge-icon" aria-hidden="true">{!! $statusIcons[$item['badge']['icon']] !!}</span>
                                    {{ $item['badge']['label'] }}
                                </span>
                            </div>

                            <div class="ui-hist-actions">
                                <a href="{{ route('detail.usulan', $item['id']) }}" class="ui-btn ui-btn-detail" title="Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                         class="lucide lucide-eye" aria-hidden="true">
                                        <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    Detail
                                </a>

                                @if ($item['status'] == 'diterima')
                                    <a href="{{ route('usulan.pdf', $item['id']) }}" class="ui-btn ui-btn-pdf" title="PDF">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                             class="lucide lucide-file-text" aria-hidden="true">
                                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/>
                                            <path d="M14 2v4a2 2 0 0 0 2 2h4"/>
                                            <path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/>
                                        </svg>
                                        PDF
                                    </a>
                                @endif

                                @if ($item['status'] == 'diterima' || $item['status'] == 'proses')
                                    <form action="{{ route('siswa.pengajuan.ajukanPembatalan', $item['id']) }}" method="POST" class="m-0 d-inline-flex"
                                          onsubmit="return confirm('Yakin ingin mengajukan pembatalan?')">
                                        @csrf
                                        <button type="submit" class="ui-btn ui-btn-cancel" title="Batal">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                 class="lucide lucide-circle-x" aria-hidden="true">
                                                <circle cx="12" cy="12" r="10"/>
                                                <path d="m15 9-6 6"/><path d="m9 9 6 6"/>
                                            </svg>
                                            Batal
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="ui-hist-empty">
                <div class="ui-hist-empty-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-inbox">
                        <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/>
                        <path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
                    </svg>
                </div>
                <p class="ui-hist-empty-text">Belum ada riwayat pengajuan tempat PKL.</p>
            </div>
        @endif
    </div>

    {{-- Riwayat Status Penetapan PKL --}}
    <div class="ui-hist-card">
        <div class="ui-hist-header">
            <div class="ui-hist-header-main">
                <div class="ui-hist-header-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-badge-check">
                        <path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/>
                        <path d="m9 12 2 2 4-4"/>
                    </svg>
                </div>
                <h6 class="ui-hist-title">Riwayat Status Penetapan PKL</h6>
            </div>
        </div>

        @if (count($pengajuanSiswa) > 0)
            <div class="ui-hist-body">
                <div class="ui-hist-list">
                    @foreach($pengajuanSiswa as $index => $pengajuan)
                    @php
                        $penetapanBadge = match($pengajuan->status) {
                            'proses'   => ['class' => 'ui-badge-warning', 'icon' => 'pending',  'label' => 'Menunggu Verifikasi'],
                            'diterima' => ['class' => 'ui-badge-success', 'icon' => 'verified', 'label' => 'Diterima'],
                            default    => ['class' => 'ui-badge-danger',  'icon' => 'rejected', 'label' => 'Ditolak'],
                        };
                    @endphp
                    <div class="ui-hist-item">
                        <div class="ui-hist-item-main">
                            <span class="ui-hist-num">#{{ $index + 1 }}</span>
                            <div class="ui-hist-info">
                                <div class="ui-hist-name">{{ $pengajuan->iduka->nama ?? '-' }}</div>
                                <div class="ui-hist-meta">
                                    <span class="ui-hist-date">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                             class="lucide lucide-calendar" aria-hidden="true">
                                            <path d="M8 2v4"/><path d="M16 2v4"/>
                                            <rect width="18" height="18" x="3" y="4" rx="2"/>
                                            <path d="M3 10h18"/>
                                        </svg>
                                        <span>{{ \Carbon\Carbon::parse($pengajuan->created_at)->format('d/m/Y') }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="ui-hist-right">
                            <div class="ui-hist-status">
                                <span class="ui-badge {{ $penetapanBadge['class'] }}">
                                    <span class="ui-badge-icon" aria-hidden="true">{!! $statusIcons[$penetapanBadge['icon']] !!}</span>
                                    {{ $penetapanBadge['label'] }}
                                </span>
                            </div>

                            <div class="ui-hist-actions">
                                <a href="{{ route('pengajuan.detail', $pengajuan->id) }}" class="ui-btn ui-btn-detail" title="Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                         class="lucide lucide-eye" aria-hidden="true">
                                        <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="ui-hist-empty">
                <div class="ui-hist-empty-icon" aria-hidden="true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"
                         class="lucide lucide-inbox">
                        <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/>
                        <path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
                    </svg>
                </div>
                <p class="ui-hist-empty-text">Belum ada riwayat penetapan PKL dari sekolah.</p>
            </div>
        @endif
    </div>

    @endif {{-- end role siswa --}}
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showInfo(title, text) {
        Swal.fire({
            icon: 'info',
            title: title,
            text: text,
            showConfirmButton: true,
            confirmButtonColor: '#2563eb',
            customClass: { popup: 'rounded-4' }
        });
    }

    function confirmAjukanPindah() {
        Swal.fire({
            title: 'Konfirmasi Mutasi',
            text: 'Yakin ingin mengajukan pindah tempat PKL?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#ffffff',
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

        Swal.fire({
            title: '<strong>Detail Pengajuan Mutasi</strong>',
            html: `
                <div class="text-start p-3 bg-light rounded-3 mt-3 shadow-sm border">
                    <div class="mb-2"><strong class="text-muted" style="font-size:12px;">Tempat PKL Lama:</strong><br><span class="fw-semibold text-dark">${pindah.iduka_lama ? pindah.iduka_lama.nama : 'Tidak Diketahui'}</span></div>
                    <div class="mb-2"><strong class="text-muted" style="font-size:12px;">Tempat PKL Baru:</strong><br><span class="fw-bold text-primary">${pindah.iduka_baru_nama}</span></div>
                    <div class="mb-2"><strong class="text-muted" style="font-size:12px;">Tanggal Pengajuan:</strong><br><span class="fw-semibold text-dark">${new Date(pindah.created_at).toLocaleDateString('id-ID')}</span></div>
                    <div class="mb-2"><strong class="text-muted" style="font-size:12px;">Status Mutasi:</strong><br><span style="color:${statusColor};" class="fw-bold"><i class="fas ${statusIcon} me-1"></i>${statusText}</span></div>
                    ${pindah.alasan ? `<div class="mb-2"><strong class="text-muted" style="font-size:12px;">Alasan Mutasi:</strong><br><span class="text-dark">${pindah.alasan}</span></div>` : ''}
                    ${pindah.catatan ? `<div class="mb-2"><strong class="text-muted" style="font-size:12px;">Catatan Validator:</strong><br><span class="text-dark">${pindah.catatan}</span></div>` : ''}
                </div>
            `,
            icon: 'info',
            iconColor: '#3b82f6',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#2563eb',
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
