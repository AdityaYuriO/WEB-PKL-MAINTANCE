@extends('layout.main')

@section('content')
    <div class="container-fluid py-3 px-2 px-md-3">
        <div class="container-xxl flex-grow-1 container-p-y">

            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-transparent border-0">
                            <h5 class="card-title fw-bold mb-1">
                                <i class="fas fa-clipboard-list text-primary me-2"></i>Rekap Absensi Siswa
                            </h5>
                            @if (auth()->check() && auth()->user()->role === 'guru')
                                <p class="mb-0 text-muted small">
                                    Hello pembimbing, tabel akan tampil otomatis untuk siswa yang Anda bimbing.
                                    Gunakan filter jika ingin mempersempit periode.
                                </p>
                            @endif
                        </div>
                        <div class="card-body">
                            @php
                                $isGuru = auth()->check() && auth()->user()->role === 'guru';
                                $isKaprog = auth()->check() && auth()->user()->role === 'kaprog';
                            @endphp

                            {{-- Form Filter --}}
                            <form id="filter-form" class="mb-4">
                                <div class="row g-3 align-items-end">
                                    {{-- Tanggal Awal --}}
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <label class="form-label">Tanggal Awal</label>
                                        <input type="date" name="start_date" class="form-control"
                                            {{ $isGuru ? '' : 'required' }}>
                                    </div>

                                    {{-- Tanggal Akhir --}}
                                    <div class="col-12 col-sm-6 col-md-4">
                                        <label class="form-label">Tanggal Akhir</label>
                                        <input type="date" name="end_date" class="form-control"
                                            {{ $isGuru ? '' : 'required' }}>
                                    </div>

                                    @if (!$isGuru)
                                        {{-- Jurusan --}}
                                        <div class="col-12 col-sm-6 col-md-4" id="jurusan-col" style="{{ $isKaprog ? 'display:none;' : '' }}">
                                            <label class="form-label">Jurusan</label>
                                            <select name="konke_id" id="filter-jurusan" class="form-control select2" style="width:100%;">
                                                <option value="">Semua Jurusan</option>
                                            </select>
                                        </div>
                                        @if($isKaprog)
                                            <input type="hidden" name="konke_id" value="{{ auth()->user()->konke_id }}">
                                        @endif

                                        {{-- Kelas --}}
                                        <div class="col-12 col-sm-6 col-md-4">
                                            <label class="form-label">Kelas</label>
                                            <select name="kelas_id" id="filter-kelas" class="form-control select2" style="width:100%;">
                                                <option value="">Semua Kelas</option>
                                            </select>
                                        </div>

                                        {{-- Siswa --}}
                                        <div class="col-12 col-sm-6 col-md-4">
                                            <label class="form-label">Siswa</label>
                                            <select name="siswa_id" id="filter-siswa" class="form-control select2" style="width:100%;" disabled>
                                                <option value="">Semua Siswa</option>
                                            </select>
                                        </div>
                                    @endif

                                    {{-- Buttons --}}
                                    <div class="col-12 col-md-4">
                                        <div class="d-grid d-sm-flex gap-2">
                                            <button type="submit" class="btn btn-primary flex-fill">
                                                <i class="fas fa-search me-1"></i> Tampilkan
                                            </button>
                                            <button type="button" class="btn btn-success flex-fill" id="export-perkelas-btn">
                                                <i class="fas fa-file-excel me-1"></i> Export Excel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            {{-- Area hasil rekap --}}
                            <div id="rekap-table"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- ⚡ CSS FIX: bikin tabel bisa di-scroll di HP                  --}}
    {{-- ============================================================ --}}
    <style>
        /* Hint scroll — hanya tampil di HP */
        .table-scroll-hint {
            display: none;
            font-size: .72rem;
            color: #64748b;
            background: #f1f5f9;
            border-radius: 8px;
            padding: 6px 10px;
            margin: 10px 0 8px;
        }

        /* Info periode box */
        .rekap-info-box {
            border: 1px solid #e2e8f0;
            border-radius: .5rem;
            background: #f8fafc;
            padding: .85rem 1rem;
        }
        .rekap-info-box p { margin-bottom: .25rem; }
        .rekap-info-box p:last-child { margin-bottom: 0; }

        /* === Mobile & Tablet (≤ 991px) === */
        @media (max-width: 991.98px) {

            /* 1) HAPUS overflow:hidden dari card (biang kerok tabel gak bisa digeser) */
            .card { overflow: visible !important; }

            /* 2) Balikin rounded corners biar card tetap cantik */
            .card > .card-header:first-child {
                border-top-left-radius: .85rem !important;
                border-top-right-radius: .85rem !important;
            }
            .card > .card-body:last-child {
                border-bottom-left-radius: .85rem !important;
                border-bottom-right-radius: .85rem !important;
            }

            /* 3) Hint muncul di HP */
            .table-scroll-hint { display: block; }

            /* 4) Paksa .table-responsive jadi scroll container beneran */
            .table-responsive {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                overflow-x: auto !important;
                overflow-y: hidden !important;
                -webkit-overflow-scrolling: touch !important;
                overscroll-behavior-x: contain !important;
                touch-action: pan-x pan-y !important;
            }

            /* 5) Tabel harus lebih lebar dari container supaya bisa di-scroll */
            #tabel-rekap {
                min-width: 720px !important;
                margin-bottom: 0 !important;
            }

            /* 6) Scrollbar tipis sebagai petunjuk bisa digeser */
            .table-responsive::-webkit-scrollbar { height: 6px; }
            .table-responsive::-webkit-scrollbar-track {
                background: #f1f5f9;
                border-radius: 3px;
            }
            .table-responsive::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 3px;
            }
            .table-responsive::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }

            /* 7) Form filter di HP lebih nyaman */
            .form-label { font-size: .85rem; margin-bottom: .35rem; }
            .form-control, .form-select { font-size: .9rem; }
        }

        /* === Extra small (≤ 575px) === */
        @media (max-width: 575.98px) {
            .card-body { padding: .85rem !important; }
            .card-header { padding: .85rem 1rem !important; }
            .card-title { font-size: 1rem !important; }

            .table { font-size: .8rem; }
            .table > :not(caption) > * > * { padding: .5rem .45rem; }

            /* Info box lebih compact */
            .rekap-info-box { padding: .65rem .75rem; font-size: .8rem; }

            /* Tombol full width di HP */
            #filter-form .btn { font-size: .85rem; }

            /* Select2 mobile-friendly */
            .select2-container--default .select2-selection--single {
                height: 38px !important;
                padding: .35rem 0 !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 1.6 !important;
                font-size: .9rem;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 36px !important;
            }
        }
    </style>
