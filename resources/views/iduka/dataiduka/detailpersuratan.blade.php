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

                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Detail Data Institusi / Perusahaan</h5>
                    
                                <!-- Tombol dan Dropdown -->
                                <div class="d-flex gap-2 ms-auto align-items-center">
                                    {{-- Tombol Kembali --}}
                                    <a href="{{ route('data.iduka') }}" class="btn btn-primary btn-back btn-sm shadow-sm">
                                        <i class="bi bi-arrow-left-circle"></i>
                                        <span class="d-none d-md-inline">Kembali</span>
                                    </a>
                    
                                    {{-- Tombol Download ATP (Hanya Desktop) --}}
                                    @if(auth()->user()->role === 'kaprog')
                                        <a href="{{ route('kaprog.download.atp', $iduka->id) }}" class="btn btn-danger btn-sm d-none d-md-inline">
                                            Download PDF ATP
                                        </a>
                                    @elseif(auth()->user()->role === 'persuratan')
                                        <a href="{{ route('persuratan.download.atp', $iduka->id) }}" class="btn btn-danger btn-sm d-none d-md-inline">
                                            Download PDF ATP
                                        </a>
                                    @endif
                    
                                    {{-- Dropdown Titik Tiga --}}
                                    <div class="dropdown">
                                        <button class="btn btn-light p-1 rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('data.iduka') }}" class="dropdown-item">
                                                    <span class="text-primary">Kembali</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" class="dropdown-item">
                                                    <span class="text-danger">Export PDF</span>
                                                </a>
                                            </li>
                                            {{-- Download PDF ATP (Tersedia di Dropdown untuk semua ukuran layar) --}}
                                            <li>
                                                <a href="{{ auth()->user()->role === 'kaprog' ? route('kaprog.download.atp', $iduka->id) : route('hubin.download.atp', $iduka->id) }}" class="dropdown-item">
                                                    <span class="text-success">Download PDF ATP</span>
                                                </a>
                                            </li>
                                            @if(auth()->user()->role == 'kaprog')
                                                <li>
                                                    <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editIdukaModalKaprog">
                                                        <i class="bi bi-pencil-square text-warning"></i>
                                                        <span class="text-warning">Edit </span>
                                                    </a>
                                                </li>
                                            @elseif(auth()->user()->role == 'hubin')
                                                <li>
                                                    <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editIdukaModalHubin">
                                                        <i class="bi bi-pencil-square text-primary"></i>
                                                        <span class="text-primary">Edit</span>
                                                    </a>
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

                                </table>
                            </div>
                            <div class="col-lg-12 d-flex justify-content-between mt-4">
                                <a href="{{ route('data.iduka') }}" class="btn btn-back shadow-sm">
                                    Kembali
                                </a>
                                @if(auth()->user()->role == 'siswa')
                                <button id="btnBuatUsulan" type="button" class="btn btn-success btn-sm d-none">
                                    <i class="bi bi-file-earmark-plus"></i> Buat Usulan
                                </button>
                                <form action="{{ route('pengajuan.ajukan', $iduka->id) }}" method="POST" class="ajukan-form">
                                    @csrf
                                    <button type="submit" id="btnAjukanPKL" class="ajukan-btn btn btn-primary shadow-sm">
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
    </script>

    @include('iduka.dataiduka.editiduka')
</body>

</html>

@endsection
