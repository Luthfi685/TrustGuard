@extends('layouts.app')

@section('title', 'TrustGuard — Cek Validitas & Keamanan Link Sebelum Anda Percaya')

@section('content')
<!-- Hero Section -->
<section class="relative pt-8 sm:pt-14 pb-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="text-center space-y-6 max-w-4xl mx-auto">

        <!-- AI Security Intelligence Pill Badge (No Competition Text) -->
        <div class="inline-flex items-center gap-2.5 px-4 py-2 rounded-full bg-gradient-to-r from-brand-50 via-indigo-50 to-brand-50 border border-brand-200 text-brand-700 text-xs font-extrabold tracking-wide shadow-sm hover:scale-105 transition-all duration-300">
            <span class="flex h-2 w-2 relative">
                <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-600"></span>
            </span>
            <span>AI-Powered Multi-Vector Web Threat Intelligence</span>
        </div>

        <!-- Main Headline -->
        <h1 class="text-3xl sm:text-6xl lg:text-7xl font-black tracking-tight text-navy-900 leading-[1.15]">
            Cek Validitas & Keamanan Link<br>
            <span class="gradient-brand-text">Sebelum Anda Percaya.</span>
        </h1>

        <!-- Subtitle -->
        <p class="text-base sm:text-xl text-navy-600 max-w-2xl mx-auto leading-relaxed font-medium">
            Lindungi data pribadi dan transaksi Anda dari bahaya <strong>phishing, malware, dan website tiruan</strong>. Analisis sertifikat SSL, umur domain RDAP, dan protokol keamanan instan dalam hitungan detik.
        </p>

        <!-- Main Hero URL Search Bar & Real-time Scanner -->
        <div id="scanner-box" class="pt-4 max-w-2xl mx-auto text-left">
            <form id="heroScanForm" action="{{ route('home') }}" method="GET" class="relative group">
                <div class="p-2 rounded-2xl bg-white border-2 border-navy-200 shadow-soft-xl group-hover:border-brand-500 transition-all duration-300 flex items-center gap-2">
                    <div class="pl-4 text-navy-400 group-hover:text-brand-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" id="urlInput" name="url" value="{{ request('url') }}" required
                           placeholder="Ketik atau tempel link (cth: https://tokopedia.com)..."
                           class="w-full py-3.5 px-2 bg-transparent text-navy-900 placeholder-navy-400 font-semibold text-base focus:outline-none">
                    <button type="submit" id="heroSubmitBtn"
                            class="px-7 py-3.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-extrabold text-sm shadow-md shadow-brand-500/25 transition-all duration-300 hover:scale-[1.02] shrink-0 flex items-center gap-2">
                        <span>Analisis Sekarang</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Quick Click Samples -->
            <div class="flex flex-wrap items-center justify-center gap-2 mt-3 text-xs text-navy-500 font-semibold">
                <span class="text-navy-400">⚡ Coba Cepat:</span>
                <button type="button" onclick="quickFill('https://github.com')" class="px-2.5 py-1 rounded-lg bg-navy-100/80 hover:bg-brand-50 hover:text-brand-700 border border-navy-200 transition font-mono">github.com (100%)</button>
                <button type="button" onclick="quickFill('https://google.com')" class="px-2.5 py-1 rounded-lg bg-navy-100/80 hover:bg-brand-50 hover:text-brand-700 border border-navy-200 transition font-mono">google.com</button>
                <button type="button" onclick="quickFill('https://tokopedia.com')" class="px-2.5 py-1 rounded-lg bg-navy-100/80 hover:bg-brand-50 hover:text-brand-700 border border-navy-200 transition font-mono">tokopedia.com</button>
                <button type="button" onclick="quickFill('https://bca-mobile-verifikasi.xyz')" class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 transition font-mono">simulasi-scam.xyz</button>
            </div>

            <!-- Loading State Progress Card (Directly on Homepage) -->
            <div id="loadingCard" class="hidden mt-6 glass-card rounded-3xl p-6 sm:p-8 shadow-soft-xl space-y-6">
                <div class="flex items-center gap-4 p-4 rounded-2xl bg-brand-50 border border-brand-200 text-brand-800">
                    <div class="w-10 h-10 rounded-xl bg-brand-600 text-white flex items-center justify-center shrink-0 shadow-md">
                        <svg class="w-6 h-6 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 id="statusTitle" class="text-sm font-extrabold text-navy-900">Memeriksa URL...</h4>
                        <p id="statusSub" class="text-xs text-navy-600 font-medium">Engine sedang melakukan analisis multi-layer keamanan domain...</p>
                    </div>
                </div>

                <!-- Steps checklist -->
                <div class="space-y-3 text-xs font-semibold">
                    <div id="step1" class="flex items-center gap-3 text-navy-400">
                        <span class="w-5 h-5 rounded-full bg-navy-200 flex items-center justify-center text-[10px] font-bold">1</span>
                        <span>Validasi Alamat URL & SSRF Defense</span>
                    </div>
                    <div id="step2" class="flex items-center gap-3 text-navy-400">
                        <span class="w-5 h-5 rounded-full bg-navy-200 flex items-center justify-center text-[10px] font-bold">2</span>
                        <span>Inspeksi Stream Sertifikat SSL/TLS (Kriptografi)</span>
                    </div>
                    <div id="step3" class="flex items-center gap-3 text-navy-400">
                        <span class="w-5 h-5 rounded-full bg-navy-200 flex items-center justify-center text-[10px] font-bold">3</span>
                        <span>Pemeriksaan Umur Domain RDAP & Rekam DNS</span>
                    </div>
                    <div id="step4" class="flex items-center gap-3 text-navy-400">
                        <span class="w-5 h-5 rounded-full bg-navy-200 flex items-center justify-center text-[10px] font-bold">4</span>
                        <span>Kalkulasi Skor Tepercaya / Trust Score (0–100)</span>
                    </div>
                </div>
            </div>

            <!-- Features Badge row -->
            <div class="flex flex-wrap items-center justify-center gap-3 sm:gap-6 mt-5 text-xs font-semibold text-navy-500">
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Audit Kriptografi SSL
                </span>
                <span class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Deteksi Phishing Real-Time
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
        <div class="flex items-center gap-2 text-xs font-extrabold text-navy-600 uppercase tracking-widest">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
            Statistik Pemindaian Real-Time
        </div>
        <span class="text-[11px] font-mono text-brand-600 bg-brand-50 px-2.5 py-1 rounded-full border border-brand-200 font-bold">
            Live Stream Synchronized
        </span>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">

        <!-- Card 1: Total Users Protected -->
        <div class="glass-card glass-card-hover rounded-3xl p-6 flex items-center gap-4 transition-all duration-300">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 border border-indigo-200 text-indigo-600 flex items-center justify-center shrink-0 shadow-sm">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <div id="statUsers" data-target="{{ $userCount }}" class="text-2xl sm:text-3xl font-black text-indigo-600 tracking-tight font-mono">
                    {{ number_format($userCount) }}
                </div>
                <div class="text-xs font-extrabold uppercase tracking-wider text-navy-600 mt-0.5">Pengguna Aktif</div>
                <div class="text-[11px] text-navy-400 font-medium">Orang yang cek web</div>
            </div>
        </div>

        <!-- Card 2: Total Scans -->
        <div class="glass-card glass-card-hover rounded-3xl p-6 flex items-center gap-4 transition-all duration-300">
            <div class="w-14 h-14 rounded-2xl bg-brand-50 border border-brand-200 text-brand-600 flex items-center justify-center shrink-0 shadow-sm">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                </svg>
            </div>
            <div>
                <div id="statTotal" data-target="{{ $totalChecked }}" class="text-2xl sm:text-3xl font-black text-navy-900 tracking-tight font-mono">
                    {{ number_format($totalChecked) }}
                </div>
                <div class="text-xs font-extrabold uppercase tracking-wider text-navy-600 mt-0.5">Website Checked</div>
                <div class="text-[11px] text-navy-400 font-medium">Total pemindaian link</div>
            </div>
        </div>

        <!-- Card 3: Trusted Sites -->
        <div class="glass-card glass-card-hover rounded-3xl p-6 flex items-center gap-4 transition-all duration-300">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center shrink-0 shadow-sm">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <div id="statTrusted" data-target="{{ $trustedCount }}" class="text-2xl sm:text-3xl font-black text-emerald-600 tracking-tight font-mono">
                    {{ number_format($trustedCount) }}
                </div>
                <div class="text-xs font-extrabold uppercase tracking-wider text-navy-600 mt-0.5">Domain Aman</div>
                <div class="text-[11px] text-navy-400 font-medium">Hasil Skor ≥ 80</div>
            </div>
        </div>

        <!-- Card 4: Risk Detected -->
        <div class="glass-card glass-card-hover rounded-3xl p-6 flex items-center gap-4 transition-all duration-300">
            <div class="w-14 h-14 rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center shrink-0 shadow-sm">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <div id="statRisk" data-target="{{ $riskCount }}" class="text-2xl sm:text-3xl font-black text-rose-600 tracking-tight font-mono">
                    {{ number_format($riskCount) }}
                </div>
                <div class="text-xs font-extrabold uppercase tracking-wider text-navy-600 mt-0.5">Risiko Dicegah</div>
                <div class="text-[11px] text-navy-400 font-medium">Indikasi Phishing & Scam</div>
            </div>
        </div>

    </div>
