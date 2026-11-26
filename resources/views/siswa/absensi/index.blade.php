@extends('layout.main')

@section('content')
<style>
#absensiPage {
    --abs-bg: #ffffff;
    --abs-muted-bg: #f8fafc;
    --abs-border: #e2e8f0;
    --abs-border-strong: #cbd5e1;
    --abs-text: #0f172a;
    --abs-text-muted: #64748b;
    --abs-text-soft: #94a3b8;
    --abs-primary: #2563eb;
    --abs-radius: 16px;
    --abs-radius-md: 12px;
    --abs-radius-sm: 9px;
    --abs-shadow: 0 1px 3px rgba(15, 23, 42, 0.05), 0 1px 2px rgba(15, 23, 42, 0.03);
}

/* ---- Card Primitive ---- */
#absensiPage .ui-card {
    background-color: var(--abs-bg);
    border: 1px solid var(--abs-border);
    border-radius: var(--abs-radius);
    box-shadow: var(--abs-shadow);
    margin-bottom: 16px;
    overflow: hidden;
}
#absensiPage .ui-card-header {
    padding: 15px 20px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    background-color: #ffffff;
}
#absensiPage .ui-card-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1 1 220px;
    min-width: 0;
}
#absensiPage .ui-card-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--abs-text);
    margin: 0;
    letter-spacing: -0.015em;
    line-height: 1.3;
}
#absensiPage .ui-card-subtitle {
    font-size: 12.5px;
    color: var(--abs-text-muted);
    margin: 2px 0 0;
    line-height: 1.4;
}
#absensiPage .ui-card-body {
    padding: 18px 20px;
}

/* ---- 3D Icon Wrapper ---- */
#absensiPage .icon-3d-box {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
#absensiPage .icon-3d-box svg {
    width: 40px;
    height: 40px;
    display: block;
}
#absensiPage .icon-3d-box-lg {
    width: 54px;
    height: 54px;
    border-radius: 14px;
}
#absensiPage .icon-3d-box-lg svg {
    width: 48px;
    height: 48px;
}

/* ---- Flash & Status Alerts ---- */
#absensiPage .ui-alert {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 13px 16px;
    border-radius: 12px;
    border: 1px solid;
    margin-bottom: 14px;
    font-size: 13.5px;
    line-height: 1.45;
}
#absensiPage .ui-alert-icon {
    flex-shrink: 0;
    margin-top: 1px;
}
#absensiPage .ui-alert-content {
    flex: 1;
    min-width: 0;
}
#absensiPage .ui-alert-close {
    background: transparent;
    border: none;
    padding: 2px;
    cursor: pointer;
    opacity: 0.65;
    color: inherit;
    line-height: 1;
}
#absensiPage .ui-alert-close:hover { opacity: 1; }

#absensiPage .ui-alert-success { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }
#absensiPage .ui-alert-danger  { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
#absensiPage .ui-alert-warning { background: #fffbeb; border-color: #fde68a; color: #92400e; }
#absensiPage .ui-alert-info    { background: #eff6ff; border-color: #bfdbfe; color: #1e40af; }

/* Ensure dynamically inserted .alert in .card-body looks clean */
#absensiPage .alert {
    border-radius: 11px;
    font-size: 13px;
    border-width: 1px;
}

/* ---- Hero Header + IDUKA Card ---- */
#absensiPage .abs-hero-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 14px 18px;
    flex-wrap: wrap;
}
#absensiPage .abs-hero-identity {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    flex: 1 1 360px;
    min-width: 0;
}
#absensiPage .abs-hero-text {
    flex: 1;
    min-width: 0;
    padding-top: 1px;
}
#absensiPage .abs-hero-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--abs-text);
    margin: 0;
    letter-spacing: -0.02em;
    line-height: 1.3;
}
#absensiPage .abs-hero-desc {
    font-size: 13px;
    color: var(--abs-text-muted);
    margin: 4px 0 0;
    line-height: 1.5;
    overflow-wrap: break-word;
    word-break: normal;
}
#absensiPage .abs-live-clock-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    flex: 0 0 auto;
    max-width: 100%;
}
#absensiPage .abs-clock-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 6px 12px;
    border-radius: 9999px;
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    font-size: 12px;
    font-weight: 600;
    color: #334155;
    font-variant-numeric: tabular-nums;
    white-space: nowrap;
}
#absensiPage .abs-clock-pill svg {
    color: #2563eb;
    flex-shrink: 0;
}

/* IDUKA Info Grid */
#absensiPage .abs-iduka-panel {
    margin-top: 16px;
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 13px;
    padding: 14px 16px;
}
#absensiPage .abs-iduka-head {
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e2e8f0;
    margin-bottom: 12px;
}
#absensiPage .abs-iduka-meta-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    flex-wrap: wrap;
    width: 100%;
}
#absensiPage .abs-iduka-label {
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: #64748b;
    margin: 0;
}
#absensiPage .abs-iduka-name {
    width: 100%;
    font-size: 14.5px;
    font-weight: 700;
    color: #0f172a;
    margin: 2px 0 0;
    line-height: 1.35;
    overflow-wrap: break-word;
    word-break: normal;
}
#absensiPage .abs-iduka-address {
    width: 100%;
    display: flex;
    align-items: flex-start;
    gap: 6px;
    font-size: 12.5px;
    color: #64748b;
    margin-top: 2px;
    line-height: 1.45;
}
#absensiPage .abs-iduka-address span {
    flex: 1;
    min-width: 0;
    overflow-wrap: break-word;
    word-break: normal;
}
#absensiPage .abs-iduka-address svg {
    flex-shrink: 0;
    margin-top: 2px;
    color: #94a3b8;
}
#absensiPage .abs-iduka-metrics {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 10px;
}
#absensiPage .abs-metric-chip {
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 9px 11px;
    display: flex;
    flex-direction: column;
    gap: 2px;
}
#absensiPage .abs-metric-label {
    font-size: 11px;
    font-weight: 600;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 4px;
}
#absensiPage .abs-metric-label svg {
    color: #3b82f6;
    flex-shrink: 0;
}
#absensiPage .abs-metric-val {
    font-size: 13.5px;
    font-weight: 700;
    color: #0f172a;
    font-variant-numeric: tabular-nums;
}

/* ---- Status Badges ---- */
#absensiPage .ui-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11.5px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 9999px;
    border: 1px solid;
    white-space: nowrap;
    line-height: 1.3;
}
#absensiPage .ui-badge-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    flex-shrink: 0;
}
#absensiPage .ui-badge-success  { background: #f0fdf4; border-color: #bbf7d0; color: #15803d; }
#absensiPage .ui-badge-success .ui-badge-dot  { background: #22c55e; }
#absensiPage .ui-badge-warning  { background: #fffbeb; border-color: #fde68a; color: #b45309; }
#absensiPage .ui-badge-warning .ui-badge-dot  { background: #f59e0b; }
#absensiPage .ui-badge-danger   { background: #fef2f2; border-color: #fecaca; color: #b91c1c; }
#absensiPage .ui-badge-danger .ui-badge-dot   { background: #ef4444; }
#absensiPage .ui-badge-info     { background: #ecfeff; border-color: #a5f3fc; color: #0e7490; }
#absensiPage .ui-badge-info .ui-badge-dot     { background: #06b6d4; }
#absensiPage .ui-badge-primary  { background: #eff6ff; border-color: #bfdbfe; color: #1d4ed8; }
#absensiPage .ui-badge-primary .ui-badge-dot  { background: #3b82f6; }
#absensiPage .ui-badge-secondary{ background: #f8fafc; border-color: #e2e8f0; color: #475569; }
#absensiPage .ui-badge-secondary .ui-badge-dot{ background: #94a3b8; }

/* ---- Today Status / Pending Grid ---- */
#absensiPage .abs-today-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
}
#absensiPage .abs-today-box {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 13px 15px;
    display: flex;
    align-items: center;
    gap: 12px;
}
#absensiPage .abs-today-title {
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    margin: 0 0 2px;
}
#absensiPage .abs-today-time {
    font-size: 20px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
    line-height: 1.2;
    font-variant-numeric: tabular-nums;
}
#absensiPage .abs-today-meta {
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
    font-size: 12px;
    color: #64748b;
}

/* ---- Panel Tindakan Absensi ---- */
#absensiPage .abs-gps-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    padding: 12px 14px;
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    margin-bottom: 14px;
}
#absensiPage .abs-gps-switch-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
}
#absensiPage .abs-gps-switch-wrap .form-check {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
    padding-left: 0;
}
#absensiPage .abs-gps-switch-wrap .form-check-input {
    margin-left: 0;
    width: 40px;
    height: 22px;
    cursor: pointer;
}
#absensiPage .abs-gps-switch-wrap .form-check-label {
    font-size: 13.5px;
    font-weight: 600;
    color: #0f172a;
    cursor: pointer;
}
#absensiPage #locationStatus {
    font-size: 12.5px;
    color: #475569;
    font-weight: 500;
}

#absensiPage .abs-controls-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1.4fr);
    gap: 12px;
    margin-bottom: 16px;
    align-items: stretch;
}
#absensiPage .abs-field-box {
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 14px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
#absensiPage .abs-field-box .form-label {
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #64748b;
    margin-bottom: 6px;
}
#absensiPage .abs-field-box .form-select {
    border-radius: 9px;
    border-color: #cbd5e1;
    font-size: 13.5px;
    font-weight: 500;
    color: #0f172a;
}
#absensiPage .abs-telemetry-box {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 14px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
#absensiPage .abs-telemetry-row {
    display: flex;
    align-items: flex-start;
    gap: 7px;
    font-size: 12.5px;
    color: #475569;
    line-height: 1.4;
    margin: 0;
}
#absensiPage .abs-telemetry-row svg {
    flex-shrink: 0;
    margin-top: 2px;
    color: #64748b;
}
#absensiPage .abs-telemetry-key {
    font-weight: 600;
    color: #334155;
    white-space: nowrap;
}
#absensiPage .abs-telemetry-val {
    font-weight: 500;
    word-break: break-word;
}

/* ---- Action Tiles Hierarchy (Primary vs Secondary) ---- */
#absensiPage .abs-action-group-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #64748b;
    margin: 0 0 8px;
}
#absensiPage .abs-action-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
}
#absensiPage .abs-action-grid + .abs-action-group-label {
    margin-top: 14px;
}
#absensiPage .abs-action-form {
    display: flex;
    width: 100%;
    margin: 0;
}

/* Shared Tile Base (overrides app.css .btn-* !important via #absensiPage specificity) */
#absensiPage .abs-tile {
    width: 100%;
    min-width: 0;
    border-radius: 14px;
    border: 1.5px solid #e2e8f0;
    padding: 16px 16px;
    display: flex;
    align-items: center;
    gap: 14px;
    text-align: left;
    cursor: pointer;
    background-color: #ffffff;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    transition: transform 0.16s ease, box-shadow 0.16s ease, background-color 0.16s ease, border-color 0.16s ease, opacity 0.16s ease;
    -webkit-tap-highlight-color: transparent;
    position: relative;
}
#absensiPage .abs-tile-secondary-row {
    padding: 13px 15px;
    border-radius: 12px;
}
#absensiPage .abs-tile:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 18px -6px rgba(15, 23, 42, 0.12);
}
#absensiPage .abs-tile:active:not(:disabled) {
    transform: translateY(0) scale(0.99);
    box-shadow: none;
}
#absensiPage .abs-tile-3d {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: transform 0.16s ease;
}
#absensiPage .abs-tile-secondary-row .abs-tile-3d {
    width: 42px;
    height: 42px;
}
#absensiPage .abs-tile:hover:not(:disabled) .abs-tile-3d {
    transform: translateY(-1px) scale(1.04);
}
#absensiPage .abs-tile-3d svg {
    width: 100%;
    height: 100%;
    display: block;
}
#absensiPage .btn-content {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: center;
    min-width: 0;
    flex: 1;
    gap: 3px;
}
#absensiPage .btn-title {
    font-size: 14.5px;
    font-weight: 700;
    line-height: 1.25;
    display: block;
}
#absensiPage .abs-tile-secondary-row .btn-title {
    font-size: 13.5px;
}
#absensiPage .btn-subtitle {
    font-size: 12px;
    font-weight: 500;
    line-height: 1.35;
    display: block;
    overflow-wrap: break-word;
    word-break: break-word;
    white-space: normal;
    opacity: 0.9;
}

/* Color Variants Compatible with updateButtonStates() JS */
#absensiPage .abs-tile.btn-success:not(:disabled) {
    background-color: #f0fdf4 !important;
    border-color: #86efac !important;
    color: #15803d !important;
}
#absensiPage .abs-tile.btn-success:not(:disabled):hover {
    background-color: #dcfce7 !important;
    border-color: #4ade80 !important;
}

#absensiPage .abs-tile.btn-warning:not(:disabled) {
    background-color: #fffbeb !important;
    border-color: #fcd34d !important;
    color: #b45309 !important;
}
#absensiPage .abs-tile.btn-warning:not(:disabled):hover {
    background-color: #fef3c7 !important;
    border-color: #fbbf24 !important;
}

#absensiPage .abs-tile.btn-info:not(:disabled) {
    background-color: #ecfeff !important;
    border-color: #67e8f9 !important;
    color: #0e7490 !important;
}
#absensiPage .abs-tile.btn-info:not(:disabled):hover {
    background-color: #cffafe !important;
    border-color: #22d3ee !important;
}

#absensiPage .abs-tile.btn-primary:not(:disabled) {
    background-color: #eff6ff !important;
    border-color: #93c5fd !important;
    color: #1d4ed8 !important;
}
#absensiPage .abs-tile.btn-primary:not(:disabled):hover {
    background-color: #dbeafe !important;
    border-color: #60a5fa !important;
}

#absensiPage .abs-tile:disabled,
#absensiPage .abs-tile.btn-secondary {
    background-color: #f8fafc !important;
    border-color: #e2e8f0 !important;
    color: #64748b !important;
    cursor: not-allowed !important;
    box-shadow: none !important;
    transform: none !important;
    opacity: 0.86 !important;
}
#absensiPage .abs-tile:disabled .abs-tile-3d,
#absensiPage .abs-tile.btn-secondary .abs-tile-3d {
    filter: grayscale(0.75);
    opacity: 0.65;
}
#absensiPage .abs-tile.is-loading {
    pointer-events: none;
    opacity: 0.78 !important;
}

