<aside id="layout-menu" class="layout-menu menu-vertical menu siswa-sidebar" aria-label="Navigasi Siswa">
    {{-- Sentinel for Sneat snet/assets/js/main.js hover handler so .layout-overlay is never targeted --}}
    <span class="layout-menu-toggle siswa-sneat-sentinel" hidden aria-hidden="true"></span>

    {{-- Header Sidebar --}}
    <div class="siswa-sidebar-header">
        <a href="{{ route('siswa.dashboard') }}"
           class="siswa-brand-link"
           data-tooltip="TATA RIKSA K-ONE (Siswa)">
            <span class="siswa-brand-logo" aria-hidden="true">
                <img src="{{ asset('images/logoo.png') }}"
                     onerror="this.onerror=null;this.src='{{ asset('images/logo.png') }}';"
                     alt="Logo WebPKL"
                     width="26"
                     height="26">
            </span>
            <span class="siswa-brand-meta">
                <span class="siswa-brand-title">TATA RIKSA K-ONE</span>
                <span class="siswa-brand-sub">
                    <span class="siswa-brand-app">WebPKL</span>
                    <span class="siswa-role-pill">Siswa</span>
                </span>
            </span>
        </a>

        <button type="button"
                id="siswa-sidebar-toggle"
                class="siswa-sidebar-toggle"
                aria-label="Ciutkan sidebar"
                aria-expanded="true"
                title="Ciutkan sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2.2"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="m15 18-6-6 6-6"/>
            </svg>
        </button>
    </div>

    {{-- Menu Navigasi --}}
    <ul class="menu-inner siswa-menu-inner">
        {{-- Kategori: Menu Utama --}}
        <li class="siswa-nav-group is-first" aria-hidden="true">
            <span class="siswa-nav-group-text">Menu Utama</span>
        </li>

        <li class="menu-item {{ Request::routeIs('siswa.dashboard') ? 'active' : '' }}">
            <a href="{{ route('siswa.dashboard') }}"
               class="siswa-nav-link"
               data-tooltip="Dashboard"
               aria-label="Dashboard">
                <span class="siswa-nav-icon" aria-hidden="true">
                    {{-- Lucide layout-dashboard --}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="7" height="9" x="3" y="3" rx="1"/>
                        <rect width="7" height="5" x="14" y="3" rx="1"/>
                        <rect width="7" height="9" x="14" y="12" rx="1"/>
                        <rect width="7" height="5" x="3" y="16" rx="1"/>
                    </svg>
                </span>
                <span class="siswa-nav-label">Dashboard</span>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('siswa.data_pribadi.*') ? 'active' : '' }}">
            <a href="{{ route('siswa.data_pribadi.create') }}"
               class="siswa-nav-link"
               data-tooltip="Data Pribadi Siswa"
               aria-label="Data Pribadi Siswa">
                <span class="siswa-nav-icon" aria-hidden="true">
                    {{-- Lucide user --}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </span>
                <span class="siswa-nav-label">Data Pribadi Siswa</span>
            </a>
        </li>

        {{-- Kategori: Aktivitas PKL --}}
        <li class="siswa-nav-group is-spaced" aria-hidden="true">
            <span class="siswa-nav-group-text">Aktivitas PKL</span>
        </li>

        <li class="menu-item {{ Request::routeIs('absensi.*') ? 'active' : '' }}">
            <a href="{{ route('absensi.index') }}"
               class="siswa-nav-link"
               data-tooltip="Absensi"
               aria-label="Absensi">
                <span class="siswa-nav-icon" aria-hidden="true">
                    {{-- Lucide calendar-check --}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 2v4"/>
                        <path d="M16 2v4"/>
                        <rect width="18" height="18" x="3" y="4" rx="2"/>
                        <path d="M3 10h18"/>
                        <path d="m9 16 2 2 4-4"/>
                    </svg>
                </span>
                <span class="siswa-nav-label">Absensi</span>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('jurnal.*') ? 'active' : '' }}">
            <a href="{{ route('jurnal.index') }}"
               class="siswa-nav-link"
               data-tooltip="Jurnal"
               aria-label="Jurnal">
                <span class="siswa-nav-icon" aria-hidden="true">
                    {{-- Lucide book-open --}}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                    </svg>
                </span>
                <span class="siswa-nav-label">Jurnal</span>
            </a>
        </li>
    </ul>
</aside>

