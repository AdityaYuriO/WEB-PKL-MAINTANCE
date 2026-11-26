@extends('layout.main')

@section('content')
@php
    $isRekomendasi = $iduka ? (bool) ($iduka->rekomendasi ?? false) : false;
    $kuotaRaw = $iduka->kuota_pkl ?? null;
    $hasKuotaNumeric = is_numeric($kuotaRaw);
    $kuotaVal = $hasKuotaNumeric ? (int) $kuotaRaw : null;

    $fotoUrl = null;
    if ($iduka && !empty($iduka->foto)) {
        $fotoNormalized = ltrim(preg_replace('/^public\//', '', (string) $iduka->foto), '/');
        $fotoUrl = asset('storage/' . $fotoNormalized);
    }

    $formatTanggal = function ($value) {
        if (empty($value)) {
            return null;
        }
        try {
            return \Carbon\Carbon::parse($value)->translatedFormat('d M Y');
        } catch (\Throwable $e) {
            return (string) $value;
        }
    };

    $formatJam = function ($value) {
        if (empty($value)) {
            return null;
        }
        try {
            return \Carbon\Carbon::parse($value)->format('H:i');
        } catch (\Throwable $e) {
            return (string) $value;
        }
    };

    $mulaiKerjasamaStr = $iduka ? $formatTanggal($iduka->mulai_kerjasama) : null;
    $akhirKerjasamaStr = $iduka ? $formatTanggal($iduka->akhir_kerjasama) : null;
    $tglAwalStr        = $iduka ? $formatTanggal($iduka->tanggal_awal) : null;
    $tglAkhirStr       = $iduka ? $formatTanggal($iduka->tanggal_akhir) : null;
    $jamMasukStr       = $iduka ? $formatJam($iduka->jam_masuk) : null;
    $jamPulangStr      = $iduka ? $formatJam($iduka->jam_pulang) : null;

    $dashboardUrl = route('siswa.dashboard');
    if (auth()->check() && \Illuminate\Support\Facades\Route::has(auth()->user()->role . '.dashboard')) {
        $dashboardUrl = route(auth()->user()->role . '.dashboard');
    }
@endphp

<style>
#idukaDetailPage {
    --id-bg: #ffffff;
    --id-surface: #f8fafc;
    --id-border: #e2e8f0;
    --id-border-strong: #cbd5e1;
    --id-text: #0f172a;
    --id-text-muted: #64748b;
    --id-text-soft: #94a3b8;
    --id-primary: #2563eb;
    --id-primary-hover: #1d4ed8;
    --id-primary-soft: #eff6ff;
    --id-primary-border: #bfdbfe;
    --id-success-bg: #f0fdf4;
    --id-success-text: #15803d;
    --id-success-border: #bbf7d0;
    --id-warning-bg: #fffbeb;
    --id-warning-text: #b45309;
    --id-warning-border: #fde68a;
    --id-danger-bg: #fef2f2;
    --id-danger-text: #dc2626;
    --id-danger-border: #fecaca;
    --id-neutral-bg: #f1f5f9;
    --id-neutral-text: #475569;
    --id-neutral-border: #cbd5e1;
    --id-radius: 16px;
    --id-radius-md: 12px;
    --id-radius-sm: 10px;
    --id-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    color: var(--id-text);
    padding-bottom: 28px;
    max-width: 100%;
    overflow-x: hidden;
}

#idukaDetailPage *,
#idukaDetailPage *::before,
#idukaDetailPage *::after {
    box-sizing: border-box;
}

#idukaDetailPage .id-shell {
    max-width: 1180px;
    margin: 0 auto;
    min-width: 0;
}

/* ---- Loading Overlay ---- */
#idukaDetailPage #id-loader-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(255, 255, 255, 0.9);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}
#idukaDetailPage .id-spinner {
    width: 40px;
    height: 40px;
    border: 3px solid var(--id-primary-border);
    border-top-color: var(--id-primary);
    border-radius: 50%;
    animation: idSpin 0.7s linear infinite;
}
@keyframes idSpin {
    to { transform: rotate(360deg); }
}
#idukaDetailPage .id-loader-text {
    margin: 12px 0 0;
    font-size: 14px;
    font-weight: 600;
    color: var(--id-primary);
}

/* ---- Alerts ---- */
#idukaDetailPage .id-alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 13px 16px;
    border-radius: 12px;
    font-size: 13.5px;
    line-height: 1.45;
    margin-bottom: 16px;
    border: 1px solid transparent;
    min-width: 0;
}
#idukaDetailPage .id-alert-danger {
    background-color: var(--id-danger-bg);
    color: #991b1b;
    border-color: var(--id-danger-border);
}
#idukaDetailPage .id-alert-success {
    background-color: var(--id-success-bg);
    color: #166534;
    border-color: var(--id-success-border);
}
#idukaDetailPage .id-alert-body {
    flex: 1;
    min-width: 0;
    overflow-wrap: anywhere;
}
#idukaDetailPage .id-alert-close {
    margin-left: auto;
    background: transparent;
    border: none;
    cursor: pointer;
    opacity: 0.65;
    color: inherit;
    padding: 4px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
#idukaDetailPage .id-alert-close:hover {
    opacity: 1;
    background-color: rgba(15, 23, 42, 0.06);
}

