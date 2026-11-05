@extends('layout.main')
@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Data Institusi / Perusahaan</title>
    <style>
        .card-hover {
            transition: transform 0.3s ease, background-color 0.3s ease, color 0.3s ease;
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
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
            border-radius: 50px;
            padding: 5px 12px;
            font-size: 25px;
        }

        .card-hover:hover .dropdown-btn {
            color: white !important;
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
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0">Cetak TP ATP Iduka</h5>

                                <div class="d-none d-md-flex gap-2 align-items-center">
                                    <form action="{{ route('cetak.iduka.index') }}" method="GET" id="filterForm" class="d-flex gap-2">
                                        <input type="hidden" name="search" id="searchValueDesktop" value="{{ request('search') }}">

                                        <select class="form-select form-select-sm w-auto" name="filter" id="filterIduka" onchange="document.getElementById('filterForm').submit()">
                                            <option value="all" {{ request('filter') == 'all' || !request('filter') ? 'selected' : '' }}>Semua</option>
                                            <option value="rekomendasi" {{ request('filter') == 'rekomendasi' ? 'selected' : '' }}>Rekomendasi</option>
                                            <option value="ajuan" {{ request('filter') == 'ajuan' ? 'selected' : '' }}>Ajuan</option>
                                        </select>

                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#searchModal">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </form>

                                    @if(in_array(auth()->user()->role, ['hubin', 'kaprog']))
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahIdukaModal">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                    @endif
                                </div>
                                <div class="d-flex d-md-none justify-content-end">
                                    <div class="dropdown">
                                        <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                           <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('siswa.dashboard') }}" class="dropdown-item text-primary">
                                                    <i class="bi bi-arrow-left-circle me-2"></i> Kembali
                                                </a>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item text-warning" data-bs-toggle="modal" data-bs-target="#searchModal">
                                                    <i class="bi bi-search me-2"></i> Cari
                                                </button>
                                            </li>

                                            @if(in_array(auth()->user()->role, ['hubin', 'kaprog']))
                                            <li>
                                                <button type="button" class="dropdown-item text-primary" data-bs-toggle="modal" data-bs-target="#tambahIdukaModal">
                                                    <i class="bi bi-plus-lg me-2"></i> Tambah INSTITUSI
                                                </button>
                                            </li>
                                            @endif

                                            <li>
                                                <div class="px-3 pt-2">
                                                    <form action="{{ route('cetak.iduka.index') }}" method="GET" id="filterFormMobile">
                                                        <input type="hidden" name="search" id="searchValueMobile" value="{{ request('search') }}">
                                                        <select class="form-select form-select-sm" name="filter" id="filterIdukaMobile" onchange="document.getElementById('filterFormMobile').submit()">
                                                            <option value="all" {{ request('filter') == 'all' || !request('filter') ? 'selected' : '' }}>Semua</option>
                                                            <option value="rekomendasi" {{ request('filter') == 'rekomendasi' ? 'selected' : '' }}>Rekomendasi</option>
                                                            <option value="ajuan" {{ request('filter') == 'ajuan' ? 'selected' : '' }}>Ajuan</option>
                                                        </select>
                                                    </form>
                                                </div>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Tampilkan info pencarian jika ada -->
                            @if(request('search'))
                            <div class="alert alert-info d-flex justify-content-between align-items-center">
                                <span>Hasil pencarian untuk: <strong>"{{ request('search') }}"</strong></span>
                                <a href="{{ route('cetak.iduka.index') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i>Hapus Pencarian
                                </a>
                            </div>
                            @endif
                        </div>

                        <!-- Modal Search -->
                        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="searchModalLabel">Cari Institusi / Perusahaan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('cetak.iduka.index') }}" method="GET">
                                        <input type="hidden" name="filter" value="{{ request('filter', 'all') }}">
                                        <div class="modal-body">
                                            <input type="text" name="search" class="form-control" placeholder="Cari Institusi / Perusahaan..." value="{{ request('search') }}">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3" id="idukaContainer">
                        @if ($iduka->isEmpty())
                            <div class="alert alert-warning">
                                @if(request('search'))
                                    Tidak ada hasil untuk pencarian "{{ request('search') }}".
                                @else
                                    Belum ada data institusi / perusahaan yang tersedia.
                                @endif
                            </div>
                        @else
                            @if(session()->has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @foreach ($iduka as $i)
                                <div class="card mb-3 shadow-sm card-hover p-3" style="border-radius: 10px;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div style="min-width: 0;">
                                            <!-- Nama Iduka dengan batas 2 baris -->
                                            <div class="fw-bold text-truncate d-inline-block w-100" style="font-size: 16px; max-height: 40px; overflow: hidden;">
                                                {{ $i->nama }}
                                            </div>
                                            <!-- Alamat dengan text-truncate -->
                                            <div class="text-muted text-truncate w-100" style="font-size: 14px;">
                                                {{ $i->alamat }}
                                            </div>
                                            @if ($i->rekomendasi == 1)
                                                <div class="text-success mt-1" style="font-size: 13px;">
                                                    <strong>Rekomendasi:</strong> INSTITUSI ini direkomendasikan
                                                </div>
                                            @endif
                                        </div>

                                        <div class="d-flex align-items-center">
                                            <!-- Tombol Detail untuk desktop -->
                                            <a href="{{ route('cetak.iduka', $i->id) }}"
                                               class="btn btn-hover rounded-pill btn-sm ms-2 d-none d-md-block">Detail</a>

                                            <!-- Tombol Icon Tanggal -->
                                            @if(auth()->user()->role === 'kaprog')
                                                <button type="button"
                                                        class="btn btn-hover rounded-pill btn-sm ms-2"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#aturTanggalModalKaprog{{ $i->id }}">
                                                    <i class="bi bi-calendar-event"></i>
                                                </button>
                                            @elseif(auth()->user()->role === 'hubin')
                                                <button type="button"
                                                        class="btn btn-outline-primary btn-sm ms-2"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#aturTanggalModalHubin{{ $i->id }}">
                                                    <i class="bi bi-calendar-event"></i>
                                                </button>
                                            @endif

                                            <!-- Mobile Handling -->
                                            @if(auth()->user()->role === 'kaprog')
                                                <!-- Dropdown di Mobile khusus kaprog -->
                                                <div class="dropdown ms-2 d-md-none">
                                                    <button class="btn dropdown-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                       <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a href="{{ route('cetak.iduka', $i->id) }}" class="dropdown-item">Detail</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            @elseif(auth()->user()->role === 'persuratan')
                                                <!-- Tombol langsung untuk persuratan di mobile -->
                                                <a href="{{ route('cetak.iduka', $i->id) }}"
                                                   class="btn btn-hover rounded-pill btn-sm ms-2 d-md-none">Detail</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                @if(auth()->user()->role === 'kaprog')
                                    <!-- Modal Atur Tanggal Kaprog -->
                                    <div class="modal fade" id="aturTanggalModalKaprog{{ $i->id }}" tabindex="-1" aria-labelledby="aturTanggalLabelKaprog{{ $i->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{ route('kaprog.tanggal.update', $i->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="aturTanggalLabelKaprog{{ $i->id }}">Atur Batas Waktu Usulan (Kaprog)</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="tanggal_awalKaprog{{ $i->id }}" class="form-label">Tanggal Awal</label>
                                                            <input type="date" class="form-control" id="tanggal_awalKaprog{{ $i->id }}" name="tanggal_awal" value="{{ $i->tanggal_awal }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="tanggal_akhirKaprog{{ $i->id }}" class="form-label">Tanggal Akhir</label>
                                                            <input type="date" class="form-control" id="tanggal_akhirKaprog{{ $i->id }}" name="tanggal_akhir" value="{{ $i->tanggal_akhir }}">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @elseif(auth()->user()->role === 'hubin')
                                    <!-- Modal Atur Tanggal Hubin -->
                                    <div class="modal fade" id="aturTanggalModalHubin{{ $i->id }}" tabindex="-1" aria-labelledby="aturTanggalLabelHubin{{ $i->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{ route('hubin.tanggal.update', $i->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="aturTanggalLabelHubin{{ $i->id }}">Atur Batas Waktu Usulan (Hubin)</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="tanggal_awalHubin{{ $i->id }}" class="form-label">Tanggal Awal</label>
                                                            <input type="date" class="form-control" id="tanggal_awalHubin{{ $i->id }}" name="tanggal_awal" value="{{ $i->tanggal_awal }}">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="tanggal_akhirHubin{{ $i->id }}" class="form-label">Tanggal Akhir</label>
                                                            <input type="date" class="form-control" id="tanggal_akhirHubin{{ $i->id }}" name="tanggal_akhir" value="{{ $i->tanggal_akhir }}">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                    <div class="card">
                        <div class="d-flex justify-content-end mt-3">
                            {{ $iduka->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // SweetAlert Konfirmasi Hapus
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();

                    Swal.fire({
                        title: "Apakah kamu yakin?",
                        text: "Data ini tidak bisa dikembalikan!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya, Hapus!",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.closest('form').submit();
                        }
                    });
                });
            });

            // Notifikasi SweetAlert setelah aksi sukses
            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success')),
                timer: 2000,
                showConfirmButton: false
            });
            @endif
        });
    </script>

    @include('iduka.dataiduka.createiduka')
</body>

</html>
@endsection
