@extends('layout.main')

@section('content')
    <div class="container-fluid">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row mb-3">
                <div class="col-12">
                    <h4 class="text-primary fw-bold mb-1">Tambah Data Siswa</h4>
                    <small class="text-muted">Form input data siswa baru</small>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-white py-2">
                            <h6 class="mb-0 fw-semibold text-white">
                                <i class="fas fa-user-plus me-2"></i>Form Tambah Data Siswa
                            </h6>
                        </div>

                        <div class="card-body p-4">
                            <!-- Form -->
                            <form action="{{ route('user.siswa.store') }}" method="POST" autocomplete="off">
                                @csrf

                                <div class="row">
                                    <!-- Kolom kiri -->
                                    <div class="col-md-6">
                                        <!-- Nama -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Nama Lengkap</label>
                                            <input type="text" name="name" class="form-control"
                                                placeholder="Masukkan nama lengkap" required>
                                        </div>

                                        <!-- NIP -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">NIP/NIS</label>
                                            <input autocomplete="off" type="text" name="nip" class="form-control"
                                                placeholder="Masukkan NIP/NIS" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Tahun Ajaran</label>
                                            <input type="text" name="tahun_ajaran" class="form-control"
                                                value="{{ old('tahun_ajaran') }}" placeholder="2025/2026" required>
                                        </div>
                                    </div>

                                    <!-- Kolom kanan -->
                                    <div class="col-md-6">
                                        <!-- Email -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Email</label>
                                            <input autocomplete="off" type="email" name="email" readonly onfocus="this.removeAttribute('readonly');" class="form-control"
                                                placeholder="contoh@email.com" required>
                                        </div>

                                        <!-- Password -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Password</label>
                                            <input autocomplete="new-password" type="password" name="password" readonly onfocus="this.removeAttribute('readonly');" class="form-control"
                                                placeholder="Masukkan password" required>
                                        </div>

                                        <!-- Jurusan -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Konsentrasi Keahlian</label>
                                            <select id="konke_id" name="konke_id" class="form-select" required>
                                                <option value="">Pilih Konsentrasi</option>
                                                @foreach ($konke as $kk)
                                                    <option value="{{ $kk->id }}"
                                                        @selected(old('konke_id') == $kk->id)>
                                                        {{ $kk->name_konke }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Kelas -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Kelas (XII)</label>
                                            <select id="kelas_id" name="kelas_id" class="form-select" required disabled>
                                                <option value="">Pilih Jurusan terlebih dahulu</option>
                                                @foreach ($kelas as $k)
                                                    <option value="{{ $k->id }}"
                                                        data-konke-id="{{ $k->konke_id }}"
                                                        data-kelas="{{ $k->name_kelas }}"
                                                        @selected(old('kelas_id') == $k->id)>
                                                        {{ trim(($k->kelas ?? 'XII') . ' ' . ($k->name_kelas ?? '')) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol -->
                                <div class="row mt-4">
                                    <div class="col-12 d-flex justify-content-end gap-2">
                                        <a href="{{ route('siswa.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times me-1"></i>Batal
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i>Simpan Data
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <!-- End Form -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const jurusanSelect = document.getElementById('konke_id');
            const kelasSelect = document.getElementById('kelas_id');
            const kelasByKonkeId = @json($kelasByKonkeId);
            const kelasOptions = Array.from(kelasSelect.options).filter(option => option.value);
            const previousKelas = @json((string) old('kelas_id'));

            function updateKelasOptions(restorePrevious = false) {
                const selectedJurusanId = jurusanSelect.value;
                const allowedKelas = kelasByKonkeId[selectedJurusanId] || [];

                kelasOptions.forEach(option => {
                    const visible = Boolean(selectedJurusanId) &&
                        option.dataset.konkeId === selectedJurusanId &&
                        allowedKelas.includes(option.dataset.kelas);
                    option.hidden = !visible;
                });

                kelasSelect.disabled = allowedKelas.length === 0;
                kelasSelect.options[0].textContent = selectedJurusanId
                    ? (allowedKelas.length ? 'Pilih Kelas' : 'Tidak ada kelas untuk jurusan ini')
                    : 'Pilih Jurusan terlebih dahulu';

                const currentOption = kelasSelect.selectedOptions[0];
                const currentIsVisible = currentOption && !currentOption.hidden;
                if (!currentIsVisible) {
                    kelasSelect.value = '';
                }

                if (restorePrevious && previousKelas) {
                    const previousOption = kelasOptions.find(option =>
                        option.value === previousKelas && !option.hidden
                    );
                    if (previousOption) kelasSelect.value = previousOption.value;
                }
            }

            jurusanSelect.addEventListener('change', function () {
                kelasSelect.value = '';
                updateKelasOptions();
            });

            updateKelasOptions(true);
        });
    </script>
@endpush