/* ---- Breadcrumb ---- */
#idukaDetailPage .id-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 14px;
    font-size: 13px;
    color: var(--id-text-muted);
    min-width: 0;
}
#idukaDetailPage .id-breadcrumb a {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: var(--id-text-muted);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.15s ease;
    min-width: 0;
}
#idukaDetailPage .id-breadcrumb a:hover {
    color: var(--id-primary);
}
#idukaDetailPage .id-bc-sep {
    color: var(--id-text-soft);
    display: inline-flex;
    align-items: center;
    flex-shrink: 0;
}
#idukaDetailPage .id-bc-current {
    color: var(--id-text);
    font-weight: 600;
    overflow-wrap: anywhere;
}

/* ---- Header Card ---- */
#idukaDetailPage .id-header-card {
    background-color: var(--id-bg);
    border: 1px solid var(--id-border);
    border-radius: var(--id-radius);
    box-shadow: var(--id-shadow);
    padding: 22px 24px;
    margin-bottom: 18px;
}
#idukaDetailPage .id-header-inner {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    min-width: 0;
}
#idukaDetailPage .id-header-identity {
    display: flex;
    align-items: flex-start;
    gap: 18px;
    flex: 1 1 auto;
    min-width: 0;
}
#idukaDetailPage .id-company-logo {
    width: 72px;
    height: 72px;
    border-radius: 14px;
    border: 1px solid var(--id-border);
    flex-shrink: 0;
    background-color: var(--id-surface);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
}
#idukaDetailPage .id-company-logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
#idukaDetailPage .id-company-logo-fallback {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    color: var(--id-text-soft);
}
#idukaDetailPage .id-header-info {
    flex: 1;
    min-width: 0;
    max-width: 100%;
}
#idukaDetailPage .id-header-badges {
    display: flex;
    align-items: center;
    gap: 7px;
    flex-wrap: wrap;
    margin-bottom: 8px;
}
#idukaDetailPage .id-company-name {
    font-size: 21px;
    font-weight: 700;
    letter-spacing: -0.02em;
    color: var(--id-text);
    margin: 0 0 4px;
    line-height: 1.25;
    word-break: break-word;
    overflow-wrap: anywhere;
}
#idukaDetailPage .id-company-industry {
    font-size: 14px;
    font-weight: 500;
    color: var(--id-text-muted);
    margin: 0 0 12px;
    line-height: 1.45;
    word-break: break-word;
    overflow-wrap: anywhere;
}
#idukaDetailPage .id-header-meta {
    display: flex;
    align-items: center;
    gap: 8px 16px;
    flex-wrap: wrap;
    min-width: 0;
}
#idukaDetailPage .id-meta-chip {
    display: inline-flex;
    align-items: flex-start;
    gap: 6px;
    font-size: 12.5px;
    color: #475569;
    line-height: 1.4;
    min-width: 0;
    max-width: 100%;
}
#idukaDetailPage .id-meta-chip svg {
    width: 14px;
    height: 14px;
    color: var(--id-text-soft);
    flex-shrink: 0;
    margin-top: 2px;
}
#idukaDetailPage .id-meta-chip span {
    overflow-wrap: anywhere;
    word-break: break-word;
}
#idukaDetailPage .id-header-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
    flex-wrap: wrap;
}
#idukaDetailPage .id-header-actions form {
    display: contents;
}

/* ---- Badges ---- */
#idukaDetailPage .id-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 3px 10px;
    border-radius: 9999px;
    font-size: 11.5px;
    font-weight: 600;
    border: 1px solid transparent;
    white-space: nowrap;
    line-height: 1.35;
    max-width: 100%;
}
#idukaDetailPage .id-badge svg {
    width: 12px;
    height: 12px;
    flex-shrink: 0;
}
#idukaDetailPage .id-badge-success {
    background-color: var(--id-success-bg);
    color: var(--id-success-text);
    border-color: var(--id-success-border);
}
#idukaDetailPage .id-badge-warning {
    background-color: var(--id-warning-bg);
    color: var(--id-warning-text);
    border-color: var(--id-warning-border);
}
#idukaDetailPage .id-badge-danger {
    background-color: var(--id-danger-bg);
    color: var(--id-danger-text);
    border-color: var(--id-danger-border);
}
#idukaDetailPage .id-badge-neutral {
    background-color: var(--id-neutral-bg);
    color: var(--id-neutral-text);
    border-color: var(--id-neutral-border);
}
#idukaDetailPage .id-badge-blue {
    background-color: var(--id-primary-soft);
    color: #1d4ed8;
    border-color: var(--id-primary-border);
}

/* ---- Buttons ---- */
#idukaDetailPage .id-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 38px;
    padding: 8px 16px;
    border-radius: var(--id-radius-sm);
    font-size: 13.5px;
    font-weight: 600;
    line-height: 1.2;
    border: 1px solid transparent;
    cursor: pointer;
    text-decoration: none;
    transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease, transform 0.12s ease;
    white-space: nowrap;
    min-width: 0;
    -webkit-tap-highlight-color: transparent;
}
#idukaDetailPage .id-btn:active:not(:disabled) {
    transform: scale(0.985);
}
#idukaDetailPage .id-btn:disabled {
    opacity: 0.65;
    cursor: not-allowed;
}
#idukaDetailPage .id-btn svg {
    width: 15px;
    height: 15px;
    flex-shrink: 0;
}
#idukaDetailPage .id-btn-primary {
    background-color: var(--id-primary);
    border-color: var(--id-primary);
    color: #ffffff;
}
#idukaDetailPage .id-btn-primary:hover:not(:disabled) {
    background-color: var(--id-primary-hover);
    border-color: var(--id-primary-hover);
    color: #ffffff;
}
#idukaDetailPage .id-btn-outline {
    background-color: #ffffff;
    border-color: var(--id-border-strong);
    color: #334155;
}
#idukaDetailPage .id-btn-outline:hover:not(:disabled) {
    background-color: var(--id-surface);
    border-color: #94a3b8;
    color: var(--id-text);
}
#idukaDetailPage .id-btn-full {
    width: 100%;
}

