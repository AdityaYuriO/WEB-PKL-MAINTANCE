@extends('layout.main')

@section('content')
@php
    $userAuth         = Auth::user();
    $isLengkap        = $userAuth ? $userAuth->isDataPribadiLengkap() : false;
    $missingFields    = ($userAuth && !$isLengkap) ? $userAuth->getMissingDataPribadiFields() : [];
    $selectedAgama    = old('agama', $dataPribadi->agama ?? '');
    $agamaStandar     = ['Islam', 'Kristen Protestan', 'Kristen Katolik', 'Hindu', 'Buddha', 'Konghucu'];
    $isCustomAgama    = $selectedAgama && !in_array($selectedAgama, $agamaStandar);
    $selectedTtd      = old('ttd_ortu_option', $dataPribadi->ttd_ortu_option ?? ($dataPribadi->ttd_ortu === 'ayah' ? 'ayah' : ($dataPribadi->ttd_ortu === 'ibu' ? 'ibu' : ((!in_array($dataPribadi->ttd_ortu, ['ayah', 'ibu']) && ($dataPribadi->id ?? null)) ? 'manual' : 'ayah'))));
    $kelasLabel       = ($siswa && $siswa->kelas) ? trim(($siswa->kelas->kelas ?? '') . ' ' . ($siswa->kelas->name_kelas ?? '')) : null;
    $konkeLabel       = ($siswa && $siswa->konke) ? ($siswa->konke->name_konke ?? null) : null;
    $fotoProfilUrl    = ($userAuth && $userAuth->profile_photo) ? asset($userAuth->profile_photo) : asset('images/default.jpg');
@endphp

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">

<style>
#dataPribadiPage {
    --dp-bg: #ffffff;
    --dp-muted: #f8fafc;
    --dp-border: #e2e8f0;
    --dp-border-strong: #cbd5e1;
    --dp-text: #0f172a;
    --dp-text-muted: #64748b;
    --dp-text-soft: #94a3b8;
    --dp-primary: #2563eb;
    --dp-radius: 16px;
    --dp-shadow: 0 1px 3px rgba(15, 23, 42, 0.05), 0 1px 2px rgba(15, 23, 42, 0.03);
}

#dataPribadiPage .dp-alert {
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
#dataPribadiPage .dp-alert-icon {
    flex-shrink: 0;
    margin-top: 2px;
}
#dataPribadiPage .dp-alert-content {
    flex: 1;
    min-width: 0;
}
#dataPribadiPage .dp-alert-close {
    background: transparent;
    border: none;
    padding: 2px;
    cursor: pointer;
    opacity: 0.65;
    color: inherit;
    line-height: 1;
    flex-shrink: 0;
}
#dataPribadiPage .dp-alert-close:hover { opacity: 1; }
#dataPribadiPage .dp-alert-success { background-color: #f0fdf4; border-color: #bbf7d0; color: #166534; }
#dataPribadiPage .dp-alert-danger  { background-color: #fef2f2; border-color: #fecaca; color: #991b1b; }
#dataPribadiPage .dp-alert-warning { background-color: #fffbeb; border-color: #fde68a; color: #92400e; }

/* ---- Card Primitive ---- */
#dataPribadiPage .dp-card {
    background-color: var(--dp-bg);
    border: 1px solid var(--dp-border);
    border-radius: var(--dp-radius);
    box-shadow: var(--dp-shadow);
    margin-bottom: 16px;
    overflow: hidden;
    scroll-margin-top: 84px;
}
#dataPribadiPage .dp-card-header {
    padding: 15px 20px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    background-color: #ffffff;
}
#dataPribadiPage .dp-card-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1 1 220px;
    min-width: 0;
}
#dataPribadiPage .dp-head-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background-color: #eff6ff;
    border: 1px solid #dbeafe;
    color: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
#dataPribadiPage .dp-head-icon svg {
    width: 18px;
    height: 18px;
}
#dataPribadiPage .dp-card-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--dp-text);
    margin: 0;
    letter-spacing: -0.015em;
    line-height: 1.3;
}
#dataPribadiPage .dp-card-subtitle {
    font-size: 12.5px;
    color: var(--dp-text-muted);
    margin: 2px 0 0;
    line-height: 1.4;
}
#dataPribadiPage .dp-card-body {
    padding: 18px 20px;
}

/* ---- Profile Hero Summary Card ---- */
#dataPribadiPage .dp-hero {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}
#dataPribadiPage .dp-hero-identity {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    flex: 1 1 360px;
    min-width: 0;
}
#dataPribadiPage .dp-avatar-wrap {
    position: relative;
    flex-shrink: 0;
}
#dataPribadiPage .dp-avatar {
    width: 72px;
    height: 72px;
    border-radius: 9999px;
    object-fit: cover;
    border: 2px solid #e2e8f0;
    background-color: #f8fafc;
    display: block;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}
#dataPribadiPage .dp-photo-error {
    margin-top: 8px;
    padding: 8px 11px;
    border-radius: 9px;
    background-color: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626;
    font-size: 12px;
    font-weight: 600;
    display: none;
    align-items: center;
    gap: 6px;
}
#dataPribadiPage .dp-photo-error.is-visible {
    display: flex;
}
#dataPribadiPage .dp-hero-info {
    flex: 1;
    min-width: 0;
}
#dataPribadiPage .dp-hero-name-row {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
#dataPribadiPage .dp-hero-name {
    font-size: 18px;
    font-weight: 700;
    color: var(--dp-text);
    margin: 0;
    letter-spacing: -0.02em;
    line-height: 1.3;
    overflow-wrap: break-word;
}
#dataPribadiPage .dp-hero-sub {
    font-size: 13px;
    color: var(--dp-text-muted);
    margin: 3px 0 0;
    line-height: 1.45;
}
#dataPribadiPage .dp-hero-chips {
    margin-top: 10px;
    display: flex;
    align-items: center;
    gap: 7px;
    flex-wrap: wrap;
}
#dataPribadiPage .dp-meta-chip {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 8px;
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    font-size: 12px;
    font-weight: 600;
    color: #334155;
    max-width: 100%;
}
#dataPribadiPage .dp-meta-chip svg {
    width: 13px;
    height: 13px;
    color: #64748b;
    flex-shrink: 0;
}
#dataPribadiPage .dp-meta-chip.is-empty {
    color: #94a3b8;
    font-weight: 500;
}
#dataPribadiPage .dp-hero-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

/* Status Pills */
#dataPribadiPage .dp-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 9999px;
    border: 1px solid;
    white-space: nowrap;
    line-height: 1.3;
}
#dataPribadiPage .dp-badge svg {
    width: 12.5px;
    height: 12.5px;
    flex-shrink: 0;
}
#dataPribadiPage .dp-badge-success { background-color: #f0fdf4; border-color: #bbf7d0; color: #15803d; }
#dataPribadiPage .dp-badge-warning { background-color: #fffbeb; border-color: #fde68a; color: #b45309; }
#dataPribadiPage .dp-badge-slate   { background-color: #f1f5f9; border-color: #cbd5e1; color: #475569; }

/* Completeness Notice Strip */
#dataPribadiPage .dp-missing-strip {
    margin-top: 14px;
    padding: 11px 14px;
    border-radius: 11px;
    background-color: #fffbeb;
    border: 1px solid #fde68a;
    color: #92400e;
    display: flex;
    align-items: flex-start;
    gap: 9px;
    font-size: 12.5px;
    line-height: 1.45;
}
#dataPribadiPage .dp-missing-strip svg {
    width: 16px;
    height: 16px;
    flex-shrink: 0;
    margin-top: 2px;
}

/* ---- Settings Layout (Desktop 2-Column Sidebar + Sections) ---- */
#dataPribadiPage .dp-settings-layout {
    display: grid;
    grid-template-columns: 230px minmax(0, 1fr);
    gap: 18px;
    align-items: start;
}
#dataPribadiPage .dp-sidebar {
    position: sticky;
    top: 82px;
    min-width: 0;
}
#dataPribadiPage .dp-nav-card {
    background-color: #ffffff;
    border: 1px solid var(--dp-border);
    border-radius: 14px;
    padding: 8px;
    box-shadow: var(--dp-shadow);
    display: flex;
    flex-direction: column;
    gap: 3px;
}
#dataPribadiPage .dp-nav-link {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 9px 11px;
    border-radius: 9px;
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    text-decoration: none;
    transition: background-color 0.14s ease, color 0.14s ease;
    border: 1px solid transparent;
}
#dataPribadiPage .dp-nav-link svg {
    width: 15px;
    height: 15px;
    color: #64748b;
    flex-shrink: 0;
}
#dataPribadiPage .dp-nav-link:hover {
    background-color: #f8fafc;
    color: #0f172a;
}
#dataPribadiPage .dp-nav-link.is-active {
    background-color: #eff6ff;
    border-color: #dbeafe;
    color: #1d4ed8;
}
#dataPribadiPage .dp-nav-link.is-active svg {
    color: #2563eb;
}
#dataPribadiPage .dp-main-col {
    min-width: 0;
}