/* ---- Buttons (shadcn-style) ---- */
#absensiPage .ui-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    height: 36px;
    padding: 0 14px;
    border-radius: 9px;
    border: 1px solid;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    white-space: nowrap;
    transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease, transform 0.12s ease;
}
#absensiPage .ui-btn:active { transform: scale(0.98); }
#absensiPage .ui-btn svg { width: 15px; height: 15px; flex-shrink: 0; }

#absensiPage .ui-btn-primary {
    background-color: #2563eb;
    border-color: #2563eb;
    color: #ffffff;
}
#absensiPage .ui-btn-primary:hover {
    background-color: #1d4ed8;
    border-color: #1d4ed8;
    color: #ffffff;
}
#absensiPage .ui-btn-info {
    background-color: #0891b2;
    border-color: #0891b2;
    color: #ffffff;
}
#absensiPage .ui-btn-info:hover {
    background-color: #0e7490;
    border-color: #0e7490;
    color: #ffffff;
}
#absensiPage .ui-btn-outline {
    background-color: #ffffff;
    border-color: #e2e8f0;
    color: #334155;
}
#absensiPage .ui-btn-outline:hover {
    background-color: #f8fafc;
    border-color: #cbd5e1;
    color: #0f172a;
}
#absensiPage .ui-btn-sm {
    height: 30px;
    padding: 0 10px;
    font-size: 12px;
    border-radius: 8px;
}
#absensiPage .ui-btn-sm svg { width: 13px; height: 13px; }

#absensiPage .ui-close-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    transition: background-color 0.15s, color 0.15s;
}
#absensiPage .ui-close-btn:hover {
    background: #f1f5f9;
    color: #0f172a;
}

/* ---- Riwayat List & Pagination ---- */
#absensiPage .abs-hist-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
#absensiPage .abs-hist-item {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 13px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    cursor: pointer;
    transition: background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
}
#absensiPage .abs-hist-item:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    box-shadow: 0 2px 6px -2px rgba(15, 23, 42, 0.06);
}
#absensiPage .abs-hist-main {
    display: flex;
    flex-direction: column;
    gap: 5px;
    min-width: 0;
    flex: 1;
}
#absensiPage .abs-hist-date-row {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.3;
}
#absensiPage .abs-hist-date-row svg {
    color: #2563eb;
    flex-shrink: 0;
}
#absensiPage .abs-hist-sub {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    font-size: 12.5px;
    color: #64748b;
    font-weight: 500;
}
#absensiPage .abs-hist-sub-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-variant-numeric: tabular-nums;
}
#absensiPage .abs-hist-sub-chip svg {
    color: #94a3b8;
    flex-shrink: 0;
}
#absensiPage .abs-hist-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}
#absensiPage .abs-hist-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 14px;
    padding-top: 12px;
    border-top: 1px solid #f1f5f9;
    font-size: 12.5px;
    color: #64748b;
}
#absensiPage .pagination .page-link {
    border-radius: 8px;
    margin: 0 2px;
    border-color: #e2e8f0;
    color: #334155;
    font-size: 12.5px;
    font-weight: 600;
    padding: 5px 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 30px;
}
#absensiPage .pagination .page-item.active .page-link {
    background-color: #2563eb;
    border-color: #2563eb;
    color: #ffffff;
}
#absensiPage .abs-empty-state {
    padding: 28px 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    gap: 8px;
    color: #64748b;
    font-size: 13px;
}
#absensiPage .abs-empty-icon {
    width: 42px;
    height: 42px;
    border-radius: 11px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
}

@keyframes absSpin {
    to { transform: rotate(360deg); }
}
#absensiPage .abs-spin {
    animation: absSpin 0.9s linear infinite;
}

/* ---- Detail Absensi Cards ---- */
#absensiPage .abs-detail-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
}
#absensiPage .abs-detail-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 13px 15px;
}
#absensiPage .abs-detail-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 10px;
    padding: 7px 0;
    border-bottom: 1px solid #e2e8f0;
    font-size: 13px;
}
#absensiPage .abs-detail-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}
#absensiPage .abs-detail-row:first-child {
    padding-top: 0;
}
#absensiPage .abs-detail-k {
    color: #64748b;
    font-weight: 500;
    flex-shrink: 0;
}
#absensiPage .abs-detail-v {
    color: #0f172a;
    font-weight: 600;
    text-align: right;
    word-break: break-word;
}
#absensiPage .abs-detail-subpanel {
    margin-top: 14px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
}
#absensiPage .abs-detail-subhead {
    padding: 10px 14px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 7px;
}
#absensiPage .abs-detail-subbody {
    padding: 13px 14px;
}

