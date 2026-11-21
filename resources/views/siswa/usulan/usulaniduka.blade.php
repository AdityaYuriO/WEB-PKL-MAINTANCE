@extends('layout.main')
@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Institusi / Perusahaan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        html, body {
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

            td, th {
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
                overflow-x: hidden; /* Hapus scroll ke samping */
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
                white-space: normal; /* Biar teks bisa turun ke bawah */
                word-wrap: break-word; /* Biar nggak kepotong */
                overflow-wrap: break-word; /* Alternatif buat jaga-jaga */
            }
        }

        /* Custom Loading Overlay */
        #loader-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.9);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
    </style>
</head>

<body>
    <!-- Custom Loading Overlay -->
    <div id="loader-overlay">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
        <p class="mt-3 fw-semibold text-primary">Antosan Kedap...</p>
    </div>

    <!-- Alert untuk pesan error/sukses -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container-fluid">
        <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
                <div class="row">

                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Detail Data Institusi / Perusahaan</h5>

                                <!-- Tombol Titik Tiga -->
                                <div class="d-flex gap-2 ms-auto">
                                    <a href="{{ route('iduka.usulan') }}" class="btn btn-primary btn-back btn-sm shadow-sm">
                                        <i class="bi bi-arrow-left-circle"></i>
                                        <span class="d-none d-md-inline">Kembali</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover">
                                    <tr>
                                        <td><i class="bi bi-building"></i> Nama Institusi</td>
                                        <td>: {{ $iduka->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td><i class="bi bi-building"></i> Nama Pimpinan</td>
                                        <td>: {{ $iduka->nama_pimpinan ?? '-'}}</td>
                                    </tr>
                                    <tr>
                                        <td><i class="bi bi-building"></i> NIP/NIK Pimpinan</td>
                                        <td>: {{ $iduka->nip_pimpinan ?? '-'}}</td>
                                    </tr>
                                    <tr>
                                        <td><i class="bi bi-building"></i> Nomor Telepon Pimpinan</td>
                                        <td>: {{ $iduka->no_hp_pimpinan ?? '-'}}</td>
                                    </tr>
                                    <tr>
                                        <td><i class="bi bi-building"></i> Jabatan</td>
                                        <td>: {{ $iduka->jabatan?? '-' }}</td>
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
                                        <td><i class="bi bi-people"></i> Jumlah Kuota Terisi PKL</td>
                                        <td>: {{ $iduka->kuota_terisi }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-lg-12 d-flex justify-content-end mt-4">
                                @if(auth()->user()->role == 'siswa')
                                <form action="{{ route('usulan.iduka.storeAjukanPkl', $iduka->id) }}" method="POST" class="ajukan-form">
                                    @csrf
                                    <input type="hidden" name="nama" value="{{ $iduka->nama }}">
                                    <input type="hidden" name="alamat" value="{{ $iduka->alamat }}">
                                    <input type="hidden" name="kode_pos" value="{{ $iduka->kode_pos}}">
                                    <input type="hidden" name="telepon" value="{{ $iduka->telepon }}">
                                    <input type="hidden" name="email" value="{{ $iduka->email }}">
                                    <input type="hidden" name="bidang_industri" value="{{ $iduka->bidang_industri }}">
                                    <input type="hidden" name="kerjasama" value="{{ $iduka->kerjasama }}">
                                    <input type="hidden" name="kuota_pkl" value="{{ $iduka->kuota_pkl }}">

                                    <button type="button" class="ajukan-btn btn btn-primary shadow-sm">
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.ajukan-btn').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();

                // Cek apakah data pribadi sudah lengkap
                fetch('/api/check-data-pribadi')
                    .then(response => response.json())
                    .then(data => {
                        if (!data.complete) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Data Belum Lengkap',
                                text: 'Silakan lengkapi data pribadi terlebih dahulu sebelum mengajukan PKL.',
                                confirmButtonText: 'OK'
                            });
                            return;
                        }

                        // Lanjutkan dengan konfirmasi SweetAlert
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
                                // Tampilkan custom loading overlay
                                document.getElementById('loader-overlay').style.display = 'flex';

                                // Submit form menggunakan fetch
                                const form = this.closest('.ajukan-form');
                                const formData = new FormData(form);

                                fetch(form.action, {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                    }
                                })
                                .then(response => {
                                    // Sembunyikan loading overlay
                                    document.getElementById('loader-overlay').style.display = 'none';

                                    if (!response.ok) {
                                        return response.json().then(err => {
                                            throw new Error(err.message || 'Terjadi kesalahan');
                                        });
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil!',
                                            text: data.message,
                                            showConfirmButton: false,
                                            timer: 2000
                                        }).then(() => {
                                            window.location.href = data.redirect;
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal!',
                                            text: data.message,
                                            confirmButtonText: 'OK'
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Terjadi Kesalahan',
                                        text: error.message || 'Gagal mengajukan PKL. Silakan coba lagi.',
                                        confirmButtonText: 'OK'
                                    });
                                });
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error checking data:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Gagal memeriksa kelengkapan data. Silakan coba lagi.',
                            confirmButtonText: 'OK'
                        });
                    });
            });
        });
    </script>
</body>

</html>

@endsection


