@extends('layout.main')
@section('content')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Usulan Institusi / Perusahaan Baru</title>
    <style>
        .table td {
            vertical-align: middle;
            border: none;
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
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
                <div class="row">
                    <div class="card-header" style="background-color: #7e7dfb">
                        <h5 style="color: white;">Detail Data Siswa</h5>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-hover">
                                <tr>
                                    <td>Nama Siswa</td>
                                    <td>:</td>
                                    <td>malvaa</td>
                                </tr>
                                <tr>
                                    <td>NIS</td>
                                    <td>:</td>
                                    <td>222310275</td>
                                </tr>
                                <tr>
                                    <td>Konsentrasi Keahlian</td>
                                    <td>:</td>
                                    <td>RPL</td>
                                </tr>
                                <tr>
                                    <td>Kelas</td>
                                    <td>:</td>
                                    <td>1111</td>
                                </tr>
                                <tr>
                                    <td>Alamat Siswa</td>
                                    <td>:</td>
                                    <td>malvaa</td>
                                </tr>
                                <tr>
                                    <td>No HP</td>
                                    <td>:</td>
                                    <td>malvaa</td>
                                </tr>
                                <tr>
                                    <td>Jenis Kelamin</td>
                                    <td>:</td>
                                    <td>malvaa</td>
                                </tr>
                                <tr>
                                    <td>Agama</td>
                                    <td>:</td>
                                    <td>malvaa</td>
                                </tr>
                                <tr>
                                    <td>Tempat, Tanggal Lahir</td>
                                    <td>:</td>
                                    <td>Kawali, 25 Februari 2025</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>:</td>
                                    <td>malvaa</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="card-header" style="background-color: #7e7dfb">
                        <h5 style="color: white;">Detail Data Institusi / Perusahaan Baru</h5>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-hover">
                                <tr>
                                    <td>Nama Institusi / Perusahaan</td>
                                    <td>:</td>
                                    <td>malvaa</td>
                                </tr>
                                <tr>
                                    <td>Nama Pimpinan</td>
                                    <td>:</td>
                                    <td>222310275</td>
                                </tr>
                                <tr>
                                    <td>NIP/NIP Pimpinan</td>
                                    <td>:</td>
                                    <td>RPL</td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td>:</td>
                                    <td>1111</td>
                                </tr>
                                <tr>
                                    <td>Alamat Lengkap Institusi / Perusahaan</td>
                                    <td>:</td>
                                    <td>malvaa</td>
                                </tr>
                                <tr>
                                    <td>Kode Pos</td>
                                    <td>:</td>
                                    <td>malvaa</td>
                                </tr>
                                <tr>
                                    <td>No Telepon Kantor/Perusahaan</td>
                                    <td>:</td>
                                    <td>malvaa</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>:</td>
                                    <td>malvaa</td>
                                </tr>
                                <tr>
                                    <td>Bidang Industri</td>
                                    <td>:</td>
                                    <td>malvaa</td>
                                </tr>
                                <tr>
                                    <td>Kerja Sama</td>
                                    <td>:</td>
                                    <td>malvaa</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="mt-3 d-flex justify-content-end mb-4">
                    <a href="{{ route('siswa.dashboard') }}" class="btn btn-primary me-2">
                        Kembali
                    </a>
                    <a href="{{ route('siswa.usulan.pdf') }}" class="btn btn-danger">
                        Export PDF<i class="bi bi-filetype-pdf"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
@endsection