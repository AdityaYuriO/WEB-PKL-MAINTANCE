@extends('layout.main')
@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Detail Institusi / Perusahaan</title>
        <style>
            .table td {
                vertical-align: middle;
            }

            .table td:first-child {
                width: 40%;
                text-align: left;
            }

            .table td:nth-child(2) {
                width: 1%;
                text-align: right;
            }

            .table td:last-child {
                width: 55%;
                text-align: left;
            }

            .card-header {
                max-width: 100%;
                padding: 25px 20px 10px 20px;
                border-radius: 8px 8px 0 0;
            }

            .btn-back {
                background-color: #7e7dfb;
                color: white;
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                font-size: 16px;
                cursor: pointer;
                transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out, background-color 0.2s ease-in-out;
            }

            .btn-back:hover {
                background-color: #7e7dfb;
                color: white;
                transform: translateY(-3px);
                box-shadow: 0 12px 24px rgba(0, 0, 0, 0.25);
            }

            .btn-back:active {
                color: white;
                background-color: #6b6bfa !important;
                transform: translateY(3px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }

            .table-responsive {
                overflow-x: auto;
            }
            .table {
                min-width: 100%;
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
                                    <h5 class="mb-0">Data Pribadi Institusi / Perusahaan</h5>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('iduka.download-pdf', $iduka->id) }}" class="btn btn-danger btn-sm d-flex align-items-center gap-2">
                                            <i class="bi bi-filetype-pdf"></i>
                                            <span class="d-none d-md-inline">PDF</span>
                                        </a>
                                        <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#editDataPribadiIdukaModal">
                                            <i class="bi bi-pencil-square"></i>
                                            <span class="d-none d-md-inline">Edit</span>
                                        </button>
                                    </div>
                                </div>                                
                            </div>
                        </div>
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div class="card-header" style="background-color: #7e7dfb">
                            <h5 style="color: white;">DATA PRIBADI Institusi / Perusahaan</h5>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                {{-- <div class="d-flex justify-content-end mt-0 mb-2">
                                <button type="button" class="btn btn-danger gap-2 me-2" data-bs-toggle="modal" data-bs-target="#tambahIdukaModal">
                                    <i class="bi bi-filetype-pdf"></i>
                                </button>

                            </div> --}}
                            
                                <div class="table-responsive text-nowrap">
                                    <table class="table table-hover">
                                        @if($iduka)
                                            <tr>
                                                <td><i class="bi bi-building"></i> Nama Institusi / Perusahaan</td>
                                                <td>:</td>
                                                <td>{{ $iduka->nama }}</td>
                                            </tr>
                                            <tr>
                                                <td><i class="bi bi-geo-alt"></i> Alamat Lengkap</td>
                                                <td>:</td>
                                                <td>{{ $iduka->alamat }}</td>
                                            </tr>
                                            <tr>
                                                <td><i class="bi bi-geo-alt"></i> Kode Pos</td>
                                                <td> : </td>
                                                <td>{{ $iduka->kode_pos }}</td>
                                            </tr>
                                            <tr>
                                                <td><i class="bi bi-telephone"></i> Nomor Telepon Institusi</td>
                                                <td>:</td>
                                                <td>{{ $iduka->telepon }}</td>
                                            </tr>
    
                                            <tr>
                                                <td><i class="bi bi-envelope"></i> Email</td>
                                                <td>:</td>
                                                <td>{{ $iduka->email }}</td>
                                            </tr>
                                            <tr>
                                                <td><i class="bi bi-lock"></i> Password</td>
                                                <td>:</td>
                                                <td>******</td>
                                            </tr>
                                            <tr>
                                                <td><i class="bi bi-briefcase"></i> Bidang Industri</td>
                                                <td>:</td>
                                                <td>{{ $iduka->bidang_industri }}</td>
                                            </tr>
                                            <tr>
                                                <td><i class="bi bi-handshake"></i> Kerjasama</td>
                                                <td>:</td>
                                                <td>{{ $iduka->kerjasama }} {{ $iduka->kerjasama_lainnya }}</td>
                                            </tr>
                                            <tr>
                                                <td><i class="bi bi-people"></i> Jumlah Kuota PKL</td>
                                                <td>:</td>
                                                <td>{{ $iduka->kuota_pkl }}</td>
                                            </tr>
                                            <tr>
                                                <td><i class="bi bi-people"></i> Durasi kerjasama</td>
                                                <td>:</td>
                                                <td>
                                                    @if($iduka->mulai_kerjasama && $iduka->akhir_kerjasama)
                                                        {{ \Carbon\Carbon::parse($iduka->mulai_kerjasama)->translatedFormat('d F Y') }} - 
                                                        {{ \Carbon\Carbon::parse($iduka->akhir_kerjasama)->translatedFormat('d F Y') }}
                                                    @else
                                                        Belum ditentukan
                                                    @endif
                                                </td>
                                            </tr>
                                            @else
                                            <p>Data tidak ditemukan.</p>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="card-header" style="background-color: #7e7dfb">
                            <h5 style="color: white;">DATA PIMPINAN INSTITUSI</h5>
                        </div>
                        <div class="card">
                            <div class="card-body">
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover">
                                    @if($iduka)
                                    <tr>
                                        <td><i class="bi bi-person"></i> Nama Pimpinan</td>
                                        <td>:</td>
                                        <td>{{ $iduka->nama_pimpinan }}</td>
                                    </tr>
                                    <tr>
                                        <td><i class="bi bi-person-vcard"></i> NIP/NIK Pimpinan</td>
                                        <td>:</td>
                                        <td>{{ $iduka->nip_pimpinan }}</td>
                                    </tr>
                                    <tr>
                                        <td><i class="bi bi-briefcase"></i> Jabatan</td>
                                        <td>:</td>
                                        <td>{{ $iduka->jabatan }}</td>
                                    </tr>
                                    <tr>
                                        <td><i class="bi bi-telephone"></i> No HP / Telepon</td>
                                        <td>:</td>
                                        <td>{{ $iduka->no_hp_pimpinan }}</td>
                                    </tr>
                                    @else
                                        <p>Data tidak ditemukan.</p>
                                    @endif
                                </table>
                            </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="card-header" style="background-color: #7e7dfb">
                            <h5 style="color: white;">DATA PEMBIMBING INSTITUSI</h5>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive text-nowrap">
                                    <table class="table table-hover">
                                        @if($pembimbing)
                                        <tr>
                                            <td><i class="bi bi-person"></i> Nama Pembimbing</td>
                                            <td>:</td>
                                            <td>{{ $pembimbing->name }}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="bi bi-person-vcard"></i> NIP/NIK Pembimbing</td>
                                            <td>:</td>
                                            <td>{{ $pembimbing->nip }}</td>
                                        </tr>
                                        <tr>
                                            <td><i class="bi bi-telephone"></i> No HP / Telepon</td>
                                            <td>:</td>
                                            <td>{{ $pembimbing->no_hp }}</td>
                                        </tr>
                                        @else
                                            <p>Belum ada pembimbing terdaftar.</p>
                                        @endif
                                    </table>
                                </div>
                                {{-- <div class="col-lg-12 d-flex justify-content-between mt-4">
                                <a href="{{ route('iduka.dashboard')}}" class="btn btn-back shadow-sm">
                                    Kembali
                                </a>
                            </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('iduka.data_pribadi_iduka.editPribadiIduka')
    </body>

    </html>
@endsection

