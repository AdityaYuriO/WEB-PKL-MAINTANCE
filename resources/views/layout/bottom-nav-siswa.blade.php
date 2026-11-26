<style>
    .siswa-bottom-nav-container {
        position: fixed;
        bottom: calc(14px + env(safe-area-inset-bottom, 0px));
        left: 0;
        right: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1040;
        padding: 0 16px;
        pointer-events: none;
    }

    .siswa-bottom-nav {
        pointer-events: auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 9999px;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.12), 0 4px 6px -4px rgba(15, 23, 42, 0.05);
        padding: 6px 8px;
        width: 100%;
        max-width: 400px;
        gap: 4px;
    }

    .siswa-nav-item {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 7px 4px 6px;
        border-radius: 9999px;
        text-decoration: none;
        color: #64748b;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        min-width: 0;
        gap: 3px;
        user-select: none;
        -webkit-tap-highlight-color: transparent;
    }

    .siswa-nav-item:hover {
        color: #1e293b;
        text-decoration: none;
    }

    .siswa-nav-item .nav-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        transition: transform 0.2s ease, color 0.2s ease;
    }

    .siswa-nav-item .nav-icon svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
        stroke-width: 2.1;
        display: block;
    }

    .siswa-nav-item .nav-label {
        font-size: 11px;
        font-weight: 500;
        line-height: 1.15;
        letter-spacing: -0.2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
        transition: color 0.2s ease, font-weight 0.2s ease;
    }

    .siswa-nav-item.active {
        background-color: #eff6ff;
        color: #2563eb;  
    }

    .siswa-nav-item.active .nav-icon {
        color: #2563eb;
        transform: translateY(-1px);
    }

    .siswa-nav-item.active .nav-label {
        color: #2563eb;
        font-weight: 700;
    }

    @media (max-width: 1199.98px) {
        body.role-siswa #layout-navbar {
            position: fixed !important;
            top: calc(12px + env(safe-area-inset-top, 0px)) !important;
            left: 14px !important;
            right: 14px !important;
            width: calc(100% - 28px) !important;
            max-width: 440px !important;
            margin: 0 auto !important;
            border-radius: 9999px !important;
            background-color: rgba(255, 255, 255, 0.90) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            border: 1px solid rgba(226, 232, 240, 0.85) !important;
            border-bottom: 1px solid rgba(226, 232, 240, 0.85) !important;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 4px 6px -4px rgba(15, 23, 42, 0.03) !important;
            z-index: 1040 !important;
            min-height: 56px !important;
            height: 56px !important;
            padding: 6px 8px 6px 18px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
        }

        body.role-siswa #layout-navbar .navbar-nav-right {
            flex-basis: auto !important;
            flex-grow: 0 !important;
            width: auto !important;
            margin-left: auto !important;
        }

        body.role-siswa #layout-navbar .navbar-nav {
            margin-left: auto !important;
            gap: 0 !important;
        }

        body.role-siswa .siswa-navbar-greeting {
            flex: 1;
            min-width: 0;
            display: flex;
            align-items: center;
            padding-right: 8px;
        }

        body.role-siswa .greeting-title {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.25;
            letter-spacing: -0.2px;
            max-width: 220px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
        }

        body.role-siswa .greeting-subtitle {
            font-size: 11px;
            color: #64748b;
            font-weight: 500;
            line-height: 1.2;
            margin-top: 1px;
            letter-spacing: -0.1px;
        }

        body.role-siswa #layout-navbar .dropdown-menu {
            border-radius: 14px !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.12) !important;
            margin-top: 8px !important;
        }

        html body.role-siswa .layout-content-navbar .layout-page,
        html body.role-siswa .layout-page {
            padding-top: calc(82px + env(safe-area-inset-top, 0px)) !important;
            padding-bottom: calc(90px + env(safe-area-inset-bottom, 0px)) !important;
        }

        body.role-siswa #layout-menu,
        body.role-siswa .layout-overlay,
        body.role-siswa .layout-menu-toggle {
            display: none !important;
        }
    }

    @media (min-width: 1200px) {
        .siswa-bottom-nav-container {
            display: none !important;
        }
    }
    
    @media (max-width: 360px) {
        body.role-siswa #layout-navbar {
            left: 8px !important;
            right: 8px !important;
            width: calc(100% - 16px) !important;
            padding: 5px 6px 5px 14px !important;
            min-height: 52px !important;
            height: 52px !important;
            top: calc(8px + env(safe-area-inset-top, 0px)) !important;
        }

        body.role-siswa .greeting-title {
            font-size: 13px;
            max-width: 150px;
        }

        body.role-siswa .greeting-subtitle {
            font-size: 10px;
        }

        html body.role-siswa .layout-content-navbar .layout-page,
        html body.role-siswa .layout-page {
            padding-top: calc(72px + env(safe-area-inset-top, 0px)) !important;
        }

        .siswa-bottom-nav-container {
            padding: 0 10px;
            bottom: calc(10px + env(safe-area-inset-bottom, 0px));
        }
        .siswa-bottom-nav {
            padding: 5px 6px;
            gap: 2px;
        }
        .siswa-nav-item {
            padding: 5px 2px 4px;
            gap: 2px;
        }
        .siswa-nav-item .nav-icon svg {
            width: 18px;
            height: 18px;
        }
        .siswa-nav-item .nav-label {
            font-size: 10px;
        }
    }
