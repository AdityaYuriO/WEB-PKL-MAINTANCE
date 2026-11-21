@extends('layout.main')
@section('content')
<!DOCTYPE html>
<html lang="en">
{{-- table{
            width: 100%;
        }
        td:first-child {
            width: 2%; 
            min-width: 30px; 
            text-align: center;
        }
        td:nth-child(2){
            width: 150px;
        }
        td:nth-child(3){
            width: 5px;
        } --}}

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Institusi/Perusahaan Tempat PKL</title>
    <style>
        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
        }

        table {
            width: 100%;
        }

        td:first-child {
            width: 2%;
            /* Lebih kecil dari sebelumnya */
            min-width: 30px;
            /* Atur batas minimum */
            text-align: center;
        }

        td:nth-child(2) {
            width: 150px;
        }

        td:nth-child(3) {
            width: 5px;
        }

        td:nth-child(2) span {
            display: block;
            margin-left: 20px;
        }

        .isi-keterangan table {
            width: 100%;
        }

        .isi-keterangan table td:first-child {
            width: 2%;
            /* Lebih kecil dari sebelumnya */
            min-width: 30px;
            /* Atur batas minimum */
            text-align: center;
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

            td,
            th {
                padding: 8px;
            }

            h4 {
                font-size: 16px;
            }
        }

        @media (max-width: 768px) {
            .table-responsive {
                overflow-x: auto;
            }

            .table-responsive table {
                width: 100%;
                border-collapse: collapse;
                white-space: nowrap;
            }

            .table-responsive tr {
                display: flex;
                flex-direction: column;
                margin-bottom: 10px;
                padding-bottom: 5px;
            }

            .table-responsive td:first-child {
                display: none;
            }

            .table-responsive td {
                display: block;
                text-align: left;
                padding: 5px 10px;
                width: 100%;
            }

            .table-responsive td:hover {
                white-space: normal;
                overflow: visible;
            }

            .table-responsive td:nth-child(2) {
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

            .table-responsive td:nth-child(3) {
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

        .img-thumbnail {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
            transition: all 0.3s ease;
        }

        .img-thumbnail:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.16), 0 4px 8px rgba(0, 0, 0, 0.23);
            transform: scale(1.02);
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
                                <h5 class="mb-0">Data Institusi / Perusahaan Tempat PKL</h5>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editDataModal">
                                    <i class="bi bi-pencil-square"></i>
                                    <span class="d-none d-md-inline">Edit</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="card bg-primary" style="padding: 12px 30px 0 30px;">
                            <h6 class="text-white">Data Pribadi Institusi</h6>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-primary"><b>Nama Institusi / Perusahaan</b></h6>
                                <span>{{ $iduka->nama }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Card ke-2 -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-primary"><b>Alamat Institusi / Perusahaan</b></h6>
                                <span>{{ $iduka->alamat }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Card ke-3 -->
                    <div class="col-12 col-md-6 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-primary"><b>Bidang Usaha / Kerja</b></h6>
                                <span>{{ $iduka->bidang_industri }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-primary"><b>Nomor Telepon / Hp Perusahaan</b></h6>
                                <span>{{ $iduka->telepon }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="card bg-primary" style="padding: 12px 30px 0 30px;">
                            <h6 class="text-white">Yang menandatangi sertifikat PKL</h6>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="card" style="padding: 12px 30px 0 30px;">
                            <h6 class=""><b>A. Kepala / Pimpinan Institusi</b></h6>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-primary"><b>Nama Lengkap</b></h6>
                                <span>{{ $iduka->nama_pimpinan }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-primary"><b>Nomor Induk Pegawai / Nomor Induk Karyawan</b></h6>
                                <span>{{ $iduka->nip_pimpinan }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-primary"><b>Jabatan</b></h6>
                                <span>{{ $iduka->jabatan }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-primary"><b>No Hp / Telepon</b></h6>
                                <span>{{ $iduka->no_hp_pimpinan ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="card" style="padding: 12px 30px 0 30px;">
                            <h6 class=""><b>B. Pembimbing Institusi / Perusahaan</b></h6>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-primary"><b>Nama Lengkap</b></h6>
                                <span>{{ $pembimbing->name ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-primary"><b>Nomor Induk Pegawai / Nomor Induk Karyawan</b></h6>
                                <span>{{ $pembimbing->nip ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-primary"><b>Nomor Hp / Telepon</b></h6>
                                <span>{{ $pembimbing->no_hp ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                                <div>
                                    <h6 class="text-primary mb-2"><b>Apakah institusi / perusahaan akan menerbitkan surat keterangan atau sertifikat dicetak oleh perusahaan atau dibantu pihak sekolah?</b></h6>
                                    <span>{{ $iduka->kolom6 == 'Ya' ? 'Cetak oleh perusahaan' : 'Dibantu pihak sekolah' }}</span>
                                </div>
                                {{-- <div class="mt-3 mt-md-0">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="kolom6" value="Ya" {{ $iduka->kolom6 == 'Ya' ? 'checked' : '' }} disabled>
                                        <label class="form-check-label">Cetak oleh perusahaan</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="kolom6" value="Tidak" {{ $iduka->kolom6 != 'Ya' ? 'checked' : '' }} disabled>
                                        <label class="form-check-label">Dibantu pihak sekolah</label>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="text-primary"><b>Apakah di institusi / perusahaan ada SOP / Aturan Kerja / Tata Tertib?</b></h6>
                                <span>{{ $iduka->kolom7 ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="text-primary"><b>Apakah institusi / perusahaan menerapkan K3LH (kesehatan, keselamatan kerja, dan lingkungan hidup)?</b></h6>
                                <span>{{ $iduka->kolom8 ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h6 class="text-primary"><b>Logo Perusahaan (Opsional)</b></h6>
                                @if($iduka->foto)
                                    <img src="{{ asset('storage/' . $iduka->foto) }}" alt="Foto Institusi" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $iduka->foto) }}" target="_blank" class="btn btn-sm btn-info">Lihat Full Size</a>
                                    </div>
                                @else
                                    <span class="text-muted">Tidak ada foto</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
                                   
                    {{-- <div class="card mb-3">
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
                                <table class="table table-hover mb-3">
                                    <tr>
                                        <td>1.</td>
                                        <td>Nama Institusi / DUDI</td>
                                        <td>:</td>
                                        <td colspan="2">{{ $iduka->nama }}</td>




                                    </tr>
                                    <tr>
                                        <td>2.</td>
                                        <td>Alamat Institusi / DUDI</td>
                                        <td colspan="2">{{ $iduka->alamat }}</td>
                                    </tr>
                                    <tr>
                                        <td rowspan="3"></td>
                                        <td rowspan="3"></td>
                                        <td colspan="2">Kec.
                                            <br>Kab/Kota*)
                                            <br>Prov.
                                        </td>
                                    </tr>
                                    <tr>

                                    </tr>
                                    <tr>

                                    </tr>
                                    <tr>
                                        <td>3.</td>
                                        <td>Bidang Usaja / Kerja</td>
                                        <td colspan="2">{{ $iduka->bidang_industri }}</td>
                                    </tr>
                                    <tr>
                                        <td>4.</td>
                                        <td>Nomor Telepon / HP Perusahaan</td>
                                        <td colspan="2">{{ $iduka->telepon }}</td>
                                    </tr>
                                    <tr>
                                        <td>5.</td>
                                        <td colspan="3">Yang akan menandatangani sertifikat PKL</td>
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <td colspan="3"><b>A. Kepala / Pimpinan Institusi / Perusahaan</b></td>

                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><span>a) Nama Lengkap</span></td>
                                        <td colspan="2">{{ $iduka->nama_pimpinan }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><span>b) Nomor Induk Pegawai / Nomor Induk Karyawan</span></td>
                                        <td colspan="2">{{ $iduka->nip_pimpinan }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><span>c) Jabatan</span></td>
                                        <td colspan="2">{{ $iduka->jabatan }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><span>d) No Hp / Telepon</span></td>
                                        <td colspan="2">{{ $iduka->no_hp_pimpinan }}</td>
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <td colspan="3"><b>B. Pembimbing Institusi / Perusahaan</b></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><span>a) Nama Lengkap</span></td>
                                        <td colspan="2">{{ $pembimbing->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><span>b) Nomor Induk Pegawai / Nomor Induk Karyawan</span></td>
                                        <td colspan="2">{{ $pembimbing->nip ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td><span>d) No Hp / Telepon</span></td>
                                        <td colspan="2">{{ $pembimbing->no_hp ?? '-' }}</td>
                                    </tr>


                                    <tr>
                                        <td>6.</td>
                                        <td>Apakah institusi / perusahaan akan menerbitkan surat keterangan atau sertifikat dicetak oleh perusahaan atau dibantu pihak sekolah?</td>
                                        <td>{{ $iduka->kolom6 == 'Ya' ? 'Cetak oleh perusahaan' : 'Dibantu pihak sekolah' }}</td>
                                    </tr>
                                    <tr>
                                        <td>7.</td>
                                        <td>Apakah di institusi / perusahaan ada SOP / Aturan Kerja / Tata Tertib?</td>
                                        <td>{{ $iduka->kolom7 }}</td>
                                    </tr>
                                    <tr>
                                        <td>8.</td>
                                        <td>Apakah institusi / perusahaan menerapkan K3LH (kesehatan, keselamatan kerja, dan lingkungan hidup)?</td>
                                        <td>{{ $iduka->kolom8 }}</td>
                                    </tr>

                                    <tr>
                                        <td>9.</td>
                                        <td>Logo Perusahaan( Opsional )</td>
                                        <td>
                                            @if($iduka->foto)
                                            <img src="{{ asset('storage/' . $iduka->foto) }}"
                                                alt="Foto Institusi"
                                                class="img-thumbnail"
                                                style="max-width: 200px; max-height: 200px;">
                                            <div class="mt-2">
                                                <a href="{{ asset('storage/' . $iduka->foto) }}"
                                                    target="_blank"
                                                    class="btn btn-sm btn-info">
                                                    Lihat Full Size
                                                </a>
                                            </div>
                                            @else
                                            <span class="text-muted">Tidak ada foto</span>
                                            @endif
                                        </td>
                                    </tr>

                                </table>

                            </div>
                        </div>
                    </div> --}}

                    <div class="card">
                        <div class="card-body">
                            <div class="keterangan">
                                <span>Keterangan :</span>
                                <div class="isi-keterangan">
                                    <div class="table-responsive text-nowrap">
                                        <table class="table table-hover">
                                            <tr>
                                                <td>1.</td>
                                                <td>Seluruh data institusi / perusahaan tempat PKL diinput melalui link
                                                    <a
                                                        href="/https://bit.ly/DataDUDIKA2024">https://bit.ly/DataDUDIKA2024</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2.</td>
                                                <td>Jika cetak sertifikat atau surat keterangan PKL akan dibantu oleh
                                                    pihak sekolah, mohon untuk dapat mengirimkan Logo dan Kop surat
                                                    perusahaan untuk kami cantumkan di berkas Sertifikat.
                                                    <br> <i class="bi bi-arrow-right-short"></i><i>Jika logo dalam
                                                        bentuk file, bisa di transfer via whatsapp, email, atau
                                                        flashdisc murid / guru yang menerima berkas ini.</i>
                                                    <br> <i class="bi bi-arrow-right-short"></i><i>Jika terdapat di web,
                                                        mohon diisikan alamat web nya.</i>
                                                    <br style="margin-left: 20px; display: block;"><span><i>Alamat web:
                                                            ....</i></span>
                                                    <i class="bi bi-arrow-right-short"></i><i>Jika logo terdapat di
                                                        brosur, pamflet atau bon, mohon ijin untuk kami bawa atau di
                                                        kami foto sebagai contoh untuk kami desain ulang.</i>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    {{-- edit data institusi --}}
    <div class="modal fade" id="editDataModal" tabindex="-1" aria-labelledby="editDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editDataModalLabel">Form Edit Data Institusi / Perusahaan Tempat PKL</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('iduka.updateInstitusi', $iduka->id) }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        {{-- Tampilkan pesan error validasi --}}
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Institusi / DUDI <i>(Dengan huruf kapital)</i></label>
                            <input type="text" class="form-control" name="nama" value="{{ $iduka->nama ?? '' }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label">Alamat Institusi / DUDI <i>(Dengan huruf kapital)</i></label>
                            <input type="text" class="form-control" name="alamat" value="{{ $iduka->alamat ?? '' }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label">Bidang Usaha / Kerja</label>
                            <input type="text" class="form-control" name="bidang_industri" value="{{ $iduka->bidang_industri ?? '' }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label">Nomor Telepon / HP Perusahaan</label>
                            <input type="text" class="form-control" name="telepon" value="{{ $iduka->telepon ?? '' }}" required>
                        </div>

                        <h5>A. Kepala / Pimpinan Institusi Perusahaan</h5>

                        <div class="mb-3">
                            <label for="" class="form-label">Nama Lengkap <i>(Dengan huruf kapital)</i></label>
                            <input type="text" class="form-control" name="nama_pimpinan" value="{{ $iduka->nama_pimpinan ?? '' }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label">Nomor Induk Pegawai / Nomor Induk Karyawan</label>
                            <input type="text" class="form-control" name="nip_pimpinan" value="{{ $iduka->nip_pimpinan ?? '' }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label">Jabatan <i>(Dengan huruf kapital)</i></label>
                            <input type="text" class="form-control" name="jabatan" value="{{ $iduka->jabatan ?? '' }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label">No HP</label>
                            <input type="text" class="form-control" name="no_hp_pimpinan" value="{{ $iduka->no_hp_pimpinan ?? '' }}" required>
                        </div>

                        <h5>B. Pembimbing Institusi / Perusahaan</h5>

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap <i>(Dengan huruf kapital)</i></label>
                            <input type="text" class="form-control" value="{{ $pembimbing->name ?? '-' }}" readonly tabindex="-1">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor Induk Pegawai / Nomor Induk Karyawan</label>
                            <input type="text" class="form-control" value="{{ $pembimbing->nip ?? '-' }}" readonly tabindex="-1">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No HP</label>
                            <input type="text" class="form-control" value="{{ $pembimbing->no_hp ?? '-' }}" readonly tabindex="-1">
                        </div>
                        <!-- kolom6 -->
                        <div class="mb-3 d-flex align-items-center border-bottom pb-3">
                            <div class="col-md-6">
                                <p class="mb-0">
                                    Apakah institusi / perusahaan akan menerbitkan surat keterangan atau sertifikat dicetak oleh perusahaan atau dibantu pihak sekolah?
                                </p>
                            </div>
                            <div class="col-md-6 d-flex flex-column ms-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kolom6" value="Ya" id="kolom6-ya" {{ $iduka->kolom6 === 'Ya' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="kolom6-ya">Cetak oleh perusahaan</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kolom6" value="Tidak" id="kolom6-tidak" {{ $iduka->kolom6 === 'Tidak' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="kolom6-tidak">Dibantu pihak sekolah</label>
                                </div>
                            </div>
                        </div>

                        <!-- kolom7 -->
                        <div class="mb-3 d-flex align-items-center border-bottom pb-3">
                            <div class="col-md-6">
                                <p class="mb-0">
                                    Apakah di institusi / perusahaan ada SOP (Standar Operasional Prosedur) / Aturan Kerja / Tata Tertib?
                                </p>
                            </div>
                            <div class="col-md-6 d-flex flex-column ms-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kolom7" value="Ya" id="kolom7-ya" {{ $iduka->kolom7 === 'Ya' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="kolom7-ya">Ya</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kolom7" value="Tidak" id="kolom7-tidak" {{ $iduka->kolom7 === 'Tidak' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="kolom7-tidak">Tidak</label>
                                </div>
                            </div>
                        </div>

                        <!-- kolom8 -->
                        <div class="mb-3 d-flex align-items-center border-bottom pb-3">
                            <div class="col-md-6">
                                <p class="mb-0">
                                    Apakah di institusi / perusahaan menerapkan K3LH (kesehatan, keselamatan kerja, dan lingkungan hidup)?
                                </p>
                            </div>
                            <div class="col-md-6 d-flex flex-column ms-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kolom8" value="Ya" id="kolom8-ya" {{ $iduka->kolom8 === 'Ya' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="kolom8-ya">Ya</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kolom8" value="Tidak" id="kolom8-tidak" {{ $iduka->kolom8 === 'Tidak' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="kolom8-tidak">Tidak</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="foto">Logo Perusahaan ( Opsional )</label><br>

                            @if ($iduka->foto)
                            <img src="{{ asset('storage/' . $iduka->foto) }}" width="100" alt="Foto Profil"><br>
                            @else
                            <p class="text-muted">Belum ada foto</p>
                            @endif

                            <input type="file" name="foto" class="form-control" accept="image/*">
                        </div>


                        {{-- Ulangi struktur yang sama untuk kolom7 dan kolom8 --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if (session()->has('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('
            success ') }}',
            showConfirmButton: false,
            timer: 1500,
            willClose: () => {
                // Bisa ditambahkan kode lain jika ingin melakukan aksi setelah animasi selesai
            }
        });
    </script>
    @endif
</body>

</html>
@endsection
