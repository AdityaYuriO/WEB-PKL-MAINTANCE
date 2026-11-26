@extends('layout.main')

@section('content')
@php
    $pendingCount  = $activeJurnals->where('status', 'pending')->count();
    $rejectedCount = $activeJurnals->where('status', 'rejected')->count();
    $approvedCount = $historyJurnals->count();
    $totalCount    = $activeJurnals->count() + $approvedCount;
    $hasFormErrors = $errors->any();
@endphp

<style>
#jurnalPage {
    --jrn-bg: #ffffff;
    --jrn-muted: #f8fafc;
    --jrn-border: #e2e8f0;
    --jrn-border-strong: #cbd5e1;
    --jrn-text: #0f172a;
    --jrn-text-muted: #64748b;
    --jrn-text-soft: #94a3b8;
    --jrn-primary: #2563eb;
    --jrn-radius: 16px;
    --jrn-shadow: 0 1px 3px rgba(15, 23, 42, 0.05), 0 1px 2px rgba(15, 23, 42, 0.03);
}

/* ---- Flash & Validation Alerts ---- */
#jurnalPage .jrn-alert {
    display: flex;
    align-items: flex-start;
    gap: 11px;
    padding: 13px 16px;
    border-radius: 12px;
    border: 1px solid;
    margin-bottom: 14px;
    font-size: 13.5px;
    line-height: 1.45;
}
#jurnalPage .jrn-alert-icon {
    flex-shrink: 0;
    margin-top: 1px;
}
#jurnalPage .jrn-alert-content {
    flex: 1;
    min-width: 0;
}
#jurnalPage .jrn-alert-close {
    background: transparent;
    border: none;
    padding: 2px;
    cursor: pointer;
    opacity: 0.65;
    color: inherit;
    line-height: 1;
    flex-shrink: 0;
}
#jurnalPage .jrn-alert-close:hover { opacity: 1; }
#jurnalPage .jrn-alert-success { background-color: #f0fdf4; border-color: #bbf7d0; color: #166534; }
#jurnalPage .jrn-alert-danger  { background-color: #fef2f2; border-color: #fecaca; color: #991b1b; }

/* ---- Card Primitive ---- */
#jurnalPage .jrn-card {
    background-color: var(--jrn-bg);
    border: 1px solid var(--jrn-border);
    border-radius: var(--jrn-radius);
    box-shadow: var(--jrn-shadow);
    margin-bottom: 16px;
    overflow: hidden;
}
#jurnalPage .jrn-card-header {
    padding: 15px 20px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    background-color: #ffffff;
}
#jurnalPage .jrn-card-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1 1 220px;
    min-width: 0;
}
#jurnalPage .jrn-card-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--jrn-text);
    margin: 0;
    letter-spacing: -0.015em;
    line-height: 1.3;
}
#jurnalPage .jrn-card-subtitle {
    font-size: 12.5px;
    color: var(--jrn-text-muted);
    margin: 2px 0 0;
    line-height: 1.4;
}
#jurnalPage .jrn-card-body {
    padding: 18px 20px;
}

/* ---- 3D Icon Boxes ---- */
#jurnalPage .icon-3d-box {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
#jurnalPage .icon-3d-box svg {
    width: 36px;
    height: 36px;
    display: block;
}
#jurnalPage .icon-3d-box-lg {
    width: 54px;
    height: 54px;
    border-radius: 14px;
}
#jurnalPage .icon-3d-box-lg svg {
    width: 44px;
    height: 44px;
}

/* ---- Hero & Ringkasan ---- */
#jurnalPage .jrn-hero-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 14px 18px;
    flex-wrap: wrap;
}
#jurnalPage .jrn-hero-identity {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    flex: 1 1 360px;
    min-width: 0;
}
#jurnalPage .jrn-hero-text {
    flex: 1;
    min-width: 0;
    padding-top: 1px;
}
#jurnalPage .jrn-hero-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--jrn-text);
    margin: 0;
    letter-spacing: -0.02em;
    line-height: 1.3;
}
#jurnalPage .jrn-hero-desc {
    font-size: 13px;
    color: var(--jrn-text-muted);
    margin: 4px 0 0;
    line-height: 1.5;
    overflow-wrap: break-word;
    word-break: normal;
}
#jurnalPage .jrn-hero-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex: 0 0 auto;
}

/* Ringkasan Metrics Grid */
#jurnalPage .jrn-stats-grid {
    margin-top: 16px;
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 10px;
}
#jurnalPage .jrn-stat-chip {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 11px 13px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    min-width: 0;
}
#jurnalPage .jrn-stat-meta {
    min-width: 0;
}
#jurnalPage .jrn-stat-label {
    font-size: 11px;
    font-weight: 600;
    color: #64748b;
    margin: 0 0 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
#jurnalPage .jrn-stat-val {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
    line-height: 1.2;
    font-variant-numeric: tabular-nums;
}
#jurnalPage .jrn-stat-icon3d {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border: 1px solid;
}
#jurnalPage .jrn-stat-icon3d svg {
    width: 22px;
    height: 22px;
    display: block;
}

/* ---- Buttons (shadcn-style, immune to app.css .btn !important) ---- */
#jurnalPage .jrn-btn,
.jrn-modal .jrn-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    height: 36px;
    padding: 0 14px;
    border-radius: 9px;
    font-size: 13px;
    font-weight: 600;
    line-height: 1;
    border: 1px solid transparent;
    cursor: pointer;
    text-decoration: none;
    white-space: nowrap;
    transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease, transform 0.12s ease;
    -webkit-tap-highlight-color: transparent;
}
#jurnalPage .jrn-btn:active:not(:disabled),
.jrn-modal .jrn-btn:active:not(:disabled) {
    transform: scale(0.985);
}
#jurnalPage .jrn-btn:disabled,
.jrn-modal .jrn-btn:disabled {
    opacity: 0.65;
    cursor: not-allowed;
}
#jurnalPage .jrn-btn svg,
.jrn-modal .jrn-btn svg {
    width: 15px;
    height: 15px;
    flex-shrink: 0;
}
#jurnalPage .jrn-btn-sm,
.jrn-modal .jrn-btn-sm {
    height: 32px;
    padding: 0 11px;
    font-size: 12.5px;
    border-radius: 8px;
}
#jurnalPage .jrn-btn-sm svg,
.jrn-modal .jrn-btn-sm svg {
    width: 14px;
    height: 14px;
}
#jurnalPage .jrn-btn-primary,
.jrn-modal .jrn-btn-primary {
    background-color: #2563eb;
    border-color: #2563eb;
    color: #ffffff;
}
#jurnalPage .jrn-btn-primary:hover:not(:disabled),
.jrn-modal .jrn-btn-primary:hover:not(:disabled) {
    background-color: #1d4ed8;
    border-color: #1d4ed8;
    color: #ffffff;
}
#jurnalPage .jrn-btn-outline,
.jrn-modal .jrn-btn-outline {
    background-color: #ffffff;
    border-color: #cbd5e1;
    color: #334155;
}
#jurnalPage .jrn-btn-outline:hover:not(:disabled),
.jrn-modal .jrn-btn-outline:hover:not(:disabled) {
    background-color: #f8fafc;
    border-color: #94a3b8;
    color: #0f172a;
}
#jurnalPage .jrn-btn-detail,
.jrn-modal .jrn-btn-detail {
    background-color: #eff6ff;
    border-color: #bfdbfe;
    color: #1d4ed8;
}
#jurnalPage .jrn-btn-detail:hover:not(:disabled),
.jrn-modal .jrn-btn-detail:hover:not(:disabled) {
    background-color: #dbeafe;
    border-color: #93c5fd;
    color: #1e40af;
}
#jurnalPage .jrn-btn-danger,
.jrn-modal .jrn-btn-danger {
    background-color: #dc2626;
    border-color: #dc2626;
    color: #ffffff;
}
#jurnalPage .jrn-btn-danger:hover:not(:disabled),
.jrn-modal .jrn-btn-danger:hover:not(:disabled) {
    background-color: #b91c1c;
    border-color: #b91c1c;
    color: #ffffff;
}
#jurnalPage .jrn-btn-danger-outline {
    background-color: #ffffff;
    border-color: #fecaca;
    color: #dc2626;
}
#jurnalPage .jrn-btn-danger-outline:hover:not(:disabled) {
    background-color: #fef2f2;
    border-color: #fca5a5;
    color: #b91c1c;
}

/* ---- Desktop 2-Column Workspace & Mobile Stack ---- */
#jurnalPage .jrn-workspace {
    display: grid;
    grid-template-columns: minmax(345px, 415px) minmax(0, 1fr);
    gap: 18px;
    align-items: start;
}
#jurnalPage .jrn-form-col {
    position: sticky;
    top: 82px;
    min-width: 0;
}
#jurnalPage .jrn-feed-col {
    min-width: 0;
}
#jurnalPage .jrn-mobile-close {
    display: none;
}

/* ---- Form Inputs & Controls ---- */
#jurnalPage .jrn-form-group {
    margin-bottom: 14px;
}
#jurnalPage .jrn-label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    font-size: 12px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 6px;
}
#jurnalPage .jrn-label-main {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
#jurnalPage .jrn-label-main svg {
    color: #64748b;
    width: 14px;
    height: 14px;
    flex-shrink: 0;
}
#jurnalPage .jrn-req {
    color: #dc2626;
}
#jurnalPage .jrn-input,
#jurnalPage .jrn-textarea {
    width: 100%;
    padding: 9px 12px;
    font-size: 13.5px;
    color: #0f172a;
    background-color: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
    display: block;
}
#jurnalPage .jrn-input:focus,
#jurnalPage .jrn-textarea:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}
#jurnalPage .jrn-input.is-invalid,
#jurnalPage .jrn-textarea.is-invalid {
    border-color: #ef4444;
    background-color: #fffafa;
}
#jurnalPage .jrn-textarea {
    min-height: 108px;
    resize: vertical;
    line-height: 1.55;
}
#jurnalPage .jrn-field-hint {
    display: block;
    font-size: 11.5px;
    color: #64748b;
    margin-top: 5px;
    line-height: 1.35;
}
#jurnalPage .jrn-field-error {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #dc2626;
    margin-top: 5px;
}
#jurnalPage .jrn-time-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
}
#jurnalPage .jrn-duration-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    font-weight: 600;
    padding: 3px 9px;
    border-radius: 9999px;
    background-color: #f1f5f9;
    color: #475569;
    margin-top: 7px;
}
#jurnalPage .jrn-duration-pill.is-valid {
    background-color: #eff6ff;
    color: #1d4ed8;
}
#jurnalPage .jrn-duration-pill.is-error {
    background-color: #fef2f2;
    color: #dc2626;
}

