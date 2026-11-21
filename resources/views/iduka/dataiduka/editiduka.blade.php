<div class="modal fade" id="editIdukaModal{{ $iduka->id }}" tabindex="-1" aria-labelledby="editIdukaModalLabel{{ $iduka->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editIdukaModalLabel{{ $iduka->id }}">Form Edit Data {{ $iduka->id }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('updateiduka.update', $iduka->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Institusi / Perusahaan*</label>
                        <input type="text" class="form-control" name="nama" value="{{ $iduka->nama }}" required>
                        <small class="form-text text-muted">Nama Institusi ini akan tercatat di sistem, pastikan sudah benar ya!</small>
                    </div>

                    <div class="mb-3">
                        <label for="nama_pimpinan" class="form-label">Nama Lengkap Pimpinan*</label>
                        <input type="text" class="form-control" name="nama_pimpinan" value="{{ $iduka->nama_pimpinan }}" required>
                        <small class="form-text text-muted">Nama lengkap ini akan tercatat di sistem, pastikan sudah benar ya!</small>
                    </div>

                    <div class="mb-3">
                        <label for="nip_pimpinan" class="form-label">NIP/NIK Pimpinan*</label>
                        <input type="text" class="form-control" name="nip_pimpinan" value="{{ $iduka->nip_pimpinan }}" required>
                        <small class="form-text text-muted">Isi NIP atau NUPTK di sini, pastikan sudah benar ya!</small>
                    </div>

                    <div class="mb-3">
                        <label for="no_hp_pimpinan" class="form-label">Nomor Telepon Pimpinan*</label>
                        <input type="text" class="form-control" name="no_hp_pimpinan" value="{{ $iduka->no_hp_pimpinan ?? '-' }}" required>
                        <small class="form-text text-muted">Masukkan nomor telepon aktif. Pastikan bisa diakses ya!</small>
                    </div>

                    <div class="mb-3">
                        <label for="jabatan" class="form-label">Jabatan*</label>
                        <input type="text" class="form-control" name="jabatan" value="{{ $iduka->jabatan }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat Lengkap*</label>
                        <input type="text" class="form-control" name="alamat" value="{{ $iduka->alamat }}" required>
                        <small class="form-text text-muted">Masukkan alamat lengkap lokasi PKL di sini ya.</small>
                    </div>

                    <div class="mb-3">
                        <label for="kode_pos" class="form-label">Kode Pos*</label>
                        <input type="text" class="form-control" name="kode_pos" value="{{ $iduka->kode_pos }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="telepon" class="form-label">Nomor Telepon*</label>
                        <input type="text" class="form-control" name="telepon" value="{{ $iduka->telepon }}" required>
                        <small class="form-text text-muted">Masukkan nomor telepon aktif. Pastikan bisa diakses ya!</small>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email*</label>
                        <input type="email" class="form-control" name="email" value="{{ $iduka->email }}" required>
                        <small class="form-text text-muted">Masukkan email aktif. Pastikan bisa diakses ya!</small>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password (Biarkan kosong jika tidak ingin diubah)</label>
                        <input type="password" class="form-control" name="password">
                        <small class="form-text text-muted">Password minimal 8 karakter.</small>
                    </div>

                    <div class="mb-3">
                        <label for="bidang_industri" class="form-label">Bidang Industri*</label>
                        <input type="text" class="form-control" name="bidang_industri" value="{{ $iduka->bidang_industri }}" required>
                        <small class="form-text text-muted">Silakan isi bidang industri yang Anda tekuni secara spesifik dan sesuai dengan sektor usaha.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kerjasama*</label>
                        <div class="row">
                            <div class="col-md-6">
                                @php
                                $kerjasamaOptions = ['Sinkronisasi', 'Guru Tamu', 'Magang / Pelatihan', 'PKL', 'Sertifikasi'];
                                @endphp
                                @foreach($kerjasamaOptions as $option)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kerjasama" value="{{ $option }}" {{ $iduka->kerjasama == $option ? 'checked' : '' }} required>
                                    <label class="form-check-label">{{ $option }}</label>
                                </div>
                                @endforeach
                            </div>
                            <div class="col-md-6">
                                @php
                                $kerjasamaOptions2 = ['Tefa', 'Serapan', 'Beasiswa', 'PBL', 'Lainnya'];
                                @endphp
                                @foreach($kerjasamaOptions2 as $option)
                                <div class="form-check">
                                    <input class="form-check-input kerjasama-radio" type="radio" name="kerjasama" value="{{ $option }}" {{ $iduka->kerjasama == $option ? 'checked' : '' }}>
                                    <label class="form-check-label">{{ $option }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-3" id="kerjasama-lainnya" style="display: none;">
                            <label class="form-label">Kerjasama (Lainnya)</label>
                            <input type="text" class="form-control" name="kerjasama_lainnya" value="{{ $iduka->kerjasama_lainnya ?? '' }}" placeholder="Masukkan Jenis Kerjasama">
                        </div>

                        <div class="mb-3">
                            <label for="jumlah_kuota_pkl" class="form-label">Jumlah Kuota PKL*</label>
                            <input type="number" class="form-control" name="kuota_pkl" value="{{ $iduka->kuota_pkl }}" required>
                            <small class="form-text text-muted">Masukkan jumlah kuota PKL yang tersedia untuk siswa.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rekomendasi</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="rekomendasi" value="1" id="rekomendasiCheckbox"
                                    {{ $iduka->rekomendasi == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="rekomendasiCheckbox">INSTITUSI ini direkomendasikan</label>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>

            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let kerjasamaRadios = document.querySelectorAll(".kerjasama-radio");
        let lainnyaInput = document.getElementById("kerjasama-lainnya");

        kerjasamaRadios.forEach(radio => {
            radio.addEventListener("change", function() {
                if (this.value === "Lainnya") {
                    lainnyaInput.style.display = "block";
                } else {
                    lainnyaInput.style.display = "none";
                }
            });

            // Check if "Lainnya" is already selected on page load
            if (radio.value === "Lainnya" && radio.checked) {
                lainnyaInput.style.display = "block";
            }
        });
    });
</script>