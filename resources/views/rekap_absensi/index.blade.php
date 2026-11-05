@extends('layout.main')

@section('content')
    <div class="container-fluid mt-3">
        <div class="container-xxl flex-grow-1 container-p-y">

            <div class="row">
                <div class="col-12">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title font-weight-bold">Rekap Absensi Siswa</h3>
                            @if (auth()->check() && auth()->user()->role === 'guru')
                                <p class="mb-0 text-muted small">Hello pembimbing, tabel akan tampil otomatis untuk siswa
                                    yang Anda bimbing. Gunakan filter jika ingin mempersempit periode.</p>
                            @endif
                        </div>
                        <div class="card-body">
                            <!-- Form Filter -->
                            @php
    $isGuru = auth()->check() && auth()->user()->role === 'guru';
    $isKaprog = auth()->check() && auth()->user()->role === 'kaprog';
@endphp
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <form id="filter-form">
                                        <div class="row">
                                            <!-- always show date inputs -->
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Tanggal Awal</label>
                                                    <input type="date" name="start_date" class="form-control"
                                                        {{ $isGuru ? '' : 'required' }}>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Tanggal Akhir</label>
                                                    <input type="date" name="end_date" class="form-control"
                                                        {{ $isGuru ? '' : 'required' }}>
                                                </div>
                                            </div>

                                            @if (!$isGuru)
                                                <!-- additional filters only for non-guru -->
                                                <div class="col-md-4" id="jurusan-col" style="{{ $isKaprog ? 'display:none;' : '' }}">
                                                    <div class="form-group">
                                                        <label>Jurusan</label>
                                                        <select name="konke_id" id="filter-jurusan"
                                                            class="form-control select2" style="width:100%;">
                                                            <option value="">Semua Jurusan</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                @if($isKaprog)
                                                    <input type="hidden" name="konke_id" value="{{ auth()->user()->konke_id }}">
                                                @endif

                                                <!-- Baris 2 -->
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Kelas</label>
                                                        <select name="kelas_id" id="filter-kelas"
                                                            class="form-control select2" style="width:100%;">
                                                            <option value="">Semua Kelas</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Siswa</label>
                                                        <select name="siswa_id" id="filter-siswa"
                                                            class="form-control select2" style="width:100%;" disabled>
                                                            <option value="">Semua Siswa</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="col-md-4 d-flex align-items-end">
                                                <div class="form-group w-100">
                                                    <div class="d-flex">
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fas fa-search"></i> Tampilkan
                                                        </button>
                                                        <button type="button" class="btn btn-success ms-2" id="export-perkelas-btn">
                                                            <i class="fas fa-file-excel"></i> Export Excel
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Area untuk menampilkan hasil rekap -->
                            <div id="rekap-table"></div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            // current logged-in role, used in JS to auto-load for pembimbing
            const currentRole = "{{ auth()->user()->role ?? '' }}";
            // for kaprog we also need its jurusan id to prefill filters
            const kaprogKonkeId = "{{ auth()->user()->konke_id ?? '' }}";

            function renderTable(responseData) {
                let html = '';
                const data = responseData.data; // Data mentah dari server

                // attach any extra student list from server (for guru role)
                const extraStudents = responseData.students || [];

                if ((!data || data.length === 0) && extraStudents.length === 0) {
                    html = `
            <div class="alert alert-warning text-center">
                <i class="fas fa-exclamation-circle"></i> Tidak ada data ditemukan.
            </div>`;
                    document.getElementById('rekap-table').innerHTML = html;
                    return;
                }

                // combine data rows with extraStudents to make sure all supervised students appear
                if (extraStudents.length) {
                    // if data exists we will add missing students later when grouping
                }

                // proceed with building table
                // 1. Ambil info filter
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

                // 2. Header Keterangan
                let periodText = startDate;
                if (endDate) {
                    periodText += ' s.d ' + endDate;
                }
                html += `
        <div class="border p-3 mb-3 small bg-light">
            <div class="row">`;
                if (currentRole === 'guru') {
                    // guru header only shows period
                    html += `
                <div class="col-md-12">
                    <p class="mb-1"><strong>Periode:</strong> ${periodText}</p>
                </div>`;
                } else {
                    html += `
                <div class="col-md-6">
                    <p class="mb-1"><strong>Periode:</strong> ${periodText}</p>
                    <p class="mb-0"><strong>Siswa:</strong> ${siswaText}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><strong>Jurusan:</strong> ${jurusanText}</p>
                    <p class="mb-0"><strong>Kelas:</strong> ${kelasText}</p>
                </div>`;
                }
                html += `
            </div>
        </div>`;

                // 3. Proses Data: Kelompokkan per Siswa dan Hitung Total
                const studentsMap = new Map();

                // Loop semua data (karena sudah diambil semua, tidak terpotong per halaman)
                data.forEach(row => {
                    const userId = row.user ? row.user.id : 'unknown';

                    // jika server sudah memberikan hitungan (role guru), gunakan langsung
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
                        return; // lanjut ke baris berikut
                    }

                    // Inisialisasi jika siswa belum ada di map
                    if (!studentsMap.has(userId)) {
                        studentsMap.set(userId, {
                            user: row.user,
                            counts: {
                                H: 0,
                                S: 0,
                                I: 0,
                                A: 0
                            }
                        });
                    }

                    const student = studentsMap.get(userId);
                    const status = (row.status ?? '').toLowerCase();
                    const statusIzin = (row.jenis_izin ?? '').toLowerCase(); // Logika Klasifikasi Status
                    // DINAS LUAR = HADIR
                    if (row.jenis_dinas && row.status_dinas === 'disetujui') {
                        student.counts.H++;
                    } else if (status === 'izin') {
                        if (statusIzin.includes('sakit')) {
                            student.counts.S++;
                        } else {
                            student.counts.I++;
                        }
                    } else if (['tepat_waktu', 'terlambat', 'hadir'].includes(status)) {
                        student.counts.H++;
                    }
                });

                // tambahkan siswa pembimbing jika belum ada (hitung 0 semua)
                if (extraStudents && extraStudents.length) {
                    extraStudents.forEach(s => {
                        const userId = s.id;
                        if (!studentsMap.has(userId)) {
                            studentsMap.set(userId, {
                                user: s,
                                counts: {
                                    H: 0,
                                    S: 0,
                                    I: 0,
                                    A: 0
                                }
                            });
                        }
                    });
                }

                // 4. Buat Tabel
                html += `
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr class="text-center">
                        <th class="align-middle" style="width: 40px;">No</th>
                        <th class="align-middle" style="min-width: 150px;">Nama Siswa</th>
                        <th class="align-middle" style="width: 100px;">NIS</th>
                        <th class="align-middle" style="width: 80px;">Kelas</th>
                        <th class="align-middle">Hadir (H)</th>
                        <th class="align-middle">Sakit (S)</th>
                        <th class="align-middle">Izin (I)</th>
                        <th class="align-middle">Alpha (A)</th>
                    </tr>
                </thead>
                <tbody>`;

                let no = 1;
                // Tampilkan hasil perhitungan
                studentsMap.forEach((val, key) => {
                    const userName = val.user ? val.user.name : '-';
                    // NIS sometimes stored on user model or in related dataPribadi record
                    const userNis = val.user ?
                        (val.user.nis ?? (val.user.data_pribadi ? val.user.data_pribadi.nip : '-')) :
                        '-';
                    // determine value to display in the jurusan/kelas column
                    let userJurusanOrKelas = '-';
                    if (val.user) {
                        // first try kelas relation/name
                        if (val.user.kelas && val.user.kelas.name_kelas) {
                            userJurusanOrKelas = val.user.kelas.name_kelas;
                        } else if (val.user.kelas) {
                            userJurusanOrKelas = val.user.kelas;
                        } else {
                            // fallback to jurusan if kelas is missing
                            userJurusanOrKelas = val.user.jurusan || '-';
                        }
                    }

                    html += `<tr>`;
                    html += `<td class="text-center">${no++}</td>`;
                    html += `<td>${userName}</td>`;
                    html += `<td class="text-center">${userNis}</td>`;
                    html += `<td class="text-ceanter">${userJurusanOrKelas}</td>`;

                    // Isi Angka
                    html += `<td class="text-center font-weight-bold">${val.counts.H}</td>`;
                    html += `<td class="text-center font-weight-bold">${val.counts.S}</td>`;
                    html += `<td class="text-center font-weight-bold">${val.counts.I}</td>`;
                    html += `<td class="text-center font-weight-bold">${val.counts.A}</td>`;

                    html += `</tr>`;
                });

                html += `   </tbody>
                </table>
            </div>`;

                // Pagination DIHAPUS di sini

                document.getElementById('rekap-table').innerHTML = html;
            }

            function fetchData() {
                console.log('fetchData called, currentRole=', currentRole);
                let params = '';
                const form = document.getElementById('filter-form');
                if (form) {
                    const fd = new FormData(form);
                    params = new URLSearchParams(fd).toString();
                }

                // ensure kaprog always supplies his jurusan id even if field is hidden
                if (currentRole === 'kaprog' && kaprogKonkeId) {
                    if (params) params += '&';
                    params += 'konke_id=' + encodeURIComponent(kaprogKonkeId);
                }

                const tableEl = document.getElementById('rekap-table');
                if (tableEl) {
                    tableEl.innerHTML =
                        '<div class="text-center p-5"><div class="spinner-border text-primary" role="status"></div><br>Memuat data...</div>';
                }

                fetch(`/rekap-absensi/data?${params}`)
                    .then(res => res.json())
                    .then(data => {
                        console.log('fetchData response', data);
                        if (data.error) {
                            if (tableEl) tableEl.innerHTML =
                                `<div class="alert alert-danger">${data.error}</div>`;
                        } else {
                            renderTable(data);
                        }
                    }).catch(err => {
                        console.error('fetchData error', err);
                        if (tableEl) tableEl.innerHTML =
                            `<div class="alert alert-danger">Terjadi kesalahan saat mengambil data.</div>`;
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

                    const url = `/rekap-absensi/export-perkelas?${params}`;
                    // trigger download
                    window.location = url;
                }

            document.addEventListener('DOMContentLoaded', function() {
                $('.select2').select2({
                    placeholder: '-- pilih --',
                    allowClear: true,
                    width: 'resolve'
                });

                const filterForm = document.getElementById('filter-form');
                if (filterForm) {
                    filterForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        fetchData(); // Panggil tanpa argumen page
                    });
                }

                const exportBtn = document.getElementById('export-perkelas-btn');
                if (exportBtn) {
                    exportBtn.addEventListener('click', function() {
                        exportPerKelas();
                    });
                }

                // if user is guru, we don't need to load dropdowns; just fetch data
                if (currentRole === 'guru') {
                    fetchData();
                } else if (currentRole === 'kaprog') {
                    // kaprog should see jurusan fixed to his own and not be able to change it
                    if (kaprogKonkeId) {
                        $('#filter-jurusan').val(kaprogKonkeId);
                        // hide the field entirely (label + select)
                        $('#jurusan-col').hide();
                    }
                    // load options passing the preset jurusan so kelas/siswa dropdowns populate
                    loadFilterOptions({ konke_id: kaprogKonkeId });
                } else {
                    // Event listener untuk pagination dihapus karena tidak diperlukan lagi
                    loadFilterOptions();
                }

                $('#filter-jurusan').on('change', function() {
                    const val = $(this).val();
                    populateSelect('#filter-kelas', [], 'name_kelas', 'Semua Kelas');
                    populateSelect('#filter-siswa', [], 'name', 'Semua Siswa');
                    $('#filter-siswa').attr('disabled', 'disabled');
                    const kelasEl = document.getElementById('filter-kelas');
                    kelasEl.setAttribute('disabled', 'disabled');
                    loadFilterOptions({
                        konke_id: val
                    }).finally(() => {
                        kelasEl.removeAttribute('disabled');
                    });
                });

                $('#filter-kelas').on('change', function() {
                    const jur = $('#filter-jurusan').val();
                    const kelas = $(this).val();
                    if (kelas) {
                        $('#filter-siswa').removeAttr('disabled');
                        loadFilterOptions({
                            konke_id: jur,
                            kelas_id: kelas
                        });
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

