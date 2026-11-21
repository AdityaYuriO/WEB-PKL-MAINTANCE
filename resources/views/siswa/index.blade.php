@extends('layout.main')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-left: 4px solid #3b82f6 !important;">
                <div class="card-body py-4">
                    <div class="d-flex align-items-center mb-3">
                        <div style="width: 48px; height: 48px; background: #eff6ff; border-radius: 12px; display: flex; align-items: center; justify-content: center;" class="me-3">
                            <i class="fas fa-tachometer-alt text-primary" style="font-size:20px;"></i>
                        </div>
                        <div>
                            <h4 class="mb-1 fw-bold text-dark">Dashboard Siswa</h4>
                            <p class="mb-0 text-muted">Akses dialihkan ke dashboard utama siswa.</p>
                        </div>
                    </div>
                    <a href="{{ route('siswa.dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-right me-2"></i> Pergi ke Dashboard Siswa
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