<script>
(function () {
    'use strict';

    var STORAGE_KEY = 'webpkl_siswa_sidebar_collapsed';
    var COLLAPSED_CLASS = 'siswa-sidebar-collapsed';
    var READY_CLASS = 'siswa-sidebar-ready';
    var DESKTOP_MIN_WIDTH = 1200;

    var rootEl = document.documentElement;
    var sidebarEl = document.getElementById('layout-menu');
    var toggleBtn = document.getElementById('siswa-sidebar-toggle');
    var menuScrollEl = sidebarEl ? sidebarEl.querySelector('.menu-inner') : null;

    if (!sidebarEl || !toggleBtn) return;

    function isDesktop() {
        return window.innerWidth >= DESKTOP_MIN_WIDTH;
    }

    function isCollapsed() {
        return rootEl.classList.contains(COLLAPSED_CLASS);
    }

    function syncToggleAttributes(collapsed) {
        var label = collapsed ? 'Perluas sidebar' : 'Ciutkan sidebar';
        toggleBtn.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
        toggleBtn.setAttribute('aria-label', label);
        toggleBtn.setAttribute('title', label);
    }

    function applySavedState() {
        var saved = false;
        try {
            saved = localStorage.getItem(STORAGE_KEY) === '1';
        } catch (e) {
            saved = false;
        }
        var shouldCollapse = isDesktop() && saved;
        rootEl.classList.toggle(COLLAPSED_CLASS, shouldCollapse);
        syncToggleAttributes(shouldCollapse);
    }

    // Floating portal tooltip on document.body so collapsed tooltips are never clipped
    var tooltipEl = null;
    var activeAnchor = null;

    function ensureTooltipEl() {
        if (tooltipEl) return tooltipEl;
        tooltipEl = document.createElement('div');
        tooltipEl.id = 'siswa-sidebar-tooltip';
        tooltipEl.className = 'siswa-sidebar-tooltip';
        tooltipEl.setAttribute('role', 'tooltip');
        document.body.appendChild(tooltipEl);
        return tooltipEl;
    }

    function hideTooltip() {
        activeAnchor = null;
        if (tooltipEl) {
            tooltipEl.classList.remove('is-visible');
        }
    }

    function showTooltip(target) {
        if (!isDesktop() || !isCollapsed()) return;
        var text = target.getAttribute('data-tooltip');
        if (!text) return;

        var tip = ensureTooltipEl();
        activeAnchor = target;
        tip.textContent = text;

        var rect = target.getBoundingClientRect();
        tip.style.left = (rect.right + 10) + 'px';
        tip.style.top = (rect.top + rect.height / 2) + 'px';
        tip.classList.add('is-visible');
    }

    function setCollapsedState(nextCollapsed) {
        hideTooltip();
        rootEl.classList.toggle(COLLAPSED_CLASS, nextCollapsed);
        syncToggleAttributes(nextCollapsed);

        try {
            localStorage.setItem(STORAGE_KEY, nextCollapsed ? '1' : '0');
        } catch (e) {}

        // Notify layout utilities & responsive widgets after CSS width transition
        setTimeout(function () {
            if (window.Helpers && window.Helpers.mainMenu && typeof window.Helpers.mainMenu.update === 'function') {
                window.Helpers.mainMenu.update();
            }
            window.dispatchEvent(new Event('resize'));
        }, 230);
    }

    applySavedState();

    // Enable transitions after first frame to prevent initial-load width animation flicker
    requestAnimationFrame(function () {
        requestAnimationFrame(function () {
            rootEl.classList.add(READY_CLASS);
        });
    });

    toggleBtn.addEventListener('click', function (e) {
        e.preventDefault();
        if (!isDesktop()) return;
        setCollapsedState(!isCollapsed());
    });

    // Tooltip events for collapsed sidebar links & brand logo
    var tooltipTargets = sidebarEl.querySelectorAll('[data-tooltip]');
    tooltipTargets.forEach(function (el) {
        el.addEventListener('mouseenter', function () {
            showTooltip(el);
        });
        el.addEventListener('mouseleave', function () {
            if (activeAnchor === el) hideTooltip();
        });
        el.addEventListener('focusin', function () {
            showTooltip(el);
        });
        el.addEventListener('focusout', function () {
            if (activeAnchor === el) hideTooltip();
        });
    });

    if (menuScrollEl) {
        menuScrollEl.addEventListener('scroll', hideTooltip, { passive: true });
        menuScrollEl.addEventListener('ps-scroll-y', hideTooltip);
    }

    window.addEventListener('resize', function () {
        hideTooltip();
        if (!isDesktop()) {
            rootEl.classList.remove(COLLAPSED_CLASS);
        } else {
            applySavedState();
        }
    });
})();
</script>
