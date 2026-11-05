<div class="modal fade" id="tambahIdukaModal" tabindex="-1" aria-labelledby="tambahIdukaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form
                action="{{ auth()->user()->role === 'hubin' ? route('hubin.iduka.store') : route('iduka.store') }}"
                method="POST">

                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="tambahIdukaModalLabel">Form Tambah Data Industri Dunia Kerja</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Institusi / Perusahaan*</label>
                        <input type="text" class="form-control" name="nama" placeholder="Masukkan Nama Institusi / Perusahaan" required>
                        <small class="form-text text-muted"><i>Nama Institusi ini akan tercatat di sistem, pastikan sudah benar!</i></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap Pimpinan*</label>
                        <input type="text" class="form-control" name="nama_pimpinan" placeholder="Masukkan Nama Lengkap Pimpinan" required>
                        <small class="form-text text-muted"><i>Nama lengkap ini akan tercatat di sistem, pastikan sudah benar!<i /></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIP/NIK Pimpinan*</label>
                        <input type="text" class="form-control" name="nip_pimpinan" placeholder="Masukkan Nip/Nik Pimpinan" required>
                        <small class="form-text text-muted"><i>Isi NIP atau NUPTK di sini, pastikan sudah benar!</i></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor Telepon Pimpinan*</label>
                        <input type="text" class="form-control" name="no_hp_pimpinan" placeholder="Masukkan Nomor Telepon Pimpinan" required>
                        <small class="form-text text-muted"><i>Masukkan nomor telepon aktif. Pastikan bisa diakses!</i></small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jabatan*</label>
                        <input type="text" class="form-control" name="jabatan" placeholder="Masukkan Jabatan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Lengkap*</label>
                        <input type="text" class="form-control" name="alamat" placeholder="Masukkan Alamat Lengkap" required>
                        <small class="form-text text-muted"><i>Masukkan alamat lengkap lokasi PKL di sini.</i></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kode Pos*</label>
                        <input type="text" class="form-control" name="kode_pos" placeholder="Masukkan Kode Pos" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor Telepon (Kantor / Perusahaan)*</label>
                        <input type="number" class="form-control" name="telepon" placeholder="Masukkan Nomor Telepon" required>
                        <small class="form-text text-muted" <i>Masukkan nomor telepon aktif. Pastikan bisa diakses!</i></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email*</label>
                        <input type="email" class="form-control" name="email" placeholder="Masukkan Email" required>
                        <small class="form-text text-muted"><i>Masukkan email aktif. Pastikan bisa diakses!</i></small>
                    </div>
                  <div class="mb-3">
    <label class="form-label">Password*</label>
    <div class="input-group">
        <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan Password" required>
        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password" tabindex="-1">
            <i class="bi bi-eye-slash"></i>
        </button>
    </div>
    <small class="form-text text-muted"><i>Password minimal 8 karakter.</i></small>
</div>

                    <div class="mb-3">
                        <label class="form-label">Bidang Industri*</label>
                        <input type="text" class="form-control" name="bidang_industri" placeholder="Masukkan Bidang Industri" required>
                        <small class="form-text text-muted"><i>Silakan isi bidang industri yang Anda tekuni secara spesifik dan sesuai dengan sektor usaha.</i></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kerjasama</label>
                        <div class="row">
                            <div class="col-md-6">
                                @php
                                $kerjasamaOptions = ['Sinkronisasi', 'Guru Tamu', 'Magang / Pelatihan', 'PKL', 'Sertifikasi'];
                                @endphp
                                @foreach($kerjasamaOptions as $option)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kerjasama" value="{{ $option }}" required>
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
                                    <input class="form-check-input kerjasama-radio" type="radio" name="kerjasama" value="{{ $option }}">
                                    <label class="form-check-label">{{ $option }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="mb-3" id="kerjasama-lainnya" style="display: none;">
                        <label class="form-label">Kerjasama (Lainnya)</label>
                        <input type="text" class="form-control" name="kerjasama_lainnya" placeholder="Masukkan Jenis Kerjasama">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jumlah Kuota PKL*</label>
                        <input type="number" class="form-control" name="kuota_pkl" placeholder="Masukkan Kuota PKL" required>
                        <small class="form-text text-muted"><i>Masukkan jumlah kuota PKL yang tersedia untuk siswa.</i></small>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rekomendasi" name="rekomendasi" value="1">
                            <label class="form-check-label" for="rekomendasi">
                                Saya merekomendasikan INSTITUSI ini
                            </label>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
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
        });
    });

     document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        });
    });
</script>