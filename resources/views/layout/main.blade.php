<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <link rel="icon" target="_blank" href="{{ asset('images/logo.png') }}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="" />
    <meta name="theme-color" content="#0d6efd">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('snet/assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('snet/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('snet/assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('snet/assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('snet/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('snet/assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <!-- Helpers -->
    <script src="{{ asset('snet/assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('snet/assets/js/config.js') }}"></script>

    <!-- Custom App CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- ============================================================
         PERBAIKAN UI (menimpa aturan bermasalah di css/app.css)
         ============================================================ --}}
    <style id="perbaikan-ui">
        /* 1) TABEL: app.css memberi .table-responsive "overflow: hidden"
              sehingga isi tabel yang lebar terpotong dan tidak bisa di-scroll. */
        .table-responsive {
            overflow-x: auto !important;
            -webkit-overflow-scrolling: touch;
        }
        .table-responsive::-webkit-scrollbar { height: 10px; }
        .table-responsive::-webkit-scrollbar-track { background: #e2e8f0; border-radius: 10px; }
        .table-responsive::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 10px; }

        /* 2) TOMBOL PANAH SIDEBAR ( < dan > ) */
        .sidebar-toggle-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            padding: 0;
            margin-left: auto;
            background: #000;
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 6px;
            color: #cbd5e1;
            cursor: pointer;
            transition: left .3s ease, background-color .2s ease, color .2s ease, border-color .2s ease;
        }
        .sidebar-toggle-btn:hover {
            background: #1e293b;
            border-color: rgba(255, 255, 255, .25);
            color: #f1f5f9;
        }
        .sidebar-toggle-btn i {
            font-size: 20px;
            line-height: 1;
            transition: transform .3s ease;
        }

        /* 3) SIDEBAR (desktop >= 1200px)
              Sidebar tetap (fixed) seperti bawaan template, posisi konten TIDAK diubah.
              Saat di-minimize: sidebar meluncur keluar, konten melebar, tombol panah
              berputar jadi ">" dan logo muncul di navbar (fade-in). */
        .sidebar-toggle-floating { display: none; }
        .navbar-brand-mini { display: none; }

        @media (min-width: 1200px) {
            #layout-menu,
            .layout-menu {
                position: fixed !important;
                top: 0;
                left: 0;
                height: 100vh !important;
                display: flex;
                flex-direction: column;
                visibility: visible;
                transition: left .3s ease, visibility 0s linear 0s;
            }
            #layout-menu .menu-inner { flex: 1 1 auto; min-height: 0; }

            .layout-page { transition: padding-left .3s ease; }

            /* tombol bawaan di dalam sidebar hanya untuk layar kecil */
            .sidebar-toggle-mobile { display: none !important; }

            .sidebar-toggle-floating {
                display: flex;
                position: fixed;
                top: 24px;
                left: calc(var(--sidebar-width, 260px) - 19px);
                margin: 0;
                z-index: 1090;
                box-shadow: 0 2px 8px rgba(0, 0, 0, .25);
            }

            html.sidebar-minimized #layout-menu,
            html.sidebar-minimized .layout-menu {
                left: calc(-1 * var(--sidebar-width, 260px) - 40px) !important;
                visibility: hidden;
                transition: left .3s ease, visibility 0s linear .3s;
            }
            html.sidebar-minimized .layout-page { padding-left: 56px !important; }
            html.sidebar-minimized .sidebar-toggle-floating { left: 6px; }
            html.sidebar-minimized .sidebar-toggle-floating i { transform: rotate(180deg); }

            /* Logo + teks di navbar: tersembunyi saat sidebar tampil, fade-in saat sidebar ditutup */
            .navbar-brand-mini {
                display: flex;
                align-items: center;
                gap: 10px;
                flex: 0 0 auto;
                margin-right: 16px;
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
                transform: translateX(-8px);
                transition: opacity .3s ease, transform .3s ease, visibility 0s linear .3s;
            }
            .navbar-brand-mini img {
                width: 36px;
                height: 36px;
                border-radius: 10px;
                box-shadow: 0 4px 12px rgba(59, 130, 246, .3);
            }
            .navbar-brand-mini span {
                font-size: 15px;
                font-weight: 700;
                letter-spacing: .5px;
                line-height: 1.2;
                text-transform: uppercase;
                white-space: nowrap;
                color: var(--text-primary, #0f172a);
            }
            html.sidebar-minimized .navbar-brand-mini {
                opacity: 1;
                visibility: visible;
                pointer-events: auto;
                transform: none;
                transition: opacity .4s ease .2s, transform .4s ease .2s, visibility 0s linear 0s;
            }
        }

        /* 4) TITIK SUBMENU: app.css memampatkan padding submenu sehingga titik bawaan
              template menimpa huruf (Siswa, GTK, Nilai, dst). */
        .layout-menu .menu-sub .menu-item > .menu-link {
            position: relative;
            padding: 8px 12px 8px 30px !important;
        }
        .layout-menu .menu-sub .menu-item > .menu-link::before {
            content: '' !important;
            position: absolute !important;
            left: 13px !important;
            top: 50% !important;
            width: 6px !important;
            height: 6px !important;
            margin: 0 !important;
            border: 0 !important;
            border-radius: 50% !important;
            background-color: currentColor !important;
            opacity: .45;
            transform: translateY(-50%) !important;
        }
        .layout-menu .menu-sub .menu-item > .menu-link:hover::before { opacity: 1; }
        .layout-menu .menu-sub .menu-item.active > .menu-link::before {
            display: block !important;
            background-color: var(--accent, #3b82f6) !important;
            opacity: 1;
        }

        /* 5) TOMBOL AKSI (ikon): ukuran sama untuk pensil, mata, dan hapus + jarak antar tombol. */
        .aksi-icon-group {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: nowrap;
            gap: 8px;
        }
        .aksi-icon-group form { display: flex !important; margin: 0; }
        .aksi-icon-group .btn {
            width: 34px;
            height: 34px;
            padding: 0 !important;
            justify-content: center;
            flex-shrink: 0;
        }
        .aksi-icon-group .btn i { font-size: 15px; line-height: 1; }

        /* 6) TEKS MELUBER: sel tabel ber-max-width (mis. Saran/Catatan di monitoring) mewarisi
              "nowrap" dari .text-nowrap sehingga teksnya menimpa kolom sebelahnya. Dibuat turun baris. */
        td[style*="max-width"] {
            white-space: normal !important;
            word-break: break-word;
        }

        /* 7) TOMBOL AKSI DI TABEL: jarak sedikit antar tombol, tombol ikon seragam ukurannya. */
        td .btn-group { display: inline-flex; gap: 6px; }
        td .btn-group > .btn {
            margin-left: 0 !important;
            border-radius: var(--bs-btn-border-radius, .375rem) !important;
        }
        td .gap-1:has(> .btn),
        td .gap-1:has(> form) { gap: 8px !important; }
        td > .btn + .btn,
        td > .btn + form,
        td > form + .btn,
        td > form + form,
        td form > .btn + .btn,
        td .d-flex:not([class*="gap-"]) > .btn + .btn,
        td .d-flex:not([class*="gap-"]) > .btn + form,
        td .d-flex:not([class*="gap-"]) > form + .btn { margin-left: 6px; }
        td .btn.me-1 { margin-right: 8px !important; }

        /* Tombol HANYA berisi ikon (ditandai skrip di bawah dengan class .btn-icon-only).
           Tombol berisi ikon + teks (mis. "Lihat", "Export") tidak ikut diubah ukurannya. */
        td .btn.btn-icon-only {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            padding: 0 !important;
            vertical-align: middle;
            flex-shrink: 0;
        }
        td .btn.btn-icon-only > i { font-size: 15px; line-height: 1; margin: 0 !important; }
        td form.aksi-form { display: inline-flex; margin: 0; vertical-align: middle; }

        /* 9) NAVBAR ATAS: menempel di atas saat halaman di-scroll. */
        #layout-navbar {
            position: sticky;
            top: 10px;
            z-index: 1020;
            box-shadow: var(--shadow-sm), 0 -10px 0 0 var(--body-bg) !important;
        }

        /* 8) JUDUL BANNER DASHBOARD: .card-title di app.css memaksa warna gelap,
              padahal di banner biru harus putih. */
        .card-title.text-white { color: #fff !important; }
    </style>

    {{-- Tombol minimize sidebar di desktop (>= 1200px). Layar kecil tetap memakai bawaan template. --}}
    <script>
        (function () {
            var root = document.documentElement;
            try {
                if (localStorage.getItem('tr-sidebar-minimized') === '1') root.classList.add('sidebar-minimized');
            } catch (e) {}
            function rapikanTombol() {
                document.querySelectorAll('td .btn:not(.btn-icon-only)').forEach(function (b) {
                    if (b.querySelector('i') && b.textContent.replace(/\s+/g, '') === '') {
                        b.classList.add('btn-icon-only');
                        if (b.parentElement && b.parentElement.tagName === 'FORM') {
                            b.parentElement.classList.add('aksi-form');
                        }
                    }
                });
            }
            document.addEventListener('DOMContentLoaded', function () {
                rapikanTombol();
                var t;
                new MutationObserver(function () {
                    clearTimeout(t);
                    t = setTimeout(rapikanTombol, 80);
                }).observe(document.body, { childList: true, subtree: true });
            });
            document.addEventListener('click', function (e) {
                var t = e.target.closest ? e.target.closest('.layout-menu-toggle') : null;
                if (!t || window.innerWidth < 1200) return;
                e.preventDefault();
                e.stopImmediatePropagation();
                var on = root.classList.toggle('sidebar-minimized');
                try { localStorage.setItem('tr-sidebar-minimized', on ? '1' : '0'); } catch (err) {}
            }, true);
        })();
    </script>

    {{-- CSS tambahan per halaman (@push('styles')) --}}
    @stack('styles')
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <!-- Tombol minimize sidebar (desktop). Sengaja di luar sidebar supaya tetap terlihat saat sidebar ditutup. -->
        <button type="button" id="sidebarToggleBtn" class="layout-menu-toggle sidebar-toggle-btn sidebar-toggle-floating"
            aria-label="Sembunyikan / tampilkan sidebar" title="Sembunyikan / tampilkan sidebar">
            <i class="bx bx-chevron-left"></i>
        </button>

        <div class="layout-container">
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="#" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <img src="{{ asset('images/logoo.png') }}" width="40px" height="40px" alt="">
                        </span>
                        <span class="app-brand-text demo fw-bolder ms-2" style="font-size: 18px;">TATA RIKSA
                            K-ONE</span>
                    </a>

                    <button type="button" class="layout-menu-toggle sidebar-toggle-btn sidebar-toggle-mobile"
                        aria-label="Tutup menu">
                        <i class="bx bx-chevron-left"></i>
                    </button>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Dashboard -->
                    <li class="menu-item {{ Request::is('dashboard', 'dashboard/*') ? 'active' : '' }}">
                        <a href="@if (auth()->user()->role == 'guru') /dashboard/guru
                        @elseif(auth()->user()->role == 'siswa') /dashboard/siswa
                        @elseif(auth()->user()->role == 'hubin') /dashboard/hubin
                        @elseif(auth()->user()->role == 'kaprog') /dashboard/kaprog
                        @elseif(auth()->user()->role == 'ppkl') /dashboard/ppkl
                        @elseif(auth()->user()->role == 'psekolah') /dashboard/psekolah
                        @elseif(auth()->user()->role == 'iduka') /dashboard/iduka
                        @elseif(auth()->user()->role == 'orangtua') /dashboard/orangtua
                        @elseif(auth()->user()->role == 'persuratan') /dashboard/persuratan
                        @elseif(auth()->user()->role == 'kepsek') /dashboard/kepsek
                        @elseif(auth()->user()->role == 'pendamping') /dashboard/pendamping @endif"
                            class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Analytics">Dashboard</div>
                        </a>
                    </li>

                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Data</span>
                    </li>

                    <!-- Layouts -->
                    @if (in_array(auth()->user()->role, ['hubin', 'psekolah']))
                        <li class="menu-item {{ Request::routeIs('hubin.iduka.index') ? 'active' : '' }}">
                            <a href="{{ route('hubin.iduka.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-building"></i>
                                <div data-i18n="Basic">Data Institusi</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::routeIs('kelas.index') ? 'active' : '' }}">
                            <a href="{{ route('kelas.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-user"></i>
                                <div data-i18n="Basic">Data Siswa</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::routeIs('naik-kelas.index') ? 'active' : '' }}">
                            <a href="{{ route('naik-kelas.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-up-arrow-alt"></i>
                                <div data-i18n="Basic">Naik Kelas & Alumni</div>
                            </a>
                        </li>

                        <li
                            class="menu-item {{ Request::routeIs('admin.pemantauanGtk', 'admin.gtkKependidikan') ? 'active open' : '' }}">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-group"></i>
                                <div data-i18n="Layouts">GTK</div>
                            </a>

                            <ul class="menu-sub">
                                <li class="menu-item {{ Request::routeIs('guru.index') ? 'active' : '' }}">
                                    <a href="{{ route('guru.index') }}" class="menu-link">
                                        <div data-i18n="Without menu">Guru</div>
                                    </a>
                                </li>

                                <li class="menu-item {{ Request::routeIs('tk.index') ? 'active' : '' }}">
                                    <a href="{{ route('tk.index') }}" class="menu-link">
                                        <div data-i18n="Without menu">Tenaga Kependidikan</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if (auth()->user()->role == 'iduka')
                        <li class="menu-item {{ Request::routeIs('iduka.pribadi') ? 'active' : '' }}">
                            <a href="{{ route('iduka.pribadi') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-building"></i>
                                <div data-i18n="Basic">Data Pribadi Institusi</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::routeIs('data.institusi') ? 'active' : '' }}">
                            <a href="{{ route('data.institusi') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-buildings"></i>
                                <div data-i18n="Basic">Data Institusi / Perusahaan</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::routeIs('tp.iduka') ? 'active' : '' }}">
                            <a href="{{ route('tp.iduka') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-map-pin"></i>
                                <div data-i18n="Basic">TP</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::is('iduka/pindah-pkl*') ? 'active open' : '' }}">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-transfer"></i>
                                <div data-i18n="Pindah PKL">Pindah PKL</div>
                            </a>

                            <ul class="menu-sub">
                                <li
                                    class="menu-item {{ Request::routeIs('iduka.pindah_pkl.index') ? 'active' : '' }}">
                                    <a href="{{ route('iduka.pindah_pkl.index') }}" class="menu-link">
                                        <div data-i18n="Pengajuan Masuk">Pengajuan Masuk</div>
                                    </a>
                                </li>

                                <li
                                    class="menu-item {{ Request::routeIs('iduka.pindah_pkl.riwayat') ? 'active' : '' }}">
                                    <a href="{{ route('iduka.pindah_pkl.riwayat') }}" class="menu-link">
                                        <div data-i18n="Riwayat">Riwayat</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    {{-- sementara akses dari hubin di blok dulu --}}
                    @if (in_array(auth()->user()->role, ['iduka', 'guru', 'kaprog']))
                        <li class="menu-item {{ Request::routeIs('approval.index') ? 'active' : '' }}">
                            <a href="{{ route('approval.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-check-circle"></i>
                                <div data-i18n="Basic">Konfir Journal</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::routeIs('konfir.absen.index') ? 'active' : '' }}">
                            <a href="{{ route('konfir.absen.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-check-square"></i>
                                <div data-i18n="Basic">Absensi</div>
                            </a>
                        </li>

                        <li
                            class="menu-item
{{ Request::routeIs('tujuan-pembelajaran.*') ||
Request::routeIs('indikator.*') ||
Request::routeIs('penilaian.*') ||
Request::routeIs('nilai-akhir.*')
    ? 'active open'
    : '' }}">

                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-bar-chart"></i>
                                <div>Nilai</div>
                            </a>

                            <ul class="menu-sub">

                                {{-- Tujuan Pembelajaran --}}
                                <li
                                    class="menu-item
    {{ Request::routeIs('tujuan-pembelajaran.*') ? 'active' : '' }}
    {{ !in_array(auth()->user()->role, ['hubin', 'kaprog']) ? 'd-none' : '' }}">

                                    <a href="{{ route('tujuan-pembelajaran.index') }}" class="menu-link">
                                        <div>Tujuan Pembelajaran</div>
                                    </a>

                                </li>

                                {{-- Indikator Penilaian --}}
                                <li
                                    class="menu-item
    {{ Request::routeIs('indikator.*') ? 'active' : '' }}
    {{ !in_array(auth()->user()->role, ['hubin', 'kaprog']) ? 'd-none' : '' }}">

                                    <a href="{{ route('indikator.index') }}" class="menu-link">
                                        <div>Indikator Penilaian</div>
                                    </a>

                                </li>


                                <li class="menu-item {{ Request::routeIs('penilaian.*') ? 'active' : '' }}">
                                    <a href="{{ route('penilaian.index') }}" class="menu-link">
                                        <div>Penilaian</div>
                                    </a>
                                </li>

                                <li class="menu-item {{ Request::routeIs('nilai-akhir.*') ? 'active' : '' }}">
                                    <a href="{{ route('nilai-akhir.index') }}" class="menu-link">
                                        <div>Nilai Akhir</div>
                                    </a>
                                </li>

                            </ul>
                        </li>
                    @endif


                    @if (in_array(auth()->user()->role, ['guru', 'kaprog']))
                        <li class="menu-item {{ Request::routeIs('pembimbing.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('pembimbing.dashboard') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-group"></i>
                                <div data-i18n="Basic">Siswa Dibimbing</div>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->role == 'siswa')
                        <li class="menu-item {{ Request::routeIs('siswa.data_pribadi.create') ? 'active' : '' }}">
                            <a href="{{ route('siswa.data_pribadi.create') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-user"></i>
                                <div data-i18n="Basic">Data Pribadi Siswa</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::routeIs('absensi.index') ? 'active' : '' }}">
                            <a href="{{ route('absensi.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                                <div data-i18n="Basic">Absensi</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::routeIs('jurnal.index') ? 'active' : '' }}">
                            <a href="{{ route('jurnal.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-book"></i>
                                <div data-i18n="Basic">Jurnal</div>
                            </a>
                        </li>
                    @endif

                    @if (in_array(auth()->user()->role, ['kaprog', 'guru', 'hubin', 'kepsek']))
                        <li class="menu-item {{ Request::routeIs('monitoring.index') ? 'active' : '' }}">
                            <a href="{{ route('monitoring.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-search"></i>
                                <div data-i18n="Basic">monitoring</div>
                            </a>
                        </li>
                        <li class="menu-item {{ Request::routeIs('rekap.absensi.index') ? 'active' : '' }}">
                            <a href="{{ route('rekap.absensi.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-calendar-event"></i>
                                <div data-i18n="Basic">Rekap Absensi</div>
                            </a>
                        </li>
                    @endif

                    @if (in_array(auth()->user()->role, ['hubin', 'psekolah']))
                        <li class="menu-item {{ Request::routeIs('data.siswa') ? 'active' : '' }}">
                            <a href="{{ route('data.siswa') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-user"></i>
                                <div data-i18n="Basic">Data siswa</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::routeIs('proker.index') ? 'active' : '' }}">
                            <a href="{{ route('proker.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-bookmark"></i>
                                <div data-i18n="Basic">Program Keahlian</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::routeIs('konke.index') ? 'active' : '' }}">
                            <a href="{{ route('konke.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-bookmark"></i>
                                <div data-i18n="Basic">Konsentrasi Keahlian</div>
                            </a>
                        </li>
                    @endif

                    @if (in_array(auth()->user()->role, ['hubin', 'kepsek']))
                        <li class="menu-item {{ Request::routeIs('data-absen.index') ? 'active' : '' }}">
                            <a href="{{ route('data-absen.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                                <div data-i18n="Basic">Absensi</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::routeIs('pembimbing.siswa.index') ? 'active' : '' }}">
                            <a href="{{ route('pembimbing.siswa.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-user-voice"></i>
                                <div data-i18n="Basic">Pembimbing</div>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->role == 'persuratan')
                        <li
                            class="menu-item {{ Request::routeIs('persuratan.data_pribadi.create') ? 'active' : '' }}">
                            <a href="{{ route('persuratan.data_pribadi.create') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-file"></i>
                                <div data-i18n="Basic">Data Pribadi Persuratan</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::routeIs('pengajuan') ? 'active' : '' }}">
                            <a href="{{ route('persuratan.review') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-file-find"></i>
                                <div data-i18n="Basic">Review Pengajuan</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::routeIs('cetak.iduka.index') ? 'active' : '' }}">
                            <a href="{{ route('cetak.iduka.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-bar-chart"></i>
                                <div data-i18n="Basic">Review Data CP ATP</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::routeIs('persuratan.suratBalasan') ? 'active' : '' }}">
                            <a href="{{ route('persuratan.suratBalasan') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-mail-send"></i>
                                <div data-i18n="Basic">Review Surat Balasan</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::routeIs('surat-pengantar.index') ? 'active' : '' }}">
                            <a href="{{ route('surat-pengantar.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-file"></i>
                                <div data-i18n="Basic">surat pengantar pkl </div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::routeIs('persuratan.pindah_pkl.index') ? 'active' : '' }}">
                            <a href="{{ route('persuratan.pindah_pkl.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-transfer"></i>
                                <div data-i18n="Basic">Pindah Pkl </div>
                            </a>
                        </li>
                    @endif

                    @if (in_array(auth()->user()->role, ['iduka']))
                        <li class="menu-item {{ Request::routeIs('review.pengajuan') ? 'active' : '' }}">
                            <a href="{{ route('pengajuan.review') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-file-find"></i>
                                <div data-i18n="Basic">Review Pengajuan</div>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->role == 'kaprog')
                        <li class="menu-item {{ Request::routeIs('data.iduka') ? 'active' : '' }}">
                            <a href="/data-iduka" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-building"></i>
                                <div data-i18n="Basic">Data Institusi</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::routeIs('cp.index') ? 'active' : '' }}">
                            <a href="{{ route('cp.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-target-lock"></i>
                                <div data-i18n="Basic">Tujuan Pembelajaran</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::routeIs('review.usulan') ? 'active' : '' }}">
                            <a href="{{ route('review.usulan') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-file-find"></i>
                                <div data-i18n="Basic">Review Usulan</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::routeIs('kaprog.review.pengajuan') ? 'active' : '' }}">
                            <a href="{{ route('kaprog.review.pengajuan') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-file-find"></i>
                                <div data-i18n="Basic">Review Pengajuan</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::is('kaprog/pindah-pkl') ? 'active' : '' }}">
                            <a href="/kaprog/pindah-pkl" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-transfer"></i>
                                <div data-i18n="Basic">Review Pindah Pkl</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::routeIs('absen.siswa.kaprog') ? 'active' : '' }}">
                            <a href="{{ route('absen.siswa.kaprog') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-calendar-check"></i>
                                <div data-i18n="Basic">Data Absensi</div>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->role == 'hubin')
                        <li class="menu-header small text-uppercase">
                            <span class="menu-header-text">Pusat Control</span>
                        </li>

                        <li class="menu-item {{ Request::routeIs('user.siswa', 'user.guru') ? 'active open' : '' }}">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-user-plus"></i>
                                <div data-i18n="Layouts">Tambah User</div>
                            </a>

                            <ul class="menu-sub">
                                <li class="menu-item {{ Request::routeIs('user.siswa') ? 'active' : '' }}">
                                    <a href="{{ route('user.siswa') }}" class="menu-link">
                                        <div data-i18n="Without menu">Siswa</div>
                                    </a>
                                </li>

                                <li class="menu-item {{ Request::routeIs('user.guru') ? 'active' : '' }}">
                                    <a href="{{ route('user.guru') }}" class="menu-link">
                                        <div data-i18n="Without menu">GTK</div>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="menu-item {{ Request::routeIs('pusatbantuan.index') ? 'active' : '' }}">
                            <a href="{{ route('pusatbantuan.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-help-circle"></i>
                                <div data-i18n="Basic">Pusat Bantuan</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::routeIs('hubin.iduka.daftar') ? 'active' : '' }}">
                            <a href="{{ route('hubin.iduka.daftar') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-building"></i>
                                <div data-i18n="Basic">Daftar Iduka</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::routeIs('hubin.daftarcetak') ? 'active' : '' }}">
                            <a href="{{ route('hubin.daftarcetak') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-printer"></i>
                                <div data-i18n="Basic">Daftar Cetak</div>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->role == 'pendamping')
                        <li class="menu-item {{ Request::routeIs('pendamping.iduka.index') ? 'active' : '' }}">
                            <a href="{{ route('pendamping.iduka.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-building"></i>
                                <div data-i18n="Basic">Data Institusi</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::routeIs('pendamping.kelas.index') ? 'active' : '' }}">
                            <a href="{{ route('pendamping.kelas.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-user"></i>
                                <div data-i18n="Basic">Data Siswa</div>
                            </a>
                        </li>

                        <li
                            class="menu-item {{ Request::routeIs('admin.pemantauanGtk', 'admin.gtkKependidikan') ? 'active open' : '' }}">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-group"></i>
                                <div data-i18n="Layouts">GTK</div>
                            </a>

                            <ul class="menu-sub">
                                <li
                                    class="menu-item {{ Request::routeIs('pendamping.guru.index') ? 'active' : '' }}">
                                    <a href="{{ route('pendamping.guru.index') }}" class="menu-link">
                                        <div data-i18n="Without menu">Guru</div>
                                    </a>
                                </li>

                                <li class="menu-item {{ Request::routeIs('pendamping.tk.index') ? 'active' : '' }}">
                                    <a href="{{ route('pendamping.tk.index') }}" class="menu-link">
                                        <div data-i18n="Without menu">Tenaga Kependidikan</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if (auth()->user()->role == 'kepsek')
                        <li class="menu-item {{ Request::routeIs('kepsek.reviewPengajuanSiswa') ? 'active' : '' }}">
                            <a href="{{ route('kepsek.reviewPengajuanSiswa') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-file"></i>
                                <div data-i18n="Basic">Data Pengajuan PKL Siswa</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::routeIs('kepsek.iduka.index') ? 'active' : '' }}">
                            <a href="{{ route('kepsek.iduka.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-building"></i>
                                <div data-i18n="Basic">Data Institusi</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::routeIs('kepsek.kelas.index') ? 'active' : '' }}">
                            <a href="{{ route('kepsek.kelas.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-user"></i>
                                <div data-i18n="Basic">Data Siswa</div>
                            </a>
                        </li>

                        <li
                            class="menu-item {{ Request::routeIs('admin.pemantauanGtk', 'admin.gtkKependidikan') ? 'active open' : '' }}">
                            <a href="javascript:void(0);" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-group"></i>
                                <div data-i18n="Layouts">GTK</div>
                            </a>

                            <ul class="menu-sub">
                                <li class="menu-item {{ Request::routeIs('kepsek.guru.index') ? 'active' : '' }}">
                                    <a href="{{ route('kepsek.guru.index') }}" class="menu-link">
                                        <div data-i18n="Without menu">Guru</div>
                                    </a>
                                </li>

                                <li class="menu-item {{ Request::routeIs('kepsek.tk.index') ? 'active' : '' }}">
                                    <a href="{{ route('kepsek.tk.index') }}" class="menu-link">
                                        <div data-i18n="Without menu">Tenaga Kependidikan</div>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if (in_array(auth()->user()->role, ['hubin', 'kaprog', 'kepsek']))
                        <li class="menu-item {{ Request::routeIs('laporan.iduka.index') ? 'active' : '' }}">
                            <a href="{{ route('laporan.iduka.index') }}" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-bar-chart-alt-2"></i>
                                <div data-i18n="Basic">Laporan Iduka</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::routeIs('progres.siswa.index') ? 'active' : '' }}">
                            <a href="{{ route('progres.siswa.index') }}" class="menu-link">
                                <i class="menu-icon bx bx-line-chart"></i>
                                <div data-i18n="Basic">Progres Siswa</div>
                            </a>
                        </li>

                        <li class="menu-item {{ Request::routeIs('jurnal.siswa-belum-isi') ? 'active' : '' }}">
                            <a href="{{ route('jurnal.siswa-belum-isi') }}" class="menu-link">
                                <i class="menu-icon bx bx-error"></i>
                                <div data-i18n="Basic">Siswa Belum Isi Jurnal</div>
                            </a>
                        </li>
                    @endif
                </ul>
            </aside>

            <div class="layout-page">
                <!-- Navbar -->
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <!-- Sidebar menu toggle (All screens) -->
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="fas fa-bars" style="font-size:22px; color:#64748b;"></i>
                        </a>
                    </div>

                    <!-- Logo kecil di navbar: fade-in saat sidebar ditutup, fade-out saat sidebar tampil -->
                    <div class="navbar-brand-mini" aria-hidden="true">
                        <img src="{{ asset('images/logoo.png') }}" alt="">
                        <span>TATA RIKSA K-ONE</span>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <ul class="navbar-nav flex-row align-items-center ms-auto gap-2">

                            <!-- Username text -->
                            <li class="d-none d-md-flex align-items-center"
                                style="color:#64748b;font-size:13.5px;font-weight:500;">
                                <i class="fas fa-user-circle me-2" style="color:#94a3b8;"></i>
                                {{ auth()->user()->profile->nama ?? auth()->user()->name }}
                            </li>

                            <!-- Role badge -->
                            <li class="d-none d-md-flex align-items-center">
                                <span class="badge"
                                    style="background:#eff6ff;color:#2563eb;font-size:11px;font-weight:700;padding:4px 10px;border-radius:20px;">
                                    {{ ucfirst(auth()->user()->role) }}
                                </span>
                            </li>

                            <!-- User Dropdown -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="{{ Auth::user()->profile_photo ? asset(Auth::user()->profile_photo) : asset('images/default.jpg') }}"
                                            alt="Foto Profil" class="rounded-circle" width="36" height="36"
                                            style="border:2px solid #e2e8f0;object-fit:cover;">
                                    </div>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end">
                                    <!-- Profile Info -->
                                    <li>
                                        <a class="dropdown-item" href="#" style="pointer-events:none;">
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ Auth::user()->profile_photo ? asset(Auth::user()->profile_photo) : asset('images/default.jpg') }}"
                                                    alt="Foto Profil" class="rounded-circle" width="40"
                                                    height="40" style="border:2px solid #e2e8f0;object-fit:cover;">
                                                <div>
                                                    <div class="fw-semibold text-truncate"
                                                        style="max-width:140px;font-size:13.5px;color:#0f172a;"
                                                        title="{{ auth()->user()->profile->nama ?? auth()->user()->name }}">
                                                        {{ auth()->user()->profile->nama ?? auth()->user()->name }}
                                                    </div>
                                                    <div style="font-size:12px;color:#64748b;">
                                                        {{ ucfirst(auth()->user()->role) }}</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>

                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>

                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                            <i class="fas fa-user-edit me-2" style="color:#64748b;width:16px;"></i>
                                            <span>Edit Profil</span>
                                        </a>
                                    </li>

                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>

                                    <li>
                                        <a class="dropdown-item logout-btn" href="/logout" style="color:#dc2626;">
                                            <i class="fas fa-sign-out-alt me-2" style="width:16px;"></i>
                                            <span>Keluar</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>

                @yield('content')
            </div>

            <!-- Overlay -->
            <div class="layout-overlay layout-menu-toggle"></div>
        </div>
        <!-- / Layout wrapper -->
    </div>

    <script>
        document.querySelectorAll('.logout-btn').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();

                Swal.fire({
                    title: "Apakah kamu yakin?",
                    text: "Data ini tidak bisa dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Logout!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = this.href; // Arahkan ke URL logout
                    }
                });
            });
        });
    </script>

    <!-- Core JS -->
    <script src="{{ asset('snet/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('snet/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('snet/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('snet/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('snet/assets/vendor/js/menu.js') }}"></script>

    <!-- Vendors JS -->
    <script src="{{ asset('snet/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('snet/assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('snet/assets/js/dashboards-analytics.js') }}"></script>

    <!-- GitHub Buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        if ("serviceWorker" in navigator) {
            window.addEventListener("load", () => {
                navigator.serviceWorker.register("/service-worker.js")
                    .then(reg => console.log("Service Worker registered:", reg))
                    .catch(err => console.log("SW registration failed:", err));
            });
        }
    </script>

    @stack('scripts')
</body>

</html>
