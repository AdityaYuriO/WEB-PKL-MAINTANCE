@extends('layout.main')

@section('content')
    <div class="container py-4">

        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Daftar Siswa di {{ $iduka->nama }}</h5>
            </div>
            <div class="card-body">
                @if ($iduka->siswa->isEmpty())
                    <p class="text-muted">Belum ada siswa yang diterima di Iduka ini.</p>
                @else
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @foreach ($iduka->siswa as $index => $siswa)
                            @if ($siswa->status == "aktif")
                                <tr>
                                    <td>{{ $no++; }}</td>
                                    <td>{{ $siswa->name }}</td>
                                    <td>{{ $siswa->kelas->name_kelas ?? '-' }}</td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection
