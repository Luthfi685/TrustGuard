<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — TrustGuard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
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
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body { background-color: #F8FAFC; font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(16px); border: 1px solid #E2E8F0; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center text-navy-900 overflow-hidden relative px-4 antialiased">

    <!-- Ambient Gradients -->
    <div class="absolute inset-0 pointer-events-none overflow-hidden -z-10">
        <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[600px] h-[600px] rounded-full bg-brand-100/60 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-[400px] h-[400px] rounded-full bg-blue-50/50 blur-3xl"></div>
    </div>

    <div class="relative z-10 w-full max-w-md">
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-brand-600 text-white flex items-center justify-center shadow-lg shadow-brand-500/25">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-black text-navy-900 tracking-tight">Admin TrustGuard</h1>
            <p class="text-sm text-navy-600 font-medium mt-1">Masuk sebagai administrator sistem</p>
        </div>

        <!-- Login Card -->
        <div class="glass-card rounded-3xl p-8 shadow-xl">
            @if(session('error'))
                <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-semibold flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-5">
                @csrf

                <div class="space-y-1.5">
                    <label class="text-xs font-extrabold text-navy-700 uppercase tracking-wider">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" required
                           placeholder="admin"
                           class="w-full px-4 py-3 rounded-xl bg-navy-50 border border-navy-200 text-navy-900 text-sm font-semibold placeholder-navy-400 focus:outline-none focus:border-brand-600 focus:bg-white transition-all">
                    @if(isset($errors) && $errors->has('username'))
                        <p class="text-xs text-rose-600 font-semibold mt-1">{{ $errors->first('username') }}</p>
                    @endif
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-extrabold text-navy-700 uppercase tracking-wider">Password</label>
                    <div class="relative">
                        <input id="passwordInput" type="password" name="password" required
                               placeholder="••••••••••"
                               class="w-full px-4 py-3 pr-12 rounded-xl bg-navy-50 border border-navy-200 text-navy-900 text-sm font-semibold placeholder-navy-400 focus:outline-none focus:border-brand-600 focus:bg-white transition-all">
                        <button type="button" onclick="togglePass()" class="absolute right-3 top-1/2 -translate-y-1/2 text-navy-400 hover:text-navy-700 transition-colors">
                            <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Default Credentials Info -->
                <div class="p-3.5 rounded-xl bg-brand-50 border border-brand-200 text-xs text-brand-800 space-y-1 font-semibold">
                    <p class="font-bold">🔐 Kredensial Default:</p>
                    <p>Username: <span class="font-mono bg-white px-2 py-0.5 rounded border border-brand-200">admin</span></p>
                    <p>Password: <span class="font-mono bg-white px-2 py-0.5 rounded border border-brand-200">trustguard2026</span></p>
                </div>

                <button type="submit"
                        class="w-full py-3.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-extrabold text-sm shadow-md shadow-brand-500/25 transition-all duration-300 hover:scale-[1.01]">
                    🔒 Masuk ke Admin Panel
                </button>
            </form>
        </div>

        <!-- Back Link -->
        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-xs text-navy-500 font-semibold hover:text-brand-600 transition-colors">
                ← Kembali ke Situs Utama
            </a>
        </div>
    </div>

    <script>
    function togglePass() {
        const input = document.getElementById('passwordInput');
        input.type = input.type === 'password' ? 'text' : 'password';
    }
    </script>
</body>
</html>
