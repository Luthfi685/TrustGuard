@extends('layouts.app')

@section('title', 'TrustGuard — Browse with confidence. Know before you trust.')

@section('content')
<!-- Hero Section -->
<section class="relative pt-12 pb-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="text-center space-y-6 max-w-4xl mx-auto">

        <!-- Competition Pill Badge -->
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-brand-50 border border-brand-200 text-brand-700 text-xs font-extrabold tracking-wide uppercase shadow-sm">
            <span class="w-2 h-2 rounded-full bg-brand-600 animate-pulse"></span>
            SWITCHFEST 2026 WEB DEVELOPMENT COMPETITION
        </div>

        <!-- Main Headline -->
        <h1 class="text-3xl sm:text-6xl lg:text-7xl font-black tracking-tight text-navy-900 leading-[1.1]">
            Browse with confidence.<br>
            <span class="gradient-brand-text">Know before you trust.</span>
        </h1>

        <!-- Subtitle -->
        <p class="text-base sm:text-xl text-navy-600 max-w-2xl mx-auto leading-relaxed font-medium">
            Platform intelijen keamanan siber otomatis yang mengonversi SSL, statistik domain RDAP, dan analisis ancaman siber menjadi <strong>Trust Score (0–100)</strong> yang intuitif.
        </p>

        <!-- Main Hero URL Search Bar -->
        <div class="pt-6 max-w-2xl mx-auto">
            <form action="{{ route('scan') }}" method="GET" class="relative group">
                <div class="p-2 rounded-2xl bg-white border-2 border-navy-200 shadow-soft-xl group-hover:border-brand-500 transition-all duration-300 flex items-center gap-2">
                    <div class="pl-4 text-navy-400 group-hover:text-brand-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="url" required
                           placeholder="Ketik atau tempel URL (cth: https://tokopedia.com)..."
                           class="w-full py-3.5 px-2 bg-transparent text-navy-900 placeholder-navy-400 font-semibold text-base focus:outline-none">
                    <button type="submit"
                            class="px-7 py-3.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-extrabold text-sm shadow-md shadow-brand-500/25 transition-all duration-300 hover:scale-[1.02] shrink-0 flex items-center gap-2">
                        <span>Analisis Sekarang</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </form>

            <div class="flex flex-wrap items-center justify-center gap-3 sm:gap-6 mt-4 text-xs font-semibold text-navy-500">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Pemindai Real-Time
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Tanpa Perlu Install App
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Protected by SSRF Defense
                </span>
            </div>
        </div>

    </div>
</section>

<!-- Live Counter Stats Grid -->
<section class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-4">
    <div class="flex items-center justify-between px-2">
        <div class="flex items-center gap-2 text-xs font-extrabold text-navy-500 uppercase tracking-widest">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
            Statistik Inspeksi Real-Time Engine
        </div>
        <span class="text-[11px] font-mono text-brand-600 bg-brand-50 px-2.5 py-1 rounded-full border border-brand-200 font-bold">
            Live Stream Updated
        </span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">

        <!-- Card 1: Total Scans -->
        <div class="glass-card glass-card-hover rounded-3xl p-7 flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-brand-50 border border-brand-200 text-brand-600 flex items-center justify-center shrink-0 shadow-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div id="statTotal" data-target="{{ $totalChecked }}" class="text-3xl font-black text-navy-900 tracking-tight font-mono">
                    {{ number_format($totalChecked) }}
                </div>
                <div class="text-xs font-extrabold uppercase tracking-wider text-navy-500 mt-1">Website Checked</div>
                <div class="text-[11px] text-navy-400 font-medium">Pemindai real-time aktif</div>
            </div>
        </div>

        <!-- Card 2: Trusted Sites -->
        <div class="glass-card glass-card-hover rounded-3xl p-7 flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center shrink-0 shadow-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <div id="statTrusted" data-target="{{ $trustedCount }}" class="text-3xl font-black text-emerald-600 tracking-tight font-mono">
                    {{ number_format($trustedCount) }}
                </div>
                <div class="text-xs font-extrabold uppercase tracking-wider text-navy-500 mt-1">Trusted Domain</div>
                <div class="text-[11px] text-navy-400 font-medium">Trust Score ≥ 80</div>
            </div>
        </div>

        <!-- Card 3: Risk Detected -->
        <div class="glass-card glass-card-hover rounded-3xl p-7 flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center shrink-0 shadow-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <div id="statRisk" data-target="{{ $riskCount }}" class="text-3xl font-black text-rose-600 tracking-tight font-mono">
                    {{ number_format($riskCount) }}
                </div>
                <div class="text-xs font-extrabold uppercase tracking-wider text-navy-500 mt-1">Risk Detected</div>
                <div class="text-[11px] text-navy-400 font-medium">Indikasi Phishing/Befilter</div>
            </div>
        </div>

    </div>
</section>

