@extends('layout.main')

@section('content')
    <div class="container-fluid"><br>
        <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
                <div class="row">
                    <div class="col-lg-12 mb-3 order-0">
                        <div class="card" style="border-left:4px solid #3b82f6;">
                            <div class="d-flex align-items-center row">
                                <div class="col-sm-7">
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
                                            <a href="{{ route('siswa.data_pribadi.create') }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i> Lengkapi Data
                                            </a>
                                        @elseif(in_array(auth()->user()->role, ['guru', 'hubin', 'kaprog']))
                                            <p class="mb-3" style="color:#64748b;font-size:14px;">Silakan lakukan absensi berbasis lokasi Anda</p>

                                            <div class="btn-group gap-2" role="group">
                                                @if (!$sudahAbsen)
                                                    <button id="absenMasukBtn" class="btn btn-primary">
                                                        <i class="fas fa-sign-in-alt"></i>
                                                        Absen Masuk
                                                    </button>
                                                    <button id="manualLocationBtn" class="btn btn-outline-secondary">
                                                        <i class="fas fa-map-marker-alt"></i> Input Manual
                                                    </button>
                                                @elseif($statusHariIni === 'hadir' && !$sudahPulang)
                                                    <button id="absenPulangBtn" class="btn btn-info"
                                                        data-absensi-id="{{ $todayAbsensi->id }}">
                                                        <i class="fas fa-sign-out-alt"></i>
                                                        Absen Pulang
                                                    </button>
                                                @else
                                                    <button class="btn btn-success disabled">
                                                        <i class="fas fa-check-circle"></i>
                                                        Absensi Selesai
                                                    </button>
                                                @endif

                                                @if (!$sudahAbsen)
                                                    <button id="izinBtn" class="btn btn-warning">
                                                        <i class="fas fa-file-alt"></i>
                                                        Ajukan Izin/Sakit
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

                                <div class="col-sm-5 text-center text-sm-left">
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
                                <div class="card-header d-flex justify-content-between align-items-center">
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
                                        <div class="col-md-6">
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
                                        <div class="col-md-6">
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

                    <!-- Lokasi Saat Ini -->
                    <div class="row">
                        <div class="col-lg-6 mb-4">
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

                        <!-- Info Lokasi Sekolah -->
                        <div class="col-lg-6 mb-4">
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
                                    <div class="alert alert-info d-flex align-items-center">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <div>
                                            Radius absensi: <strong>{{ $lokasiSekolah['radius'] }} meter</strong> dari
                                            lokasi sekolah
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Riwayat Absensi -->
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-history" style="color:#3b82f6;"></i>
                                        <h5 class="mb-0">Riwayat Absensi</h5>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-filter"></i> Filter
                                        </button>
                                        <button class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download"></i> Export
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive text-nowrap">
                                        <table class="table table-hover">
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
                                                            <td>{{ Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y') }}
                                                            </td>
                                                            <td>{{ $absensi->jam_masuk ?? '-' }}</td>
                                                            <td>{{ $absensi->jam_pulang ?? '-' }}</td>
                                                            <td>
                                                                <span
                                                                    class="badge bg-{{ $absensi->status == 'hadir' ? 'success' : ($absensi->status == 'sakit' ? 'warning' : ($absensi->status == 'izin' ? 'info' : 'secondary')) }}">
                                                                    {{ ucfirst($absensi->status) }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                @if ($absensi->latitude && $absensi->longitude)
                                                                    {{ number_format($absensi->jarak, 2) }} meter dari
                                                                    sekolah
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td>{{ $absensi->keterangan ?? '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
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

                @if (auth()->user()->role == 'siswa')
                    {{-- Konten untuk siswa (tetap sama) --}}
                @endif
            </div>
        </div>
    </div>
@endsection

<!-- Modal untuk Izin/Sakit -->
<div class="modal fade" id="izinModal" tabindex="-1" aria-labelledby="izinModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="izinModalLabel">Form Izin/Sakit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="izinForm">
                    <div class="mb-3">
                        <label for="statusIzin" class="form-label">Status Absensi</label>
                        <select class="form-select" id="statusIzin" required>
                            <option value="" selected disabled>Pilih status...</option>
                            <option value="sakit">Sakit</option>
                            <option value="izin">Izin</option>
                            <option value="alpha">Alpha</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="alasanIzin" class="form-label">Alasan</label>
                        <textarea class="form-control" id="alasanIzin" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="buktiFile" class="form-label">Upload Bukti (Opsional)</label>
                        <input type="file" class="form-control" id="buktiFile" accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">Format: PDF, JPG, PNG (Max: 2MB)</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="submitIzin">Kirim</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Input Lokasi Manual -->
<div class="modal fade" id="manualLocationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Input Lokasi Manual</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Latitude</label>
                    <input type="text" class="form-control" id="manualLat" placeholder="Contoh: -7.161891">
                </div>
                <div class="mb-3">
                    <label class="form-label">Longitude</label>
                    <input type="text" class="form-control" id="manualLng" placeholder="Contoh: 108.328864">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="submitManualLocation">Simpan</button>
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert --}}
@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000,
            customClass: {
                popup: 'animate__animated animate__fadeInDown'
            }
        });
    </script>
@endif

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            text: "{{ session('error') }}",
            showConfirmButton: false,
            timer: 2500,
            customClass: {
                popup: 'animate__animated animate__shakeX'
            }
        });
    </script>
