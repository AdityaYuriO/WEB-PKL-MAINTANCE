@extends('layout.main')

@section('content')
@php
    $usulanList = isset($usulanSiswa) ? $usulanSiswa : collect();
    $activeUsulanItem = $usulanList->whereIn('status', ['proses', 'diterima'])->first();
    $hasActiveUsulan = !is_null($activeUsulanItem);

    $kerjasamaCol1 = ['Sinkronisasi', 'Guru Tamu', 'Magang / Pelatihan', 'PKL', 'Sertifikasi'];
    $kerjasamaCol2 = ['Tefa', 'Serapan', 'Beasiswa', 'PBL', 'Lainnya'];
    $selectedKerjasama = old('kerjasama', '');
@endphp

<style>
#usulanFormPage {
    --uf-bg: #ffffff;
    --uf-surface: #f8fafc;
    --uf-border: #e2e8f0;
    --uf-border-strong: #cbd5e1;
    --uf-text: #0f172a;
    --uf-text-muted: #64748b;
    --uf-text-soft: #94a3b8;
    --uf-primary: #2563eb;
    --uf-primary-hover: #1d4ed8;
    --uf-primary-soft: #eff6ff;
    --uf-primary-border: #bfdbfe;
    --uf-danger: #dc2626;
    --uf-danger-bg: #fef2f2;
    --uf-danger-border: #fecaca;
    --uf-success: #15803d;
    --uf-success-bg: #f0fdf4;
    --uf-success-border: #bbf7d0;
    --uf-warning: #b45309;
    --uf-warning-bg: #fffbeb;
    --uf-warning-border: #fde68a;
    --uf-radius: 16px;
    --uf-radius-md: 12px;
    --uf-radius-sm: 10px;
    --uf-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    color: var(--uf-text);
    padding-bottom: 28px;
    max-width: 100%;
    overflow-x: hidden;
}

#usulanFormPage *,
#usulanFormPage *::before,
#usulanFormPage *::after {
    box-sizing: border-box;
}

#usulanFormPage .uf-shell {
    max-width: 1180px;
    margin: 0 auto;
    min-width: 0;
}

/* ---- Breadcrumb ---- */
#usulanFormPage .uf-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 14px;
    font-size: 13px;
    color: var(--uf-text-muted);
    min-width: 0;
}
#usulanFormPage .uf-breadcrumb a {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: var(--uf-text-muted);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.15s ease;
    min-width: 0;
}
#usulanFormPage .uf-breadcrumb a:hover {
    color: var(--uf-primary);
}
#usulanFormPage .uf-bc-sep {
    color: #94a3b8;
    display: inline-flex;
    align-items: center;
    flex-shrink: 0;
}
#usulanFormPage .uf-bc-current {
    color: var(--uf-text);
    font-weight: 600;
    overflow-wrap: anywhere;
}

/* ---- Alerts ---- */
#usulanFormPage .uf-alert {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    padding: 13px 16px;
    border-radius: 12px;
    border: 1px solid transparent;
    margin-bottom: 16px;
    font-size: 13.5px;
    line-height: 1.45;
    min-width: 0;
}
#usulanFormPage .uf-alert-danger {
    background-color: var(--uf-danger-bg);
    border-color: var(--uf-danger-border);
    color: #991b1b;
}
#usulanFormPage .uf-alert-success {
    background-color: var(--uf-success-bg);
    border-color: var(--uf-success-border);
    color: #166534;
}
#usulanFormPage .uf-alert-warning {
    background-color: var(--uf-warning-bg);
    border-color: var(--uf-warning-border);
    color: #92400e;
}
#usulanFormPage .uf-alert-icon {
    flex-shrink: 0;
    margin-top: 2px;
}
#usulanFormPage .uf-alert-body {
    flex: 1;
    min-width: 0;
    overflow-wrap: anywhere;
    word-break: break-word;
}
#usulanFormPage .uf-alert-title {
    margin: 0 0 3px;
    font-size: 13.5px;
    font-weight: 700;
}
#usulanFormPage .uf-alert-list {
    margin: 6px 0 0;
    padding-left: 18px;
}
#usulanFormPage .uf-alert-list li {
    margin-bottom: 2px;
}
#usulanFormPage .uf-alert-close {
    margin-left: auto;
    background: transparent;
    border: none;
    color: inherit;
    opacity: 0.65;
    cursor: pointer;
    padding: 4px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
#usulanFormPage .uf-alert-close:hover {
    opacity: 1;
    background-color: rgba(15, 23, 42, 0.06);
}
#usulanFormPage .uf-alert-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    margin-top: 8px;
    font-size: 12.5px;
    font-weight: 600;
    color: #92400e;
    text-decoration: underline;
    text-underline-offset: 3px;
}
#usulanFormPage .uf-alert-link:hover {
    color: #78350f;
}

/* ---- Page Header Card ---- */
#usulanFormPage .uf-header-card {
    background-color: var(--uf-bg);
    border: 1px solid var(--uf-border);
    border-radius: var(--uf-radius);
    padding: 20px 24px;
    box-shadow: var(--uf-shadow);
    margin-bottom: 18px;
}
#usulanFormPage .uf-header-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}
#usulanFormPage .uf-header-main {
    flex: 1 1 340px;
    min-width: 0;
}
#usulanFormPage .uf-title-line {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 5px;
}
#usulanFormPage .uf-title {
    margin: 0;
    font-size: 21px;
    font-weight: 700;
    letter-spacing: -0.02em;
    color: var(--uf-text);
    line-height: 1.25;
}
#usulanFormPage .uf-subtitle {
    margin: 0;
    font-size: 13.5px;
    color: var(--uf-text-muted);
    line-height: 1.5;
    overflow-wrap: anywhere;
}
#usulanFormPage .uf-header-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    flex-shrink: 0;
}

/* ---- Badges ---- */
#usulanFormPage .uf-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 9999px;
    border: 1px solid transparent;
    white-space: nowrap;
    line-height: 1.35;
    max-width: 100%;
}
#usulanFormPage .uf-badge svg {
    width: 12.5px;
    height: 12.5px;
    flex-shrink: 0;
}
#usulanFormPage .uf-badge-blue {
    background-color: var(--uf-primary-soft);
    border-color: var(--uf-primary-border);
    color: #1d4ed8;
}
#usulanFormPage .uf-badge-warning {
    background-color: var(--uf-warning-bg);
    border-color: var(--uf-warning-border);
    color: var(--uf-warning);
}
#usulanFormPage .uf-badge-success {
    background-color: var(--uf-success-bg);
    border-color: var(--uf-success-border);
    color: var(--uf-success);
}
#usulanFormPage .uf-badge-danger {
    background-color: var(--uf-danger-bg);
    border-color: var(--uf-danger-border);
    color: var(--uf-danger);
}
#usulanFormPage .uf-badge-neutral {
    background-color: #f1f5f9;
    border-color: #e2e8f0;
    color: #475569;
}

