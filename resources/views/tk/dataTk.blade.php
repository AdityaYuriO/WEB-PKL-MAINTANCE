@extends('layout.main')
@section('content')
<div class="container-fluid">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">Data Tenaga Kependidikan</h5>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#searchModal">
                                    <i class="bi bi-search"></i>
                                    <span class="d-none d-md-inline">Search</span>
                                </button>

                                @if(in_array(auth()->user()->role, ['hubin']))
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahTkModal">
                                    <i class="bi bi-plus-lg"></i> <span class="d-none d-md-inline">Tambah</span>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="searchModalLabel">Cari Tenaga Kependidikan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="#">
                                        <input type="text" name="search" class="form-control" placeholder="Cari Tenaga Kependidikan...">
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-hover" style="text-align: center">
                                <thead>
                                    <th>No</th>
                                    <th style="min-width: 300px;">Nama GTK</th>
                                    <th>NIK</th>
                                    <th>NIP/NUPTK</th>
                                    <th>Email</th>
                                    <th>Aksi</th>
                                </thead>
                                <tbody>
                                    @foreach ($kependik as $index => $item)
                                    <tr>
                                        <td>{{ ($kependik->firstItem() ?? 1) + $loop->index }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->nik }}</td>
                                        <td>{{ $item->nip_nuptk ?? '-' }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>
                                            <div class="d-flex gap-1 justify-content-center flex-nowrap">
                                                @if(in_array(auth()->user()->role, ['hubin']))
                                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editTkModal{{ $item->id }}">
                                                    <i class="bi bi-pen"></i>
                                                </button>
                                                @endif

                                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailTkModal{{ $item->id }}">
                                                    <i class="bi bi-eye"></i>
                                                </button>

                                                @if(in_array(auth()->user()->role, ['hubin']))
                                                <form action="{{ route('kependik.destroy', $item->id) }}" method="POST" class="delete-form d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="delete-btn btn btn-danger btn-sm">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="editTkModal{{ $item->id }}" tabindex="-1" aria-labelledby="editTkModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="editTkModalLabel">Form Edit Data Tenaga Kependidikan</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('kependik.update', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Nama Lengkap*</label>
                                                            <input type="text" class="form-control" name="nama" value="{{ $item->nama }}" required>
                                                            <small class="form-text text-muted"><i>Nama lengkap ini akan tercatat di sistem, pastikan sudah benar!</i></small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">NIK*</label>
                                                            <input type="text" class="form-control" name="nik" value="{{ $item->nik }}" required>
                                                            <small class="form-text text-muted"><i>Isi NIK di sini ya, pastikan sesuai dengan data di KTP</i></small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">NIP/NUPTK (opsional)</label>
                                                            <input type="text" class="form-control" name="nip_nuptk" value="{{ $item->nip_nuptk }}">
                                                            <small class="form-text text-muted"><i>Isi NIP atau NUPTK di sini, pastikan sudah benar!</i></small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Tempat Lahir*</label>
                                                            <input type="text" class="form-control" name="tempat_lahir" value="{{ $item->tempat_lahir }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Tanggal Lahir*</label>
                                                            <input type="date" class="form-control" name="tanggal_lahir" value="{{ $item->tanggal_lahir }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Jenis Kelamin*</label>
                                                            <select class="form-control" name="jenis_kelamin" required>
                                                                <option value="Laki-laki" {{ $item->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                                                <option value="Perempuan" {{ $item->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Alamat*</label>
                                                            <textarea class="form-control" name="alamat" rows="3" required>{{ $item->alamat }}</textarea>
                                                            <small class="form-text text-muted"><i>Pastikan alamat ditulis dengan lengkap dan sesuai.</i></small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Email*</label>
                                                            <input type="email" class="form-control" name="email" value="{{ $item->email }}" required>
                                                            <small class="form-text text-muted"><i>Masukkan email aktif. Pastikan bisa diakses!</i></small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">No HP*</label>
                                                            <input type="text" class="form-control" name="no_hp" value="{{ $item->no_hp }}" required>
                                                            <small class="form-text text-muted"><i>Masukkan nomor hp aktif. Pastikan bisa diakses!</i></small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Password (Kosongkan jika tidak ingin diubah)</label>
                                                              <div class="input-group">
                                                            <input type="password" class="form-control"  id="password-required" name="password" placeholder="Masukkan Password Baru">
                                                              <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password-required" tabindex="-1">
            <i class="bi bi-eye-slash"></i>
        </button>
    </div>
                                                            <small class="form-text text-muted"><i>Password minimal 8 karakter.</i></small>
                                                          
      ton>
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
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($kependik instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="d-flex justify-content-end mt-3">
                            {{ $kependik->links('pagination::bootstrap-5') }}
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Modal Tambah --}}
                <div class="modal fade" id="tambahTkModal" tabindex="-1" aria-labelledby="tambahTkModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="tambahTkModalLabel">Form Tambah Data Tenaga Kependidikan</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('kependik.store') }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Lengkap*</label>
                                        <input type="text" class="form-control" name="nama" placeholder="Masukkan Nama Lengkap" required>
                                        <small class="form-text text-muted"><i>Nama lengkap ini akan tercatat di sistem, pastikan sudah benar!</i></small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">NIK*</label>
                                        <input type="text" class="form-control" name="nik" placeholder="Masukkan NIK" required>
                                        <small class="form-text text-muted"><i>Isi NIK di sini ya, pastikan sesuai dengan data di KTP!</i></small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">NIP/NUPTK (opsional)</label>
                                        <input type="text" class="form-control" name="nip_nuptk" placeholder="Masukkan NIP/NUPTK">
                                        <small class="form-text text-muted"><i>Isi NIP atau NUPTK di sini, pastikan sudah benar!</i></small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tempat Lahir*</label>
                                        <input type="text" class="form-control" name="tempat_lahir" placeholder="Masukkan Tempat Lahir" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Lahir*</label>
                                        <input type="date" class="form-control" name="tanggal_lahir" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Jenis Kelamin*</label>
                                        <select class="form-control" name="jenis_kelamin" required>
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Alamat*</label>
                                        <textarea class="form-control" name="alamat" rows="3" placeholder="Masukkan Alamat" required></textarea>
                                        <small class="form-text text-muted"><i>Pastikan alamat ditulis dengan lengkap dan sesuai.</i></small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email*</label>
                                        <input type="email" class="form-control" name="email" placeholder="Masukkan Email" required>
                                        <small class="form-text text-muted"><i>Masukkan email aktif. Pastikan bisa diakses!</i></small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">No HP*</label>
                                        <input type="text" class="form-control" name="no_hp" placeholder="Masukkan No HP" required>
                                        <small class="form-text text-muted"><i>Masukkan nomor hp aktif. Pastikan bisa diakses!</i></small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password*</label>
                                         <div class="input-group">
                                        <input type="password" class="form-control"  id="password-required" name="password" placeholder="Masukkan Password" required>
                                          <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password-required" tabindex="-1">
            <i class="bi bi-eye-slash"></i>
        </button>
    </div>
                                        <small class="form-text text-muted"><i>Password minimal 8 karakter.</i></small>
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
            </div>
        </div>
    </div>
</div>
@include('tk.detailTk')
{{-- alert hapus --}}

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Mencegah submit langsung

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
                        this.closest('form').submit(); // Submit form setelah konfirmasi
                    }
                });
            });
        });

        @if(session('success'))
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
        title: 'Gagal Menambahkan',
        html: `{!! implode('<br>', $errors->all()) !!}`,
        confirmButtonText: 'Tutup'
    });
    @endif
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector("#tambahTkModal form");

        form.addEventListener("submit", function(event) {
            const password = form.querySelector("[name='password']").value;
            if (password.length < 6) {
                event.preventDefault();
                alert("Password minimal 6 karakter!");
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.querySelector("input[name='search']");
        const tableRows = document.querySelectorAll("tbody tr");

        searchInput.addEventListener("keyup", function() {
            const searchValue = this.value.toLowerCase();

            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchValue) ? "" : "none";
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

@endsection
