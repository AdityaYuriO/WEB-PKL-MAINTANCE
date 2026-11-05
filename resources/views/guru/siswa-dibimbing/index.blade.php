@extends('layout.main')

@section('content')
    <div class="container-fluid">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-white">
                        Siswa Bimbingan {{ auth()->user()->guru->nama ?? 'Guru tidak terdaftar' }}
                    </h5>
                </div>
                <div class="card-body">
                    @if ($siswas->count())
                        <div class="table-responsive my-2">
                            <table class="table table-bordered table-striped align-middle">
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
                            Belum ada siswa bimbingan.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endsection
