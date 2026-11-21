@extends('layout.main')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    {{-- Welcome Card --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="border-left:4px solid #3b82f6;">
                <div class="d-flex align-items-center row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div style="width:44px;height:44px;background:#eff6ff;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-chalkboard-teacher" style="font-size:20px;color:#3b82f6;"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-0">Halo, {{ Auth::user()->name }}!</h5>
                                    <small style="color:#64748b;">Dashboard Guru PKL</small>
                                </div>
                            </div>
                            <p style="color:#64748b;font-size:14px;margin-bottom:16px;">
                                Pantau perkembangan siswa bimbingan Anda dan konfirmasi jurnal kegiatan PKL.
                            </p>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('guru.siswa-dibimbing.index') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-users"></i> Siswa Dibimbing
                                </a>
                                <a href="{{ route('guru.konfir-jurnal.index') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-book-open"></i> Konfirmasi Jurnal
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center">
                        <div class="card-body pb-0 px-md-4">
                            <img src="{{ asset('snet/assets/img/illustrations/man-with-laptop-light.png') }}"
                                height="130" alt="Guru" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Access Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div style="width:48px;height:48px;background:#eff6ff;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-users" style="font-size:20px;color:#3b82f6;"></i>
                    </div>
                    <div>
                        <div style="font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Siswa Dibimbing</div>
                        <div style="font-size:22px;font-weight:800;color:#0f172a;">-</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div style="width:48px;height:48px;background:#f0fdf4;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-book-open" style="font-size:20px;color:#22c55e;"></i>
                    </div>
                    <div>
                        <div style="font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Jurnal Menunggu</div>
                        <div style="font-size:22px;font-weight:800;color:#0f172a;">-</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="{{ route('guru.konfir-jurnal.index') }}" style="text-decoration:none;">
                <div class="card" style="cursor:pointer;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div style="width:48px;height:48px;background:#fffbeb;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-clock" style="font-size:20px;color:#f59e0b;"></i>
                        </div>
                        <div>
                            <div style="font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Jurnal Pending</div>
                            <div style="font-size:13px;color:#3b82f6;font-weight:600;">Lihat Semua &rarr;</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-lg-3">
            <a href="{{ route('guru.absen.index') }}" style="text-decoration:none;">
                <div class="card" style="cursor:pointer;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div style="width:48px;height:48px;background:#ecfeff;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-calendar-check" style="font-size:20px;color:#06b6d4;"></i>
                        </div>
                        <div>
                            <div style="font-size:12px;color:#64748b;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Absensi Saya</div>
                            <div style="font-size:13px;color:#3b82f6;font-weight:600;">Lihat Riwayat &rarr;</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- Menu Navigasi Cepat --}}
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-compass" style="color:#3b82f6;"></i>
                        <h5 class="mb-0">Navigasi Cepat</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('guru.siswa-dibimbing.index') }}" class="btn btn-outline-primary d-flex align-items-center gap-2">
                            <i class="fas fa-users"></i>
                            <span>Kelola Siswa Dibimbing</span>
                            <i class="fas fa-chevron-right ms-auto" style="font-size:11px;"></i>
                        </a>
                        <a href="{{ route('guru.konfir-jurnal.index') }}" class="btn btn-outline-primary d-flex align-items-center gap-2">
                            <i class="fas fa-book"></i>
                            <span>Konfirmasi Jurnal PKL</span>
                            <i class="fas fa-chevron-right ms-auto" style="font-size:11px;"></i>
                        </a>
                        <a href="{{ route('guru.konfir-jurnal.riwayat') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                            <i class="fas fa-history"></i>
                            <span>Riwayat Konfirmasi Jurnal</span>
                            <i class="fas fa-chevron-right ms-auto" style="font-size:11px;"></i>
                        </a>
                        <a href="{{ route('guru.absen.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                            <i class="fas fa-fingerprint"></i>
                            <span>Riwayat Absensi Saya</span>
                            <i class="fas fa-chevron-right ms-auto" style="font-size:11px;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-info-circle" style="color:#3b82f6;"></i>
                        <h5 class="mb-0">Informasi Sistem</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex align-items-start gap-3 p-3" style="background:#f8fafc;border-radius:10px;">
                            <div style="width:36px;height:36px;background:#eff6ff;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas fa-book-reader" style="font-size:15px;color:#3b82f6;"></i>
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:13.5px;color:#0f172a;">Jurnal Harus Divalidasi</div>
                                <div style="font-size:12.5px;color:#64748b;">Periksa jurnal siswa bimbingan secara berkala dan berikan konfirmasi.</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-3 p-3" style="background:#f8fafc;border-radius:10px;">
                            <div style="width:36px;height:36px;background:#f0fdf4;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fas fa-map-marker-alt" style="font-size:15px;color:#22c55e;"></i>
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:13.5px;color:#0f172a;">Absensi Berbasis Lokasi</div>
                                <div style="font-size:12.5px;color:#64748b;">Gunakan fitur absensi dari dashboard utama setiap hari kerja.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