/* Upload Zone */
#jurnalPage .jrn-upload-zone {
    position: relative;
    border: 1.5px dashed #cbd5e1;
    border-radius: 11px;
    background-color: #f8fafc;
    padding: 12px 14px;
    display: flex;
    align-items: center;
    gap: 11px;
    cursor: pointer;
    transition: border-color 0.15s ease, background-color 0.15s ease;
}
#jurnalPage .jrn-upload-zone:hover {
    border-color: #2563eb;
    background-color: #eff6ff;
}
#jurnalPage .jrn-upload-zone input[type="file"] {
    position: absolute;
    inset: 0;
    opacity: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
}
#jurnalPage .jrn-upload-icon {
    width: 36px;
    height: 36px;
    border-radius: 9px;
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    color: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
#jurnalPage .jrn-upload-copy {
    min-width: 0;
    flex: 1;
}
#jurnalPage .jrn-upload-title {
    font-size: 12.5px;
    font-weight: 600;
    color: #0f172a;
    display: block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
#jurnalPage .jrn-upload-sub {
    font-size: 11px;
    color: #64748b;
    display: block;
    margin-top: 1px;
}
#jurnalPage .jrn-photo-preview {
    margin-top: 8px;
    padding: 8px 10px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    background-color: #ffffff;
    display: none;
    align-items: center;
    gap: 10px;
}
#jurnalPage .jrn-photo-preview.is-visible {
    display: flex;
}
#jurnalPage .jrn-photo-preview img {
    width: 42px;
    height: 42px;
    border-radius: 7px;
    object-fit: cover;
    border: 1px solid #e2e8f0;
    flex-shrink: 0;
}
#jurnalPage .jrn-photo-preview-name {
    flex: 1;
    min-width: 0;
    font-size: 12px;
    font-weight: 600;
    color: #334155;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Check Cards */
#jurnalPage .jrn-check-stack {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 16px;
}
#jurnalPage .jrn-check-card {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    background-color: #f8fafc;
    cursor: pointer;
    margin: 0;
    transition: border-color 0.15s ease, background-color 0.15s ease;
}
#jurnalPage .jrn-check-card:hover {
    border-color: #cbd5e1;
    background-color: #f1f5f9;
}
#jurnalPage .jrn-check-card input[type="checkbox"] {
    width: 17px;
    height: 17px;
    margin-top: 2px;
    accent-color: #2563eb;
    cursor: pointer;
    flex-shrink: 0;
}
#jurnalPage .jrn-check-title {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12.5px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.3;
}
#jurnalPage .jrn-check-desc {
    display: block;
    font-size: 11.5px;
    color: #64748b;
    margin-top: 2px;
    line-height: 1.35;
}
#jurnalPage .jrn-form-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
    padding-top: 12px;
    border-top: 1px solid #f1f5f9;
}

/* ---- Segmented Tabs & Search Toolbar ---- */
#jurnalPage .jrn-toolbar {
    padding: 14px 18px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    flex-direction: column;
    gap: 12px;
    background-color: #ffffff;
}
#jurnalPage .jrn-toolbar-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
}
#jurnalPage .jrn-tabs {
    display: inline-flex;
    flex-wrap: nowrap;
    align-items: center;
    padding: 4px;
    background-color: #f1f5f9;
    border-radius: 11px;
    border: 1px solid #e2e8f0;
    gap: 4px;
    list-style: none;
    margin: 0;
    max-width: 100%;
}
#jurnalPage .jrn-tabs .nav-item {
    margin: 0;
}
#jurnalPage .jrn-tab-btn {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 7px !important;
    padding: 7px 13px !important;
    border-radius: 8px !important;
    border: 1px solid transparent !important;
    background-color: transparent !important;
    color: #475569 !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    line-height: 1.2 !important;
    cursor: pointer;
    transition: background-color 0.15s ease, color 0.15s ease, box-shadow 0.15s ease;
    white-space: nowrap;
}
#jurnalPage .jrn-tab-btn:hover {
    color: #0f172a !important;
}
#jurnalPage .jrn-tab-btn.active {
    background-color: #ffffff !important;
    color: #0f172a !important;
    border-color: #e2e8f0 !important;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06) !important;
}
#jurnalPage .jrn-tab-count {
    font-size: 11px;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 9999px;
    font-variant-numeric: tabular-nums;
}
#jurnalPage .jrn-tab-count.is-amber {
    background-color: #fef3c7;
    color: #b45309;
}
#jurnalPage .jrn-tab-count.is-green {
    background-color: #dcfce7;
    color: #15803d;
}
#jurnalPage .jrn-search-wrap {
    position: relative;
    flex: 1 1 210px;
    max-width: 280px;
    min-width: 0;
}
#jurnalPage .jrn-search-wrap svg.jrn-search-icon {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    width: 14px;
    height: 14px;
    color: #94a3b8;
    pointer-events: none;
}
#jurnalPage .jrn-search-input {
    width: 100%;
    height: 36px;
    padding: 0 30px 0 32px;
    font-size: 12.5px;
    color: #0f172a;
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 9px;
    transition: border-color 0.15s ease, background-color 0.15s ease;
}
#jurnalPage .jrn-search-input:focus {
    outline: none;
    background-color: #ffffff;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}
#jurnalPage .jrn-search-clear {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    border: none;
    color: #94a3b8;
    padding: 2px;
    cursor: pointer;
    display: none;
    line-height: 1;
}
#jurnalPage .jrn-search-clear.is-visible {
    display: inline-flex;
}

/* Status Filter Chips (Active Tab) */
#jurnalPage .jrn-filter-bar {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
#jurnalPage .jrn-filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 11px;
    border-radius: 9999px;
    border: 1px solid #e2e8f0;
    background-color: #ffffff;
    color: #475569;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
}
#jurnalPage .jrn-filter-chip:hover {
    background-color: #f8fafc;
    border-color: #cbd5e1;
}
#jurnalPage .jrn-filter-chip.is-active {
    background-color: #eff6ff;
    border-color: #bfdbfe;
    color: #1d4ed8;
}

/* ---- Journal Feed List & Item Cards ---- */
#jurnalPage .jrn-feed-body {
    padding: 16px 18px;
    background-color: #ffffff;
}
#jurnalPage .jrn-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
#jurnalPage .jrn-item {
    border: 1px solid #e2e8f0;
    border-radius: 13px;
    background-color: #ffffff;
    padding: 15px 16px;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}
#jurnalPage .jrn-item:hover {
    border-color: #cbd5e1;
    box-shadow: 0 4px 12px -4px rgba(15, 23, 42, 0.08);
}
#jurnalPage .jrn-item.is-rejected {
    border-left: 3.5px solid #ef4444;
}
#jurnalPage .jrn-item-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 10px;
}
#jurnalPage .jrn-item-date-wrap {
    min-width: 0;
    flex: 1 1 200px;
}
#jurnalPage .jrn-item-date {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13.5px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.3;
}
#jurnalPage .jrn-item-date svg {
    color: #2563eb;
    width: 14px;
    height: 14px;
    flex-shrink: 0;
}
#jurnalPage .jrn-item-submeta {
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    font-size: 12px;
    color: #64748b;
    font-variant-numeric: tabular-nums;
}
#jurnalPage .jrn-item-submeta-piece {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
#jurnalPage .jrn-item-submeta-piece svg {
    width: 12.5px;
    height: 12.5px;
    color: #94a3b8;
    flex-shrink: 0;
}

/* Status Badges */
#jurnalPage .jrn-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 9999px;
    border: 1px solid;
    white-space: nowrap;
    line-height: 1.25;
}
#jurnalPage .jrn-badge svg {
    width: 12.5px;
    height: 12.5px;
    flex-shrink: 0;
}
#jurnalPage .jrn-badge-pending  { background-color: #fffbeb; border-color: #fde68a; color: #b45309; }
#jurnalPage .jrn-badge-rejected { background-color: #fef2f2; border-color: #fecaca; color: #b91c1c; }
#jurnalPage .jrn-badge-approved { background-color: #f0fdf4; border-color: #bbf7d0; color: #15803d; }

#jurnalPage .jrn-item-uraian {
    font-size: 13.5px;
    color: #334155;
    line-height: 1.55;
    margin: 0 0 11px;
    overflow-wrap: break-word;
    word-break: normal;
}

/* Tags Row */
#jurnalPage .jrn-item-tags {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
    margin-bottom: 12px;
}
#jurnalPage .jrn-tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    font-weight: 600;
    padding: 3px 9px;
    border-radius: 7px;
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #475569;
    text-decoration: none;
}
#jurnalPage .jrn-tag svg {
    width: 12px;
    height: 12px;
    flex-shrink: 0;
}
#jurnalPage .jrn-tag-photo {
    background-color: #f0f9ff;
    border-color: #bae6fd;
    color: #0369a1;
    cursor: pointer;
}
#jurnalPage .jrn-tag-photo:hover {
    background-color: #e0f2fe;
}
#jurnalPage .jrn-tag-idea {
    background-color: #fffbeb;
    border-color: #fde68a;
    color: #b45309;
}
#jurnalPage .jrn-tag-mapel {
    background-color: #eff6ff;
    border-color: #bfdbfe;
    color: #1d4ed8;
}

