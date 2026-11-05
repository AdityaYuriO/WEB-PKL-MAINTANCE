<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tata Riksa K-ONE | PKL Management System</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy:   #0f172a;
            --navy2:  #1e293b;
            --accent: #3b82f6;
            --border: rgba(255,255,255,0.08);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--navy);
            color: #f1f5f9;
            min-height: 100vh;
            /* Dot pattern overlay without gradient */
            background-image: radial-gradient(rgba(255,255,255,0.035) 1px, transparent 1px);
            background-size: 28px 28px;
        }

        /* ---- Topbar ---- */
        .topbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 48px;
            height: 64px;
            background: rgba(15,23,42,0.85);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border);
        }

        .topbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 15px;
            font-weight: 700;
            color: #f1f5f9;
            text-decoration: none;
            letter-spacing: 0.5px;
        }

        .topbar-brand i {
            font-size: 22px;
            color: var(--accent);
        }

        .topbar-nav {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-topbar-ghost {
            padding: 8px 18px;
            border: 1.5px solid rgba(255,255,255,0.15);
            border-radius: 8px;
            color: #cbd5e1;
            font-size: 13.5px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            background: transparent;
            cursor: pointer;
            text-decoration: none;
            transition: border-color 0.2s, color 0.2s;
            display: flex;
            align-items: center;
            gap: 7px;
        }
        .btn-topbar-ghost:hover {
            border-color: var(--accent);
            color: #fff;
        }

        .btn-topbar-solid {
            padding: 8px 20px;
            border: none;
            border-radius: 8px;
            background-color: var(--accent);
            color: #fff;
            font-size: 13.5px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.2s, transform 0.15s;
            display: flex;
            align-items: center;
            gap: 7px;
            box-shadow: 0 4px 12px rgba(59,130,246,0.35);
        }
        .btn-topbar-solid:hover {
            background-color: #2563eb;
            transform: translateY(-1px);
        }

        /* ---- Hero ---- */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            padding: 80px 24px 60px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(59,130,246,0.12);
            border: 1px solid rgba(59,130,246,0.25);
            border-radius: 40px;
            padding: 6px 16px;
            font-size: 12px;
            font-weight: 700;
            color: #93c5fd;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 28px;
        }

        .hero-badge i { font-size: 13px; }

        .hero h1 {
            font-size: clamp(36px, 6vw, 64px);
            font-weight: 800;
            color: #f8fafc;
            line-height: 1.15;
            letter-spacing: -0.5px;
            margin-bottom: 20px;
        }

        .hero h1 span {
            color: var(--accent);
        }

        .hero p {
            font-size: 17px;
            color: #94a3b8;
            max-width: 520px;
            line-height: 1.75;
            margin-bottom: 40px;
        }

        .hero-cta {
            display: flex;
            gap: 14px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-cta-primary {
            padding: 14px 32px;
            background-color: var(--accent);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 6px 20px rgba(59,130,246,0.35);
            transition: background-color 0.2s, transform 0.15s, box-shadow 0.2s;
        }
        .btn-cta-primary:hover {
            background-color: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(59,130,246,0.4);
            color: #fff;
        }

        .btn-cta-ghost {
            padding: 14px 32px;
            border: 1.5px solid rgba(255,255,255,0.15);
            border-radius: 10px;
            color: #cbd5e1;
            font-size: 15px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            background: transparent;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: border-color 0.2s, color 0.2s;
        }
        .btn-cta-ghost:hover {
            border-color: #3b82f6;
            color: #fff;
        }

        /* ---- Feature Cards ---- */
        .features {
            padding: 60px 24px 80px;
            max-width: 1080px;
            margin: 0 auto;
        }

        .features-title {
            text-align: center;
            margin-bottom: 48px;
        }

        .features-title h2 {
            font-size: 28px;
            font-weight: 800;
            color: #f1f5f9;
            margin-bottom: 10px;
        }

        .features-title p {
            color: #64748b;
            font-size: 14.5px;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
        }

        .feature-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 14px;
            padding: 28px 24px;
            transition: border-color 0.2s, background 0.2s;
        }

        .feature-card:hover {
            border-color: rgba(59,130,246,0.3);
            background: rgba(59,130,246,0.06);
        }

        .feature-icon {
            width: 48px; height: 48px;
            background: rgba(59,130,246,0.12);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            border: 1px solid rgba(59,130,246,0.2);
        }

        .feature-icon i {
            font-size: 20px;
            color: var(--accent);
        }

        .feature-card h3 {
            font-size: 15px;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 8px;
        }

        .feature-card p {
            font-size: 13px;
            color: #64748b;
            line-height: 1.7;
        }

        /* ---- Footer ---- */
        .footer {
            border-top: 1px solid var(--border);
            padding: 24px 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .footer span {
            font-size: 13px;
            color: #475569;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .topbar { padding: 0 20px; }
            .hero h1 { font-size: 32px; }
            .footer { flex-direction: column; text-align: center; }
        }
    </style>
</head>
<body>

    <!-- Topbar -->
    <header class="topbar">
        <a href="#" class="topbar-brand">
            <i class="fas fa-graduation-cap"></i>
            TATA RIKSA K-ONE
        </a>

        @if (Route::has('login'))
            <nav class="topbar-nav">
                @auth
                    <a href="{{ url('/home') }}" class="btn-topbar-ghost">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-topbar-solid">
                        <i class="fas fa-sign-in-alt"></i> Masuk
                    </a>
                @endauth
            </nav>
        @endif
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-badge">
            <i class="fas fa-star"></i>
            Platform PKL Sekolah
        </div>

        <h1>
            Kelola PKL<br>
            <span>Lebih Cerdas & Efisien</span>
        </h1>

        <p>
            Tata Riksa K-ONE memudahkan koordinasi PKL antara siswa, guru pembimbing,
            dan mitra industri dalam satu platform terintegrasi.
        </p>

        <div class="hero-cta">
            @auth
                <a href="{{ url('/home') }}" class="btn-cta-primary">
                    <i class="fas fa-tachometer-alt"></i> Buka Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-cta-primary">
                    <i class="fas fa-sign-in-alt"></i> Masuk Sekarang
                </a>
                <a href="#features" class="btn-cta-ghost">
                    <i class="fas fa-info-circle"></i> Pelajari Fitur
                </a>
            @endauth
        </div>
    </section>

    <!-- Features -->
    <section class="features" id="features">
        <div class="features-title">
            <h2>Fitur Unggulan</h2>
            <p>Semua yang Anda butuhkan untuk pengelolaan PKL yang terstruktur</p>
        </div>

        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-calendar-check"></i></div>
                <h3>Absensi Berbasis Lokasi</h3>
                <p>Siswa dan guru dapat melakukan absensi dengan validasi GPS secara real-time.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-book-open"></i></div>
                <h3>Jurnal Digital</h3>
                <p>Pencatatan kegiatan harian PKL secara digital yang dapat dipantau pembimbing.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-chart-bar"></i></div>
                <h3>Penilaian Terstruktur</h3>
                <p>Sistem penilaian berbasis indikator dengan rekap nilai akhir otomatis.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-file-alt"></i></div>
                <h3>Manajemen Surat</h3>
                <p>Pengelolaan surat pengantar dan surat balasan PKL secara digital.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-users"></i></div>
                <h3>Multi-Role Access</h3>
                <p>Akses berbeda untuk siswa, guru, hubin, kaprog, kepsek, dan mitra industri.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-building"></i></div>
                <h3>Data Institusi Mitra</h3>
                <p>Database lengkap tempat PKL (IDUKA) yang terintegrasi dengan data siswa.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <span>
            <i class="fas fa-graduation-cap me-1" style="color:#3b82f6;"></i>
            &copy; {{ date('Y') }} Tata Riksa K-ONE &mdash; SMKN 1 Kawali
        </span>
        <span>
            Laravel v{{ Illuminate\Foundation\Application::VERSION }} &bull; PHP v{{ PHP_VERSION }}
        </span>
    </footer>

</body>
</html>
