<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — TrustGuard Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        navy: { 50: '#F8FAFC', 100: '#F1F5F9', 200: '#E2E8F0', 300: '#CBD5E1', 400: '#94A3B8', 500: '#64748B', 600: '#475569', 700: '#334155', 800: '#1E293B', 900: '#0F172A' },
                        brand: { 50: '#EFF6FF', 100: '#DBEAFE', 600: '#2563EB', 700: '#1D4ED8' }
                    },
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                }
            }
        }
    </script>
    <style>
        body { background-color: #F8FAFC; color: #0F172A; font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-panel { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(16px); border: 1px solid #E2E8F0; }
        ::-webkit-scrollbar { width: 6px; } ::-webkit-scrollbar-track { background: #F1F5F9; } ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 3px; }
    </style>
</head>
<body class="min-h-screen flex antialiased">

    <!-- Sidebar -->
    <aside class="w-64 shrink-0 bg-white border-r border-navy-200 min-h-screen flex flex-col shadow-sm">
        <!-- Brand -->
        <div class="p-6 border-b border-navy-200">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-brand-600 text-white flex items-center justify-center shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-base font-extrabold text-navy-900">TrustGuard</span>
                    <span class="block text-[10px] font-extrabold text-brand-600 uppercase tracking-widest -mt-0.5">Admin Panel</span>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-extrabold transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-brand-50 text-brand-600 border border-brand-200 shadow-sm' : 'text-navy-600 hover:text-navy-900 hover:bg-navy-50' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('admin.scans') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-extrabold transition-colors {{ request()->routeIs('admin.scans') ? 'bg-brand-50 text-brand-600 border border-brand-200 shadow-sm' : 'text-navy-600 hover:text-navy-900 hover:bg-navy-50' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Kelola Scan
            </a>

            <a href="{{ route('admin.reports') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-extrabold transition-colors {{ request()->routeIs('admin.reports') ? 'bg-brand-50 text-brand-600 border border-brand-200 shadow-sm' : 'text-navy-600 hover:text-navy-900 hover:bg-navy-50' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Laporan Komunitas
            </a>

            <div class="pt-4 mt-4 border-t border-navy-200">
                <a href="{{ route('home') }}" target="_blank"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-extrabold text-navy-600 hover:text-navy-900 hover:bg-navy-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Lihat Situs Utama
                </a>

                <form action="{{ route('admin.logout') }}" method="POST" class="mt-1">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-extrabold text-rose-600 hover:bg-rose-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout Admin
                    </button>
                </form>
            </div>
        </nav>

        <!-- Admin Badge -->
        <div class="p-4 border-t border-navy-200">
            <div class="flex items-center gap-3 p-3 rounded-xl bg-navy-50 border border-navy-200">
                <div class="w-8 h-8 rounded-lg bg-brand-600 text-white flex items-center justify-center text-xs font-bold">👤</div>
                <div>
                    <span class="text-xs font-bold text-navy-900 block">{{ session('admin_user', 'admin') }}</span>
                    <span class="text-[10px] text-navy-500 font-semibold">Administrator</span>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 min-h-screen overflow-auto">
        <!-- Top Bar -->
        <div class="sticky top-0 z-30 bg-white border-b border-navy-200 px-6 py-4 flex items-center justify-between shadow-sm">
            <div>
                <h1 class="text-lg font-black text-navy-900">@yield('page-title', 'Dashboard')</h1>
                <p class="text-xs text-navy-500 font-medium">@yield('page-subtitle', 'Panel Administrasi TrustGuard')</p>
            </div>
            <div class="text-xs text-navy-500 font-mono font-bold">
                {{ now()->translatedFormat('d M Y, H:i') }} WIB
            </div>
        </div>

        <!-- Flash Messages -->
        <div class="px-6 pt-4">
            @if(session('success'))
                <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-semibold flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <div class="px-6 pb-10">
            @yield('content')
        </div>
    </main>

</body>
</html>
