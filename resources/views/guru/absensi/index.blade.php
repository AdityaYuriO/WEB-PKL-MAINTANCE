@extends('layout.main')

@section('content')
<div class="container">
    <h2>Konfirmasi Absensi Siswa</h2>
    @if (!empty($pageError))
        <div class="alert alert-warning" role="alert">{{ $pageError }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
    @endif
    @if (session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif
    <ul class="nav nav-tabs" id="absensiTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="absensi-tab" data-bs-toggle="tab" href="#absensi" role="tab">Absensi</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="izin-tab" data-bs-toggle="tab" href="#izin" role="tab">Izin</a>
        </li>
    </ul>
    <div class="tab-content" id="absensiTabContent">
        <!-- Tab Absensi -->
        <div class="tab-pane fade show active" id="absensi" role="tabpanel">
            @if($absensiPending->count() > 0)
                <div class="table-responsive mt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>IDUKA</th>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Waktu</th>
                                <th>Status IDUKA & Guru</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($absensiPending as $absensi)
                            <tr>
                                <td>{{ $absensi->user->name }}</td>
                                <td>{{ $absensi->iduka->nama }}</td>
                                <td>{{ $absensi->tanggal->format('d/m/Y') }}</td>
                                <td>{{ ucfirst($absensi->jenis) }}</td>
                                <td>{{ $absensi->jam->format('H:i') }}</td>
                                <td>
                                    @if($absensi->status_konfirmasi === 'disetujui')
                                        <span class="badge badge-success">IDUKA Disetujui</span>
                                    @elseif($absensi->status_konfirmasi === 'ditolak')
                                        <span class="badge badge-danger">IDUKA Ditolak</span>
                                    @else
                                        <span class="badge badge-warning">IDUKA Pending</span>
                                    @endif
                                    <br>
                                    @if($absensi->status_konfirmasi_guru === 'pending')
                                        <span class="badge badge-warning">Guru Pending</span>
                                    @elseif($absensi->status_konfirmasi_guru === 'disetujui')
                                        <span class="badge badge-success">Guru Disetujui</span>
                                    @elseif($absensi->status_konfirmasi_guru === 'ditolak')
                                        <span class="badge badge-danger">Guru Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approveModal{{ $absensi->id }}">Setujui</button>
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $absensi->id }}">Tolak</button>
                                    <!-- Modal Approve -->
                                    <div class="modal fade" id="approveModal{{ $absensi->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('guru.absensi.approve', $absensi->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Setujui Absensi</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menyetujui absensi ini?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success">Ya, Setujui</button>
                                                        {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button> --}}
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal Reject -->
                                    <div class="modal fade" id="rejectModal{{ $absensi->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('guru.absensi.reject', $absensi->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Tolak Absensi</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="catatan{{ $absensi->id }}">Alasan Penolakan</label>
                                                            <textarea class="form-control" id="catatan{{ $absensi->id }}" name="catatan_guru" rows="3" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-danger">Tolak</button>
                                                        {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button> --}}
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
                <p class="mt-3">Tidak ada absensi yang perlu dikonfirmasi.</p>
            @endif
        </div>
        <!-- Tab Izin -->
        <div class="tab-pane fade" id="izin" role="tabpanel">
            @if($izinPending->count() > 0)
                <div class="table-responsive mt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>IDUKA</th>
                                <th>Tanggal</th>
                                <th>Jenis Izin</th>
                                <th>Keterangan</th>
                                <th>Status IDUKA & Guru</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($izinPending as $izin)
                            <tr>
                                <td>{{ $izin->user->name }}</td>
                                <td>{{ $izin->iduka->nama }}</td>
                                <td>{{ $izin->tanggal->format('d/m/Y') }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $izin->jenis_izin)) }}</td>
                                <td>{{ $izin->keterangan }}</td>
                                <td>
                                    @if($izin->status_konfirmasi === 'disetujui')
                                        <span class="badge badge-success">IDUKA Disetujui</span>
                                    @elseif($izin->status_konfirmasi === 'ditolak')
                                        <span class="badge badge-danger">IDUKA Ditolak</span>
                                    @else
                                        <span class="badge badge-warning">IDUKA Pending</span>
                                    @endif
                                    <br>
                                    @if($izin->status_konfirmasi_guru === 'pending')
                                        <span class="badge badge-warning">Guru Pending</span>
                                    @elseif($izin->status_konfirmasi_guru === 'disetujui')
                                        <span class="badge badge-success">Guru Disetujui</span>
                                    @elseif($izin->status_konfirmasi_guru === 'ditolak')
                                        <span class="badge badge-danger">Guru Ditolak</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approveIzinModal{{ $izin->id }}">Setujui</button>
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectIzinModal{{ $izin->id }}">Tolak</button>
                                    <!-- Modal Approve Izin -->
                                    <div class="modal fade" id="approveIzinModal{{ $izin->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('guru.absensi.approveIzin', $izin->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Setujui Izin</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menyetujui izin ini?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-success">Ya, Setujui</button>
                                                        {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button> --}}
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal Reject Izin -->
                                    <div class="modal fade" id="rejectIzinModal{{ $izin->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('guru.absensi.rejectIzin', $izin->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Tolak Izin</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="catatanIzin{{ $izin->id }}">Alasan Penolakan</label>
                                                            <textarea class="form-control" id="catatanIzin{{ $izin->id }}" name="catatan_guru" rows="3" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-danger">Tolak</button>
                                                        {{-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button> --}}
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
                <p class="mt-3">Tidak ada izin yang perlu dikonfirmasi.</p>
            @endif
        </div>
    </div>
</div>
@endsection