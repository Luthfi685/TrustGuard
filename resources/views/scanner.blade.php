@extends('layouts.app')

@section('title', 'Pemindai URL & Domain Real-Time — TrustGuard')

@section('content')
<section class="py-14 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto space-y-10">

    <!-- Header Section -->
    <div class="text-center space-y-3">
        <span class="text-xs font-extrabold uppercase tracking-widest text-brand-600 bg-brand-50 border border-brand-200 px-3.5 py-1.5 rounded-full">
            Real-Time Security Inspection
        </span>
        <h1 class="text-3xl sm:text-5xl font-black text-navy-900 tracking-tight">
            Pemindai Keamanan URL & Domain
        </h1>
        <p class="text-navy-600 text-base max-w-xl mx-auto font-medium">
            Masukkan URL situs web untuk menganalisis sertifikat SSL, umur domain RDAP, rekam DNS, dan ancaman siber secara otomatis.
        </p>
    </div>

    <!-- Scanner Input Card -->
    <div class="glass-card rounded-3xl p-8 sm:p-10 shadow-soft-xl relative overflow-hidden">
        <form id="scanForm" action="{{ route('scan') }}" method="GET" class="space-y-6">
            <div class="space-y-2">
                <label for="urlInput" class="block text-xs font-extrabold text-navy-700 uppercase tracking-wider">Alamat URL Situs Web</label>
                <div class="relative">
                    <input type="text" id="urlInput" name="url" value="{{ request('url') }}" required
                           placeholder="https://tokopedia.com atau https://shopee.co.id"
                           class="w-full pl-12 pr-4 py-4 rounded-2xl bg-navy-50 border-2 border-navy-200 text-navy-900 placeholder-navy-400 font-semibold text-base focus:outline-none focus:border-brand-600 focus:bg-white transition-all shadow-inner">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-navy-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <button type="submit" id="submitBtn"
                    class="w-full py-4 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-extrabold text-base shadow-md shadow-brand-500/25 transition-all duration-300 hover:scale-[1.01] flex items-center justify-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>Mulai Pemindai Real-Time</span>
            </button>
        </form>

        <!-- Loading State Progress Card -->
        <div id="loadingCard" class="hidden mt-8 pt-8 border-t border-navy-200 space-y-6">
            <div class="flex items-center gap-4 p-4 rounded-2xl bg-brand-50 border border-brand-200 text-brand-800">
                <div class="w-10 h-10 rounded-xl bg-brand-600 text-white flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <div>
                    <h4 id="statusTitle" class="text-sm font-extrabold text-navy-900">Memeriksa URL...</h4>
                    <p id="statusSub" class="text-xs text-navy-600 font-medium">Engine sedang melakukan analisis keamanan domain...</p>
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
                    <span>Inspeksi Stream Sertifikat SSL/TLS</span>
                </div>
                <div id="step3" class="flex items-center gap-3 text-navy-400">
                    <span class="w-5 h-5 rounded-full bg-navy-200 flex items-center justify-center text-[10px] font-bold">3</span>
                    <span>Pemeriksaan Umur Domain RDAP & Rekam DNS</span>
                </div>
                <div id="step4" class="flex items-center gap-3 text-navy-400">
                    <span class="w-5 h-5 rounded-full bg-navy-200 flex items-center justify-center text-[10px] font-bold">4</span>
                    <span>Kalkulasi Trust Score (0–100)</span>
                </div>
            </div>
        </div>
    </div>

</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const urlQuery = urlParams.get('url');

    if (urlQuery) {
        startScan(urlQuery);
    }

    document.getElementById('scanForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const urlVal = document.getElementById('urlInput').value.trim();
        if (urlVal) {
            startScan(urlVal);
        }
    });
});

function startScan(urlVal) {
    const loadingCard = document.getElementById('loadingCard');
    const submitBtn = document.getElementById('submitBtn');
    const statusTitle = document.getElementById('statusTitle');
    const statusSub = document.getElementById('statusSub');

    loadingCard.classList.remove('hidden');
    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');

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
            statusTitle.textContent = stepObj.text;
            statusSub.textContent = stepObj.sub;

            const el = document.getElementById(stepObj.id);
            if (el) {
                el.className = 'flex items-center gap-3 text-brand-600 font-bold';
                el.querySelector('span').className = 'w-5 h-5 rounded-full bg-brand-600 text-white flex items-center justify-center text-[10px] font-bold';
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
</script>
@endsection
