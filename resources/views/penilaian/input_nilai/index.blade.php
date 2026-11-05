@extends('layout.main')

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- ===== ALERT SUKSES ===== --}}
    @if(session('success'))
    <div class="alert-custom alert-success-custom" id="alertSuccess">
        <div class="alert-icon">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="alert-content"><strong>Berhasil!</strong> {{ session('success') }}</div>
        <button class="alert-close" onclick="closeAlert('alertSuccess')">âœ•</button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert-custom alert-error-custom" id="alertError">
        <div class="alert-icon">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="alert-content"><strong>Gagal!</strong> {{ session('error') }}</div>
        <button class="alert-close" onclick="closeAlert('alertError')">âœ•</button>
    </div>
    @endif

    {{-- ===== PAGE HEADER ===== --}}
    <div class="page-header mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="header-icon">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <div>
                <h4 class="page-title mb-0">Input Penilaian Siswa</h4>
                <p class="page-subtitle mb-0">Masukkan nilai berdasarkan indikator penilaian siswa</p>
            </div>
        </div>
    </div>

    <form action="{{ route('penilaian.store') }}" method="POST" id="formPenilaian">
        @csrf

        {{-- ===== CARD PILIH SISWA ===== --}}
        <div class="card-modern mb-4">
            <div class="card-modern-header">
                <div class="d-flex align-items-center gap-2">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>Pilih Siswa</span>
                </div>
            </div>
            <div class="card-modern-body">
                <div class="row align-items-end g-3">
                    <div class="col-md-7">
                        <label class="form-label-custom">Cari & Pilih Siswa</label>
                        <select name="user_id" id="siswaSelect" class="form-select-custom" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach ($siswa as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <div class="siswa-info-box" id="siswaInfoBox" style="display:none;">
                            <div class="d-flex align-items-center gap-3">
                                <div class="siswa-avatar" id="siswaAvatar">S</div>
                                <div>
                                    <div class="siswa-name" id="siswaName">-</div>
                                    <div class="siswa-badge">âœ“ Siswa Terpilih</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== LOADING SPINNER ===== --}}
        <div id="loadingSpinner" style="display:none;" class="text-center py-5">
            <div class="spinner-modern"></div>
            <p class="mt-3 text-muted small">Memuat data indikator...</p>
        </div>

        {{-- ===== TABEL INDIKATOR ===== --}}
        <div id="tabelIndikator" style="display:none;">
            <div class="card-modern">
                <div class="card-modern-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7"/>
                            </svg>
                            <span>Tabel Indikator Penilaian</span>
                        </div>
                        <span class="badge-count" id="badgeJumlah">0 Indikator</span>
                    </div>
                </div>

                <div class="card-modern-body p-0">
                    <div class="table-responsive text-nowrap">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th style="width:4%">No</th>
                                    <th style="width:24%">Tujuan Pembelajaran / Indikator</th>
                                    <th style="width:13%">Ketercapaian</th>
                                    <th style="width:14%">Jenis Penilaian</th>
                                    <th style="width:10%">Nilai</th>
                                    <th style="width:35%">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody id="tabelIndikatorBody"></tbody>
                        </table>
                    </div>
                </div>

                <div class="card-modern-footer">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <p class="mb-0 text-muted small d-flex align-items-center gap-1">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Pastikan semua kolom terisi sebelum menyimpan
                        </p>
                        <button type="submit" class="btn-submit" id="btnSubmit">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Penilaian
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </form>

    {{-- ===== TABEL DRAFT PENILAIAN ===== --}}
    @if(isset($draftData) && count($draftData) > 0)
    <div class="card-modern mt-4">
        <div class="card-modern-header">
            <div class="d-flex align-items-center gap-2">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Draft Penilaian â€” Menunggu Penilaian Pihak Lain</span>
            </div>
        </div>
        <div class="card-modern-body p-0">
            <div class="table-responsive text-nowrap">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th style="width:5%">#</th>
                            <th style="width:25%">Nama Siswa</th>
                            <th style="width:35%">Penilaian Guru Pembimbing</th>
                            <th style="width:35%">Penilaian Instruktur IDUKA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($draftData as $idx => $draft)
                        <tr>
                            <td><div class="row-number">{{ $idx + 1 }}</div></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="siswa-avatar-sm">{{ strtoupper(substr($draft['siswa']->name, 0, 1)) }}</div>
                                    <span style="font-weight:600;color:#1e1b4b;">{{ $draft['siswa']->name }}</span>
                                </div>
                            </td>
                            <td>
                                @if($draft['guru']->count())
                                    <span class="draft-done-badge">âœ“ Sudah Dinilai</span>
                                    <div class="draft-detail mt-1">
                                        Rata-rata: <strong>{{ round($draft['guru']->avg('nilai'), 1) }}</strong>
                                    </div>
                                @else
                                    <span class="draft-pending-badge">â³ Belum Dinilai</span>
                                @endif
                            </td>
                            <td>
                                @if($draft['iduka']->count())
                                    <span class="draft-done-badge">âœ“ Sudah Dinilai</span>
                                    <div class="draft-detail mt-1">
                                        Rata-rata: <strong>{{ round($draft['iduka']->avg('nilai'), 1) }}</strong>
                                    </div>
                                @else
                                    <span class="draft-pending-badge">â³ Belum Dinilai</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>

