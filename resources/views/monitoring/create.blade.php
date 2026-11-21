@extends('layout.main')

@section('content')
    <div class="container my-3">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex align-items-center">
                            <a href="{{ route('monitoring.index') }}" class="btn btn-outline-secondary me-3">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                            <div>
                                <h5 class="card-title fw-bold mb-0">
                                    <i class="bx bx-plus text-success me-2"></i>
                                    Tambah Data Monitoring
                                </h5>
                                <p class="text-muted mb-0">Isi form untuk menambah data monitoring IDUKA</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('monitoring.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- IDUKA --}}
                            <div class="mb-3">
                                <label for="iduka_id" class="form-label fw-semibold">
                                    <i class="bx bx-building me-1"></i> IDUKA <span class="text-danger">*</span>
                                </label>
                                <select class="form-select select2 @error('iduka_id') is-invalid @enderror" id="iduka_id"
                                    name="iduka_id" required>
                                    <option value="">Pilih IDUKA...</option>
                                    @foreach ($idukas as $iduka)
                                        <option value="{{ $iduka->id }}"
                                            {{ old('iduka_id') == $iduka->id ? 'selected' : '' }}>
                                            {{ $iduka->nama }} - {{ $iduka->alamat }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('iduka_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            {{-- Saran / Catatan --}}
                            <div class="mb-3">
                                <label for="saran" class="form-label fw-semibold">
                                    <i class="bx bx-comment-detail me-1"></i> Saran / Catatan
                                </label>
                                <textarea class="form-control @error('saran') is-invalid @enderror" id="saran" name="saran" rows="4"
                                    placeholder="Masukkan saran atau catatan monitoring...">{{ old('saran') }}</textarea>
                                @error('saran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Catatan hasil kunjungan atau monitoring ke IDUKA</div>
                            </div>

                            {{-- Perkiraan Siswa Diterima --}}
                            <div class="mb-3">
                                <label for="perikiraan_siswa_diterima" class="form-label fw-semibold">
                                    <i class="bx bx-user-check me-1"></i> Perkiraan Siswa Diterima
                                </label>
                                <input type="number"
                                    class="form-control @error('perikiraan_siswa_diterima') is-invalid @enderror"
                                    id="perikiraan_siswa_diterima" name="perikiraan_siswa_diterima" min="0"
                                    value="{{ old('perikiraan_siswa_diterima') }}" placeholder="Contoh: 10">
                                @error('perikiraan_siswa_diterima')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Perkiraan jumlah siswa yang akan diterima di IDUKA ini</div>
                            </div>

                            {{-- Upload Foto Monitoring --}}
                            <div class="mb-4">
                                <label for="foto" class="form-label fw-semibold">
                                    <i class="bx bx-camera me-1"></i> Foto Monitoring
                                </label>
                                <input type="file" class="form-control @error('foto.*') is-invalid @enderror"
                                    id="foto" name="foto[]" accept="image/*" multiple>
                                @error('foto.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Upload hingga 3 foto dokumentasi monitoring (JPG, PNG, GIF, max 2MB per foto)
                                </div>

                                {{-- Preview gambar --}}
                                <div id="imagePreview" class="mt-3 d-flex flex-wrap gap-2"></div>
                            </div>

                            {{-- Tanggal --}}
                            <div class="mb-4">
                                <label for="tgl" class="form-label fw-semibold">
                                    <i class="bx bx-calendar me-1"></i> Tanggal Pembuatan
                                </label>
                                <input type="date" name="tgl" id="tgl" class="form-control"
                                    value="{{ old('tgl') }}">
                            </div>

                            {{-- Tombol --}}
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('monitoring.index') }}" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-times me-2"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-2"></i> Simpan Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const input = document.getElementById('foto');
        const preview = document.getElementById('imagePreview');
        let files = [];

        input.addEventListener('change', (e) => {
            const newFiles = Array.from(e.target.files);

            // gabungkan file lama dan baru, maksimal 3
            files = [...files, ...newFiles].slice(0, 3);

            // gunakan DataTransfer supaya semua file tetap tersimpan
            const dataTransfer = new DataTransfer();
            files.forEach(file => dataTransfer.items.add(file));
            input.files = dataTransfer.files;

            // tampilkan preview
            preview.innerHTML = '';
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = (event) => {
                    const img = document.createElement('img');
                    img.src = event.target.result;
                    img.classList.add('img-thumbnail');
                    img.style.maxWidth = '120px';
                    img.style.maxHeight = '120px';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const files = document.querySelector('#foto').files;
            const maxSize = 2 * 1024 * 1024; // 2MB
            for (let file of files) {
                if (file.size > maxSize) {
                    e.preventDefault();
                    alert(`File "${file.name}" terlalu besar! Maksimal 2MB per foto.`);
                    return;
                }
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#iduka_id').select2({
                theme: 'bootstrap-5', // sesuaikan jika kamu pakai Bootstrap 5
                placeholder: "Pilih IDUKA...",
                allowClear: true
            });
        });
    </script>
@endpush