</section>

<!-- How It Works (3 Steps) -->
<section class="py-20 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="text-center space-y-3 mb-16">
        <span class="text-xs font-extrabold uppercase tracking-widest text-brand-600 bg-brand-50 border border-brand-200 px-3.5 py-1.5 rounded-full">Proses Cepat & Cerdas</span>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-navy-900">Bagaimana TrustGuard Melindungi Anda?</h2>
        <p class="text-navy-600 text-base max-w-xl mx-auto font-medium">3 langkah mudah untuk mengetahui tingkat risiko situs web sebelum Anda memasukkan data atau bertransaksi.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
        <!-- Connecting line desktop -->
        <div class="hidden md:block absolute top-1/2 left-1/6 right-1/6 h-0.5 bg-gradient-to-r from-brand-200 via-brand-400 to-brand-200 -z-10 -translate-y-6"></div>

        <!-- Step 1 -->
        <div class="glass-card rounded-3xl p-8 text-center space-y-4 hover:-translate-y-1.5 transition-all duration-300">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-brand-600 to-brand-500 text-white font-black text-xl flex items-center justify-center mx-auto shadow-md shadow-brand-500/25">1</div>
            <h3 class="text-xl font-bold text-navy-900">Input Tautan / Domain</h3>
            <p class="text-navy-600 text-sm leading-relaxed">Cukup tempelkan link mencurigakan dari WhatsApp, SMS, email, atau media sosial ke kolom pemindai.</p>
        </div>

        <!-- Step 2 -->
        <div class="glass-card rounded-3xl p-8 text-center space-y-4 hover:-translate-y-1.5 transition-all duration-300">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-brand-600 to-brand-500 text-white font-black text-xl flex items-center justify-center mx-auto shadow-md shadow-brand-500/25">2</div>
            <h3 class="text-xl font-bold text-navy-900">Inspeksi Multi-Vektor</h3>
            <p class="text-navy-600 text-sm leading-relaxed">Engine otomatis mengaudit validitas sertifikat SSL, umur domain RDAP, rekam DNS, hingga mendeteksi pola phishing.</p>
        </div>

        <!-- Step 3 -->
        <div class="glass-card rounded-3xl p-8 text-center space-y-4 hover:-translate-y-1.5 transition-all duration-300">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-brand-600 to-brand-500 text-white font-black text-xl flex items-center justify-center mx-auto shadow-md shadow-brand-500/25">3</div>
            <h3 class="text-xl font-bold text-navy-900">Keputusan Instan</h3>
            <p class="text-navy-600 text-sm leading-relaxed">Dapatkan skor Trust Score (0–100) dan rekomendasi tindakan pencegahan dalam bahasa awam yang mudah dipahami.</p>
        </div>
    </div>
