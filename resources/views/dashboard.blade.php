@extends('layout.main')

@section('content')
    {{-- MOBILE FIX: CSS responsive --}}
    <style>
        @media (max-width: 767.98px) {
            .content-wrapper .container-xxl { padding-left: 12px !important; padding-right: 12px !important; }
            .card-body { padding: 1rem !important; }
            .card-header { padding: .875rem 1rem !important; }
            .card-header h5 { font-size: .95rem !important; }

            .absen-action-group .btn { flex: 1 1 100%; width: 100%; }

            /* Tabel -> card di mobile */
            .table-mobile-cards { white-space: normal; }
            .table-mobile-cards thead { display: none; }
            .table-mobile-cards tbody tr:not(.empty-state) {
                display: block; border: 1px solid #e2e8f0; border-radius: 10px;
                margin-bottom: 10px; padding: 8px 14px; background: #fafbfc;
            }
            .table-mobile-cards tbody tr:not(.empty-state) td {
                display: flex; justify-content: space-between; align-items: flex-start;
                padding: 6px 0 !important; border: none !important; font-size: 13px;
                white-space: normal !important; text-align: right;
            }
            .table-mobile-cards tbody tr:not(.empty-state) td::before {
                content: attr(data-label); font-weight: 600; color: #64748b;
                margin-right: 12px; flex-shrink: 0; text-align: left;
            }
            .table-mobile-cards tbody tr.empty-state td { display: block; }

            .riwayat-header { flex-direction: column !important; align-items: flex-start !important; gap: 10px; }
            .riwayat-header .d-flex { width: 100%; }
            .riwayat-header .d-flex .btn { flex: 1; }
        }
        @media (max-width: 575.98px) {
            .welcome-image-col { display: none !important; }
            .status-card-header { flex-direction: column !important; align-items: flex-start !important; gap: 8px; }
            .absen-action-group .btn { font-size: 13px; padding: .5rem .75rem; }
        }
    </style>

    <div class="container-fluid"><br>
        <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
                <div class="row">
                    <div class="col-lg-12 mb-3 order-0">
                        <div class="card" style="border-left:4px solid #3b82f6;">
                            <div class="d-flex align-items-center row">
                                {{-- MOBILE FIX: col-12 col-md-7 --}}
                                <div class="col-12 col-md-7">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center gap-3 mb-3">
                                            <div style="width:44px;height:44px;background:#eff6ff;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                                <i class="fas fa-hand-wave" style="font-size:20px;color:#3b82f6;"></i>
                                            </div>
                                            <div>
                                                <h5 class="card-title mb-0" style="color:#0f172a;">Halo, {{ Auth::user()->name }}!</h5>
                                                <small style="color:#64748b;">Selamat datang kembali</small>
                                            </div>
                                        </div>

                                        @if (auth()->user()->role == 'siswa')
                                            <p class="mb-3" style="color:#64748b;font-size:14px;">Data kamu belum terisi sepenuhnya nih. Ayo isi terlebih dahulu!</p>
                                            <a href="{{ route('siswa.data_pribadi.create') }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i> Lengkapi Data
                                            </a>
                                        @elseif(in_array(auth()->user()->role, ['guru', 'hubin', 'kaprog']))
                                            <p class="mb-3" style="color:#64748b;font-size:14px;">Silakan lakukan absensi berbasis lokasi Anda</p>

                                            {{-- MOBILE FIX: ganti btn-group -> d-flex flex-wrap --}}
                                            <div class="d-flex flex-wrap gap-2 absen-action-group" role="group">
                                                @if (!$sudahAbsen)
                                                    <button id="absenMasukBtn" class="btn btn-primary">
                                                        <i class="fas fa-sign-in-alt"></i> Absen Masuk
                                                    </button>
                                                    <button id="manualLocationBtn" class="btn btn-outline-secondary">
                                                        <i class="fas fa-map-marker-alt"></i> Input Manual
                                                    </button>
                                                    <button id="izinBtn" class="btn btn-warning">
                                                        <i class="fas fa-file-alt"></i> Ajukan Izin/Sakit
                                                    </button>
                                                @elseif($statusHariIni === 'hadir' && !$sudahPulang)
                                                    <button id="absenPulangBtn" class="btn btn-info" data-absensi-id="{{ $todayAbsensi->id }}">
                                                        <i class="fas fa-sign-out-alt"></i> Absen Pulang
                                                    </button>
                                                @else
                                                    <button class="btn btn-success disabled">
                                                        <i class="fas fa-check-circle"></i> Absensi Selesai
                                                    </button>
                                                @endif
                                            </div>
                                        @else
                                            <p class="mb-2" style="color:#64748b;font-size:14px;">Selamat datang di sistem manajemen PKL.</p>
                                            <p style="font-size:13px;color:#94a3b8;">Login sebagai:
                                                <strong style="color:#3b82f6;">{{ ucfirst(auth()->user()->role) }}</strong>
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                {{-- MOBILE FIX: sembunyikan gambar di HP --}}
                                <div class="col-12 col-md-5 text-center welcome-image-col">
                                    <div class="card-body pb-0 px-0 px-md-4">
                                        <img src="{{ asset('snet/assets/img/illustrations/man-with-laptop-light.png') }}"
                                            height="140" alt="View Badge User"
                                            data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                            data-app-light-img="illustrations/man-with-laptop-light.png" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if (in_array(auth()->user()->role, ['guru', 'hubin', 'kaprog']))
                    <!-- Status Absensi Hari Ini -->
                    <div class="row mt-3">
                        <div class="col-lg-12 mb-4">
                            <div class="card">
                                {{-- MOBILE FIX: tambah class + flex-wrap --}}
                                <div class="card-header status-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-calendar-check" style="color:#3b82f6;"></i>
                                        <h5 class="mb-0">Status Absensi Hari Ini</h5>
                                    </div>
                                    <span class="badge" style="background:#eff6ff;color:#2563eb;font-size:12px;font-weight:700;padding:5px 12px;border-radius:20px;">
                                        <i class="fas fa-calendar me-1"></i>{{ date('d F Y') }}
                                    </span>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div id="statusAbsensiMasuk" class="text-center py-3">
                                                @if ($sudahAbsen)
                                                    <div style="color:#15803d;">
                                                        <div style="width:64px;height:64px;background:#f0fdf4;border-radius:16px;display:inline-flex;align-items:center;justify-content:center;margin-bottom:12px;">
                                                            <i class="fas fa-check-circle" style="font-size:28px;color:#22c55e;"></i>
                                                        </div>
                                                        <h5 style="font-weight:700;">Sudah Absen Masuk</h5>
                                                        <p style="font-size:13px;color:#64748b;">Status: <strong>{{ ucfirst($statusHariIni) }}</strong></p>
                                                        @if ($todayAbsensi->jam_masuk)
                                                            <p style="font-size:13px;color:#64748b;"><i class="fas fa-clock me-1"></i>{{ $todayAbsensi->jam_masuk }}</p>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div style="color:#b45309;">
                                                        <div style="width:64px;height:64px;background:#fffbeb;border-radius:16px;display:inline-flex;align-items:center;justify-content:center;margin-bottom:12px;">
                                                            <i class="fas fa-exclamation-circle" style="font-size:28px;color:#f59e0b;"></i>
                                                        </div>
                                                        <h5 style="font-weight:700;">Belum Absen Masuk</h5>
                                                        <p style="font-size:13px;color:#64748b;">Silakan lakukan absensi sekarang</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div id="statusAbsensiPulang" class="text-center py-3">
                                                @if ($sudahPulang)
                                                    <div style="color:#15803d;">
                                                        <div style="width:64px;height:64px;background:#f0fdf4;border-radius:16px;display:inline-flex;align-items:center;justify-content:center;margin-bottom:12px;">
                                                            <i class="fas fa-sign-out-alt" style="font-size:28px;color:#22c55e;"></i>
                                                        </div>
                                                        <h5 style="font-weight:700;">Sudah Absen Pulang</h5>
                                                        <p style="font-size:13px;color:#64748b;"><i class="fas fa-clock me-1"></i>{{ $todayAbsensi->jam_pulang }}</p>
                                                    </div>
                                                @elseif($statusHariIni === 'hadir')
                                                    <div style="color:#b45309;">
                                                        <div style="width:64px;height:64px;background:#fffbeb;border-radius:16px;display:inline-flex;align-items:center;justify-content:center;margin-bottom:12px;">
                                                            <i class="fas fa-hourglass-half" style="font-size:28px;color:#f59e0b;"></i>
                                                        </div>
                                                        <h5 style="font-weight:700;">Belum Absen Pulang</h5>
                                                        <p style="font-size:13px;color:#64748b;">Silakan absen pulang nanti</p>
                                                    </div>
                                                @else
                                                    <div style="color:#94a3b8;">
                                                        <div style="width:64px;height:64px;background:#f1f5f9;border-radius:16px;display:inline-flex;align-items:center;justify-content:center;margin-bottom:12px;">
                                                            <i class="fas fa-minus-circle" style="font-size:28px;color:#94a3b8;"></i>
                                                        </div>
                                                        <h5 style="font-weight:700;">Tidak Perlu Absen Pulang</h5>
                                                        <p style="font-size:13px;color:#94a3b8;">Status: {{ ucfirst($statusHariIni ?? '-') }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lokasi -->
                    <div class="row">
                        <div class="col-12 col-lg-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-location-arrow" style="color:#3b82f6;"></i>
                                        <h5 class="mb-0">Lokasi Anda Saat Ini</h5>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="lokasiInfo" class="text-center py-4">
                                        <div style="width:56px;height:56px;background:#f1f5f9;border-radius:14px;display:inline-flex;align-items:center;justify-content:center;margin-bottom:12px;">
                                            <i class="fas fa-map-marked-alt" style="font-size:24px;color:#94a3b8;"></i>
                                        </div>
                                        <p style="font-size:13.5px;color:#64748b;">Klik tombol absen untuk mendeteksi lokasi Anda</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-school" style="color:#3b82f6;"></i>
                                        <h5 class="mb-0">Lokasi Sekolah</h5>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3" style="gap:14px;">
                                        <div style="width:44px;height:44px;background:#eff6ff;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                            <i class="fas fa-map-pin" style="font-size:18px;color:#3b82f6;"></i>
                                        </div>
                                        <div>
                                            <div style="font-size:14px;font-weight:600;color:#0f172a;">SMKN 1 Kawali</div>
                                            <small style="color:#64748b;">Jl. Pendidikan No. 123, Kawali</small>
                                        </div>
                                    </div>
                                    <div class="alert alert-info d-flex align-items-center mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <div>Radius absensi: <strong>{{ $lokasiSekolah['radius'] }} meter</strong> dari lokasi sekolah</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Riwayat Absensi -->
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <div class="card">
                                {{-- MOBILE FIX: riwayat-header + flex-wrap --}}
                                <div class="card-header riwayat-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-history" style="color:#3b82f6;"></i>
                                        <h5 class="mb-0">Riwayat Absensi</h5>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-secondary"><i class="fas fa-filter"></i> Filter</button>
                                        <button class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i> Export</button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        {{-- MOBILE FIX: table-mobile-cards --}}
                                        <table class="table table-hover table-mobile-cards">
                                            <thead>
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Jam Masuk</th>
                                                    <th>Jam Pulang</th>
                                                    <th>Status</th>
                                                    <th>Lokasi</th>
                                                    <th>Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody id="riwayatAbsensi">
                                                @if ($riwayatAbsensi->count() > 0)
                                                    @foreach ($riwayatAbsensi as $absensi)
                                                        <tr>
                                                            {{-- MOBILE FIX: tambah data-label --}}
                                                            <td data-label="Tanggal">{{ Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y') }}</td>
                                                            <td data-label="Jam Masuk">{{ $absensi->jam_masuk ?? '-' }}</td>
                                                            <td data-label="Jam Pulang">{{ $absensi->jam_pulang ?? '-' }}</td>
                                                            <td data-label="Status">
                                                                <span class="badge bg-{{ $absensi->status == 'hadir' ? 'success' : ($absensi->status == 'sakit' ? 'warning' : ($absensi->status == 'izin' ? 'info' : 'secondary')) }}">
                                                                    {{ ucfirst($absensi->status) }}
                                                                </span>
                                                            </td>
                                                            <td data-label="Lokasi">
                                                                @if ($absensi->latitude && $absensi->longitude)
                                                                    {{ number_format($absensi->jarak, 2) }} meter dari sekolah
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td data-label="Keterangan">{{ $absensi->keterangan ?? '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    {{-- MOBILE FIX: class empty-state --}}
                                                    <tr class="empty-state">
                                                        <td colspan="6" class="text-center py-4">
                                                            <div style="width:56px;height:56px;background:#f1f5f9;border-radius:14px;display:inline-flex;align-items:center;justify-content:center;margin-bottom:10px;">
                                                                <i class="fas fa-inbox" style="font-size:22px;color:#94a3b8;"></i>
                                                            </div>
                                                            <p style="color:#94a3b8;font-size:13.5px;margin:0;">Belum ada riwayat absensi</p>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

{{-- ===== MODAL & SCRIPT TETAP SAMA seperti aslinya ===== --}}
