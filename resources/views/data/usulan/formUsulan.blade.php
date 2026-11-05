@extends('layout.main')
@section('content')
<div class="container-fluid">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="card-header" style="background-color: #7e7dfb">
                    <h5 style="color: white;">Form Usulan Institusi / Perusahaan Baru</h5>
                </div>
                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('usulan.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nama Institusi / Perusahaan*</label>
                                <input type="text" class="form-control" name="nama" placeholder="Masukkan Nama Institusi / Perusahaan" required>
                                <small class="form-text text-muted"><i>Nama Institusi ini akan tercatat di sistem, pastikan sudah benar!</i></small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap Pimpinan*</label>
                                <input type="text" class="form-control" name="nama_pimpinan" placeholder="Masukkan Nama Lengkap Pimpinan" required>
                                <small class="form-text text-muted"><i>Nama lengkap ini akan tercatat di sistem, pastikan sudah benar!</i></small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">NIP/NIK Pimpinan*</label>
                                <input type="text" class="form-control" name="nip_pimpinan" placeholder="Masukkan NIP/NIK Pimpinan" required>
                                <small class="form-text text-muted"><i>Isi NIP atau NUPTK di sini, pastikan sudah benar!</i></small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jabatan*</label>
                                <input type="text" class="form-control" name="jabatan" placeholder="Masukkan Jabatan" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alamat Lengkap*</label>
                                <input type="text" class="form-control" name="alamat" placeholder="Masukkan Alamat Lengkap" required>
                                <small class="form-text text-muted"><i>Masukkan alamat lengkap lokasi PKL di sini!</i></small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kode Pos*</label>
                                <input type="text" class="form-control" name="kode_pos" placeholder="Masukkan Kode Pos" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nomor Telepon (Kantor / Perusahaan)*</label>
                                <input type="number" class="form-control" name="telepon" placeholder="Masukkan Nomor Telepon" required>
                                <small class="form-text text-muted"><i>Masukkan nomor telepon aktif. Pastikan bisa diakses!</i></small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email*</label>
                                <input type="email" class="form-control" name="email" placeholder="Masukkan Email" required>
                                <small class="form-text text-muted"><i>Masukkan email aktif. Pastikan bisa diakses!</i></small>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password untuk Akun Institusi / Perusahaan*</label>
                                <input type="password" name="password" id="password" class="form-control" required minlength="6">
                                <small class="form-text text-muted"><i>Password minimal 8 karakter.</i></small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Bidang Industri*</label>
                                <input type="text" class="form-control" name="bidang_industri" placeholder="Masukkan Bidang Industri" required>
                                <small class="form-text text-muted"><i>Silakan isi bidang industri yang Anda tekuni secara spesifik dan sesuai dengan sektor usaha!</i></small>
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

                            <!-- Field status default 'proses' -->
                            <input type="hidden" name="status" value="proses">

                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-primary">Kirim</button>
                                <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // ---- Toggle input "Lainnya" ----
    const radios = document.querySelectorAll('.kerjasama-radio');
    const lainnyaInput = document.getElementById('kerjasama-lainnya');

    radios.forEach(radio => {
        radio.addEventListener('change', () => {
            lainnyaInput.style.display = (radio.value === "Lainnya") ? "block" : "none";
        });
    });

    // ---- SweetAlert Konfirmasi ----
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');

    submitBtn.addEventListener('click', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Kirim Data?',
            text: "Pastikan semua data yang diisi sudah benar!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kirim!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush


