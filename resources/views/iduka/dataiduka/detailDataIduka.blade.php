@extends('layout.main')
@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Detail INSTITUSI</title>
        <style>
            html,
            body {
                max-width: 100%;
                overflow-x: hidden;
            }

            .table td {
                vertical-align: middle;
            }

            .table td:first-child {
                width: 30%;
                text-align: left;
            }

            .card-header {
                max-width: 100%;
                padding: 25px 20px 10px 20px;
                border-radius: 8px 8px 0 0;
            }

            @media (max-width: 576px) {
                .modal-dialog {
                    max-width: 95%;
                }

                table {
                    font-size: 14px;
                    white-space: nowrap;
                }

                td,
                th {
                    padding: 8px;
                }

                h4 {
                    font-size: 16px;
                }
            }

            .table-responsive {
                width: 100%;
                overflow-x: auto;
            }


            /* Responsif untuk tabel */
            @media (max-width: 768px) {
                .table-responsive {
                    overflow-x: hidden;
                    /* Hapus scroll ke samping */
                }

                .table {
                    width: 100%;
                    border-collapse: collapse;
                }

                .table tr {
                    display: flex;
                    flex-direction: column;
                    padding: 10px;
                }

                .table td {
                    display: block;
                    width: 100%;
                    padding: 8px;
                }

                .table td:first-child {
                    display: inline-block;
                    width: auto;
                    white-space: nowrap;
                    font-weight: bold;
                }

                .table td:nth-child(2) {
                    display: block;
                    margin-top: 5px;
                    color: #333;
                    white-space: normal;
                    /* Biar teks bisa turun ke bawah */
                    word-wrap: break-word;
                    /* Biar nggak kepotong */
                    overflow-wrap: break-word;
                    /* Alternatif buat jaga-jaga */
                }
            }
        </style>
    </head>

    <body>
        <div class="container-fluid">
            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row">

                        <div class="card mb-3" style="overflow: visible;">
                            <div class="card-body ">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Detail Data Institusi / Perusahaan</h5>

                                    <!-- Tombol dan Dropdown -->
                                    <div class="d-flex gap-2 ms-auto align-items-center">
                                        {{-- Tombol Kembali --}}


                                        @if (auth()->user()->role == 'kaprog')
                                            <a href="{{ route('iduka.index') }}"
                                                class="btn btn-primary btn-back btn-sm shadow-sm">
                                                <i class="bi bi-arrow-left-circle"></i>
                                                <span class="d-none d-md-inline">Kembali</span>
                                            </a>
                                        @elseif(auth()->user()->role == 'hubin')
                                            <a href="{{ route('hubin.iduka.index') }}"
                                                class="btn btn-primary btn-back btn-sm shadow-sm">
                                                <i class="bi bi-arrow-left-circle"></i>
                                                <span class="d-none d-md-inline">Kembali</span>
                                            </a>
                                        @elseif(auth()->user()->role == 'kepsek')
                                            <a href="{{ route('kepsek.iduka.index') }}"
                                                class="btn btn-primary btn-back btn-sm shadow-sm">
                                                <i class="bi bi-arrow-left-circle"></i>
                                                <span class="d-none d-md-inline">Kembali</span>
                                            </a>
                                        @endif

                                        {{-- Tombol Download ATP (Hanya Desktop) --}}
                                        @if (auth()->user()->role === 'kaprog')
                                            <a href="{{ route('kaprog.download.atp', $iduka->id) }}"
                                                class="btn btn-danger btn-sm d-none d-md-inline">
                                                Download PDF ATP
                                            </a>
                                        @elseif(auth()->user()->role === 'persuratan')
                                            <a href="{{ route('hubin.download.atp', $iduka->id) }}"
                                                class="btn btn-danger btn-sm d-none d-md-inline">
                                                Download PDF ATP
                                            </a>
                                        @endif

                                        {{-- Dropdown Titik Tiga --}}
                                        <div class="dropdown">
                                            <button class="btn btn-light p-1 rounded-circle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                              
                                                <li>
                                                    <a href="{{ route('detailIduka.pdf', $iduka->id) }}"
                                                        class="dropdown-item">
                                                        <span class="text-danger">Export PDF 1</span>
                                                    </a>
                                                </li>
                                                {{-- Download PDF ATP (Tersedia di Dropdown untuk semua ukuran layar) --}}
                                                <li>
                                                    <a href="{{ auth()->user()->role === 'kaprog' ? route('kaprog.download.atp', $iduka->id) : route('hubin.download.atp', $iduka->id) }}"
                                                        class="dropdown-item">
                                                        <span class="text-success">Download PDF ATP</span>
                                                    </a>
                                                </li>
                                                @if (auth()->user()->role == 'kaprog')
                                                    <li>
                                                        <button class="dropdown-item text-warning" data-bs-toggle="modal"
                                                            data-bs-target="#editKelasModal{{ $iduka->id }}">
                                                            Edit
                                                        </button>
                                                    </li>
                                                @elseif(auth()->user()->role == 'hubin')
                                                    <li>
                                                        <button class="dropdown-item text-warning" data-bs-toggle="modal"
                                                            data-bs-target="#editKelasModal{{ $iduka->id }}">
                                                            Edit
                                                        </button>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive text-nowrap">
                                    <table class="table table-hover">
                                        <tr>
                                            <td><i class="bi bi-building"></i> Nama INSTITUSI</td>
                                            <td>: {{ $iduka->nama }}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="bi bi-building"></i> Nama Pimpinan</td>
                                            <td>: {{ $iduka->nama_pimpinan ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="bi bi-building"></i> NIP/NIK Pimpinan</td>
                                            <td>: {{ $iduka->nip_pimpinan ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="bi bi-building"></i> Nomor Telepon Pimpinan</td>
                                            <td>: {{ $iduka->no_hp_pimpinan ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="bi bi-building"></i> Jabatan</td>
                                            <td>: {{ $iduka->jabatan ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="bi bi-geo-alt"></i> Alamat Lengkap</td>
                                            <td>: {{ $iduka->alamat }}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="bi bi-geo-alt"></i> Kode Pos</td>
                                            <td>: {{ $iduka->kode_pos }}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="bi bi-telephone"></i> Nomor Telepon</td>
                                            <td>: {{ $iduka->telepon }}</td>
                                        </tr>

                                        <tr>
                                            <td><i class="bi bi-envelope"></i> Email</td>
                                            <td>: {{ $iduka->email }}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="bi bi-lock"></i> Password</td>
                                            <td>: ******</td>
                                        </tr>
                                        <tr>
                                            <td><i class="bi bi-briefcase"></i> Bidang Industri</td>
                                            <td>: {{ $iduka->bidang_industri }}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="bi bi-handshake"></i> Kerjasama</td>
                                            <td>: {{ $iduka->kerjasama }}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="bi bi-people"></i> Jumlah Kuota PKL</td>
                                            <td>: {{ $iduka->kuota_pkl }}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="bi bi-people"></i> Jumlah Kuota Terisi</td>
                                            <td>: {{ $iduka->kuota_terisi }}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="bi bi-people"></i> Durasi kerjasama</td>
                                            <td>:
                                                @if ($iduka->mulai_kerjasama && $iduka->akhir_kerjasama)
                                                    {{ \Carbon\Carbon::parse($iduka->mulai_kerjasama)->translatedFormat('d F Y') }}
                                                    -
                                                    {{ \Carbon\Carbon::parse($iduka->akhir_kerjasama)->translatedFormat('d F Y') }}
                                                @else
                                                    Belum ditentukan
                                                @endif
                                            </td>
                                        </tr>

                                    </table>
                                </div>
                                <div class="col-lg-12 d-flex justify-content-between mt-4">
                                   @if (auth()->user()->role == 'kaprog')
                                            <a href="{{ route('iduka.index') }}"
                                                class="btn btn-primary btn-back btn-sm shadow-sm">
                                                <i class="bi bi-arrow-left-circle"></i>
                                                <span class="d-none d-md-inline">Kembali</span>
                                            </a>
                                        @elseif(auth()->user()->role == 'hubin')
                                            <a href="{{ route('hubin.iduka.index') }}"
                                                class="btn btn-primary btn-back btn-sm shadow-sm">
                                                <i class="bi bi-arrow-left-circle"></i>
                                                <span class="d-none d-md-inline">Kembali</span>
                                            </a>
                                        @elseif(auth()->user()->role == 'kepsek')
                                            <a href="{{ route('kepsek.iduka.index') }}"
                                                class="btn btn-primary btn-back btn-sm shadow-sm">
                                                <i class="bi bi-arrow-left-circle"></i>
                                                <span class="d-none d-md-inline">Kembali</span>
                                            </a>
                                        @endif

                                    @if (auth()->user()->role == 'siswa')
                                        <button id="btnBuatUsulan" type="button" class="btn btn-success btn-sm d-none">
                                            <i class="bi bi-file-earmark-plus"></i> Buat Usulan
                                        </button>
                                        <form action="{{ route('pengajuan.ajukan', $iduka->id) }}" method="POST"
                                            class="ajukan-form">
                                            @csrf
                                            <button type="submit" id="btnAjukanPKL"
                                                class="ajukan-btn btn btn-primary shadow-sm">
                                                Ajukan PKL
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal Edit Iduka --}}

        <div class="modal fade" id="editKelasModal{{ $iduka->id }}" tabindex="-1"
            aria-labelledby="editKelasModalLabel{{ $iduka->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white iduka-edit-modal-header">
                        <h5 class="modal-title" id="editKelasModalLabel{{ $iduka->id }}">Edit Data {{ $iduka->nama }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form action="{{ route('updateiduka.update', parameters: $iduka->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">

                            <div class="mb-3">
                                <label for="nama{{ $iduka->id }}" class="form-label">Nama Institusi*</label>
                                <input type="text" class="form-control" id="nama{{ $iduka->id }}" name="nama"
                                    value="{{ $iduka->nama }}" required>
                                <small class="form-text text-muted">Nama Institusi ini akan tercatat di sistem, pastikan
                                    sudah benar ya!</small>
                            </div>

                            <div class="mb-3">
                                <label for="nama_pimpinan{{ $iduka->id }}" class="form-label">Nama Pimpinan*</label>
                                <input type="text" class="form-control" id="nama_pimpinan{{ $iduka->id }}"
                                    name="nama_pimpinan" value="{{ $iduka->nama_pimpinan }}" required>
                                <small class="form-text text-muted">Nama lengkap ini akan tercatat di sistem, pastikan
                                    sudah benar ya!</small>
                            </div>

                            <div class="mb-3">
                                <label for="nip_pimpinan{{ $iduka->id }}" class="form-label">NIP Pimpinan*</label>
                                <input type="text" class="form-control" id="nip_pimpinan{{ $iduka->id }}"
                                    name="nip_pimpinan" value="{{ $iduka->nip_pimpinan }}" required>
                                <small class="form-text text-muted">Isi NIP atau NUPTK di sini, pastikan sudah benar
                                    ya!</small>
                            </div>

                            <div class="mb-3">
                                <label for="no_hp_pimpinan{{ $iduka->id }}" class="form-label">No HP Pimpinan*</label>
                                <input type="text" class="form-control" id="no_hp_pimpinan{{ $iduka->id }}"
                                    name="no_hp_pimpinan" value="{{ $iduka->no_hp_pimpinan }}" required>
                                <small class="form-text text-muted">Masukkan nomor telepon aktif. Pastikan bisa diakses
                                    ya!</small>
                            </div>

                            <div class="mb-3">
                                <label for="jabatan{{ $iduka->id }}" class="form-label">Jabatan*</label>
                                <input type="text" class="form-control" id="jabatan{{ $iduka->id }}"
                                    name="jabatan" value="{{ $iduka->jabatan }}" required>
                            </div>


                            <!-- Kolom Kanan -->

                            <div class="mb-3">
                                <label for="alamat{{ $iduka->id }}" class="form-label">Alamat*</label>
                                <textarea class="form-control" id="alamat{{ $iduka->id }}" name="alamat" required>{{ $iduka->alamat }}</textarea>
                                <small class="form-text text-muted">Masukkan alamat lengkap lokasi PKL di sini ya.</small>
                            </div>

                            <div class="mb-3">
                                <label for="kode_pos{{ $iduka->id }}" class="form-label">Kode Pos*</label>
                                <input type="text" class="form-control" id="kode_pos{{ $iduka->id }}"
                                    name="kode_pos" value="{{ $iduka->kode_pos }}" required>
                                <small class="form-text text-muted">Masukkan nomor telepon aktif. Pastikan bisa diakses
                                    ya!</small>
                            </div>

                            <div class="mb-3">
                                <label for="telepon{{ $iduka->id }}" class="form-label">Telepon*</label>
                                <input type="text" class="form-control" id="telepon{{ $iduka->id }}"
                                    name="telepon" value="{{ $iduka->telepon }}" required>
                                <small class="form-text text-muted">Masukkan nomor telepon aktif. Pastikan bisa diakses
                                    ya!</small>
                            </div>

                            <div class="mb-3">
                                <label for="email{{ $iduka->id }}" class="form-label">Email*</label>
                                <input type="email" class="form-control" id="email{{ $iduka->id }}" name="email"
                                    value="{{ $iduka->email }}" required>
                                <small class="form-text text-muted">Masukkan email aktif. Pastikan bisa diakses ya!</small>
                            </div>

                            <div class="mb-3">
                                <label for="password{{ $iduka->id }}" class="form-label">
                                    Password (Biarkan kosong jika tidak ingin diubah)
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password{{ $iduka->id }}"
                                        name="password" placeholder="Kosongkan jika tidak diubah">
                                    <button type="button" class="btn btn-outline-secondary toggle-password"
                                        data-target="password{{ $iduka->id }}" tabindex="-1">
                                        <i class="bi bi-eye-slash"></i>
                                    </button>
                                </div>
                                <small class="form-text text-muted">Password minimal 8 karakter.</small>
                            </div>



                            <!-- Bagian Bawah -->

                            <div class="mb-3">
                                <label for="bidang_industri{{ $iduka->id }}" class="form-label">Bidang
                                    Industri*</label>
                                <input type="text" class="form-control" id="bidang_industri{{ $iduka->id }}"
                                    name="bidang_industri" value="{{ $iduka->bidang_industri }}" required>
                                <small class="form-text text-muted">Silakan isi bidang industri yang Anda tekuni secara
                                    spesifik dan sesuai dengan sektor usaha.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kerjasama*</label>
                                <select class="form-select" name="kerjasama" id="kerjasama{{ $iduka->id }}" required>
                                    <option value="">Pilih Jenis Kerjasama</option>
                                    <option value="Sinkronisasi"
                                        {{ $iduka->kerjasama == 'Sinkronisasi' ? 'selected' : '' }}>Sinkronisasi</option>
                                    <option value="Guru Tamu" {{ $iduka->kerjasama == 'Guru Tamu' ? 'selected' : '' }}>
                                        Guru Tamu</option>
                                    <option value="Magang / Pelatihan"
                                        {{ $iduka->kerjasama == 'Magang / Pelatihan' ? 'selected' : '' }}>Magang /
                                        Pelatihan</option>
                                    <option value="PKL" {{ $iduka->kerjasama == 'PKL' ? 'selected' : '' }}>PKL</option>
                                    <option value="Sertifikasi"
                                        {{ $iduka->kerjasama == 'Sertifikasi' ? 'selected' : '' }}>Sertifikasi</option>
                                    <option value="Tefa" {{ $iduka->kerjasama == 'Tefa' ? 'selected' : '' }}>Tefa
                                    </option>
                                    <option value="Serapan" {{ $iduka->kerjasama == 'Serapan' ? 'selected' : '' }}>Serapan
                                    </option>
                                    <option value="Beasiswa" {{ $iduka->kerjasama == 'Beasiswa' ? 'selected' : '' }}>
                                        Beasiswa</option>
                                    <option value="PBL" {{ $iduka->kerjasama == 'PBL' ? 'selected' : '' }}>PBL</option>
                                    <option value="Lainnya" {{ $iduka->kerjasama == 'Lainnya' ? 'selected' : '' }}>Lainnya
                                    </option>
                                </select>
                            </div>

                            <div class="mb-3" id="kerjasama_lainnya_container{{ $iduka->id }}"
                                style="{{ $iduka->kerjasama == 'Lainnya' ? '' : 'display:none;' }}">
                                <label for="kerjasama_lainnya{{ $iduka->id }}" class="form-label">Jenis Kerjasama
                                    Lainnya</label>
                                <input type="text" class="form-control" id="kerjasama_lainnya{{ $iduka->id }}"
                                    name="kerjasama_lainnya" value="{{ $iduka->kerjasama_lainnya }}">
                            </div>



                            <div class="mb-3">
                                <label for="kuota_pkl{{ $iduka->id }}" class="form-label">Kuota PKL*</label>
                                <input type="number" class="form-control" id="kuota_pkl{{ $iduka->id }}"
                                    name="kuota_pkl" value="{{ $iduka->kuota_pkl }}" required>
                                <small class="form-text text-muted">Masukkan jumlah kuota PKL yang tersedia untuk
                                    siswa.</small>
                            </div>

                            @if (auth()->user()->role == 'hubin')
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="rekomendasi{{ $iduka->id }}"
                                        name="rekomendasi" value="1" {{ $iduka->rekomendasi ? 'checked' : '' }}>
                                    <label class="form-check-label"
                                        for="rekomendasi{{ $iduka->id }}">Rekomendasi</label>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button> --}}
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>


        <script>
            document.querySelectorAll('.ajukan-btn').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault(); // Mencegah penghapusan langsung

                    Swal.fire({
                        title: "Apakah kamu yakin?",
                        text: "Ingin mengajukan Institusi ini?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya, Ajukan!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.closest('.ajukan-form').submit();
                        }
                    });
                });
            });
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function() {
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

        @include('iduka.dataiduka.editiduka')
    </body>

    </html>
@endsection