/* ---- Two Column Layout ---- */
#idukaDetailPage .id-layout {
    display: grid;
    grid-template-columns: minmax(0, 1.58fr) minmax(290px, 1fr);
    gap: 20px;
    align-items: start;
    min-width: 0;
}
#idukaDetailPage .id-main,
#idukaDetailPage .id-sidebar {
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 16px;
}
#idukaDetailPage .id-sidebar {
    position: sticky;
    top: 84px;
}

/* ---- Cards ---- */
#idukaDetailPage .id-card {
    background-color: var(--id-bg);
    border: 1px solid var(--id-border);
    border-radius: var(--id-radius);
    box-shadow: var(--id-shadow);
    overflow: hidden;
    max-width: 100%;
    min-width: 0;
}
#idukaDetailPage .id-card-header {
    padding: 15px 20px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    background-color: #ffffff;
}
#idukaDetailPage .id-card-head-left {
    display: flex;
    align-items: center;
    gap: 11px;
    min-width: 0;
    flex: 1 1 200px;
}
#idukaDetailPage .id-card-header-icon {
    width: 36px;
    height: 36px;
    border-radius: var(--id-radius-sm);
    background-color: var(--id-primary-soft);
    border: 1px solid #dbeafe;
    color: var(--id-primary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
#idukaDetailPage .id-card-header-icon svg {
    width: 17px;
    height: 17px;
}
#idukaDetailPage .id-card-header-icon.is-amber {
    background-color: var(--id-warning-bg);
    border-color: var(--id-warning-border);
    color: #d97706;
}
#idukaDetailPage .id-card-header-icon.is-green {
    background-color: var(--id-success-bg);
    border-color: var(--id-success-border);
    color: #16a34a;
}
#idukaDetailPage .id-card-header-icon.is-slate {
    background-color: #f1f5f9;
    border-color: var(--id-border);
    color: #475569;
}
#idukaDetailPage .id-card-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--id-text);
    margin: 0;
    letter-spacing: -0.015em;
    line-height: 1.3;
}
#idukaDetailPage .id-card-desc {
    font-size: 12.5px;
    color: var(--id-text-muted);
    margin: 2px 0 0;
    line-height: 1.4;
}
#idukaDetailPage .id-card-body {
    padding: 18px 20px;
}

/* ---- KV Grid ---- */
#idukaDetailPage .id-kv-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
}
#idukaDetailPage .id-kv-grid-full {
    grid-template-columns: 1fr;
}
#idukaDetailPage .id-kv-span2 {
    grid-column: 1 / -1;
}
#idukaDetailPage .id-kv-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: 12px 14px;
    background-color: var(--id-surface);
    border-radius: var(--id-radius-sm);
    border: 1px solid var(--id-border);
    min-width: 0;
}
#idukaDetailPage .id-kv-label {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--id-text-muted);
}
#idukaDetailPage .id-kv-label svg {
    width: 12.5px;
    height: 12.5px;
    color: var(--id-text-soft);
    flex-shrink: 0;
}
#idukaDetailPage .id-kv-value {
    font-size: 13.5px;
    font-weight: 600;
    color: var(--id-text);
    line-height: 1.45;
    word-break: break-word;
    overflow-wrap: anywhere;
}
#idukaDetailPage .id-kv-value.is-muted {
    font-weight: 500;
    color: var(--id-text-soft);
}

/* ---- Kuota Stat ---- */
#idukaDetailPage .id-kuota-stat {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 14px 16px;
    background-color: var(--id-surface);
    border-radius: var(--id-radius-md);
    border: 1px solid var(--id-border);
    margin-bottom: 12px;
    flex-wrap: wrap;
}
#idukaDetailPage .id-kuota-left {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
}
#idukaDetailPage .id-kuota-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--id-radius-sm);
    background-color: var(--id-primary-soft);
    border: 1px solid var(--id-primary-border);
    color: var(--id-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
#idukaDetailPage .id-kuota-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--id-text-muted);
    margin-bottom: 2px;
}
#idukaDetailPage .id-kuota-value {
    font-size: 20px;
    font-weight: 700;
    color: var(--id-text);
    line-height: 1.1;
}

/* ---- Period Rows ---- */
#idukaDetailPage .id-period-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 0;
    border-bottom: 1px solid #f1f5f9;
}
#idukaDetailPage .id-period-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}
#idukaDetailPage .id-period-row:first-child {
    padding-top: 0;
}
#idukaDetailPage .id-period-icon {
    color: var(--id-text-soft);
    flex-shrink: 0;
}
#idukaDetailPage .id-period-label {
    font-size: 13px;
    color: var(--id-text-muted);
    min-width: 0;
    flex: 1 1 auto;
}
#idukaDetailPage .id-period-value {
    font-size: 13px;
    font-weight: 600;
    color: var(--id-text);
    margin-left: auto;
    text-align: right;
}

