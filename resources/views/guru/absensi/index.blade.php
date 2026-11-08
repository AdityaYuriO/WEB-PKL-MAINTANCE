@extends('layout.main')

@section('content')
<div class="container">
    <h2 class="mb-3">Konfirmasi Absensi Siswa</h2>

    {{-- Tabs --}}
    <ul class="nav nav-tabs nav-tabs-responsive" id="absensiTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="absensi-tab" data-toggle="tab" href="#absensi" role="tab">
                <i class="fas fa-calendar-check mr-1"></i>Absensi
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="izin-tab" data-toggle="tab" href="#izin" role="tab">
                <i class="fas fa-file-alt mr-1"></i>Izin
            </a>
        </li>
    </ul>

    <div class="tab-content" id="absensiTabContent">

        {{-- Tab Absensi --}}
        <div class="tab-pane fade show active" id="absensi" role="tabpanel">
            @if($absensiPending->count() > 0)
                <div class="table-scroll-hint">
                    <i class="fas fa-arrows-alt-h"></i> Geser tabel ke kiri/kanan untuk melihat semua kolom
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-mobile">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>IDUKA</th>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Waktu</th>
                                <th>Status IDUKA &amp; Guru</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($absensiPending as $absensi)
                            <tr>
                                <td data-label="Nama Siswa">
                                    <div class="fw-semibold">{{ $absensi->user->name }}</div>
                                </td>
                                <td data-label="IDUKA">{{ $absensi->iduka->nama }}</td>
                                <td data-label="Tanggal">{{ $absensi->tanggal->format('d/m/Y') }}</td>
                                <td data-label="Jenis">
                                    <span class="badge badge-info">{{ ucfirst($absensi->jenis) }}</span>
                                </td>
                                <td data-label="Waktu">{{ $absensi->jam->format('H:i') }} WIB</td>
                                <td data-label="Status">
                                    <div class="d-flex flex-column gap-1">
                                        @if($absensi->status_konfirmasi === 'disetujui')
                                            <span class="badge badge-success">IDUKA Disetujui</span>
                                        @elseif($absensi->status_konfirmasi === 'ditolak')
                                            <span class="badge badge-danger">IDUKA Ditolak</span>
                                        @else
                                            <span class="badge badge-warning">IDUKA Pending</span>
                                        @endif
                                        @if($absensi->status_konfirmasi_guru === 'pending')
                                            <span class="badge badge-warning">Guru Pending</span>
                                        @elseif($absensi->status_konfirmasi_guru === 'disetujui')
                                            <span class="badge badge-success">Guru Disetujui</span>
                                        @elseif($absensi->status_konfirmasi_guru === 'ditolak')
                                            <span class="badge badge-danger">Guru Ditolak</span>
                                        @endif
                                    </div>
                                </td>
                                <td data-label="Aksi">
                                    <div class="action-buttons">
                                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#approveModal{{ $absensi->id }}">
                                            <i class="fas fa-check"></i> Setujui
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#rejectModal{{ $absensi->id }}">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    </div>

                                    {{-- Modal Approve --}}
                                    <div class="modal fade" id="approveModal{{ $absensi->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('guru.absensi.approve', $absensi->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Setujui Absensi</h5>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-0">Apakah Anda yakin ingin menyetujui absensi <strong>{{ $absensi->user->name }}</strong>?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success">Ya, Setujui</button>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Modal Reject --}}
                                    <div class="modal fade" id="rejectModal{{ $absensi->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('guru.absensi.reject', $absensi->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Tolak Absensi</h5>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group mb-0">
                                                            <label for="catatan{{ $absensi->id }}">Alasan Penolakan</label>
                                                            <textarea class="form-control" id="catatan{{ $absensi->id }}" name="catatan_guru" rows="3" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-danger">Tolak</button>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle"></i> Tidak ada absensi yang perlu dikonfirmasi.
                </div>
            @endif
        </div>

        {{-- Tab Izin --}}
        <div class="tab-pane fade" id="izin" role="tabpanel">
            @if($izinPending->count() > 0)
                <div class="table-scroll-hint">
                    <i class="fas fa-arrows-alt-h"></i> Geser tabel ke kiri/kanan untuk melihat semua kolom
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-mobile">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>IDUKA</th>
                                <th>Tanggal</th>
                                <th>Jenis Izin</th>
                                <th>Keterangan</th>
                                <th>Status IDUKA &amp; Guru</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($izinPending as $izin)
                            <tr>
                                <td data-label="Nama Siswa">
                                    <div class="fw-semibold">{{ $izin->user->name }}</div>
                                </td>
                                <td data-label="IDUKA">{{ $izin->iduka->nama }}</td>
                                <td data-label="Tanggal">{{ $izin->tanggal->format('d/m/Y') }}</td>
                                <td data-label="Jenis Izin">
                                    <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $izin->jenis_izin)) }}</span>
                                </td>
                                <td data-label="Keterangan">{{ $izin->keterangan }}</td>
                                <td data-label="Status">
                                    <div class="d-flex flex-column gap-1">
                                        @if($izin->status_konfirmasi === 'disetujui')
                                            <span class="badge badge-success">IDUKA Disetujui</span>
                                        @elseif($izin->status_konfirmasi === 'ditolak')
                                            <span class="badge badge-danger">IDUKA Ditolak</span>
                                        @else
                                            <span class="badge badge-warning">IDUKA Pending</span>
                                        @endif
                                        @if($izin->status_konfirmasi_guru === 'pending')
                                            <span class="badge badge-warning">Guru Pending</span>
                                        @elseif($izin->status_konfirmasi_guru === 'disetujui')
                                            <span class="badge badge-success">Guru Disetujui</span>
                                        @elseif($izin->status_konfirmasi_guru === 'ditolak')
                                            <span class="badge badge-danger">Guru Ditolak</span>
                                        @endif
                                    </div>
                                </td>
                                <td data-label="Aksi">
                                    <div class="action-buttons">
                                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#approveIzinModal{{ $izin->id }}">
                                            <i class="fas fa-check"></i> Setujui
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#rejectIzinModal{{ $izin->id }}">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    </div>

                                    {{-- Modal Approve Izin --}}
                                    <div class="modal fade" id="approveIzinModal{{ $izin->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('guru.absensi.approveIzin', $izin->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Setujui Izin</h5>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-0">Apakah Anda yakin ingin menyetujui izin <strong>{{ $izin->user->name }}</strong>?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success">Ya, Setujui</button>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Modal Reject Izin --}}
                                    <div class="modal fade" id="rejectIzinModal{{ $izin->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form action="{{ route('guru.absensi.rejectIzin', $izin->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Tolak Izin</h5>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group mb-0">
                                                            <label for="catatanIzin{{ $izin->id }}">Alasan Penolakan</label>
                                                            <textarea class="form-control" id="catatanIzin{{ $izin->id }}" name="catatan_guru" rows="3" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-danger">Tolak</button>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle"></i> Tidak ada izin yang perlu dikonfirmasi.
                </div>
            @endif
        </div>

    </div>
</div>

<style>
    /* ============================================================ */
    /* ===== MOBILE FIXES ===== */
    /* ============================================================ */

    /* Hint scroll — hanya tampil di HP */
    .table-scroll-hint {
        display: none;
        font-size: .72rem;
        color: #64748b;
        background: #f1f5f9;
        border-radius: 8px;
        padding: 6px 10px;
        margin-top: 12px;
        margin-bottom: 0;
    }

    /* Action buttons: horizontal by default */
    .action-buttons {
        display: flex;
        gap: 6px;
        justify-content: center;
        flex-wrap: wrap;
    }
    .action-buttons .btn { white-space: nowrap; }

    /* Tabs: rapikan di semua ukuran */
    .nav-tabs-responsive .nav-link {
        font-size: .95rem;
        padding: .5rem 1rem;
    }

    /* Status badges stacked */
    .badge + .badge { margin-top: 4px; }

    /* === Tablet & Mobile (≤ 767px) === */
    @media (max-width: 767.98px) {
        .table-scroll-hint { display: block; }

        /* WAJIB: min-width tabel supaya scroll, bukan nyempit */
        .table-mobile { min-width: 780px; }

        /* Card / container padding */
        .container { padding-left: 12px; padding-right: 12px; }
        h2 { font-size: 1.15rem; }

        /* Tabs: font lebih kecil */
        .nav-tabs-responsive .nav-link {
            font-size: .85rem;
            padding: .45rem .85rem;
        }

        /* Table umum */
        .table { font-size: .85rem; }
        .table > :not(caption) > * > * { padding: .55rem .5rem; }
        .badge { font-size: .7rem; padding: .35em .55em; }

        /* Tombol Aksi — stack vertical, full width */
        .action-buttons {
            flex-direction: column;
            gap: 4px;
            min-width: 90px;
        }
        .action-buttons .btn {
            width: 100%;
            font-size: .78rem;
            padding: .35rem .5rem;
        }

        /* Modal: lebih ramah di HP */
        .modal-dialog { margin: .5rem; }
        .modal-header { padding: .75rem 1rem; }
        .modal-body { padding: .9rem 1rem; }
        .modal-footer { padding: .65rem 1rem; gap: .4rem; }
        .modal-footer .btn { flex: 1; }
    }

    /* === Extra small (≤ 575px) === */
    @media (max-width: 575.98px) {
        .table { font-size: .78rem; }
        .table > :not(caption) > * > * { padding: .5rem .4rem; }
        .badge { font-size: .65rem; padding: .3em .5em; }

        .nav-tabs-responsive .nav-link {
            font-size: .78rem;
            padding: .4rem .7rem;
        }

        .action-buttons .btn {
            font-size: .72rem;
            padding: .3rem .45rem;
        }
    }
</style>
@endsection
