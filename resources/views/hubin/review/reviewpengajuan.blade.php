@extends('layout.main')
@section('content')
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Review Pengajuan</title>
        <style>
            .card-hover {
                 transition: transform 0.3s ease, background-color 0.3s ease, color 0.3s ease;
                 height: 70px;
                 flex-direction: column;
                 justify-content: center; /* Menjaga jarak sama antara atas dan bawah */
                 display: flex;
             }
             .card-hover:hover {
                 transform: scale(1.03);
                 background-color: #7e7dfb !important; /* Warna diperbaiki */
                 color: white !important; /* Agar teks berubah saat hover */
             }
             .card-hover:hover .btn-hover {
                 background-color: white;
                 color: #7e7dfb;
                 border-color: white;
             }
             .btn-hover {
                 background-color: #7e7dfb;
                 color: white;
                 transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
                 border-radius: 50px;
                 border: 2px solid #7e7dfb;
             }
             .btn-hover:hover {
                 background-color: white;
                 color: #7e7dfb;
                 border-color: white;
             }
 
             .dropdown-btn {
                 color: #7e7dfb;
                 transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
                 border-radius: 50px;
                 padding: 5px 12px;
                 font-size: 25px;
             }
 
             .card-hover:hover .dropdown-btn {
                 color: white !important;
             }
 
             /* Memberikan margin bawah pada button-group */
             .button-group {
                 margin-bottom: 18px; /* Menambahkan jarak bawah 20px antara tombol dan card */
             }
         </style>
    </head>
    <body>
        <div class="container-fluid">              
                        <div class="col-md-12 mt-3">
                            <div class="button-group">
                                <a href="{{ route('history.diterima') }}" class="btn btn-success btn-status">History Diterima</a>
                                <a href="{{ route('history.ditolak') }}" class="btn btn-danger btn-status">History Ditolak</a>
                            </div>

                            @if(session()->has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif   
                            <div class="card mb-3 shadow-sm card-hover" style="padding: 30px; border-radius: 10px;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="mb-0" style="font-size: 18px">Nama Siswa</div>
                                        <div class="">Kelas</div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('detail.pengajuan') }}" class="btn btn-hover rounded-pill">Detail</a>
                                        <div class="dropdown ms-2">
                                            <button class="btn dropdown-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                ⋮
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                     {{-- <form action="#" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus iduka ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">Hapus</button>
                                                    </form> --}}
                                                    <button type="submit" class="dropdown-item text-danger">Hapus</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('iduka.dataiduka.createiduka')
    </body>
    </html>
@endsection