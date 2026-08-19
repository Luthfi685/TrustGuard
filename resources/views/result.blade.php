@extends('layouts.app')

@section('title', 'Laporan Analisis Keamanan — ' . $scan->domain)

@section('content')
<section class="py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8">
    
    <!-- Top Action & Navigation Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 glass-card p-6 rounded-3xl">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="text-xs font-extrabold uppercase tracking-wider px-3 py-1 rounded-full bg-brand-50 text-brand-700 border border-brand-200">
                    Laporan Keamanan Domain
                </span>
                <span class="text-xs text-navy-400 font-mono">ID: #{{ $scan->id }}</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-navy-900 font-mono tracking-tight">
                {{ $scan->domain }}
            </h1>
            <p class="text-xs text-navy-500 font-medium">
                URL Asli: <span class="font-mono text-navy-700">{{ $scan->url }}</span> • Diinspeksi pada: {{ $scan->created_at->translatedFormat('d F Y, H:i') }} WIB
            </p>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            <button id="shareBtn" onclick="shareResult()"
                    class="px-4 py-2.5 rounded-xl bg-white hover:bg-navy-50 text-navy-700 text-xs font-bold border border-navy-200 shadow-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                </svg>
                Bagikan Laporan
            </button>
            <button onclick="window.print()"
                    class="px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-500/20 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak PDF
            </button>
            <a href="{{ route('scan', ['url' => $scan->url]) }}"
               class="px-4 py-2.5 rounded-xl bg-white hover:bg-navy-50 text-navy-700 text-xs font-bold border border-navy-200 shadow-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Pindai Ulang
            </a>
            <a href="{{ route('report.index', ['url' => $scan->url]) }}"
               class="px-4 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-extrabold border border-rose-200 shadow-sm transition-all flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                Laporkan Situs
            </a>
        </div>
    </div>

    <!-- Main Grid (Gauge Card & Details) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Card 1: Score Gauge Card -->
        <div class="glass-card p-8 rounded-3xl text-center flex flex-col items-center justify-center">
            <div class="text-xs font-extrabold uppercase tracking-widest text-navy-400 mb-6">Trust Score Result</div>

            <!-- SVG Animated Circular Score Gauge -->
            @php
                $circumference = 2 * M_PI * 42;
                $finalOffset = $circumference - ($scan->trust_score / 100) * $circumference;
            @endphp
            <div class="relative w-48 h-48 flex items-center justify-center mb-6">
                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                    <!-- Track Circle -->
                    <circle cx="50" cy="50" r="42" stroke="#E2E8F0" stroke-width="10" fill="transparent" />
                    <!-- Progress Circle -->
                    <circle id="scoreRing" cx="50" cy="50" r="42"
                            stroke="{{ $scan->status_color }}" stroke-width="10"
                            stroke-linecap="round" fill="transparent"
                            stroke-dasharray="{{ $circumference }}"
                            stroke-dashoffset="{{ $circumference }}"
                            style="transition: stroke-dashoffset 1.4s cubic-bezier(0.22,1,0.36,1);"/>
                </svg>
                <div class="absolute flex flex-col items-center justify-center">
                    <span id="scoreCounter" class="text-5xl font-black font-mono tracking-tight text-navy-900">0</span>
                    <span class="text-xs text-navy-500 font-bold uppercase tracking-wider">dari 100</span>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-extrabold border shadow-sm mb-4"
                 style="background-color: {{ $scan->status_color }}15; color: {{ $scan->status_color }}; border-color: {{ $scan->status_color }}30;">
                <span class="w-2.5 h-2.5 rounded-full animate-ping" style="background-color: {{ $scan->status_color }}"></span>
                Tingkat Keamanan: {{ $scan->status_label }}
            </div>

            <p class="text-xs text-navy-600 max-w-xs leading-relaxed font-medium">
                @if($scan->trust_score >= 80)
                    Domain ini aman untuk dijelajahi. Sertifikat SSL valid dan tidak terdaftar dalam database ancaman siber.
                @elseif($scan->trust_score >= 60)
                    Waspada! Terdeteksi beberapa indikator risiko ringan seperti domain tergolong baru atau sertifikat SSL mendekati kadaluarsa.
                @else
                    BAHAYA TINGGI! Hindari menginput kata sandi atau informasi kartu kredit di situs ini.
                @endif
            </p>

            @if($communityReportsCount > 0)
                <div class="mt-6 w-full p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span>Ada <strong>{{ $communityReportsCount }}</strong> Laporan Komunitas tentang domain ini.</span>
                </div>
            @endif

            <!-- Quick Report Threat CTA -->
            <div class="mt-6 pt-4 border-t border-navy-200/80 w-full text-center space-y-2">
                <span class="text-[11px] text-navy-500 font-medium block">Menemukan kejanggalan pada situs ini?</span>
                <a href="{{ route('report.index', ['url' => $scan->url]) }}"
                   class="w-full py-2.5 px-4 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-extrabold flex items-center justify-center gap-2 transition-all shadow-sm">
                    🚨 Laporkan Situs Mencurigakan
                </a>
            </div>
        </div>

        <!-- Metric Cards Grid (Card 2 & 3 Span 2) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- SSL & HTTPS Overview Card -->
            <div class="glass-card p-6 sm:p-8 rounded-3xl space-y-4">
                <div class="flex items-center justify-between border-b border-navy-200/80 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 border border-brand-200 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-navy-900">Sertifikat SSL / TLS</h3>
                            <p class="text-xs text-navy-500 font-medium">Inspeksi Enkripsi Koneksi Web</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold border
                        {{ ($scan->ssl_info['is_valid'] ?? false) ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">
                        {{ ($scan->ssl_info['is_valid'] ?? false) ? '✅ Valid & Enkripsi Aktif' : '❌ Tidak Valid / Tanpa SSL' }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                    <div class="p-4 rounded-2xl bg-navy-50/80 border border-navy-200/60">
                        <div class="text-[11px] font-bold uppercase tracking-wider text-navy-500">Penerbit (Issuer)</div>
                        <div class="text-sm font-extrabold text-navy-900 font-mono mt-1 truncate">
                            {{ $scan->ssl_info['issuer'] ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-navy-50/80 border border-navy-200/60">
                        <div class="text-[11px] font-bold uppercase tracking-wider text-navy-500">Masa Berlaku Sisa</div>
                        <div class="text-sm font-extrabold text-navy-900 font-mono mt-1">
                            {{ $scan->ssl_info['days_remaining'] ?? 0 }} Hari
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-navy-50/80 border border-navy-200/60">
                        <div class="text-[11px] font-bold uppercase tracking-wider text-navy-500">Kadaluarsa Pada</div>
                        <div class="text-sm font-extrabold text-navy-900 font-mono mt-1 truncate">
                            {{ $scan->ssl_info['valid_to'] ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- RDAP & Domain Age Overview Card -->
            @php
                $createdStr = $scan->rdap_info['created_at'] ?? null;
                $ageFormatted = $scan->rdap_info['age_formatted'] ?? null;
                $ageDays = $scan->rdap_info['age_days'] ?? null;

                if ($createdStr && strtotime($createdStr)) {
                    try {
                        $start = new \DateTime($createdStr);
                        $end = new \DateTime($scan->created_at ?? 'now');
                        $interval = $start->diff($end);
                        $years = $interval->y;
                        $months = $interval->m;
                        $days = $interval->d;
                        if (!$ageFormatted) {
                            $ageFormatted = "{$years} Tahun, {$months} Bulan, {$days} Hari";
                        }
                        if (!$ageDays) {
                            $ageDays = (int)floor((strtotime($scan->created_at ?? 'now') - strtotime($createdStr)) / 86400);
                        }
                    } catch (\Throwable $e) {}
                }
            @endphp
            <div class="glass-card p-6 sm:p-8 rounded-3xl space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-navy-200/80 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 border border-brand-200 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-navy-900">Metadata Registrasi RDAP</h3>
                            <p class="text-xs text-navy-500 font-medium">Informasi Umur Domain & Registrar</p>
                        </div>
                    </div>
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-brand-50 text-brand-700 border border-brand-200 font-mono shadow-sm">
                        ⏳ {{ $ageFormatted ?? (($scan->rdap_info['age_years'] ?? 0) . ' Tahun') }}
                    </span>
                </div>

                <!-- Domain Age Detailed Banner -->
                <div class="p-4 rounded-2xl bg-gradient-to-r from-brand-50/70 to-blue-50/40 border border-brand-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="space-y-0.5 text-center sm:text-left">
                        <span class="text-[11px] font-extrabold uppercase tracking-wider text-brand-700">Durasi Masa Aktif Domain</span>
                        <div class="text-base font-black text-navy-900 font-mono">
                            {{ $ageFormatted ?? 'Informasi RDAP Terproteksi' }}
                        </div>
                    </div>
                    @if($ageDays)
                        <div class="px-3.5 py-1.5 rounded-xl bg-white text-brand-700 font-mono font-extrabold text-xs border border-brand-200 shadow-sm shrink-0">
                            Total: {{ number_format($ageDays) }} Hari Aktif
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-1">
                    <div class="p-4 rounded-2xl bg-navy-50/80 border border-navy-200/60">
                        <div class="text-[11px] font-bold uppercase tracking-wider text-navy-500">Registrar</div>
                        <div class="text-sm font-extrabold text-navy-900 font-mono mt-1 truncate" title="{{ $scan->rdap_info['registrar'] ?? 'N/A' }}">
                            {{ $scan->rdap_info['registrar'] ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-navy-50/80 border border-navy-200/60">
                        <div class="text-[11px] font-bold uppercase tracking-wider text-navy-500">Tanggal Didaftarkan</div>
                        <div class="text-sm font-extrabold text-navy-900 font-mono mt-1 truncate">
                            {{ $scan->rdap_info['created_at'] ?? 'N/A' }}
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-navy-50/80 border border-navy-200/60">
                        <div class="text-[11px] font-bold uppercase tracking-wider text-navy-500">Server IP Alamat</div>
                        <div class="text-sm font-extrabold text-navy-900 font-mono mt-1 truncate">
                            {{ $scan->ip_address ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- Recommendations Card -->
    <div class="glass-card p-6 sm:p-8 rounded-3xl space-y-4">
        <h3 class="text-base font-extrabold text-navy-900">Rekomendasi Tindakan Keamanan Pengguna</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @if(!empty($scan->recommendations))
                @foreach($scan->recommendations as $rec)
                    <div class="p-4 rounded-2xl border flex items-start gap-3 text-xs leading-relaxed font-semibold
                        {{ $rec['type'] === 'danger' ? 'bg-rose-50 border-rose-200 text-rose-800' : '' }}
                        {{ $rec['type'] === 'warning' ? 'bg-amber-50 border-amber-200 text-amber-800' : '' }}
                        {{ $rec['type'] === 'safe' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : '' }}">
                        <span class="mt-0.5 text-base">
                            {{ $rec['type'] === 'danger' ? '🚨' : ($rec['type'] === 'warning' ? '⚠️' : '✅') }}
                        </span>
                        <div>
                            <strong class="block font-extrabold mb-0.5">
                                {{ $rec['type'] === 'danger' ? 'Peringatan Risiko Tinggi' : ($rec['type'] === 'warning' ? 'Perhatian Keamanan' : 'Rekomendasi Aman') }}
                            </strong>
                            <span>{{ $rec['text'] }}</span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const finalScore = {{ $scan->trust_score }};
    const finalOffset = {{ $finalOffset }};
    const ring = document.getElementById('scoreRing');
    const counter = document.getElementById('scoreCounter');

    requestAnimationFrame(() => {
        setTimeout(() => {
            if (ring) ring.style.strokeDashoffset = finalOffset;
        }, 150);
    });

    let current = 0;
    const duration = 1400;
    const stepTime = 16;
    const steps = Math.ceil(duration / stepTime);
    const increment = finalScore / steps;
    const timer = setInterval(() => {
        current = Math.min(current + increment, finalScore);
        if (counter) counter.textContent = Math.round(current);
        if (current >= finalScore) clearInterval(timer);
    }, stepTime);
});

function shareResult() {
    const url = window.location.href;
    const domain = '{{ $scan->domain }}';
    const score = {{ $scan->trust_score }};
    const label = '{{ $scan->status_label }}';
    const text = `TrustGuard Security Report\nDomain: ${domain}\nTrust Score: ${score}/100 — ${label}\nCek sendiri:`;

    if (navigator.share) {
        navigator.share({ title: 'TrustGuard — Laporan Keamanan', text: text, url: url })
            .catch(() => {});
    } else {
        navigator.clipboard.writeText(url).then(() => {
            const btn = document.getElementById('shareBtn');
            const original = btn.innerHTML;
            btn.innerHTML = `<svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Link Tersalin!`;
            btn.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-200');
            setTimeout(() => { btn.innerHTML = original; btn.classList.remove('bg-emerald-50', 'text-emerald-700', 'border-emerald-200'); }, 2500);
        });
    }
}
</script>
@endsection