/* ---- Form Controls (shadcn-style, immune to app.css .btn/.card) ---- */
#dataPribadiPage .dp-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px 16px;
}
#dataPribadiPage .dp-col-full {
    grid-column: 1 / -1;
}
#dataPribadiPage .dp-field {
    min-width: 0;
}
#dataPribadiPage .dp-label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 6px;
    font-size: 12.5px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 6px;
}
#dataPribadiPage .dp-label-text {
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
#dataPribadiPage .dp-req {
    color: #dc2626;
    font-weight: 700;
}
#dataPribadiPage .dp-opt-tag {
    font-size: 11px;
    font-weight: 500;
    color: #94a3b8;
}
#dataPribadiPage .dp-input-wrap {
    position: relative;
}
#dataPribadiPage .dp-input-icon {
    position: absolute;
    left: 11px;
    top: 50%;
    transform: translateY(-50%);
    width: 15px;
    height: 15px;
    color: #94a3b8;
    pointer-events: none;
}
#dataPribadiPage .dp-input-icon.is-top {
    top: 13px;
    transform: none;
}
#dataPribadiPage .dp-input,
#dataPribadiPage .dp-select,
#dataPribadiPage .dp-textarea {
    width: 100%;
    padding: 9px 12px;
    font-size: 13.5px;
    color: #0f172a;
    background-color: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
    display: block;
    line-height: 1.45;
}
#dataPribadiPage .dp-input.has-icon,
#dataPribadiPage .dp-select.has-icon,
#dataPribadiPage .dp-textarea.has-icon {
    padding-left: 35px;
}
#dataPribadiPage .dp-input.has-right-btn {
    padding-right: 40px;
}
#dataPribadiPage .dp-textarea {
    min-height: 82px;
    resize: vertical;
}
#dataPribadiPage .dp-input:focus,
#dataPribadiPage .dp-select:focus,
#dataPribadiPage .dp-textarea:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}
#dataPribadiPage .dp-input.is-invalid,
#dataPribadiPage .dp-select.is-invalid,
#dataPribadiPage .dp-textarea.is-invalid {
    border-color: #ef4444;
    background-color: #fffafa;
}
#dataPribadiPage .dp-hint {
    display: block;
    font-size: 11.5px;
    color: #64748b;
    margin-top: 5px;
    line-height: 1.35;
}
#dataPribadiPage .dp-error {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #dc2626;
    margin-top: 5px;
}

/* Read-only system field styling */
#dataPribadiPage .dp-readonly-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    padding: 9px 12px;
    border-radius: 10px;
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    color: #334155;
    font-size: 13.5px;
    font-weight: 500;
    min-height: 39px;
}
#dataPribadiPage .dp-readonly-left {
    display: flex;
    align-items: center;
    gap: 7px;
    min-width: 0;
}
#dataPribadiPage .dp-readonly-left svg {
    width: 14px;
    height: 14px;
    color: #64748b;
    flex-shrink: 0;
}
#dataPribadiPage .dp-readonly-val {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
#dataPribadiPage .dp-readonly-val.is-empty {
    color: #94a3b8;
    font-style: normal;
}
#dataPribadiPage .dp-readonly-tag {
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #64748b;
    background-color: #e2e8f0;
    padding: 2px 7px;
    border-radius: 6px;
    flex-shrink: 0;
}

/* Password Eye Button */
#dataPribadiPage .dp-pw-toggle {
    position: absolute;
    right: 6px;
    top: 50%;
    transform: translateY(-50%);
    width: 30px;
    height: 30px;
    border-radius: 7px;
    border: none;
    background: transparent;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background-color 0.14s ease, color 0.14s ease;
}
#dataPribadiPage .dp-pw-toggle:hover {
    background-color: #f1f5f9;
    color: #0f172a;
}

/* Parent Sub-Panels (Ayah / Ibu / Wali) */
#dataPribadiPage .dp-subpanel-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 14px;
}
#dataPribadiPage .dp-subpanel {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 13px;
    padding: 15px 16px;
    min-width: 0;
}
#dataPribadiPage .dp-subpanel-head {
    display: flex;
    align-items: center;
    gap: 8px;
    padding-bottom: 10px;
    margin-bottom: 13px;
    border-bottom: 1px solid #e2e8f0;
    font-size: 13.5px;
    font-weight: 700;
    color: #0f172a;
}
#dataPribadiPage .dp-subpanel-head svg {
    width: 16px;
    height: 16px;
    color: #2563eb;
    flex-shrink: 0;
}
#dataPribadiPage .dp-divider {
    height: 1px;
    background-color: #f1f5f9;
    margin: 18px 0;
}
#dataPribadiPage .dp-manual-box {
    margin-top: 12px;
    padding: 14px;
    border-radius: 12px;
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
}

/* ---- Buttons & Save Footer ---- */
#dataPribadiPage .dp-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    height: 38px;
    padding: 0 16px;
    border-radius: 10px;
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
#dataPribadiPage .dp-btn:active:not(:disabled) {
    transform: scale(0.985);
}
#dataPribadiPage .dp-btn:disabled {
    opacity: 0.65;
    cursor: not-allowed;
}
#dataPribadiPage .dp-btn svg {
    width: 15px;
    height: 15px;
    flex-shrink: 0;
}
#dataPribadiPage .dp-btn-sm {
    height: 32px;
    padding: 0 12px;
    font-size: 12.5px;
    border-radius: 8px;
}
#dataPribadiPage .dp-btn-primary {
    background-color: #2563eb;
    border-color: #2563eb;
    color: #ffffff;
}
#dataPribadiPage .dp-btn-primary:hover:not(:disabled) {
    background-color: #1d4ed8;
    border-color: #1d4ed8;
    color: #ffffff;
}
#dataPribadiPage .dp-btn-outline {
    background-color: #ffffff;
    border-color: #cbd5e1;
    color: #334155;
}
#dataPribadiPage .dp-btn-outline:hover:not(:disabled) {
    background-color: #f8fafc;
    border-color: #94a3b8;
    color: #0f172a;
}
#dataPribadiPage .dp-action-bar {
    background-color: #ffffff;
    border: 1px solid var(--dp-border);
    border-radius: 14px;
    box-shadow: var(--dp-shadow);
    padding: 14px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}
#dataPribadiPage .dp-action-note {
    font-size: 12.5px;
    color: #64748b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 6px;
}
#dataPribadiPage .dp-action-note svg {
    width: 14px;
    height: 14px;
    color: #94a3b8;
    flex-shrink: 0;
}
#dataPribadiPage .dp-action-btns {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-left: auto;
}

/* ---- Responsive Breakpoints ---- */
@media (max-width: 991.98px) {
    #dataPribadiPage .dp-settings-layout {
        grid-template-columns: 1fr;
        gap: 14px;
    }
    #dataPribadiPage .dp-sidebar {
        position: static;
    }
    #dataPribadiPage .dp-nav-card {
        flex-direction: row;
        flex-wrap: wrap;
        gap: 6px;
        padding: 8px;
    }
    #dataPribadiPage .dp-nav-link {
        padding: 7px 11px;
        font-size: 12px;
        background-color: #f8fafc;
        border-color: #e2e8f0;
    }
    #dataPribadiPage .dp-subpanel-grid {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 767.98px) {
    #dataPribadiPage .dp-card-header { padding: 13px 15px; }
    #dataPribadiPage .dp-card-body   { padding: 14px 15px; }
    #dataPribadiPage .dp-hero {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
    }
    #dataPribadiPage .dp-hero-identity {
        flex: none;
        width: 100%;
        gap: 12px;
    }
    #dataPribadiPage .dp-avatar {
        width: 60px;
        height: 60px;
        border-radius: 9999px;
    }
    #dataPribadiPage .dp-hero-name { font-size: 16.5px; }
    #dataPribadiPage .dp-hero-actions {
        width: 100%;
    }
    #dataPribadiPage .dp-hero-actions > .dp-btn {
        width: 100%;
    }
    #dataPribadiPage .dp-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    #dataPribadiPage .dp-subpanel {
        padding: 13px 14px;
    }
    #dataPribadiPage .dp-action-bar {
        padding: 13px 15px;
        flex-direction: column;
        align-items: stretch;
    }
    #dataPribadiPage .dp-action-btns {
        margin-left: 0;
        width: 100%;
    }
    #dataPribadiPage .dp-action-btns .dp-btn {
        flex: 1;
    }
}