<!-- How It Works (3 Steps) -->
<section class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="text-center space-y-3 mb-16">
        <span class="text-xs font-extrabold uppercase tracking-widest text-brand-600 bg-brand-50 border border-brand-200 px-3.5 py-1.5 rounded-full">Proses Kerja</span>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-navy-900">Bagaimana TrustGuard Bekerja?</h2>
        <p class="text-navy-600 text-base max-w-xl mx-auto font-medium">3 langkah mudah untuk mengetahui tingkat keamanan dan validitas situs web sebelum Anda bertransaksi.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
        <!-- Connecting line desktop -->
        <div class="hidden md:block absolute top-1/2 left-1/6 right-1/6 h-0.5 bg-gradient-to-r from-brand-200 via-brand-400 to-brand-200 -z-10 -translate-y-6"></div>

        <!-- Step 1 -->
        <div class="glass-card rounded-3xl p-8 text-center space-y-4">
            <div class="w-14 h-14 rounded-2xl bg-brand-600 text-white font-black text-xl flex items-center justify-center mx-auto shadow-md shadow-brand-500/25">1</div>
            <h3 class="text-xl font-bold text-navy-900">Tempelkan URL</h3>
            <p class="text-navy-600 text-sm leading-relaxed">Masukkan tautan web yang ingin Anda cek ke kolom pemindai real-time TrustGuard.</p>
        </div>

        <!-- Step 2 -->
        <div class="glass-card rounded-3xl p-8 text-center space-y-4">
            <div class="w-14 h-14 rounded-2xl bg-brand-600 text-white font-black text-xl flex items-center justify-center mx-auto shadow-md shadow-brand-500/25">2</div>
            <h3 class="text-xl font-bold text-navy-900">Analisis Otomatis</h3>
            <p class="text-navy-600 text-sm leading-relaxed">Engine memindai SSL, umur domain RDAP, DNS record, security headers, dan threat intel secara otomatis.</p>
        </div>

        <!-- Step 3 -->
        <div class="glass-card rounded-3xl p-8 text-center space-y-4">
            <div class="w-14 h-14 rounded-2xl bg-brand-600 text-white font-black text-xl flex items-center justify-center mx-auto shadow-md shadow-brand-500/25">3</div>
            <h3 class="text-xl font-bold text-navy-900">Dapatkan Trust Score</h3>
            <p class="text-navy-600 text-sm leading-relaxed">Terima laporan visual Trust Score (0–100) beserta panduan tindakan aman dalam bahasa yang mudah dipahami.</p>
        </div>
    </div>
</section>

<!-- Feature Showcase Grid -->
<section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="bg-gradient-to-br from-brand-600 via-brand-700 to-navy-900 rounded-3xl p-10 sm:p-14 text-white shadow-soft-xl relative overflow-hidden">
        <div class="relative z-10 max-w-2xl space-y-6">
            <span class="inline-block px-3.5 py-1.5 rounded-full bg-white/10 text-white text-xs font-extrabold tracking-wider border border-white/20 uppercase">NextGen Cyber Protection</span>
            <h2 class="text-3xl sm:text-5xl font-black leading-tight">Melindungi Anda dari Penipuan Web & Phishing</h2>
            <p class="text-blue-100 text-base leading-relaxed font-medium">
                Dilengkapi dengan proteksi SSRF Defense, edukasi kuis interaktif, dan feed laporan komunitas untuk menciptakan ekosistem internet Indonesia yang lebih aman.
            </p>
            <div class="pt-4 flex flex-wrap gap-4">
                <a href="{{ route('scan') }}" class="px-7 py-3.5 rounded-xl bg-white text-brand-700 hover:bg-blue-50 font-extrabold text-sm shadow-lg transition-all hover:scale-105">
                    Coba Pemindai Sekarang →
                </a>
                <a href="{{ route('learn.index') }}" class="px-7 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-extrabold text-sm border border-white/20 transition-all">
                    Akademi Keamanan Digital
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Number ticker animation on initial load
    animateNumber('statTotal');
    animateNumber('statTrusted');
    animateNumber('statRisk');

    // 2. Real-time Live Polling every 3.5 seconds
    setInterval(fetchLiveStats, 3500);
});

function animateNumber(elementId) {
    const el = document.getElementById(elementId);
    if (!el) return;
    const target = parseInt(el.getAttribute('data-target')) || 0;
    let current = 0;
    const duration = 1200;
    const stepTime = 16;
    const steps = Math.ceil(duration / stepTime);
    const increment = target / steps;

    const timer = setInterval(() => {
        current = Math.min(current + increment, target);
        el.textContent = Math.round(current).toLocaleString('en-US');
        if (current >= target) clearInterval(timer);
    }, stepTime);
}

function fetchLiveStats() {
    fetch('{{ route("api.stats") }}')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                updateStat('statTotal', data.totalChecked);
                updateStat('statTrusted', data.trustedCount);
                updateStat('statRisk', data.riskCount);
            }
        })
        .catch(err => console.log('Stats sync error:', err));
}

function updateStat(elementId, newValue) {
    const el = document.getElementById(elementId);
    if (!el) return;
    const currentVal = parseInt(el.getAttribute('data-target')) || 0;

    if (newValue !== currentVal) {
        el.setAttribute('data-target', newValue);
        el.textContent = newValue.toLocaleString('en-US');
        
        el.classList.add('scale-110', 'text-brand-600');
        setTimeout(() => {
            el.classList.remove('scale-110', 'text-brand-600');
        }, 500);
    }
}
</script>
@endsection
