@extends('layout.main')
@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Review Pengajuan PKL</title>
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
                    <div class="card-header" style="background-color: #7e7dfb">
                        <h5 style="color: white;">Review Pengajuan PKL</h5>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5>Data Siswa</h5>
                            <table class="table table-striped">
                                <tr>
                                    <td>Nama Siswa</td>
                                    <td>:</td>
                                    <td>{{ $usulan->user->name }}</td>
                                </tr>
                                <tr>
                                    <td>NIS</td>
                                    <td>:</td>
                                    <td>{{ $usulan->user->dataPribadi->nip }}</td>
                                </tr>
                                <tr>
                                    <td>Kelas</td>
                                    <td>:</td>
                                    <td>{{ $usulan->user->dataPribadi->kelas->name_kelas ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Konsentrasi Keahlian</td>
                                    <td>:</td>
                                    <td>{{ $usulan->user->dataPribadi->konkes->name_konke ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Jenis Kelamin</td>
                                    <td>:</td>
                                    <td>{{ $usulan->user->dataPribadi->jk }}</td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>:</td>
                                    <td>{{ $usulan->user->dataPribadi->alamat_siswa }}</td>
                                </tr>
                                <tr>
                                    <td>Tempat Lahir</td>
                                    <td>:</td>
                                    <td>{{ $usulan->user->dataPribadi->tempat_lhr }}</td>
                                </tr>
                                <tr>
                                    <td>Tanggal Lahir</td>
                                    <td>:</td>
                                    <td>{{ date('d F Y', strtotime($usulan->user->dataPribadi->tgl_lahir)) }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>:</td>
                                    <td>{{ $usulan->user->email }}</td>
                                </tr>
                                <tr>
                                    <td>Nomor Telepon</td>
                                    <td>:</td>
                                    <td>{{ $usulan->user->dataPribadi->no_hp }}</td>
                                </tr>
                            </table>

                            <h5 class="mt-4">Data Institusi / Perusahaan yang Diajukan</h5>
                            <table class="table table-striped">
                            <tr>
                                    <td>Nama INSTITUSI</td>
                                    <td>:</td>
                                    <td>{{ $usulan->iduka->nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Nama Pimpinan</td>
                                    <td>:</td>
                                    <td>{{ $usulan->iduka->nama_pimpinan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>NIP/NIP Pimpinan</td>
                                    <td>:</td>
                                    <td>{{ $usulan->iduka->nip_pimpinan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Jabatan</td>
                                    <td>:</td>
                                    <td>{{ $usulan->iduka->jabatan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Alamat Lengkap INSTITUSI</td>
                                    <td>:</td>
                                    <td>{{ $usulan->iduka->alamat ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Kode Pos</td>
                                    <td>:</td>
                                    <td>{{ $usulan->iduka->kode_pos ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>No Telepon Kantor/Perusahaan</td>
                                    <td>:</td>
                                    <td>{{ $usulan->iduka->telepon ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>:</td>
                                    <td>{{ $usulan->iduka->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Bidang Industri</td>
                                    <td>:</td>
                                    <td>{{ $usulan->iduka->bidang_industri ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Kerja Sama</td>
                                    <td>:</td>
                                    <td>{{ $usulan->iduka->kerjasama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Kuota PKL</td>
                                    <td>:</td>
                                    <td>{{ $usulan->iduka->kuota_pkl ?? '-' }}</td>
                                </tr>
                            </table>

                            <div class="d-flex justify-content-between mt-3 mb-2">
                                <div class="d-flex justify-content-start">
                                    <a href="{{ route('review.usulan')}}" class="btn btn-back shadow-sm">
                                        <i class="bi bi-arrow-left"></i>
                                    </a>
                                </div>

                                <div class="button-group">
                                <form action="{{ route('usulan.diterimaUsulan', $usulan->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" name="status" value="diterima"  class="btn btn-action btn-accept">Terima</button>
                                </form>

                                <form action="{{ route('usulan.diterimaUsulan', $usulan->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" name="status" value="ditolak"  class="btn btn-action btn-reject">Tolak</button>
                                </form>
                                </div>
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