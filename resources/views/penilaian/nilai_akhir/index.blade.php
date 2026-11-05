@extends('layout.main')

@section('content')
<div class="container-fluid px-4 py-4">

    {{-- ===== ALERT ===== --}}
    @if(session('success'))
    <div class="alert-custom alert-success-custom" id="alertSuccess">
        <div class="alert-icon">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="alert-content"><strong>Gagal!</strong> {{ session('error') }}</div>
        <button class="alert-close" onclick="closeAlert('alertError')">âœ•</button>
    </div>
    @endif

    {{-- ===== PAGE HEADER ===== --}}
    <div class="page-header mb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="header-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="page-title mb-0">Rekap Nilai Akhir Siswa</h4>
                    <p class="page-subtitle mb-0">Ringkasan penilaian PKL dari guru pembimbing & instruktur IDUKA</p>
                </div>
            </div>

            {{-- Summary Badge --}}
            <div class="d-flex gap-2 flex-wrap">
                <div class="summary-badge badge-total">
                    <span class="badge-num">{{ count($data) }}</span>
                    <span class="badge-label">Total Siswa</span>
                </div>
                <div class="summary-badge badge-sb">
                    <span class="badge-num">{{ collect($data)->where('predikat', 'Sangat Baik')->count() }}</span>
                    <span class="badge-label">Sangat Baik</span>
                </div>
                <div class="summary-badge badge-baik">
                    <span class="badge-num">{{ collect($data)->where('predikat', 'Baik')->count() }}</span>
                    <span class="badge-label">Baik</span>
                </div>
                <div class="summary-badge badge-cukup">
                    <span class="badge-num">{{ collect($data)->where('predikat', 'Cukup')->count() }}</span>
                    <span class="badge-label">Cukup</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== CARD TABEL ===== --}}
    <div class="card-modern">
        <div class="card-modern-header">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M3 14h18M10 3v18M14 3v18"/>
                    </svg>
                    <span>Data Rekap Penilaian</span>
                </div>

                {{-- Search --}}
                <div class="search-box">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Cari nama siswa..." onkeyup="filterTable()">
                </div>
            </div>
        </div>

        <div class="card-modern-body p-0">
            @if(count($data) > 0)
            <div class="table-responsive text-nowrap">
                <table class="table-modern" id="rekapTable">
                    <thead>
                        <tr>
                            <th style="width:5%">#</th>
                            <th style="width:28%">Nama Siswa</th>
                            <th style="width:13%">Nilai Guru</th>
                            <th style="width:13%">Nilai IDUKA</th>
                            <th style="width:13%">Nilai Akhir</th>
                            <th style="width:13%">Predikat</th>
                            <th style="width:15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $index => $d)
                        <tr>
                            <td>
                                <div class="row-number">{{ $index + 1 }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="siswa-avatar-sm">{{ strtoupper(substr($d['nama'], 0, 1)) }}</div>
                                    <span class="siswa-nama">{{ $d['nama'] }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="nilai-box">
                                    <span class="nilai-angka">{{ number_format($d['nilai_guru'], 1) }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="nilai-box">
                                    <span class="nilai-angka">{{ number_format($d['nilai_iduka'], 1) }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="nilai-akhir-box">
                                    <span class="nilai-akhir-angka">{{ number_format($d['nilai_akhir'], 1) }}</span>
                                </div>
                            </td>
                            <td>
                                @php
                                    $predikat = $d['predikat'];
                                    $predikatClass = match($predikat) {
                                        'Sangat Baik' => 'predikat-sb',
                                        'Baik'        => 'predikat-baik',
                                        default       => 'predikat-cukup',
                                    };
                                @endphp
                                <span class="predikat-badge {{ $predikatClass }}">
                                    {{ $predikat }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('penilaian.export', ['id' => $d['id']]) }}"
                                   class="btn-export"
                                   title="Export Lembar Penilaian {{ $d['nama'] }}">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Export
                                </a>
                                <div class="dropdown d-inline-block">
                                    <button class="btn-export dropdown-toggle" type="button" data-bs-toggle="dropdown" 
                                            style="margin-top:6px;background:linear-gradient(135deg,#f7c32e,#c1272d);color:#222; width: 100%; justify-content: center;">
                                        <i class="fas fa-print me-1"></i> Cetak
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="border-radius:12px; font-size:13px;">
                                        <li>
                                            <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ route('sertifikat.preview', ['id' => $d['id']]) }}" target="_blank">
                                                <i class="fas fa-edit text-warning" style="width:16px;"></i> Preview & Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item py-2 d-flex align-items-center gap-2" href="{{ route('sertifikat.cetak.pdf', ['id' => $d['id']]) }}" target="_blank">
                                                <i class="fas fa-file-pdf text-danger" style="width:16px;"></i> Cetak PDF Langsung
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Empty state setelah search --}}
            <div id="emptySearch" style="display:none;" class="text-center py-5">
                <svg width="48" height="48" fill="none" stroke="#c4b5fd" viewBox="0 0 24 24" style="display:block;margin:0 auto 12px">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <p class="text-muted">Tidak ada siswa yang cocok dengan pencarian.</p>
            </div>

            @else
            {{-- Empty State --}}
            <div class="text-center py-5">
                <svg width="60" height="60" fill="none" stroke="#c4b5fd" viewBox="0 0 24 24" style="display:block;margin:0 auto 16px">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h6 style="color:#4c1d95;font-weight:600;">Belum Ada Data Rekap</h6>
                <p class="text-muted small">Data rekap nilai akan muncul setelah penilaian dari guru dan instruktur IDUKA selesai diinput.</p>
            </div>
            @endif
        </div>

        @if(count($data) > 0)
        <div class="card-modern-footer">
            <p class="mb-0 text-muted small d-flex align-items-center gap-1">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Menampilkan <strong>{{ count($data) }}</strong> data siswa yang sudah dinilai oleh kedua pihak.
            </p>
        </div>
        @endif
    </div>

