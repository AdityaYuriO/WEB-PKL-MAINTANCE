@extends('layout.main')

@section('content')
<div class="container">
    <h3>Daftar Pengajuan PKL</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Siswa</th>
                <th>Institusi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengajuan as $p)
            <tr>
                <td>{{ $p->siswa->name }}</td>
                <td>{{ $p->iduka->nama }}</td>
                <td>
                    <span class="badge bg-{{ $p->status == 'diterima' ? 'success' : ($p->status == 'ditolak' ? 'danger' : 'warning') }}">
                        {{ ucfirst($p->status) }}
                    </span>
                </td>
                <td>
                    @if(auth()->user()->role == 'iduka')
                    <form action="{{ route('pengajuan.verifikasi', $p->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button name="status" value="diterima" class="btn btn-success">Terima</button>
                        <button name="status" value="ditolak" class="btn btn-danger">Tolak</button>
                    </form>
                    @endif

                    @if(auth()->user()->role == 'persuratan')
                    <a href="{{ route('pengajuan.downloadCp', $p->iduka_id) }}" class="btn btn-secondary">Download CP</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