/* Feedback / Rejection Note */
#jurnalPage .jrn-item-feedback {
    padding: 10px 12px;
    border-radius: 9px;
    background-color: #fef2f2;
    border: 1px solid #fecaca;
    color: #991b1b;
    font-size: 12.5px;
    line-height: 1.45;
    margin-bottom: 12px;
    display: flex;
    align-items: flex-start;
    gap: 8px;
}
#jurnalPage .jrn-item-feedback svg {
    width: 15px;
    height: 15px;
    flex-shrink: 0;
    margin-top: 2px;
}

/* Card Footer Actions */
#jurnalPage .jrn-item-footer {
    padding-top: 11px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
}
#jurnalPage .jrn-item-approver {
    font-size: 12px;
    color: #64748b;
    font-weight: 500;
}
#jurnalPage .jrn-item-actions {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-left: auto;
}

/* ---- Tab 2: Riwayat (Approved) ---- */
#jurnalPage .jrn-hist-summary {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    padding: 12px 15px;
    margin-bottom: 14px;
    border-radius: 12px;
    background-color: #f0fdf4;
    border: 1px solid #bbf7d0;
}
#jurnalPage .jrn-hist-summary-left {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}
#jurnalPage .jrn-hist-summary-icon {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background-color: #ffffff;
    border: 1px solid #bbf7d0;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
#jurnalPage .jrn-hist-summary-icon svg {
    width: 22px;
    height: 22px;
    display: block;
}
#jurnalPage .jrn-hist-summary-title {
    font-size: 13px;
    font-weight: 700;
    color: #14532d;
    margin: 0;
    line-height: 1.25;
}
#jurnalPage .jrn-hist-summary-sub {
    font-size: 11.5px;
    color: #166534;
    margin: 1px 0 0;
    line-height: 1.3;
}
#jurnalPage .jrn-hist-summary-pills {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
#jurnalPage .jrn-hist-stat-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 9999px;
    background-color: #ffffff;
    border: 1px solid #bbf7d0;
    font-size: 11.5px;
    font-weight: 600;
    color: #15803d;
    font-variant-numeric: tabular-nums;
}
#jurnalPage .jrn-hist-list {
    display: flex;
    flex-direction: column;
    gap: 13px;
}
#jurnalPage .jrn-hist-card {
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}
#jurnalPage .jrn-hist-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 6px 16px -6px rgba(15, 23, 42, 0.08);
}
#jurnalPage .jrn-hist-card-head {
    padding: 14px 16px 12px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
}
#jurnalPage .jrn-hist-head-left {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    flex: 1 1 230px;
    min-width: 0;
}
#jurnalPage .jrn-hist-date-tile {
    width: 46px;
    min-height: 48px;
    border-radius: 10px;
    background-color: #f0fdf4;
    border: 1px solid #bbf7d0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 4px;
    flex-shrink: 0;
    text-align: center;
}
#jurnalPage .jrn-hist-date-day {
    font-size: 16px;
    font-weight: 800;
    color: #15803d;
    line-height: 1.05;
    font-variant-numeric: tabular-nums;
}
#jurnalPage .jrn-hist-date-mon {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #166534;
    margin-top: 2px;
}
#jurnalPage .jrn-hist-title-block {
    min-width: 0;
    flex: 1;
}
#jurnalPage .jrn-hist-title {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
    line-height: 1.35;
    overflow-wrap: break-word;
    word-break: normal;
}
#jurnalPage .jrn-hist-meta {
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 6px 10px;
    flex-wrap: wrap;
    font-size: 12px;
    color: #64748b;
    font-variant-numeric: tabular-nums;
}
#jurnalPage .jrn-hist-meta-item {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
#jurnalPage .jrn-hist-meta-item svg {
    width: 12.5px;
    height: 12.5px;
    color: #94a3b8;
    flex-shrink: 0;
}
#jurnalPage .jrn-hist-duration {
    display: inline-flex;
    align-items: center;
    padding: 1px 7px;
    border-radius: 9999px;
    background-color: #f1f5f9;
    color: #475569;
    font-size: 11px;
    font-weight: 600;
}
#jurnalPage .jrn-hist-status {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 9999px;
    background-color: #dcfce7;
    border: 1px solid #86efac;
    color: #15803d;
    font-size: 11.5px;
    font-weight: 700;
    white-space: nowrap;
    line-height: 1.25;
}
#jurnalPage .jrn-hist-status svg {
    width: 13px;
    height: 13px;
    flex-shrink: 0;
}
#jurnalPage .jrn-hist-card-body {
    padding: 13px 16px;
}
#jurnalPage .jrn-hist-content-wrap {
    display: flex;
    align-items: flex-start;
    gap: 14px;
}
#jurnalPage .jrn-hist-desc-col {
    flex: 1;
    min-width: 0;
}
#jurnalPage .jrn-hist-uraian {
    margin: 0;
    font-size: 13.5px;
    color: #334155;
    line-height: 1.6;
    overflow-wrap: break-word;
    word-break: normal;
}
#jurnalPage .jrn-hist-tags {
    margin-top: 10px;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
#jurnalPage .jrn-hist-thumb-btn {
    position: relative;
    width: 96px;
    height: 76px;
    padding: 0;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    background-color: #f8fafc;
    overflow: hidden;
    flex-shrink: 0;
    cursor: zoom-in;
    display: block;
    transition: border-color 0.15s ease, transform 0.15s ease;
}
#jurnalPage .jrn-hist-thumb-btn:hover {
    border-color: #2563eb;
    transform: translateY(-1px);
}
#jurnalPage .jrn-hist-thumb-btn img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
#jurnalPage .jrn-hist-thumb-badge {
    position: absolute;
    right: 5px;
    bottom: 5px;
    width: 22px;
    height: 22px;
    border-radius: 6px;
    background-color: rgba(15, 23, 42, 0.72);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
}
#jurnalPage .jrn-hist-card-foot {
    padding: 10px 16px;
    background-color: #f8fafc;
    border-top: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    flex-wrap: wrap;
}
#jurnalPage .jrn-hist-verifiers {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
    min-width: 0;
}
#jurnalPage .jrn-verifier-chip {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 9px;
    border-radius: 9999px;
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    font-size: 11.5px;
    font-weight: 600;
    color: #334155;
}
#jurnalPage .jrn-verifier-chip svg {
    width: 12px;
    height: 12px;
    color: #16a34a;
    flex-shrink: 0;
}
#jurnalPage .jrn-verifier-date {
    color: #64748b;
    font-weight: 500;
}
#jurnalPage .jrn-hist-foot-actions {
    margin-left: auto;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Empty & Search Empty States */
#jurnalPage .jrn-empty {
    padding: 36px 18px;
    text-align: center;
    border: 1.5px dashed #cbd5e1;
    border-radius: 14px;
    background-color: #f8fafc;
    display: flex;
    flex-direction: column;
    align-items: center;
}
#jurnalPage .jrn-empty-illus {
    width: 72px;
    height: 72px;
    border-radius: 18px;
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 14px;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.04);
}
#jurnalPage .jrn-empty-illus svg {
    width: 50px;
    height: 50px;
}
#jurnalPage .jrn-empty-title {
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 4px;
}
#jurnalPage .jrn-empty-desc {
    font-size: 13px;
    color: #64748b;
    margin: 0;
    max-width: 340px;
    line-height: 1.45;
}

/* ---- Modern Shadcn-styled Dialogs / Modals ---- */
.jrn-modal .modal-content {
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 20px 40px -12px rgba(15, 23, 42, 0.18);
    overflow: hidden;
    background-color: #ffffff;
}
.jrn-modal .modal-header {
    padding: 15px 20px;
    border-bottom: 1px solid #f1f5f9;
    background-color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.jrn-modal .modal-title {
    font-size: 15.5px;
    font-weight: 700;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 9px;
    margin: 0;
}
.jrn-modal .jrn-modal-title-icon {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border: 1px solid;
}
.jrn-modal .jrn-modal-close {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    border: 1px solid transparent;
    background: transparent;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background-color 0.15s ease, color 0.15s ease;
}
.jrn-modal .jrn-modal-close:hover {
    background-color: #f1f5f9;
    color: #0f172a;
}
.jrn-modal .modal-body {
    padding: 18px 20px;
}
.jrn-modal .modal-footer {
    padding: 13px 20px;
    border-top: 1px solid #f1f5f9;
    background-color: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 8px;
}
#imageModal {
    z-index: 1065;
}
.jrn-modal-loading {
    text-align: center;
    padding: 36px 16px;
}
.jrn-modal-spinner {
    width: 34px;
    height: 34px;
    border: 3px solid #e2e8f0;
    border-top-color: #2563eb;
    border-radius: 9999px;
    animation: jrnSpin 0.75s linear infinite;
    margin: 0 auto 10px;
}
@keyframes jrnSpin {
    to { transform: rotate(360deg); }
}

