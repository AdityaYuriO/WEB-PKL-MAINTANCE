@extends('layout.main')

@section('content')
    <div class="container-fluid">
        <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
                <div class="row">

                    {{-- Cek jika tidak ada data sama sekali --}}
                    @if ($pengajuanByYear->isEmpty())
                        <div class="col-12">
                            <div class="alert alert-warning text-center">
                                Belum ada siswa diterima PKL.
                            </div>
                        </div>
                    @endif

                    {{-- Loop jika ada data --}}
                    @foreach ($pengajuanByYear as $year => $pengajuans)
                        <div class="col-12">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="mb-0">Daftar Siswa Diterima PKL Tahun {{ $year }}</h5>
                                </div>
                            </div>
                            <div class="card shadow-sm p-3">
                                @if(session()->has('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif   
                                <div class="table-responsive text-nowrap">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Siswa</th>
                                                <th>NISN</th>
                                                <th>Kelas</th>
                                                <th>Tanggal Diterima</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($pengajuans as $key => $pengajuan)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $pengajuan->siswa->name ?? '-' }}</td>
                                                    <td>{{ $pengajuan->siswa->nip ?? '-' }}</td>
                                                    <td>{{ $pengajuan->siswa->kelas->kelas ?? '-' }} {{ $pengajuan->siswa->kelas->name_kelas ?? '-' }}</td>
                                                    <td>{{ $pengajuan->created_at->format('d/m/Y') }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">Tidak ada siswa diterima PKL untuk tahun {{ $year }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
@endsection