/* ---- Profile Photo Cropper Modal ---- */
#cropPhotoModal .modal-content {
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 20px 50px rgba(15, 23, 42, 0.18);
    overflow: hidden;
}
#cropPhotoModal .dp-crop-header {
    padding: 18px 22px 14px;
    border-bottom: 1px solid #e2e8f0;
    background-color: #ffffff;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
}
#cropPhotoModal .dp-crop-title-wrap {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    min-width: 0;
}
#cropPhotoModal .dp-crop-title-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background-color: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #2563eb;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
#cropPhotoModal .dp-crop-title {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.3;
}
#cropPhotoModal .dp-crop-subtitle {
    margin: 2px 0 0;
    font-size: 12.5px;
    color: #64748b;
    line-height: 1.4;
}
#cropPhotoModal .dp-crop-close {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background-color: #ffffff;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    transition: background-color 0.15s ease, color 0.15s ease;
}
#cropPhotoModal .dp-crop-close:hover:not(:disabled) {
    background-color: #f1f5f9;
    color: #0f172a;
}
#cropPhotoModal .dp-crop-close:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
#cropPhotoModal .dp-crop-body {
    padding: 18px 22px;
    background-color: #f8fafc;
}
#cropPhotoModal .dp-crop-layout {
    display: flex;
    align-items: flex-start;
    gap: 18px;
}
#cropPhotoModal .dp-crop-main {
    flex: 1 1 auto;
    min-width: 0;
}
#cropPhotoModal .dp-crop-stage {
    width: 100%;
    height: 340px;
    max-height: 54vh;
    background-color: #0f172a;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    overflow: hidden;
    position: relative;
}
#cropPhotoModal #dpCropTargetImg {
    display: block;
    max-width: 100%;
}
#cropPhotoModal .cropper-bg {
    background-image: none !important;
    background-color: #0f172a !important;
}
#cropPhotoModal .cropper-modal {
    background-color: #020617 !important;
    opacity: 0.68 !important;
}
#cropPhotoModal .cropper-view-box {
    outline: 2px solid #ffffff;
    outline-color: rgba(255, 255, 255, 0.92);
}
#cropPhotoModal .cropper-line {
    background-color: rgba(255, 255, 255, 0.65);
}
#cropPhotoModal .cropper-point {
    background-color: #2563eb;
    width: 8px;
    height: 8px;
    opacity: 1;
    border-radius: 9999px;
}
#cropPhotoModal .dp-crop-toolbar {
    margin-top: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    flex-wrap: wrap;
}
#cropPhotoModal .dp-crop-hint {
    margin: 8px 0 0;
    font-size: 12px;
    color: #64748b;
    text-align: center;
    line-height: 1.4;
}
#cropPhotoModal .dp-crop-side {
    width: 196px;
    flex-shrink: 0;
    background-color: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 14px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 10px;
}
#cropPhotoModal .dp-crop-side-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #64748b;
}
#cropPhotoModal .dp-crop-preview-ring {
    padding: 4px;
    border-radius: 9999px;
    border: 2px solid #e2e8f0;
    background-color: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
#cropPhotoModal #dpCropLivePreview {
    width: 112px;
    height: 112px;
    border-radius: 9999px;
    overflow: hidden;
    background-color: #f1f5f9;
}
#cropPhotoModal .dp-crop-preview-meta {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    color: #64748b;
    line-height: 1.35;
}
#cropPhotoModal .dp-crop-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 3px 9px;
    border-radius: 9999px;
    background-color: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #1d4ed8;
    font-size: 11px;
    font-weight: 600;
}
#cropPhotoModal .dp-crop-error {
    margin-top: 12px;
    padding: 9px 12px;
    border-radius: 9px;
    background-color: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626;
    font-size: 12.5px;
    font-weight: 600;
    display: none;
    align-items: center;
    gap: 8px;
}
#cropPhotoModal .dp-crop-error.is-visible {
    display: flex;
}
#cropPhotoModal .dp-crop-footer {
    padding: 14px 22px;
    border-top: 1px solid #e2e8f0;
    background-color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 9px;
    flex-wrap: wrap;
}
#cropPhotoModal .dp-crop-btn {
    height: 36px;
    padding: 0 14px;
    border-radius: 9px;
    font-size: 13px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    cursor: pointer;
    border: 1px solid transparent;
    transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
}
#cropPhotoModal .dp-crop-btn svg {
    width: 15px;
    height: 15px;
    flex-shrink: 0;
}
#cropPhotoModal .dp-crop-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
#cropPhotoModal .dp-crop-btn-outline {
    background-color: #ffffff;
    border-color: #cbd5e1;
    color: #334155;
}
#cropPhotoModal .dp-crop-btn-outline:hover:not(:disabled) {
    background-color: #f8fafc;
    border-color: #94a3b8;
    color: #0f172a;
}
#cropPhotoModal .dp-crop-btn-primary {
    background-color: #2563eb;
    border-color: #2563eb;
    color: #ffffff;
}
#cropPhotoModal .dp-crop-btn-primary:hover:not(:disabled) {
    background-color: #1d4ed8;
    border-color: #1d4ed8;
}
@media (max-width: 767.98px) {
    #cropPhotoModal .dp-crop-header,
    #cropPhotoModal .dp-crop-body,
    #cropPhotoModal .dp-crop-footer {
        padding-left: 16px;
        padding-right: 16px;
    }
    #cropPhotoModal .dp-crop-layout {
        flex-direction: column;
    }
    #cropPhotoModal .dp-crop-main {
        width: 100%;
    }
    #cropPhotoModal .dp-crop-stage {
        height: 270px;
    }
    #cropPhotoModal .dp-crop-side {
        width: 100%;
        flex-direction: row;
        text-align: left;
        padding: 12px 14px;
        gap: 14px;
    }
    #cropPhotoModal #dpCropLivePreview {
        width: 76px;
        height: 76px;
    }
    #cropPhotoModal .dp-crop-preview-meta {
        align-items: flex-start;
    }
    #cropPhotoModal .dp-crop-toolbar {
        gap: 6px;
    }
    #cropPhotoModal .dp-crop-toolbar .dp-crop-btn {
        flex: 1;
        padding: 0 10px;
    }
    #cropPhotoModal .dp-crop-footer {
        flex-direction: column-reverse;
    }
    #cropPhotoModal .dp-crop-footer .dp-crop-btn {
        width: 100%;
    }
}
</style>