/* ---- Action CTA Card ---- */
#idukaDetailPage .id-action-card {
    background-color: var(--id-primary-soft);
    border: 1px solid var(--id-primary-border);
    border-radius: var(--id-radius);
    padding: 20px;
}
#idukaDetailPage .id-action-card-title {
    font-size: 15px;
    font-weight: 700;
    color: #1e3a8a;
    margin: 0 0 5px;
}
#idukaDetailPage .id-action-card-desc {
    font-size: 13px;
    color: #1e40af;
    margin: 0 0 15px;
    line-height: 1.5;
}

/* ---- Empty / 404 State ---- */
#idukaDetailPage .id-empty {
    padding: 56px 20px;
    text-align: center;
    color: var(--id-text-muted);
}
#idukaDetailPage .id-empty-icon {
    width: 52px;
    height: 52px;
    border-radius: 9999px;
    background-color: #f1f5f9;
    border: 1px solid var(--id-border);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 14px;
    color: var(--id-text-soft);
}
#idukaDetailPage .id-empty h3 {
    font-size: 16px;
    font-weight: 700;
    color: var(--id-text);
    margin: 0 0 6px;
}
#idukaDetailPage .id-empty p {
    font-size: 13.5px;
    color: var(--id-text-muted);
    margin: 0 auto 20px;
    max-width: 360px;
    line-height: 1.5;
}

/* ---- Responsive Breakpoints ---- */
/* Laptop: 1024pxâ€“1439px */
@media (max-width: 1439.98px) and (min-width: 1024px) {
    #idukaDetailPage .id-layout {
        grid-template-columns: minmax(0, 1.5fr) minmax(275px, 1fr);
        gap: 18px;
    }
}

/* Tablet: 768pxâ€“1023px */
@media (max-width: 1023.98px) {
    #idukaDetailPage .id-layout {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    #idukaDetailPage .id-sidebar {
        position: static;
        top: auto;
    }
}

/* Mobile landscape & Large Smartphone: 431pxâ€“767px */
@media (max-width: 767.98px) {
    #idukaDetailPage .id-header-card {
        padding: 18px;
        border-radius: 14px;
    }
    #idukaDetailPage .id-header-inner {
        flex-direction: column;
        align-items: stretch;
        gap: 16px;
    }
    #idukaDetailPage .id-header-identity {
        gap: 14px;
    }
    #idukaDetailPage .id-company-logo {
        width: 60px;
        height: 60px;
        border-radius: 12px;
    }
    #idukaDetailPage .id-company-name {
        font-size: 18.5px;
    }
    #idukaDetailPage .id-header-actions {
        width: 100%;
    }
    #idukaDetailPage .id-header-actions .id-btn {
        flex: 1 1 0;
        min-height: 42px;
    }
    #idukaDetailPage .id-card {
        border-radius: 14px;
    }
    #idukaDetailPage .id-card-header {
        padding: 14px 16px;
    }
    #idukaDetailPage .id-card-body {
        padding: 16px;
    }
    #idukaDetailPage .id-kv-grid {
        grid-template-columns: 1fr;
        gap: 10px;
    }
    #idukaDetailPage .id-action-card {
        padding: 16px;
        border-radius: 14px;
    }
}

/* Standard Smartphone: 375pxâ€“430px */
@media (max-width: 430.98px) {
    #idukaDetailPage .id-header-card {
        padding: 16px 15px;
    }
    #idukaDetailPage .id-company-name {
        font-size: 17.5px;
    }
    #idukaDetailPage .id-company-industry {
        font-size: 13px;
        margin-bottom: 10px;
    }
    #idukaDetailPage .id-header-actions {
        flex-direction: column-reverse;
        gap: 8px;
    }
    #idukaDetailPage .id-header-actions .id-btn {
        width: 100%;
        flex: none;
    }
    #idukaDetailPage .id-card-header {
        padding: 13px 15px;
    }
    #idukaDetailPage .id-card-body {
        padding: 14px 15px;
    }
    #idukaDetailPage .id-kv-item {
        padding: 10px 12px;
    }
}

/* Small Smartphone: 320pxâ€“374px */
@media (max-width: 374.98px) {
    #idukaDetailPage .id-header-card {
        padding: 14px 13px;
    }
    #idukaDetailPage .id-header-identity {
        flex-direction: column;
        gap: 12px;
    }
    #idukaDetailPage .id-company-logo {
        width: 56px;
        height: 56px;
    }
    #idukaDetailPage .id-company-name {
        font-size: 16px;
    }
    #idukaDetailPage .id-card-header {
        padding: 12px 13px;
    }
    #idukaDetailPage .id-card-body {
        padding: 13px;
    }
    #idukaDetailPage .id-card-header-icon {
        width: 32px;
        height: 32px;
    }
    #idukaDetailPage .id-card-title {
        font-size: 14px;
    }
    #idukaDetailPage .id-kv-item {
        padding: 9px 11px;
    }
    #idukaDetailPage .id-kuota-value {
        font-size: 18px;
    }
    #idukaDetailPage .id-action-card {
        padding: 14px;
    }
}
</style>

