<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TrustGuard — Browse with confidence. Know before you trust.')</title>
    
    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#EFF6FF',
                            100: '#DBEAFE',
                            200: '#BFDBFE',
                            300: '#93C5FD',
                            400: '#60A5FA',
                            500: '#3B82F6',
                            600: '#2563EB',
                            700: '#1D4ED8',
                            800: '#1E40AF',
                            900: '#1E3A8A',
                            950: '#172554',
                        },
                        navy: {
                            50: '#F8FAFC',
                            100: '#F1F5F9',
                            200: '#E2E8F0',
                            300: '#CBD5E1',
                            400: '#94A3B8',
                            500: '#64748B',
                            600: '#475569',
                            700: '#334155',
                            800: '#1E293B',
                            900: '#0F172A',
                            950: '#020617',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', '"Inter"', 'sans-serif'],
                        mono: ['ui-monospace', 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', 'monospace'],
                    },
                    boxShadow: {
                        'soft-sm': '0 2px 8px -2px rgba(37, 99, 235, 0.05), 0 1px 4px -1px rgba(15, 23, 42, 0.03)',
                        'soft-md': '0 12px 24px -6px rgba(37, 99, 235, 0.08), 0 4px 12px -2px rgba(15, 23, 42, 0.04)',
                        'soft-xl': '0 25px 50px -12px rgba(37, 99, 235, 0.12), 0 8px 24px -4px rgba(15, 23, 42, 0.06)',
                        'glow-blue': '0 0 35px -5px rgba(37, 99, 235, 0.35)',
                    }
                }
            }
        }
    </script>
    
    <style>
        body {
            background-color: #F8FAFC;
            color: #0F172A;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
        }
        
        .glass-header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border: 1px solid #E2E8F0;
            box-shadow: 0 10px 30px -10px rgba(37, 99, 235, 0.06), 0 4px 12px -2px rgba(15, 23, 42, 0.03);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card-hover:hover {
            border-color: #93C5FD;
            transform: translateY(-3px);
            box-shadow: 0 20px 40px -15px rgba(37, 99, 235, 0.14), 0 8px 16px -4px rgba(15, 23, 42, 0.05);
        }

        .gradient-brand-text {
            background: linear-gradient(135deg, #1E40AF 0%, #2563EB 50%, #0EA5E9 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #F1F5F9; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #2563EB; }
    </style>
    
    @yield('styles')
</head>
<body class="min-h-screen flex flex-col relative antialiased selection:bg-brand-500 selection:text-white">

    <!-- Ambient Light Background Gradients -->
    <div class="fixed top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-[500px] bg-gradient-to-b from-brand-100/60 via-blue-50/40 to-transparent blur-[120px] pointer-events-none -z-10"></div>
    <div class="fixed top-96 right-0 w-96 h-96 bg-cyan-100/40 blur-[130px] pointer-events-none -z-10"></div>
    <div class="fixed bottom-0 left-0 w-96 h-96 bg-blue-100/30 blur-[130px] pointer-events-none -z-10"></div>

    <!-- Top Navigation Bar -->
    <nav class="sticky top-0 z-50 glass-header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                
                <!-- Logo Brand -->
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-tr from-brand-600 to-brand-500 text-white flex items-center justify-center shadow-md shadow-brand-500/20 group-hover:scale-105 transition-all duration-300">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-lg sm:text-xl font-extrabold tracking-tight text-navy-900 flex items-center gap-1.5">
                            Trust<span class="text-brand-600">Guard</span>
                            <span class="inline-block w-2 h-2 rounded-full bg-brand-500 animate-ping"></span>
                        </span>
                        <span class="hidden sm:block text-[10px] uppercase font-bold tracking-widest text-navy-400 -mt-1">NextGen Web Security</span>
                    </div>
                </a>

                <!-- Desktop Navigation Menu -->
                <div class="hidden md:flex items-center space-x-1 bg-navy-100/80 p-1.5 rounded-2xl border border-navy-200/80">
                    <a href="{{ route('home') }}"
                       class="px-4 py-2 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('home') ? 'bg-white text-brand-600 shadow-sm' : 'text-navy-600 hover:text-navy-900 hover:bg-white/60' }}">
                        Beranda
                    </a>
                    <a href="{{ route('learn.index') }}"
                       class="px-4 py-2 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('learn.index') ? 'bg-white text-brand-600 shadow-sm' : 'text-navy-600 hover:text-navy-900 hover:bg-white/60' }}">
                        Akademi Keamanan
                    </a>
                    <a href="{{ route('report.index') }}"
                       class="px-4 py-2 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('report.index') ? 'bg-white text-brand-600 shadow-sm' : 'text-navy-600 hover:text-navy-900 hover:bg-white/60' }}">
                        Lapor Situs
                    </a>
                    <a href="{{ route('dashboard') }}"
                       class="px-4 py-2 rounded-xl text-sm font-bold transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-white text-brand-600 shadow-sm' : 'text-navy-600 hover:text-navy-900 hover:bg-white/60' }}">
                        Dashboard
                    </a>
                </div>

                <!-- Desktop CTA + Mobile Hamburger -->
                <div class="flex items-center gap-3">
                    <!-- Desktop CTA -->
                    <a href="{{ route('home') }}#scanner-box"
                       onclick="if(window.location.pathname === '/' || window.location.pathname === '') { document.getElementById('urlInput')?.focus(); }"
                       class="hidden md:inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm bg-brand-600 hover:bg-brand-700 text-white shadow-md shadow-brand-500/25 transition-all duration-300 hover:scale-[1.03]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Pindai URL
                    </a>
                    <!-- Mobile: Scan shortcut + Hamburger -->
                    <a href="{{ route('home') }}#scanner-box"
                       onclick="if(window.location.pathname === '/' || window.location.pathname === '') { document.getElementById('urlInput')?.focus(); }"
                       class="md:hidden flex items-center justify-center w-9 h-9 rounded-xl bg-brand-600 text-white shadow-md shadow-brand-500/25">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </a>
                    <button id="mobileMenuBtn" class="md:hidden flex items-center justify-center w-9 h-9 rounded-xl bg-navy-100 border border-navy-200 text-navy-700" onclick="toggleMobileMenu()">
                        <svg id="hamburgerIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg id="closeIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

            </div>
        </div>

        <!-- Mobile Dropdown Menu -->
        <div id="mobileMenu" class="md:hidden hidden border-t border-navy-200/80 bg-white/95 backdrop-blur-xl">
            <div class="max-w-7xl mx-auto px-4 py-4 space-y-1">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('home') ? 'bg-brand-50 text-brand-700 border border-brand-200' : 'text-navy-700 hover:bg-navy-100' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Beranda
                </a>
                <a href="{{ route('learn.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('learn.index') ? 'bg-brand-50 text-brand-700 border border-brand-200' : 'text-navy-700 hover:bg-navy-100' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    Akademi Keamanan
                </a>
                <a href="{{ route('report.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('report.index') ? 'bg-brand-50 text-brand-700 border border-brand-200' : 'text-navy-700 hover:bg-navy-100' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Lapor Situs
                </a>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('dashboard') ? 'bg-brand-50 text-brand-700 border border-brand-200' : 'text-navy-700 hover:bg-navy-100' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Dashboard
                </a>
            </div>
        </div>
    </nav>

    <script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        const hamburger = document.getElementById('hamburgerIcon');
        const close = document.getElementById('closeIcon');
        const isHidden = menu.classList.contains('hidden');
        menu.classList.toggle('hidden');
        hamburger.classList.toggle('hidden', !isHidden);
        close.classList.toggle('hidden', isHidden);
    }
    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
        const menu = document.getElementById('mobileMenu');
        const btn = document.getElementById('mobileMenuBtn');
        if (!menu.contains(e.target) && !btn.contains(e.target) && !menu.classList.contains('hidden')) {
            toggleMobileMenu();
        }
    });
    </script>

    <!-- Main Content -->
    <main class="flex-grow pb-20 md:pb-0">
        @yield('content')
    </main>

    <!-- Mobile Bottom Navigation Bar -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-xl border-t border-navy-200/80 shadow-[0_-4px_24px_-4px_rgba(15,23,42,0.08)]">
        <div class="grid grid-cols-4 h-16">
            <a href="{{ route('home') }}" class="flex flex-col items-center justify-center gap-1 {{ request()->routeIs('home') ? 'text-brand-600' : 'text-navy-400' }}">
                <svg class="w-5 h-5" fill="{{ request()->routeIs('home') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="text-[10px] font-extrabold uppercase tracking-wide">Beranda</span>
            </a>
            <a href="{{ route('learn.index') }}" class="flex flex-col items-center justify-center gap-1 {{ request()->routeIs('learn.index') ? 'text-brand-600' : 'text-navy-400' }}">
                <svg class="w-5 h-5" fill="{{ request()->routeIs('learn.index') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <span class="text-[10px] font-extrabold uppercase tracking-wide">Akademi</span>
            </a>
            <a href="{{ route('report.index') }}" class="flex flex-col items-center justify-center gap-1 {{ request()->routeIs('report.index') ? 'text-rose-600' : 'text-navy-400' }}">
                <svg class="w-5 h-5" fill="{{ request()->routeIs('report.index') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span class="text-[10px] font-extrabold uppercase tracking-wide">Lapor</span>
            </a>
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center gap-1 {{ request()->routeIs('dashboard') ? 'text-brand-600' : 'text-navy-400' }}">
                <svg class="w-5 h-5" fill="{{ request()->routeIs('dashboard') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span class="text-[10px] font-extrabold uppercase tracking-wide">Dashboard</span>
            </a>
        </div>
    </nav>

    <!-- Premium Footer -->
    <footer class="bg-white border-t border-navy-200/80 {{ request()->routeIs('home') ? 'mt-24' : 'mt-12' }}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 {{ request()->routeIs('home') ? 'py-14' : 'py-6' }}">

            @if(request()->routeIs('home'))
            {{-- Full footer hanya di halaman Beranda --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-brand-600 text-white flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-extrabold text-navy-900">TrustGuard</span>
                    </div>
                    <p class="text-navy-600 text-sm max-w-md leading-relaxed">
                        Mengubah data teknis keamanan web menjadi indikator intuitif <strong>Trust Score (0–100)</strong> untuk menciptakan ekosistem internet yang tepercaya bagi masyarakat Indonesia.
                    </p>
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-brand-50 border border-brand-200 text-xs font-semibold text-brand-700">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        ⚡ NextGen Cyber Safety & Trust Score Platform
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-extrabold text-navy-400 uppercase tracking-widest mb-4">Navigasi Utama</h4>
                    <ul class="space-y-3 text-sm font-semibold">
                        <li><a href="{{ route('home') }}" class="text-navy-600 hover:text-brand-600 transition-colors">Beranda</a></li>
                        <li><a href="{{ route('scan') }}" class="text-navy-600 hover:text-brand-600 transition-colors">Pemindai URL Real-Time</a></li>
                        <li><a href="{{ route('learn.index') }}" class="text-navy-600 hover:text-brand-600 transition-colors">Akademi Keamanan Digital</a></li>
                        <li><a href="{{ route('report.index') }}" class="text-navy-600 hover:text-brand-600 transition-colors">Lapor Situs Mencurigakan</a></li>
                        <li><a href="{{ route('dashboard') }}" class="text-navy-600 hover:text-brand-600 transition-colors">Executive Security Dashboard</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-extrabold text-navy-400 uppercase tracking-widest mb-4">Standar Keamanan</h4>
                    <ul class="space-y-2.5 text-xs text-navy-600 font-semibold mb-4">
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Enkripsi SSL/TLS 256-Bit
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-brand-600"></span>
                            Proteksi Real-Time SSRF Defense
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-brand-600"></span>
                            Inspeksi RDAP Protocol & DNS
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-brand-600"></span>
                            Threat Intelligence Engine
                        </li>
                    </ul>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-mono font-bold border border-emerald-200">
                        🛡️ System Protected 24/7
                    </span>
                </div>
            </div>
            <div class="border-t border-navy-200/80 pt-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-navy-500 font-medium">
                <p>&copy; 2026 TrustGuard. All rights reserved.</p>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="font-semibold text-emerald-700">System Online & Protected</span>
                </div>
            </div>
            @else
            {{-- Footer minimal di halaman lain --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-navy-500 font-medium">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-lg bg-brand-600 text-white flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <span class="font-bold text-navy-700">TrustGuard</span>
                    <span class="text-navy-300">•</span>
                    <p>&copy; 2026 All rights reserved.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="font-semibold text-emerald-700">System Online & Protected</span>
                </div>
            </div>
            @endif

        </div>
    </footer>
    <div class="md:hidden h-16"></div><!-- Spacer for mobile bottom nav -->

    @yield('scripts')
</body>
</html>
