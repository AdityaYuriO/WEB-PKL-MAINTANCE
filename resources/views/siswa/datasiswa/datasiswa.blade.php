@extends('layout.main')
@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Data Siswa</title>

        <style>
            .student-actions-card {
                overflow: visible !important;
            }

            @media (max-width: 768px) {
                .table th:nth-child(2),
                .table td:nth-child(2) {
                    min-width: 180px;
                    max-width: 100%;
                    white-space: normal;
                    word-wrap: break-word;
                    overflow-wrap: break-word;
                }
                .table th:nth-child(5),
                .table td:nth-child(5) {
                    min-width: 180px;
                    max-width: 100%;
                    white-space: normal;
                    word-wrap: break-word;
                    overflow-wrap: break-word;
                }
            }
        </style>

    </head>

    <body>
        <div class="container-fluid">
            <div class="content-wrapper">

                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row">

                        <div class="card mb-3 student-actions-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="mb-0">Data Siswa</h5>

                                    {{-- Desktop View --}}
                                    <div class="d-none d-md-flex gap-2">
                                        <a href="{{ route('siswa.download-template') }}" class="btn btn-success btn-sm">
                                            <i class="bi bi-download"></i>
                                        </a>

                                        <button type="button" class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#searchModal">
                                            <i class="bi bi-search"></i>
                                        </button>

                                        @if(in_array(auth()->user()->role, ['hubin', 'guru']))
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-plus-lg"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#tambahSiswaModal">
                                                            Tambah Data Manual
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="file" name="file" class="d-none" id="fileInput" required onchange="this.form.submit()">
                                                            <button type="button" class="dropdown-item" onclick="document.getElementById('fileInput').click();">
                                                                Import Excel
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Mobile View --}}
                                    <div class="d-flex d-md-none">
                                        <div class="dropdown">
                                            <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a href="{{ route('siswa.download-template') }}" class="dropdown-item text-success">
                                                        <i class="bi bi-download me-1"></i> Download Template
                                                    </a>
                                                </li>
                                                <li>
                                                    <button type="button" class="dropdown-item text-warning" data-bs-toggle="modal" data-bs-target="#searchModal">
                                                        <i class="bi bi-search me-1"></i> Search
                                                    </button>
                                                </li>

                                                @if(in_array(auth()->user()->role, ['hubin', 'guru']))
                                                    <li>
                                                        <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#tambahSiswaModal">
                                                            <i class="bi bi-plus me-1"></i> Tambah Manual
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="file" name="file" class="d-none" id="fileInputMobile" required onchange="this.form.submit()">
                                                            <button type="button" class="dropdown-item" onclick="document.getElementById('fileInputMobile').click();">
                                                                <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="searchModalLabel">Cari Data Siswa</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="#">
                                            <input type="text" name="search" class="form-control" placeholder="Cari Siswa...">
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow-sm border-0 mb-4">
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
                                <div class="table-responsive text-nowrap">
                                    <table class="table table-hover">
                                        <thead style="text-align: center">
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
                                        <tbody style="text-align: center">
                                            @foreach ($siswa as $index => $s)
                                                <tr>
                                                    <td>{{ $siswa->firstItem() + $loop->index }}</td>                                                    <td>{{ $s->name }}</td>
                                                    <td>{{ $s->nip }}</td>
                                                    <td>
                                                        {{ optional($s->kelas)->name_kelas ?? '-' }}
                                                        <small class="text-muted d-block">ID : {{ $s->kelas_id ?? '-' }}</small>
                                                    </td>
                                                    <td>{{ optional($s->konke)->name_konke ?? '-' }}
                                                        <small class="text-muted d-block">ID : {{ $s->konke_id ?? '-' }}</small>
                                                    </td>
                                                    <td></td>
                                                    <td>
                                                        <div class="d-flex gap-2 justify-content-center flex-nowrap">
                                                            @if(in_array(auth()->user()->role, ['hubin', 'guru', 'kaprog']))
                                                            <button class="btn btn-warning btn-sm d-flex align-items-center"
                                                                data-bs-toggle="modal" data-bs-target="#editSiswaModal{{ $s->id }}">
                                                                <i class="bi bi-pen"></i>
                                                            </button>
                                                            @endif

                                                             @if (auth()->user()->role == 'kaprog')
                                                            <a href="{{ route('kaprog.siswa.detail', $s->id) }}" class="btn btn-info btn-sm d-flex align-items-center">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        @elseif(auth()->user()->role == 'hubin')
                                                            <a href="{{ route('siswa.detail', $s->id) }}" class="btn btn-info btn-sm d-flex align-items-center">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        @elseif(auth()->user()->role == 'kepsek')
                                                            <a href="{{ route('kepsek.siswa.detail', $s->id) }}" class="btn btn-info btn-sm d-flex align-items-center">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        @endif
                                                            @if(in_array(auth()->user()->role, ['hubin', 'guru']))
                                                            <form action="{{ route('siswa.destroy', $s->id) }}" method="POST" class="form-hapus-siswa">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm d-flex align-items-center">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>


                                                {{-- Modal Edit Siswa --}}
                                                <div class="modal fade" id="editSiswaModal{{ $s->id }}"
                                                    tabindex="-1" aria-labelledby="editSiswaModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Edit Data Siswa</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <form action="{{ route('siswa.update', $s->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Nama Lengkap Siswa*</label>
                                                                        <input type="text" class="form-control" name="name" value="{{ $s->name }}" required>
                                                                        <small class="form-text text-muted"><i>Nama lengkap ini akan tercatat di sistem, pastikan sudah benar!</i></small>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">NIS*</label>
                                                                        <input type="text" class="form-control" name="nip" value="{{ $s->nip }}" required>
                                                                        <small class="form-text text-muted"><i>Isi NIS di sini, pastikan sudah benar!</i></small>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Kelas*</label>
                                                                        <select class="form-control" name="kelas_id"
                                                                            required>
                                                                            <option value="">Pilih Kelas</option>
                                                                            @foreach ($kelas as $kls)
                                                                                <option value="{{ $kls->id }}"
                                                                                    {{ $s->kelas_id == $kls->id ? 'selected' : '' }}>
                                                                                    {{-- {{ $kls->kelas }} --}}
                                                                                    {{ $kls->name_kelas }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        <small class="form-text text-muted"><i>Pilih kelas yang sesuai!</i></small>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label"> Konsentrasi
                                                                            Keahlian*</label>
                                                                        <select class="form-control" name="konke_id"
                                                                            required>
                                                                            <option value="">Pilih Konsentrasi
                                                                                Keahlian</option>
                                                                            @foreach ($konke as $k)
                                                                                <option value="{{ $k->id }}"
                                                                                    {{ $s->konke_id == $k->id ? 'selected' : '' }}>
                                                                                    {{ $k->name_konke }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Email*</label>
                                                                        <input type="text" class="form-control" name="email" value="{{ $s->email }}" required>
                                                                        <small class="form-text text-muted"><i>Masukkan email aktif. Pastikan bisa diakses!</i></small>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Password Baru
                                                                            (Opsional)</label>
                                                                              <div class="input-group">
                                                                        <input type="password"  id="password-edit-{{ $s->id }}" class="form-control"
                                                                            name="password">
                                                                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password-edit-{{ $s->id }}" tabindex="-1">
                                                                            <i class="fas fa-eye-slash"></i>
                                                                        </button>
                                                                    </div>
                                                                        <small class="form-text text-muted"><i>Password minimal 8 karakter.</i></small>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    {{-- <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button> --}}
                                                                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-end mt-3">
                                    {{ $siswa->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
    </body>
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
                            <label class="form-label">Nama Lengkap Siswa*</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Masukkan Nama Lengkap Siswa" required>
                            <small class="form-text text-muted"><i>Nama lengkap ini akan tercatat di sistem, pastikan sudah benar!</i></small>

                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">NIS*</label>
                            <input type="text" class="form-control" name="nip" value="{{ old('nip') }}" placeholder="Masukkan NISN" required>
                            <small class="form-text text-muted"><i>Isi NIS di sini, pastikan sudah benar!</i></small>
                            @error('nip')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kelas</label>
                            <select class="form-control" name="kelas_id" required>
                                <option value="">Pilih Kelas</option>
                                @foreach ($kelas as $kls)
                                    <option value="{{ $kls->id }}" {{ old('kelas_id') == $kls->id ? 'selected' : '' }}> {{ $kls->name_kelas }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted"><i>Pilih kelas yang sesuai!</i></small>
                            @error('kelas_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konsentrasi Keahlian*</label>
                            <select class="form-control" name="konke_id" required>
                                <option value="">Pilih Konsentrasi Keahlian</option>
                                @foreach ($konke as $k)
                                    <option value="{{ $k->id }}" {{ old('konke_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->name_konke }}
                                    </option>
                                @endforeach
                            </select>
                            @error('konke_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email*</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Masukkan Email" required>
                            <small class="form-text text-muted"><i>Masukkan email aktif. Pastikan bisa diakses!</i></small>
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password*</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password-create" name="password"
                                    placeholder="Masukkan Password" required>
                                <button type="button" class="btn btn-outline-secondary toggle-password"
                                    data-target="password-create" tabindex="-1">
                                    <i class="fas fa-eye-slash"></i>
                                </button>
                            </div>
                            <small class="form-text text-muted"><i>Password minimal 8 karakter.</i></small>
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('siswa.datasiswa.createSiswa')
    @include('siswa.datasiswa.editSiswa')

    </html>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    if (input) {
                        if (input.type === 'password') {
                            input.type = 'text';
                            icon.classList.remove('bi-eye-slash');
                            icon.classList.add('bi-eye');
                        } else {
                            input.type = 'password';
                            icon.classList.remove('bi-eye');
                            icon.classList.add('bi-eye-slash');
                        }
                    }
                });
            });

            // Delete confirmation
            document.querySelectorAll('.form-hapus-siswa').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: "Data siswa yang dihapus tidak bisa dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Search functionality
            const searchInput = document.querySelector("input[name='search']");
            if (searchInput) {
                const tableRows = document.querySelectorAll("tbody tr");
                searchInput.addEventListener("keyup", function() {
                    const searchValue = this.value.toLowerCase();
                    tableRows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchValue) ? "" : "none";
                    });
                });
            }

            // Flash messages
            let successMessage = "{{ session('success') }}";
            let errorMessage = "{{ session('error') }}";

            if (successMessage) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: successMessage,
                    timer: 2000,
                    showConfirmButton: false
                });
            }

            if (errorMessage) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: errorMessage,
                    showConfirmButton: true
                });
            }
        });
    </script>
@endsection
