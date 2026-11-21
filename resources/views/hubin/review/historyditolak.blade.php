@extends('layout.main')

@section('content')
<style>
    .btn-back {
                background-color: #7e7dfb;
                color: white;
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
                border: none;
                padding: 7px 14px;
                border-radius: 5px;
                font-size: 16px;
                cursor: pointer;
                transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out, background-color 0.2s ease-in-out;
            }

            .btn-back:hover, .btn-reset:hover {
                background-color: #7e7dfb;
                color: white;
                transform: translateY(-3px);
                box-shadow: 0 12px 24px rgba(0, 0, 0, 0.25); 
            }

            .btn-back:active, .btn-reset:active {
                color: white;
                background-color: #6b6bfa !important; 
                transform: translateY( 3px); 
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
            }
            .btn-reset {
                background-color: #7e7dfb;
                color: white;
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
                border: none;
                padding: 6px 12px;
                border-radius: 5px;
                font-size: 14px;
                margin-bottom: 0.5rem;
                cursor: pointer;
                transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out, background-color 0.2s ease-in-out;
            }
</style>
    <div class="container-fluid">
        <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
                <div class="row">    
                    <div class="col-md-12 mt-3">
                        <div class="col-md-12 mt-3 d-flex justify-content-between align-items-center">
                            <h4 class="mb-3">History Pengajuan Diterima</h4>
                            <button class="btn btn-reset shadow-sm">Reset Data</button>
                        </div>
                        <div class="card shadow-sm" style="padding: 20px;">
                            @if(session()->has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif   
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Nama Institusi</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data History Ditolak -->
                                    <tr>
                                        <td>1</td>
                                        <td>Mark Johnson</td>
                                        <td>XI RPL 2</td>
                                        <td>Desa Kawali</td>
                                        <td>19-02-2025</td>
                                        <td><span class="badge bg-danger">Ditolak</span></td>
                                        <td>
                                            {{-- <a href="#" class="btn btn-info btn-sm">Detail</a> --}}
                                            <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <!-- Add more rows here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex justify-content mt-3 mb-2">
                        <a href="{{ route('review.pengajuan')}}" class="btn btn-back shadow-sm">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
