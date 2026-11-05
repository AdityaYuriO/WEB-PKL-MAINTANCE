@extends('layout.main')
@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Pengajuan PKL</title>
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

        .btn-action {
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .btn-accept {
            background-color: #28a745;
            color: white;
            border: none;
        }

        .btn-accept:hover {
            background-color: #28a745;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.25);
        }

        .btn-reject {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .btn-reject:hover {
            background-color: #dc3545;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.25);
        }

        .btn-back {
            padding: 5px 10px;
            /* Sesuaikan padding untuk ukuran lebih kecil */
            font-size: 14px;
            /* Sesuaikan ukuran font untuk tombol lebih kecil */
            background-color: #7e7dfb;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            display: flex;
            align-items: center;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-back:hover {
            background-color: #7e7dfb;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.25);
        }

        .btn-back i {
            margin-right: 8px;
        }

        .button-group {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
                <div class="row">
                    <div class="card-header bg-primary text-white">
                        <h5 class="text-white">Detail Pengajuan PKL</h5>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5>Data Siswa</h5>
                            <div class="table-responsive text-nowrap">
                                <table class="table table-striped">
                                    <tr>
                                        <td>Nama Siswa</td>
                                        <td>:</td>
                                        <td>{{ $pengajuan->dataPribadi->name ?? 'Tidak ada data' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Kelas</td>
                                        <td>:</td>
                                        <td> {{ $pengajuan->dataPribadi->kelas->name_kelas ?? 'Tidak ada data' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Jurusan</td>
                                        <td>:</td>
                                        <td>{{ $pengajuan->dataPribadi->konkes->name_konke ?? 'Tidak ada data' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Status Pengajuan</td>
                                        <td>:</td>
                                        <td>{{ ucfirst($pengajuan->status) }}</td>
                                    </tr>
                                </table>
                            </div>

                            <h5 class="mt-4">Data Institusi / Perusahaan</h5>
                            <div class="table-responsive text-nowrap">
                                <table class="table table-striped">
                                    <tr>
                                        <td>Nama Institusi</td>
                                        <td>:</td>
                                        <td>{{ $pengajuan->iduka->nama ?? 'Tidak ada data' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Alamat Lengkap</td>
                                        <td>:</td>
                                        <td>{{ $pengajuan->iduka->alamat ?? 'Tidak ada data' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Bidang Industri</td>
                                        <td>:</td>
                                        <td>{{ $pengajuan->iduka->bidang_industri ?? 'Tidak ada data' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nomor Telpon</td>
                                        <td>:</td>
                                        <td>{{ $pengajuan->iduka->telepon ?? 'Tidak ada data' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>:</td>
                                        <td>{{ $pengajuan->iduka->email ?? 'Tidak ada data' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Jumlah Kuota PKL</td>
                                        <td>:</td>
                                        <td>{{ $pengajuan->iduka->kuota_pkl ?? 'Tidak ada data' }}</td>
                                    </tr>
                                </table>
                            </div>

                            <h5 class="mt-4">Data Pimpinan</h5>
                           <div class="table-responsive text-nowrap">
                            <table class="table table-striped">
                                <tr>
                                    <td>Nama Pimpinan</td>
                                    <td>:</td>
                                    <td>{{ $pengajuan->iduka->nama_pimpinan ?? 'Tidak ada data' }}</td>
                                </tr>
                                <tr>
                                    <td>NIP/NIK Pimpinan</td>
                                    <td>:</td>
                                    <td>{{ $pengajuan->iduka->nip_pimpinan ?? 'Tidak ada data' }}</td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td>:</td>
                                    <td>{{ $pengajuan->iduka->jabatan ?? 'Tidak ada data' }}</td>
                                </tr>
                                <tr>
                                    <td>Nomor Telpon Pimpinan</td>
                                    <td>:</td>
                                    <td>{{ $pengajuan->iduka->no_hp_pimpinan ?? 'Tidak ada data' }}</td>
                                </tr>
                            </table>
                           </div>

                            <h5 class="mt-4">Data Pembimbing</h5>
                            <div class="table-responsive text-nowrap">
                                <table class="table table-striped">
                                    <tr>
                                        <td>Nama Pembimbing</td>
                                        <td>:</td>
                                        <td>{{ $pengajuan->iduka->user->pembimbingpkl->name ?? 'Tidak ada data' }}</td>
                                    </tr>
                                    <tr>
                                        <td>NIP/NIK Pembimbing</td>
                                        <td>:</td>
                                        <td>{{ $pengajuan->iduka->user->pembimbingpkl->nip ?? 'Tidak ada data' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nomor Telpon Pembimbing</td>
                                        <td>:</td>
                                        <td>{{ $pengajuan->iduka->user->pembimbingpkl->no_hp ?? 'Tidak ada data' }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between mt-3 mb-2">
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                                <a href="{{ route('persuratan.download', $pengajuan->id) }}" class="btn btn-danger">
                                    <i class="bi bi-filetype-pdf"></i><span class="d-none d-sm-inline">Download PDF
                                </a>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

@endsection