@endsection

@push('scripts')
    <script>
        const currentRole = "{{ auth()->user()->role ?? '' }}";
        const kaprogKonkeId = "{{ auth()->user()->konke_id ?? '' }}";

        function renderTable(responseData) {
            let html = '';
            const data = responseData.data;
            const extraStudents = responseData.students || [];

            if ((!data || data.length === 0) && extraStudents.length === 0) {
                html = `
                    <div class="alert alert-warning text-center mb-0">
                        <i class="fas fa-exclamation-circle me-1"></i> Tidak ada data ditemukan.
                    </div>`;
                document.getElementById('rekap-table').innerHTML = html;
                return;
            }

            // 1. Ambil info filter
            let startDate = document.querySelector('input[name="start_date"]')?.value || '';
            let endDate = document.querySelector('input[name="end_date"]')?.value || '';
            if (!startDate && !endDate) {
                startDate = 'Semua';
                endDate = '';
            }
            const jurusanText = currentRole === 'guru' ? '' : $("#filter-jurusan option:selected").text();
            const kelasText = currentRole === 'guru' ? '' : $("#filter-kelas option:selected").text();
            const siswaText = currentRole === 'guru' ? '' : $("#filter-siswa option:selected").text();

            let periodText = startDate;
            if (endDate) periodText += ' s.d ' + endDate;

            // 2. Header info periode (responsive)
            html += `<div class="rekap-info-box mb-3"><div class="row g-2">`;
            if (currentRole === 'guru') {
                html += `
                    <div class="col-12">
                        <p><strong>Periode:</strong> ${periodText}</p>
                    </div>`;
            } else {
                html += `
                    <div class="col-12 col-md-6">
                        <p><strong>Periode:</strong> ${periodText}</p>
                        <p><strong>Siswa:</strong> ${siswaText}</p>
                    </div>
                    <div class="col-12 col-md-6">
                        <p><strong>Jurusan:</strong> ${jurusanText}</p>
                        <p><strong>Kelas:</strong> ${kelasText}</p>
                    </div>`;
            }
            html += `</div></div>`;

            // 3. Proses data
            const studentsMap = new Map();
            data.forEach(row => {
                const userId = row.user ? row.user.id : 'unknown';
                if (row.counts) {
                    studentsMap.set(userId, {
                        user: row.user,
                        counts: {
                            H: row.counts.H || 0,
                            S: row.counts.S || 0,
                            I: row.counts.I || 0,
                            A: row.counts.A || 0,
                        }
                    });
                    return;
                }

                if (!studentsMap.has(userId)) {
                    studentsMap.set(userId, {
                        user: row.user,
                        counts: { H: 0, S: 0, I: 0, A: 0 }
                    });
                }

                const student = studentsMap.get(userId);
                const status = (row.status ?? '').toLowerCase();
                const statusIzin = (row.jenis_izin ?? '').toLowerCase();

                if (row.jenis_dinas && row.status_dinas === 'disetujui') {
                    student.counts.H++;
                } else if (status === 'izin') {
                    if (statusIzin.includes('sakit')) student.counts.S++;
                    else student.counts.I++;
                } else if (['tepat_waktu', 'terlambat', 'hadir'].includes(status)) {
                    student.counts.H++;
                }
            });

            if (extraStudents && extraStudents.length) {
                extraStudents.forEach(s => {
                    if (!studentsMap.has(s.id)) {
                        studentsMap.set(s.id, {
                            user: s,
                            counts: { H: 0, S: 0, I: 0, A: 0 }
                        });
                    }
                });
            }

            // 4. Scroll hint + Tabel
            html += `
                <div class="table-scroll-hint">
                    <i class="bi bi-arrow-left-right"></i> Geser tabel ke kiri/kanan untuk melihat semua kolom
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm align-middle mb-0" id="tabel-rekap">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th style="width: 40px;">No</th>
                                <th style="min-width: 150px;">Nama Siswa</th>
                                <th style="width: 100px;">NIS</th>
                                <th style="width: 90px;">Kelas</th>
                                <th>Hadir (H)</th>
                                <th>Sakit (S)</th>
                                <th>Izin (I)</th>
                                <th>Alpha (A)</th>
                            </tr>
                        </thead>
                        <tbody>`;

            let no = 1;
            studentsMap.forEach((val) => {
                const userName = val.user ? val.user.name : '-';
                const userNis = val.user ?
                    (val.user.nis ?? (val.user.data_pribadi ? val.user.data_pribadi.nip : '-')) :
                    '-';
                let userJurusanOrKelas = '-';
                if (val.user) {
                    if (val.user.kelas && val.user.kelas.name_kelas) {
                        userJurusanOrKelas = val.user.kelas.name_kelas;
                    } else if (val.user.kelas) {
                        userJurusanOrKelas = val.user.kelas;
                    } else {
                        userJurusanOrKelas = val.user.jurusan || '-';
                    }
                }

                html += `
                    <tr>
                        <td class="text-center">${no++}</td>
                        <td>${userName}</td>
                        <td class="text-center">${userNis}</td>
                        <td class="text-center">${userJurusanOrKelas}</td>
                        <td class="text-center fw-bold">${val.counts.H}</td>
                        <td class="text-center fw-bold">${val.counts.S}</td>
                        <td class="text-center fw-bold">${val.counts.I}</td>
                        <td class="text-center fw-bold">${val.counts.A}</td>
                    </tr>`;
            });

            html += `</tbody></table></div>`;

            document.getElementById('rekap-table').innerHTML = html;
        }

        function fetchData() {
            let params = '';
            const form = document.getElementById('filter-form');
            if (form) {
                const fd = new FormData(form);
                params = new URLSearchParams(fd).toString();
            }

            if (currentRole === 'kaprog' && kaprogKonkeId) {
                if (params) params += '&';
                params += 'konke_id=' + encodeURIComponent(kaprogKonkeId);
            }

            const tableEl = document.getElementById('rekap-table');
            if (tableEl) {
                tableEl.innerHTML = `
                    <div class="text-center p-5">
                        <div class="spinner-border text-primary" role="status"></div>
                        <div class="mt-2 small text-muted">Memuat data...</div>
                    </div>`;
            }

            fetch(`/rekap-absensi/data?${params}`)
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        if (tableEl) tableEl.innerHTML = `<div class="alert alert-danger mb-0">${data.error}</div>`;
                    } else {
                        renderTable(data);
                    }
                }).catch(err => {
                    console.error('fetchData error', err);
                    if (tableEl) tableEl.innerHTML = `<div class="alert alert-danger mb-0">Terjadi kesalahan saat mengambil data.</div>`;
                });
        }

        function exportPerKelas() {
            let params = '';
            const form = document.getElementById('filter-form');
            if (form) {
                const fd = new FormData(form);
                params = new URLSearchParams(fd).toString();
            }

            if (currentRole === 'kaprog' && kaprogKonkeId) {
                if (params) params += '&';
                params += 'konke_id=' + encodeURIComponent(kaprogKonkeId);
            }

            window.location = `/rekap-absensi/export-perkelas?${params}`;
        }

        document.addEventListener('DOMContentLoaded', function() {
            $('.select2').select2({
                placeholder: '-- pilih --',
                allowClear: true,
                width: '100%'
            });

            const filterForm = document.getElementById('filter-form');
            if (filterForm) {
                filterForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    fetchData();
                });
            }

            const exportBtn = document.getElementById('export-perkelas-btn');
            if (exportBtn) {
                exportBtn.addEventListener('click', function() {
                    exportPerKelas();
                });
            }

            if (currentRole === 'guru') {
                fetchData();
            } else if (currentRole === 'kaprog') {
                if (kaprogKonkeId) {
                    $('#filter-jurusan').val(kaprogKonkeId);
                    $('#jurusan-col').hide();
                }
                loadFilterOptions({ konke_id: kaprogKonkeId });
            } else {
                loadFilterOptions();
            }

            $('#filter-jurusan').on('change', function() {
                const val = $(this).val();
                populateSelect('#filter-kelas', [], 'name_kelas', 'Semua Kelas');
                populateSelect('#filter-siswa', [], 'name', 'Semua Siswa');
                $('#filter-siswa').attr('disabled', 'disabled');
                const kelasEl = document.getElementById('filter-kelas');
                kelasEl.setAttribute('disabled', 'disabled');
                loadFilterOptions({ konke_id: val }).finally(() => {
                    kelasEl.removeAttribute('disabled');
                });
            });

            $('#filter-kelas').on('change', function() {
                const jur = $('#filter-jurusan').val();
                const kelas = $(this).val();
                if (kelas) {
                    $('#filter-siswa').removeAttr('disabled');
                    loadFilterOptions({ konke_id: jur, kelas_id: kelas });
                } else {
                    populateSelect('#filter-siswa', [], 'name', 'Semua Siswa');
                    $('#filter-siswa').attr('disabled', 'disabled');
                }
            });
        });

        function populateSelect(selector, items, textField, defaultText) {
            const sel = document.querySelector(selector);
            const prev = sel.value;
            sel.innerHTML = `<option value="">${defaultText}</option>`;
            items.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.textContent = item[textField];
                sel.appendChild(opt);
            });
            if (prev && sel.querySelector(`option[value="${prev}"]`)) {
                sel.value = prev;
            } else {
                sel.value = '';
            }
            if (typeof $ !== 'undefined' && $(sel).hasClass('select2')) {
                $(sel).trigger('change.select2');
            }
        }

        function loadFilterOptions(filters = {}) {
            if (!filters.hasOwnProperty('konke_id')) {
                filters.konke_id = $('#filter-jurusan').val() || '';
            }
            if (!filters.hasOwnProperty('kelas_id')) {
                filters.kelas_id = $('#filter-kelas').val() || '';
            }

            let url = '/rekap-absensi/filter-options';
            const clean = {};
            Object.keys(filters).forEach(k => {
                if (filters[k] !== '' && filters[k] !== null && filters[k] !== undefined) {
                    clean[k] = filters[k];
                }
            });
            if (Object.keys(clean).length) {
                url += '?' + new URLSearchParams(clean).toString();
            }
            return fetch(url)
                .then(res => res.json())
                .then(data => {
                    populateSelect('#filter-jurusan', data.jurusan, 'name_konke', 'Semua Jurusan');
                    let kelasList = data.kelas || [];
                    if (clean.konke_id) {
                        kelasList = kelasList.filter(k => String(k.konke_id) === String(clean.konke_id));
                    }
                    populateSelect('#filter-kelas', kelasList, 'name_kelas', 'Semua Kelas');

                    if (clean.kelas_id) {
                        populateSelect('#filter-siswa', data.siswa, 'name', 'Semua Siswa');
                        document.getElementById('filter-siswa').removeAttribute('disabled');
                    } else {
                        populateSelect('#filter-siswa', [], 'name', 'Semua Siswa');
                        document.getElementById('filter-siswa').setAttribute('disabled', 'disabled');
                    }
                    return data;
                }).catch(err => {
                    console.error('Error fetching filter options', err);
                    throw err;
                });
        }
    </script>
@endpush
