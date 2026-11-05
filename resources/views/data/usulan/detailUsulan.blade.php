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
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover">
                                <tr>
                                    <td>Nama Siswa</td>
                                    <td>:</td>
                                    <td>{{ $dataPribadi->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>NIS</td>
                                    <td>:</td>
                                    <td>{{ $dataPribadi->nip ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Konsentrasi Keahlian</td>
                                    <td>:</td>
                                    <td>{{ $dataPribadi->konkes->name_konke ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Kelas</td>
                                    <td>:</td>
                                    <td>{{ $dataPribadi->kelas->name_kelas ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Alamat Siswa</td>
                                    <td>:</td>
                                    <td>{{ $dataPribadi->alamat_siswa ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>No HP</td>
                                    <td>:</td>
                                    <td>{{ $dataPribadi->no_hp ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Jenis Kelamin</td>
                                    <td>:</td>
                                    <td>{{ $dataPribadi->jk ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Agama</td>
                                    <td>:</td>
                                    <td>{{ $dataPribadi->agama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Tempat, Tanggal Lahir</td>
                                    <td>:</td>
                                    <td>{{ $dataPribadi->tempat_lhr ?? '-' }}, {{ $dataPribadi->tgl_lahir ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>:</td>
                                    <td>{{ $dataPribadi->email ?? '-' }}</td>
                                </tr>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="card-header" style="background-color: #7e7dfb">
                        <h5 style="color: white;">Detail Data Institusi / Perusahaan Baru</h5>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            @if ($usulanIduka)
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover">
                                <tr>
                                    <td>Nama Institusi / Perusahaan</td>
                                    <td>:</td>
                                    <td>{{ $usulanIduka->nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Nama Pimpinan</td>
                                    <td>:</td>
                                    <td>{{ $usulanIduka->nama_pimpinan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>NIP/NIP Pimpinan</td>
                                    <td>:</td>
                                    <td>{{ $usulanIduka->nip_pimpinan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td>:</td>
                                    <td>{{ $usulanIduka->jabatan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Alamat Lengkap Institusi / Perusahaan</td>
                                    <td>:</td>
                                    <td>{{ $usulanIduka->alamat ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Kode Pos</td>
                                    <td>:</td>
                                    <td>{{ $usulanIduka->kode_pos ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>No Telepon Kantor</td>
                                    <td>:</td>
                                    <td>{{ $usulanIduka->telepon ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>:</td>
                                    <td>{{ $usulanIduka->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Bidang Industri</td>
                                    <td>:</td>
                                    <td>{{ $usulanIduka->bidang_industri ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Kerja Sama</td>
                                    <td>:</td>
                                    <td>{{ $usulanIduka->kerjasama ?? '-' }}</td>
                                </tr>
                            </table>
                            </div>
                            @elseif ($pengajuanUsulan && $pengajuanUsulan->iduka)
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover">
                                <tr>
                                    <td>Nama IDUKA</td>
                                    <td>:</td>
                                    <td>{{ $pengajuanUsulan->iduka->nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Nama Pimpinan</td>
                                    <td>:</td>
                                    <td>{{ $pengajuanUsulan->iduka->nama_pimpinan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td>:</td>
                                    <td>{{ $pengajuanUsulan->iduka->jabatan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Alamat Lengkap IDUKA</td>
                                    <td>:</td>
                                    <td>{{ $pengajuanUsulan->iduka->alamat ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Kode Pos</td>
                                    <td>:</td>
                                    <td>{{ $pengajuanUsulan->iduka->kode_pos ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>No Telepon Kantor</td>
                                    <td>:</td>
                                    <td>{{ $pengajuanUsulan->iduka->telepon ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>:</td>
                                    <td>{{ $pengajuanUsulan->iduka->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Bidang Industri</td>
                                    <td>:</td>
                                    <td>{{ $pengajuanUsulan->iduka->bidang_industri ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Kerja Sama</td>
                                    <td>:</td>
                                    <td>{{ $pengajuanUsulan->iduka->kerjasama ?? '-' }}</td>
                                </tr>
                            </table>
                            </div>
                            @else
                            <p>Belum ada data IDUKA yang diusulkan atau diajukan.</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="mt-3 d-flex justify-content-end mb-4">
                    <a href="{{ route('siswa.dashboard') }}" class="btn btn-primary me-2">
                        Kembali
                    </a>

                   

                    <!-- <a href="{{ route('siswa.usulan.pdf') }}" class="btn btn-danger">
                        <i class="bi bi-filetype-pdf"></i>
                    </a> -->

                </div>
            </div>
        </div>
    </div>
</body>

</html>
@endsection