</section>

<!-- Feature Showcase Section -->
<section class="py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <div class="bg-gradient-to-br from-navy-900 via-navy-800 to-brand-900 rounded-3xl p-8 sm:p-14 text-white shadow-soft-xl relative overflow-hidden">
        <div class="relative z-10 max-w-2xl space-y-6">
            <span class="inline-block px-3.5 py-1.5 rounded-full bg-white/10 text-white text-xs font-extrabold tracking-wider border border-white/20 uppercase">NextGen Cyber Ecosystem</span>
            <h2 class="text-3xl sm:text-5xl font-black leading-tight">Membangun Ekosistem Web yang Tepercaya</h2>
            <p class="text-navy-200 text-base leading-relaxed font-medium">
                Dilengkapi dengan pertahanan SSRF, akademi simulasi phishing interaktif, dan pelaporan crowdsourced komunitas untuk mewujudkan ruang siber yang aman bagi seluruh masyarakat Indonesia.
            </p>
            <div class="pt-4 flex flex-wrap gap-4">
                <button type="button" onclick="document.getElementById('scanner-box').scrollIntoView({behavior: 'smooth'}); document.getElementById('urlInput')?.focus();"
                   class="px-7 py-3.5 rounded-xl bg-white text-brand-700 hover:bg-blue-50 font-extrabold text-sm shadow-lg transition-all hover:scale-105">
                    Mulai Pindai Link Gratis →
                </button>
                <a href="{{ route('learn.index') }}" class="px-7 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-extrabold text-sm border border-white/20 transition-all">
                    Asah Skill di Akademi 🎓
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
    animateNumber('statUsers');
    animateNumber('statTotal');
    animateNumber('statTrusted');
    animateNumber('statRisk');

    // 2. Real-time Live Polling every 3.5 seconds
    setInterval(fetchLiveStats, 3500);

    // 3. Scan URL Handler directly on homepage
    const urlParams = new URLSearchParams(window.location.search);
    const urlQuery = urlParams.get('url');
    if (urlQuery) {
        startScan(urlQuery);
    }

    const form = document.getElementById('heroScanForm');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const urlVal = document.getElementById('urlInput').value.trim();
            if (urlVal) {
                startScan(urlVal);
            }
        });
    }
});