</style>

<nav class="siswa-bottom-nav-container d-xl-none" aria-label="Navigasi Siswa">
    <div class="siswa-bottom-nav">
        {{-- 1. Dashboard --}}
        <a href="/dashboard/siswa"
           class="siswa-nav-item {{ Request::routeIs('siswa.dashboard') || Request::is('dashboard/siswa*') ? 'active' : '' }}"
           title="Dashboard">
            <div class="nav-icon">
                {{-- Lucide Layout Dashboard --}}
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard">
                    <rect width="7" height="9" x="3" y="3" rx="1"/>
                    <rect width="7" height="5" x="14" y="3" rx="1"/>
                    <rect width="7" height="9" x="14" y="12" rx="1"/>
                    <rect width="7" height="5" x="3" y="16" rx="1"/>
                </svg>
            </div>
            <span class="nav-label">Dashboard</span>
        </a>

        {{-- 2. Absensi --}}
        <a href="{{ route('absensi.index') }}"
           class="siswa-nav-item {{ Request::routeIs('absensi.*') || Request::is('*absensi*') ? 'active' : '' }}"
           title="Absensi">
            <div class="nav-icon">
                {{-- Lucide Calendar Check --}}
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-check">
                    <path d="M8 2v4"/>
                    <path d="M16 2v4"/>
                    <rect width="18" height="18" x="3" y="4" rx="2"/>
                    <path d="M3 10h18"/>
                    <path d="m9 16 2 2 4-4"/>
                </svg>
            </div>
            <span class="nav-label">Absensi</span>
        </a>

        {{-- 3. Jurnal --}}
        <a href="{{ route('jurnal.index') }}"
           class="siswa-nav-item {{ Request::routeIs('jurnal.*') || Request::is('*jurnal*') ? 'active' : '' }}"
           title="Jurnal">
            <div class="nav-icon">
                {{-- Lucide Book Open --}}
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-open">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                </svg>
            </div>
            <span class="nav-label">Jurnal</span>
        </a>

        {{-- 4. Data Pribadi --}}
        <a href="{{ route('siswa.data_pribadi.create') }}"
           class="siswa-nav-item {{ Request::routeIs('siswa.data_pribadi.*') || Request::is('siswa/data-pribadi*') ? 'active' : '' }}"
           title="Data Pribadi">
            <div class="nav-icon">
                {{-- Lucide User --}}
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
            <span class="nav-label">Data Pribadi</span>
        </a>
    </div>
</nav>

