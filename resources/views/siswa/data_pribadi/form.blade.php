@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="fas fa-user-edit text-primary me-2"></i>Lengkapi Data Pribadi</h4>
            <span class="text-muted">Pastikan seluruh data diri dan orang tua terisi dengan valid.</span>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm mb-4">
            <i class="fas fa-exclamation-circle fs-5 mt-1 me-2"></i>
            <div>
                <strong class="d-block mb-1">Terdapat kesalahan pada isian:</strong>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('siswa.data_pribadi.store') }}" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ old('id', $dataPribadi->id ?? '') }}">

        <div class="row">
            <!-- LEFT COLUMN: Data Siswa -->
            <div class="col-xl-6 col-lg-12 mb-4">
                <div class="card h-100 card-hover">
                    <div class="card-header d-flex align-items-center border-bottom mb-3">
                        <i class="fas fa-id-card text-primary me-2 fs-5"></i>
                        <h5 class="card-title mb-0">Informasi Siswa</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $siswa->name ?? '') }}" required placeholder="Masukkan nama lengkap">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nomor Induk Siswa (NIS) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                    <input type="text" name="nip" class="form-control" value="{{ old('nip', $siswa->nip ?? '') }}" required placeholder="Contoh: 192010... ">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                    <select class="form-select" name="jk" required>
                                        <option value="">Pilih Kelamin</option>
                                        <option value="laki_laki" {{ old('jk', $dataPribadi->jk ?? '') == 'laki_laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="perempuan" {{ old('jk', $dataPribadi->jk ?? '') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    <input type="text" name="tempat_lhr" class="form-control" value="{{ old('tempat_lhr', $dataPribadi->tempat_lhr ?? '') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    <input type="date" name="tgl_lahir" class="form-control" value="{{ old('tgl_lahir', $dataPribadi->tgl_lahir ?? '') }}" required>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Agama <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-pray"></i></span>
                                    <select name="agama" class="form-select" id="agama-select" required>
                                        <option value="" disabled {{ old('agama', $dataPribadi->agama ?? '') == '' ? 'selected' : '' }}>-- Pilih Agama --</option>
                                        <option value="Islam" {{ old('agama', $dataPribadi->agama ?? '') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                        <option value="Kristen Protestan" {{ old('agama', $dataPribadi->agama ?? '') == 'Kristen Protestan' ? 'selected' : '' }}>Kristen Protestan</option>
                                        <option value="Kristen Katolik" {{ old('agama', $dataPribadi->agama ?? '') == 'Kristen Katolik' ? 'selected' : '' }}>Kristen Katolik</option>
                                        <option value="Hindu" {{ old('agama', $dataPribadi->agama ?? '') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                        <option value="Buddha" {{ old('agama', $dataPribadi->agama ?? '') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                        <option value="Konghucu" {{ old('agama', $dataPribadi->agama ?? '') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                        <option value="Lainnya" {{ !in_array(old('agama', $dataPribadi->agama ?? ''), ['Islam','Kristen Protestan','Kristen Katolik','Hindu','Buddha','Konghucu','']) && old('agama', $dataPribadi->agama ?? '') ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 mb-3" id="agama-lainnya-container" style="display: none;">
                                <label class="form-label">Agama Lainnya <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="agama_lainnya" id="agama-lainnya-input" value="{{ !in_array(old('agama', $dataPribadi->agama ?? ''), ['Islam','Kristen Protestan','Kristen Katolik','Hindu','Buddha','Konghucu','']) ? old('agama', $dataPribadi->agama ?? '') : '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Data Sekolah & Kontak -->
            <div class="col-xl-6 col-lg-12 mb-4">
                <div class="card h-100 card-hover">
                    <div class="card-header d-flex align-items-center border-bottom mb-3">
                        <i class="fas fa-graduation-cap text-primary me-2 fs-5"></i>
                        <h5 class="card-title mb-0">Akademik & Kontak</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Konsentrasi Keahlian <span class="text-danger">*</span></label>
                                <select class="form-select student-jurusan" name="konke_id" required>
                                    <option value="">-- Pilih Jurusan --</option>
                                    @foreach ($konke as $k)
                                        <option value="{{ $k->id }}" {{ $siswa->konke_id == $k->id ? 'selected' : '' }}>
                                            {{ $k->name_konke }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kelas (XII) <span class="text-danger">*</span></label>
                                <select class="form-select student-kelas" name="kelas_id" required disabled>
                                    <option value="">Pilih Jurusan terlebih dahulu</option>
                                    @foreach ($kelas as $kls)
                                        <option value="{{ $kls->id }}"
                                            data-konke-id="{{ $kls->konke_id }}"
                                            data-kelas="{{ $kls->name_kelas }}"
                                            @selected($siswa->kelas_id == $kls->id)>
                                            {{ trim(($kls->kelas ?? 'XII') . ' ' . $kls->name_kelas) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tahun Ajaran <span class="text-danger">*</span></label>
                                <select class="form-select" name="tahun_ajaran_id" required>
                                    <option value="" disabled selected>-- Pilih Tahun --</option>
                                    @foreach ($tahunAjaran as $thn)
                                        {{-- GANTI $siswa MENJADI $dataPribadi DI SINI --}}
                                        <option value="{{ $thn->id }}"
                                            {{ (old('tahun_ajaran_id', $dataPribadi->tahun_ajaran_id) == $thn->id) ? 'selected' : '' }}>
                                            {{ $thn->tahun_ajaran }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-map"></i></span>
                                    <textarea name="alamat_siswa" class="form-control" rows="2" required placeholder="Jl. Raya Kawali...">{{ old('alamat_siswa', $dataPribadi->alamat_siswa ?? '') }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Telepon / WA <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $dataPribadi->no_hp ?? '') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Alamat Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $siswa->email ?? '') }}" required>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Ubah Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password-showhide" name="password" placeholder="Kosongkan jika tak ingin diubah">
                                    <button type="button" class="btn btn-outline-secondary toggle-password px-3" data-target="password-showhide" tabindex="-1">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </div>
                                <small class="text-muted"><i class="fas fa-info-circle"></i> Minimal 8 karakter.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FULL WIDTH COLUMN: Data Orang Tua -->
            <div class="col-12 mb-4">
                <div class="card card-hover">
                    <div class="card-header d-flex align-items-center border-bottom mb-3">
                        <i class="fas fa-users text-primary me-2 fs-5"></i>
                        <h5 class="card-title mb-0">Informasi Orang Tua / Wali</h5>
                    </div>
                    <div class="card-body">

                        <!-- Ayah & Ibu side by side on large screens -->
                        <div class="row">
                            <!-- AYAH -->
                            <div class="col-lg-6 border-end-lg mb-4 mb-lg-0 pe-lg-4">
                                <h6 class="text-primary fw-bold mb-3"><i class="fas fa-male me-2"></i>Data Ayah</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Ayah <span class="text-danger">*</span></label>
                                        <input type="text" name="name_ayh" class="form-control" value="{{ old('name_ayh', $dataPribadi->name_ayh ?? '') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">NIK Ayah <span class="text-danger">*</span></label>
                                        <input type="text" name="nik_ayh" class="form-control" value="{{ old('nik_ayh', $dataPribadi->nik_ayh ?? '') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tempat Lahir</label>
                                        <input type="text" name="tempat_lhr_ayh" class="form-control" value="{{ old('tempat_lhr_ayh', $dataPribadi->tempat_lhr_ayh ?? '') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal Lahir</label>
                                        <input type="date" name="tgl_lahir_ayh" class="form-control" value="{{ old('tgl_lahir_ayh', $dataPribadi->tgl_lahir_ayh ?? '') }}" required>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Pekerjaan Ayah</label>
                                        <input type="text" name="pekerjaan_ayh" class="form-control" value="{{ old('pekerjaan_ayh', $dataPribadi->pekerjaan_ayh ?? '') }}" required>
                                    </div>
                                </div>
                            </div>

                            <!-- IBU -->
                            <div class="col-lg-6 ps-lg-4">
                                <h6 class="text-primary fw-bold mb-3"><i class="fas fa-female me-2"></i>Data Ibu</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Nama Ibu <span class="text-danger">*</span></label>
                                        <input type="text" name="name_ibu" class="form-control" value="{{ old('name_ibu', $dataPribadi->name_ibu ?? '') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">NIK Ibu <span class="text-danger">*</span></label>
                                        <input type="text" name="nik_ibu" class="form-control" value="{{ old('nik_ibu', $dataPribadi->nik_ibu ?? '') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tempat Lahir</label>
                                        <input type="text" name="tempat_lhr_ibu" class="form-control" value="{{ old('tempat_lhr_ibu', $dataPribadi->tempat_lhr_ibu ?? '') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal Lahir</label>
                                        <input type="date" name="tgl_lahir_ibu" class="form-control" value="{{ old('tgl_lahir_ibu', $dataPribadi->tgl_lahir_ibu ?? '') }}" required>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Pekerjaan Ibu</label>
                                        <input type="text" name="pekerjaan_ibu" class="form-control" value="{{ old('pekerjaan_ibu', $dataPribadi->pekerjaan_ibu ?? '') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Kontak & TTD Wali -->
                        <h6 class="text-primary fw-bold mb-3"><i class="fas fa-file-signature me-2"></i>Kontak Darurat & Tanda Tangan Surat</h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nomor WhatsApp Orangtua <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                                    <input type="text" name="no_tlp" class="form-control" value="{{ old('no_tlp', $dataPribadi->no_tlp ?? '') }}" required placeholder="085...">
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Email Orangtua</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email_ortu" class="form-control" value="{{ old('email_ortu', $dataPribadi->email_ortu ?? '') }}" placeholder="opsional@mail.com">
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tanda Tangan Utama</label>
                                <select name="ttd_ortu_option" id="ttd_ortu_option" class="form-select" onchange="toggleManualFields()">
                                    <option value="ayah" {{ old('ttd_ortu_option', $dataPribadi->ttd_ortu_option ?? ($dataPribadi->ttd_ortu === 'ayah' ? 'ayah' : '')) == 'ayah' ? 'selected' : '' }}>Ayah</option>
                                    <option value="ibu" {{ old('ttd_ortu_option', $dataPribadi->ttd_ortu_option ?? ($dataPribadi->ttd_ortu === 'ibu' ? 'ibu' : '')) == 'ibu' ? 'selected' : '' }}>Ibu</option>
                                    <option value="manual" {{ old('ttd_ortu_option', $dataPribadi->ttd_ortu_option ?? (!in_array($dataPribadi->ttd_ortu, ['ayah', 'ibu']) && $dataPribadi->id ? 'manual' : '')) == 'manual' ? 'selected' : '' }}>Wali / Manual</option>
                                </select>
                            </div>

                            <!-- Manual Field Container -->
                            <div class="col-12" id="manualFields" style="display: none;">
                                <div class="row p-3 bg-light rounded-3 border">
                                    <div class="col-md-6 mb-2 mb-md-0">
                                        <label class="form-label">Hubungan dengan Siswa</label>
                                        <input type="text" name="ttd_ortu_manual_hubungan" class="form-control border-white" value="{{ old('ttd_ortu_manual_hubungan', $dataPribadi->ttd_ortu ?? '') }}" placeholder="Contoh: Paman / Kakek">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Wali Lengkap</label>
                                        <input type="text" name="ttd_ortu_manual_nama" class="form-control border-white" value="{{ old('ttd_ortu_manual_nama', $dataPribadi->ttd_ortu_nama ?? '') }}" placeholder="Sesuai KTP wali">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 text-end mb-4">
                <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                    <i class="fas fa-save me-2"></i> Simpan Pendaftaran
                </button>
            </div>
        </div>
    </form>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const kelasByKonkeId = @json($kelasByKonkeId);
            document.querySelectorAll('.student-jurusan').forEach(jurusanSelect => {
                const form = jurusanSelect.closest('form');
                const kelasSelect = form.querySelector('.student-kelas');
                const options = Array.from(kelasSelect.options).filter(option => option.value);
                const updateOptions = function (preserveSelection = false) {
                    const selectedJurusan = jurusanSelect.value;
                    const allowedClasses = kelasByKonkeId[selectedJurusan] || [];

                    options.forEach(option => {
                        option.hidden = option.dataset.konkeId !== selectedJurusan ||
                            !allowedClasses.includes(option.dataset.kelas);
                    });
                    kelasSelect.disabled = allowedClasses.length === 0;
                    kelasSelect.options[0].textContent = selectedJurusan
                        ? (allowedClasses.length ? 'Pilih Kelas XII' : 'Tidak ada kelas untuk jurusan ini')
                        : 'Pilih Jurusan terlebih dahulu';
                    if (!preserveSelection || !kelasSelect.selectedOptions[0] ||
                        kelasSelect.selectedOptions[0].hidden) {
                        kelasSelect.value = '';
                    }
                };

                jurusanSelect.addEventListener('change', () => updateOptions());
                updateOptions(true);
            });
        });
    </script>

    <!-- SweetAlert2 Scripts & Handlers -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if (session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'success',
                title: 'Tersimpan!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000,
                customClass: { popup: 'rounded-4' }
            });
        });
    </script>
@endif

@if ($errors->any())
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Data!',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                // confirmButtonText: 'Tutup',
                customClass: { popup: 'rounded-4' }
            });
        });
    </script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle Agama
        const agamaSelect = document.getElementById('agama-select');
        const agamaLainnyaContainer = document.getElementById('agama-lainnya-container');
        const agamaLainnyaInput = document.getElementById('agama-lainnya-input');
        const form = agamaSelect.closest('form');

        function toggleAgamaLainnya() {
            const selectedValue = agamaSelect.value;
            const agamaStandar = ['Islam', 'Kristen Protestan', 'Kristen Katolik', 'Hindu', 'Buddha', 'Konghucu'];
            const currentAgama = "{{ old('agama', $dataPribadi->agama ?? '') }}";
            const isCustomAgama = currentAgama && !agamaStandar.includes(currentAgama);

            if (selectedValue === 'Lainnya' || isCustomAgama) {
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
        agamaSelect.addEventListener('change', toggleAgamaLainnya);

        form.addEventListener('submit', function(e) {
            if (agamaSelect.value === 'Lainnya') {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'agama';
                hiddenInput.value = agamaLainnyaInput.value.trim();
                form.appendChild(hiddenInput);
                agamaSelect.disabled = true;
            }
        });

        // Toggle Password Show/Hide
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            });
        });

        // Toggle Manual TTD Fields init
        toggleManualFields();
    });

    function toggleManualFields() {
        const select = document.getElementById('ttd_ortu_option');
        const manualFields = document.getElementById('manualFields');
        if (select.value === 'manual') {
            manualFields.style.display = 'block';
            document.querySelector("input[name='ttd_ortu_manual_hubungan']").required = true;
            document.querySelector("input[name='ttd_ortu_manual_nama']").required = true;
        } else {
            manualFields.style.display = 'none';
            document.querySelector("input[name='ttd_ortu_manual_hubungan']").required = false;
            document.querySelector("input[name='ttd_ortu_manual_nama']").required = false;
        }
    }
</script>
<style>
    .border-end-lg { border-right: none; }
    @media (min-width: 992px) {
        .border-end-lg { border-right: 1px solid var(--border-color) !important; }
    }
</style>
@endsection