/* ---- Buttons ---- */
#usulanFormPage .uf-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    min-height: 38px;
    padding: 8px 16px;
    border-radius: 10px;
    font-size: 13.5px;
    font-weight: 600;
    line-height: 1.2;
    border: 1px solid transparent;
    cursor: pointer;
    text-decoration: none;
    white-space: nowrap;
    transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease, transform 0.12s ease;
    -webkit-tap-highlight-color: transparent;
    min-width: 0;
}
#usulanFormPage .uf-btn:active:not(:disabled) {
    transform: scale(0.985);
}
#usulanFormPage .uf-btn:disabled {
    opacity: 0.65;
    cursor: not-allowed;
}
#usulanFormPage .uf-btn svg {
    width: 15px;
    height: 15px;
    flex-shrink: 0;
}
#usulanFormPage .uf-btn-sm {
    min-height: 32px;
    padding: 6px 12px;
    font-size: 12.5px;
    border-radius: 8px;
}
#usulanFormPage .uf-btn-primary {
    background-color: var(--uf-primary);
    border-color: var(--uf-primary);
    color: #ffffff;
}
#usulanFormPage .uf-btn-primary:hover:not(:disabled) {
    background-color: var(--uf-primary-hover);
    border-color: var(--uf-primary-hover);
    color: #ffffff;
}
#usulanFormPage .uf-btn-outline {
    background-color: #ffffff;
    border-color: var(--uf-border-strong);
    color: #334155;
}
#usulanFormPage .uf-btn-outline:hover:not(:disabled) {
    background-color: var(--uf-surface);
    border-color: #94a3b8;
    color: var(--uf-text);
}
#usulanFormPage .uf-btn-spinner {
    display: none;
    width: 15px;
    height: 15px;
    border: 2px solid rgba(255, 255, 255, 0.35);
    border-top-color: #ffffff;
    border-radius: 50%;
    animation: ufSpin 0.65s linear infinite;
    flex-shrink: 0;
}
#usulanFormPage .uf-btn.is-loading .uf-btn-icon {
    display: none;
}
#usulanFormPage .uf-btn.is-loading .uf-btn-spinner {
    display: inline-block;
}
@keyframes ufSpin {
    to { transform: rotate(360deg); }
}

/* ---- Two Column Main Layout ---- */
#usulanFormPage .uf-layout {
    display: grid;
    grid-template-columns: minmax(0, 1.58fr) minmax(290px, 1fr);
    gap: 20px;
    align-items: start;
    min-width: 0;
}
#usulanFormPage .uf-main-col,
#usulanFormPage .uf-sidebar {
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 16px;
}
#usulanFormPage .uf-sidebar {
    position: sticky;
    top: 84px;
}

/* ---- Cards ---- */
#usulanFormPage .uf-card {
    background-color: var(--uf-bg);
    border: 1px solid var(--uf-border);
    border-radius: var(--uf-radius);
    box-shadow: var(--uf-shadow);
    overflow: hidden;
    min-width: 0;
    max-width: 100%;
    scroll-margin-top: 90px;
}
#usulanFormPage .uf-card-header {
    padding: 15px 20px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    background-color: #ffffff;
}
#usulanFormPage .uf-card-head-left {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 0;
    flex: 1 1 200px;
}
#usulanFormPage .uf-card-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background-color: var(--uf-primary-soft);
    border: 1px solid #dbeafe;
    color: var(--uf-primary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
#usulanFormPage .uf-card-icon svg {
    width: 18px;
    height: 18px;
}
#usulanFormPage .uf-card-icon.is-amber {
    background-color: var(--uf-warning-bg);
    border-color: var(--uf-warning-border);
    color: #d97706;
}
#usulanFormPage .uf-card-icon.is-green {
    background-color: var(--uf-success-bg);
    border-color: var(--uf-success-border);
    color: #16a34a;
}
#usulanFormPage .uf-card-icon.is-slate {
    background-color: #f1f5f9;
    border-color: var(--uf-border);
    color: #475569;
}
#usulanFormPage .uf-card-title {
    margin: 0;
    font-size: 15px;
    font-weight: 700;
    color: var(--uf-text);
    letter-spacing: -0.015em;
    line-height: 1.3;
}
#usulanFormPage .uf-card-desc {
    margin: 2px 0 0;
    font-size: 12.5px;
    color: var(--uf-text-muted);
    line-height: 1.4;
}
#usulanFormPage .uf-card-body {
    padding: 18px 20px;
}

/* ---- Form Grid & Controls ---- */
#usulanFormPage .uf-form {
    display: flex;
    flex-direction: column;
    gap: 16px;
    min-width: 0;
}
#usulanFormPage .uf-grid-2 {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 15px 16px;
}
#usulanFormPage .uf-col-full {
    grid-column: 1 / -1;
}
#usulanFormPage .uf-field {
    min-width: 0;
    scroll-margin-top: 96px;
}
#usulanFormPage .uf-label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    flex-wrap: wrap;
    font-size: 13px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 6px;
}
#usulanFormPage .uf-label-text {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    min-width: 0;
}
#usulanFormPage .uf-req {
    color: var(--uf-danger);
    font-weight: 700;
}
#usulanFormPage .uf-opt-tag {
    font-size: 11px;
    font-weight: 500;
    color: var(--uf-text-soft);
    background-color: #f8fafc;
    border: 1px solid var(--uf-border);
    padding: 1px 7px;
    border-radius: 6px;
}
#usulanFormPage .uf-input-wrap {
    position: relative;
}
#usulanFormPage .uf-input-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    width: 15px;
    height: 15px;
    color: var(--uf-text-soft);
    pointer-events: none;
}
#usulanFormPage .uf-input-icon.is-top {
    top: 13px;
    transform: none;
}
#usulanFormPage .uf-input,
#usulanFormPage .uf-textarea {
    width: 100%;
    min-height: 41px;
    padding: 9px 12px;
    font-size: 13.5px;
    color: var(--uf-text);
    background-color: #ffffff;
    border: 1px solid var(--uf-border-strong);
    border-radius: var(--uf-radius-sm);
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
    display: block;
    line-height: 1.45;
}
#usulanFormPage .uf-input::placeholder,
#usulanFormPage .uf-textarea::placeholder {
    color: #94a3b8;
}
#usulanFormPage .uf-input.has-icon,
#usulanFormPage .uf-textarea.has-icon {
    padding-left: 36px;
}
#usulanFormPage .uf-input.has-right-btn {
    padding-right: 42px;
}
#usulanFormPage .uf-textarea {
    min-height: 86px;
    resize: vertical;
}
#usulanFormPage .uf-input:focus,
#usulanFormPage .uf-textarea:focus {
    outline: none;
    border-color: var(--uf-primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}
