<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | Tata Riksa K-ONE</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy:        #0f172a;
            --navy2:       #1e293b;
            --accent:      #3b82f6;
            --accent-dark: #2563eb;
            --border:      #e2e8f0;
            --text:        #0f172a;
            --text-muted:  #64748b;
            --bg:          #f1f5f9;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            /* Subtle dot grid pattern */
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 24px 24px;
        }

        /* ---- Loader ---- */
        #loader-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15,23,42,0.55);
            backdrop-filter: blur(4px);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        #loader-overlay .spinner-border { width: 3rem; height: 3rem; }
        #loader-overlay p { color: #f1f5f9; margin-top: 14px; font-weight: 600; font-size: 14px; }

        /* ---- Main card ---- */
        .login-card {
            display: flex;
            width: 900px;
            max-width: 95vw;
            min-height: 520px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(15,23,42,0.15), 0 6px 20px rgba(15,23,42,0.08);
        }

        /* ---- Left panel (dark navy) ---- */
        .login-left {
            flex: 1;
            background-color: var(--navy);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            position: relative;
            overflow: hidden;
        }

        /* Decorative corner circles (no gradient – solid subtle) */
        .login-left::before {
            content: '';
            position: absolute;
            top: -50px; right: -50px;
            width: 160px; height: 160px;
            background: rgba(59,130,246,0.12);
            border-radius: 50%;
        }
        .login-left::after {
            content: '';
            position: absolute;
            bottom: -50px; left: -50px;
            width: 160px; height: 160px;
            background: rgba(59,130,246,0.08);
            border-radius: 50%;
        }

        .login-left .brand-icon {
            width: 64px; height: 64px;
            background: rgba(59,130,246,0.15);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 24px;
            border: 1px solid rgba(59,130,246,0.25);
        }
        .login-left .brand-icon i { font-size: 28px; color: var(--accent); }

        .login-left .school-img {
            width: 100%;
            max-width: 300px;
            border-radius: 12px;
            border: 3px solid rgba(255,255,255,0.08);
            margin-bottom: 28px;
            transition: transform 0.3s ease;
        }
        .login-left .school-img:hover { transform: scale(1.02); }

        .login-left h2 {
            color: #f1f5f9;
            font-size: 17px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 10px;
            text-align: center;
        }
        .login-left p {
            color: #94a3b8;
            font-size: 13px;
            text-align: center;
            max-width: 240px;
            line-height: 1.7;
        }

        /* ---- Right panel (white form) ---- */
        .login-right {
            flex: 1;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 48px 40px;
        }

        .login-right .login-header {
            margin-bottom: 32px;
        }

        .login-right .login-header .icon-wrap {
            width: 48px; height: 48px;
            background: #eff6ff;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 16px;
        }
        .login-right .login-header .icon-wrap i {
            font-size: 22px;
            color: var(--accent);
        }

        .login-right .login-header h1 {
            font-size: 24px;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 6px;
        }
        .login-right .login-header p {
            font-size: 14px;
            color: var(--text-muted);
        }

        /* Input group with icon */
        .input-icon-wrap {
            position: relative;
            margin-bottom: 16px;
        }
        .input-icon-wrap .icon {
            position: absolute;
            left: 14px; top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 15px;
            pointer-events: none;
        }
        .input-icon-wrap input {
            width: 100%;
            padding: 13px 16px 13px 42px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: var(--text);
            background: #f8fafc;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .input-icon-wrap input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
            background: #fff;
        }
        .input-icon-wrap input::placeholder { color: #94a3b8; }

        /* Alert */
        .alert-custom {
            background: #fef2f2;
            border-left: 3px solid #ef4444;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #b91c1c;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .alert-custom ul { margin: 0; padding-left: 16px; }

        /* Buttons */
        .btn-login {
            width: 100%;
            padding: 13px;
            background-color: var(--accent);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.15s, box-shadow 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            box-shadow: 0 4px 12px rgba(59,130,246,0.3);
            margin-bottom: 12px;
        }
        .btn-login:hover {
            background-color: var(--accent-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(59,130,246,0.35);
        }
        .btn-login:active { transform: translateY(0); }
        .btn-login:disabled { opacity: 0.7; cursor: not-allowed; }

        .btn-back {
            width: 100%;
            padding: 12px;
            background: #f1f5f9;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: background-color 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            text-decoration: none;
        }
        .btn-back:hover { background: #e2e8f0; color: var(--text); }

        /* Responsive */
        @media (max-width: 768px) {
            .login-left { display: none; }
            .login-right { padding: 36px 24px; }
            .login-card { border-radius: 16px; }
        }
    </style>
</head>

<body>

    <!-- Loader -->
    <div id="loader-overlay">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p>Sedang masuk...</p>
    </div>

    <!-- Login Card -->
    <div class="login-card">

        <!-- Left Panel -->
        <div class="login-left">
            <div class="brand-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <img src="{{ asset('images/smk1.JPG') }}" alt="SMKN 1 Kawali" class="school-img">
            <h2>Tata Riksa K-ONE</h2>
            <p>Platform PKL terintegrasi untuk siswa, guru, dan mitra industri.</p>
        </div>

        <!-- Right Panel -->
        <div class="login-right">
            <div class="login-header">
                <div class="icon-wrap">
                    <i class="fas fa-sign-in-alt"></i>
                </div>
                <h1>Selamat Datang</h1>
                <p>Masuk ke akun Anda untuk lanjut.</p>
            </div>

            @if ($errors->any())
                <div class="alert-custom">
                    <i class="fas fa-exclamation-circle" style="font-size:16px;margin-top:1px;"></i>
                    <ul>
                        @foreach ($errors->all() as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="login-form" action="" method="POST">
                @csrf

                <div class="input-icon-wrap">
                    <i class="fas fa-id-card icon"></i>
                    <input type="text" value="{{ old('nip') }}" name="nip"
                        placeholder="NIS / NIP / Email" autocomplete="username">
                </div>

                <div class="input-icon-wrap" style="margin-bottom:28px;">
                    <i class="fas fa-lock icon"></i>
                    <input type="password" name="password" placeholder="Password" autocomplete="current-password">
                </div>

                <button id="login-btn" class="btn-login" type="submit">
                    <i class="fas fa-sign-in-alt"></i>
                    Masuk
                </button>

                <a href="/" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Beranda
                </a>
            </form>
        </div>

    </div>

    <script>
        const form = document.getElementById('login-form');
        const loader = document.getElementById('loader-overlay');
        const loginBtn = document.getElementById('login-btn');

        form.addEventListener('submit', function () {
            loader.style.display = 'flex';
            loginBtn.disabled = true;
            loginBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Memproses...';
        });
    </script>

</body>
</html>