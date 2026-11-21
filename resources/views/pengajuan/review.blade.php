@extends('layout.main')
@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Review Pengajuan</title>
    <style>
        .card-hover {
            transition: transform 0.3s ease, background-color 0.3s ease, color 0.3s ease;
            height: 70px;
            flex-direction: column;
            justify-content: center;
            display: flex;
        }

        .card-hover:hover {
            transform: scale(1.03);
            background-color: #7e7dfb !important;
            color: white !important;
        }

        .card-hover:hover .btn-hover {
            background-color: white;
            color: #7e7dfb;
            border-color: white;
        }

        .btn-hover {
            background-color: #7e7dfb;
            color: white;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
            border-radius: 50px;
            border: 2px solid #7e7dfb;
        }

        .btn-hover:hover {
            background-color: white;
            color: #7e7dfb;
            border-color: white;
        }

        .dropdown-btn {
            color: #7e7dfb;
            font-size: 25px;
            border-radius: 50px;
        }

        .card-hover:hover .dropdown-btn {
            color: white !important;
        }

        .button-group {
            margin-bottom: 18px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
                <div class="row">
                    <div class="card mb-3">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="mb-2">
                                <h5 class="mb-1">Review Pengajuan Institusi / Perusahaan</h5>
                                <p class="mb-0">JUMLAH KUOTA TERSEDIA: <strong>{{ $iduka->kuota_pkl }}</strong></p>
                                <!-- <p class="mb-0">SISA KUOTA: <strong>{{ $sisa_kuota }}</strong></p> -->
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('review.pengajuanditerima') }}" class="btn btn-success btn-status btn-sm">
                                    <i class="bi bi-check-circle"></i>
                                    <span class="d-none d-md-inline">History Diterima</span>
                                </a>
                                <a href="{{ route('review.pengajuanditolak') }}" class="btn btn-danger btn-status btn-sm">
                                    <i class="bi bi-x-circle"></i>
                                    <span class="d-none d-md-inline">History Ditolak</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">

                        {{-- Jika tidak ada pengajuan --}}
                        @if($pengajuans->isEmpty())
                        <div class="alert alert-warning d-flex justify-content-center align-items-center text-center">
                            <span style="text-align: center">Belum ada siswa yang mengajukan.</span>
                        </div>
                        @else

                       {{-- Looping Data Pengajuan --}}
                        @foreach($pengajuans as $pengajuan)
                        <div class="card mb-3 shadow-sm card-hover" style="padding: 30px; border-radius: 10px;">
                            @if(session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="mb-0" style="font-size: 18px; max-width: 130px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        @if($pengajuan->dataPribadi)
                                        <strong>{{ $pengajuan->dataPribadi->user->name ?? 'Nama Tidak Tersedia' }}</strong>
                                        @else
                                        <p class="text-danger">Data pribadi tidak ditemukan untuk siswa ini.</p>
                                        @endif
                                    </div>                                                                     
                                    <div class="">
                                        Kelas: {{ $pengajuan->dataPribadi->kelas->name_kelas ?? '-' }}
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    @if($iduka->kuota_pkl > 0)
                                    {{-- Tombol Detail --}}
                                    <a href="{{ route('pengajuan.detail', $pengajuan->id) }}" class="btn btn-primary btn-sm rounded-3 me-2">
                                        Detail
                                    </a>
                                    @else
                                    {{-- Tombol Detail Nonaktif (SweetAlert) --}}
                                    <button type="button" class="btn btn-primary btn-sm rounded-3 me-2" onclick="showKuotaAlert()">
                                        Detail
                                    </button>

                                    {{-- Tombol Tolak --}}
                                    <form id="form-tolak-{{ $pengajuan->id }}" action="{{ route('pengajuan.tolak', $pengajuan->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('PATCH')
                                    </form>

                                    <button type="button" class="btn btn-danger me-2" onclick="confirmTolak('{{ $pengajuan->id }}')">Tolak</button>
                                    @endif

                                    {{-- Dropdown Menu --}}
                                    <!-- <div class="dropdown ms-2">
                                        <button class="btn dropdown-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <form action="#" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">Hapus</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('iduka.dataiduka.createiduka')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function showKuotaAlert() {
            Swal.fire({
                icon: 'info',
                title: 'Kuota Tidak Tersedia',
                text: 'Maaf, kuota PKL sudah habis. Tambah kuota PKL terlebih dahulu',
                confirmButtonColor: '#7e7dfb',
            });
        }

        function confirmTolak(id) {
        Swal.fire({
            title: 'Tolak Pengajuan?',
            text: "Pengajuan ini akan ditolak. Lanjutkan?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Ya, Tolak',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-tolak-' + id).submit();
            }
        });
    }
    
    </script>

</body>

</html>

@endsection