/* ---- Responsive Breakpoints ---- */
@media (max-width: 767.98px) {
    #absensiPage .ui-card-header { padding: 13px 15px; }
    #absensiPage .ui-card-body { padding: 14px 15px !important; }
    #absensiPage .abs-hero-top {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
    }
    #absensiPage .abs-hero-identity {
        flex: none;
        width: 100%;
        gap: 12px;
    }
    #absensiPage .abs-hero-identity .icon-3d-box-lg {
        width: 44px;
        height: 44px;
        border-radius: 12px;
    }
    #absensiPage .abs-hero-identity .icon-3d-box-lg svg {
        width: 38px;
        height: 38px;
    }
    #absensiPage .abs-hero-title { font-size: 16.5px; }
    #absensiPage .abs-hero-desc { font-size: 12.5px; line-height: 1.45; }
    #absensiPage .abs-live-clock-wrap {
        width: 100%;
        gap: 6px;
    }
    #absensiPage .abs-iduka-panel { padding: 12px 13px; }
    #absensiPage .abs-iduka-metrics { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    #absensiPage .abs-iduka-metrics .abs-metric-chip:first-child { grid-column: 1 / -1; }
    #absensiPage .abs-today-grid { grid-template-columns: 1fr; }
    #absensiPage .abs-controls-grid { grid-template-columns: 1fr; }
    #absensiPage .abs-action-grid { gap: 10px; }
    #absensiPage .abs-tile {
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 14px 10px;
        gap: 9px;
    }
    #absensiPage .abs-tile-secondary-row {
        flex-direction: row;
        text-align: left;
        align-items: center;
        padding: 12px 12px;
        gap: 10px;
    }
    #absensiPage .abs-tile .btn-content {
        align-items: center;
    }
    #absensiPage .abs-tile-secondary-row .btn-content {
        align-items: flex-start;
    }
    #absensiPage .btn-title { font-size: 13.5px; }
    #absensiPage .abs-tile-secondary-row .btn-title { font-size: 13px; }
    #absensiPage .btn-subtitle { font-size: 11px; }
    #absensiPage .abs-hist-item {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
        padding: 12px 13px;
    }
    #absensiPage .abs-hist-right {
        width: 100%;
        justify-content: space-between;
        padding-top: 9px;
        border-top: 1px solid #f1f5f9;
    }
    #absensiPage .abs-detail-grid { grid-template-columns: 1fr; }
}
@media (max-width: 380px) {
    #absensiPage .abs-action-grid.is-secondary-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div id="absensiPage" class="container-xxl flex-grow-1 container-p-y">

    {{-- ============================================================
         Flash Messages & Validation Errors
    ============================================================ --}}
    @if (session('success'))
        <div class="ui-alert ui-alert-success alert-dismissible fade show" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"
                 class="lucide lucide-check-circle-2 ui-alert-icon">
                <circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>
            </svg>
            <div class="ui-alert-content"><strong>Berhasil!</strong> {{ session('success') }}</div>
            <button type="button" class="ui-alert-close" data-bs-dismiss="alert" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="ui-alert ui-alert-danger alert-dismissible fade show" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"
                 class="lucide lucide-alert-triangle ui-alert-icon">
                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                <path d="M12 9v4"/><path d="M12 17h.01"/>
            </svg>
            <div class="ui-alert-content"><strong>Error!</strong> {{ session('error') }}</div>
            <button type="button" class="ui-alert-close" data-bs-dismiss="alert" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
    @endif

    @if (session('warning'))
        <div class="ui-alert ui-alert-warning alert-dismissible fade show" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"
                 class="lucide lucide-alert-circle ui-alert-icon">
                <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>
            </svg>
            <div class="ui-alert-content"><strong>Peringatan!</strong> {{ session('warning') }}</div>
            <button type="button" class="ui-alert-close" data-bs-dismiss="alert" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
    @endif

    @if (session('info'))
        <div class="ui-alert ui-alert-info alert-dismissible fade show" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"
                 class="lucide lucide-info ui-alert-icon">
                <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
            </svg>
            <div class="ui-alert-content"><strong>Informasi:</strong> {{ session('info') }}</div>
            <button type="button" class="ui-alert-close" data-bs-dismiss="alert" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="ui-alert ui-alert-danger alert-dismissible fade show" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"
                 class="lucide lucide-alert-triangle ui-alert-icon">
                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                <path d="M12 9v4"/><path d="M12 17h.01"/>
            </svg>
            <div class="ui-alert-content">
                <strong>Validasi Error!</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="ui-alert-close" data-bs-dismiss="alert" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- ============================================================
         1. Header & Informasi IDUKA
    ============================================================ --}}
    <div class="ui-card">
        <div class="ui-card-body card-body">
            <div class="abs-hero-top">
                <div class="abs-hero-identity">
                    <div class="icon-3d-box icon-3d-box-lg" style="background:#eff6ff;border:1px solid #dbeafe;" aria-hidden="true">
                        {{-- 3D Radar / Attendance Pin --}}
                        <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <ellipse cx="32" cy="54" rx="22" ry="6" fill="#bfdbfe"/>
                            <ellipse cx="32" cy="51.5" rx="22" ry="6" fill="#dbeafe"/>
                            <ellipse cx="32" cy="51.5" rx="14" ry="3.8" stroke="#60a5fa" stroke-width="1.6"/>
                            <path d="M32 10c-9.9 0-18 7.8-18 17.5 0 11.4 14.5 22.8 16.9 24.6a1.8 1.8 0 0 0 2.2 0C35.5 50.3 50 38.9 50 27.5 50 17.8 41.9 10 32 10Z" transform="translate(0, 2.5)" fill="#1e40af"/>
                            <path d="M32 10c-9.9 0-18 7.8-18 17.5 0 11.4 14.5 22.8 16.9 24.6a1.8 1.8 0 0 0 2.2 0C35.5 50.3 50 38.9 50 27.5 50 17.8 41.9 10 32 10Z" fill="#2563eb"/>
                            <circle cx="32" cy="27.5" r="9.5" fill="#ffffff"/>
                            <circle cx="32" cy="27.5" r="4.5" fill="#3b82f6"/>
                        </svg>
                    </div>
                    <div class="abs-hero-text">
                        <h5 class="abs-hero-title">Absensi PKL</h5>
                        <p class="abs-hero-desc">Jangan lupa untuk melakukan absensi setiap hari. Pastikan Anda berada tepat di radius IDUKA.</p>
                    </div>
                </div>

                <div class="abs-live-clock-wrap">
                    <span class="abs-clock-pill">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-calendar">
                            <path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>
                        </svg>
                        <span id="absLiveDate">{{ \Carbon\Carbon::today()->locale('id')->isoFormat('ddd, D MMM Y') }}</span>
                    </span>
                    <span class="abs-clock-pill">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-clock">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <span id="absLiveClock">{{ now()->format('H:i:s') }}</span>
                    </span>
                </div>
            </div>

            @auth
                @if (Auth::user()->idukaDiterima)
                    <div class="abs-iduka-panel">
                        <div class="abs-iduka-head">
                            <div class="abs-iduka-meta-row">
                                <p class="abs-iduka-label">IDUKA Anda</p>
                                <div>
                                    @if ($isHoliday)
                                        <span class="ui-badge ui-badge-danger"><span class="ui-badge-dot"></span>Hari Libur</span>
                                    @elseif(isset($pkEnded) && $pkEnded)
                                        <span class="ui-badge ui-badge-warning"><span class="ui-badge-dot"></span>Periode Selesai</span>
                                    @else
                                        <span class="ui-badge ui-badge-success"><span class="ui-badge-dot"></span>PKL Aktif</span>
                                    @endif
                                </div>
                            </div>
                            <p class="abs-iduka-name">{{ Auth::user()->idukaDiterima->nama }}</p>
                            <div class="abs-iduka-address">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                     class="lucide lucide-map-pin">
                                    <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                                <span>{{ Auth::user()->idukaDiterima->alamat }}</span>
                            </div>
                        </div>

                        <div class="abs-iduka-metrics">
                            <div class="abs-metric-chip">
                                <span class="abs-metric-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    Radius
                                </span>
                                <span class="abs-metric-val">
                                    {{ Auth::user()->idukaDiterima->radius ?? 'Belum diatur' }} meter
                                </span>
                            </div>
                            <div class="abs-metric-chip">
                                <span class="abs-metric-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                                    </svg>
                                    Jam Masuk
                                </span>
                                <span class="abs-metric-val">
                                    {{ Auth::user()->idukaDiterima->jam_masuk
                                        ? date('H:i', strtotime(Auth::user()->idukaDiterima->jam_masuk))
                                        : 'Belum di tentukan' }}
                                </span>
                            </div>
                            <div class="abs-metric-chip">
                                <span class="abs-metric-label">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l4 2"/>
                                    </svg>
                                    Jam Pulang
                                </span>
                                <span class="abs-metric-val">
                                    {{ Auth::user()->idukaDiterima->jam_pulang
                                        ? date('H:i', strtotime(Auth::user()->idukaDiterima->jam_pulang))
                                        : 'Belum Ditentukan' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="ui-alert ui-alert-warning mt-3 mb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-alert-circle ui-alert-icon">
                            <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>
                        </svg>
                        <div class="ui-alert-content">
                            Anda belum terdaftar di IDUKA manapun. Silakan hubungi administrator.
                        </div>
                    </div>
                @endif

                @if(isset($pkEnded) && $pkEnded)
                    <div class="ui-alert ui-alert-warning mt-3 mb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"
                             class="lucide lucide-info ui-alert-icon">
                            <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
                        </svg>
                        <div class="ui-alert-content">
                            <strong>Periode PKL telah selesai.</strong> Absensi tidak lagi dibuka.
                        </div>
                    </div>
                @endif
            @endauth
        </div>
    </div>

    {{-- ============================================================
         2. Alert Hari Libur
    ============================================================ --}}
    @if ($isHoliday)
        <div class="ui-card" style="border-color:#fecaca;">
            <div class="ui-card-header" style="background:#fef2f2;border-bottom-color:#fecaca;">
                <div class="ui-card-header-left">
                    <div class="icon-3d-box" style="background:#fee2e2;border:1px solid #fecaca;" aria-hidden="true">
                        {{-- 3D Holiday Alert Icon --}}
                        <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <ellipse cx="32" cy="56" rx="18" ry="3.5" fill="#fecaca"/>
                            <path d="M30 10c1.1-2 3.9-2 5 0l19 34c1.1 2-.3 4.5-2.5 4.5h-38c-2.2 0-3.6-2.5-2.5-4.5l19-34Z" transform="translate(0, 3)" fill="#b91c1c"/>
                            <path d="M30 10c1.1-2 3.9-2 5 0l19 34c1.1 2-.3 4.5-2.5 4.5h-38c-2.2 0-3.6-2.5-2.5-4.5l19-34Z" fill="#ef4444"/>
                            <rect x="29.5" y="22" width="5" height="14" rx="2.5" fill="#ffffff"/>
                            <circle cx="32" cy="41.5" r="2.8" fill="#ffffff"/>
                        </svg>
                    </div>
                    <div>
                        <h6 class="ui-card-title" style="color:#991b1b;">Hari Libur</h6>
                        <p class="ui-card-subtitle" style="color:#b91c1c;">Absensi ditutup untuk hari ini</p>
                    </div>
                </div>
            </div>
            <div class="ui-card-body">
                <div class="ui-alert ui-alert-danger mb-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"
                         class="ui-alert-icon">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                        <path d="M12 9v4"/><path d="M12 17h.01"/>
                    </svg>
                    <div class="ui-alert-content">
                        <strong>Peringatan!</strong> Hari ini adalah hari libur ({{ $holidayLabel }}). Absensi ditutup.
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ============================================================
         3. Status Absensi Hari Ini / Menunggu Konfirmasi
    ============================================================ --}}
    @if ($absensiHariIni)
        <div class="ui-card">
            <div class="ui-card-header">
                <div class="ui-card-header-left">
                    <div class="icon-3d-box" style="background:#f0fdf4;border:1px solid #bbf7d0;" aria-hidden="true">
                        {{-- 3D Centang Terverifikasi --}}
                        <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <ellipse cx="32" cy="56.5" rx="18" ry="3.5" fill="#bbf7d0"/>
                            <path transform="translate(32 33.5) scale(2.15) translate(-12 -12)" d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z" fill="#15803d"/>
                            <path transform="translate(32 29.5) scale(2.15) translate(-12 -12)" d="M3.85 8.62a4 4 0 0 1 4.78-4.77 4 4 0 0 1 6.74 0 4 4 0 0 1 4.78 4.78 4 4 0 0 1 0 6.74 4 4 0 0 1-4.77 4.78 4 4 0 0 1-6.75 0 4 4 0 0 1-4.78-4.77 4 4 0 0 1 0-6.76Z" fill="#22c55e"/>
                            <circle cx="32" cy="29.5" r="14" stroke="#86efac" stroke-width="1.6"/>
                            <path d="m24.5 30 5 5 10.5-10.5" stroke="#ffffff" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <h6 class="ui-card-title">Status Absensi Hari Ini</h6>
                        <p class="ui-card-subtitle">Catatan kehadiran Anda yang tersimpan hari ini</p>
                    </div>
                </div>
            </div>

            <div class="ui-card-body">
                @if ($absensiHariIni->status === 'izin')
                    <div class="ui-alert ui-alert-info mb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"
                             class="ui-alert-icon">
                            <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
                        </svg>
                        <div class="ui-alert-content">
                            <strong>Anda sedang izin hari ini</strong><br>
                            Alasan: {{ $absensiHariIni->keterangan_izin }}<br>
                            <small style="opacity:0.85;">Waktu pengajuan: {{ $absensiHariIni->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                @elseif($absensiHariIni && $absensiHariIni->status_dinas === 'disetujui')
                    <div class="ui-alert ui-alert-info mb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"
                             class="ui-alert-icon">
                            <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><rect width="20" height="14" x="2" y="6" rx="2"/>
                        </svg>
                        <div class="ui-alert-content">
                            <strong>Anda sedang dinas luar hari ini</strong><br>
                            Jenis: {{ ucfirst(str_replace('_', ' ', $absensiHariIni->jenis_dinas)) }}<br>
                            Alasan: {{ $absensiHariIni->keterangan_dinas }}<br>
                            <small style="display:block;margin-top:4px;opacity:0.85;">
                                Anda bisa langsung absen pulang tanpa harus absen masuk terlebih dahulu. Absen pulang dapat dilakukan dari mana saja setelah jam 12.00 siang.
                            </small>
                        </div>
                    </div>
                @else
                    <div class="abs-today-grid">
                        @if ($absensiHariIni->jam_masuk)
                            <div class="abs-today-box">
                                <div class="icon-3d-box" style="background:#dcfce7;border:1px solid #bbf7d0;" aria-hidden="true">
                                    {{-- 3D Emerald Check-In Clock --}}
                                    <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="32" cy="34" r="22" fill="#15803d"/>
                                        <circle cx="32" cy="30" r="22" fill="#22c55e"/>
                                        <circle cx="32" cy="30" r="16" fill="#ffffff"/>
                                        <path d="M32 20v10.5l7 4" stroke="#16a34a" stroke-width="3.6" stroke-linecap="round" stroke-linejoin="round"/>
                                        <circle cx="32" cy="30" r="2.2" fill="#15803d"/>
                                    </svg>
                                </div>
                                <div style="min-width:0;flex:1;">
                                    <p class="abs-today-title">Absen Masuk</p>
                                    <p class="abs-today-time">{{ $absensiHariIni->jam_masuk->format('H:i') }}</p>
                                    <div class="abs-today-meta">
                                        @if ($absensiHariIni->status === 'tepat_waktu')
                                            <span class="ui-badge ui-badge-success"><span class="ui-badge-dot"></span>Tepat Waktu</span>
                                        @else
                                            <span class="ui-badge ui-badge-warning"><span class="ui-badge-dot"></span>Terlambat</span>
                                        @endif
                                        @if ($absensiHariIni->status_dinas === 'disetujui')
                                            <span class="ui-badge ui-badge-primary"><span class="ui-badge-dot"></span>Dinas Luar</span>
                                        @endif
                                        <span>Batas: {{ Auth::user()->idukaDiterima->jam_masuk ? Auth::user()->idukaDiterima->jam_masuk->format('H:i') : '08:00' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($absensiHariIni->jam_pulang)
                            <div class="abs-today-box">
                                <div class="icon-3d-box" style="background:#fef3c7;border:1px solid #fde68a;" aria-hidden="true">
                                    {{-- 3D Amber Check-Out Clock --}}
                                    <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="32" cy="34" r="22" fill="#b45309"/>
                                        <circle cx="32" cy="30" r="22" fill="#f59e0b"/>
                                        <circle cx="32" cy="30" r="16" fill="#fffbeb"/>
                                        <path d="M32 20v10l-6.5 3.8" stroke="#d97706" stroke-width="3.6" stroke-linecap="round" stroke-linejoin="round"/>
                                        <circle cx="32" cy="30" r="2.2" fill="#b45309"/>
                                    </svg>
                                </div>
                                <div style="min-width:0;flex:1;">
                                    <p class="abs-today-title">Absen Pulang</p>
                                    <p class="abs-today-time">{{ $absensiHariIni->jam_pulang->format('H:i') }}</p>
                                    <div class="abs-today-meta">
                                        @if ($absensiHariIni->status_dinas === 'disetujui')
                                            <span class="ui-badge ui-badge-primary"><span class="ui-badge-dot"></span>Dinas Luar</span>
                                        @else
                                            <span class="ui-badge ui-badge-warning"><span class="ui-badge-dot"></span>Selesai</span>
                                        @endif
                                        <span>Minimal: {{ Auth::user()->idukaDiterima->jam_pulang ? Auth::user()->idukaDiterima->jam_pulang->format('H:i') : '15:00' }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @elseif ($hasPendingIzin || $hasPendingDinas || $hasPendingMasuk || $hasPendingPulang)
        <div class="ui-card">
            <div class="ui-card-header">
                <div class="ui-card-header-left">
                    <div class="icon-3d-box" style="background:#fffbeb;border:1px solid #fde68a;" aria-hidden="true">
                        {{-- 3D Amber Pending Clock --}}
                        <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <ellipse cx="32" cy="56" rx="18" ry="3.5" fill="#fde68a"/>
                            <circle cx="32" cy="34" r="22" fill="#b45309"/>
                            <circle cx="32" cy="30" r="22" fill="#f59e0b"/>
                            <circle cx="32" cy="30" r="17" stroke="#fde68a" stroke-width="1.6"/>
                            <path d="M32 19.5v11l7.5 4" stroke="#ffffff" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div>
                        <h6 class="ui-card-title">Status Menunggu Konfirmasi</h6>
                        <p class="ui-card-subtitle">Pengajuan Anda sedang menunggu persetujuan IDUKA</p>
                    </div>
                </div>
            </div>

            <div class="ui-card-body">
                @if ($hasPendingMasuk || $hasPendingPulang)
                    <div class="abs-today-grid {{ ($hasPendingIzin || $hasPendingDinas) ? 'mb-3' : '' }}">
                        @if ($hasPendingMasuk)
                            <div class="abs-today-box">
                                <div class="icon-3d-box" style="background:#fef3c7;border:1px solid #fde68a;" aria-hidden="true">
                                    <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="32" cy="34" r="22" fill="#b45309"/>
                                        <circle cx="32" cy="30" r="22" fill="#f59e0b"/>
                                        <circle cx="32" cy="30" r="16" fill="#fffbeb"/>
                                        <path d="M32 20v10.5l7 4" stroke="#d97706" stroke-width="3.6" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div style="min-width:0;flex:1;">
                                    <p class="abs-today-title">Absen Masuk</p>
                                    <p class="abs-today-time" style="font-size:15px;color:#b45309;">Menunggu Konfirmasi</p>
                                    <div class="abs-today-meta">
                                        <span class="ui-badge ui-badge-warning"><span class="ui-badge-dot"></span>Pending</span>
                                        <span>Diajukan: {{ $absensiPending->where('jenis', 'masuk')->first()->jam->format('H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($hasPendingPulang)
                            <div class="abs-today-box">
                                <div class="icon-3d-box" style="background:#fef3c7;border:1px solid #fde68a;" aria-hidden="true">
                                    <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="32" cy="34" r="22" fill="#b45309"/>
                                        <circle cx="32" cy="30" r="22" fill="#f59e0b"/>
                                        <circle cx="32" cy="30" r="16" fill="#fffbeb"/>
                                        <path d="M32 20v10.5l-6.5 3.8" stroke="#d97706" stroke-width="3.6" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <div style="min-width:0;flex:1;">
                                    <p class="abs-today-title">Absen Pulang</p>
                                    <p class="abs-today-time" style="font-size:15px;color:#b45309;">Menunggu Konfirmasi</p>
                                    <div class="abs-today-meta">
                                        <span class="ui-badge ui-badge-warning"><span class="ui-badge-dot"></span>Pending</span>
                                        <span>Diajukan: {{ $absensiPending->where('jenis', 'pulang')->first()->jam->format('H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                @if ($hasPendingIzin)
                    <div class="ui-alert ui-alert-warning {{ $hasPendingDinas ? 'mb-2' : 'mb-0' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"
                             class="ui-alert-icon">
                            <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
                        </svg>
                        <div class="ui-alert-content">
                            <strong>Izin menunggu konfirmasi</strong><br>
                            Jenis: {{ ucfirst(str_replace('_', ' ', $izinPending->jenis_izin)) }}<br>
                            Alasan: {{ $izinPending->keterangan }}<br>
                            <small style="opacity:0.85;">Diajukan: {{ $izinPending->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                @endif

                @if ($hasPendingDinas)
                    <div class="ui-alert ui-alert-info mb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"
                             class="ui-alert-icon">
                            <path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><rect width="20" height="14" x="2" y="6" rx="2"/>
                        </svg>
                        <div class="ui-alert-content">
                            <strong>Dinas luar menunggu konfirmasi</strong><br>
                            Jenis: {{ ucfirst(str_replace('_', ' ', $dinasPending->jenis_dinas)) }}<br>
                            Alasan: {{ $dinasPending->keterangan }}<br>
                            <small style="opacity:0.85;">Diajukan: {{ $dinasPending->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- ============================================================
         4. Panel Tindakan Absensi
    ============================================================ --}}
    <div class="ui-card">
        <div class="ui-card-header">
            <div class="ui-card-header-left">
                <div class="icon-3d-box" style="background:#eff6ff;border:1px solid #dbeafe;" aria-hidden="true">
                    {{-- 3D Compass / Action Hub Icon --}}
                    <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="32" cy="56" rx="18" ry="3.5" fill="#bfdbfe"/>
                        <circle cx="32" cy="34" r="21" fill="#1e40af"/>
                        <circle cx="32" cy="30" r="21" fill="#2563eb"/>
                        <circle cx="32" cy="30" r="15" fill="#eff6ff"/>
                        <path d="m39 23-4.5 11.5L23 39l4.5-11.5L39 23Z" fill="#2563eb"/>
                        <path d="m39 23-4.5 11.5L27.5 27.5 39 23Z" fill="#ef4444"/>
                        <circle cx="32" cy="30" r="2.2" fill="#ffffff"/>
                    </svg>
                </div>
                <div>
                    <h6 class="ui-card-title">Panel Tindakan Absensi</h6>
                    <p class="ui-card-subtitle">Pastikan GPS aktif dan Anda berada dalam radius target</p>
                </div>
            </div>
        </div>

        <div class="ui-card-body">
            {{-- GPS Toggle Bar --}}
            <div class="abs-gps-bar">
                <div class="abs-gps-switch-wrap">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="locationSwitch"
                            {{ $absensiHariIni && ($absensiHariIni->status === 'izin' || ($absensiHariIni->jam_masuk && $absensiHariIni->jam_pulang)) ? 'disabled' : '' }}
                            {{ $isHoliday ? 'disabled' : '' }}>
                        <label class="form-check-label" for="locationSwitch">Akses Lokasi</label>
                    </div>
                </div>
                <span id="locationStatus">
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
                </span>
            </div>

            {{-- Pilih Lokasi & Info Lokasi --}}
            <div class="abs-controls-grid">
                <div class="abs-field-box">
                    <label for="lokasiSelect" class="form-label">Pilih Lokasi Absensi</label>
                    <select class="form-select" id="lokasiSelect" {{ $isHoliday ? 'disabled' : '' }}>
                        <option value="pusat">Lokasi Pusat</option>
                        @if (Auth::user()->idukaDiterima && Auth::user()->idukaDiterima->is_pusat)
                            @foreach (Auth::user()->idukaDiterima->cabangs as $cabang)
                                <option value="cabang_{{ $cabang->id }}">{{ $cabang->nama }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="abs-telemetry-box" id="locationInfo">
                    @if ($isHoliday)
                        <p class="abs-telemetry-row">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            <span class="abs-telemetry-key">Status:</span>
                            <span id="distanceStatus" class="abs-telemetry-val text-danger">Hari libur ({{ $holidayLabel }})</span>
                        </p>
                        <p class="abs-telemetry-row">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/></svg>
                            <span class="abs-telemetry-key">Jarak:</span>
                            <span id="distanceValue" class="abs-telemetry-val">-</span>
                        </p>
                        <p class="abs-telemetry-row">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                            <span class="abs-telemetry-key">Lokasi Anda:</span>
                            <span id="userLocation" class="abs-telemetry-val">-</span>
                        </p>
                    @else
                        <p class="abs-telemetry-row">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                            <span class="abs-telemetry-key">Status:</span>
                            <span id="distanceStatus" class="abs-telemetry-val">Menunggu akses lokasi</span>
                        </p>
                        <p class="abs-telemetry-row">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/></svg>
                            <span class="abs-telemetry-key">Jarak:</span>
                            <span id="distanceValue" class="abs-telemetry-val">-</span>
                        </p>
                        <p class="abs-telemetry-row">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                            <span class="abs-telemetry-key">Lokasi Anda:</span>
                            <span id="userLocation" class="abs-telemetry-val">-</span>
                        </p>
                    @endif
                </div>
            </div>

            {{-- Primary Actions: Absen Masuk & Absen Pulang --}}
            <p class="abs-action-group-label">Absensi Harian</p>
            <div class="abs-action-grid">
                {{-- Tombol Absen Masuk --}}
                <form method="POST" action="{{ route('absensi.masuk') }}" id="formMasuk" class="abs-action-form">
                    @csrf
                    <input type="hidden" name="latitude" id="latitudeMasuk">
                    <input type="hidden" name="longitude" id="longitudeMasuk">
                    <input type="hidden" name="accuracy" id="accuracyMasuk">
                    <input type="hidden" name="lokasi_id" id="lokasiIdMasuk" value="pusat">

                    <button type="submit" class="abs-tile btn-success" id="btnMasuk"
                        {{ $absensiHariIni && ($absensiHariIni->jam_masuk || $hasApprovedIzin || $hasApprovedDinas) ? 'disabled' : '' }}
                        {{ $hasPendingIzin || $hasPendingDinas || $hasPendingMasuk ? 'disabled' : '' }}
                        {{ $isHoliday ? 'disabled' : '' }}>
                        <span class="abs-tile-3d" aria-hidden="true">
                            {{-- 3D Emerald Check-In Clock --}}
                            <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <ellipse cx="32" cy="56.5" rx="18" ry="3.5" fill="#bbf7d0"/>
                                <circle cx="32" cy="34" r="21" fill="#15803d"/>
                                <circle cx="32" cy="30" r="21" fill="#22c55e"/>
                                <circle cx="32" cy="30" r="15.5" fill="#ffffff"/>
                                <path d="M32 20v10.5l7 4" stroke="#16a34a" stroke-width="3.6" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="32" cy="30" r="2.2" fill="#15803d"/>
                            </svg>
                        </span>
                        <span class="btn-content">
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
                        </span>
                    </button>
                </form>

                {{-- Tombol Absen Pulang --}}
                <form method="POST" action="{{ route('absensi.pulang.siswa') }}" id="formPulang" class="abs-action-form">
                    @csrf
                    <input type="hidden" name="latitude" id="latitudePulang" value="0">
                    <input type="hidden" name="longitude" id="longitudePulang" value="0">
                    <input type="hidden" name="accuracy" id="accuracyPulang" value="0">
                    <input type="hidden" name="lokasi_id" id="lokasiIdPulang" value="pusat">

                    <button type="submit" class="abs-tile btn-warning" id="btnPulang"
                        {{ !$absensiHariIni || !$absensiHariIni->jam_masuk || $absensiHariIni->jam_pulang || $hasApprovedIzin ? 'disabled' : '' }}
                        {{ $hasPendingIzin ? 'disabled' : '' }}
                        @if (!$hasApprovedDinas && $hasPendingDinas) disabled @endif
                        {{ $hasPendingPulang ? 'disabled' : '' }}
                        {{ $isHoliday ? 'disabled' : '' }}>
                        <span class="abs-tile-3d" aria-hidden="true">
                            {{-- 3D Amber Clock-Out / Return Clock --}}
                            <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <ellipse cx="32" cy="56.5" rx="18" ry="3.5" fill="#fde68a"/>
                                <circle cx="32" cy="34" r="21" fill="#b45309"/>
                                <circle cx="32" cy="30" r="21" fill="#f59e0b"/>
                                <circle cx="32" cy="30" r="15.5" fill="#fffbeb"/>
                                <path d="M32 20v10.2l-6.5 3.8" stroke="#d97706" stroke-width="3.6" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M41.5 37.5H53l-3.8-3.8M53 37.5l-3.8 3.8" stroke="#b45309" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span class="btn-content">
                            <span class="btn-title">Absen Pulang</span>
                            <span class="btn-subtitle">
                                @if ($isHoliday)
                                    Hari libur
                                @elseif ($absensiHariIni && $absensiHariIni->jam_pulang)
                                    Sudah absen: {{ $absensiHariIni->jam_pulang->format('H:i') }}
                                @elseif ($hasApprovedIzin)
                                    Sedang izin
                                @elseif ($hasApprovedDinas)
                                    @if ($absensiHariIni->jam_pulang)
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
                                    Anda harus absen masuk terlebih dahulu
                                @else
                                    Klik untuk absen pulang
                                @endif
                            </span>
                        </span>
                    </button>
                </form>
            </div>

            {{-- Secondary Actions: Izin & Dinas Luar --}}
            <p class="abs-action-group-label">Pengajuan Lainnya</p>
            <div class="abs-action-grid is-secondary-grid">
                {{-- Tombol Izin --}}
                <button type="button" class="abs-tile abs-tile-secondary-row btn-info" id="btnIzin"
                    {{ $absensiHariIni ? 'disabled' : '' }} {{ $hasPendingIzin || $hasApprovedIzin ? 'disabled' : '' }}
                    {{ $hasPendingDinas || $hasApprovedDinas ? 'disabled' : '' }} {{ $hasPendingMasuk ? 'disabled' : '' }} {{ $hasPendingPulang ? 'disabled' : '' }}
                    {{ $isHoliday ? 'disabled' : '' }}>
                    <span class="abs-tile-3d" aria-hidden="true">
                        {{-- 3D Cyan Izin Document --}}
                        <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <ellipse cx="32" cy="57" rx="18" ry="3.2" fill="#a5f3fc"/>
                            <rect x="13" y="10" width="32" height="41" rx="6" transform="rotate(-6 29 30)" fill="#0e7490"/>
                            <path d="M18 11h19l10 10v25a5 5 0 0 1-5 5H18a5 5 0 0 1-5-5V16a5 5 0 0 1 5-5Z" fill="#22d3ee"/>
                            <path d="M18 8h19l10 10v25a5 5 0 0 1-5 5H18a5 5 0 0 1-5-5V13a5 5 0 0 1 5-5Z" fill="#ffffff" stroke="#cffafe" stroke-width="1"/>
                            <path d="M37 8v7a3 3 0 0 0 3 3h7Z" fill="#a5f3fc"/>
                            <rect x="20" y="23" width="18" height="3.2" rx="1.6" fill="#0891b2"/>
                            <rect x="20" y="30" width="21" height="3" rx="1.5" fill="#67e8f9"/>
                            <rect x="20" y="37" width="13" height="3" rx="1.5" fill="#a5f3fc"/>
                        </svg>
                    </span>
                    <span class="btn-content">
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
                    </span>
                </button>

                {{-- Tombol Dinas Luar --}}
                <button type="button" class="abs-tile abs-tile-secondary-row btn-primary" id="btnDinas"
                    {{ $absensiHariIni ? 'disabled' : '' }} {{ $hasPendingDinas || $hasApprovedDinas ? 'disabled' : '' }}
                    {{ $hasPendingIzin || $hasApprovedIzin ? 'disabled' : '' }} {{ $hasPendingMasuk ? 'disabled' : '' }} {{ $hasPendingPulang ? 'disabled' : '' }}
                    {{ $isHoliday ? 'disabled' : '' }}>
                    <span class="abs-tile-3d" aria-hidden="true">
                        {{-- 3D Blue Briefcase --}}
                        <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <ellipse cx="32" cy="56.5" rx="20" ry="3.5" fill="#bfdbfe"/>
                            <path d="M24 17v-3.5A3.5 3.5 0 0 1 27.5 10h9a3.5 3.5 0 0 1 3.5 3.5V17" stroke="#1e40af" stroke-width="4" stroke-linecap="round"/>
                            <rect x="10" y="21" width="44" height="31" rx="7" fill="#1e40af"/>
                            <rect x="10" y="18" width="44" height="30" rx="7" fill="#2563eb"/>
                            <path d="M10 25c0-3.9 3.1-7 7-7h30c3.9 0 7 3.1 7 7v6c0 1.7-1.3 3-3 3H13c-1.7 0-3-1.3-3-3v-6Z" fill="#3b82f6"/>
                            <rect x="27" y="30" width="10" height="7" rx="2" fill="#eff6ff"/>
                        </svg>
                    </span>
                    <span class="btn-content">
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
                    </span>
                </button>
            </div>
        </div>
    </div>

    {{-- ============================================================
         5. Form Pengajuan Izin
    ============================================================ --}}
    <div id="formIzinContainer" style="display: none;">
        <div class="ui-card" style="border-color:#a5f3fc;">
            <div class="ui-card-header">
                <div class="ui-card-header-left">
                    <div class="icon-3d-box" style="background:#ecfeff;border:1px solid #cffafe;" aria-hidden="true">
                        <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 11h19l10 10v25a5 5 0 0 1-5 5H18a5 5 0 0 1-5-5V16a5 5 0 0 1 5-5Z" fill="#0891b2"/>
                            <path d="M18 8h19l10 10v25a5 5 0 0 1-5 5H18a5 5 0 0 1-5-5V13a5 5 0 0 1 5-5Z" fill="#ffffff" stroke="#a5f3fc" stroke-width="1"/>
                            <path d="M37 8v7a3 3 0 0 0 3 3h7Z" fill="#67e8f9"/>
                            <rect x="20" y="23" width="18" height="3.2" rx="1.6" fill="#0891b2"/>
                            <rect x="20" y="30" width="21" height="3" rx="1.5" fill="#67e8f9"/>
                        </svg>
                    </div>
                    <div>
                        <h6 class="ui-card-title" style="color:#0e7490;">Form Pengajuan Izin</h6>
                        <p class="ui-card-subtitle">Lengkapi jenis dan alasan izin hari ini</p>
                    </div>
                </div>
                <button type="button" class="ui-close-btn" id="closeIzinForm" aria-label="Tutup form izin">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('absensi.izin') }}" id="formIzin">
                @csrf
                <div class="ui-card-body card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="jenisIzin" class="form-label fw-semibold" style="font-size:13px;">Jenis Izin</label>
                            <select class="form-select" id="jenisIzin" name="jenis_izin" required>
                                <option value="">Pilih jenis izin</option>
                                <option value="sakit">Sakit</option>
                                <option value="keperluan_keluarga">Keperluan Keluarga</option>
                                <option value="keperluan_sekolah">Keperluan Sekolah</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="keteranganIzin" class="form-label fw-semibold" style="font-size:13px;">Keterangan/Alasan</label>
                            <textarea class="form-control" id="keteranganIzin" name="keterangan" rows="2"
                                placeholder="Jelaskan alasan izin Anda..." required></textarea>
                            <div class="form-text">Minimal 10 karakter, maksimal 500 karakter</div>
                        </div>
                    </div>
                    <div class="ui-alert ui-alert-warning mt-3 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="ui-alert-icon"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                        <div class="ui-alert-content">
                            <strong>Perhatian:</strong> Setelah mengajukan izin, Anda tidak bisa melakukan absensi masuk/pulang hari ini.
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="ui-btn ui-btn-outline" id="cancelIzin">Batal</button>
                        <button type="submit" class="ui-btn ui-btn-info">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                            Ajukan Izin
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================
         6. Form Pengajuan Dinas Luar
    ============================================================ --}}
    <div id="formDinasContainer" style="display: none;">
        <div class="ui-card" style="border-color:#bfdbfe;">
            <div class="ui-card-header">
                <div class="ui-card-header-left">
                    <div class="icon-3d-box" style="background:#eff6ff;border:1px solid #dbeafe;" aria-hidden="true">
                        <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M24 17v-3.5A3.5 3.5 0 0 1 27.5 10h9a3.5 3.5 0 0 1 3.5 3.5V17" stroke="#1e40af" stroke-width="4" stroke-linecap="round"/>
                            <rect x="10" y="21" width="44" height="31" rx="7" fill="#1e40af"/>
                            <rect x="10" y="18" width="44" height="30" rx="7" fill="#2563eb"/>
                            <path d="M10 25c0-3.9 3.1-7 7-7h30c3.9 0 7 3.1 7 7v6c0 1.7-1.3 3-3 3H13c-1.7 0-3-1.3-3-3v-6Z" fill="#3b82f6"/>
                            <rect x="27" y="30" width="10" height="7" rx="2" fill="#eff6ff"/>
                        </svg>
                    </div>
                    <div>
                        <h6 class="ui-card-title" style="color:#1d4ed8;">Form Pengajuan Dinas Luar</h6>
                        <p class="ui-card-subtitle">Isi jenis instansi dan keperluan dinas luar Anda</p>
                    </div>
                </div>
                <button type="button" class="ui-close-btn" id="closeDinasForm" aria-label="Tutup form dinas luar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            <form method="POST" action="{{ route('absensi.dinas-luar') }}" id="formDinas">
                @csrf
                <div class="ui-card-body card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="jenisDinas" class="form-label fw-semibold" style="font-size:13px;">Jenis Dinas</label>
                            <select class="form-select" id="jenisDinas" name="jenis_dinas" required>
                                <option value="">Pilih jenis dinas</option>
                                <option value="perusahaan">Perusahaan</option>
                                <option value="sekolah">Sekolah</option>
                                <option value="instansi_pemerintah">Instansi Pemerintah</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="keteranganDinas" class="form-label fw-semibold" style="font-size:13px;">Keterangan/Alasan</label>
                            <textarea class="form-control" id="keteranganDinas" name="keterangan" rows="2"
                                placeholder="Jelaskan alasan dinas luar Anda..." required></textarea>
                            <div class="form-text">Minimal 10 karakter, maksimal 500 karakter</div>
                        </div>
                    </div>
                    <div class="ui-alert ui-alert-info mt-3 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="ui-alert-icon"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                        <div class="ui-alert-content">
                            <strong>Informasi:</strong> Setelah mengajukan dinas luar dan disetujui, Anda tetap wajib melakukan absensi pulang seperti biasa.
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="ui-btn ui-btn-outline" id="cancelDinas">Batal</button>
                        <button type="submit" class="ui-btn ui-btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                            Ajukan Dinas Luar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================
         7. Trigger Riwayat Absensi
    ============================================================ --}}
    <div class="ui-card">
        <div class="ui-card-header" style="border-bottom:none;">
            <div class="ui-card-header-left">
                <div class="icon-3d-box" style="background:#eff6ff;border:1px solid #dbeafe;" aria-hidden="true">
                    {{-- 3D Calendar History Icon --}}
                    <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="32" cy="56.5" rx="18" ry="3.5" fill="#bfdbfe"/>
                        <rect x="11" y="14" width="42" height="39" rx="8" fill="#1e40af"/>
                        <rect x="11" y="11" width="42" height="38" rx="8" fill="#ffffff" stroke="#bfdbfe" stroke-width="1.5"/>
                        <path d="M11 19c0-4.4 3.6-8 8-8h26c4.4 0 8 3.6 8 8v5H11v-5Z" fill="#2563eb"/>
                        <rect x="20" y="7" width="4.5" height="9" rx="2.2" fill="#1d4ed8"/>
                        <rect x="39.5" y="7" width="4.5" height="9" rx="2.2" fill="#1d4ed8"/>
                        <rect x="18" y="30" width="8" height="6" rx="1.8" fill="#3b82f6"/>
                        <rect x="28" y="30" width="8" height="6" rx="1.8" fill="#93c5fd"/>
                        <rect x="38" y="30" width="8" height="6" rx="1.8" fill="#bfdbfe"/>
                        <rect x="18" y="39" width="8" height="6" rx="1.8" fill="#bfdbfe"/>
                        <rect x="28" y="39" width="8" height="6" rx="1.8" fill="#93c5fd"/>
                    </svg>
                </div>
                <div>
                    <h6 class="ui-card-title">Riwayat Absensi</h6>
                    <p class="ui-card-subtitle">Lihat rekap kehadiran, jam masuk/pulang, dan lokasi</p>
                </div>
            </div>
            <button type="button" class="ui-btn ui-btn-primary" id="toggleRiwayat">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye">
                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/>
                </svg>
                Lihat Riwayat
            </button>
        </div>
    </div>

    {{-- ============================================================
         8. Daftar Riwayat Absensi (Hidden by default)
    ============================================================ --}}
    <div id="riwayatContainer" style="display: none;">
        <div class="ui-card">
            <div class="ui-card-header">
                <div class="ui-card-header-left">
                    <div>
                        <h6 class="ui-card-title">Daftar Riwayat Absensi</h6>
                        <p class="ui-card-subtitle">Klik item atau tombol Detail untuk informasi lengkap</p>
                    </div>
                </div>
                <button type="button" class="ui-btn ui-btn-outline ui-btn-sm" id="hideRiwayat">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off">
                        <path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/>
                        <path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/><path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/>
                        <path d="m2 2 20 20"/>
                    </svg>
                    Sembunyikan
                </button>
            </div>
            <div class="ui-card-body">
                <div id="riwayatAbsensiTable" class="abs-hist-list">
                    <div class="abs-empty-state">
                        <div class="abs-empty-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="abs-spin">
                                <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                            </svg>
                        </div>
                        <span>Memuat data riwayat absensi...</span>
                    </div>
                </div>

                <div class="abs-hist-footer">
                    <div>
                        Menampilkan <strong id="showingCount">0</strong> dari <strong id="totalCount">0</strong> data
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0" id="paginationRiwayat">
                            <!-- Pagination will be inserted here -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         9. Detail Absensi (Hidden by default)
    ============================================================ --}}
    <div id="detailAbsensiContainer" style="display: none;">
        <div class="ui-card">
            <div class="ui-card-header">
                <div class="ui-card-header-left">
                    <div class="icon-3d-box" style="background:#eff6ff;border:1px solid #dbeafe;" aria-hidden="true">
                        <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="32" cy="34" r="21" fill="#1e40af"/>
                            <circle cx="32" cy="30" r="21" fill="#2563eb"/>
                            <circle cx="32" cy="30" r="16" stroke="#93c5fd" stroke-width="1.4"/>
                            <circle cx="32" cy="21" r="2.6" fill="#ffffff"/>
                            <rect x="29.5" y="27" width="5" height="14" rx="2.5" fill="#ffffff"/>
                        </svg>
                    </div>
                    <div>
                        <h6 class="ui-card-title">Detail Absensi</h6>
                        <p class="ui-card-subtitle">Rincian waktu, status, dan koordinat kehadiran</p>
                    </div>
                </div>
                <button type="button" class="ui-close-btn" id="closeDetailAbsensi" aria-label="Tutup detail absensi">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            <div class="ui-card-body" id="detailAbsensiContent">
                <!-- Detail content will be inserted here -->
            </div>
        </div>
    </div>

</div>

<script>
    // Live clock update (device clock matching JS time validation)
    (function startLiveClock() {
        const dateEl = document.getElementById('absLiveDate');
        const clockEl = document.getElementById('absLiveClock');
        if (!clockEl) return;

        function tick() {
            const now = new Date();
            clockEl.textContent = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            }).replace(/\./g, ':');
            if (dateEl) {
                dateEl.textContent = now.toLocaleDateString('id-ID', {
                    weekday: 'short',
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
            }
        }
        tick();
        setInterval(tick, 1000);
    })();

    // Variabel global untuk menyimpan posisi
    let currentPosition = null;
    let watchId = null;
    let riwayatData = [];
    let currentPage = 1;
    let perPage = 10;
    let riwayatVisible = false;

    // Status absensi hari ini dari server
    const absensiHariIni = @json($absensiHariIni);
    const sudahAbsenMasuk = absensiHariIni && absensiHariIni.jam_masuk;
    const sudahAbsenPulang = absensiHariIni && absensiHariIni.jam_pulang;
    const sedangIzin = absensiHariIni && absensiHariIni.status === 'izin';
    const sedangDinas = absensiHariIni && absensiHariIni.status_dinas === 'disetujui';

    // Status pending dan approved dari server
    const hasPendingIzin = @json($hasPendingIzin ?? false);
    const hasPendingDinas = @json($hasPendingDinas ?? false);
    const hasApprovedIzin = @json($hasApprovedIzin ?? false);
    const hasApprovedDinas = @json($hasApprovedDinas ?? false);

    // Status pending absensi
    const hasPendingMasuk = @json($absensiPending && $absensiPending->where('jenis', 'masuk')->count() > 0);
    const hasPendingPulang = @json($absensiPending && $absensiPending->where('jenis', 'pulang')->count() > 0);

    // Status hari libur
    const isHoliday = @json($isHoliday ?? false);
    const holidayLabel = @json($holidayLabel ?? '');

    // PKL period flag (set server-side)
    const pkEnded = @json($pkEnded ?? false);

    // Elemen UI
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

    // Elemen form Izin
    const formIzinContainer = document.getElementById('formIzinContainer');
    const closeIzinForm = document.getElementById('closeIzinForm');
    const cancelIzin = document.getElementById('cancelIzin');

    // Elemen form Dinas
    const formDinasContainer = document.getElementById('formDinasContainer');
    const closeDinasForm = document.getElementById('closeDinasForm');
    const cancelDinas = document.getElementById('cancelDinas');

    // Elemen riwayat absensi
    const toggleRiwayat = document.getElementById('toggleRiwayat');
    const hideRiwayat = document.getElementById('hideRiwayat');
    const riwayatContainer = document.getElementById('riwayatContainer');
    const riwayatAbsensiTable = document.getElementById('riwayatAbsensiTable');
    const showingCount = document.getElementById('showingCount');
    const totalCount = document.getElementById('totalCount');
    const paginationRiwayat = document.getElementById('paginationRiwayat');

    // Elemen detail absensi
    const detailAbsensiContainer = document.getElementById('detailAbsensiContainer');
    const closeDetailAbsensi = document.getElementById('closeDetailAbsensi');
    const detailAbsensiContent = document.getElementById('detailAbsensiContent');

    // Koordinat IDUKA dari user yang login
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

    // Hitung jam masuk awal (1 jam sebelum jam masuk resmi)
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

    // Data cabang
    const cabangs = @json(Auth::user()->idukaDiterima && Auth::user()->idukaDiterima->is_pusat ? Auth::user()->idukaDiterima->cabangs : []);

    // Event listener untuk tombol toggle riwayat
    toggleRiwayat.addEventListener('click', function() {
        showRiwayat();
    });

    // Event listener untuk tombol hide riwayat
    hideRiwayat.addEventListener('click', function() {
        hideRiwayatSection();
    });

    // Event listener untuk perubahan lokasi
    lokasiSelect.addEventListener('change', function () {
        const selectedValue = this.value;
        lokasiIdMasuk.value = selectedValue;
        lokasiIdPulang.value = selectedValue;
        updateLocationInfo(currentPosition);
    });

    // Event listener untuk tombol Izin
    btnIzin.addEventListener('click', function() {
        if (this.disabled) return;

        if (isHoliday) {
            alert('Hari ini adalah hari libur (' + holidayLabel + '). Tidak bisa mengajukan izin.');
            return;
        }

        formDinasContainer.style.display = 'none';

        if (formIzinContainer.style.display === 'none' || formIzinContainer.style.display === '') {
            formIzinContainer.style.display = 'block';
            document.getElementById('formIzin').reset();
            formIzinContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            formIzinContainer.style.display = 'none';
        }
    });

    // Event listener untuk tombol Dinas
    btnDinas.addEventListener('click', function() {
        if (this.disabled) return;

        if (isHoliday) {
            alert('Hari ini adalah hari libur (' + holidayLabel + '). Tidak bisa mengajukan dinas luar.');
            return;
        }

        formIzinContainer.style.display = 'none';

        if (formDinasContainer.style.display === 'none' || formDinasContainer.style.display === '') {
            formDinasContainer.style.display = 'block';
            document.getElementById('formDinas').reset();
            formDinasContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            formDinasContainer.style.display = 'none';
        }
    });

    // Event listener untuk menutup form izin
    closeIzinForm.addEventListener('click', function() {
        formIzinContainer.style.display = 'none';
    });
    cancelIzin.addEventListener('click', function() {
        formIzinContainer.style.display = 'none';
    });

    // Event listener untuk menutup form dinas
    closeDinasForm.addEventListener('click', function() {
        formDinasContainer.style.display = 'none';
    });
    cancelDinas.addEventListener('click', function() {
        formDinasContainer.style.display = 'none';
    });

    // Event listener untuk menutup detail absensi
    closeDetailAbsensi.addEventListener('click', function() {
        hideDetailAbsensi();
    });

    // Fungsi untuk menampilkan riwayat
    function showRiwayat() {
        riwayatContainer.style.display = 'block';
        riwayatVisible = true;

        if (riwayatData.length === 0) {
            loadRiwayatAbsensi();
        }

        riwayatContainer.scrollIntoView({ behavior: 'smooth' });
    }

    // Fungsi untuk menyembunyikan riwayat
    function hideRiwayatSection() {
        riwayatContainer.style.display = 'none';
        riwayatVisible = false;
    }

    // Fungsi untuk menampilkan detail absensi
    function showDetailAbsensi(id) {
        if (!riwayatVisible) {
            showRiwayat();
        }

        const absensi = riwayatData.find(a => a.id == id);
        if (!absensi) return;

        formIzinContainer.style.display = 'none';
        formDinasContainer.style.display = 'none';
        detailAbsensiContainer.style.display = 'block';

        let html = `
            <div class="abs-detail-grid">
                <div class="abs-detail-card">
                    <div class="abs-detail-row">
                        <span class="abs-detail-k">Tanggal</span>
                        <span class="abs-detail-v">${formatDate(absensi.tanggal)}</span>
                    </div>
                    <div class="abs-detail-row">
                        <span class="abs-detail-k">Hari</span>
                        <span class="abs-detail-v">${formatDay(absensi.tanggal)}</span>
                    </div>
                    <div class="abs-detail-row">
                        <span class="abs-detail-k">Status</span>
                        <span class="abs-detail-v">${getStatusBadge(absensi)}</span>
                    </div>
                    <div class="abs-detail-row">
                        <span class="abs-detail-k">Lokasi</span>
                        <span class="abs-detail-v">${absensi.lokasiIduka ? absensi.lokasiIduka.nama : '-'}</span>
                    </div>
                </div>
                <div class="abs-detail-card">
                    <div class="abs-detail-row">
                        <span class="abs-detail-k">Jam Masuk</span>
                        <span class="abs-detail-v">${absensi.jam_masuk ? formatTime(absensi.jam_masuk) : '-'}</span>
                    </div>
                    <div class="abs-detail-row">
                        <span class="abs-detail-k">Jam Pulang</span>
                        <span class="abs-detail-v">${absensi.jam_pulang ? formatTime(absensi.jam_pulang) : '-'}</span>
                    </div>
                    <div class="abs-detail-row">
                        <span class="abs-detail-k">Durasi Kerja</span>
                        <span class="abs-detail-v">${calculateDuration(absensi.jam_masuk, absensi.jam_pulang)}</span>
                    </div>
                </div>
            </div>
        `;

        if (absensi.status === 'izin') {
            html += `
                <div class="abs-detail-subpanel">
                    <div class="abs-detail-subhead">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#0891b2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
                        Informasi Izin
                    </div>
                    <div class="abs-detail-subbody">
                        <div class="abs-detail-grid">
                            <div class="abs-detail-card">
                                <div class="abs-detail-row">
                                    <span class="abs-detail-k">Jenis</span>
                                    <span class="abs-detail-v">${formatJenisIzin(absensi.jenis_izin)}</span>
                                </div>
                                <div class="abs-detail-row">
                                    <span class="abs-detail-k">Keterangan</span>
                                    <span class="abs-detail-v">${absensi.keterangan_izin || '-'}</span>
                                </div>
                            </div>
                            <div class="abs-detail-card">
                                <div class="abs-detail-row">
                                    <span class="abs-detail-k">Disetujui Oleh</span>
                                    <span class="abs-detail-v">${absensi.approved_by ? absensi.approvedBy.name : '-'}</span>
                                </div>
                                <div class="abs-detail-row">
                                    <span class="abs-detail-k">Tanggal Disetujui</span>
                                    <span class="abs-detail-v">${absensi.approved_at ? formatDateTime(absensi.approved_at) : '-'}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        if (absensi.status_dinas === 'disetujui') {
            html += `
                <div class="abs-detail-subpanel">
                    <div class="abs-detail-subhead">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><rect width="20" height="14" x="2" y="6" rx="2"/></svg>
                        Informasi Dinas Luar
                    </div>
                    <div class="abs-detail-subbody">
                        <div class="abs-detail-grid">
                            <div class="abs-detail-card">
                                <div class="abs-detail-row">
                                    <span class="abs-detail-k">Jenis</span>
                                    <span class="abs-detail-v">${formatJenisDinas(absensi.jenis_dinas)}</span>
                                </div>
                                <div class="abs-detail-row">
                                    <span class="abs-detail-k">Keterangan</span>
                                    <span class="abs-detail-v">${absensi.keterangan_dinas || '-'}</span>
                                </div>
                            </div>
                            <div class="abs-detail-card">
                                <div class="abs-detail-row">
                                    <span class="abs-detail-k">Disetujui Oleh</span>
                                    <span class="abs-detail-v">${absensi.approved_by ? absensi.approvedBy.name : '-'}</span>
                                </div>
                                <div class="abs-detail-row">
                                    <span class="abs-detail-k">Tanggal Disetujui</span>
                                    <span class="abs-detail-v">${absensi.approved_at ? formatDateTime(absensi.approved_at) : '-'}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        if (absensi.latitude && absensi.longitude) {
            html += `
                <div class="abs-detail-subpanel">
                    <div class="abs-detail-subhead">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                        Lokasi Absensi
                    </div>
                    <div class="abs-detail-subbody">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <div class="abs-detail-card">
                                    <div class="abs-detail-row">
                                        <span class="abs-detail-k">Koordinat Masuk</span>
                                        <span class="abs-detail-v">Lat: ${absensi.latitude}, Lng: ${absensi.longitude}</span>
                                    </div>
                                    ${absensi.latitude_pulang && absensi.longitude_pulang ? `
                                    <div class="abs-detail-row">
                                        <span class="abs-detail-k">Koordinat Pulang</span>
                                        <span class="abs-detail-v">Lat: ${absensi.latitude_pulang}, Lng: ${absensi.longitude_pulang}</span>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div id="map-${absensi.id}" style="height: 280px; width: 100%; border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            setTimeout(() => {
                initMap(absensi);
            }, 100);
        }

        detailAbsensiContent.innerHTML = html;
        detailAbsensiContainer.scrollIntoView({ behavior: 'smooth' });
    }

    // Fungsi untuk menyembunyikan detail absensi
    function hideDetailAbsensi() {
        detailAbsensiContainer.style.display = 'none';
    }

    // Fungsi untuk menghitung jarak antara dua titik (Haversine formula)
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000;
        const dLat = deg2rad(lat2 - lat1);
        const dLon = deg2rad(lon2 - lon1);
        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    function deg2rad(deg) {
        return deg * (Math.PI / 180);
    }

    // Fungsi untuk validasi jam pulang
    function validateJamPulang() {
        const now = new Date();
        const [jam, menit] = jamPulangIduka.split(':');
        const jamPulangDate = new Date();
        jamPulangDate.setHours(parseInt(jam), parseInt(menit), 0, 0);

        if (sedangDinas) {
            return { valid: true, message: '' };
        }

        if (now < jamPulangDate) {
            return {
                valid: false,
                message: `Belum waktunya absen pulang. Absen pulang dibuka pukul ${jamPulangIduka}.`
            };
        }

        return { valid: true, message: '' };
    }

    // Fungsi untuk validasi jam masuk
    function validateJamMasuk() {
        const now = new Date();
        const [jam, menit] = jamMasukIduka.split(':').map(Number);
        const jamMasukDate = new Date();
        jamMasukDate.setHours(jam, menit, 0, 0);

        const jamMasukStart = new Date(jamMasukDate.getTime() - 60 * 60 * 1000);

        if (now < jamMasukStart) {
            return {
                valid: false,
                message: `Absen masuk hanya dapat dilakukan mulai pukul ${jamMasukAllowStart}.`
            };
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
            locationStatus.textContent = sedangDinas ? "Sedang dinas luar, siap untuk absen pulang..." :
                "Mengambil lokasi...";

            navigator.geolocation.getCurrentPosition(
                showPosition,
                showError, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
            );

            watchId = navigator.geolocation.watchPosition(
                watchPosition,
                showError, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
            );
        } else {
            locationStatus.textContent = "Geolocation tidak didukung oleh browser ini.";
        }
    }

    function watchPosition(position) {
        currentPosition = position;
        updateLocationInfo(position);
    }

    function showPosition(position) {
        currentPosition = position;
        updateLocationInfo(position);
    }

    function updateLocationInfo(position) {
        if (!position) return;

        const userLat = position.coords.latitude;
        const userLng = position.coords.longitude;
        const accuracy = position.coords.accuracy;

        userLocation.textContent = userLat.toFixed(6) + ", " + userLng.toFixed(6);
        locationStatus.textContent = "Lokasi terdeteksi dengan akurasi Â±" + Math.round(accuracy) + "m";

        if (sedangDinas) {
            distanceStatus.textContent = "Anda sedang dinas luar, dapat absen pulang dari mana saja setelah jam 12.00";
            distanceStatus.className = "abs-telemetry-val text-success";
            distanceValue.textContent = "Tidak dibatasi lokasi (dinas luar)";

            const now = new Date();
            const jam12 = new Date();
            jam12.setHours(12, 0, 0, 0);
            if (now >= jam12) {
                btnPulang.disabled = false;
                btnPulang.title = '';
            } else {
                btnPulang.disabled = true;
                btnPulang.title = 'Absen pulang untuk dinas luar hanya dapat dilakukan setelah jam 12.00 siang.';
            }
            return;
        }

        const selectedLokasiId = lokasiSelect.value;
        let targetLat, targetLng, targetRadius, lokasiName;

        if (selectedLokasiId === 'pusat') {
            targetLat = idukaLat;
            targetLng = idukaLng;
            targetRadius = allowedRadius;
            lokasiName = "Pusat";
        } else {
            const cabangId = parseInt(selectedLokasiId.replace('cabang_', ''));
            const selectedCabang = cabangs.find(c => c.id === cabangId);

            if (selectedCabang) {
                targetLat = parseFloat(selectedCabang.latitude);
                targetLng = parseFloat(selectedCabang.longitude);
                targetRadius = parseFloat(selectedCabang.radius) || 100;
                lokasiName = selectedCabang.nama;
            } else {
                targetLat = idukaLat;
                targetLng = idukaLng;
                targetRadius = allowedRadius;
                lokasiName = "Pusat";
            }
        }

        if (!hasValidIduka || targetLat === null || targetLng === null || targetLat === 0 || targetLng === 0) {
            distanceStatus.textContent = `Koordinat ${lokasiName} belum diatur`;
            distanceStatus.className = "abs-telemetry-val text-warning";
            distanceValue.textContent = "Tidak dapat menghitung jarak";

            if (!sudahAbsenMasuk && !sedangIzin) btnMasuk.disabled = true;
            if (!sudahAbsenPulang && !sedangIzin && sudahAbsenMasuk) btnPulang.disabled = true;
            return;
        }

        const distance = calculateDistance(userLat, userLng, targetLat, targetLng);
        distanceValue.textContent = Math.round(distance) + " meter dari " + lokasiName + " (akurasi: Â±" + Math.round(accuracy) + "m)";

        const effectiveRadius = targetRadius + accuracy;
        const isWithinRadius = distance <= effectiveRadius;

        if (isWithinRadius) {
            distanceStatus.textContent = `Anda berada dalam radius yang diizinkan (${lokasiName})`;
            distanceStatus.className = "abs-telemetry-val text-success";

            const jamValidation = validateJamMasuk();

            if (!sudahAbsenMasuk && !sedangIzin) {
                btnMasuk.disabled = !jamValidation.valid;
                btnMasuk.title = jamValidation.valid ? "" : jamValidation.message;
            }
            if (sudahAbsenMasuk && !sudahAbsenPulang && !sedangIzin) {
                const jamValidation = validateJamPulang();
                btnPulang.disabled = !jamValidation.valid;
                btnPulang.title = jamValidation.valid ? "" : jamValidation.message;
            }
        } else {
            distanceStatus.textContent = `Anda berada di luar radius yang diizinkan (${lokasiName})`;
            distanceStatus.className = "abs-telemetry-val text-danger";

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
            case error.PERMISSION_DENIED:
                errorMessage = "Pengguna menolak permintaan geolocation.";
                break;
            case error.POSITION_UNAVAILABLE:
                errorMessage = "Informasi lokasi tidak tersedia.";
                break;
            case error.TIMEOUT:
                errorMessage = "Permintaan lokasi sudah timeout.";
                break;
            case error.UNKNOWN_ERROR:
                errorMessage = "Error tidak diketahui: " + error.message;
                break;
        }

        locationStatus.textContent = errorMessage;
        distanceStatus.textContent = "Error lokasi";
        distanceStatus.className = "abs-telemetry-val text-danger";
        distanceValue.textContent = "-";
        userLocation.textContent = "-";

        if (!sudahAbsenMasuk && !sedangIzin) btnMasuk.disabled = true;
        if (!sudahAbsenPulang && !sedangIzin) btnPulang.disabled = true;
    }

    // Event listener untuk switch lokasi
    locationSwitch.addEventListener('change', function () {
        if (isHoliday) {
            this.checked = false;
            return;
        }

        if (this.checked) {
            getLocation();
            updateButtonStates();
        } else {
            if (watchId !== null) {
                navigator.geolocation.clearWatch(watchId);
                watchId = null;
            }

            locationStatus.textContent = "Akses lokasi dimatikan";
            distanceStatus.textContent = "Akses lokasi dimatikan";
            distanceStatus.className = "abs-telemetry-val";
            distanceValue.textContent = "-";
            userLocation.textContent = "-";

            if (!sudahAbsenMasuk && !sedangIzin) btnMasuk.disabled = true;
            if (!sudahAbsenPulang && !sedangIzin) btnPulang.disabled = true;

            updateButtonStates();
        }
    });

    // Validasi form absen masuk
    document.getElementById('formMasuk').addEventListener('submit', function (e) {
        if (isHoliday) {
            e.preventDefault();
            alert('Hari ini adalah hari libur (' + holidayLabel + '). Absensi ditutup.');
            return false;
        }

        if (!locationSwitch.checked) {
            e.preventDefault();
            alert('Silakan aktifkan akses lokasi terlebih dahulu.');
            return false;
        }

        if (hasPendingIzin || hasApprovedIzin) {
            e.preventDefault();
            alert(hasApprovedIzin ? 'Anda sedang izin hari ini.' :
                'Anda sudah mengajukan izin. Menunggu konfirmasi.');
            return false;
        }

        if (hasPendingDinas || hasApprovedDinas) {
            e.preventDefault();
            alert(hasApprovedDinas ? 'Anda sedang dinas luar hari ini.' :
                'Anda sudah mengajukan dinas luar. Menunggu konfirmasi.');
            return false;
        }

        if (sudahAbsenMasuk || sedangIzin) {
            e.preventDefault();
            alert(sedangIzin ? 'Anda sedang izin hari ini.' : 'Anda sudah melakukan absen masuk hari ini.');
            return false;
        }

        if (sedangDinas) {
            console.log('Siswa sedang dinas luar, tetap diizinkan absen masuk');
        }

        if (!currentPosition) {
            e.preventDefault();
            alert('Lokasi tidak terdeteksi. Silakan aktifkan akses lokasi.');
            return false;
        }

        const jamValidation = validateJamMasuk();
        if (!jamValidation.valid) {
            e.preventDefault();
            alert(jamValidation.message);
            return false;
        }

        const selectedLokasiId = lokasiSelect.value;
        let targetLat, targetLng, targetRadius, lokasiName;

        if (selectedLokasiId === 'pusat') {
            targetLat = idukaLat;
            targetLng = idukaLng;
            targetRadius = allowedRadius;
            lokasiName = "Pusat";
        } else {
            const cabangId = parseInt(selectedLokasiId.replace('cabang_', ''));
            const selectedCabang = cabangs.find(c => c.id === cabangId);

            if (selectedCabang) {
                targetLat = parseFloat(selectedCabang.latitude);
                targetLng = parseFloat(selectedCabang.longitude);
                targetRadius = parseFloat(selectedCabang.radius) || 100;
                lokasiName = selectedCabang.nama;
            } else {
                targetLat = idukaLat;
                targetLng = idukaLng;
                targetRadius = allowedRadius;
                lokasiName = "Pusat";
            }
        }

        if (!hasValidIduka || targetLat === null || targetLng === null || targetLat === 0 || targetLng === 0) {
            e.preventDefault();
            alert(`Koordinat ${lokasiName} belum diatur. Silakan hubungi administrator.`);
            return false;
        }

        const userLat = currentPosition.coords.latitude;
        const userLng = currentPosition.coords.longitude;
        const accuracy = currentPosition.coords.accuracy;
        const distance = calculateDistance(userLat, userLng, targetLat, targetLng);

        if (distance > (targetRadius + accuracy)) {
            e.preventDefault();
            alert(
                `Anda berada di luar radius yang diizinkan untuk absensi di ${lokasiName}.\nJarak Anda: ${Math.round(distance)} meter\nRadius maksimal: ${targetRadius} meter\nAkurasi GPS: Â±${Math.round(accuracy)}m\nTotal toleransi: ${Math.round(targetRadius + accuracy)}m`
            );
            return false;
        }

        btnMasuk.classList.add('is-loading');
        const sub = btnMasuk.querySelector('.btn-subtitle');
        if (sub) sub.textContent = 'Memproses absen masuk...';
    });

    // Validasi form absen pulang
    document.getElementById('formPulang').addEventListener('submit', function (e) {
        if (isHoliday) {
            e.preventDefault();
            alert('Hari ini adalah hari libur (' + holidayLabel + '). Absensi ditutup.');
            return false;
        }

        if (!locationSwitch.checked) {
            e.preventDefault();
            alert('Silakan aktifkan akses lokasi terlebih dahulu.');
            return false;
        }

        if (hasPendingIzin || hasApprovedIzin) {
            e.preventDefault();
            alert(hasApprovedIzin ? 'Anda sedang izin hari ini.' :
                'Anda sudah mengajukan izin. Menunggu konfirmasi.');
            return false;
        }

        if (hasPendingDinas && !hasApprovedDinas) {
            e.preventDefault();
            alert('Dinas luar Anda masih menunggu konfirmasi.');
            return false;
        }

        if (sedangDinas) {
            const now = new Date();
            const jam12 = new Date();
            jam12.setHours(12, 0, 0, 0);

            if (now < jam12) {
                e.preventDefault();
                alert('Absen pulang untuk dinas luar hanya dapat dilakukan setelah jam 12.00 siang.');
                return false;
            }

            if (latitudePulang) latitudePulang.value = "0";
            if (longitudePulang) longitudePulang.value = "0";
            if (accuracyPulang) accuracyPulang.value = "0";
        } else {
            if (!sudahAbsenMasuk) {
                e.preventDefault();
                alert('Anda belum melakukan absen masuk hari ini.');
                return false;
            }

            if (sudahAbsenPulang || sedangIzin) {
                e.preventDefault();
                alert(sedangIzin ? 'Anda sedang izin hari ini.' :
                    'Anda sudah melakukan absensi pulang hari ini.');
                return false;
            }

            const jamValidation = validateJamPulang();
            if (!jamValidation.valid) {
                e.preventDefault();
                alert(jamValidation.message);
                return false;
            }

            if (!currentPosition) {
                e.preventDefault();
                alert('Lokasi tidak terdeteksi. Silakan aktifkan akses lokasi.');
                return false;
            }

            const selectedLokasiId = lokasiSelect.value;
            let targetLat, targetLng, targetRadius, lokasiName;

            if (selectedLokasiId === 'pusat') {
                targetLat = idukaLat;
                targetLng = idukaLng;
                targetRadius = allowedRadius;
                lokasiName = "Pusat";
            } else {
                const cabangId = parseInt(selectedLokasiId.replace('cabang_', ''));
                const selectedCabang = cabangs.find(c => c.id === cabangId);

                if (selectedCabang) {
                    targetLat = parseFloat(selectedCabang.latitude);
                    targetLng = parseFloat(selectedCabang.longitude);
                    targetRadius = parseFloat(selectedCabang.radius) || 100;
                    lokasiName = selectedCabang.nama;
                } else {
                    targetLat = idukaLat;
                    targetLng = idukaLng;
                    targetRadius = allowedRadius;
                    lokasiName = "Pusat";
                }
            }

            if (!hasValidIduka || targetLat === null || targetLng === null || targetLat === 0 || targetLng === 0) {
                e.preventDefault();
                alert(`Koordinat ${lokasiName} belum diatur. Silakan hubungi administrator.`);
                return false;
            }

            const userLat = currentPosition.coords.latitude;
            const userLng = currentPosition.coords.longitude;
            const accuracy = currentPosition.coords.accuracy;
            const distance = calculateDistance(userLat, userLng, targetLat, targetLng);

            if (distance > (targetRadius + accuracy)) {
                e.preventDefault();
                alert(
                    `Anda berada di luar radius yang diizinkan untuk absensi di ${lokasiName}.\nJarak Anda: ${Math.round(distance)} meter\nRadius maksimal: ${targetRadius} meter\nAkurasi GPS: Â±${Math.round(accuracy)}m\nTotal toleransi: ${Math.round(targetRadius + accuracy)}m`
                );
                return false;
            }
        }

        btnPulang.classList.add('is-loading');
        const sub = btnPulang.querySelector('.btn-subtitle');
        if (sub) sub.textContent = 'Memproses absen pulang...';
    });

    document.addEventListener('DOMContentLoaded', function () {
        const formIzin = document.getElementById('formIzin');
        const formDinas = document.getElementById('formDinas');
        let absensiHariIni = false;

        document.getElementById('btnIzin').addEventListener('click', function() {
            cekStatusIzin();
        });

        document.getElementById('btnDinas').addEventListener('click', function() {
            cekStatusDinas();
        });

        function cekStatusIzin() {
            fetch('{{ route('absensi.cek-izin') }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    absensiHariIni = data.has_attendance || data.has_approved_izin || data
                        .has_pending_masuk || data.has_pending_pulang;

                    if (data.has_attendance) {
                        disableIzinForm('Anda sudah memiliki record absensi hari ini. Tidak bisa mengajukan izin.');
                    } else if (data.has_approved_izin) {
                        disableIzinForm('Anda sudah mengajukan izin hari ini.');
                    } else if (data.has_pending_izin) {
                        disableIzinForm('Anda sudah mengajukan izin. Menunggu konfirmasi IDUKA.');
                    } else if (data.has_pending_masuk) {
                        disableIzinForm('Anda memiliki absensi masuk yang menunggu konfirmasi. Tidak bisa mengajukan izin.');
                    } else if (data.has_pending_pulang) {
                        disableIzinForm('Anda memiliki absensi pulang yang menunggu konfirmasi. Tidak bisa mengajukan izin.');
                    } else {
                        enableIzinForm();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function cekStatusDinas() {
            fetch('{{ route('absensi.cek-dinas') }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(data => {
                    absensiHariIni = data.has_attendance || data.has_approved_dinas || data
                        .has_pending_masuk || data.has_pending_pulang;

                    if (data.has_attendance) {
                        disableDinasForm('Anda sudah memiliki record absensi hari ini. Tidak bisa mengajukan dinas luar.');
                    } else if (data.has_approved_dinas) {
                        disableDinasForm('Anda sudah mengajukan dinas luar hari ini.');
                    } else if (data.has_pending_dinas) {
                        disableDinasForm('Anda sudah mengajukan dinas luar. Menunggu konfirmasi IDUKA.');
                    } else if (data.has_pending_masuk) {
                        disableDinasForm('Anda memiliki absensi masuk yang menunggu konfirmasi. Tidak bisa mengajukan dinas luar.');
                    } else if (data.has_pending_pulang) {
                        disableDinasForm('Anda memiliki absensi pulang yang menunggu konfirmasi. Tidak bisa mengajukan dinas luar.');
                    } else {
                        enableDinasForm();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function disableIzinForm(message) {
            formIzin.querySelectorAll('select, textarea, button[type="submit"]').forEach(el => {
                el.disabled = true;
            });

            let alertDiv = formIzin.querySelector('.izin-status-alert');
            if (!alertDiv) {
                alertDiv = document.createElement('div');
                alertDiv.className = 'ui-alert ui-alert-info mt-3 mb-0 izin-status-alert';
                formIzin.querySelector('.card-body').appendChild(alertDiv);
            }
            alertDiv.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="ui-alert-icon"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg><div class="ui-alert-content">${message}</div>`;
        }

        function enableIzinForm() {
            formIzin.querySelectorAll('select, textarea, button[type="submit"]').forEach(el => {
                el.disabled = false;
            });

            const alertDiv = formIzin.querySelector('.izin-status-alert');
            if (alertDiv) {
                alertDiv.remove();
            }
        }

        function disableDinasForm(message) {
            formDinas.querySelectorAll('select, textarea, button[type="submit"]').forEach(el => {
                el.disabled = true;
            });

            let alertDiv = formDinas.querySelector('.dinas-status-alert');
            if (!alertDiv) {
                alertDiv = document.createElement('div');
                alertDiv.className = 'ui-alert ui-alert-info mt-3 mb-0 dinas-status-alert';
                formDinas.querySelector('.card-body').appendChild(alertDiv);
            }
            alertDiv.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="ui-alert-icon"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg><div class="ui-alert-content">${message}</div>`;
        }

        function enableDinasForm() {
            formDinas.querySelectorAll('select, textarea, button[type="submit"]').forEach(el => {
                el.disabled = false;
            });

            const alertDiv = formDinas.querySelector('.dinas-status-alert');
            if (alertDiv) {
                alertDiv.remove();
            }
        }

        document.getElementById('formIzin').addEventListener('submit', function (e) {
            if (absensiHariIni) {
                e.preventDefault();
                alert('Anda sudah memiliki record absensi hari ini. Tidak bisa mengajukan izin.');
                return false;
            }

            if (hasPendingDinas || hasApprovedDinas) {
                e.preventDefault();
                alert(hasApprovedDinas ? 'Anda sedang dinas luar hari ini.' :
                    'Anda sudah mengajukan dinas luar. Menunggu konfirmasi.');
                return false;
            }

            if (hasPendingMasuk) {
                e.preventDefault();
                alert('Anda memiliki absensi masuk yang menunggu konfirmasi. Tidak bisa mengajukan izin.');
                return false;
            }

            if (hasPendingPulang) {
                e.preventDefault();
                alert('Anda memiliki absensi pulang yang menunggu konfirmasi. Tidak bisa mengajukan izin.');
                return false;
            }

            const keterangan = document.getElementById('keteranganIzin').value.trim();
            if (keterangan.length < 10) {
                e.preventDefault();
                alert('Keterangan izin minimal 10 karakter.');
                return false;
            }

            if (!confirm('Apakah Anda yakin ingin mengajukan izin? Setelah izin diajukan, Anda tidak bisa melakukan absensi hari ini.')) {
                e.preventDefault();
                return false;
            }
        });

        document.getElementById('formDinas').addEventListener('submit', function (e) {
            if (absensiHariIni) {
                e.preventDefault();
                alert('Anda sudah memiliki record absensi hari ini. Tidak bisa mengajukan dinas luar.');
                return false;
            }

            if (hasPendingIzin || hasApprovedIzin) {
                e.preventDefault();
                alert(hasApprovedIzin ? 'Anda sedang izin hari ini.' :
                    'Anda sudah mengajukan izin. Menunggu konfirmasi.');
                return false;
            }

            if (hasPendingMasuk) {
                e.preventDefault();
                alert('Anda memiliki absensi masuk yang menunggu konfirmasi. Tidak bisa mengajukan dinas luar.');
                return false;
            }

            if (hasPendingPulang) {
                e.preventDefault();
                alert('Anda memiliki absensi pulang yang menunggu konfirmasi. Tidak bisa mengajukan dinas luar.');
                return false;
            }

            const keterangan = document.getElementById('keteranganDinas').value.trim();
            if (keterangan.length < 10) {
                e.preventDefault();
                alert('Keterangan dinas minimal 10 karakter.');
                return false;
            }

            if (!confirm('Apakah Anda yakin ingin mengajukan dinas luar? Setelah dinas diajukan dan disetujui, Anda tetap wajib melakukan absensi pulang seperti biasa.')) {
                e.preventDefault();
                return false;
            }
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

                const subText = btnPulang.querySelector('.btn-subtitle');
                if (subText && !jamValidation.valid) {
                    subText.textContent = `Belum waktunya (absen pulang dapat dilakukan ${jamPulangIduka})`;
                } else if (subText && jamValidation.valid) {
                    subText.textContent = 'Klik untuk absen pulang';
                }
            }
        }

        updateButtonStatusBasedOnTime();
        setInterval(updateButtonStatusBasedOnTime, 60000);
    });

    // Auto-check jika tidak ada IDUKA yang valid
    document.addEventListener('DOMContentLoaded', function () {
        if (!hasValidIduka) {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'ui-alert ui-alert-danger mt-0 mb-3';
            alertDiv.innerHTML =
                '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="ui-alert-icon"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg><div class="ui-alert-content"><strong>Peringatan:</strong> Koordinat IDUKA belum diatur. Silakan hubungi administrator untuk mengatur koordinat IDUKA.</div>';
            document.querySelector('.card-body').prepend(alertDiv);
        }

        if ((sudahAbsenMasuk && sudahAbsenPulang) || sedangIzin) {
            locationSwitch.disabled = true;
        }

        updateButtonStates();
    });

    // Fungsi untuk load riwayat absensi
    function loadRiwayatAbsensi() {
        riwayatAbsensiTable.innerHTML = `
            <div class="abs-empty-state">
                <div class="abs-empty-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="abs-spin">
                        <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                    </svg>
                </div>
                <span>Memuat data riwayat absensi...</span>
            </div>
        `;

        fetch(`{{ route('absensi.riwayat.data') }}?page=${currentPage}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            riwayatData = data.data;
            renderRiwayatTable(data);
            renderPagination(data);
        })
        .catch(error => {
            console.error('Error:', error);
            riwayatAbsensiTable.innerHTML = `
                <div class="abs-empty-state" style="color:#b91c1c;">
                    <div class="abs-empty-icon" style="border-color:#fecaca;background:#fef2f2;color:#ef4444;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>
                        </svg>
                    </div>
                    <span>Gagal memuat data riwayat absensi. Silakan coba lagi.</span>
                </div>
            `;
        });
    }

    // Fungsi untuk render list riwayat absensi (Desktop & Mobile responsif)
    function renderRiwayatTable(data) {
        if (data.data.length === 0) {
            riwayatAbsensiTable.innerHTML = `
                <div class="abs-empty-state">
                    <div class="abs-empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/>
                            <path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
                        </svg>
                    </div>
                    <span>Tidak ada data riwayat absensi</span>
                </div>
            `;
            showingCount.textContent = '0';
            totalCount.textContent = '0';
            return;
        }

        let html = '';
        data.data.forEach(absensi => {
            const statusBadge = getStatusBadge(absensi);
            const jamMasuk = absensi.jam_masuk ? formatTime(absensi.jam_masuk) : '-';
            const jamPulang = absensi.jam_pulang ? formatTime(absensi.jam_pulang) : '-';
            const lokasi = absensi.lokasiIduka ? absensi.lokasiIduka.nama : '-';

            html += `
                <div class="abs-hist-item" data-id="${absensi.id}">
                    <div class="abs-hist-main">
                        <div class="abs-hist-date-row">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar">
                                <path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>
                            </svg>
                            <span>${formatDate(absensi.tanggal)}</span>
                        </div>
                        <div class="abs-hist-sub">
                            <span class="abs-hist-sub-chip">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                Masuk: <strong>${jamMasuk}</strong>
                            </span>
                            <span class="abs-hist-sub-chip">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l4 2"/></svg>
                                Pulang: <strong>${jamPulang}</strong>
                            </span>
                            <span class="abs-hist-sub-chip">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                                ${lokasi}
                            </span>
                        </div>
                    </div>

                    <div class="abs-hist-right">
                        ${statusBadge}
                        <button type="button" class="ui-btn ui-btn-outline ui-btn-sm view-detail" data-id="${absensi.id}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye">
                                <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                            Detail
                        </button>
                    </div>
                </div>
            `;
        });

        riwayatAbsensiTable.innerHTML = html;

        const start = (data.current_page - 1) * data.per_page + 1;
        const end = Math.min(data.current_page * data.per_page, data.total);
        showingCount.textContent = `${start}-${end}`;
        totalCount.textContent = data.total;

        document.querySelectorAll('.view-detail').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const id = this.getAttribute('data-id');
                showDetailAbsensi(id);
            });
        });

        document.querySelectorAll('#riwayatAbsensiTable [data-id]').forEach(row => {
            row.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                showDetailAbsensi(id);
            });
        });
    }

    // Fungsi untuk render pagination
    function renderPagination(data) {
        const prevChevron = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>`;
        const nextChevron = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>`;
        let html = '';

        if (data.current_page > 1) {
            html += `<li class="page-item">
                <a class="page-link" href="#" data-page="${data.current_page - 1}" aria-label="Previous">
                    ${prevChevron}
                </a>
            </li>`;
        } else {
            html += `<li class="page-item disabled">
                <a class="page-link" href="#" aria-label="Previous">
                    ${prevChevron}
                </a>
            </li>`;
        }

        for (let i = 1; i <= data.last_page; i++) {
            if (i === data.current_page) {
                html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
            } else {
                html += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
        }

        if (data.current_page < data.last_page) {
            html += `<li class="page-item">
                <a class="page-link" href="#" data-page="${data.current_page + 1}" aria-label="Next">
                    ${nextChevron}
                </a>
            </li>`;
        } else {
            html += `<li class="page-item disabled">
                <a class="page-link" href="#" aria-label="Next">
                    ${nextChevron}
                </a>
            </li>`;
        }

        paginationRiwayat.innerHTML = html;

        document.querySelectorAll('#paginationRiwayat .page-link[data-page]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                currentPage = parseInt(this.getAttribute('data-page'));
                loadRiwayatAbsensi();
            });
        });
    }

    // Fungsi untuk inisialisasi peta
    function initMap(absensi) {
        const masukLocation = {
            lat: parseFloat(absensi.latitude),
            lng: parseFloat(absensi.longitude)
        };

        let pulangLocation = null;
        if (absensi.latitude_pulang && absensi.longitude_pulang) {
            pulangLocation = {
                lat: parseFloat(absensi.latitude_pulang),
                lng: parseFloat(absensi.longitude_pulang)
            };
        }

        const map = new google.maps.Map(document.getElementById(`map-${absensi.id}`), {
            zoom: 15,
            center: masukLocation,
        });

        new google.maps.Marker({
            position: masukLocation,
            map: map,
            title: "Lokasi Absen Masuk",
            icon: {
                url: "http://maps.google.com/mapfiles/ms/icons/green-dot.png"
            }
        });

        if (pulangLocation) {
            new google.maps.Marker({
                position: pulangLocation,
                map: map,
                title: "Lokasi Absen Pulang",
                icon: {
                    url: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"
                }
            });

            const line = new google.maps.Polyline({
                path: [masukLocation, pulangLocation],
                geodesic: true,
                strokeColor: '#FF0000',
                strokeOpacity: 1.0,
                strokeWeight: 2
            });

            line.setMap(map);
        }
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }

    function formatDay(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { weekday: 'long' });
    }

    function formatTime(timeString) {
        const date = new Date(timeString);
        return date.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function formatDateTime(dateTimeString) {
        const date = new Date(dateTimeString);
        return date.toLocaleString('id-ID', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function calculateDuration(jamMasuk, jamPulang) {
        if (!jamMasuk || !jamPulang) return '-';

        const start = new Date(jamMasuk);
        const end = new Date(jamPulang);
        const diffMs = end - start;
        const diffHours = Math.floor(diffMs / (1000 * 60 * 60));
        const diffMinutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));

        return `${diffHours} jam ${diffMinutes} menit`;
    }

    function getStatusBadge(absensi) {
        if (absensi.status === 'izin') {
            return '<span class="ui-badge ui-badge-info"><span class="ui-badge-dot"></span>Izin</span>';
        } else if (absensi.status_dinas === 'disetujui') {
            return '<span class="ui-badge ui-badge-primary"><span class="ui-badge-dot"></span>Dinas Luar</span>';
        } else if (absensi.status === 'tepat_waktu') {
            return '<span class="ui-badge ui-badge-success"><span class="ui-badge-dot"></span>Tepat Waktu</span>';
        } else if (absensi.status === 'terlambat') {
            return '<span class="ui-badge ui-badge-warning"><span class="ui-badge-dot"></span>Terlambat</span>';
        } else {
            return `<span class="ui-badge ui-badge-secondary"><span class="ui-badge-dot"></span>${absensi.status}</span>`;
        }
    }

    function formatJenisIzin(jenis) {
        const jenisMap = {
            'sakit': 'Sakit',
            'keperluan_keluarga': 'Keperluan Keluarga',
            'keperluan_sekolah': 'Keperluan Sekolah',
            'lainnya': 'Lainnya'
        };
        return jenisMap[jenis] || jenis;
    }

    function formatJenisDinas(jenis) {
        const jenisMap = {
            'perusahaan': 'Perusahaan',
            'sekolah': 'Sekolah',
            'instansi_pemerintah': 'Instansi Pemerintah',
            'lainnya': 'Lainnya'
        };
        return jenisMap[jenis] || jenis;
    }

    function updateButtonStates() {
        if (pkEnded) {
            btnMasuk.disabled = true;
            btnPulang.disabled = true;
            btnIzin.disabled = true;
            btnDinas.disabled = true;
            btnMasuk.classList.remove('btn-success');
            btnMasuk.classList.add('btn-secondary');
            btnPulang.classList.remove('btn-warning');
            btnPulang.classList.add('btn-secondary');
            btnIzin.classList.remove('btn-info');
            btnIzin.classList.add('btn-secondary');
            btnDinas.classList.remove('btn-primary');
            btnDinas.classList.add('btn-secondary');

            locationStatus.textContent = 'Periode PKL telah selesai. Tidak dapat melakukan absensi.';
            distanceStatus.textContent = '';
            distanceValue.textContent = '-';
            userLocation.textContent = '-';
            return;
        }

        const absensiHariIni = @json($absensiHariIni);
        const sedangDinas = absensiHariIni && absensiHariIni.status_dinas === 'disetujui';
        const sudahAbsenMasuk = absensiHariIni && absensiHariIni.jam_masuk;
        const sudahAbsenPulang = absensiHariIni && absensiHariIni.jam_pulang;
        const sedangIzin = absensiHariIni && absensiHariIni.status === 'izin';

        if (isHoliday) {
            btnMasuk.disabled = true;
            btnMasuk.title = 'Hari ini adalah hari libur (' + holidayLabel + '). Absensi ditutup.';
            btnMasuk.classList.remove('btn-success');
            btnMasuk.classList.add('btn-secondary');

            btnPulang.disabled = true;
            btnPulang.title = 'Hari ini adalah hari libur (' + holidayLabel + '). Absensi ditutup.';
            btnPulang.classList.remove('btn-warning');
            btnPulang.classList.add('btn-secondary');

            btnIzin.disabled = true;
            btnIzin.title = 'Hari ini adalah hari libur (' + holidayLabel + '). Tidak bisa mengajukan izin.';
            btnIzin.classList.remove('btn-info');
            btnIzin.classList.add('btn-secondary');

            btnDinas.disabled = true;
            btnDinas.title = 'Hari ini adalah hari libur (' + holidayLabel + '). Tidak bisa mengajukan dinas luar.';
            btnDinas.classList.remove('btn-primary');
            btnDinas.classList.add('btn-secondary');

            locationStatus.textContent = "Hari ini adalah hari libur (" + holidayLabel + "). Absensi ditutup.";
            distanceStatus.textContent = "Hari libur";
            distanceStatus.className = "abs-telemetry-val text-danger";
            distanceValue.textContent = "-";
            userLocation.textContent = "-";

            locationSwitch.disabled = true;
            locationSwitch.checked = false;

            return;
        }

        if (pkEnded) {
            btnMasuk.disabled = true;
            btnPulang.disabled = true;
            btnMasuk.classList.remove('btn-success');
            btnMasuk.classList.add('btn-secondary');
            btnPulang.classList.remove('btn-warning');
            btnPulang.classList.add('btn-secondary');
            return;
        }

        if (sudahAbsenMasuk || sedangIzin || sedangDinas || hasPendingIzin || hasPendingDinas || hasApprovedIzin ||
            hasApprovedDinas || hasPendingMasuk) {
            btnMasuk.disabled = true;
            btnMasuk.classList.remove('btn-success');
            btnMasuk.classList.add('btn-secondary');
        } else if (!locationSwitch.checked) {
            btnMasuk.disabled = true;
            btnMasuk.classList.remove('btn-success');
            btnMasuk.classList.add('btn-secondary');
        } else {
            const jamValidation = validateJamMasuk();
            if (!jamValidation.valid) {
                btnMasuk.disabled = true;
                btnMasuk.title = jamValidation.message;
            } else {
                btnMasuk.disabled = false;
                btnMasuk.title = "";
            }
            btnMasuk.classList.add('btn-success');
            btnMasuk.classList.remove('btn-secondary');
        }

        if (sedangDinas && !sudahAbsenPulang) {
            const now = new Date();
            const jam12 = new Date();
            jam12.setHours(12, 0, 0, 0);

            if (now < jam12) {
                btnPulang.disabled = true;
                btnPulang.title = 'Absen pulang untuk dinas luar hanya dapat dilakukan setelah jam 12.00 siang.';
            } else {
                btnPulang.disabled = !locationSwitch.checked;
                btnPulang.title = '';
            }

            if (locationSwitch.checked && now >= jam12) {
                btnPulang.classList.add('btn-warning');
                btnPulang.classList.remove('btn-secondary');
            } else {
                btnPulang.classList.remove('btn-warning');
                btnPulang.classList.add('btn-secondary');
            }

            const btnSubtitle = btnPulang.querySelector('.btn-subtitle');
            if (btnSubtitle) {
                if (now < jam12) {
                    btnSubtitle.textContent = 'Dinas luar - bisa pulang setelah jam 12.00';
                } else {
                    btnSubtitle.textContent = 'Klik untuk absen pulang';
                }
            }
        } else if (!sudahAbsenMasuk || sudahAbsenPulang || sedangIzin || hasPendingIzin || hasApprovedIzin ||
            hasPendingMasuk || hasPendingPulang) {
            btnPulang.disabled = true;
            if (sudahAbsenPulang) {
                btnPulang.classList.remove('btn-warning');
                btnPulang.classList.add('btn-secondary');
            }
        } else if (hasPendingDinas && !hasApprovedDinas) {
            btnPulang.disabled = true;
            btnPulang.classList.remove('btn-warning');
            btnPulang.classList.add('btn-secondary');
        } else if (sudahAbsenMasuk && !sudahAbsenPulang && !sedangIzin) {
            const jamValidation = validateJamPulang();
            btnPulang.disabled = !jamValidation.valid || !locationSwitch.checked;
            btnPulang.title = jamValidation.valid ? "" : jamValidation.message;

            if (jamValidation.valid && locationSwitch.checked) {
                btnPulang.classList.add('btn-warning');
                btnPulang.classList.remove('btn-secondary');
            } else {
                btnPulang.classList.remove('btn-warning');
                btnPulang.classList.add('btn-secondary');
            }
        }

        if (absensiHariIni || hasPendingIzin || hasApprovedIzin || hasPendingDinas || hasApprovedDinas ||
            hasPendingMasuk || hasPendingPulang) {
            btnIzin.disabled = true;
            btnIzin.classList.remove('btn-info');
            btnIzin.classList.add('btn-secondary');
        } else {
            btnIzin.disabled = false;
            btnIzin.classList.add('btn-info');
            btnIzin.classList.remove('btn-secondary');
        }

        if (absensiHariIni || hasPendingDinas || hasApprovedDinas || hasPendingIzin || hasApprovedIzin ||
            hasPendingMasuk || hasPendingPulang) {
            btnDinas.disabled = true;
            btnDinas.classList.remove('btn-primary');
            btnDinas.classList.add('btn-secondary');
        } else {
            btnDinas.disabled = false;
            btnDinas.classList.add('btn-primary');
            btnDinas.classList.remove('btn-secondary');
        }
    }

    // Panggil updateButtonStates saat halaman dimuat
    updateButtonStates();
</script>
@endsection