#usulanFormPage .uf-input.is-invalid,
#usulanFormPage .uf-textarea.is-invalid {
    border-color: #ef4444;
    background-color: #fffafa;
}
#usulanFormPage .uf-input.is-invalid:focus,
#usulanFormPage .uf-textarea.is-invalid:focus {
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.12);
}
#usulanFormPage .uf-hint {
    display: block;
    font-size: 12px;
    color: var(--uf-text-muted);
    margin-top: 5px;
    line-height: 1.4;
}
#usulanFormPage .uf-error {
    display: flex;
    align-items: flex-start;
    gap: 5px;
    font-size: 12px;
    font-weight: 600;
    color: var(--uf-danger);
    margin-top: 6px;
    line-height: 1.35;
}

/* ---- Password Toggle ---- */
#usulanFormPage .uf-pw-toggle {
    position: absolute;
    right: 6px;
    top: 50%;
    transform: translateY(-50%);
    width: 32px;
    height: 32px;
    border-radius: 7px;
    border: none;
    background: transparent;
    color: var(--uf-text-muted);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background-color 0.14s ease, color 0.14s ease;
}
#usulanFormPage .uf-pw-toggle:hover {
    background-color: #f1f5f9;
    color: var(--uf-text);
}

/* ---- Selectable Radio Grid (Kerjasama) ---- */
#usulanFormPage .uf-radio-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 9px;
}
#usulanFormPage .uf-radio-option {
    position: relative;
    display: block;
    margin: 0;
    cursor: pointer;
    min-width: 0;
}
#usulanFormPage .uf-radio-input {
    position: absolute;
    left: 14px;
    top: 50%;
    opacity: 0;
    width: 1px;
    height: 1px;
    margin: 0;
    pointer-events: none;
}
#usulanFormPage .uf-radio-card {
    display: flex;
    align-items: center;
    gap: 10px;
    min-height: 44px;
    padding: 9px 12px;
    border-radius: var(--uf-radius-sm);
    border: 1px solid var(--uf-border);
    background-color: var(--uf-surface);
    color: #334155;
    font-size: 13px;
    font-weight: 500;
    line-height: 1.35;
    transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease, box-shadow 0.15s ease;
    user-select: none;
    -webkit-tap-highlight-color: transparent;
}
#usulanFormPage .uf-radio-option:hover .uf-radio-card {
    background-color: #ffffff;
    border-color: var(--uf-border-strong);
    color: var(--uf-text);
}
#usulanFormPage .uf-radio-dot {
    width: 17px;
    height: 17px;
    border-radius: 9999px;
    border: 2px solid #cbd5e1;
    background-color: #ffffff;
    flex-shrink: 0;
    position: relative;
    transition: border-color 0.15s ease, background-color 0.15s ease;
}
#usulanFormPage .uf-radio-dot::after {
    content: '';
    position: absolute;
    inset: 3px;
    border-radius: 9999px;
    background-color: #ffffff;
    transform: scale(0);
    transition: transform 0.15s ease;
}
#usulanFormPage .uf-radio-text {
    flex: 1;
    min-width: 0;
    overflow-wrap: anywhere;
    word-break: break-word;
}
#usulanFormPage .uf-radio-input:checked + .uf-radio-card {
    background-color: var(--uf-primary-soft);
    border-color: var(--uf-primary);
    color: #1e40af;
    font-weight: 600;
}
#usulanFormPage .uf-radio-input:checked + .uf-radio-card .uf-radio-dot {
    border-color: var(--uf-primary);
    background-color: var(--uf-primary);
}
#usulanFormPage .uf-radio-input:checked + .uf-radio-card .uf-radio-dot::after {
    transform: scale(1);
}
#usulanFormPage .uf-radio-input:focus-visible + .uf-radio-card {
    outline: 2px solid #93c5fd;
    outline-offset: 2px;
}
#usulanFormPage .uf-radio-grid.is-invalid .uf-radio-card {
    border-color: #fca5a5;
}

#usulanFormPage .uf-lainnya-box {
    margin-top: 12px;
    padding: 13px 14px;
    border-radius: 12px;
    background-color: var(--uf-surface);
    border: 1px solid var(--uf-border);
}

/* ---- Action Bar ---- */
#usulanFormPage .uf-action-bar {
    background-color: var(--uf-bg);
    border: 1px solid var(--uf-border);
    border-radius: 14px;
    box-shadow: var(--uf-shadow);
    padding: 15px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}
#usulanFormPage .uf-action-note {
    margin: 0;
    font-size: 12.5px;
    color: var(--uf-text-muted);
    display: flex;
    align-items: center;
    gap: 7px;
    min-width: 0;
}
#usulanFormPage .uf-action-note svg {
    width: 15px;
    height: 15px;
    color: #94a3b8;
    flex-shrink: 0;
}
#usulanFormPage .uf-action-btns {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-left: auto;
}

/* ---- Guide List (Sidebar) ---- */
#usulanFormPage .uf-guide-list {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    gap: 11px;
}
#usulanFormPage .uf-guide-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 10px 12px;
    border-radius: var(--uf-radius-sm);
    background-color: var(--uf-surface);
    border: 1px solid var(--uf-border);
    font-size: 12.5px;
    color: #334155;
    line-height: 1.48;
}
#usulanFormPage .uf-guide-bullet {
    width: 18px;
    height: 18px;
    border-radius: 9999px;
    background-color: var(--uf-primary-soft);
    border: 1px solid var(--uf-primary-border);
    color: var(--uf-primary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 1px;
}
#usulanFormPage .uf-guide-bullet svg {
    width: 11px;
    height: 11px;
}
#usulanFormPage .uf-guide-text {
    flex: 1;
    min-width: 0;
    overflow-wrap: anywhere;
}
#usulanFormPage .uf-guide-footer {
    margin-top: 14px;
    padding-top: 12px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
}
#usulanFormPage .uf-guide-footer-note {
    font-size: 12px;
    color: var(--uf-text-muted);
    margin: 0;
}

/* ---- History Section (Full Width Below Form) ---- */
#usulanFormPage .uf-history-wrap {
    margin-top: 20px;
}
#usulanFormPage .uf-table-wrap {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
#usulanFormPage .uf-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13.5px;
}
#usulanFormPage .uf-table thead th {
    background-color: var(--uf-surface);
    color: var(--uf-text-muted);
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    padding: 11px 16px;
    border-bottom: 1px solid var(--uf-border);
    white-space: nowrap;
    text-align: left;
}
#usulanFormPage .uf-table tbody td {
    padding: 13px 16px;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}
#usulanFormPage .uf-table tbody tr:last-child td {
    border-bottom: none;
}
#usulanFormPage .uf-table tbody tr:hover td {
    background-color: var(--uf-surface);
}
#usulanFormPage .uf-table-title {
    font-weight: 600;
    color: var(--uf-text);
    overflow-wrap: anywhere;
}
#usulanFormPage .uf-table-sub {
    font-size: 12px;
    color: var(--uf-text-muted);
    margin-top: 2px;
    overflow-wrap: anywhere;
}