<div id="dataPribadiPage" class="container-xxl flex-grow-1 container-p-y">

    @if (session('success'))
        <div class="dp-alert dp-alert-success alert-dismissible fade show" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"
                 class="dp-alert-icon">
                <circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>
            </svg>
            <div class="dp-alert-content"><strong>Tersimpan!</strong> {{ session('success') }}</div>
            <button type="button" class="dp-alert-close" data-bs-dismiss="alert" aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="dp-alert dp-alert-danger alert-dismissible fade show" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"
                 class="dp-alert-icon">
                <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/>
            </svg>
            <div class="dp-alert-content"><strong>Gagal!</strong> {{ session('error') }}</div>
            <button type="button" class="dp-alert-close" data-bs-dismiss="alert" aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="dp-alert dp-alert-danger alert-dismissible fade show" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"
                 class="dp-alert-icon">
                <circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/>
            </svg>
            <div class="dp-alert-content">
                <strong>Terdapat kesalahan pada isian:</strong>
                <ul class="mb-0 mt-1 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="dp-alert-close" data-bs-dismiss="alert" aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
    @endif

    <div class="dp-card">
        <div class="dp-card-body">
            <div class="dp-hero">
                <div class="dp-hero-identity">
                    <div class="dp-avatar-wrap">
                        <img src="{{ $fotoProfilUrl }}"
                             alt="Foto Profil {{ $siswa->name ?? 'Siswa' }}"
                             class="dp-avatar"
                             id="dpAvatarPreview"
                             data-saved-src="{{ $fotoProfilUrl }}">
                    </div>
                    <div class="dp-hero-info">
                        <div class="dp-hero-name-row">
                            <h4 class="dp-hero-name">{{ $siswa->name ?? ($userAuth->name ?? 'Pengaturan Data Pribadi') }}</h4>
                            @if ($isLengkap)
                                <span class="dp-badge dp-badge-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                                    Data Lengkap
                                </span>
                            @else
                                <span class="dp-badge dp-badge-warning">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                                    Perlu Dilengkapi
                                </span>
                            @endif
                        </div>

                        <p class="dp-hero-sub">Kelola identitas pribadi, informasi akademik, dan data orang tua/wali untuk keperluan PKL.</p>

                        <div class="dp-hero-chips">
                            <span class="dp-meta-chip {{ empty($siswa->nip) ? 'is-empty' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="9" y2="9"/><line x1="4" x2="20" y1="15" y2="15"/><line x1="10" x2="8" y1="3" y2="21"/><line x1="16" x2="14" y1="3" y2="21"/></svg>
                                NIS: {{ $siswa->nip ?: 'Belum diisi' }}
                            </span>
                            <span class="dp-meta-chip {{ !$kelasLabel ? 'is-empty' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                                {{ $kelasLabel ?: 'Kelas belum dipilih' }}
                            </span>
                            <span class="dp-meta-chip {{ !$konkeLabel ? 'is-empty' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="14" x="3" y="7" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                {{ $konkeLabel ?: 'Jurusan belum dipilih' }}
                            </span>
                            @if (!empty($siswa->tahun_ajaran))
                                <span class="dp-meta-chip">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                                    TA {{ $siswa->tahun_ajaran }}
                                </span>
                            @endif
                        </div>

                        <div class="dp-photo-error" id="dpPhotoError" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                            <span id="dpPhotoErrorText"></span>
                        </div>
                    </div>
                </div>

                <div class="dp-hero-actions">
                    <input type="file"
                           id="profilePhotoInput"
                           accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                           class="d-none"
                           style="display:none;"
                           aria-label="Pilih file foto profil">

                    <button type="button" class="dp-btn dp-btn-outline dp-btn-sm" id="btnTriggerPhotoPicker">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                        <span>Ubah Foto Profil</span>
                    </button>
                </div>
            </div>

            @if (!$isLengkap && count($missingFields) > 0)
                <div class="dp-missing-strip">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    <div>
                        <strong>Bagian yang belum lengkap:</strong> {{ implode(', ', $missingFields) }}. Silakan lengkapi kolom di bawah lalu simpan perubahan.
                    </div>
                </div>
            @endif
        </div>
    </div>

    <form action="{{ route('siswa.data_pribadi.store') }}" method="POST" id="dataPribadiForm">
        @csrf
        <input type="hidden" name="id" value="{{ old('id', $dataPribadi->id ?? '') }}">

        <div class="dp-settings-layout">
            {{-- Sidebar Navigation --}}
            <aside class="dp-sidebar" aria-label="Navigasi bagian data pribadi">
                <nav class="dp-nav-card">
                    <a href="#sec-profil" class="dp-nav-link is-active" data-dp-nav="sec-profil">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span>Identitas & Akun</span>
                    </a>
                    <a href="#sec-pribadi" class="dp-nav-link" data-dp-nav="sec-pribadi">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><circle cx="12" cy="10" r="3"/><path d="M7 20v-1a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1"/></svg>
                        <span>Informasi Pribadi</span>
                    </a>
                    <a href="#sec-akademik" class="dp-nav-link" data-dp-nav="sec-akademik">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                        <span>Informasi Akademik</span>
                    </a>
                    <a href="#sec-ortu" class="dp-nav-link" data-dp-nav="sec-ortu">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span>Orang Tua / Wali</span>
                    </a>
                </nav>
            </aside>

            {{-- Right Column: Grouped Profile Setting Cards --}}
            <div class="dp-main-col">

                {{-- 1. Identitas & Akun Siswa --}}
                <section id="sec-profil" class="dp-card">
                    <div class="dp-card-header">
                        <div class="dp-card-header-left">
                            <div class="dp-head-icon" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <div>
                                <h5 class="dp-card-title">Identitas & Akun Siswa</h5>
                                <p class="dp-card-subtitle">Informasi utama akun dan akses login siswa</p>
                            </div>
                        </div>
                    </div>

                    <div class="dp-card-body">
                        <div class="dp-grid">
                            <div class="dp-field dp-col-full">
                                <label class="dp-label" for="inputName">
                                    <span class="dp-label-text">Nama Lengkap <span class="dp-req">*</span></span>
                                </label>
                                <div class="dp-input-wrap">
                                    <svg class="dp-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    <input type="text"
                                           id="inputName"
                                           name="name"
                                           class="dp-input has-icon @error('name') is-invalid @enderror"
                                           value="{{ old('name', $siswa->name ?? '') }}"
                                           placeholder="Masukkan nama lengkap sesuai ijazah"
                                           required>
                                </div>
                                @error('name')
                                    <span class="dp-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="dp-field">
                                <label class="dp-label" for="inputNip">
                                    <span class="dp-label-text">Nomor Induk Siswa (NIS) <span class="dp-req">*</span></span>
                                </label>
                                <div class="dp-input-wrap">
                                    <svg class="dp-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="9" y2="9"/><line x1="4" x2="20" y1="15" y2="15"/><line x1="10" x2="8" y1="3" y2="21"/><line x1="16" x2="14" y1="3" y2="21"/></svg>
                                    <input type="text"
                                           id="inputNip"
                                           name="nip"
                                           class="dp-input has-icon @error('nip') is-invalid @enderror"
                                           value="{{ old('nip', $siswa->nip ?? '') }}"
                                           placeholder="Contoh: 23241001"
                                           required>
                                </div>
                                @error('nip')
                                    <span class="dp-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="dp-field">
                                <label class="dp-label" for="inputEmail">
                                    <span class="dp-label-text">Alamat Email <span class="dp-req">*</span></span>
                                </label>
                                <div class="dp-input-wrap">
                                    <svg class="dp-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                    <input type="email"
                                           id="inputEmail"
                                           name="email"
                                           class="dp-input has-icon @error('email') is-invalid @enderror"
                                           value="{{ old('email', $siswa->email ?? '') }}"
                                           placeholder="nama@email.com"
                                           required>
                                </div>
                                @error('email')
                                    <span class="dp-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="dp-field dp-col-full">
                                <label class="dp-label">
                                    <span class="dp-label-text">Peran Akun</span>
                                </label>
                                <div class="dp-readonly-box">
                                    <div class="dp-readonly-left">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                        <span class="dp-readonly-val">{{ ucfirst($siswa->role ?? 'Siswa') }}</span>
                                    </div>
                                    <span class="dp-readonly-tag">Read-only</span>
                                </div>
                            </div>

                            <div class="dp-field">
                                <label class="dp-label" for="password-showhide">
                                    <span class="dp-label-text">Ubah Password</span>
                                    <span class="dp-opt-tag">Opsional</span>
                                </label>
                                <div class="dp-input-wrap">
                                    <svg class="dp-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                    <input type="password"
                                           class="dp-input has-icon has-right-btn @error('password') is-invalid @enderror"
                                           id="password-showhide"
                                           name="password"
                                           autocomplete="new-password"
                                           placeholder="Kosongkan jika tak ingin diubah">
                                    <button type="button"
                                            class="dp-pw-toggle toggle-password"
                                            data-target="password-showhide"
                                            tabindex="-1"
                                            aria-label="Tampilkan atau sembunyikan password">
                                        <svg class="pw-icon-hide" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                                        <svg class="pw-icon-show" style="display:none;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                </div>
                                <small class="dp-hint">Minimal 8 karakter apabila diisi.</small>
                                @error('password')
                                    <span class="dp-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="dp-field">
                                <label class="dp-label" for="password-confirm-showhide">
                                    <span class="dp-label-text">Konfirmasi Password</span>
                                    <span class="dp-opt-tag">Opsional</span>
                                </label>
                                <div class="dp-input-wrap">
                                    <svg class="dp-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                    <input type="password"
                                           class="dp-input has-icon has-right-btn @error('password') is-invalid @enderror"
                                           id="password-confirm-showhide"
                                           name="password_confirmation"
                                           autocomplete="new-password"
                                           placeholder="Ulangi password baru">
                                    <button type="button"
                                            class="dp-pw-toggle toggle-password"
                                            data-target="password-confirm-showhide"
                                            tabindex="-1"
                                            aria-label="Tampilkan atau sembunyikan konfirmasi password">
                                        <svg class="pw-icon-hide" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                                        <svg class="pw-icon-show" style="display:none;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                </div>
                                <small class="dp-hint">Harus sama dengan password baru.</small>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- 2. Informasi Pribadi --}}
                <section id="sec-pribadi" class="dp-card">
                    <div class="dp-card-header">
                        <div class="dp-card-header-left">
                            <div class="dp-head-icon" style="background-color:#f0f9ff;border-color:#bae6fd;color:#0284c7;" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><circle cx="12" cy="10" r="3"/><path d="M7 20v-1a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1"/></svg>
                            </div>
                            <div>
                                <h5 class="dp-card-title">Informasi Pribadi</h5>
                                <p class="dp-card-subtitle">Data demografi, kontak WhatsApp, dan alamat domisili</p>
                            </div>
                        </div>
                    </div>

                    <div class="dp-card-body">
                        <div class="dp-grid">
                            <div class="dp-field">
                                <label class="dp-label" for="selectJk">
                                    <span class="dp-label-text">Jenis Kelamin <span class="dp-req">*</span></span>
                                </label>
                                <div class="dp-input-wrap">
                                    <svg class="dp-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                    <select id="selectJk" class="dp-select has-icon @error('jk') is-invalid @enderror" name="jk" required>
                                        <option value="">Pilih jenis kelamin</option>
                                        <option value="laki_laki" {{ old('jk', $dataPribadi->jk ?? '') == 'laki_laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="perempuan" {{ old('jk', $dataPribadi->jk ?? '') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                @error('jk')
                                    <span class="dp-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="dp-field">
                                <label class="dp-label" for="agama-select">
                                    <span class="dp-label-text">Agama <span class="dp-req">*</span></span>
                                </label>
                                <select name="agama" class="dp-select @error('agama') is-invalid @enderror" id="agama-select" required>
                                    <option value="" disabled {{ $selectedAgama == '' ? 'selected' : '' }}>Pilih agama</option>
                                    <option value="Islam" {{ $selectedAgama == 'Islam' ? 'selected' : '' }}>Islam</option>
                                    <option value="Kristen Protestan" {{ $selectedAgama == 'Kristen Protestan' ? 'selected' : '' }}>Kristen Protestan</option>
                                    <option value="Kristen Katolik" {{ $selectedAgama == 'Kristen Katolik' ? 'selected' : '' }}>Kristen Katolik</option>
                                    <option value="Hindu" {{ $selectedAgama == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Buddha" {{ $selectedAgama == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                    <option value="Konghucu" {{ $selectedAgama == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                    <option value="Lainnya" {{ ($selectedAgama === 'Lainnya' || $isCustomAgama) ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('agama')
                                    <span class="dp-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="dp-field dp-col-full" id="agama-lainnya-container" style="display: none;">
                                <label class="dp-label" for="agama-lainnya-input">
                                    <span class="dp-label-text">Agama Lainnya <span class="dp-req">*</span></span>
                                </label>
                                <input type="text"
                                       class="dp-input"
                                       name="agama_lainnya"
                                       id="agama-lainnya-input"
                                       placeholder="Tuliskan agama / kepercayaan"
                                       value="{{ $isCustomAgama ? $selectedAgama : old('agama_lainnya', '') }}">
                            </div>

                            <div class="dp-field">
                                <label class="dp-label" for="inputTempatLhr">
                                    <span class="dp-label-text">Tempat Lahir <span class="dp-req">*</span></span>
                                </label>
                                <div class="dp-input-wrap">
                                    <svg class="dp-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                                    <input type="text"
                                           id="inputTempatLhr"
                                           name="tempat_lhr"
                                           class="dp-input has-icon @error('tempat_lhr') is-invalid @enderror"
                                           value="{{ old('tempat_lhr', $dataPribadi->tempat_lhr ?? '') }}"
                                           placeholder="Contoh: Ciamis"
                                           required>
                                </div>
                                @error('tempat_lhr')
                                    <span class="dp-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="dp-field">
                                <label class="dp-label" for="inputTglLahir">
                                    <span class="dp-label-text">Tanggal Lahir <span class="dp-req">*</span></span>
                                </label>
                                <input type="date"
                                       id="inputTglLahir"
                                       name="tgl_lahir"
                                       class="dp-input @error('tgl_lahir') is-invalid @enderror"
                                       value="{{ old('tgl_lahir', $dataPribadi->tgl_lahir ?? '') }}"
                                       required>
                                @error('tgl_lahir')
                                    <span class="dp-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="dp-field dp-col-full">
                                <label class="dp-label" for="inputNoHp">
                                    <span class="dp-label-text">No. Telepon / WhatsApp <span class="dp-req">*</span></span>
                                </label>
                                <div class="dp-input-wrap">
                                    <svg class="dp-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                    <input type="text"
                                           id="inputNoHp"
                                           name="no_hp"
                                           class="dp-input has-icon @error('no_hp') is-invalid @enderror"
                                           value="{{ old('no_hp', $dataPribadi->no_hp ?? '') }}"
                                           placeholder="08xxxxxxxxxx"
                                           required>
                                </div>
                                @error('no_hp')
                                    <span class="dp-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="dp-field dp-col-full">
                                <label class="dp-label" for="inputAlamat">
                                    <span class="dp-label-text">Alamat Lengkap <span class="dp-req">*</span></span>
                                </label>
                                <div class="dp-input-wrap">
                                    <svg class="dp-input-icon is-top" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                    <textarea id="inputAlamat"
                                              name="alamat_siswa"
                                              class="dp-textarea has-icon @error('alamat_siswa') is-invalid @enderror"
                                              rows="3"
                                              placeholder="Tuliskan alamat lengkap (Dusun/Jalan, RT/RW, Desa, Kecamatan, Kabupaten)"
                                              required>{{ old('alamat_siswa', $dataPribadi->alamat_siswa ?? '') }}</textarea>
                                </div>
                                @error('alamat_siswa')
                                    <span class="dp-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </section>

                {{-- 3. Informasi Akademik --}}
                <section id="sec-akademik" class="dp-card">
                    <div class="dp-card-header">
                        <div class="dp-card-header-left">
                            <div class="dp-head-icon" style="background-color:#f0fdf4;border-color:#bbf7d0;color:#16a34a;" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                            </div>
                            <div>
                                <h5 class="dp-card-title">Informasi Akademik</h5>
                                <p class="dp-card-subtitle">Kelas, program konsentrasi keahlian, dan tahun ajaran terdaftar</p>
                            </div>
                        </div>
                    </div>

                    <div class="dp-card-body">
                        <div class="dp-grid">
                            <div class="dp-field">
                                <label class="dp-label" for="selectKelas">
                                    <span class="dp-label-text">Kelas <span class="dp-req">*</span></span>
                                </label>
                                <select id="selectKelas" class="dp-select @error('kelas_id') is-invalid @enderror" name="kelas_id" required>
                                    <option value="">Pilih kelas</option>
                                    @foreach ($kelas as $kls)
                                        <option value="{{ $kls->id }}" {{ old('kelas_id', $siswa->kelas_id ?? '') == $kls->id ? 'selected' : '' }}>
                                            {{ $kls->kelas }} {{ $kls->name_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kelas_id')
                                    <span class="dp-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="dp-field">
                                <label class="dp-label" for="selectKonke">
                                    <span class="dp-label-text">Konsentrasi Keahlian <span class="dp-req">*</span></span>
                                </label>
                                <select id="selectKonke" class="dp-select @error('konke_id') is-invalid @enderror" name="konke_id" required>
                                    <option value="">Pilih konsentrasi keahlian</option>
                                    @foreach ($konke as $k)
                                        <option value="{{ $k->id }}" {{ old('konke_id', $siswa->konke_id ?? '') == $k->id ? 'selected' : '' }}>
                                            {{ $k->name_konke }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('konke_id')
                                    <span class="dp-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="dp-field dp-col-full">
                                <label class="dp-label">
                                    <span class="dp-label-text">Tahun Ajaran</span>
                                </label>
                                <div class="dp-readonly-box">
                                    <div class="dp-readonly-left">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                                        <span class="dp-readonly-val {{ empty($siswa->tahun_ajaran) ? 'is-empty' : '' }}">
                                            {{ !empty($siswa->tahun_ajaran) ? $siswa->tahun_ajaran : 'Belum diatur pada akun' }}
                                        </span>
                                    </div>
                                    <span class="dp-readonly-tag">Sistem</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- 4. Informasi Orang Tua / Wali --}}
                <section id="sec-ortu" class="dp-card">
                    <div class="dp-card-header">
                        <div class="dp-card-header-left">
                            <div class="dp-head-icon" style="background-color:#fffbeb;border-color:#fde68a;color:#d97706;" aria-hidden="true">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </div>
                            <div>
                                <h5 class="dp-card-title">Informasi Orang Tua / Wali</h5>
                                <p class="dp-card-subtitle">Data ayah, ibu, kontak darurat, dan penandatangan surat PKL</p>
                            </div>
                        </div>
                    </div>

                    <div class="dp-card-body">
                        <div class="dp-subpanel-grid">
                            {{-- Data Ayah --}}
                            <div class="dp-subpanel">
                                <div class="dp-subpanel-head">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    <span>Data Ayah</span>
                                </div>

                                <div class="dp-grid">
                                    <div class="dp-field dp-col-full">
                                        <label class="dp-label" for="inputNameAyah">
                                            <span class="dp-label-text">Nama Ayah <span class="dp-req">*</span></span>
                                        </label>
                                        <input type="text"
                                               id="inputNameAyah"
                                               name="name_ayh"
                                               class="dp-input @error('name_ayh') is-invalid @enderror"
                                               value="{{ old('name_ayh', $dataPribadi->name_ayh ?? '') }}"
                                               placeholder="Nama lengkap ayah"
                                               required>
                                        @error('name_ayh')
                                            <span class="dp-error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="dp-field dp-col-full">
                                        <label class="dp-label" for="inputNikAyah">
                                            <span class="dp-label-text">NIK Ayah <span class="dp-req">*</span></span>
                                        </label>
                                        <input type="text"
                                               id="inputNikAyah"
                                               name="nik_ayh"
                                               class="dp-input @error('nik_ayh') is-invalid @enderror"
                                               value="{{ old('nik_ayh', $dataPribadi->nik_ayh ?? '') }}"
                                               placeholder="16 digit NIK"
                                               required>
                                        @error('nik_ayh')
                                            <span class="dp-error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="dp-field">
                                        <label class="dp-label" for="inputTempatAyah">
                                            <span class="dp-label-text">Tempat Lahir <span class="dp-req">*</span></span>
                                        </label>
                                        <input type="text"
                                               id="inputTempatAyah"
                                               name="tempat_lhr_ayh"
                                               class="dp-input @error('tempat_lhr_ayh') is-invalid @enderror"
                                               value="{{ old('tempat_lhr_ayh', $dataPribadi->tempat_lhr_ayh ?? '') }}"
                                               placeholder="Kota/Kabupaten"
                                               required>
                                        @error('tempat_lhr_ayh')
                                            <span class="dp-error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="dp-field">
                                        <label class="dp-label" for="inputTglAyah">
                                            <span class="dp-label-text">Tanggal Lahir</span>
                                        </label>
                                        <input type="date"
                                               id="inputTglAyah"
                                               name="tgl_lahir_ayh"
                                               class="dp-input @error('tgl_lahir_ayh') is-invalid @enderror"
                                               value="{{ old('tgl_lahir_ayh', $dataPribadi->tgl_lahir_ayh ?? '') }}"
                                               required>
                                        @error('tgl_lahir_ayh')
                                            <span class="dp-error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="dp-field dp-col-full">
                                        <label class="dp-label" for="inputKerjaAyah">
                                            <span class="dp-label-text">Pekerjaan Ayah</span>
                                        </label>
                                        <input type="text"
                                               id="inputKerjaAyah"
                                               name="pekerjaan_ayh"
                                               class="dp-input @error('pekerjaan_ayh') is-invalid @enderror"
                                               value="{{ old('pekerjaan_ayh', $dataPribadi->pekerjaan_ayh ?? '') }}"
                                               placeholder="Contoh: Wiraswasta"
                                               required>
                                        @error('pekerjaan_ayh')
                                            <span class="dp-error">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Data Ibu --}}
                            <div class="dp-subpanel">
                                <div class="dp-subpanel-head">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    <span>Data Ibu</span>
                                </div>

                                <div class="dp-grid">
                                    <div class="dp-field dp-col-full">
                                        <label class="dp-label" for="inputNameIbu">
                                            <span class="dp-label-text">Nama Ibu <span class="dp-req">*</span></span>
                                        </label>
                                        <input type="text"
                                               id="inputNameIbu"
                                               name="name_ibu"
                                               class="dp-input @error('name_ibu') is-invalid @enderror"
                                               value="{{ old('name_ibu', $dataPribadi->name_ibu ?? '') }}"
                                               placeholder="Nama lengkap ibu"
                                               required>
                                        @error('name_ibu')
                                            <span class="dp-error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="dp-field dp-col-full">
                                        <label class="dp-label" for="inputNikIbu">
                                            <span class="dp-label-text">NIK Ibu <span class="dp-req">*</span></span>
                                        </label>
                                        <input type="text"
                                               id="inputNikIbu"
                                               name="nik_ibu"
                                               class="dp-input @error('nik_ibu') is-invalid @enderror"
                                               value="{{ old('nik_ibu', $dataPribadi->nik_ibu ?? '') }}"
                                               placeholder="16 digit NIK"
                                               required>
                                        @error('nik_ibu')
                                            <span class="dp-error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="dp-field">
                                        <label class="dp-label" for="inputTempatIbu">
                                            <span class="dp-label-text">Tempat Lahir <span class="dp-req">*</span></span>
                                        </label>
                                        <input type="text"
                                               id="inputTempatIbu"
                                               name="tempat_lhr_ibu"
                                               class="dp-input @error('tempat_lhr_ibu') is-invalid @enderror"
                                               value="{{ old('tempat_lhr_ibu', $dataPribadi->tempat_lhr_ibu ?? '') }}"
                                               placeholder="Kota/Kabupaten"
                                               required>
                                        @error('tempat_lhr_ibu')
                                            <span class="dp-error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="dp-field">
                                        <label class="dp-label" for="inputTglIbu">
                                            <span class="dp-label-text">Tanggal Lahir</span>
                                        </label>
                                        <input type="date"
                                               id="inputTglIbu"
                                               name="tgl_lahir_ibu"
                                               class="dp-input @error('tgl_lahir_ibu') is-invalid @enderror"
                                               value="{{ old('tgl_lahir_ibu', $dataPribadi->tgl_lahir_ibu ?? '') }}"
                                               required>
                                        @error('tgl_lahir_ibu')
                                            <span class="dp-error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="dp-field dp-col-full">
                                        <label class="dp-label" for="inputKerjaIbu">
                                            <span class="dp-label-text">Pekerjaan Ibu</span>
                                        </label>
                                        <input type="text"
                                               id="inputKerjaIbu"
                                               name="pekerjaan_ibu"
                                               class="dp-input @error('pekerjaan_ibu') is-invalid @enderror"
                                               value="{{ old('pekerjaan_ibu', $dataPribadi->pekerjaan_ibu ?? '') }}"
                                               placeholder="Contoh: Ibu Rumah Tangga"
                                               required>
                                        @error('pekerjaan_ibu')
                                            <span class="dp-error">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="dp-divider"></div>

                        {{-- Kontak Darurat & Penandatangan Surat --}}
                        <div class="dp-subpanel-head" style="border-bottom:none;padding-bottom:0;margin-bottom:12px;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                            <span>Kontak Darurat & Penandatangan Surat</span>
                        </div>

                        <div class="dp-grid">
                            <div class="dp-field">
                                <label class="dp-label" for="inputNoTlpOrtu">
                                    <span class="dp-label-text">Nomor WhatsApp Orang Tua <span class="dp-req">*</span></span>
                                </label>
                                <div class="dp-input-wrap">
                                    <svg class="dp-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                    <input type="text"
                                           id="inputNoTlpOrtu"
                                           name="no_tlp"
                                           class="dp-input has-icon @error('no_tlp') is-invalid @enderror"
                                           value="{{ old('no_tlp', $dataPribadi->no_tlp ?? '') }}"
                                           placeholder="085xxxxxxxxx"
                                           required>
                                </div>
                                @error('no_tlp')
                                    <span class="dp-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="dp-field">
                                <label class="dp-label" for="inputEmailOrtu">
                                    <span class="dp-label-text">Email Orang Tua</span>
                                    <span class="dp-opt-tag">Opsional</span>
                                </label>
                                <div class="dp-input-wrap">
                                    <svg class="dp-input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                    <input type="email"
                                           id="inputEmailOrtu"
                                           name="email_ortu"
                                           class="dp-input has-icon @error('email_ortu') is-invalid @enderror"
                                           value="{{ old('email_ortu', $dataPribadi->email_ortu ?? '') }}"
                                           placeholder="opsional@mail.com">
                                </div>
                                @error('email_ortu')
                                    <span class="dp-error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="dp-field dp-col-full">
                                <label class="dp-label" for="ttd_ortu_option">
                                    <span class="dp-label-text">Penandatangan Utama (Surat Pernyataan)</span>
                                </label>
                                <select name="ttd_ortu_option" id="ttd_ortu_option" class="dp-select" onchange="toggleManualFields()">
                                    <option value="ayah" {{ $selectedTtd == 'ayah' ? 'selected' : '' }}>Ayah</option>
                                    <option value="ibu" {{ $selectedTtd == 'ibu' ? 'selected' : '' }}>Ibu</option>
                                    <option value="manual" {{ $selectedTtd == 'manual' ? 'selected' : '' }}>Wali / Manual</option>
                                </select>
                            </div>
                        </div>

                        <div id="manualFields" class="dp-manual-box" style="display: none;">
                            <div class="dp-grid">
                                <div class="dp-field">
                                    <label class="dp-label" for="inputWaliHubungan">
                                        <span class="dp-label-text">Hubungan dengan Siswa <span class="dp-req">*</span></span>
                                    </label>
                                    <input type="text"
                                           id="inputWaliHubungan"
                                           name="ttd_ortu_manual_hubungan"
                                           class="dp-input"
                                           value="{{ old('ttd_ortu_manual_hubungan', $dataPribadi->ttd_ortu ?? '') }}"
                                           placeholder="Contoh: Paman / Kakek / Kakak">
                                </div>
                                <div class="dp-field">
                                    <label class="dp-label" for="inputWaliNama">
                                        <span class="dp-label-text">Nama Wali Lengkap <span class="dp-req">*</span></span>
                                    </label>
                                    <input type="text"
                                           id="inputWaliNama"
                                           name="ttd_ortu_manual_nama"
                                           class="dp-input"
                                           value="{{ old('ttd_ortu_manual_nama', $dataPribadi->ttd_ortu_nama ?? '') }}"
                                           placeholder="Sesuai KTP wali">
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- Sticky / Bottom Action Bar --}}
                <div class="dp-action-bar">
                    <p class="dp-action-note">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                        <span>Kolom bertanda <strong style="color:#dc2626;">*</strong> wajib diisi dengan valid.</span>
                    </p>
                    <div class="dp-action-btns">
                        <button type="reset" class="dp-btn dp-btn-outline" id="btnResetProfile">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                            <span>Reset</span>
                        </button>
                        <button type="submit" class="dp-btn dp-btn-primary" id="btnSaveProfile">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

{{-- Modal Crop Foto Profil (1:1 Persegi -> Preview Lingkaran) --}}
<div class="modal fade" id="cropPhotoModal" tabindex="-1" aria-labelledby="cropPhotoModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="dp-crop-header">
                <div class="dp-crop-title-wrap">
                    <div class="dp-crop-title-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2v14a2 2 0 0 0 2 2h14"/><path d="M18 22V8a2 2 0 0 0-2-2H2"/></svg>
                    </div>
                    <div>
                        <h5 class="dp-crop-title" id="cropPhotoModalLabel">Crop Foto Profil</h5>
                        <p class="dp-crop-subtitle">Atur posisi, ukuran area crop 1:1, dan zoom sebelum menyimpan.</p>
                    </div>
                </div>
                <button type="button" class="dp-crop-close" id="btnCropHeaderClose" data-bs-dismiss="modal" aria-label="Tutup modal crop">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>

            <div class="dp-crop-body">
                <div class="dp-crop-layout">
                    <div class="dp-crop-main">
                        <div class="dp-crop-stage">
                            <img id="dpCropTargetImg" alt="Area crop foto profil">
                        </div>

                        <div class="dp-crop-toolbar">
                            <button type="button" class="dp-crop-btn dp-crop-btn-outline" id="btnCropZoomOut" title="Perkecil gambar">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/><line x1="8" x2="14" y1="11" y2="11"/></svg>
                                <span>Zoom Out</span>
                            </button>
                            <button type="button" class="dp-crop-btn dp-crop-btn-outline" id="btnCropZoomIn" title="Perbesar gambar">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/><line x1="11" x2="11" y1="8" y2="14"/><line x1="8" x2="14" y1="11" y2="11"/></svg>
                                <span>Zoom In</span>
                            </button>
                            <button type="button" class="dp-crop-btn dp-crop-btn-outline" id="btnCropReset" title="Kembalikan ke posisi awal">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                <span>Reset</span>
                            </button>
                        </div>

                        <p class="dp-crop-hint">Geser gambar untuk memilih komposisi terbaik, atau tarik sudut area crop untuk menyesuaikan ukuran (rasio 1:1).</p>
                    </div>

                    <div class="dp-crop-side">
                        <div class="dp-crop-preview-ring">
                            <div id="dpCropLivePreview" aria-label="Preview lingkaran foto profil"></div>
                        </div>
                        <div class="dp-crop-preview-meta">
                            <span class="dp-crop-side-label">Preview Hasil</span>
                            <span class="dp-crop-badge">Rasio 1:1 &middot; Lingkaran</span>
                            <span>Format output JPG (maks. 2 MB)</span>
                        </div>
                    </div>
                </div>

                <div class="dp-crop-error" id="dpCropError" role="alert">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    <span id="dpCropErrorText"></span>
                </div>
            </div>

            <div class="dp-crop-footer">
                <button type="button" class="dp-crop-btn dp-crop-btn-outline" id="btnCropCancel" data-bs-dismiss="modal">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    <span>Batal</span>
                </button>
                <button type="button" class="dp-crop-btn dp-crop-btn-primary" id="btnCropSave">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    <span>Simpan Foto</span>
                </button>
            </div>
        </div>
    </div>
</div>

@if (session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (window.Swal) {
                Swal.fire({
                    icon: 'success',
                    title: 'Tersimpan!',
                    text: @json(session('success')),
                    showConfirmButton: false,
                    timer: 2200,
                    confirmButtonColor: '#2563eb'
                });
            }
        });
    </script>
