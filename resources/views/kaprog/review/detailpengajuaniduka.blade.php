@extends('layout.main')

@section('content')
<div class="container mt-4">
    <h4 class="mb-3">Detail Pengajuan ke INSTITUSI: {{ $iduka->nama }}</h4>

    @if($pengajuans->isEmpty())
        <div class="alert alert-info">Belum ada siswa yang mengajukan PKL ke institusi ini.</div>
    @else
        <div class="table-responsive text-nowrap">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>

                        <th>Konsentrasi Keahlian</th>

                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengajuans as $pengajuan)
                    <tr>
                        <td>{{ $pengajuan->user->name }}</td>
                        <td>{{ $pengajuan->user->dataPribadi->kelas->kelas ?? '-' }} {{ $pengajuan->user->dataPribadi->kelas->name_kelas ?? '-' }}</td>
                        <td>{{ $pengajuan->user->dataPribadi->konkes->name_konke }}</td>

                        <td>{{ ucfirst($pengajuan->status) }}</td>
                        <td>
                            <form method="POST" action="{{ route('kaprog.usulan-pkl.status', $pengajuan->id) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="diterima">
                                <button class="btn btn-success btn-sm me-1">Terima</button>
                            </form>

                            <form method="POST" action="{{ route('kaprog.usulan-pkl.status', $pengajuan->id) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="ditolak">
                                <button class="btn btn-danger btn-sm">Tolak</button>
                            </form>

                            <a href="{{ route('kaprog.usulan-pkl.detailSiswa', $pengajuan->id) }}" class="btn btn-primary btn-sm mb-1">Lihat Detail</a>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

