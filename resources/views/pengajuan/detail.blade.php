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
                        <h5 style="color: white;">Detail Data Pengajuan PKL</h5>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover">
                                    <tr>
                                        <td>Nama Siswa</td>
                                        <td>:</td>
                                        <td>{{ $pengajuan->dataPribadi->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>NIS</td>
                                        <td>:</td>
                                        <td>{{ $pengajuan->dataPribadi->nip ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Kelas</td>
                                        <td>:</td>
                                        <td>{{ $pengajuan->dataPribadi->kelas->name_kelas ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>No HP</td>
                                        <td>:</td>
                                        <td>{{ $pengajuan->dataPribadi->no_hp ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>:</td>
                                        <td>{{ $pengajuan->dataPribadi->email ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Status Pengajuan</td>
                                        <td>:</td>
                                        <td>
                                            @if($pengajuan->status == 'proses')
                                            <span class="badge bg-warning">Menunggu Verifikasi</span>
                                            @elseif($pengajuan->status == 'diterima')
                                            <span class="badge bg-success">Diterima</span>
                                            @else
                                            <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="card-header" style="background-color: #7e7dfb">
                        <h5 style="color: white;">Detail Institusi / Perusahaan</h5>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover">
                                    <tr>
                                        <td>Nama Institusi</td>
                                        <td>:</td>
                                        <td>{{ $pengajuan->iduka->nama ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nama Pimpinan</td>
                                        <td>:</td>
                                        <td>{{ $pengajuan->iduka->nama_pimpinan ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>NIP Pimpinan</td>
                                        <td>:</td>
                                        <td>{{ $pengajuan->iduka->nip_pimpinan ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Jabatan Pimpinan</td>
                                        <td>:</td>
                                        <td>{{ $pengajuan->iduka->jabatan ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Alamat Institusi</td>
                                        <td>:</td>
                                        <td>{{ $pengajuan->iduka->alamat ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Kode POS</td>
                                        <td>:</td>
                                        <td>{{ $pengajuan->iduka->kode_pos ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>No Telepon Institusi</td>
                                        <td>:</td>
                                        <td>{{ $pengajuan->iduka->telepon ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>:</td>
                                        <td>{{ $pengajuan->iduka->email ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Bidang Industri</td>
                                        <td>:</td>
                                        <td>{{ $pengajuan->iduka->bidang_industri ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Kerjasama</td>
                                        <td>:</td>
                                        <td>{{ $pengajuan->iduka->kerjasama ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="mt-3 d-flex justify-content-end mb-4">
                            <a href="{{ route('pengajuan.review') }}" class="btn btn-primary me-2">
                                Kembali
                            </a>
                            @if(Auth::user()->role == 'iduka' && $pengajuan->status == 'proses')
                            <form id="terima-form" action="{{ route('pengajuan.terima', $pengajuan->id) }}" method="POST" class="me-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success">Terima</button>
                            </form>
        
                            <form id="tolak-form" action="{{ route('pengajuan.tolak', $pengajuan->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-danger">Tolak</button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Fungsi untuk handle tombol "Terima"
    document.getElementById('terima-form').addEventListener('submit', function (e) {
        e.preventDefault(); // Mencegah form submit secara langsung
        Swal.fire({
            title: 'Apakah kamu yakin?',
            text: "Kamu akan menerima pengajuan ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Terima',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit(); // Kirim form jika pengguna klik "Terima"
            }
        });
    });

    // Fungsi untuk handle tombol "Tolak"
    document.getElementById('tolak-form').addEventListener('submit', function (e) {
        e.preventDefault(); // Mencegah form submit secara langsung
        Swal.fire({
            title: 'Apakah kamu yakin?',
            text: "Kamu akan menolak pengajuan ini!",
            icon: 'error',
            showCancelButton: true,
            confirmButtonText: 'Tolak',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit(); // Kirim form jika pengguna klik "Tolak"
            }
        });
    });
</script>
@endpush

