@extends('layout.main')
@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Data Siswa Kelas</title>
    </head>

    <body>
        <div class="container-fluid">
            <div class="content-wrapper" style="overflow: visible !important;">
                <div class="container-xxl flex-grow-1 container-p-y" style="overflow: visible !important;">

                        </div>
                        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="searchModalLabel">Cari Siswa</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="#">
                                            <input type="text" name="search" class="form-control"
                                                placeholder="Cari Siswa...">
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="card shadow-sm border-0 mb-4" style="overflow: visible;">
                                <div class="card-header d-flex justify-content-between align-items-center bg-white py-3" style="overflow: visible; position: relative; z-index: 99;">
                                    <h5 class="mb-0">Data Siswa</h5>
                                    <div class="d-flex gap-2">
                                        <!-- Tombol Kembali -->
                                        <a href="{{ route('kelas.index') }}"
                                            class="btn btn-primary btn-sm shadow-sm">
                                            <i class="bi bi-arrow-left"></i>
                                            <span class="d-none d-md-inline">Kembali</span>
                                        </a>

                                        <!-- Tombol Search -->
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#searchModal">
                                            <i class="bi bi-search"></i>
                                            <span class="d-none d-md-inline">Search</span>
                                        </button>

                                        <a href="{{ route('siswa.download-template') }}"
                                            class="btn btn-success btn-sm d-flex align-items-center">
                                            <i class="bi bi-download me-1"></i>
                                            <span class="d-none d-md-inline">Template</span>
                                        </a>

                                        <!-- Dropdown Tambah -->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-plus-lg"></i> <span class="d-none d-md-inline">Tambah</span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow" style="z-index: 9999 !important;">
                                                <li>
                                                    <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#tambahSiswaModal">
                                                        Tambah Data Manual
                                                    </button>
                                                </li>
                                                <li>
                                                    <form action="{{ route('siswa.import') }}" method="POST"
                                                        enctype="multipart/form-data" class="m-0">
                                                        @csrf
                                                        <input type="file" name="file" class="d-none"
                                                            id="fileInput" required onchange="this.form.submit()">
                                                        <button type="button" class="dropdown-item"
                                                            onclick="document.getElementById('fileInput').click();">
                                                            Import Excel
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body" style="position: relative; z-index: 1;">
                                    <div class="table-responsive text-nowrap">
                                        <table class="table table-hover" style="text-align: center">
                                            <thead>
                                                <tr>

                                                    <th>No</th>
                                                    <th>Nama</th>
                                                    <th>NIS</th>
                                                    <th>Kelas</th>
                                                    <th>Konsentrasi Keahlian</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($siswa as $index => $s)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $s->name }}</td>
                                                        <td>{{ $s->nip }}</td>
                                                        <td>
                                                            {{ trim(($s->kelas->kelas ?? '') . ' ' . ($s->kelas->name_kelas ?? '')) }}
                                                            <small class="text-muted d-block">ID :
                                                                {{ $s->kelas_id ?? '-' }}</small>
                                                        </td>
                                                        <td>{{ optional($s->konke)->name_konke ?? '-' }}
                                                            <small class="text-muted d-block">ID :
                                                                {{ $s->konke_id ?? '-' }}</small>
                                                        </td>
                                                        <td></td>
                                                        <td>
                                                            <div class="aksi-icon-group">
                                                                <button class="btn btn-warning btn-sm"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editSiswaModal{{ $s->id }}">
                                                                    <i class="bi bi-pen"></i>
                                                                </button>
                                                                <a href="{{ route('siswa.detail', $s->id) }}"
                                                                    class="btn btn-info btn-sm">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <form action="{{ route('siswa.destroy', $s->id) }}"
                                                                    method="POST"class="delete-form d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="delete-btn btn btn-danger btn-sm">
                                                                        <i class="fas fa-trash"></i></button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>

                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @foreach ($siswa as $s)
        <div class="modal fade" id="editSiswaModal{{ $s->id }}" tabindex="-1"
            aria-labelledby="editSiswaModalLabel{{ $s->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white student-edit-modal-header">
                        <h5 class="modal-title" id="editSiswaModalLabel{{ $s->id }}">Edit Data Siswa</h5>
                        <button type="button" class="btn-close modal-close-light" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form action="{{ route('siswa.update', $s->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Siswa</label>
                                <input type="text" class="form-control" name="name" value="{{ $s->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">NIS</label>
                                <input type="text" class="form-control" name="nip" value="{{ $s->nip }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jurusan</label>
                                <select class="form-control student-jurusan" name="konke_id" required>
                                    <option value="">Pilih Konsentrasi Keahlian</option>
                                    @foreach ($konke as $k)
                                        <option value="{{ $k->id }}" @selected($s->konke_id == $k->id)>
                                            {{ $k->name_konke }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kelas (XII)</label>
                                <select class="form-control student-kelas" name="kelas_id" required disabled>
                                    <option value="">Pilih Jurusan terlebih dahulu</option>
                                    @foreach ($kelas as $kls)
                                        <option value="{{ $kls->id }}" data-konke-id="{{ $kls->konke_id }}"
                                            data-kelas="{{ $kls->name_kelas }}" @selected($s->kelas_id == $kls->id)>
                                            {{ trim(($kls->kelas ?? 'XII') . ' ' . $kls->name_kelas) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ $s->email }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password Baru (Opsional)</label>
                                <div class="input-group">
                                    <input type="password" class="form-control"
                                        id="password-optional-{{ $s->id }}" name="password">
                                    <button type="button" class="btn btn-outline-secondary toggle-password"
                                        data-target="password-optional-{{ $s->id }}" tabindex="-1">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
    {{-- create datasiswa --}}
    <div class="modal fade" id="tambahSiswaModal" tabindex="-1" aria-labelledby="tambahSiswaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('siswa.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Siswa</label>
                            <input type="text" class="form-control" name="name" placeholder="Masukkan Nama Siswa"
                                required>
                            <small class="form-text text-muted">Nama lengkap ini akan tercatat di sistem, pastikan sudah
                                benar ya!</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIS</label>
                            <input type="text" class="form-control" name="nip" placeholder="Masukkan NISN"
                                required>
                            <small class="form-text text-muted">Isi NIS di sini, pastikan sudah benar ya!</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kelas</label>
                            <input type="text" class="form-control"
                                value="{{ $s->kelas->kelas ?? '' }} {{ optional($s->kelas ?? '')->name_kelas ?? '-' }}"
                                readonly>
                            <input type="hidden" name="kelas_id" value="{{ $s->kelas_id ?? '' }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konsentrasi Keahlian</label>
                            <input type="text" class="form-control" value="{{ $s->konke->name_konke ?? '' }}"
                                readonly>
                            <input type="hidden" name="konke_id" value="{{ $s->konke_id ?? '' }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" name="email" placeholder="Masukkan Email"
                                required>
                            <small class="form-text text-muted">Masukkan email aktif. Pastikan bisa diakses ya!</small>
                        </div>
                       <div class="mb-3">
    <label class="form-label">Password</label>
    <div class="input-group">
        <input type="password" class="form-control" id="password-input" name="password" placeholder="Masukkan Password" required>
        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password-input" tabindex="-1">
            <i class="fas fa-eye-slash"></i>
        </button>
    </div>
    <small class="form-text text-muted">Password minimal 8 karakter.</small>
</div>


                    </div>
                    <div class="modal-footer">
                        {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button> --}}

                        <button type="submit" class="btn btn-primary">Simpan</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('siswa.datasiswa.createSiswa')
    @include('siswa.datasiswa.editSiswa')

        </body>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const kelasByKonkeId = @json($kelasByKonkeId);

                document.querySelectorAll('.student-jurusan').forEach(jurusanSelect => {
                    const modal = jurusanSelect.closest('.modal-content');
                    const kelasSelect = modal.querySelector('.student-kelas');
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
    </html>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle konfirmasi hapus
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();

                    Swal.fire({
                        title: "Apakah kamu yakin?",
                        text: "Data ini tidak bisa dikembalikan!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya, Hapus!",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.closest('form').submit();
                        }
                    });
                });
            });

            // SweetAlert notifikasi sukses
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: @json(session('success')),
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif

            // SweetAlert error validasi
            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menambahkan Siswa',
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                    // confirmButtonText: 'Tutup'
                });
            @endif
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
@endsection