@endif

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@if (in_array(auth()->user()->role, ['guru', 'hubin', 'kaprog']))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== SCRIPT LOADED ===');

            // Koordinat lokasi sekolah
            var sekolahLat = {{ $lokasiSekolah['latitude'] ?? -7.161891 }};
            var sekolahLng = {{ $lokasiSekolah['longitude'] ?? 108.328864 }};
            var radiusAbsensi = {{ $lokasiSekolah['radius'] ?? 1000 }};

            console.log('Lokasi sekolah:', sekolahLat, sekolahLng, radiusAbsensi);

            // Inisialisasi modal
            var izinModal = null;
            var manualLocationModal = null;

            try {
                var izinModalEl = document.getElementById('izinModal');
                var manualModalEl = document.getElementById('manualLocationModal');

                if (izinModalEl && typeof bootstrap !== 'undefined') {
                    izinModal = new bootstrap.Modal(izinModalEl);
                }
                if (manualModalEl && typeof bootstrap !== 'undefined') {
                    manualLocationModal = new bootstrap.Modal(manualModalEl);
                }
                console.log('Modal initialized:', izinModal !== null, manualLocationModal !== null);
            } catch (error) {
                console.error('Error inisialisasi modal:', error);
            }

            // Fungsi hitung jarak
            function hitungJarak(lat1, lon1, lat2, lon2) {
                var R = 6371e3;
                var rad = Math.PI / 180;
                var phi1 = lat1 * rad;
                var phi2 = lat2 * rad;
                var deltaPhi = (lat2 - lat1) * rad;
                var deltaLambda = (lon2 - lon1) * rad;

                var a = Math.sin(deltaPhi / 2) * Math.sin(deltaPhi / 2) +
                    Math.cos(phi1) * Math.cos(phi2) *
                    Math.sin(deltaLambda / 2) * Math.sin(deltaLambda / 2);
                var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

                return R * c;
            }

            // Fungsi reset button
            function resetButton(btnId, html) {
                var btn = document.getElementById(btnId);
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = html;
                }
            }

            // Fungsi simpan absensi
            function simpanAbsensiKeDatabase(data) {
                if (typeof Swal === 'undefined') {
                    alert('SweetAlert2 belum dimuat');
                    return;
                }

                Swal.fire({
                    title: 'Menyimpan data...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: function() {
                        Swal.showLoading();
                    }
                });

                var csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    Swal.close();
                    Swal.fire('Error', 'CSRF token tidak ditemukan', 'error');
                    return;
                }

                fetch('/absensi-guru', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(function(response) {
                        if (!response.ok) {
                            throw new Error('HTTP error ' + response.status);
                        }
                        return response.json();
                    })
                    .then(function(result) {
                        Swal.close();
                        if (result.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Absensi Berhasil!',
                                text: result.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(function() {
                                location.reload();
                            });
                        } else {
                            throw new Error(result.message || 'Gagal menyimpan');
                        }
                    })
                    .catch(function(error) {
                        Swal.close();
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: error.message || 'Terjadi kesalahan saat menyimpan data'
                        });
                        resetButton('absenMasukBtn', '<i class="fas fa-sign-in-alt me-2"></i>Absen Masuk');
                    });
            }

            // Fungsi absensi dengan lokasi
            function lakukanAbsensiDenganLokasi() {
                var btn = document.getElementById('absenMasukBtn');
                if (!btn) {
                    console.error('Tombol absen masuk tidak ditemukan');
                    return;
                }

                console.log('Melakukan absensi...');

                // Cek HTTPS kecuali localhost
                var isLocalhost = location.hostname === 'localhost' ||
                    location.hostname === '127.0.0.1' ||
                    location.hostname.indexOf('localhost') > -1;

                if (location.protocol !== 'https:' && !isLocalhost) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Gunakan Input Manual',
                            html: 'Geolocation memerlukan koneksi HTTPS.<br>Silakan gunakan tombol <strong>"Input Manual"</strong>.',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        alert('Geolocation memerlukan HTTPS. Gunakan Input Manual.');
                    }
                    return;
                }

                if (!navigator.geolocation) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Browser Tidak Mendukung',
                            html: 'Browser Anda tidak mendukung geolocation.<br>Silakan gunakan tombol <strong>"Input Manual"</strong>.'
                        });
                    } else {
                        alert('Browser tidak mendukung geolocation. Gunakan Input Manual.');
                    }
                    return;
                }

                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mendeteksi lokasi...';

                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        var latitude = position.coords.latitude;
                        var longitude = position.coords.longitude;
                        var jarak = hitungJarak(latitude, longitude, sekolahLat, sekolahLng);

                        console.log('Posisi:', latitude, longitude, 'Jarak:', jarak);

                        var lokasiInfo = document.getElementById('lokasiInfo');
                        if (lokasiInfo) {
                            lokasiInfo.innerHTML =
                                '<div class="text-center">' +
                                '<i class="fas fa-map-marker-alt fa-3x text-success mb-3"></i>' +
                                '<h6>Lokasi Terdeteksi</h6>' +
                                '<p class="mb-1">Lat: ' + latitude.toFixed(6) + '</p>' +
                                '<p class="mb-1">Lng: ' + longitude.toFixed(6) + '</p>' +
                                '<p class="mb-0">Jarak: <strong>' + jarak.toFixed(2) + ' meter</strong></p>' +
                                '</div>';
                        }

                        if (jarak <= radiusAbsensi) {
                            var now = new Date();
                            var hours = String(now.getHours()).padStart(2, '0');
                            var minutes = String(now.getMinutes()).padStart(2, '0');
                            var seconds = String(now.getSeconds()).padStart(2, '0');
                            var jamMasuk = hours + ':' + minutes + ':' + seconds;

                            var year = now.getFullYear();
                            var month = String(now.getMonth() + 1).padStart(2, '0');
                            var day = String(now.getDate()).padStart(2, '0');
                            var tanggal = year + '-' + month + '-' + day;

                            var absensiData = {
                                latitude: latitude,
                                longitude: longitude,
                                jarak: jarak,
                                status: 'hadir',
                                jam_masuk: jamMasuk,
                                keterangan: 'Absen masuk berbasis lokasi',
                                tanggal: tanggal
                            };

                            console.log('Data absensi:', absensiData);
                            simpanAbsensiKeDatabase(absensiData);
                        } else {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Di Luar Radius',
                                    text: 'Anda berada ' + jarak.toFixed(2) +
                                        ' meter dari sekolah (max: ' + radiusAbsensi + 'm)'
                                });
                            } else {
                                alert('Anda di luar radius: ' + jarak.toFixed(2) + ' meter');
                            }
                            resetButton('absenMasukBtn', '<i class="fas fa-sign-in-alt me-2"></i>Absen Masuk');
                        }
                    },
                    function(error) {
                        console.error('Geolocation error:', error);

                        var errorMsg = 'Tidak dapat mendeteksi lokasi';
                        if (error.code === 1) {
                            errorMsg = 'Anda menolak izin lokasi. Silakan gunakan "Input Manual".';
                        } else if (error.code === 2) {
                            errorMsg = 'Lokasi tidak tersedia. Silakan gunakan "Input Manual".';
                        } else if (error.code === 3) {
                            errorMsg = 'Timeout. Silakan coba lagi atau gunakan "Input Manual".';
                        }

                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error Lokasi',
                                text: errorMsg
                            });
                        } else {
                            alert(errorMsg);
                        }
                        resetButton('absenMasukBtn', '<i class="fas fa-sign-in-alt me-2"></i>Absen Masuk');
                    }, {
                        enableHighAccuracy: true,
                        timeout: 15000,
                        maximumAge: 0
                    }
                );
            }

            // Fungsi absen pulang
            function lakukanAbsenPulang() {
                var btn = document.getElementById('absenPulangBtn');
                if (!btn) return;

                var absensiId = btn.getAttribute('data-absensi-id');
                if (!absensiId) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error', 'ID absensi tidak ditemukan', 'error');
                    }
                    return;
                }

                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

                var now = new Date();
                var hours = String(now.getHours()).padStart(2, '0');
                var minutes = String(now.getMinutes()).padStart(2, '0');
                var seconds = String(now.getSeconds()).padStart(2, '0');
                var jamPulang = hours + ':' + minutes + ':' + seconds;

                var data = {
                    jam_pulang: jamPulang
                };

                var csrfToken = document.querySelector('meta[name="csrf-token"]');

                fetch('/absensi-guru/' + absensiId + '/pulang', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(result) {
                        if (result.success) {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Absen pulang berhasil',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(function() {
                                    location.reload();
                                });
                            } else {
                                alert('Absen pulang berhasil');
                                location.reload();
                            }
                        } else {
                            throw new Error(result.message);
                        }
                    })
                    .catch(function(error) {
                        console.error('Error:', error);
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Error', error.message || 'Gagal menyimpan', 'error');
                        } else {
                            alert('Error: ' + error.message);
                        }
                        resetButton('absenPulangBtn', '<i class="fas fa-sign-out-alt me-2"></i>Absen Pulang');
                    });
            }

            // Event listeners
            var absenMasukBtn = document.getElementById('absenMasukBtn');
            if (absenMasukBtn) {
                console.log('Tombol absen masuk ditemukan');
                absenMasukBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Absen masuk diklik');
                    lakukanAbsensiDenganLokasi();
                });
            } else {
                console.log('Tombol absen masuk TIDAK ditemukan');
            }

            var absenPulangBtn = document.getElementById('absenPulangBtn');
            if (absenPulangBtn) {
                console.log('Tombol absen pulang ditemukan');
                absenPulangBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Absen pulang diklik');
                    lakukanAbsenPulang();
                });
            }

            var izinBtn = document.getElementById('izinBtn');
            if (izinBtn && izinModal) {
                console.log('Tombol izin ditemukan');
                izinBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Izin diklik');
                    izinModal.show();
                });
            }

            var manualLocationBtn = document.getElementById('manualLocationBtn');
            if (manualLocationBtn && manualLocationModal) {
                console.log('Tombol manual location ditemukan');
                manualLocationBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Manual location diklik');
                    manualLocationModal.show();
                });
            }

            // Submit manual location
            var submitManualBtn = document.getElementById('submitManualLocation');
            if (submitManualBtn) {
                submitManualBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    var latInput = document.getElementById('manualLat');
                    var lngInput = document.getElementById('manualLng');
                    var lat = parseFloat(latInput.value);
                    var lng = parseFloat(lngInput.value);

                    if (isNaN(lat) || isNaN(lng)) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Error', 'Masukkan koordinat yang valid', 'error');
                        } else {
                            alert('Masukkan koordinat yang valid');
                        }
                        return;
                    }

                    var jarak = hitungJarak(lat, lng, sekolahLat, sekolahLng);

                    if (jarak <= radiusAbsensi) {
                        var now = new Date();
                        var hours = String(now.getHours()).padStart(2, '0');
                        var minutes = String(now.getMinutes()).padStart(2, '0');
                        var seconds = String(now.getSeconds()).padStart(2, '0');
                        var jamMasuk = hours + ':' + minutes + ':' + seconds;

                        var year = now.getFullYear();
                        var month = String(now.getMonth() + 1).padStart(2, '0');
                        var day = String(now.getDate()).padStart(2, '0');
                        var tanggal = year + '-' + month + '-' + day;

                        var absensiData = {
                            latitude: lat,
                            longitude: lng,
                            jarak: jarak,
                            status: 'hadir',
                            jam_masuk: jamMasuk,
                            keterangan: 'Absen masuk dengan input manual',
                            tanggal: tanggal
                        };

                        if (manualLocationModal) manualLocationModal.hide();
                        simpanAbsensiKeDatabase(absensiData);
                    } else {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Di Luar Radius',
                                text: 'Jarak ' + jarak.toFixed(2) + ' meter dari sekolah (max: ' +
                                    radiusAbsensi + 'm)'
                            });
                        } else {
                            alert('Jarak terlalu jauh: ' + jarak.toFixed(2) + ' meter');
                        }
                    }
                });
            }

            // Submit izin
            var submitIzinBtn = document.getElementById('submitIzin');
            if (submitIzinBtn) {
                submitIzinBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    var statusIzin = document.getElementById('statusIzin').value;
                    var alasanIzin = document.getElementById('alasanIzin').value;
                    var buktiFile = document.getElementById('buktiFile').files[0];

                    if (!statusIzin || !alasanIzin) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire('Error', 'Lengkapi form terlebih dahulu', 'warning');
                        } else {
                            alert('Lengkapi form terlebih dahulu');
                        }
                        return;
                    }

                    var formData = new FormData();
                    formData.append('status', statusIzin);
                    formData.append('alasan', alasanIzin);

                    var now = new Date();
                    var year = now.getFullYear();
                    var month = String(now.getMonth() + 1).padStart(2, '0');
                    var day = String(now.getDate()).padStart(2, '0');
                    var tanggal = year + '-' + month + '-' + day;

                    formData.append('tanggal', tanggal);
                    formData.append('keterangan', 'Absen ' + statusIzin);

                    if (buktiFile) {
                        formData.append('bukti_file', buktiFile);
                    }

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Mengirim...',
                            allowOutsideClick: false,
                            didOpen: function() {
                                Swal.showLoading();
                            }
                        });
                    }

                    var csrfToken = document.querySelector('meta[name="csrf-token"]');

                    fetch('/absensi-guru', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(function(response) {
                            return response.json();
                        })
                        .then(function(result) {
                            if (typeof Swal !== 'undefined') {
                                Swal.close();
                            }
                            if (result.success) {
                                if (izinModal) izinModal.hide();
                                document.getElementById('izinForm').reset();
                                if (typeof Swal !== 'undefined') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: 'Data izin berhasil disimpan',
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(function() {
                                        location.reload();
                                    });
                                } else {
                                    alert('Data izin berhasil disimpan');
                                    location.reload();
                                }
                            } else {
                                throw new Error(result.message);
                            }
                        })
                        .catch(function(error) {
                            if (typeof Swal !== 'undefined') {
                                Swal.close();
                                Swal.fire('Error', error.message || 'Gagal mengirim data', 'error');
                            } else {
                                alert('Error: ' + error.message);
                            }
                        });
                });
            }

            console.log('=== SCRIPT READY ===');
        });
    </script>
@endif