<div id="toastContainer" style="position:fixed;top:20px;right:20px;z-index:9999;"></div>

<style>
/* ===========================
   ALERT
=========================== */
.alert-custom { display:flex; align-items:center; gap:12px; padding:14px 18px; border-radius:12px; margin-bottom:20px; font-size:14px; animation:slideDown 0.35s ease; }
.alert-success-custom { background:#f0fdf4; border:1.5px solid #86efac; color:#15803d; }
.alert-error-custom   { background:#fef2f2; border:1.5px solid #fca5a5; color:#dc2626; }
.alert-icon    { flex-shrink:0; }
.alert-content { flex:1; font-size:14px; }
.alert-close   { background:none; border:none; cursor:pointer; font-size:16px; color:inherit; opacity:0.5; padding:0 4px; transition:opacity .2s; }
.alert-close:hover { opacity:1; }

/* ===========================
   PAGE HEADER
=========================== */
.page-header   { padding-bottom:18px; border-bottom:2px solid #ede9fe; }
.header-icon   { width:46px; height:46px; background:linear-gradient(135deg,#6c63ff,#a78bfa); border-radius:12px; display:flex; align-items:center; justify-content:center; color:white; box-shadow:0 4px 12px rgba(108,99,255,0.3); }
.page-title    { font-size:20px; font-weight:700; color:#1e1b4b; }
.page-subtitle { font-size:13px; color:#6b7280; margin-top:2px; }

/* ===========================
   CARD MODERN
=========================== */
.card-modern         { background:white; border-radius:16px; border:1px solid #ede9fe; box-shadow:0 2px 8px rgba(108,99,255,0.07),0 1px 2px rgba(0,0,0,0.04); overflow:hidden; }
.card-modern-header  { padding:14px 20px; background:linear-gradient(135deg,#f5f3ff,#ede9fe); border-bottom:1px solid #ddd6fe; font-weight:600; font-size:14px; color:#4c1d95; }
.card-modern-body    { padding:20px; }
.card-modern-footer  { padding:14px 20px; background:#fafafa; border-top:1px solid #f3f4f6; }

/* ===========================
   FORM
=========================== */
.form-label-custom   { display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px; }
.form-select-custom  { width:100%; padding:10px 14px; border:1.5px solid #ddd6fe; border-radius:10px; font-size:14px; color:#374151; background:white; outline:none; transition:border-color .2s,box-shadow .2s; cursor:pointer; }
.form-select-custom:focus { border-color:#6c63ff; box-shadow:0 0 0 3px rgba(108,99,255,0.12); }

/* ===========================
   SISWA INFO
=========================== */
.siswa-info-box { background:linear-gradient(135deg,#f5f3ff,#ede9fe); border:1.5px solid #c4b5fd; border-radius:12px; padding:12px 16px; animation:fadeIn 0.3s ease; }
.siswa-avatar   { width:42px; height:42px; background:linear-gradient(135deg,#6c63ff,#a78bfa); border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:17px; flex-shrink:0; box-shadow:0 3px 8px rgba(108,99,255,0.3); }
.siswa-avatar-sm{ width:32px; height:32px; background:linear-gradient(135deg,#6c63ff,#a78bfa); border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:13px; flex-shrink:0; }
.siswa-name     { font-weight:700; font-size:14px; color:#1e1b4b; }
.siswa-badge    { font-size:11px; color:#6c63ff; font-weight:600; margin-top:2px; }

/* ===========================
   TABLE MODERN
=========================== */
.table-modern { width:100%; border-collapse:collapse; font-size:13px; }
.table-modern thead tr { background:linear-gradient(135deg,#6c63ff,#7c3aed); }
.table-modern thead th { padding:13px 14px; color:white; font-weight:600; font-size:12px; text-transform:uppercase; letter-spacing:.4px; border:none; white-space:nowrap; }
.table-modern tbody tr { border-bottom:1px solid #f3f4f6; transition:background .15s; }
.table-modern tbody tr:last-child { border-bottom:none; }
.table-modern tbody tr:hover { background:#faf5ff; }
.table-modern tbody td { padding:11px 14px; color:#374151; vertical-align:middle; }

/* ===========================
   ROW TUJUAN PEMBELAJARAN (parent/header group)
=========================== */
.row-tp {
    background: linear-gradient(135deg, #f5f3ff, #ede9fe) !important;
    border-bottom: 1px solid #ddd6fe !important;
}
.row-tp td {
    padding: 10px 14px !important;
}
.tp-nomor {
    width: 28px; height: 28px;
    background: linear-gradient(135deg, #6c63ff, #7c3aed);
    color: white;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 800;
    flex-shrink: 0;
}
.tp-label {
    font-weight: 700;
    font-size: 13px;
    color: #4c1d95;
}

/* ===========================
   ROW INDIKATOR (child)
=========================== */
.row-indikator td {
    padding: 10px 14px !important;
    background: white;
}
.row-indikator:hover td { background: #faf5ff !important; }
.indikator-dot {
    width: 8px; height: 8px;
    background: #a78bfa;
    border-radius: 50%;
    display: inline-block;
    margin-right: 6px;
    flex-shrink: 0;
}
.indikator-text {
    font-size: 13px;
    color: #374151;
}
.indikator-indent {
    padding-left: 28px !important;
}

/* ===========================
   ROW NUMBER
=========================== */
.row-number { width:26px; height:26px; background:linear-gradient(135deg,#6c63ff,#a78bfa); color:white; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; }

/* ===========================
   INPUT & SELECT IN TABLE
=========================== */
.input-table, .select-table { width:100%; padding:7px 10px; border:1.5px solid #e5e7eb; border-radius:8px; font-size:13px; color:#374151; background:white; outline:none; transition:border-color .2s,box-shadow .2s; }
.input-table:focus, .select-table:focus { border-color:#6c63ff; box-shadow:0 0 0 3px rgba(108,99,255,0.1); }

/* ===========================
   BADGE
=========================== */
.badge-count { background:linear-gradient(135deg,#6c63ff,#7c3aed); color:white; padding:4px 14px; border-radius:20px; font-size:12px; font-weight:600; }
.draft-done-badge    { background:#dcfce7; color:#15803d; border:1px solid #86efac; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:600; }
.draft-pending-badge { background:#fef9c3; color:#92400e; border:1px solid #fcd34d; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:600; }
.draft-detail { font-size:12px; color:#6b7280; }

/* ===========================
   BUTTON SUBMIT
=========================== */
.btn-submit { display:inline-flex; align-items:center; gap:8px; padding:10px 26px; background:linear-gradient(135deg,#6c63ff,#7c3aed); color:white; border:none; border-radius:10px; font-size:14px; font-weight:600; cursor:pointer; transition:transform .15s,box-shadow .15s; box-shadow:0 4px 14px rgba(108,99,255,0.35); }
.btn-submit:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(108,99,255,0.45); }
.btn-submit:active { transform:translateY(0); }
.btn-submit:disabled { opacity:.7; cursor:not-allowed; transform:none; }

/* ===========================
   SPINNER
=========================== */
.spinner-modern { width:44px; height:44px; border:3px solid #ede9fe; border-top:3px solid #6c63ff; border-radius:50%; margin:0 auto; animation:spin 0.75s linear infinite; }

/* ===========================
   TOAST
=========================== */
.toast-item { display:flex; align-items:center; gap:10px; padding:14px 18px; border-radius:12px; margin-bottom:10px; font-size:14px; font-weight:500; min-width:290px; box-shadow:0 8px 24px rgba(0,0,0,0.15); animation:slideInRight 0.3s ease; color:white; }
.toast-success { background:linear-gradient(135deg,#16a34a,#15803d); }
.toast-error   { background:linear-gradient(135deg,#dc2626,#b91c1c); }

/* ===========================
   ANIMASI
=========================== */
@keyframes slideDown    { from{opacity:0;transform:translateY(-12px)} to{opacity:1;transform:translateY(0)} }
@keyframes slideInRight { from{opacity:0;transform:translateX(30px)}  to{opacity:1;transform:translateX(0)} }
@keyframes fadeIn       { from{opacity:0} to{opacity:1} }
@keyframes spin         { to{transform:rotate(360deg)} }
</style>

<script>
/* ===========================
   ALERT
=========================== */
function closeAlert(id) {
    const el = document.getElementById(id);
    if (el) { el.style.opacity='0'; el.style.transition='opacity 0.3s'; setTimeout(()=>el.remove(),300); }
}
setTimeout(() => ['alertSuccess','alertError'].forEach(id => closeAlert(id)), 5000);

/* ===========================
   TOAST
=========================== */
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    const icons = {
        success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        error:   '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
    };
    const toast = document.createElement('div');
    toast.className = `toast-item toast-${type}`;
    toast.innerHTML = `<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">${icons[type]}</svg><span>${message}</span>`;
    container.appendChild(toast);
    setTimeout(() => { toast.style.opacity='0'; toast.style.transition='opacity 0.3s'; setTimeout(()=>toast.remove(),300); }, 4000);
}

@if(session('success')) showToast("{{ session('success') }}", 'success'); @endif
@if(session('error'))   showToast("{{ session('error') }}",   'error');   @endif

/* ===========================
   SELECT SISWA â†’ LOAD INDIKATOR GROUPED
=========================== */
document.getElementById('siswaSelect').addEventListener('change', function () {
    const siswaId        = this.value;
    const siswaName      = this.options[this.selectedIndex].text;
    const tabelIndikator = document.getElementById('tabelIndikator');
    const tabelBody      = document.getElementById('tabelIndikatorBody');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const siswaInfoBox   = document.getElementById('siswaInfoBox');
    const badgeJumlah    = document.getElementById('badgeJumlah');

    if (!siswaId) {
        tabelIndikator.style.display = 'none';
        siswaInfoBox.style.display   = 'none';
        return;
    }

    // Info siswa terpilih
    document.getElementById('siswaName').textContent   = siswaName;
    document.getElementById('siswaAvatar').textContent = siswaName.charAt(0).toUpperCase();
    siswaInfoBox.style.display = 'block';

    tabelIndikator.style.display = 'none';
    loadingSpinner.style.display = 'block';
    tabelBody.innerHTML          = '';

    fetch(`/penilaian/get-indikator/${siswaId}`)
        .then(r => r.json())
        .then(tujuanList => {
            loadingSpinner.style.display = 'none';
            tabelBody.innerHTML = '';

            if (!tujuanList.length) {
                tabelBody.innerHTML = `
                    <tr><td colspan="6" class="text-center py-5">
                        <svg width="48" height="48" fill="none" stroke="#c4b5fd" viewBox="0 0 24 24" style="display:block;margin:0 auto 10px">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span class="text-muted">Tidak ada indikator untuk siswa ini</span>
                    </td></tr>`;
                tabelIndikator.style.display = 'block';
                return;
            }

            // Hitung total indikator
            const totalIndikator = tujuanList.reduce((sum, tp) => sum + (tp.indikator_penilaians?.length || 0), 0);
            badgeJumlah.textContent = totalIndikator + ' Indikator';

            // ============================================================
            // Render grouped: 1 row header TP + N rows indikator di bawahnya
            // Persis seperti format docx
            // ============================================================

            tujuanList.forEach((tp, tpIndex) => {
                const indikators = tp.indikator_penilaians || [];

                // ROW HEADER: Tujuan Pembelajaran (input jenis penilaian, nilai, deskripsi)
                const rowTP = document.createElement('tr');
                rowTP.className = 'row-tp';
                rowTP.innerHTML = `
                    <td>
                        <div class="tp-nomor">${tpIndex + 1}</div>
                    </td>
                    <td>
                        <span class="tp-label">${tp.tujuan_pembelajaran}</span>
                    </td>
                    <td></td>
                    <td>
                        <select name="jenis_penilaian_tp[${tp.id}]" class="select-table" required>
                            <option value="">-- Pilih --</option>
                            <option value="guru_pembimbing">Guru Pembimbing</option>
                            <option value="instruktur_iduka">Instruktur IDUKA</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" name="nilai_tp[${tp.id}]" class="input-table" min="0" max="100" placeholder="0–100" required>
                    </td>
                    <td>
                        <input type="text" name="deskripsi_tp[${tp.id}]" class="input-table" placeholder="Opsional">
                    </td>
                `;
                tabelBody.appendChild(rowTP);

                // ROW INDIKATOR: satu baris per indikator (hanya input ketercapaian)
                indikators.forEach((indikator, iIdx) => {
                    const rowInd = document.createElement('tr');
                    rowInd.className = 'row-indikator';
                    rowInd.innerHTML = `
                        <td class="text-center" style="color:#a78bfa;font-size:12px;font-weight:600;">
                            ${tpIndex + 1}.${iIdx + 1}
                        </td>
                        <td class="indikator-indent">
                            <div class="d-flex align-items-start gap-1">
                                <span class="indikator-dot mt-1"></span>
                                <span class="indikator-text">${indikator.indikator}</span>
                            </div>
                        </td>
                        <td>
                            <select name="ketercapaian[${indikator.id}]" class="select-table" required>
                                <option value="">-- Pilih --</option>
                                <option value="Ya">✓ Ya</option>
                                <option value="Tidak">✗ Tidak</option>
                            </select>
                        </td>
                        <td colspan="3"></td>
                    `;
                    tabelBody.appendChild(rowInd);
                });
            });

            tabelIndikator.style.display = 'block';
        })
        .catch(err => {
            loadingSpinner.style.display = 'none';
            console.error(err);
            showToast('Gagal memuat data indikator. Coba lagi.', 'error');
        });
});

/* ===========================
   SUBMIT â€” loading state
=========================== */
document.getElementById('formPenilaian').addEventListener('submit', function () {
    const btn = document.getElementById('btnSubmit');
    btn.disabled = true;
    btn.innerHTML = `
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            style="animation:spin 0.75s linear infinite">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
        Menyimpan...`;
});
</script>
@endsection