#usulanFormPage .uf-mobile-list {
    display: none;
    flex-direction: column;
    gap: 10px;
    padding: 14px;
}
#usulanFormPage .uf-mobile-item {
    border: 1px solid var(--uf-border);
    border-radius: 12px;
    padding: 13px 14px;
    background-color: var(--uf-bg);
}
#usulanFormPage .uf-mobile-item-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 8px;
}
#usulanFormPage .uf-mobile-item-name {
    font-size: 14px;
    font-weight: 700;
    color: var(--uf-text);
    line-height: 1.35;
    overflow-wrap: anywhere;
    min-width: 0;
}
#usulanFormPage .uf-mobile-item-meta {
    display: flex;
    flex-direction: column;
    gap: 4px;
    font-size: 12.5px;
    color: var(--uf-text-muted);
    margin-bottom: 10px;
}
#usulanFormPage .uf-mobile-item-foot {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    padding-top: 9px;
    border-top: 1px solid #f1f5f9;
    flex-wrap: wrap;
}

#usulanFormPage .uf-empty {
    padding: 32px 20px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}
#usulanFormPage .uf-empty-icon {
    width: 44px;
    height: 44px;
    border-radius: 9999px;
    background-color: #f1f5f9;
    border: 1px solid var(--uf-border);
    color: #94a3b8;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
#usulanFormPage .uf-empty-title {
    margin: 0;
    font-size: 14px;
    font-weight: 700;
    color: #334155;
}
#usulanFormPage .uf-empty-desc {
    margin: 0;
    font-size: 12.5px;
    color: var(--uf-text-muted);
    max-width: 360px;
    line-height: 1.45;
}

/* ---- Responsive Breakpoints ---- */
/* Laptop: 1024pxâ€“1439px */
@media (max-width: 1439.98px) and (min-width: 1024px) {
    #usulanFormPage .uf-layout {
        grid-template-columns: minmax(0, 1.5fr) minmax(275px, 1fr);
        gap: 18px;
    }
}

/* Tablet: 768pxâ€“1023px */
@media (max-width: 1023.98px) {
    #usulanFormPage .uf-layout {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    #usulanFormPage .uf-sidebar {
        position: static;
        top: auto;
    }
}

/* Mobile landscape & Large Smartphone: 431pxâ€“767px */
@media (max-width: 767.98px) {
    #usulanFormPage .uf-header-card {
        padding: 18px;
        border-radius: 14px;
    }
    #usulanFormPage .uf-title {
        font-size: 18.5px;
    }
    #usulanFormPage .uf-header-actions {
        width: 100%;
    }
    #usulanFormPage .uf-header-actions .uf-btn {
        flex: 1 1 auto;
    }
    #usulanFormPage .uf-card {
        border-radius: 14px;
    }
    #usulanFormPage .uf-card-header {
        padding: 14px 16px;
    }
    #usulanFormPage .uf-card-body {
        padding: 16px;
    }
    #usulanFormPage .uf-grid-2 {
        grid-template-columns: 1fr;
        gap: 13px;
    }
    #usulanFormPage .uf-action-bar {
        padding: 14px 16px;
        flex-direction: column;
        align-items: stretch;
    }
    #usulanFormPage .uf-action-btns {
        margin-left: 0;
        width: 100%;
    }
    #usulanFormPage .uf-action-btns .uf-btn {
        flex: 1 1 0;
        min-height: 42px;
    }
    #usulanFormPage .uf-table-wrap {
        display: none;
    }
    #usulanFormPage .uf-mobile-list {
        display: flex;
    }
}

/* Standard Smartphone: 375pxâ€“430px */
@media (max-width: 430.98px) {
    #usulanFormPage .uf-header-card {
        padding: 16px 15px;
    }
    #usulanFormPage .uf-title {
        font-size: 17.5px;
    }
    #usulanFormPage .uf-card-header {
        padding: 13px 15px;
    }
    #usulanFormPage .uf-card-body {
        padding: 14px 15px;
    }
    #usulanFormPage .uf-radio-grid {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    #usulanFormPage .uf-action-btns {
        flex-direction: column-reverse;
        gap: 8px;
    }
    #usulanFormPage .uf-action-btns .uf-btn {
        width: 100%;
    }
    #usulanFormPage .uf-mobile-list {
        padding: 12px;
    }
}

/* Small Smartphone: 320pxâ€“374px */
@media (max-width: 374.98px) {
    #usulanFormPage .uf-header-card {
        padding: 14px 13px;
    }
    #usulanFormPage .uf-title {
        font-size: 16.5px;
    }
    #usulanFormPage .uf-subtitle {
        font-size: 12.5px;
    }
    #usulanFormPage .uf-card-header {
        padding: 12px 13px;
    }
    #usulanFormPage .uf-card-body {
        padding: 13px;
    }
    #usulanFormPage .uf-card-icon {
        width: 32px;
        height: 32px;
    }
    #usulanFormPage .uf-card-title {
        font-size: 14px;
    }
    #usulanFormPage .uf-input,
    #usulanFormPage .uf-textarea {
        font-size: 13.5px;
    }
    #usulanFormPage .uf-radio-card {
        padding: 9px 10px;
        font-size: 12.5px;
    }
    #usulanFormPage .uf-action-bar {
        padding: 13px;
    }
    #usulanFormPage .uf-guide-item {
        padding: 9px 10px;
        font-size: 12px;
    }
}
</style>

