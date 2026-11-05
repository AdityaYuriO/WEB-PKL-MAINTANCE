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
                            <h4 class="card-title text-white mb-2 fw-bold">Selamat Datang, Bapak/Ibu {{ Auth::user()->name }}!</h4>
                            <p class="mb-4 text-white-50">
                                Ini adalah dashboard panel Kepala Program (Kaprog). Anda dapat memantau data usulan PKL, mengevaluasi proses pengajuan, dan mengelola administratif program keahlian Anda.
                            </p>
                            <a href="{{ route('profile.edit') }}" class="btn btn-light text-primary border-0 shadow-sm fw-semibold">
                                <i class="fas fa-user-edit me-2"></i> Lengkapi Profil
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-4 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('snet/assets/img/illustrations/man-with-laptop-light.png') }}" 
                                 height="140" 
                                 alt="Welcome Kaprog" 
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
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="fas fa-users fs-4"></i>
                        </span>
                    </div>
                    <div>
                        <h6 class="mb-0 text-muted">Total Siswa PKL</h6>
                        <h4 class="mb-0 fw-bold mt-1 text-dark">Data Belum Tersedia</h4>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100 card-hover">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="fas fa-check-circle fs-4"></i>
                        </span>
                    </div>
                    <div>
                        <h6 class="mb-0 text-muted">Usulan Diterima</h6>
                        <h4 class="mb-0 fw-bold mt-1 text-dark">Data Belum Tersedia</h4>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-12 mb-4">
            <div class="card shadow-sm border-0 h-100 card-hover">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar flex-shrink-0 me-3">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="fas fa-clock fs-4"></i>
                        </span>
                    </div>
                    <div>
                        <h6 class="mb-0 text-muted">Menunggu Review</h6>
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
                    <p class="text-secondary">Mohon pantau terus pengajuan usulan PKL dari para siswa. Pastikan semua dokumen dan persyaratan dari IDUKA telah terpenuhi sebelum memberikan verifikasi dan persetujuan.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection