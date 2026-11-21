@extends('layout.main')

@section('content')
<div class="container-fluid">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="card mb-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Histori Pengajuan IDUKA</h5>
                      
                    </div>
                </div>

                <div class="col-md-12 mt-3">
                    @if($historiPengajuan->isEmpty())
                    <p class="text-center">Tidak ada histori pengajuan.</p>
                    @else
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @foreach($historiPengajuan as $iduka_id => $pengajuanGroup)
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ $pengajuanGroup->first()->iduka->nama }}</h5>
                                <span class="badge bg-primary rounded-pill">
                                    {{ $pengajuanGroup->count() }} pengajuan
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Siswa</th>
                                            <th>Kelas</th>
                                            <th>Status</th>
                                            <th>Tanggal Update</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pengajuanGroup as $index => $pengajuan)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $pengajuan->siswa->name }}</td>
                                            <td>{{ $pengajuan->siswa->kelas->kelas }} {{ $pengajuan->siswa->kelas->name_kelas }}</td>
                                            <td>
                                                @if($pengajuan->status == 'diterima')
                                                <span class="badge bg-success">Diterima</span>
                                                @elseif($pengajuan->status == 'ditolak')
                                                <span class="badge bg-danger">Ditolak</span>
                                                @else
                                                <span class="badge bg-warning text-dark">{{ $pengajuan->status }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $pengajuan->updated_at->format('d M Y H:i') }}</td>
                                            
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
