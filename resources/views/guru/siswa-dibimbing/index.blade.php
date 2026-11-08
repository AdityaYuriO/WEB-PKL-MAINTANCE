@extends('layout.main')

@section('content')
    <div class="container-fluid py-3 px-2 px-md-3">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-users me-2"></i>
                        Siswa Bimbingan {{ auth()->user()->guru->nama ?? 'Guru tidak terdaftar' }}
                    </h5>
                </div>
                <div class="card-body">
                    @if ($siswas->count())
                        <div class="table-scroll-hint">
                            <i class="bi bi-arrow-left-right"></i> Geser tabel ke kiri/kanan untuk melihat semua kolom
                        </div>
                        <div class="table-responsive my-2">
                            <table class="table table-bordered table-striped align-middle mb-0" id="tabel-siswa-bimbingan">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
                                        <th>Jurusan</th>
                                        <th>IDUKA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siswas as $s)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $s->name }}</td>
                                            <td>{{ $s->kelas->kelas ?? '-' }}</td>
                                            <td>{{ $s->kelas->name_kelas ?? '-' }}</td>
                                            <td>{{ $s->idukaDiterima->nama ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted fst-italic text-center my-3">
                            <i class="fas fa-info-circle me-1"></i> Belum ada siswa bimbingan.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- ⚡ CSS FIX: bikin tabel bisa di-scroll di HP                  --}}
    {{-- ============================================================ --}}
    <style>
        /* Hint scroll — hanya tampil di HP */
        .table-scroll-hint {
            display: none;
            font-size: .72rem;
            color: #64748b;
            background: #f1f5f9;
            border-radius: 8px;
            padding: 6px 10px;
            margin-bottom: 8px;
        }

        /* === Mobile & Tablet (≤ 991px) === */
        @media (max-width: 991.98px) {

            /* 1) HAPUS overflow:hidden dari card (biang kerok tabel gak bisa digeser) */
            .card {
                overflow: visible !important;
            }

            /* 2) Balikin rounded corners biar card tetap cantik */
            .card > .card-header:first-child {
                border-top-left-radius: .5rem !important;
                border-top-right-radius: .5rem !important;
            }
            .card > .card-body:last-child {
                border-bottom-left-radius: .5rem !important;
                border-bottom-right-radius: .5rem !important;
            }

            /* 3) Hint muncul di HP */
            .table-scroll-hint { display: block; }

            /* 4) Paksa .table-responsive jadi scroll container beneran */
            .table-responsive {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                overflow-x: auto !important;
                overflow-y: hidden !important;
                -webkit-overflow-scrolling: touch !important;
                overscroll-behavior-x: contain !important;
                touch-action: pan-x pan-y !important;
            }

            /* 5) Tabel harus lebih lebar dari container supaya bisa di-scroll */
            #tabel-siswa-bimbingan {
                min-width: 640px !important;
                margin-bottom: 0 !important;
            }

            /* 6) Scrollbar tipis biar kelihatan bisa digeser */
            .table-responsive::-webkit-scrollbar { height: 6px; }
            .table-responsive::-webkit-scrollbar-track {
                background: #f1f5f9;
                border-radius: 3px;
            }
            .table-responsive::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 3px;
            }
            .table-responsive::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }
        }

        /* === Extra small (≤ 575px) === */
        @media (max-width: 575.98px) {
            .card-body { padding: .75rem !important; }
            .card-header h5 { font-size: .95rem !important; }
            .table { font-size: .82rem; }
            .table > :not(caption) > * > * { padding: .5rem .4rem; }
        }
    </style>
@endsection