</div>

<style>
/* ===========================
   ALERT
=========================== */
.alert-custom {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 18px; border-radius: 12px;
    margin-bottom: 20px; font-size: 14px;
    animation: slideDown 0.35s ease;
}
.alert-success-custom { background:#f0fdf4; border:1.5px solid #86efac; color:#15803d; }
.alert-error-custom   { background:#fef2f2; border:1.5px solid #fca5a5; color:#dc2626; }
.alert-icon  { flex-shrink:0; }
.alert-content { flex:1; font-size:14px; }
.alert-close { background:none; border:none; cursor:pointer; font-size:16px; color:inherit; opacity:0.5; padding:0 4px; transition:opacity .2s; }
.alert-close:hover { opacity:1; }

/* ===========================
   PAGE HEADER
=========================== */
.page-header { padding-bottom: 18px; border-bottom: 2px solid #ede9fe; }
.header-icon {
    width:46px; height:46px;
    background: linear-gradient(135deg, #6c63ff, #a78bfa);
    border-radius:12px; display:flex; align-items:center; justify-content:center;
    color:white; box-shadow:0 4px 12px rgba(108,99,255,0.3);
}
.page-title   { font-size:20px; font-weight:700; color:#1e1b4b; }
.page-subtitle{ font-size:13px; color:#6b7280; margin-top:2px; }

/* ===========================
   SUMMARY BADGES
=========================== */
.summary-badge {
    display:flex; flex-direction:column; align-items:center;
    padding:10px 18px; border-radius:12px; min-width:70px;
}
.badge-num   { font-size:22px; font-weight:800; line-height:1; }
.badge-label { font-size:11px; font-weight:500; margin-top:2px; }
.badge-total { background:#f5f3ff; border:1.5px solid #ddd6fe; color:#4c1d95; }
.badge-sb    { background:#f0fdf4; border:1.5px solid #86efac; color:#15803d; }
.badge-baik  { background:#eff6ff; border:1.5px solid #93c5fd; color:#1d4ed8; }
.badge-cukup { background:#fffbeb; border:1.5px solid #fcd34d; color:#92400e; }

/* ===========================
   CARD MODERN
=========================== */
.card-modern {
    background:white; border-radius:16px;
    border:1px solid #ede9fe;
    box-shadow:0 2px 8px rgba(108,99,255,0.07), 0 1px 2px rgba(0,0,0,0.04);
    overflow:hidden;
}
.card-modern-header {
    padding:14px 20px;
    background:linear-gradient(135deg, #f5f3ff, #ede9fe);
    border-bottom:1px solid #ddd6fe;
    font-weight:600; font-size:14px; color:#4c1d95;
}
.card-modern-body  { padding:20px; min-height: 350px; }
.card-modern-footer{
    padding:14px 20px;
    background:#fafafa;
    border-top:1px solid #f3f4f6;
}

/* ===========================
   SEARCH BOX
=========================== */
.search-box {
    display:flex; align-items:center; gap:8px;
    padding:7px 12px;
    background:white;
    border:1.5px solid #ddd6fe;
    border-radius:10px;
    transition:border-color .2s, box-shadow .2s;
}
.search-box:focus-within {
    border-color:#6c63ff;
    box-shadow:0 0 0 3px rgba(108,99,255,0.12);
}
.search-box svg { color:#a78bfa; flex-shrink:0; }
.search-box input {
    border:none; outline:none;
    font-size:13px; color:#374151;
    width:180px; background:transparent;
}

/* ===========================
   TABLE MODERN
=========================== */
.table-responsive { overflow: visible !important; }
.table-modern { width:100%; border-collapse:collapse; font-size:13px; }
.table-modern thead tr { background:linear-gradient(135deg, #6c63ff, #7c3aed); }
.table-modern thead th {
    padding:13px 14px; color:white;
    font-weight:600; font-size:12px;
    text-transform:uppercase; letter-spacing:.4px;
    border:none; white-space:nowrap;
}
.table-modern tbody tr { border-bottom:1px solid #f3f4f6; transition:background .15s; }
.table-modern tbody tr:last-child { border-bottom:none; }
.table-modern tbody tr:hover { background:#faf5ff; }
.table-modern tbody tr:nth-child(even) { background:#fdfcff; }
.table-modern tbody tr:nth-child(even):hover { background:#faf5ff; }
.table-modern tbody td { padding:12px 14px; color:#374151; vertical-align:middle; }

/* ===========================
   ROW NUMBER
=========================== */
.row-number {
    width:26px; height:26px;
    background:linear-gradient(135deg,#6c63ff,#a78bfa);
    color:white; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:11px; font-weight:700;
}

/* ===========================
   SISWA INFO
=========================== */
.siswa-avatar-sm {
    width:32px; height:32px;
    background:linear-gradient(135deg,#6c63ff,#a78bfa);
    color:white; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:13px; font-weight:700; flex-shrink:0;
}
.siswa-nama { font-weight:600; color:#1e1b4b; font-size:13px; }

/* ===========================
   NILAI BOX
=========================== */
.nilai-box {
    display:inline-flex; align-items:center; justify-content:center;
    background:#f5f3ff; border:1px solid #ddd6fe;
    border-radius:8px; padding:4px 12px; min-width:50px;
}
.nilai-angka { font-weight:700; font-size:14px; color:#4c1d95; }
.nilai-akhir-box {
    display:inline-flex; align-items:center; justify-content:center;
    background:linear-gradient(135deg,#6c63ff,#7c3aed);
    border-radius:8px; padding:4px 12px; min-width:50px;
}
.nilai-akhir-angka { font-weight:800; font-size:14px; color:white; }

/* ===========================
   PREDIKAT BADGE
=========================== */
.predikat-badge {
    display:inline-flex; align-items:center;
    padding:4px 12px; border-radius:20px;
    font-size:12px; font-weight:600; white-space:nowrap;
}
.predikat-sb    { background:#dcfce7; color:#15803d; border:1px solid #86efac; }
.predikat-baik  { background:#dbeafe; color:#1d4ed8; border:1px solid #93c5fd; }
.predikat-cukup { background:#fef9c3; color:#92400e; border:1px solid #fcd34d; }

/* ===========================
   BUTTON EXPORT
=========================== */
.btn-export {
    display:inline-flex; align-items:center; gap:6px;
    padding:7px 14px;
    background:linear-gradient(135deg,#6c63ff,#7c3aed);
    color:white; border:none; border-radius:8px;
    font-size:12px; font-weight:600;
    text-decoration:none; white-space:nowrap;
    transition:transform .15s, box-shadow .15s;
    box-shadow:0 2px 8px rgba(108,99,255,0.25);
}
.btn-export:hover {
    transform:translateY(-1px);
    box-shadow:0 4px 14px rgba(108,99,255,0.4);
    color:white; text-decoration:none;
}
.btn-export:active { transform:translateY(0); color:white; }

/* ===========================
   ANIMASI
=========================== */
@keyframes slideDown {
    from { opacity:0; transform:translateY(-12px); }
    to   { opacity:1; transform:translateY(0); }
}
</style>

<script>
function closeAlert(id) {
    const el = document.getElementById(id);
    if (el) { el.style.opacity='0'; el.style.transition='opacity 0.3s'; setTimeout(()=>el.remove(),300); }
}
setTimeout(() => ['alertSuccess','alertError'].forEach(id => closeAlert(id)), 5000);

function filterTable() {
    const keyword = document.getElementById('searchInput').value.toLowerCase();
    const rows    = document.querySelectorAll('#rekapTable tbody tr');
    let visible   = 0;

    rows.forEach(row => {
        const nama = row.querySelector('.siswa-nama');
        if (nama && nama.textContent.toLowerCase().includes(keyword)) {
            row.style.display = '';
            visible++;
        } else {
            row.style.display = 'none';
        }
    });

    document.getElementById('emptySearch').style.display = visible === 0 ? 'block' : 'none';
}
</script>
@endsection