<div id="usulanFormPage" class="container-xxl flex-grow-1 container-p-y">
    <div class="uf-shell">

        {{-- Breadcrumb --}}
        <nav class="uf-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('siswa.dashboard') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" aria-hidden="true">
                    <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                    <path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                </svg>
                <span>Dashboard</span>
            </a>
            <span class="uf-bc-sep" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </span>
            <a href="{{ route('iduka.usulan') }}">Usulan IDUKA</a>
            <span class="uf-bc-sep" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </span>
            <span class="uf-bc-current">Usulan</span>
        </nav>

        {{-- Session & Validation Alerts --}}
        @if (session('error'))
            <div class="uf-alert uf-alert-danger" role="alert">
                <svg class="uf-alert-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>
                </svg>
                <div class="uf-alert-body">{{ session('error') }}</div>
                <button type="button" class="uf-alert-close" onclick="this.closest('.uf-alert').remove()" aria-label="Tutup peringatan">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        @if (session('success'))
            <div class="uf-alert uf-alert-success" role="alert">
                <svg class="uf-alert-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                <div class="uf-alert-body">{{ session('success') }}</div>
                <button type="button" class="uf-alert-close" onclick="this.closest('.uf-alert').remove()" aria-label="Tutup notifikasi">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="uf-alert uf-alert-danger" role="alert">
                <svg class="uf-alert-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                    <path d="M12 9v4"/><path d="M12 17h.01"/>
                </svg>
                <div class="uf-alert-body">
                    <p class="uf-alert-title">Mohon periksa kembali data yang Anda masukkan:</p>
                    <ul class="uf-alert-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="uf-alert-close" onclick="this.closest('.uf-alert').remove()" aria-label="Tutup daftar error">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>
        @endif

        @if ($hasActiveUsulan && $activeUsulanItem)
            <div class="uf-alert uf-alert-warning" role="status">
                <svg class="uf-alert-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>
                </svg>
                <div class="uf-alert-body">
                    <p class="uf-alert-title">Usulan Aktif Tercatat di Sistem</p>
                    <div>
                        Anda saat ini sudah memiliki usulan institusi <strong>{{ $activeUsulanItem->nama }}</strong> dengan status
                        <strong>{{ $activeUsulanItem->status === 'diterima' ? 'Diterima' : 'Menunggu Verifikasi' }}</strong>.
                        Pengajuan usulan baru tidak dapat diproses selama masih terdapat usulan yang sedang diproses atau sudah diterima.
                    </div>
                    <a href="{{ route('detail.usulan') }}" class="uf-alert-link">Lihat Detail Surat Usulan</a>
                </div>
            </div>
        @endif

        {{-- Page Header Card --}}
        <header class="uf-header-card">
            <div class="uf-header-row">
                <div class="uf-header-main">
                    <div class="uf-title-line">
                        <h1 class="uf-title">Usulan</h1>
                        <span class="uf-badge uf-badge-blue">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/>
                                <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/>
                                <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/>
                            </svg>
                            <span>Institusi / Perusahaan Baru</span>
                        </span>
                    </div>
                    <p class="uf-subtitle">
                        Form pengajuan usulan institusi atau perusahaan baru sebagai tempat pelaksanaan PKL untuk diverifikasi dan dibuatkan akun mitra.
                    </p>
                </div>

                <div class="uf-header-actions">
                    <a href="{{ route('iduka.usulan') }}" class="uf-btn uf-btn-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/>
                            <path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/>
                        </svg>
                        <span>Daftar IDUKA</span>
                    </a>
                    <a href="{{ route('siswa.dashboard') }}" class="uf-btn uf-btn-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>
                        </svg>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>
        </header>

        {{-- Main 2-Column Content --}}
        <div class="uf-layout">

            {{-- Left Column: Grouped Form --}}
            <div class="uf-main-col">
                <form id="usulanForm" class="uf-form" action="{{ route('usulan.store') }}" method="POST">
                    @csrf
                    <!-- Field status default 'proses' -->
                    <input type="hidden" name="status" value="proses">

                    {{-- A. Informasi Institusi / Perusahaan --}}
                    <section class="uf-card">
                        <div class="uf-card-header">
                            <div class="uf-card-head-left">
                                <div class="uf-card-icon" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/>
                                        <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/>
                                        <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/>
                                        <path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="uf-card-title">Informasi Institusi / Perusahaan</h2>
                                    <p class="uf-card-desc">Identitas perusahaan, sektor usaha, alamat, dan kontak resmi kantor</p>
                                </div>
                            </div>
                        </div>

                        <div class="uf-card-body">
                            <div class="uf-grid-2">
                                {{-- Nama Institusi --}}
                                <div class="uf-field uf-col-full">
                                    <label class="uf-label" for="inputNamaInstitusi">
                                        <span class="uf-label-text">Nama Institusi / Perusahaan <span class="uf-req">*</span></span>
                                    </label>
                                    <div class="uf-input-wrap">
                                        <svg class="uf-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/>
                                            <path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/>
                                        </svg>
                                        <input type="text"
                                               id="inputNamaInstitusi"
                                               name="nama"
                                               maxlength="255"
                                               class="uf-input has-icon @error('nama') is-invalid @enderror"
                                               value="{{ old('nama') }}"
                                               placeholder="Masukkan Nama Institusi / Perusahaan"
                                               required>
                                    </div>
                                    <span class="uf-hint">Nama Institusi ini akan tercatat di sistem, pastikan sudah benar!</span>
                                    @error('nama')
                                        <span class="uf-error" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Bidang Industri --}}
                                <div class="uf-field uf-col-full">
                                    <label class="uf-label" for="inputBidangIndustri">
                                        <span class="uf-label-text">Bidang Industri <span class="uf-req">*</span></span>
                                    </label>
                                    <div class="uf-input-wrap">
                                        <svg class="uf-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <rect width="20" height="14" x="2" y="7" rx="2"/>
                                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                                        </svg>
                                        <input type="text"
                                               id="inputBidangIndustri"
                                               name="bidang_industri"
                                               class="uf-input has-icon @error('bidang_industri') is-invalid @enderror"
                                               value="{{ old('bidang_industri') }}"
                                               placeholder="Masukkan Bidang Industri"
                                               required>
                                    </div>
                                    <span class="uf-hint">Silakan isi bidang industri yang ditekuni secara spesifik dan sesuai dengan sektor usaha!</span>
                                    @error('bidang_industri')
                                        <span class="uf-error" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Alamat Lengkap --}}
                                <div class="uf-field uf-col-full">
                                    <label class="uf-label" for="inputAlamatLengkap">
                                        <span class="uf-label-text">Alamat Lengkap <span class="uf-req">*</span></span>
                                    </label>
                                    <div class="uf-input-wrap">
                                        <svg class="uf-input-icon is-top" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/>
                                            <circle cx="12" cy="10" r="3"/>
                                        </svg>
                                        <textarea id="inputAlamatLengkap"
                                                  name="alamat"
                                                  rows="3"
                                                  class="uf-textarea has-icon @error('alamat') is-invalid @enderror"
                                                  placeholder="Masukkan Alamat Lengkap"
                                                  required>{{ old('alamat') }}</textarea>
                                    </div>
                                    <span class="uf-hint">Masukkan alamat lengkap lokasi PKL di sini!</span>
                                    @error('alamat')
                                        <span class="uf-error" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Kode Pos --}}
                                <div class="uf-field">
                                    <label class="uf-label" for="inputKodePos">
                                        <span class="uf-label-text">Kode Pos <span class="uf-req">*</span></span>
                                    </label>
                                    <div class="uf-input-wrap">
                                        <svg class="uf-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <line x1="4" x2="20" y1="9" y2="9"/><line x1="4" x2="20" y1="15" y2="15"/>
                                            <line x1="10" x2="8" y1="3" y2="21"/><line x1="16" x2="14" y1="3" y2="21"/>
                                        </svg>
                                        <input type="text"
                                               id="inputKodePos"
                                               name="kode_pos"
                                               maxlength="10"
                                               inputmode="numeric"
                                               class="uf-input has-icon @error('kode_pos') is-invalid @enderror"
                                               value="{{ old('kode_pos') }}"
                                               placeholder="Masukkan Kode Pos"
                                               required>
                                    </div>
                                    @error('kode_pos')
                                        <span class="uf-error" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Nomor Telepon --}}
                                <div class="uf-field">
                                    <label class="uf-label" for="inputTelepon">
                                        <span class="uf-label-text">Nomor Telepon (Kantor / Perusahaan) <span class="uf-req">*</span></span>
                                    </label>
                                    <div class="uf-input-wrap">
                                        <svg class="uf-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                        </svg>
                                        <input type="tel"
                                               id="inputTelepon"
                                               name="telepon"
                                               maxlength="20"
                                               inputmode="tel"
                                               class="uf-input has-icon @error('telepon') is-invalid @enderror"
                                               value="{{ old('telepon') }}"
                                               placeholder="Masukkan Nomor Telepon"
                                               required>
                                    </div>
                                    <span class="uf-hint">Masukkan nomor telepon aktif. Pastikan bisa diakses!</span>
                                    @error('telepon')
                                        <span class="uf-error" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="uf-field uf-col-full">
                                    <label class="uf-label" for="inputEmailInstitusi">
                                        <span class="uf-label-text">Email <span class="uf-req">*</span></span>
                                    </label>
                                    <div class="uf-input-wrap">
                                        <svg class="uf-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <rect width="20" height="16" x="2" y="4" rx="2"/>
                                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                                        </svg>
                                        <input type="email"
                                               id="inputEmailInstitusi"
                                               name="email"
                                               class="uf-input has-icon @error('email') is-invalid @enderror"
                                               value="{{ old('email') }}"
                                               placeholder="Masukkan Email"
                                               required>
                                    </div>
                                    <span class="uf-hint">Masukkan email aktif. Pastikan bisa diakses!</span>
                                    @error('email')
                                        <span class="uf-error" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- B. Data Pimpinan Institusi --}}
                    <section class="uf-card">
                        <div class="uf-card-header">
                            <div class="uf-card-head-left">
                                <div class="uf-card-icon is-amber" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="uf-card-title">Data Pimpinan Institusi</h2>
                                    <p class="uf-card-desc">Identitas pimpinan atau penanggung jawab institusi / perusahaan</p>
                                </div>
                            </div>
                        </div>

                        <div class="uf-card-body">
                            <div class="uf-grid-2">
                                {{-- Nama Lengkap Pimpinan --}}
                                <div class="uf-field uf-col-full">
                                    <label class="uf-label" for="inputNamaPimpinan">
                                        <span class="uf-label-text">Nama Lengkap Pimpinan <span class="uf-req">*</span></span>
                                    </label>
                                    <div class="uf-input-wrap">
                                        <svg class="uf-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                                            <circle cx="12" cy="7" r="4"/>
                                        </svg>
                                        <input type="text"
                                               id="inputNamaPimpinan"
                                               name="nama_pimpinan"
                                               maxlength="255"
                                               class="uf-input has-icon @error('nama_pimpinan') is-invalid @enderror"
                                               value="{{ old('nama_pimpinan') }}"
                                               placeholder="Masukkan Nama Lengkap Pimpinan"
                                               required>
                                    </div>
                                    <span class="uf-hint">Nama lengkap ini akan tercatat di sistem, pastikan sudah benar!</span>
                                    @error('nama_pimpinan')
                                        <span class="uf-error" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- NIP/NIK Pimpinan --}}
                                <div class="uf-field">
                                    <label class="uf-label" for="inputNipPimpinan">
                                        <span class="uf-label-text">NIP/NIK Pimpinan <span class="uf-req">*</span></span>
                                    </label>
                                    <div class="uf-input-wrap">
                                        <svg class="uf-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <rect width="18" height="14" x="3" y="5" rx="2"/>
                                            <line x1="7" x2="11" y1="10" y2="10"/>
                                            <line x1="7" x2="17" y1="14" y2="14"/>
                                        </svg>
                                        <input type="text"
                                               id="inputNipPimpinan"
                                               name="nip_pimpinan"
                                               maxlength="50"
                                               class="uf-input has-icon @error('nip_pimpinan') is-invalid @enderror"
                                               value="{{ old('nip_pimpinan') }}"
                                               placeholder="Masukkan NIP/NIK Pimpinan"
                                               required>
                                    </div>
                                    <span class="uf-hint">Isi NIP atau NUPTK di sini, pastikan sudah benar!</span>
                                    @error('nip_pimpinan')
                                        <span class="uf-error" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Jabatan --}}
                                <div class="uf-field">
                                    <label class="uf-label" for="inputJabatan">
                                        <span class="uf-label-text">Jabatan <span class="uf-req">*</span></span>
                                    </label>
                                    <div class="uf-input-wrap">
                                        <svg class="uf-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                                            <rect width="20" height="14" x="2" y="6" rx="2"/>
                                        </svg>
                                        <input type="text"
                                               id="inputJabatan"
                                               name="jabatan"
                                               maxlength="255"
                                               class="uf-input has-icon @error('jabatan') is-invalid @enderror"
                                               value="{{ old('jabatan') }}"
                                               placeholder="Masukkan Jabatan"
                                               required>
                                    </div>
                                    @error('jabatan')
                                        <span class="uf-error" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- C. Kerja Sama & Akun Institusi --}}
                    <section class="uf-card">
                        <div class="uf-card-header">
                            <div class="uf-card-head-left">
                                <div class="uf-card-icon is-green" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m11 17 2 2a1 1 0 1 0 3-3"/>
                                        <path d="m14 14 2.5 2.5a1 1 0 1 0 3-3l-3.88-3.88a3 3 0 0 0-4.24 0l-.88.88a1 1 0 1 1-3-3l2.81-2.81a5.79 5.79 0 0 1 7.06-.87l.47.28a2 2 0 0 0 1.42.25L21 4"/>
                                        <path d="m21 3 1 11h-2"/>
                                        <path d="M3 3 2 14l6.5 6.5a1 1 0 1 0 3-3"/>
                                        <path d="M3 4h8"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="uf-card-title">Kerja Sama &amp; Akun Institusi</h2>
                                    <p class="uf-card-desc">Pilih jenis kerja sama dan buat password akun institusi</p>
                                </div>
                            </div>
                        </div>

                        <div class="uf-card-body">
                            {{-- Kerjasama Radio Group --}}
                            <div class="uf-field" style="margin-bottom: 18px;">
                                <label class="uf-label">
                                    <span class="uf-label-text">Kerjasama <span class="uf-req">*</span></span>
                                </label>
                                <div class="uf-radio-grid @error('kerjasama') is-invalid @enderror">
                                    @foreach (array_merge($kerjasamaCol1, $kerjasamaCol2) as $idx => $option)
                                        @php
                                            $radioId = 'kerjasama_' . $idx;
                                        @endphp
                                        <label class="uf-radio-option" for="{{ $radioId }}">
                                            <input class="uf-radio-input kerjasama-radio"
                                                   type="radio"
                                                   id="{{ $radioId }}"
                                                   name="kerjasama"
                                                   value="{{ $option }}"
                                                   {{ $selectedKerjasama === $option ? 'checked' : '' }}
                                                   {{ $idx === 0 ? 'required' : '' }}>
                                            <span class="uf-radio-card">
                                                <span class="uf-radio-dot" aria-hidden="true"></span>
                                                <span class="uf-radio-text">{{ $option }}</span>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('kerjasama')
                                    <span class="uf-error" role="alert">{{ $message }}</span>
                                @enderror

                                {{-- Conditional Kerjasama Lainnya --}}
                                <div class="uf-lainnya-box"
                                     id="kerjasama-lainnya"
                                     style="display: {{ $selectedKerjasama === 'Lainnya' ? 'block' : 'none' }};">
                                    <label class="uf-label" for="inputKerjasamaLainnya">
                                        <span class="uf-label-text">Kerjasama (Lainnya)</span>
                                        <span class="uf-opt-tag">Opsional</span>
                                    </label>
                                    <input type="text"
                                           id="inputKerjasamaLainnya"
                                           class="uf-input @error('kerjasama_lainnya') is-invalid @enderror"
                                           name="kerjasama_lainnya"
                                           value="{{ old('kerjasama_lainnya') }}"
                                           placeholder="Masukkan Jenis Kerjasama">
                                    @error('kerjasama_lainnya')
                                        <span class="uf-error" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Password Akun Institusi --}}
                            <div class="uf-field">
                                <label class="uf-label" for="password">
                                    <span class="uf-label-text">Password untuk Akun Institusi / Perusahaan <span class="uf-req">*</span></span>
                                </label>
                                <div class="uf-input-wrap">
                                    <svg class="uf-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                    </svg>
                                    <input type="password"
                                           id="password"
                                           name="password"
                                           minlength="6"
                                           class="uf-input has-icon has-right-btn @error('password') is-invalid @enderror"
                                           placeholder="Minimal 6 karakter"
                                           autocomplete="new-password"
                                           required>
                                    <button type="button"
                                            class="uf-pw-toggle"
                                            id="btnTogglePassword"
                                            aria-label="Tampilkan atau sembunyikan password"
                                            aria-pressed="false">
                                        <svg id="iconEyeOpen" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        <svg id="iconEyeClosed" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display:none;">
                                            <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/>
                                            <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                                            <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                                            <line x1="2" x2="22" y1="2" y2="22"/>
                                        </svg>
                                    </button>
                                </div>
                                <span class="uf-hint">Password minimal 6 karakter (digunakan untuk akun institusi / perusahaan).</span>
                                @error('password')
                                    <span class="uf-error" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </section>

                    {{-- Form Action Bar --}}
                    <div class="uf-action-bar">
                        <p class="uf-action-note">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
                            </svg>
                            <span>Field bertanda <strong style="color:#dc2626;">*</strong> wajib diisi dengan benar.</span>
                        </p>

                        <div class="uf-action-btns">
                            <a href="{{ route('siswa.dashboard') }}" class="uf-btn uf-btn-outline" id="btnKembaliUsulan">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>
                                </svg>
                                <span>Kembali</span>
                            </a>

                            <button type="submit" class="uf-btn uf-btn-primary" id="btnSubmitUsulan">
                                <span class="uf-btn-spinner" aria-hidden="true"></span>
                                <svg class="uf-btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/>
                                </svg>
                                <span id="btnSubmitUsulanLabel">Kirim Usulan</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Right Column: Panduan Pengisian --}}
            <aside class="uf-sidebar" aria-label="Panduan pengisian usulan">
                <section class="uf-card">
                    <div class="uf-card-header">
                        <div class="uf-card-head-left">
                            <div class="uf-card-icon is-slate" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                                    <path d="M12 17h.01"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="uf-card-title">Panduan Pengisian</h2>
                                <p class="uf-card-desc">Ketentuan penting sebelum mengajukan</p>
                            </div>
                        </div>
                    </div>

                    <div class="uf-card-body">
                        <ul class="uf-guide-list">
                            <li class="uf-guide-item">
                                <span class="uf-guide-bullet" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                </span>
                                <span class="uf-guide-text">
                                    Pastikan <strong>Data Pribadi Siswa</strong> sudah diisi lengkap sebelum mengirimkan usulan institusi baru.
                                </span>
                            </li>
                            <li class="uf-guide-item">
                                <span class="uf-guide-bullet" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                </span>
                                <span class="uf-guide-text">
                                    Nama institusi, nama lengkap pimpinan, dan NIP/NIK/NUPTK akan tercatat di sistem â€” periksa kembali ejaannya.
                                </span>
                            </li>
                            <li class="uf-guide-item">
                                <span class="uf-guide-bullet" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                </span>
                                <span class="uf-guide-text">
                                    Masukkan nomor telepon kantor dan alamat email yang aktif serta dapat dihubungi.
                                </span>
                            </li>
                            <li class="uf-guide-item">
                                <span class="uf-guide-bullet" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                </span>
                                <span class="uf-guide-text">
                                    Siswa hanya dapat mengajukan usulan baru jika belum memiliki usulan berstatus <strong>Menunggu Verifikasi</strong> atau <strong>Diterima</strong>.
                                </span>
                            </li>
                        </ul>

                        <div class="uf-guide-footer">
                            <p class="uf-guide-footer-note">Ingin memilih institusi yang sudah terdaftar?</p>
                            <a href="{{ route('iduka.usulan') }}" class="uf-btn uf-btn-outline uf-btn-sm">
                                <span>Lihat Daftar IDUKA</span>
                            </a>
                        </div>
                    </div>
                </section>
            </aside>
        </div>

        {{-- Riwayat Usulan Institusi Baru (dari UsulanIdukaController::index -> $usulanSiswa) --}}
        @if (isset($usulanSiswa))
            <section class="uf-card uf-history-wrap" aria-label="Riwayat usulan institusi Anda">
                <div class="uf-card-header">
                    <div class="uf-card-head-left">
                        <div class="uf-card-icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/>
                                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                                <path d="M12 11h4"/><path d="M12 16h4"/>
                                <path d="M8 11h.01"/><path d="M8 16h.01"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="uf-card-title">Riwayat Usulan Anda</h2>
                            <p class="uf-card-desc">Daftar usulan institusi baru yang pernah diajukan melalui akun ini</p>
                        </div>
                    </div>

                    @if ($usulanList->isNotEmpty())
                        <span class="uf-badge uf-badge-neutral">{{ $usulanList->count() }} Usulan</span>
                    @endif
                </div>

                @if ($usulanList->isEmpty())
                    <div class="uf-empty">
                        <div class="uf-empty-icon" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="9" x2="15" y1="15" y2="15"/>
                            </svg>
                        </div>
                        <p class="uf-empty-title">Belum Ada Riwayat Usulan</p>
                        <p class="uf-empty-desc">
                            Anda belum pernah mengirimkan usulan institusi baru. Lengkapi form di atas untuk mengajukan institusi PKL.
                        </p>
                    </div>
                @else
                    {{-- Desktop Table --}}
                    <div class="uf-table-wrap">
                        <table class="uf-table">
                            <thead>
                                <tr>
                                    <th>Nama Institusi / Perusahaan</th>
                                    <th>Bidang Industri</th>
                                    <th>Kerjasama</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Status</th>
                                    <th style="text-align:right;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($usulanList as $item)
                                    @php
                                        $badgeMeta = match ($item->status) {
                                            'diterima' => ['class' => 'uf-badge-success', 'label' => 'Diterima'],
                                            'proses'   => ['class' => 'uf-badge-warning', 'label' => 'Menunggu Verifikasi'],
                                            'ditolak'  => ['class' => 'uf-badge-danger',  'label' => 'Ditolak'],
                                            default    => ['class' => 'uf-badge-neutral', 'label' => ucfirst((string) $item->status)],
                                        };
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="uf-table-title">{{ $item->nama }}</div>
                                            @if (!empty($item->email))
                                                <div class="uf-table-sub">{{ $item->email }}</div>
                                            @endif
                                        </td>
                                        <td>{{ $item->bidang_industri ?? '-' }}</td>
                                        <td>{{ $item->kerjasama ?? '-' }}</td>
                                        <td>{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') : '-' }}</td>
                                        <td>
                                            <span class="uf-badge {{ $badgeMeta['class'] }}">{{ $badgeMeta['label'] }}</span>
                                        </td>
                                        <td style="text-align:right; white-space:nowrap;">
                                            <a href="{{ route('detail.usulan') }}" class="uf-btn uf-btn-outline uf-btn-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                                    <circle cx="12" cy="12" r="3"/>
                                                </svg>
                                                <span>Detail</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Card List --}}
                    <div class="uf-mobile-list">
                        @foreach ($usulanList as $item)
                            @php
                                $badgeMeta = match ($item->status) {
                                    'diterima' => ['class' => 'uf-badge-success', 'label' => 'Diterima'],
                                    'proses'   => ['class' => 'uf-badge-warning', 'label' => 'Menunggu Verifikasi'],
                                    'ditolak'  => ['class' => 'uf-badge-danger',  'label' => 'Ditolak'],
                                    default    => ['class' => 'uf-badge-neutral', 'label' => ucfirst((string) $item->status)],
                                };
                            @endphp
                            <div class="uf-mobile-item">
                                <div class="uf-mobile-item-top">
                                    <div class="uf-mobile-item-name">{{ $item->nama }}</div>
                                    <span class="uf-badge {{ $badgeMeta['class'] }}">{{ $badgeMeta['label'] }}</span>
                                </div>
                                <div class="uf-mobile-item-meta">
                                    <div>Bidang: <strong style="color:#334155;">{{ $item->bidang_industri ?? '-' }}</strong></div>
                                    <div>Kerjasama: <strong style="color:#334155;">{{ $item->kerjasama ?? '-' }}</strong></div>
                                </div>
                                <div class="uf-mobile-item-foot">
                                    <span style="font-size:12px;color:#64748b;">
                                        {{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') : '-' }}
                                    </span>
                                    <a href="{{ route('detail.usulan') }}" class="uf-btn uf-btn-outline uf-btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        <span>Detail</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById('usulanForm');
    const submitBtn = document.getElementById('btnSubmitUsulan');
    const submitLabel = document.getElementById('btnSubmitUsulanLabel');
    const radios = document.querySelectorAll('.kerjasama-radio');
    const lainnyaBox = document.getElementById('kerjasama-lainnya');
    const lainnyaInput = document.getElementById('inputKerjasamaLainnya');
    const pwInput = document.getElementById('password');
    const pwToggleBtn = document.getElementById('btnTogglePassword');
    const iconEyeOpen = document.getElementById('iconEyeOpen');
    const iconEyeClosed = document.getElementById('iconEyeClosed');

    // Toggle input "Lainnya" berdasarkan seluruh pilihan radio kerjasama
    function syncLainnyaField() {
        const selected = document.querySelector('.kerjasama-radio:checked');
        const isLainnya = selected && selected.value === 'Lainnya';
        if (lainnyaBox) {
            lainnyaBox.style.display = isLainnya ? 'block' : 'none';
        }
        if (!isLainnya && lainnyaInput) {
            lainnyaInput.value = '';
        }
    }

    radios.forEach(function (radio) {
        radio.addEventListener('change', syncLainnyaField);
    });
    syncLainnyaField();

    // Toggle tampil/sembunyikan password akun institusi
    if (pwToggleBtn && pwInput) {
        pwToggleBtn.addEventListener('click', function () {
            const isHidden = pwInput.type === 'password';
            pwInput.type = isHidden ? 'text' : 'password';
            pwToggleBtn.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
            if (iconEyeOpen && iconEyeClosed) {
                iconEyeOpen.style.display = isHidden ? 'none' : 'block';
                iconEyeClosed.style.display = isHidden ? 'block' : 'none';
            }
        });
    }

    // Gulir ke field bermasalah pertama jika validasi backend gagal
    const firstInvalid = form ? form.querySelector('.is-invalid') : null;
    if (firstInvalid) {
        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // State & konfirmasi pengiriman form + cegah double submit
    let isSubmitting = false;

    function lockSubmitState() {
        isSubmitting = true;
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('is-loading');
            submitBtn.setAttribute('aria-busy', 'true');
        }
        if (submitLabel) {
            submitLabel.textContent = 'Mengirim...';
        }
        const pklLoader = document.getElementById('pkl-page-loader');
        if (pklLoader) {
            pklLoader.style.display = 'block';
            pklLoader.classList.add('pkl-loader-visible');
        }
    }

    window.addEventListener('pageshow', function () {
        isSubmitting = false;
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('is-loading');
            submitBtn.removeAttribute('aria-busy');
        }
        if (submitLabel) {
            submitLabel.textContent = 'Kirim Usulan';
        }
    });

    if (form) {
        form.addEventListener('submit', function (e) {
            if (isSubmitting) {
                e.preventDefault();
                return;
            }

            if (!form.checkValidity()) {
                e.preventDefault();
                form.reportValidity();
                return;
            }

            e.preventDefault();

            if (typeof Swal === 'undefined') {
                lockSubmitState();
                form.submit();
                return;
            }

            Swal.fire({
                title: 'Kirim Data Usulan?',
                text: 'Pastikan seluruh data institusi dan pimpinan yang diisi sudah benar.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#ffffff',
                confirmButtonText: 'Ya, Kirim!',
                cancelButtonText: 'Batal'
            }).then(function (result) {
                if (result.isConfirmed) {
                    lockSubmitState();
                    form.submit();
                }
            });
        });
    }
});
</script>
@endpush
