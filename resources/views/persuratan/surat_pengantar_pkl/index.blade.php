@extends('layout.main')

@section('content')
    <div class="container-fluid">
        <div class="container-xxl flex-grow-1 container-p-y">

            {{-- Alert Error --}}
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Alert Success --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Header & Search --}}
            <div class="card mb-3">
                <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <form method="GET" action="{{ route('surat-pengantar.index') }}"
                        class="d-flex align-items-center gap-2 flex-grow-1" style="max-width: 500px;">
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="form-control form-control-sm" placeholder="Cari nama atau alamat...">

                        <button type="submit" class="btn btn-warning btn-sm d-flex align-items-center">
                            <i class="bi bi-search me-1"></i> Cari
                        </button>
                    </form>
                </div>
            </div>

            {{-- Data Iduka --}}
            @if ($idukas->isEmpty())
                <div class="alert alert-warning">Belum ada data institusi / perusahaan.</div>
            @else
                @foreach ($idukas as $i)
                    <div class="card mb-3 shadow-sm card-hover p-3" style="border-radius: 10px;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-bold" style="font-size: 16px;">{{ $i->nama }}</div>
                                <div class="text-muted" style="font-size: 14px;">{{ $i->alamat }}</div>
                                <div class="mt-1" style="font-size: 13px;">
                                    <span class="badge bg-info">
                                        {{ $i->siswa->where('status', 'aktif')->count() }} Siswa
                                    </span>
                                </div>
                                @if ($i->rekomendasi == 1)
                                    <div class="text-success mt-1" style="font-size: 13px;">
                                        <strong>Rekomendasi:</strong> INSTITUSI ini direkomendasikan
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex gap-2">
                                {{-- Tombol Cetak --}}
                                @if ($i->siswa->count() > 0)
                                    <a href="{{ route('surat-pengantar.cetak', $i->id) }}"
                                        class="btn btn-hover rounded-pill btn-sm">
                                        <i class="bi bi-download me-1"></i> Download
                                    </a>
                                @else
                                    <button class="btn btn-secondary rounded-pill btn-sm" disabled
                                        title="Tidak ada siswa">
                                        <i class="bi bi-download me-1"></i> Download
                                    </button>
                                @endif

                                {{-- Tombol Detail (buka modal siswa) --}}
                                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill"
                                    data-bs-toggle="modal" data-bs-target="#modalDetail{{ $i->id }}">
                                    <i class="bi bi-people me-1"></i> Detail
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Detail Siswa --}}
                    <div class="modal fade" id="modalDetail{{ $i->id }}" tabindex="-1"
                        aria-labelledby="modalLabel{{ $i->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="modalLabel{{ $i->id }}">Daftar Siswa di
                                        {{ $i->nama }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    @if ($i->siswa->isEmpty())
                                        <div class="alert alert-warning mb-0">Belum ada siswa di IDUKA ini.</div>
                                    @else
                                        <div class="table-responsive text-nowrap">
                                            <table class="table table-striped table-sm align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Siswa</th>
                                                        <th>Email</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($i->siswa as $index => $s)
                                                    @if ( $s->status == "aktif")
                                                        <tr>
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>{{ $s->name }}</td>
                                                            <td>{{ $s->email }}</td>
                                                        </tr>
                                                    @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>

                                <div class="modal-footer">
                                    {{-- <button type="button" class="btn btn-secondary btn-sm"
                                        data-bs-dismiss="modal">Tutup</button> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $idukas->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    {{-- Styling --}}
    <style>
        .card-hover {
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .card-hover:hover {
            transform: scale(1.02);
            background-color: #f6f7ff;
        }

        .btn-hover {
            background-color: #7e7dfb;
            color: white;
            border-radius: 50px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn-hover:hover {
            background-color: white;
            color: #7e7dfb;
            border: 1px solid #7e7dfb;
        }
    </style>
@endsection
