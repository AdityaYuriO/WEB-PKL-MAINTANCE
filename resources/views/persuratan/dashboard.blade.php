@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- Welcome Card -->
        <div class="col-lg-12 mb-4 order-0">
            <div class="card shadow-sm border-0 bg-primary text-white card-hover">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-8">
                        <div class="card-body">
                            <h4 class="card-title text-white mb-2 fw-bold">Selamat Datang, Bagian Persuratan ({{ Auth::user()->name }})!</h4>
                            <p class="mb-4 text-white-50">
                                Ini adalah dashboard panel Tata Usaha/Persuratan. Anda dapat mengelola dokumen legalitas PKL, memproses surat pengajuan ke IDUKA, dan memverifikasi kelengkapan administrasi siswa.
                            </p>
                            <a href="{{ route('profile.edit') }}" class="btn btn-light text-primary border-0 shadow-sm fw-semibold">
                                <i class="fas fa-file-signature me-2"></i> Kelola Profil
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-4 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('snet/assets/img/illustrations/man-with-laptop-light.png') }}" 
                                 height="140" 
                                 alt="Welcome Persuratan" 
                                 class="d-none d-sm-block ms-auto">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Placeholder -->
    <div class="row">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100 card-hover">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="fas fa-envelope-open-text fs-4"></i>
                        </span>
                    </div>
                    <div>
                        <h6 class="mb-0 text-muted">Surat Masuk/Keluar</h6>
                        <h4 class="mb-0 fw-bold mt-1 text-dark">Data Belum Tersedia</h4>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100 card-hover">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="fas fa-hourglass-half fs-4"></i>
                        </span>
                    </div>
                    <div>
                        <h6 class="mb-0 text-muted">Surat Menunggu Cetak</h6>
                        <h4 class="mb-0 fw-bold mt-1 text-dark">Data Belum Tersedia</h4>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card shadow-sm border-0 h-100 card-hover">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="fas fa-check-double fs-4"></i>
                        </span>
                    </div>
                    <div>
                        <h6 class="mb-0 text-muted">Surat Selesai Diproses</h6>
                        <h4 class="mb-0 fw-bold mt-1 text-dark">Data Belum Tersedia</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Information Card -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 card-hover">
                <div class="card-header bg-white border-bottom pb-3">
                    <h5 class="card-title fw-bold mb-0 text-dark">
                        <i class="fas fa-info-circle text-primary me-2"></i> Informasi Penting
                    </h5>
                </div>
                <div class="card-body pt-4">
                    <p class="text-secondary">Pastikan untuk selalu memeriksa daftar pengajuan cetak surat dari unit kerja (Hubin/Kaprog) secara berkala agar proses administrasi PKL siswa berjalan lancar tanpa hambatan.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