function quickFill(url) {
    const input = document.getElementById('urlInput');
    if (input) {
        input.value = url;
        startScan(url);
    }
}

function startScan(urlVal) {
    const loadingCard = document.getElementById('loadingCard');
    const submitBtn = document.getElementById('heroSubmitBtn');
    const statusTitle = document.getElementById('statusTitle');
    const statusSub = document.getElementById('statusSub');

    if (loadingCard) loadingCard.classList.remove('hidden');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }

    // Scroll smoothly to loading area
    document.getElementById('scanner-box').scrollIntoView({ behavior: 'smooth' });

    const steps = [
        { id: 'step1', text: 'Validasi Alamat URL & SSRF Defense', sub: 'Memeriksa keabsahan format URL dan proteksi IP privat...' },
        { id: 'step2', text: 'Inspeksi Stream Sertifikat SSL/TLS', sub: 'Menganalisis penerbit SSL dan tanggal kedaluwarsa...' },
        { id: 'step3', text: 'Pemeriksaan Umur Domain RDAP & Rekam DNS', sub: 'Mengambil metadata registrasi domain dan IP server...' },
        { id: 'step4', text: 'Kalkulasi Trust Score (0–100)', sub: 'Menghitung bobot skor keamanan...' }
    ];

    let currentStep = 0;
    const interval = setInterval(() => {
        if (currentStep < steps.length) {
            const stepObj = steps[currentStep];
            if (statusTitle) statusTitle.textContent = stepObj.text;
            if (statusSub) statusSub.textContent = stepObj.sub;

            const el = document.getElementById(stepObj.id);
            if (el) {
                el.className = 'flex items-center gap-3 text-brand-600 font-bold';
                const circle = el.querySelector('span');
                if (circle) circle.className = 'w-5 h-5 rounded-full bg-brand-600 text-white flex items-center justify-center text-[10px] font-bold';
            }
            currentStep++;
        } else {
            clearInterval(interval);
            executeFetchScan(urlVal);
        }
    }, 600);
}

function executeFetchScan(urlVal) {
    fetch('{{ route("api.scan") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ url: urlVal })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success && data.redirect_url) {
            window.location.href = data.redirect_url;
        } else {
            alert(data.error || 'Terjadi kesalahan saat memindai domain.');
            location.reload();
        }
    })
    .catch(err => {
        console.error(err);
        alert('Gagal terhubung ke server pemindai.');
        location.reload();
    });
}

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
                updateStat('statUsers', data.userCount);
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