<div id="idukaDetailPage" class="container-xxl flex-grow-1 container-p-y">
    {{-- Loading Overlay --}}
    <div id="id-loader-overlay" role="status" aria-live="polite" aria-label="Memproses pengajuan">
        <div class="id-spinner"></div>
        <p class="id-loader-text">Memproses...</p>
    </div>

    <div class="id-shell">

        {{-- Alerts --}}
        @if (session('error'))
            <div class="id-alert id-alert-danger" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="flex-shrink:0;margin-top:2px;">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <div class="id-alert-body">{{ session('error') }}</div>
                <button type="button" class="id-alert-close" onclick="this.closest('.id-alert').remove()" aria-label="Tutup peringatan">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        @if (session('success'))
            <div class="id-alert id-alert-success" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="flex-shrink:0;margin-top:2px;">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                <div class="id-alert-body">{{ session('success') }}</div>
                <button type="button" class="id-alert-close" onclick="this.closest('.id-alert').remove()" aria-label="Tutup notifikasi">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- Breadcrumb --}}
        <nav class="id-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ $dashboardUrl }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" aria-hidden="true">
                    <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                    <path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                </svg>
                <span>Dashboard</span>
            </a>
            <span class="id-bc-sep" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </span>
            <a href="{{ route('iduka.usulan') }}">Usulan IDUKA</a>
            <span class="id-bc-sep" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </span>
            <span class="id-bc-current">Detail IDUKA</span>
        </nav>

        @if (!$iduka)
            {{-- Null / Not Found State --}}
            <div class="id-card">
                <div class="id-empty">
                    <div class="id-empty-icon" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/>
                            <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/>
                            <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/>
                        </svg>
                    </div>
                    <h3>Institusi Tidak Ditemukan</h3>
                    <p>Data institusi atau perusahaan yang Anda cari tidak tersedia atau sudah tidak aktif.</p>
                    <a href="{{ route('iduka.usulan') }}" class="id-btn id-btn-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>
                        </svg>
                        <span>Kembali ke Daftar</span>
                    </a>
                </div>
            </div>
        @else
            {{-- Header Profil IDUKA --}}
            <header class="id-header-card">
                <div class="id-header-inner">
                    <div class="id-header-identity">
                        {{-- Logo / Foto atau Placeholder Ikon --}}
                        <div class="id-company-logo">
                            @if ($fotoUrl)
                                <img src="{{ $fotoUrl }}"
                                     alt="Logo {{ $iduka->nama }}"
                                     onerror="this.style.display='none'; if (this.nextElementSibling) { this.nextElementSibling.style.display='flex'; }">
                                <span class="id-company-logo-fallback" style="display:none;" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/>
                                        <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/>
                                        <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/>
                                        <path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/>
                                    </svg>
                                </span>
                            @else
                                <span class="id-company-logo-fallback" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/>
                                        <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/>
                                        <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/>
                                        <path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/>
                                    </svg>
                                </span>
                            @endif
                        </div>

                        <div class="id-header-info">
                            {{-- Status & Kategori Badges --}}
                            <div class="id-header-badges">
                                @if ($isRekomendasi)
                                    <span class="id-badge id-badge-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/>
                                        </svg>
                                        <span>Rekomendasi</span>
                                    </span>
                                @else
                                    <span class="id-badge id-badge-blue">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/>
                                        </svg>
                                        <span>Ajuan</span>
                                    </span>
                                @endif

                                @if (!empty($iduka->kerjasama))
                                    <span class="id-badge id-badge-neutral">
                                        <span>{{ $iduka->kerjasama }}</span>
                                    </span>
                                @endif

                                @if ($iduka->is_pusat)
                                    <span class="id-badge id-badge-warning">Pusat</span>
                                @endif
                            </div>

                            <h1 class="id-company-name">{{ $iduka->nama }}</h1>

                            @if (!empty($iduka->bidang_industri))
                                <p class="id-company-industry">{{ $iduka->bidang_industri }}</p>
                            @endif

                            {{-- Alamat & Kontak Singkat --}}
                            @if (!empty($iduka->alamat) || !empty($iduka->telepon) || !empty($iduka->email))
                                <div class="id-header-meta">
                                    @if (!empty($iduka->alamat))
                                        <span class="id-meta-chip">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/>
                                                <circle cx="12" cy="10" r="3"/>
                                            </svg>
                                            <span>{{ \Illuminate\Support\Str::limit($iduka->alamat, 90) }}</span>
                                        </span>
                                    @endif

                                    @if (!empty($iduka->telepon))
                                        <span class="id-meta-chip">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                            </svg>
                                            <span>{{ $iduka->telepon }}</span>
                                        </span>
                                    @endif

                                    @if (!empty($iduka->email))
                                        <span class="id-meta-chip">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                                            </svg>
                                            <span>{{ $iduka->email }}</span>
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="id-header-actions">
                        <a href="{{ route('iduka.usulan') }}" class="id-btn id-btn-outline">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>
                            </svg>
                            <span>Kembali</span>
                        </a>

                        @if (auth()->check() && auth()->user()->role == 'siswa')
                            <form action="{{ route('usulan.iduka.storeAjukanPkl', $iduka->id) }}" method="POST" class="ajukan-form">
                                @csrf
                                <input type="hidden" name="nama"            value="{{ $iduka->nama }}">
                                <input type="hidden" name="alamat"          value="{{ $iduka->alamat }}">
                                <input type="hidden" name="kode_pos"        value="{{ $iduka->kode_pos }}">
                                <input type="hidden" name="telepon"         value="{{ $iduka->telepon }}">
                                <input type="hidden" name="email"           value="{{ $iduka->email }}">
                                <input type="hidden" name="bidang_industri" value="{{ $iduka->bidang_industri }}">
                                <input type="hidden" name="kerjasama"       value="{{ $iduka->kerjasama }}">
                                <input type="hidden" name="kuota_pkl"       value="{{ $iduka->kuota_pkl }}">
                                <button type="button" class="id-btn id-btn-primary ajukan-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/>
                                    </svg>
                                    <span>Ajukan PKL</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </header>

            {{-- Two-Column Content --}}
            <div class="id-layout">

                {{-- Left Column: Informasi Umum & Informasi Kontak --}}
                <div class="id-main">

                    {{-- A. Informasi Umum --}}
                    <section class="id-card">
                        <div class="id-card-header">
                            <div class="id-card-head-left">
                                <div class="id-card-header-icon" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/>
                                        <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/>
                                        <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/>
                                        <path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="id-card-title">Informasi Umum</h2>
                                    <p class="id-card-desc">Identitas, bidang usaha, dan alamat institusi</p>
                                </div>
                            </div>
                        </div>

                        <div class="id-card-body">
                            <div class="id-kv-grid">
                                <div class="id-kv-item id-kv-span2">
                                    <span class="id-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/></svg>
                                        <span>Nama Institusi / Perusahaan</span>
                                    </span>
                                    <span class="id-kv-value {{ empty($iduka->nama) ? 'is-muted' : '' }}">
                                        {{ $iduka->nama ?: '-' }}
                                    </span>
                                </div>

                                <div class="id-kv-item">
                                    <span class="id-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="14" x="2" y="7" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                                        <span>Bidang Industri</span>
                                    </span>
                                    <span class="id-kv-value {{ empty($iduka->bidang_industri) ? 'is-muted' : '' }}">
                                        {{ $iduka->bidang_industri ?: '-' }}
                                    </span>
                                </div>

                                <div class="id-kv-item">
                                    <span class="id-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m11 17 2 2a1 1 0 1 0 3-3"/><path d="m14 14 2.5 2.5a1 1 0 1 0 3-3l-3.88-3.88a3 3 0 0 0-4.24 0l-.88.88a1 1 0 1 1-3-3l2.81-2.81a5.79 5.79 0 0 1 7.06-.87l.47.28a2 2 0 0 0 1.42.25L21 4"/><path d="m21 3 1 11h-2"/><path d="M3 3 2 14l6.5 6.5a1 1 0 1 0 3-3"/><path d="M3 4h8"/></svg>
                                        <span>Kerjasama</span>
                                    </span>
                                    <span class="id-kv-value {{ empty($iduka->kerjasama) ? 'is-muted' : '' }}">
                                        {{ $iduka->kerjasama ?: '-' }}
                                    </span>
                                </div>

                                @if (!empty($iduka->kerjasama_lainnya))
                                    <div class="id-kv-item id-kv-span2">
                                        <span class="id-kv-label">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                                            <span>Keterangan Kerja Sama</span>
                                        </span>
                                        <span class="id-kv-value">{{ $iduka->kerjasama_lainnya }}</span>
                                    </div>
                                @endif

                                <div class="id-kv-item id-kv-span2">
                                    <span class="id-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                                        <span>Alamat Lengkap</span>
                                    </span>
                                    <span class="id-kv-value {{ empty($iduka->alamat) ? 'is-muted' : '' }}">
                                        {{ $iduka->alamat ?: '-' }}
                                    </span>
                                </div>

                                <div class="id-kv-item">
                                    <span class="id-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="4" x2="20" y1="9" y2="9"/><line x1="4" x2="20" y1="15" y2="15"/><line x1="10" x2="8" y1="3" y2="21"/><line x1="16" x2="14" y1="3" y2="21"/></svg>
                                        <span>Kode Pos</span>
                                    </span>
                                    <span class="id-kv-value {{ empty($iduka->kode_pos) ? 'is-muted' : '' }}">
                                        {{ $iduka->kode_pos ?: '-' }}
                                    </span>
                                </div>

                                @if ($mulaiKerjasamaStr || $akhirKerjasamaStr)
                                    <div class="id-kv-item">
                                        <span class="id-kv-label">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                                            <span>Periode Kerja Sama</span>
                                        </span>
                                        <span class="id-kv-value">
                                            {{ $mulaiKerjasamaStr ?? '-' }} &ndash; {{ $akhirKerjasamaStr ?? '-' }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>

                    {{-- B. Informasi Kontak & Pimpinan --}}
                    <section class="id-card">
                        <div class="id-card-header">
                            <div class="id-card-head-left">
                                <div class="id-card-header-icon is-amber" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="id-card-title">Informasi Kontak &amp; Pimpinan</h2>
                                    <p class="id-card-desc">Penanggung jawab serta kontak resmi perusahaan</p>
                                </div>
                            </div>
                        </div>

                        <div class="id-card-body">
                            <div class="id-kv-grid">
                                <div class="id-kv-item">
                                    <span class="id-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        <span>Nama Pimpinan</span>
                                    </span>
                                    <span class="id-kv-value {{ empty($iduka->nama_pimpinan) ? 'is-muted' : '' }}">
                                        {{ $iduka->nama_pimpinan ?: '-' }}
                                    </span>
                                </div>

                                <div class="id-kv-item">
                                    <span class="id-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="14" x="2" y="7" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                                        <span>Jabatan</span>
                                    </span>
                                    <span class="id-kv-value {{ empty($iduka->jabatan) ? 'is-muted' : '' }}">
                                        {{ $iduka->jabatan ?: '-' }}
                                    </span>
                                </div>

                                <div class="id-kv-item">
                                    <span class="id-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="14" x="3" y="5" rx="2"/><line x1="7" x2="11" y1="10" y2="10"/><line x1="7" x2="17" y1="14" y2="14"/></svg>
                                        <span>NIP / NIK Pimpinan</span>
                                    </span>
                                    <span class="id-kv-value {{ empty($iduka->nip_pimpinan) ? 'is-muted' : '' }}">
                                        {{ $iduka->nip_pimpinan ?: '-' }}
                                    </span>
                                </div>

                                <div class="id-kv-item">
                                    <span class="id-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                        <span>No. HP Pimpinan</span>
                                    </span>
                                    <span class="id-kv-value {{ empty($iduka->no_hp_pimpinan) ? 'is-muted' : '' }}">
                                        {{ $iduka->no_hp_pimpinan ?: '-' }}
                                    </span>
                                </div>

                                <div class="id-kv-item">
                                    <span class="id-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                        <span>Nomor Telepon Kantor</span>
                                    </span>
                                    <span class="id-kv-value {{ empty($iduka->telepon) ? 'is-muted' : '' }}">
                                        {{ $iduka->telepon ?: '-' }}
                                    </span>
                                </div>

                                <div class="id-kv-item">
                                    <span class="id-kv-label">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                        <span>Email</span>
                                    </span>
                                    <span class="id-kv-value {{ empty($iduka->email) ? 'is-muted' : '' }}">
                                        {{ $iduka->email ?: '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                {{-- Right Column: Informasi Kerja Sama PKL, Jadwal & Aksi --}}
                <aside class="id-sidebar">

                    {{-- C. Informasi Kerja Sama --}}
                    <section class="id-card">
                        <div class="id-card-header">
                            <div class="id-card-head-left">
                                <div class="id-card-header-icon is-green" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                        <circle cx="9" cy="7" r="4"/>
                                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="id-card-title">Kerja Sama PKL</h2>
                                    <p class="id-card-desc">Kuota dan status penempatan siswa</p>
                                </div>
                            </div>
                        </div>

                        <div class="id-card-body">
                            <div class="id-kuota-stat">
                                <div class="id-kuota-left">
                                    <div class="id-kuota-icon" aria-hidden="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="id-kuota-label">Jumlah Kuota PKL</div>
                                        <div class="id-kuota-value">{{ $hasKuotaNumeric ? $kuotaVal : '-' }}</div>
                                    </div>
                                </div>

                                @if ($hasKuotaNumeric)
                                    @if ($kuotaVal <= 0)
                                        <span class="id-badge id-badge-warning">Kuota Penuh</span>
                                    @else
                                        <span class="id-badge id-badge-success">Tersedia</span>
                                    @endif
                                @endif
                            </div>

                            <div class="id-kv-grid id-kv-grid-full" style="gap: 10px;">
                                <div class="id-kv-item">
                                    <span class="id-kv-label">Kategori IDUKA</span>
                                    <span class="id-kv-value">
                                        @if ($isRekomendasi)
                                            <span style="color: var(--id-success-text);">Rekomendasi</span>
                                        @else
                                            <span style="color: var(--id-primary);">Ajuan</span>
                                        @endif
                                    </span>
                                </div>

                                <div class="id-kv-item">
                                    <span class="id-kv-label">Jenis Kerja Sama</span>
                                    <span class="id-kv-value {{ empty($iduka->kerjasama) ? 'is-muted' : '' }}">
                                        {{ $iduka->kerjasama ?: '-' }}
                                    </span>
                                </div>

                                @if ($tglAwalStr || $tglAkhirStr)
                                    <div class="id-kv-item">
                                        <span class="id-kv-label">Periode Penerimaan PKL</span>
                                        <span class="id-kv-value">
                                            {{ $tglAwalStr ?? '-' }} &ndash; {{ $tglAkhirStr ?? '-' }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </section>

                    {{-- Jadwal Operasional (hanya tampil jika tersedia) --}}
                    @if ($jamMasukStr || $jamPulangStr || !empty($iduka->radius))
                        <section class="id-card">
                            <div class="id-card-header">
                                <div class="id-card-head-left">
                                    <div class="id-card-header-icon is-slate" aria-hidden="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="id-card-title">Jadwal &amp; Presensi</h2>
                                        <p class="id-card-desc">Jam operasional dan area presensi</p>
                                    </div>
                                </div>
                            </div>

                            <div class="id-card-body" style="padding-top: 14px; padding-bottom: 14px;">
                                @if ($jamMasukStr)
                                    <div class="id-period-row">
                                        <svg class="id-period-icon" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        <span class="id-period-label">Jam Masuk</span>
                                        <span class="id-period-value">{{ $jamMasukStr }} WIB</span>
                                    </div>
                                @endif

                                @if ($jamPulangStr)
                                    <div class="id-period-row">
                                        <svg class="id-period-icon" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 8 14"/></svg>
                                        <span class="id-period-label">Jam Pulang</span>
                                        <span class="id-period-value">{{ $jamPulangStr }} WIB</span>
                                    </div>
                                @endif

                                @if (!empty($iduka->radius))
                                    <div class="id-period-row">
                                        <svg class="id-period-icon" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="M12 2v4m0 12v4M2 12h4m12 0h4"/></svg>
                                        <span class="id-period-label">Radius Presensi</span>
                                        <span class="id-period-value">{{ $iduka->radius }} m</span>
                                    </div>
                                @endif
                            </div>
                        </section>
                    @endif

                    {{-- CTA Pengajuan PKL (khusus siswa) --}}
                    @if (auth()->check() && auth()->user()->role == 'siswa')
                        <div class="id-action-card">
                            <p class="id-action-card-title">Ajukan PKL di Institusi Ini</p>
                            <p class="id-action-card-desc">
                                Pastikan data pribadi Anda sudah lengkap sebelum mengajukan permohonan PKL ke institusi ini.
                            </p>
                            <form action="{{ route('usulan.iduka.storeAjukanPkl', $iduka->id) }}" method="POST" class="ajukan-form">
                                @csrf
                                <input type="hidden" name="nama"            value="{{ $iduka->nama }}">
                                <input type="hidden" name="alamat"          value="{{ $iduka->alamat }}">
                                <input type="hidden" name="kode_pos"        value="{{ $iduka->kode_pos }}">
                                <input type="hidden" name="telepon"         value="{{ $iduka->telepon }}">
                                <input type="hidden" name="email"           value="{{ $iduka->email }}">
                                <input type="hidden" name="bidang_industri" value="{{ $iduka->bidang_industri }}">
                                <input type="hidden" name="kerjasama"       value="{{ $iduka->kerjasama }}">
                                <input type="hidden" name="kuota_pkl"       value="{{ $iduka->kuota_pkl }}">
                                <button type="button" class="id-btn id-btn-primary id-btn-full ajukan-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/>
                                    </svg>
                                    <span>Ajukan PKL</span>
                                </button>
                            </form>
                        </div>
                    @endif

                </aside>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    let isSubmitting = false;
    const allSubmitButtons = document.querySelectorAll('.ajukan-btn');

    function setButtonsBusy(busy) {
        isSubmitting = busy;
        allSubmitButtons.forEach(function (btn) {
            btn.disabled = busy;
        });
    }

    allSubmitButtons.forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.preventDefault();
            if (isSubmitting) return;

            setButtonsBusy(true);

            fetch('/api/check-data-pribadi')
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    if (!data.complete) {
                        setButtonsBusy(false);
                        Swal.fire({
                            icon: 'error',
                            title: 'Data Belum Lengkap',
                            text: 'Silakan lengkapi data pribadi terlebih dahulu sebelum mengajukan PKL.',
                            confirmButtonColor: '#2563eb',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Apakah kamu yakin?',
                        text: 'Ingin mengajukan Institusi ini?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#2563eb',
                        cancelButtonColor: '#ffffff',
                        cancelButtonText: 'Batal',
                        confirmButtonText: 'Ya, Ajukan!'
                    }).then(function (result) {
                        if (!result.isConfirmed) {
                            setButtonsBusy(false);
                            return;
                        }

                        const overlay = document.getElementById('id-loader-overlay');
                        if (overlay) overlay.style.display = 'flex';

                        const form = button.closest('.ajukan-form');
                        const formData = new FormData(form);
                        const csrfMeta = document.querySelector('meta[name="csrf-token"]');

                        fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': csrfMeta ? csrfMeta.content : ''
                            }
                        })
                        .then(function (response) {
                            if (overlay) overlay.style.display = 'none';

                            const contentType = response.headers.get('content-type') || '';

                            if (!response.ok) {
                                if (contentType.includes('application/json')) {
                                    return response.json().then(function (err) {
                                        throw new Error(err.message || 'Terjadi kesalahan');
                                    });
                                }
                                throw new Error('Terjadi kesalahan saat memproses pengajuan.');
                            }

                            if (contentType.includes('application/json')) {
                                return response.json();
                            }

                            return {
                                success: true,
                                message: 'Usulan PKL berhasil diajukan!',
                                redirect: response.url || @json(route('siswa.dashboard'))
                            };
                        })
                        .then(function (res) {
                            if (res && res.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: res.message || 'Usulan PKL berhasil diajukan!',
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(function () {
                                    window.location.href = res.redirect || @json(route('siswa.dashboard'));
                                });
                            } else {
                                setButtonsBusy(false);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: (res && res.message) ? res.message : 'Gagal mengajukan PKL.',
                                    confirmButtonColor: '#2563eb',
                                    confirmButtonText: 'OK'
                                });
                            }
                        })
                        .catch(function (error) {
                            if (overlay) overlay.style.display = 'none';
                            setButtonsBusy(false);
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: error.message || 'Gagal mengajukan PKL. Silakan coba lagi.',
                                confirmButtonColor: '#2563eb',
                                confirmButtonText: 'OK'
                            });
                        });
                    });
                })
                .catch(function (error) {
                    setButtonsBusy(false);
                    console.error('Error checking data:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal memeriksa kelengkapan data. Silakan coba lagi.',
                        confirmButtonColor: '#2563eb',
                        confirmButtonText: 'OK'
                    });
                });
        });
    });
})();
</script>
@endpush