/* ---- Responsive Breakpoints ---- */
@media (max-width: 991.98px) {
    #jurnalPage .jrn-workspace {
        grid-template-columns: 1fr;
        gap: 14px;
    }
    #jurnalPage .jrn-form-col {
        position: static;
    }
    #jurnalPage #createFormPanel {
        display: none;
    }
    #jurnalPage #createFormPanel.is-mobile-open {
        display: block;
    }
    #jurnalPage .jrn-mobile-close {
        display: inline-flex;
    }
}
@media (max-width: 767.98px) {
    #jurnalPage .jrn-card-header { padding: 13px 15px; }
    #jurnalPage .jrn-card-body   { padding: 14px 15px; }
    #jurnalPage .jrn-hero-top {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
    }
    #jurnalPage .jrn-hero-identity {
        flex: none;
        width: 100%;
        gap: 12px;
    }
    #jurnalPage .jrn-hero-identity .icon-3d-box-lg {
        width: 44px;
        height: 44px;
        border-radius: 12px;
    }
    #jurnalPage .jrn-hero-identity .icon-3d-box-lg svg {
        width: 36px;
        height: 36px;
    }
    #jurnalPage .jrn-hero-title { font-size: 16.5px; }
    #jurnalPage .jrn-hero-desc  { font-size: 12.5px; }
    #jurnalPage .jrn-hero-actions {
        width: 100%;
    }
    #jurnalPage .jrn-hero-actions .jrn-btn {
        width: 100%;
    }
    #jurnalPage .jrn-stats-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px;
        margin-top: 14px;
    }
    #jurnalPage .jrn-stat-chip {
        padding: 10px 11px;
    }
    #jurnalPage .jrn-stat-val { font-size: 16px; }
    #jurnalPage .jrn-toolbar { padding: 12px 14px; }
    #jurnalPage .jrn-toolbar-top {
        flex-direction: column;
        align-items: stretch;
    }
    #jurnalPage .jrn-tabs {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    #jurnalPage .jrn-tab-btn {
        width: 100%;
        padding: 7px 8px !important;
        font-size: 12px !important;
    }
    #jurnalPage .jrn-search-wrap {
        flex: none;
        max-width: 100%;
        width: 100%;
    }
    #jurnalPage .jrn-feed-body { padding: 13px 14px; }
    #jurnalPage .jrn-item { padding: 13px 14px; }
    #jurnalPage .jrn-item-footer {
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
    }
    #jurnalPage .jrn-item-actions {
        margin-left: 0;
        width: 100%;
    }
    #jurnalPage .jrn-item-actions .jrn-btn-detail {
        flex: 1;
    }
    #jurnalPage .jrn-hist-summary {
        padding: 11px 13px;
    }
    #jurnalPage .jrn-hist-card-head {
        padding: 12px 13px 10px;
    }
    #jurnalPage .jrn-hist-date-tile {
        width: 42px;
        min-height: 44px;
    }
    #jurnalPage .jrn-hist-date-day { font-size: 14.5px; }
    #jurnalPage .jrn-hist-title { font-size: 13.5px; }
    #jurnalPage .jrn-hist-card-body {
        padding: 12px 13px;
    }
    #jurnalPage .jrn-hist-content-wrap {
        gap: 11px;
    }
    #jurnalPage .jrn-hist-thumb-btn {
        width: 78px;
        height: 66px;
    }
    #jurnalPage .jrn-hist-card-foot {
        padding: 10px 13px;
        flex-direction: column;
        align-items: stretch;
        gap: 8px;
    }
    #jurnalPage .jrn-hist-foot-actions {
        margin-left: 0;
        width: 100%;
    }
    #jurnalPage .jrn-hist-foot-actions .jrn-btn {
        width: 100%;
    }
}
@media (max-width: 380px) {
    #jurnalPage .jrn-time-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div id="jurnalPage" class="container-xxl flex-grow-1 container-p-y">

    {{-- ============================================================
         Flash Messages & Validation Errors
    ============================================================ --}}
    @if (session('success'))
        <div class="jrn-alert jrn-alert-success alert-dismissible fade show" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"
                 class="jrn-alert-icon">
                <circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>
            </svg>
            <div class="jrn-alert-content"><strong>Berhasil!</strong> {{ session('success') }}</div>
            <button type="button" class="jrn-alert-close" data-bs-dismiss="alert" aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="jrn-alert jrn-alert-danger alert-dismissible fade show" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"
                 class="jrn-alert-icon">
                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/>
            </svg>
            <div class="jrn-alert-content"><strong>Error!</strong> {{ session('error') }}</div>
            <button type="button" class="jrn-alert-close" data-bs-dismiss="alert" aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="jrn-alert jrn-alert-danger alert-dismissible fade show" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"
                 class="jrn-alert-icon">
                <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>
            </svg>
            <div class="jrn-alert-content">
                <strong>Periksa isian jurnal:</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="jrn-alert-close" data-bs-dismiss="alert" aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- ============================================================
         1. Header & Ringkasan Statistik Jurnal
    ============================================================ --}}
    <div class="jrn-card">
        <div class="jrn-card-body">
            <div class="jrn-hero-top">
                <div class="jrn-hero-identity">
                    <div class="icon-3d-box icon-3d-box-lg" style="background-color:#eff6ff;border:1px solid #dbeafe;" aria-hidden="true">
                        {{-- 3D Journal Notebook & Pen --}}
                        <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <ellipse cx="30" cy="56" rx="20" ry="4" fill="#bfdbfe"/>
                            <rect x="13" y="12" width="34" height="41" rx="6" fill="#1e40af"/>
                            <rect x="15" y="10" width="34" height="40" rx="6" fill="#2563eb"/>
                            <rect x="15" y="10" width="8" height="40" rx="3" fill="#1d4ed8"/>
                            <rect x="26" y="16" width="18" height="7" rx="2.5" fill="#ffffff"/>
                            <rect x="26" y="27" width="17" height="3" rx="1.5" fill="#93c5fd"/>
                            <rect x="26" y="33" width="13" height="3" rx="1.5" fill="#bfdbfe"/>
                            <g transform="translate(36, 26) rotate(-18)">
                                <rect x="4" y="4" width="8" height="22" rx="3" fill="#b45309"/>
                                <rect x="3" y="3" width="8" height="22" rx="3" fill="#f59e0b"/>
                                <path d="M3 23h8l-4 7-4-7Z" fill="#fde68a"/>
                                <path d="M5.6 27.6 7 30l1.4-2.4H5.6Z" fill="#1e293b"/>
                            </g>
                        </svg>
                    </div>
                    <div class="jrn-hero-text">
                        <h4 class="jrn-hero-title">Jurnal PKL</h4>
                        <p class="jrn-hero-desc">Catat kegiatan harian, jam pelaksanaan, dan dokumentasi selama PKL untuk divalidasi.</p>
                    </div>
                </div>

                <div class="jrn-hero-actions">
                    <button type="button" class="jrn-btn jrn-btn-primary" id="btnTambahJurnal">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"/><path d="M12 5v14"/>
                        </svg>
                        <span id="btnTambahJurnalText">Tambah Jurnal</span>
                    </button>
                </div>
            </div>

            <div class="jrn-stats-grid">
                <div class="jrn-stat-chip">
                    <div class="jrn-stat-meta">
                        <p class="jrn-stat-label">Total Jurnal</p>
                        <p class="jrn-stat-val">{{ $totalCount }}</p>
                    </div>
                    <div class="jrn-stat-icon3d" style="background-color:#eff6ff;border-color:#dbeafe;" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="4" y="4" width="15" height="17" rx="3" fill="#1d4ed8"/>
                            <rect x="5" y="3" width="15" height="17" rx="3" fill="#3b82f6"/>
                            <rect x="8" y="7" width="9" height="2.2" rx="1.1" fill="#ffffff"/>
                            <rect x="8" y="11.5" width="7" height="2" rx="1" fill="#bfdbfe"/>
                        </svg>
                    </div>
                </div>

                <div class="jrn-stat-chip">
                    <div class="jrn-stat-meta">
                        <p class="jrn-stat-label">Menunggu</p>
                        <p class="jrn-stat-val">{{ $pendingCount }}</p>
                    </div>
                    <div class="jrn-stat-icon3d" style="background-color:#fffbeb;border-color:#fde68a;" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="13.2" r="8.5" fill="#b45309"/>
                            <circle cx="12" cy="11.2" r="8.5" fill="#f59e0b"/>
                            <circle cx="12" cy="11.2" r="6.7" stroke="#fde68a" stroke-width="1"/>
                            <path d="M12 7.8v3.6l2.4 1.4" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>

                <div class="jrn-stat-chip">
                    <div class="jrn-stat-meta">
                        <p class="jrn-stat-label">Ditolak</p>
                        <p class="jrn-stat-val">{{ $rejectedCount }}</p>
                    </div>
                    <div class="jrn-stat-icon3d" style="background-color:#fef2f2;border-color:#fecaca;" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="13.2" r="8.5" fill="#b91c1c"/>
                            <circle cx="12" cy="11.2" r="8.5" fill="#ef4444"/>
                            <circle cx="12" cy="11.2" r="6.7" stroke="#fca5a5" stroke-width="1"/>
                            <path d="m9.4 8.6 5.2 5.2m0-5.2-5.2 5.2" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>

                <div class="jrn-stat-chip">
                    <div class="jrn-stat-meta">
                        <p class="jrn-stat-label">Disetujui</p>
                        <p class="jrn-stat-val">{{ $approvedCount }}</p>
                    </div>
                    <div class="jrn-stat-icon3d" style="background-color:#f0fdf4;border-color:#bbf7d0;" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="13.2" r="8.5" fill="#15803d"/>
                            <circle cx="12" cy="11.2" r="8.5" fill="#22c55e"/>
                            <circle cx="12" cy="11.2" r="6.7" stroke="#86efac" stroke-width="1"/>
                            <path d="m8.8 11.4 2.1 2.1 4.3-4.3" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         2. Main Workspace: Form (Left Sticky / Mobile Toggle) + List (Right)
    ============================================================ --}}
    <div class="jrn-workspace">

        {{-- Left Column: Form Pengisian Jurnal --}}
        <div class="jrn-form-col">
            <div id="createFormPanel" class="jrn-card {{ $hasFormErrors ? 'is-mobile-open' : '' }}">
                <div class="jrn-card-header">
                    <div class="jrn-card-header-left">
                        <div class="icon-3d-box" style="background-color:#eff6ff;border:1px solid #dbeafe;" aria-hidden="true">
                            {{-- 3D Write / Plus Note Icon --}}
                            <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <ellipse cx="32" cy="55.5" rx="18" ry="3.5" fill="#bfdbfe"/>
                                <rect x="14" y="13" width="36" height="40" rx="8" fill="#1e40af"/>
                                <rect x="14" y="10" width="36" height="39" rx="8" fill="#ffffff" stroke="#bfdbfe" stroke-width="1.5"/>
                                <rect x="21" y="18" width="22" height="4" rx="2" fill="#2563eb"/>
                                <rect x="21" y="26" width="18" height="3.2" rx="1.6" fill="#93c5fd"/>
                                <rect x="21" y="33" width="14" height="3.2" rx="1.6" fill="#cbd5e1"/>
                                <circle cx="44" cy="41" r="10" fill="#1d4ed8"/>
                                <circle cx="44" cy="39" r="10" fill="#2563eb"/>
                                <path d="M40 39h8M44 35v8" stroke="#ffffff" stroke-width="2.6" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <div>
                            <h5 class="jrn-card-title">Isi Jurnal Harian</h5>
                            <p class="jrn-card-subtitle">Lengkapi kegiatan PKL hari ini atau sebelumnya</p>
                        </div>
                    </div>

                    <button type="button" class="jrn-btn jrn-btn-outline jrn-btn-sm jrn-mobile-close" id="btnCloseCreateForm" aria-label="Tutup form">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>

                <div class="jrn-card-body">
                    <form action="{{ route('jurnal.store') }}" method="POST" enctype="multipart/form-data" id="createJournalForm">
                        @csrf

                        <div class="jrn-form-group">
                            <label class="jrn-label" for="createTgl">
                                <span class="jrn-label-main">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                                    Tanggal Kegiatan <span class="jrn-req">*</span>
                                </span>
                            </label>
                            <input type="date"
                                   id="createTgl"
                                   name="tgl"
                                   class="jrn-input @error('tgl') is-invalid @enderror"
                                   value="{{ old('tgl', date('Y-m-d')) }}"
                                   max="{{ date('Y-m-d') }}"
                                   required>
                            <small class="jrn-field-hint">Hanya tanggal hari ini atau sebelumnya yang dapat dipilih.</small>
                            @error('tgl')
                                <span class="jrn-field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="jrn-form-group">
                            <div class="jrn-time-grid">
                                <div>
                                    <label class="jrn-label" for="createJamMulai">
                                        <span class="jrn-label-main">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                            Jam Mulai <span class="jrn-req">*</span>
                                        </span>
                                    </label>
                                    <input type="time"
                                           id="createJamMulai"
                                           name="jam_mulai"
                                           class="jrn-input @error('jam_mulai') is-invalid @enderror"
                                           value="{{ old('jam_mulai') }}"
                                           required>
                                    @error('jam_mulai')
                                        <span class="jrn-field-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label class="jrn-label" for="createJamSelesai">
                                        <span class="jrn-label-main">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                            Jam Selesai <span class="jrn-req">*</span>
                                        </span>
                                    </label>
                                    <input type="time"
                                           id="createJamSelesai"
                                           name="jam_selesai"
                                           class="jrn-input @error('jam_selesai') is-invalid @enderror"
                                           value="{{ old('jam_selesai') }}"
                                           required>
                                    @error('jam_selesai')
                                        <span class="jrn-field-error">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div id="createDurationPill" class="jrn-duration-pill" style="display:none;"></div>
                        </div>

                        <div class="jrn-form-group">
                            <label class="jrn-label" for="createUraian">
                                <span class="jrn-label-main">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/></svg>
                                    Uraian Kegiatan <span class="jrn-req">*</span>
                                </span>
                                <span id="createUraianCount" style="font-weight:600;color:#64748b;font-variant-numeric:tabular-nums;">0 / 1000</span>
                            </label>
                            <textarea id="createUraian"
                                      name="uraian"
                                      class="jrn-textarea @error('uraian') is-invalid @enderror"
                                      rows="4"
                                      maxlength="1000"
                                      placeholder="Jelaskan aktivitas PKL yang dikerjakan, langkah kerja, dan hasil yang dicapai..."
                                      required>{{ old('uraian') }}</textarea>
                            @error('uraian')
                                <span class="jrn-field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="jrn-form-group">
                            <label class="jrn-label" for="createFotoInput">
                                <span class="jrn-label-main">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                    Foto Dokumentasi
                                </span>
                                <span style="font-weight:500;color:#64748b;">Opsional</span>
                            </label>

                            <div class="jrn-upload-zone">
                                <input type="file" name="foto" accept="image/*" id="createFotoInput">
                                <div class="jrn-upload-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                                </div>
                                <div class="jrn-upload-copy">
                                    <span class="jrn-upload-title" id="createFotoLabel">Pilih foto kegiatan dari perangkat</span>
                                    <span class="jrn-upload-sub">Format JPG, PNG, GIF Â· Maks. 2MB</span>
                                </div>
                            </div>

                            <div class="jrn-photo-preview" id="createFotoPreview">
                                <img id="createFotoPreviewImg" src="" alt="Preview foto kegiatan">
                                <span class="jrn-photo-preview-name" id="createFotoPreviewName"></span>
                                <button type="button" class="jrn-btn jrn-btn-outline jrn-btn-sm" id="btnClearCreateFoto">Hapus</button>
                            </div>
                            @error('foto')
                                <span class="jrn-field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="jrn-check-stack">
                            <label class="jrn-check-card" for="is_pengetahuan_baru">
                                <input type="checkbox"
                                       name="is_pengetahuan_baru"
                                       id="is_pengetahuan_baru"
                                       value="1"
                                       {{ old('is_pengetahuan_baru') ? 'checked' : '' }}>
                                <div>
                                    <span class="jrn-check-title">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"/><path d="M9 18h6"/><path d="M10 22h4"/></svg>
                                        Termasuk pengetahuan baru
                                    </span>
                                    <span class="jrn-check-desc">Tandai jika mendapatkan materi atau keterampilan baru.</span>
                                </div>
                            </label>

                            <label class="jrn-check-card" for="is_dalam_mapel">
                                <input type="checkbox"
                                       name="is_dalam_mapel"
                                       id="is_dalam_mapel"
                                       value="1"
                                       {{ old('is_dalam_mapel') ? 'checked' : '' }}>
                                <div>
                                    <span class="jrn-check-title">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                                        Kegiatan ada dalam mapel sekolah
                                    </span>
                                    <span class="jrn-check-desc">Tandai jika sesuai mata pelajaran kompetensi kejuruan.</span>
                                </div>
                            </label>
                        </div>

                        <div class="jrn-form-footer">
                            <button type="button" class="jrn-btn jrn-btn-outline jrn-mobile-close" id="btnCancelCreateForm">Batal</button>
                            <button type="submit" class="jrn-btn jrn-btn-primary" id="btnCreateSubmit" style="flex:1;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                <span>Simpan Jurnal</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Right Column: Daftar & Riwayat Jurnal --}}
        <div class="jrn-feed-col">
            <div class="jrn-card">
                <div class="jrn-toolbar">
                    <div class="jrn-toolbar-top">
                        <ul class="nav jrn-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="jrn-tab-btn active" id="pending-tab" data-bs-toggle="tab"
                                        data-bs-target="#tab-jurnal" type="button" role="tab" aria-selected="true">
                                    <span>Menunggu Persetujuan</span>
                                    <span class="jrn-tab-count is-amber">{{ $activeJurnals->count() }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="jrn-tab-btn" id="history-tab" data-bs-toggle="tab"
                                        data-bs-target="#tab-riwayat" type="button" role="tab" aria-selected="false">
                                    <span>Riwayat</span>
                                    <span class="jrn-tab-count is-green">{{ $historyJurnals->count() }}</span>
                                </button>
                            </li>
                        </ul>

                        <div class="jrn-search-wrap">
                            <svg class="jrn-search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            <input type="text" id="jurnalSearchInput" class="jrn-search-input" placeholder="Cari tanggal atau uraian..." aria-label="Cari jurnal">
                            <button type="button" id="jurnalSearchClear" class="jrn-search-clear" aria-label="Bersihkan pencarian">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    @if ($activeJurnals->count() > 0 && $rejectedCount > 0)
                        <div class="jrn-filter-bar" id="activeStatusFilters">
                            <button type="button" class="jrn-filter-chip is-active" data-status-filter="all">Semua ({{ $activeJurnals->count() }})</button>
                            <button type="button" class="jrn-filter-chip" data-status-filter="pending">Menunggu ({{ $pendingCount }})</button>
                            <button type="button" class="jrn-filter-chip" data-status-filter="rejected">Ditolak ({{ $rejectedCount }})</button>
                        </div>
                    @endif
                </div>

                <div class="jrn-feed-body">
                    <div class="tab-content">

                        {{-- Tab 1: Jurnal Aktif (Pending & Rejected) --}}
                        <div class="tab-pane fade show active" id="tab-jurnal" role="tabpanel">
                            @if ($activeJurnals->count() > 0)
                                <div class="jrn-list" id="activeJournalList">
                                    @foreach ($activeJurnals as $jurnal)
                                        @php
                                            $tglText = \Carbon\Carbon::parse($jurnal->tgl)->locale('id')->isoFormat('dddd, D MMMM YYYY');
                                            $tglShort = \Carbon\Carbon::parse($jurnal->tgl)->locale('id')->isoFormat('D MMM YYYY');
                                            $searchHaystack = strtolower($tglText . ' ' . $jurnal->uraian . ' ' . $jurnal->status);
                                        @endphp
                                        <div class="jrn-item {{ $jurnal->status === 'rejected' ? 'is-rejected' : '' }}"
                                             data-jurnal-item
                                             data-status="{{ $jurnal->status }}"
                                             data-search="{{ e($searchHaystack) }}">

                                            <div class="jrn-item-head">
                                                <div class="jrn-item-date-wrap">
                                                    <div class="jrn-item-date">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                                                        <span>{{ $tglText }}</span>
                                                    </div>
                                                    <div class="jrn-item-submeta">
                                                        <span class="jrn-item-submeta-piece">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                            {{ $jurnal->jam_mulai }} - {{ $jurnal->jam_selesai }}
                                                        </span>
                                                        @if ($jurnal->created_at)
                                                            <span class="jrn-item-submeta-piece">
                                                                Diisi {{ $jurnal->created_at->locale('id')->isoFormat('D MMM, HH:mm') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div>
                                                    @if ($jurnal->status === 'rejected')
                                                        <span class="jrn-badge jrn-badge-rejected">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                                                            Ditolak
                                                        </span>
                                                    @else
                                                        <span class="jrn-badge jrn-badge-pending">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                            Menunggu Persetujuan
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <p class="jrn-item-uraian">{{ Str::limit($jurnal->uraian, 180) }}</p>

                                            @if ($jurnal->foto || $jurnal->is_pengetahuan_baru || $jurnal->is_dalam_mapel)
                                                <div class="jrn-item-tags">
                                                    @if ($jurnal->foto)
                                                        <button type="button"
                                                                class="jrn-tag jrn-tag-photo"
                                                                onclick="showImageModal('{{ asset($jurnal->foto) }}')">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                                            Dengan foto
                                                        </button>
                                                    @endif
                                                    @if ($jurnal->is_pengetahuan_baru)
                                                        <span class="jrn-tag jrn-tag-idea">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"/><path d="M9 18h6"/><path d="M10 22h4"/></svg>
                                                            Pengetahuan Baru
                                                        </span>
                                                    @endif
                                                    @if ($jurnal->is_dalam_mapel)
                                                        <span class="jrn-tag jrn-tag-mapel">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                                                            Dalam Mapel
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif

                                            @if ($jurnal->status === 'rejected' && $jurnal->rejected_reason)
                                                <div class="jrn-item-feedback">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                                                    <div><strong>Catatan:</strong> {{ $jurnal->rejected_reason }}</div>
                                                </div>
                                            @endif

                                            <div class="jrn-item-footer">
                                                <span class="jrn-item-approver">{{ $tglShort }}</span>
                                                <div class="jrn-item-actions">
                                                    <button type="button"
                                                            class="jrn-btn jrn-btn-detail jrn-btn-sm"
                                                            onclick="showJournalDetail({{ $jurnal->id }})"
                                                            title="Lihat Detail">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                                                        Detail
                                                    </button>
                                                    <button type="button"
                                                            class="jrn-btn jrn-btn-outline jrn-btn-sm"
                                                            onclick="alert('Fitur sedang diperbaiki, silakan coba lagi nanti.')"
                                                            title="Edit"
                                                            aria-label="Edit jurnal">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                                    </button>
                                                    <button type="button"
                                                            class="jrn-btn jrn-btn-danger-outline jrn-btn-sm"
                                                            onclick="confirmDelete({{ $jurnal->id }})"
                                                            title="Hapus"
                                                            aria-label="Hapus jurnal">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="jrn-empty">
                                    <div class="jrn-empty-illus" aria-hidden="true">
                                        <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <ellipse cx="32" cy="54" rx="18" ry="3.5" fill="#dbeafe"/>
                                            <rect x="13" y="13" width="38" height="37" rx="8" fill="#1e40af"/>
                                            <rect x="13" y="10" width="38" height="36" rx="8" fill="#ffffff" stroke="#bfdbfe" stroke-width="1.5"/>
                                            <rect x="20" y="18" width="24" height="4" rx="2" fill="#2563eb"/>
                                            <rect x="20" y="25" width="18" height="3" rx="1.5" fill="#93c5fd"/>
                                            <rect x="20" y="31" width="14" height="3" rx="1.5" fill="#cbd5e1"/>
                                        </svg>
                                    </div>
                                    <h6 class="jrn-empty-title">Belum Ada Jurnal Aktif</h6>
                                    <p class="jrn-empty-desc">Kamu belum memiliki jurnal yang menunggu persetujuan atau ditolak.</p>
                                    <button type="button" class="jrn-btn jrn-btn-primary jrn-btn-sm mt-3" id="btnEmptyCreate">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                        Tulis Jurnal Baru
                                    </button>
                                </div>
                            @endif
                        </div>

                        {{-- Tab 2: Riwayat (Approved) --}}
                        <div class="tab-pane fade" id="tab-riwayat" role="tabpanel">
                            @if ($historyJurnals->count() > 0)
                                @php
                                    $histPembimbingCount = $historyJurnals->where('validasi_pembimbing', 'sudah')->count();
                                    $histIdukaCount      = $historyJurnals->where('validasi_iduka', 'sudah')->count();
                                    $histFotoCount       = $historyJurnals->whereNotNull('foto')->count();
                                @endphp

                                <div class="jrn-hist-summary">
                                    <div class="jrn-hist-summary-left">
                                        <div class="jrn-hist-summary-icon" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="12" cy="13.2" r="8.5" fill="#15803d"/>
                                                <circle cx="12" cy="11.2" r="8.5" fill="#22c55e"/>
                                                <circle cx="12" cy="11.2" r="6.7" stroke="#86efac" stroke-width="1"/>
                                                <path d="m8.8 11.4 2.1 2.1 4.3-4.3" stroke="#ffffff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="jrn-hist-summary-title">{{ $historyJurnals->count() }} Jurnal Telah Disetujui</p>
                                            <p class="jrn-hist-summary-sub">Arsip kegiatan PKL terverifikasi</p>
                                        </div>
                                    </div>

                                    <div class="jrn-hist-summary-pills">
                                        @if ($histPembimbingCount > 0)
                                            <span class="jrn-hist-stat-pill">Pembimbing: {{ $histPembimbingCount }}</span>
                                        @endif
                                        @if ($histIdukaCount > 0)
                                            <span class="jrn-hist-stat-pill">IDUKA: {{ $histIdukaCount }}</span>
                                        @endif
                                        @if ($histFotoCount > 0)
                                            <span class="jrn-hist-stat-pill">Foto: {{ $histFotoCount }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="jrn-hist-list" id="historyJournalList">
                                    @foreach ($historyJurnals as $jurnal)
                                        @php
                                            $tglCarbon   = \Carbon\Carbon::parse($jurnal->tgl)->locale('id');
                                            $tglFull     = $tglCarbon->isoFormat('dddd, D MMMM YYYY');
                                            $tglTitle    = $tglCarbon->isoFormat('D MMMM YYYY');
                                            $dayNum      = $tglCarbon->format('d');
                                            $monthShort  = $tglCarbon->isoFormat('MMM');

                                            $startTs = $jurnal->jam_mulai ? strtotime($jurnal->jam_mulai) : false;
                                            $endTs   = $jurnal->jam_selesai ? strtotime($jurnal->jam_selesai) : false;
                                            $jamDisplay = ($startTs !== false ? date('H:i', $startTs) : $jurnal->jam_mulai)
                                                . ' - '
                                                . ($endTs !== false ? date('H:i', $endTs) : $jurnal->jam_selesai);

                                            $durationText = null;
                                            if ($startTs !== false && $endTs !== false && $endTs > $startTs) {
                                                $mins = (int) round(($endTs - $startTs) / 60);
                                                $hrs  = intdiv($mins, 60);
                                                $rem  = $mins % 60;
                                                $durationText = trim(($hrs > 0 ? $hrs . ' jam ' : '') . ($rem > 0 ? $rem . ' mnt' : ''));
                                            }

                                            $searchHaystack = strtolower($tglFull . ' ' . $jurnal->uraian . ' ' . ($jurnal->approved_by ?? ''));
                                        @endphp
                                        <article class="jrn-hist-card"
                                                 data-jurnal-item
                                                 data-status="approved"
                                                 data-search="{{ e($searchHaystack) }}">

                                            <div class="jrn-hist-card-head">
                                                <div class="jrn-hist-head-left">
                                                    <div class="jrn-hist-date-tile" aria-hidden="true">
                                                        <span class="jrn-hist-date-day">{{ $dayNum }}</span>
                                                        <span class="jrn-hist-date-mon">{{ $monthShort }}</span>
                                                    </div>
                                                    <div class="jrn-hist-title-block">
                                                        <h6 class="jrn-hist-title">Kegiatan Harian - {{ $tglTitle }}</h6>
                                                        <div class="jrn-hist-meta">
                                                            <span class="jrn-hist-meta-item">
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                                                {{ $jamDisplay }}
                                                            </span>
                                                            @if ($durationText)
                                                                <span class="jrn-hist-duration">{{ $durationText }}</span>
                                                            @endif
                                                            @if ($jurnal->created_at)
                                                                <span class="jrn-hist-meta-item">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                                                                    Diisi {{ $jurnal->created_at->locale('id')->isoFormat('D MMM, HH:mm') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div>
                                                    <span class="jrn-hist-status">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z"/><path d="m9 12 2 2 4-4"/></svg>
                                                        Disetujui
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="jrn-hist-card-body">
                                                <div class="jrn-hist-content-wrap">
                                                    <div class="jrn-hist-desc-col">
                                                        <p class="jrn-hist-uraian">{{ Str::limit($jurnal->uraian, 220) }}</p>

                                                        @if ($jurnal->is_pengetahuan_baru || $jurnal->is_dalam_mapel)
                                                            <div class="jrn-hist-tags">
                                                                @if ($jurnal->is_pengetahuan_baru)
                                                                    <span class="jrn-tag jrn-tag-idea">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"/><path d="M9 18h6"/><path d="M10 22h4"/></svg>
                                                                        Pengetahuan Baru
                                                                    </span>
                                                                @endif
                                                                @if ($jurnal->is_dalam_mapel)
                                                                    <span class="jrn-tag jrn-tag-mapel">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                                                                        Dalam Mapel
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>

                                                    @if ($jurnal->foto)
                                                        <button type="button"
                                                                class="jrn-hist-thumb-btn"
                                                                onclick="showImageModal('{{ asset($jurnal->foto) }}')"
                                                                title="Perbesar foto kegiatan"
                                                                aria-label="Perbesar foto kegiatan {{ $tglTitle }}">
                                                            <img src="{{ asset($jurnal->foto) }}" alt="Dokumentasi {{ $tglTitle }}" loading="lazy">
                                                            <span class="jrn-hist-thumb-badge" aria-hidden="true">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" x2="14" y1="3" y2="10"/><line x1="3" x2="10" y1="21" y2="14"/></svg>
                                                            </span>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="jrn-hist-card-foot">
                                                <div class="jrn-hist-verifiers">
                                                    @if ($jurnal->validasi_pembimbing === 'sudah')
                                                        <span class="jrn-verifier-chip">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                                            Pembimbing
                                                            @if ($jurnal->approved_pembimbing_at)
                                                                <span class="jrn-verifier-date">Â· {{ $jurnal->approved_pembimbing_at->locale('id')->isoFormat('D MMM') }}</span>
                                                            @endif
                                                        </span>
                                                    @endif
                                                    @if ($jurnal->validasi_iduka === 'sudah')
                                                        <span class="jrn-verifier-chip">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                                            IDUKA
                                                            @if ($jurnal->approved_iduka_at)
                                                                <span class="jrn-verifier-date">Â· {{ $jurnal->approved_iduka_at->locale('id')->isoFormat('D MMM') }}</span>
                                                            @endif
                                                        </span>
                                                    @endif
                                                    @if ($jurnal->validasi_pembimbing !== 'sudah' && $jurnal->validasi_iduka !== 'sudah')
                                                        <span class="jrn-verifier-chip">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                                            {{ $jurnal->approved_by ?? 'Terverifikasi' }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="jrn-hist-foot-actions">
                                                    <button type="button"
                                                            class="jrn-btn jrn-btn-detail jrn-btn-sm"
                                                            onclick="showJournalDetail({{ $jurnal->id }})"
                                                            title="Lihat Detail">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                                                        <span>Lihat Detail</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            @else
                                <div class="jrn-empty">
                                    <div class="jrn-empty-illus" style="background-color:#f0fdf4;border-color:#bbf7d0;" aria-hidden="true">
                                        <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <ellipse cx="32" cy="54" rx="18" ry="3.5" fill="#bbf7d0"/>
                                            <rect x="13" y="13" width="38" height="37" rx="8" fill="#15803d"/>
                                            <rect x="13" y="10" width="38" height="36" rx="8" fill="#22c55e"/>
                                            <circle cx="32" cy="28" r="10.5" fill="#ffffff"/>
                                            <path d="m27.5 28.2 3 3 6.2-6.2" stroke="#16a34a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                    <h6 class="jrn-empty-title">Belum Ada Riwayat Disetujui</h6>
                                    <p class="jrn-empty-desc">Jurnal yang sudah divalidasi oleh pembimbing atau IDUKA akan tersimpan rapi di sini.</p>
                                    <button type="button" class="jrn-btn jrn-btn-outline jrn-btn-sm mt-3" id="btnGoToPendingTab">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                                        Lihat Jurnal Menunggu
                                    </button>
                                </div>
                            @endif
                        </div>

                    </div>

                    <div id="jurnalSearchEmpty" class="jrn-empty mt-2" style="display: none;">
                        <h6 class="jrn-empty-title">Tidak Ada Hasil</h6>
                        <p class="jrn-empty-desc">Tidak ditemukan jurnal yang sesuai dengan filter atau kata kunci pencarian.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     Modals: View Detail, Image Lightbox, Edit, Delete
============================================================ --}}

{{-- View Journal Modal --}}
<div class="modal fade jrn-modal" id="viewJournalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span class="jrn-modal-title-icon" style="background-color:#eff6ff;border-color:#bfdbfe;color:#2563eb;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </span>
                    Detail Jurnal
                </h5>
                <button type="button" class="jrn-modal-close" data-bs-dismiss="modal" aria-label="Tutup">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            <div class="modal-body" id="journalDetailContent"></div>
        </div>
    </div>
</div>

{{-- Image Lightbox Modal --}}
<div class="modal fade jrn-modal" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span class="jrn-modal-title-icon" style="background-color:#f0f9ff;border-color:#bae6fd;color:#0284c7;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                    </span>
                    Foto Kegiatan
                </h5>
                <button type="button" class="jrn-modal-close" data-bs-dismiss="modal" aria-label="Tutup">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            <div class="modal-body text-center" style="background-color:#f8fafc;">
                <img id="modalImage" src="" alt="Foto Kegiatan" class="img-fluid rounded" style="max-height:70vh;">
            </div>
        </div>
    </div>
</div>

{{-- Edit Journal Modal --}}
<div class="modal fade jrn-modal" id="editJournalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span class="jrn-modal-title-icon" style="background-color:#fffbeb;border-color:#fde68a;color:#d97706;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                    </span>
                    Edit Jurnal
                </h5>
                <button type="button" class="jrn-modal-close" data-bs-dismiss="modal" aria-label="Tutup">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            <form id="editJournalForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body" id="editJournalContent">
                    <div class="jrn-modal-loading">
                        <div class="jrn-modal-spinner"></div>
                        <p class="mb-0" style="font-size:13px;color:#64748b;">Memuat data jurnal...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="jrn-btn jrn-btn-outline" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="jrn-btn jrn-btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Update Jurnal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade jrn-modal" id="deleteJournalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <span class="jrn-modal-title-icon" style="background-color:#fef2f2;border-color:#fecaca;color:#dc2626;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                    </span>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="jrn-modal-close" data-bs-dismiss="modal" aria-label="Tutup">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            <div class="modal-body text-center" style="padding:24px 20px 18px;">
                <div style="width:62px;height:62px;border-radius:16px;background-color:#fef2f2;border:1px solid #fecaca;display:inline-flex;align-items:center;justify-content:center;margin-bottom:14px;" aria-hidden="true">
                    {{-- 3D Danger Trash Alert --}}
                    <svg width="42" height="42" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="32" cy="56" rx="16" ry="3.5" fill="#fecaca"/>
                        <path d="M18 22h28l-2.6 26.5A4.5 4.5 0 0 1 39 52.5H25a4.5 4.5 0 0 1-4.4-4L18 22Z" transform="translate(0,2.5)" fill="#b91c1c"/>
                        <path d="M18 20h28l-2.6 26.5A4.5 4.5 0 0 1 39 50.5H25a4.5 4.5 0 0 1-4.4-4L18 20Z" fill="#ef4444"/>
                        <rect x="14" y="15" width="36" height="5" rx="2.5" fill="#dc2626"/>
                        <rect x="25" y="10" width="14" height="5" rx="2.5" fill="#991b1b"/>
                        <path d="M27 28v14M37 28v14" stroke="#ffffff" stroke-width="3.2" stroke-linecap="round"/>
                    </svg>
                </div>
                <h6 style="font-size:15.5px;font-weight:700;color:#0f172a;margin:0 0 6px;">Hapus jurnal ini?</h6>
                <p style="font-size:13px;color:#64748b;margin:0;line-height:1.5;">
                    Data jurnal beserta dokumentasinya akan dihapus secara permanen dan tidak dapat dikembalikan.
                </p>
            </div>
            <div class="modal-footer" style="justify-content:center;">
                <button type="button" class="jrn-btn jrn-btn-outline" data-bs-dismiss="modal" style="flex:1;">Batal</button>
                <form id="deleteJournalForm" method="POST" style="flex:1;margin:0;display:flex;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="btnConfirmDelete" class="jrn-btn jrn-btn-danger" style="width:100%;">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.jQuery && $.ajaxSetup) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        });
    }

    // ---- Responsive Form Panel Toggle (Mobile/Tablet) & Desktop Focus ----
    const createFormPanel = document.getElementById('createFormPanel');
    const btnTambahJurnal = document.getElementById('btnTambahJurnal');
    const btnTambahJurnalText = document.getElementById('btnTambahJurnalText');
    const btnCloseCreateForm = document.getElementById('btnCloseCreateForm');
    const btnCancelCreateForm = document.getElementById('btnCancelCreateForm');
    const btnEmptyCreate = document.getElementById('btnEmptyCreate');
    const createUraian = document.getElementById('createUraian');

    function syncMobileToggleLabel() {
        if (!btnTambahJurnalText || !createFormPanel) return;
        const isMobile = window.innerWidth < 992;
        if (isMobile && createFormPanel.classList.contains('is-mobile-open')) {
            btnTambahJurnalText.textContent = 'Tutup Form';
        } else {
            btnTambahJurnalText.textContent = 'Tambah Jurnal';
        }
    }

    function openOrFocusCreateForm() {
        if (!createFormPanel) return;
        if (window.innerWidth < 992) {
            createFormPanel.classList.add('is-mobile-open');
            syncMobileToggleLabel();
            createFormPanel.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } else {
            createFormPanel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
        setTimeout(() => {
            createUraian?.focus();
        }, 200);
    }

    if (btnTambahJurnal) {
        btnTambahJurnal.addEventListener('click', function() {
            if (window.innerWidth < 992) {
                const isOpen = createFormPanel.classList.contains('is-mobile-open');
                if (isOpen) {
                    createFormPanel.classList.remove('is-mobile-open');
                    syncMobileToggleLabel();
                } else {
                    openOrFocusCreateForm();
                }
            } else {
                openOrFocusCreateForm();
            }
        });
    }

    [btnCloseCreateForm, btnCancelCreateForm].forEach(btn => {
        if (btn) {
            btn.addEventListener('click', function() {
                createFormPanel.classList.remove('is-mobile-open');
                syncMobileToggleLabel();
            });
        }
    });

    if (btnEmptyCreate) {
        btnEmptyCreate.addEventListener('click', openOrFocusCreateForm);
    }

    window.addEventListener('resize', syncMobileToggleLabel);
    syncMobileToggleLabel();

    // ---- Uraian Character Counter ----
    const createUraianCount = document.getElementById('createUraianCount');
    function updateUraianCount() {
        if (!createUraian || !createUraianCount) return;
        const len = createUraian.value.length;
        createUraianCount.textContent = `${len} / 1000`;
        createUraianCount.style.color = len >= 950 ? '#dc2626' : '#64748b';
    }
    if (createUraian) {
        createUraian.addEventListener('input', updateUraianCount);
        updateUraianCount();
    }

    // ---- Time Duration Helper ----
    const createJamMulai = document.getElementById('createJamMulai');
    const createJamSelesai = document.getElementById('createJamSelesai');
    const createDurationPill = document.getElementById('createDurationPill');

    function updateDurationHint() {
        if (!createJamMulai || !createJamSelesai || !createDurationPill) return;
        const start = createJamMulai.value;
        const end = createJamSelesai.value;

        if (!start || !end) {
            createDurationPill.style.display = 'none';
            return;
        }

        const [sh, sm] = start.split(':').map(Number);
        const [eh, em] = end.split(':').map(Number);
        const diffMinutes = (eh * 60 + em) - (sh * 60 + sm);

        createDurationPill.style.display = 'inline-flex';
        if (diffMinutes <= 0) {
            createDurationPill.className = 'jrn-duration-pill is-error';
            createDurationPill.textContent = 'Jam selesai harus lebih besar dari jam mulai';
        } else {
            const hrs = Math.floor(diffMinutes / 60);
            const mins = diffMinutes % 60;
            const parts = [];
            if (hrs > 0) parts.push(`${hrs} jam`);
            if (mins > 0) parts.push(`${mins} menit`);
            createDurationPill.className = 'jrn-duration-pill is-valid';
            createDurationPill.textContent = `Durasi: ${parts.join(' ')}`;
        }
    }

    createJamMulai?.addEventListener('change', updateDurationHint);
    createJamSelesai?.addEventListener('change', updateDurationHint);
    updateDurationHint();

    // ---- Create Foto Preview ----
    const createFotoInput = document.getElementById('createFotoInput');
    const createFotoLabel = document.getElementById('createFotoLabel');
    const createFotoPreview = document.getElementById('createFotoPreview');
    const createFotoPreviewImg = document.getElementById('createFotoPreviewImg');
    const createFotoPreviewName = document.getElementById('createFotoPreviewName');
    const btnClearCreateFoto = document.getElementById('btnClearCreateFoto');

    if (createFotoInput) {
        createFotoInput.addEventListener('change', function() {
            const file = this.files && this.files[0];
            if (!file) {
                createFotoPreview.classList.remove('is-visible');
                createFotoLabel.textContent = 'Pilih foto kegiatan dari perangkat';
                return;
            }
            createFotoLabel.textContent = file.name;
            createFotoPreviewName.textContent = file.name;

            const reader = new FileReader();
            reader.onload = function(evt) {
                createFotoPreviewImg.src = evt.target.result;
                createFotoPreview.classList.add('is-visible');
            };
            reader.readAsDataURL(file);
        });
    }

    if (btnClearCreateFoto && createFotoInput) {
        btnClearCreateFoto.addEventListener('click', function() {
            createFotoInput.value = '';
            createFotoPreviewImg.src = '';
            createFotoPreview.classList.remove('is-visible');
            createFotoLabel.textContent = 'Pilih foto kegiatan dari perangkat';
        });
    }

    // ---- Validasi & Loading State Form Create ----
    const createForm = document.getElementById('createJournalForm');
    const btnCreateSubmit = document.getElementById('btnCreateSubmit');
    if (createForm) {
        createForm.addEventListener('submit', function(e) {
            const jamMulai = this.querySelector('input[name="jam_mulai"]').value;
            const jamSelesai = this.querySelector('input[name="jam_selesai"]').value;

            if (jamMulai && jamSelesai && jamSelesai <= jamMulai) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: 'Jam selesai harus lebih dari jam mulai.',
                    confirmButtonColor: '#2563eb'
                });
                return false;
            }

            if (btnCreateSubmit) {
                btnCreateSubmit.disabled = true;
                btnCreateSubmit.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span>Menyimpan...</span>';
            }
        });
    }

    // ---- Search & Status Filter (Client-Side over Rendered Items) ----
    const searchInput = document.getElementById('jurnalSearchInput');
    const searchClear = document.getElementById('jurnalSearchClear');
    const searchEmpty = document.getElementById('jurnalSearchEmpty');
    const statusChips = document.querySelectorAll('#activeStatusFilters .jrn-filter-chip');
    let currentStatusFilter = 'all';

    function applyFeedFilters() {
        const query = (searchInput?.value || '').trim().toLowerCase();
        const activePane = document.querySelector('#jurnalPage .tab-pane.active');
        if (!activePane) return;

        if (searchClear) {
            searchClear.classList.toggle('is-visible', query.length > 0);
        }

        const isActiveTab = activePane.id === 'tab-jurnal';
        const items = activePane.querySelectorAll('[data-jurnal-item]');
        let visibleCount = 0;

        items.forEach(item => {
            const haystack = item.getAttribute('data-search') || '';
            const status = item.getAttribute('data-status') || '';
            const matchesQuery = query === '' || haystack.includes(query);
            const matchesStatus = !isActiveTab || currentStatusFilter === 'all' || status === currentStatusFilter;

            const show = matchesQuery && matchesStatus;
            item.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        if (searchEmpty) {
            const hasItemsInTab = items.length > 0;
            searchEmpty.style.display = (hasItemsInTab && visibleCount === 0) ? 'flex' : 'none';
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', applyFeedFilters);
    }
    if (searchClear && searchInput) {
        searchClear.addEventListener('click', function() {
            searchInput.value = '';
            applyFeedFilters();
            searchInput.focus();
        });
    }
    statusChips.forEach(chip => {
        chip.addEventListener('click', function() {
            statusChips.forEach(c => c.classList.remove('is-active'));
            this.classList.add('is-active');
            currentStatusFilter = this.getAttribute('data-status-filter') || 'all';
            applyFeedFilters();
        });
    });

    // ---- Show Image Lightbox Modal ----
    window.showImageModal = function(src) {
        const modalEl = document.getElementById('imageModal');
        const imgEl = document.getElementById('modalImage');
        if (!modalEl || !imgEl) return;
        imgEl.src = src;
        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    };

    // ---- Show Journal Detail Modal ----
    window.showJournalDetail = function(id) {
        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('viewJournalModal'));
        const modalBody = document.getElementById('journalDetailContent');

        modalBody.innerHTML = `
            <div class="jrn-modal-loading">
                <div class="jrn-modal-spinner"></div>
                <p class="mb-0" style="font-size:13px;color:#64748b;">Memuat detail jurnal...</p>
            </div>
        `;

        modal.show();

        fetch(`/jurnal/${id}/show`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modalBody.innerHTML = data.data;
                } else {
                    throw new Error(data.message || 'Gagal memuat detail jurnal');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                modalBody.innerHTML = `
                    <div class="jrn-alert jrn-alert-danger mb-0">
                        <div class="jrn-alert-content">
                            <strong>Gagal memuat detail:</strong> <span class="jrn-err-msg"></span>
                            <div class="mt-2">
                                <button type="button" onclick="showJournalDetail(${id})" class="jrn-btn jrn-btn-danger-outline jrn-btn-sm">
                                    Coba Lagi
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                const errSpan = modalBody.querySelector('.jrn-err-msg');
                if (errSpan) errSpan.textContent = error.message || 'Terjadi kesalahan.';
            });
    };

    // ---- Edit Journal Modal ----
    window.editJournal = function(id) {
        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editJournalModal'));
        const modalBody = document.getElementById('editJournalContent');
        const form = document.getElementById('editJournalForm');

        modalBody.innerHTML = `
            <div class="jrn-modal-loading">
                <div class="jrn-modal-spinner"></div>
                <p class="mb-0" style="font-size:13px;color:#64748b;">Memuat data jurnal...</p>
            </div>
        `;

        form.action = `/jurnal/${id}`;
        modal.show();

        fetch(`/jurnal/${id}/edit`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            })
            .then(response => response.text())
            .then(html => {
                modalBody.innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                modalBody.innerHTML = `
                    <div class="jrn-alert jrn-alert-danger mb-0">
                        <div class="jrn-alert-content">
                            Gagal memuat data jurnal. Silakan coba lagi.
                            <div class="mt-2">
                                <button type="button" onclick="editJournal(${id})" class="jrn-btn jrn-btn-danger-outline jrn-btn-sm">
                                    Coba Lagi
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
    };

    // ---- AJAX Submit Edit Form ----
    const editForm = document.getElementById('editJournalForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const jamMulai = this.querySelector('input[name="jam_mulai"]')?.value;
            const jamSelesai = this.querySelector('input[name="jam_selesai"]')?.value;

            if (jamMulai && jamSelesai && jamSelesai <= jamMulai) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: 'Jam selesai harus lebih dari jam mulai.',
                    confirmButtonColor: '#2563eb'
                });
                return false;
            }

            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;

            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Menyimpan...';

            fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    }
                })
                .then(response => {
                    if (response.redirected) {
                        return { success: true, redirect: response.url };
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success || data.redirect) {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editJournalModal'));
                        modal?.hide();

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Jurnal berhasil diperbarui.',
                            confirmButtonColor: '#2563eb',
                            timer: 1800,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        let errorMessage = 'Terjadi kesalahan saat memperbarui jurnal.';
                        if (data.message) {
                            errorMessage = data.message;
                        } else if (data.errors) {
                            errorMessage = Object.values(data.errors)[0][0];
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMessage,
                            confirmButtonColor: '#2563eb'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan jaringan. Silakan coba lagi.',
                        confirmButtonColor: '#2563eb'
                    });
                })
                .finally(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                });
        });
    }

    // ---- Delete Confirmation Modal ----
    window.confirmDelete = function(id) {
        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteJournalModal'));
        const form = document.getElementById('deleteJournalForm');
        form.action = `/jurnal/${id}`;
        modal.show();
    };

    const deleteForm = document.getElementById('deleteJournalForm');
    const btnConfirmDelete = document.getElementById('btnConfirmDelete');
    if (deleteForm && btnConfirmDelete) {
        deleteForm.addEventListener('submit', function() {
            btnConfirmDelete.disabled = true;
            btnConfirmDelete.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Menghapus...';
        });
    }

    // ---- Tab Hash Persistence & Filter Sync ----
    const filterBar = document.getElementById('activeStatusFilters');
    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tabButton => {
        tabButton.addEventListener('shown.bs.tab', function(e) {
            const target = e.target.getAttribute('data-bs-target');
            if (target) {
                history.pushState(null, null, target);
            }
            if (filterBar) {
                filterBar.style.display = (target === '#tab-jurnal') ? 'flex' : 'none';
            }
            applyFeedFilters();
        });
    });

    const btnGoToPendingTab = document.getElementById('btnGoToPendingTab');
    if (btnGoToPendingTab) {
        btnGoToPendingTab.addEventListener('click', function() {
            const pendingTab = document.getElementById('pending-tab');
            if (pendingTab) {
                bootstrap.Tab.getOrCreateInstance(pendingTab).show();
            }
        });
    }

    const hash = window.location.hash;
    if (hash) {
        const tabTrigger = document.querySelector(`button[data-bs-target="${hash}"]`);
        if (tabTrigger) {
            bootstrap.Tab.getOrCreateInstance(tabTrigger).show();
        }
    }
});
</script>
@endsection