@endif

@if (session('error'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (window.Swal) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyimpan!',
                    text: @json(session('error')),
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#2563eb'
                });
            }
        });
    </script>
@endif

@if ($errors->any())
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (window.Swal) {
                Swal.fire({
                    icon: 'error',
                    title: 'Periksa Kembali Isian',
                    html: {!! json_encode(implode('<br>', array_map('e', $errors->all()))) !!},
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#2563eb'
                });
            }
        });
    </script>
@endif

<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script>
    function toggleManualFields() {
        const select = document.getElementById('ttd_ortu_option');
        const manualFields = document.getElementById('manualFields');
        const hubunganInput = document.querySelector("input[name='ttd_ortu_manual_hubungan']");
        const namaInput = document.querySelector("input[name='ttd_ortu_manual_nama']");
        if (!select || !manualFields) return;

        if (select.value === 'manual') {
            manualFields.style.display = 'block';
            if (hubunganInput) hubunganInput.required = true;
            if (namaInput) namaInput.required = true;
        } else {
            manualFields.style.display = 'none';
            if (hubunganInput) hubunganInput.required = false;
            if (namaInput) namaInput.required = false;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const agamaSelect = document.getElementById('agama-select');
        const agamaLainnyaContainer = document.getElementById('agama-lainnya-container');
        const agamaLainnyaInput = document.getElementById('agama-lainnya-input');
        const form = document.getElementById('dataPribadiForm');
        const btnSaveProfile = document.getElementById('btnSaveProfile');
        const btnResetProfile = document.getElementById('btnResetProfile');

        function toggleAgamaLainnya() {
            if (!agamaSelect || !agamaLainnyaContainer || !agamaLainnyaInput) return;
            const selectedValue = agamaSelect.value;
            const agamaStandar = ['Islam', 'Kristen Protestan', 'Kristen Katolik', 'Hindu', 'Buddha', 'Konghucu'];
            const currentAgama = @json(old('agama', $dataPribadi->agama ?? ''));
            const isCustomAgama = currentAgama && !agamaStandar.includes(currentAgama);

            if (selectedValue === 'Lainnya' || (isCustomAgama && selectedValue === '')) {
                agamaLainnyaContainer.style.display = 'block';
                agamaLainnyaInput.required = true;
                if (isCustomAgama && selectedValue !== 'Lainnya') {
                    agamaSelect.value = 'Lainnya';
                    agamaLainnyaInput.value = currentAgama;
                }
            } else {
                agamaLainnyaContainer.style.display = 'none';
                agamaLainnyaInput.required = false;
            }
        }

        toggleAgamaLainnya();
        agamaSelect?.addEventListener('change', toggleAgamaLainnya);

        if (form) {
            form.addEventListener('submit', function() {
                if (agamaSelect && agamaSelect.value === 'Lainnya' && agamaLainnyaInput) {
                    form.querySelectorAll('input.dp-agama-hidden').forEach(el => el.remove());
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'agama';
                    hiddenInput.className = 'dp-agama-hidden';
                    hiddenInput.value = agamaLainnyaInput.value.trim();
                    form.appendChild(hiddenInput);
                    agamaSelect.disabled = true;
                }

                if (btnSaveProfile) {
                    btnSaveProfile.disabled = true;
                    btnSaveProfile.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span>Menyimpan...</span>';
                }
            });
        }

        if (btnResetProfile) {
            btnResetProfile.addEventListener('click', function() {
                setTimeout(() => {
                    if (agamaSelect) agamaSelect.disabled = false;
                    toggleAgamaLainnya();
                    toggleManualFields();
                }, 0);
            });
        }

        // Toggle Password Show/Hide
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const iconHide = this.querySelector('.pw-icon-hide');
                const iconShow = this.querySelector('.pw-icon-show');
                if (!input) return;

                if (input.type === 'password') {
                    input.type = 'text';
                    if (iconHide) iconHide.style.display = 'none';
                    if (iconShow) iconShow.style.display = 'block';
                } else {
                    input.type = 'password';
                    if (iconHide) iconHide.style.display = 'block';
                    if (iconShow) iconShow.style.display = 'none';
                }
            });
        });

        toggleManualFields();

        const navLinks = document.querySelectorAll('#dataPribadiPage .dp-nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const targetId = this.getAttribute('data-dp-nav');
                const sectionEl = targetId ? document.getElementById(targetId) : null;
                if (sectionEl) {
                    e.preventDefault();
                    navLinks.forEach(l => l.classList.remove('is-active'));
                    this.classList.add('is-active');
                    sectionEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // ---- Direct Profile Photo Picker, Cropper Modal (1:1 -> Circular) & AJAX Upload ----
        const avatarPreview = document.getElementById('dpAvatarPreview');
        const photoInput = document.getElementById('profilePhotoInput');
        const btnTriggerPhoto = document.getElementById('btnTriggerPhotoPicker');
        const photoErrorBox = document.getElementById('dpPhotoError');
        const photoErrorText = document.getElementById('dpPhotoErrorText');

        const cropModalEl = document.getElementById('cropPhotoModal');
        const cropTargetImg = document.getElementById('dpCropTargetImg');
        const cropLivePreview = document.getElementById('dpCropLivePreview');
        const cropErrorBox = document.getElementById('dpCropError');
        const cropErrorText = document.getElementById('dpCropErrorText');
        const btnCropZoomOut = document.getElementById('btnCropZoomOut');
        const btnCropZoomIn = document.getElementById('btnCropZoomIn');
        const btnCropReset = document.getElementById('btnCropReset');
        const btnCropCancel = document.getElementById('btnCropCancel');
        const btnCropHeaderClose = document.getElementById('btnCropHeaderClose');
        const btnCropSave = document.getElementById('btnCropSave');

        const uploadPhotoUrl = @json(route('profile.update'));
        const maxSourceBytes = 15 * 1024 * 1024;
        const maxUploadBytes = 2 * 1024 * 1024;
        const allowedMimeTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        const allowedExtensions = ['jpg', 'jpeg', 'png'];
        const defaultCropSaveHtml = btnCropSave ? btnCropSave.innerHTML : '';

        let savedPhotoSrc = avatarPreview ? (avatarPreview.getAttribute('data-saved-src') || avatarPreview.src) : '';
        let cropperInstance = null;
        let isUploadingPhoto = false;

        function getCropModalInstance() {
            if (!cropModalEl || !window.bootstrap || !window.bootstrap.Modal) return null;
            return window.bootstrap.Modal.getOrCreateInstance(cropModalEl);
        }

        function showHeroPhotoError(msg) {
            if (photoErrorBox && photoErrorText) {
                photoErrorText.textContent = msg;
                photoErrorBox.classList.add('is-visible');
            }
            if (window.Swal) {
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Foto Gagal',
                    text: msg,
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#2563eb'
                });
            }
        }

        function clearHeroPhotoError() {
            if (photoErrorBox && photoErrorText) {
                photoErrorText.textContent = '';
                photoErrorBox.classList.remove('is-visible');
            }
        }

        function showCropModalError(msg) {
            if (cropErrorBox && cropErrorText) {
                cropErrorText.textContent = msg;
                cropErrorBox.classList.add('is-visible');
            }
            if (window.Swal) {
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Foto Gagal',
                    text: msg,
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#2563eb'
                });
            }
        }

        function clearCropModalError() {
            if (cropErrorBox && cropErrorText) {
                cropErrorText.textContent = '';
                cropErrorBox.classList.remove('is-visible');
            }
        }

        function setCropSavingState(saving) {
            isUploadingPhoto = saving;
            [btnCropSave, btnCropCancel, btnCropHeaderClose, btnCropZoomOut, btnCropZoomIn, btnCropReset].forEach(btn => {
                if (btn) btn.disabled = saving;
            });
            if (btnCropSave) {
                btnCropSave.innerHTML = saving
                    ? '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span>Mengunggah...</span>'
                    : defaultCropSaveHtml;
            }
        }

        function destroyCropperInstance() {
            if (cropperInstance) {
                cropperInstance.destroy();
                cropperInstance = null;
            }
            if (cropTargetImg) cropTargetImg.removeAttribute('src');
            if (cropLivePreview) cropLivePreview.innerHTML = '';
        }

        function canvasToJpegBlob(canvas, quality) {
            return new Promise(resolve => {
                canvas.toBlob(blob => resolve(blob), 'image/jpeg', quality);
            });
        }

        if (cropModalEl) {
            cropModalEl.addEventListener('shown.bs.modal', function() {
                if (!cropTargetImg || !cropTargetImg.getAttribute('src') || !window.Cropper) return;
                if (cropperInstance) {
                    cropperInstance.destroy();
                    cropperInstance = null;
                }
                cropperInstance = new window.Cropper(cropTargetImg, {
                    aspectRatio: 1,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 0.85,
                    restore: false,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
                    background: false,
                    checkOrientation: true,
                    responsive: true,
                    preview: '#dpCropLivePreview'
                });
            });

            cropModalEl.addEventListener('hide.bs.modal', function(e) {
                if (isUploadingPhoto) {
                    e.preventDefault();
                }
            });

            cropModalEl.addEventListener('hidden.bs.modal', function() {
                destroyCropperInstance();
                clearCropModalError();
                setCropSavingState(false);
                if (photoInput) photoInput.value = '';
            });
        }

        if (btnTriggerPhoto && photoInput) {
            btnTriggerPhoto.addEventListener('click', function() {
                clearHeroPhotoError();
                photoInput.click();
            });
        }

        if (photoInput) {
            photoInput.addEventListener('change', function() {
                clearHeroPhotoError();
                const file = this.files && this.files[0];
                if (!file) return;

                const fileName = (file.name || '').toLowerCase();
                const ext = fileName.includes('.') ? fileName.split('.').pop() : '';
                const mimeValid = !file.type || allowedMimeTypes.includes(file.type.toLowerCase());
                const extValid = allowedExtensions.includes(ext);

                if (!mimeValid || !extValid) {
                    this.value = '';
                    showHeroPhotoError('Format foto tidak didukung. Gunakan file JPG, JPEG, atau PNG.');
                    return;
                }

                if (file.size > maxSourceBytes) {
                    this.value = '';
                    showHeroPhotoError('Ukuran gambar sumber terlalu besar. Maksimal ukuran file awal adalah 15 MB.');
                    return;
                }

                const cropModal = getCropModalInstance();
                if (!cropModal || !window.Cropper || !cropTargetImg) {
                    this.value = '';
                    showHeroPhotoError('Komponen crop gambar belum siap. Pastikan koneksi internet aktif lalu muat ulang halaman.');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(evt) {
                    const dataUrl = evt.target && evt.target.result;
                    if (!dataUrl) {
                        photoInput.value = '';
                        showHeroPhotoError('Gagal memuat data gambar yang dipilih.');
                        return;
                    }

                    const probeImg = new Image();
                    probeImg.onload = function() {
                        destroyCropperInstance();
                        clearCropModalError();
                        setCropSavingState(false);
                        cropTargetImg.src = dataUrl;
                        cropModal.show();
                    };
                    probeImg.onerror = function() {
                        photoInput.value = '';
                        showHeroPhotoError('File gambar tidak valid atau rusak. Pilih file JPG/PNG lainnya.');
                    };
                    probeImg.src = dataUrl;
                };
                reader.onerror = function() {
                    photoInput.value = '';
                    showHeroPhotoError('Gagal membaca file gambar dari perangkat.');
                };
                reader.readAsDataURL(file);
            });
        }

        if (btnCropZoomOut) {
            btnCropZoomOut.addEventListener('click', function() {
                if (cropperInstance && !isUploadingPhoto) {
                    cropperInstance.zoom(-0.15);
                }
            });
        }

        if (btnCropZoomIn) {
            btnCropZoomIn.addEventListener('click', function() {
                if (cropperInstance && !isUploadingPhoto) {
                    cropperInstance.zoom(0.15);
                }
            });
        }

        if (btnCropReset) {
            btnCropReset.addEventListener('click', function() {
                if (cropperInstance && !isUploadingPhoto) {
                    cropperInstance.reset();
                }
            });
        }

        if (btnCropSave) {
            btnCropSave.addEventListener('click', async function() {
                if (isUploadingPhoto) return;
                if (!cropperInstance) {
                    showCropModalError('Area crop belum siap. Silakan tutup dialog dan pilih foto kembali.');
                    return;
                }

                clearCropModalError();
                setCropSavingState(true);

                try {
                    const croppedCanvas = cropperInstance.getCroppedCanvas({
                        width: 512,
                        height: 512,
                        minWidth: 256,
                        minHeight: 256,
                        maxWidth: 1024,
                        maxHeight: 1024,
                        fillColor: '#ffffff',
                        imageSmoothingEnabled: true,
                        imageSmoothingQuality: 'high'
                    });

                    if (!croppedCanvas) {
                        showCropModalError('Gagal memproses hasil crop gambar. Silakan coba lagi.');
                        setCropSavingState(false);
                        return;
                    }

                    let croppedBlob = await canvasToJpegBlob(croppedCanvas, 0.9);
                    if (croppedBlob && croppedBlob.size > maxUploadBytes) {
                        croppedBlob = await canvasToJpegBlob(croppedCanvas, 0.75);
                    }

                    if (!croppedBlob) {
                        showCropModalError('Gagal mengonversi hasil crop gambar.');
                        setCropSavingState(false);
                        return;
                    }

                    if (croppedBlob.size > maxUploadBytes) {
                        showCropModalError('Ukuran hasil crop melebihi batas maksimal 2 MB. Perkecil zoom atau pilih gambar lain.');
                        setCropSavingState(false);
                        return;
                    }

                    const croppedFile = new File([croppedBlob], 'profile_cropped.jpg', {
                        type: 'image/jpeg'
                    });

                    const formData = new FormData();
                    formData.append('profile_photo', croppedFile);
                    formData.append('photo_only', '1');

                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                        || document.querySelector('#dataPribadiForm input[name="_token"]')?.value
                        || '';

                    const response = await fetch(uploadPhotoUrl, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: formData
                    });

                    const payload = await response.json().catch(() => null);

                    if (!response.ok || !payload || !payload.success) {
                        const firstValidationErr = payload?.errors?.profile_photo?.[0];
                        const errMessage = firstValidationErr || payload?.message || 'Gagal memperbarui foto profil. Silakan coba lagi.';
                        showCropModalError(errMessage);
                        setCropSavingState(false);
                        return;
                    }

                    savedPhotoSrc = payload.profile_photo_url || savedPhotoSrc;
                    if (avatarPreview) {
                        avatarPreview.src = savedPhotoSrc;
                        avatarPreview.setAttribute('data-saved-src', savedPhotoSrc);
                    }

                    // Sinkronkan juga foto profil pada navbar layout bila ada
                    document.querySelectorAll('img[alt="Foto Profil"]').forEach(img => {
                        img.src = savedPhotoSrc;
                    });

                    setCropSavingState(false);
                    const cropModal = getCropModalInstance();
                    if (cropModal) {
                        cropModal.hide();
                    }

                    if (window.Swal) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Tersimpan!',
                            text: payload.message || 'Foto profil berhasil diperbarui.',
                            showConfirmButton: false,
                            timer: 2000,
                            confirmButtonColor: '#2563eb'
                        });
                    }
                } catch (err) {
                    showCropModalError('Terjadi kesalahan jaringan saat mengunggah foto. Silakan coba lagi.');
                    setCropSavingState(false);
                }
            });
        }
    });
</script>
@endsection
