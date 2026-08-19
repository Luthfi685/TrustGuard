@extends('layouts.app')

@section('title', 'Akademi Keamanan Digital & Gamifikasi — TrustGuard')

@section('content')
<section class="py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-12">
    
    <!-- Hero Header -->
    <div class="text-center space-y-3 max-w-3xl mx-auto">
        <span class="text-xs font-extrabold uppercase tracking-widest text-brand-600 bg-brand-50 border border-brand-200 px-4 py-1.5 rounded-full shadow-sm">
            Digital Safety Academy
        </span>
        <h1 class="text-3xl sm:text-5xl font-black text-navy-900 tracking-tight">
            Akademi Keamanan & Gamifikasi Kuis
        </h1>
        <p class="text-navy-600 text-base font-medium">
            Tingkatkan kewaspadaan siber Anda melalui simulasi kasus nyata, kuis interaktif, kumpulkan poin XP, dan raih Lencana Keahlian Digital.
        </p>
    </div>

    <!-- User XP & Level Overview Banner Card -->
    <div class="glass-card p-8 sm:p-10 rounded-3xl space-y-8 border-t-4 border-t-brand-600 shadow-soft-xl">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6 border-b border-navy-200/80 pb-6">
            <div class="flex items-center gap-5">
                <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-brand-600 to-brand-500 text-white font-black text-3xl flex items-center justify-center shadow-lg shadow-brand-500/30 shrink-0">
                    Lvl {{ $userProgress->level }}
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl font-black text-navy-900">{{ $userProgress->level_title }}</span>
                        <span class="px-3 py-1 rounded-full bg-brand-50 text-brand-700 text-xs font-extrabold font-mono border border-brand-200 shadow-sm">
                            {{ number_format($userProgress->points) }} XP Total
                        </span>
                    </div>
                    <p class="text-xs text-navy-500 font-medium">
                        Selesaikan kuis simulasi di bawah untuk menaikkan level keahlian hingga <strong>Level 5 (Trust Guardian)</strong>.
                    </p>
                </div>
            </div>

            <!-- Level Progress Bar -->
            <div class="w-full md:w-72 space-y-2">
                <div class="flex justify-between text-xs font-extrabold text-navy-700">
                    <span>Progress Level {{ $userProgress->level }}</span>
                    <span class="font-mono text-brand-600">{{ $userProgress->points % 200 }} / 200 XP</span>
                </div>
                <div class="w-full h-3.5 rounded-full bg-navy-100 p-0.5 border border-navy-200">
                    <div class="h-full rounded-full bg-gradient-to-r from-brand-600 to-brand-500 transition-all duration-500 shadow-sm"
                         style="width: {{ min(100, (($userProgress->points % 200) / 200) * 100) }}%"></div>
                </div>
            </div>
        </div>

        <!-- 5 Level Roadmap Stepper -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 pt-2">
            @php
                $roadmap = [
                    1 => ['title' => 'Digital Beginner', 'icon' => '🛡️', 'xp' => '0 XP'],
                    2 => ['title' => 'Cyber Explorer', 'icon' => '🔍', 'xp' => '200 XP'],
                    3 => ['title' => 'Security Sentinel', 'icon' => '⚡', 'xp' => '400 XP'],
                    4 => ['title' => 'Threat Hunter', 'icon' => '🎯', 'xp' => '600 XP'],
                    5 => ['title' => 'Trust Guardian', 'icon' => '👑', 'xp' => '800 XP'],
                ];
            @endphp
            @foreach($roadmap as $lvlNum => $lvlInfo)
                @php $isCurrent = ($userProgress->level == $lvlNum); $isAchieved = ($userProgress->level >= $lvlNum); @endphp
                <div class="p-3.5 rounded-2xl border text-center space-y-1 transition-all
                    {{ $isCurrent ? 'bg-brand-50 border-brand-300 ring-2 ring-brand-500/20 shadow-sm' : ($isAchieved ? 'bg-emerald-50/50 border-emerald-200 text-emerald-800' : 'bg-navy-50/50 border-navy-200 text-navy-400') }}">
                    <div class="text-xl">{{ $lvlInfo['icon'] }}</div>
                    <div class="text-xs font-extrabold truncate">{{ $lvlInfo['title'] }}</div>
                    <div class="text-[10px] font-mono font-bold text-navy-400">{{ $lvlInfo['xp'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Quiz Section & Category Filter Tabs -->
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-navy-900 tracking-tight">Modul Simulasi & Kuis Keamanan</h2>
                <p class="text-xs text-navy-500 font-medium">Pilihlah skenario kuis di bawah untuk menguji ketangkasan deteksi siber Anda</p>
            </div>

            <!-- Category Filter Pills -->
            <div class="flex flex-wrap gap-2">
                <button onclick="filterQuizzes('all')" id="btn-all" class="quiz-filter-btn px-4 py-2 rounded-xl text-xs font-extrabold transition-all bg-brand-600 text-white shadow-sm">
                    Semua ({{ count($quizzes) }})
                </button>
                <button onclick="filterQuizzes('Phishing Alert')" id="btn-Phishing Alert" class="quiz-filter-btn px-4 py-2 rounded-xl text-xs font-extrabold transition-all bg-white border border-navy-200 text-navy-700 hover:bg-navy-50">
                    Phishing
                </button>
                <button onclick="filterQuizzes('HTTPS Security')" id="btn-HTTPS Security" class="quiz-filter-btn px-4 py-2 rounded-xl text-xs font-extrabold transition-all bg-white border border-navy-200 text-navy-700 hover:bg-navy-50">
                    HTTPS & Domain
                </button>
                <button onclick="filterQuizzes('Malware Protection')" id="btn-Malware Protection" class="quiz-filter-btn px-4 py-2 rounded-xl text-xs font-extrabold transition-all bg-white border border-navy-200 text-navy-700 hover:bg-navy-50">
                    Malware & SMS
                </button>
            </div>
        </div>

        <!-- Quiz Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($quizzes as $quiz)
                @php
                    $isDone = in_array($quiz['id'], $userProgress->completed_quizzes ?? []);
                    $catName = $quiz['category'] ?? 'Phishing Alert';
                @endphp
                <div class="quiz-card glass-card glass-card-hover p-7 rounded-3xl flex flex-col justify-between space-y-6 relative overflow-hidden transition-all duration-300 {{ $isDone ? 'border-emerald-300 bg-gradient-to-b from-white to-emerald-50/30' : '' }}"
                     data-category="{{ $catName }}">
                    
                    @if($isDone)
                        <div class="absolute top-4 right-4 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-extrabold flex items-center gap-1 border border-emerald-300 shadow-sm">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            Selesai (+{{ $quiz['points'] ?? 100 }} XP)
                        </div>
                    @endif

                    <div class="space-y-4">
                        <!-- Top Header -->
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-brand-50 text-brand-700 border border-brand-200">
                                {{ $catName }}
                            </span>
                            <span class="text-xs font-mono font-black text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">
                                +{{ $quiz['points'] ?? 100 }} XP
                            </span>
                        </div>

                        <!-- Title -->
                        <h3 class="text-lg font-black text-navy-900 leading-snug">{{ $quiz['title'] }}</h3>

                        <!-- Interactive Simulation Preview Box -->
                        @if(!empty($quiz['url_display']))
                            <div class="p-3 rounded-2xl bg-navy-900 text-white space-y-1.5 shadow-inner">
                                <div class="flex items-center gap-1.5 opacity-60">
                                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                    <span class="text-[10px] font-mono text-navy-300 ml-1">Simulasi Tautan / Lampiran</span>
                                </div>
                                <div class="px-2.5 py-1.5 rounded-lg bg-navy-950 font-mono text-[11px] text-cyan-300 truncate border border-navy-800">
                                    {{ $quiz['url_display'] }}
                                </div>
                            </div>
                        @endif

                        <!-- Scenario Description -->
                        <p class="text-navy-600 text-xs leading-relaxed font-medium">
                            {{ $quiz['scenario'] }}
                        </p>
                    </div>

                    <!-- Options List Buttons -->
                    <div class="space-y-2.5 pt-2">
                        <div class="text-[10px] font-extrabold uppercase tracking-wider text-navy-400">Pilihlah Tindakan Terbaik:</div>
                        @foreach($quiz['options'] as $opt)
                            <button onclick="submitAnswer('{{ $quiz['id'] }}', '{{ $opt['id'] }}')"
                                    class="w-full p-3.5 rounded-2xl bg-white hover:bg-brand-50 hover:border-brand-400 border border-navy-200/90 text-navy-800 text-xs font-bold text-left shadow-sm transition-all duration-200 flex items-center justify-between group">
                                <div class="flex items-start gap-2.5">
                                    <span class="w-5 h-5 rounded-lg bg-navy-100 group-hover:bg-brand-600 group-hover:text-white text-navy-700 font-extrabold text-[11px] flex items-center justify-center shrink-0 transition-colors">
                                        {{ strtoupper($opt['id']) }}
                                    </span>
                                    <span class="leading-snug mt-0.5">{{ $opt['text'] }}</span>
                                </div>
                                <svg class="w-4 h-4 text-navy-400 group-hover:text-brand-600 shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        @endforeach
                    </div>

                </div>
            @endforeach
        </div>
    </div>

</section>

<!-- Custom Glassmorphism Quiz Result Modal -->
<div id="resultModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-navy-900/60 backdrop-blur-md transition-all duration-300">
    <div class="bg-white rounded-3xl p-8 max-w-lg w-full shadow-2xl border border-navy-200 space-y-6 relative animate-in fade-in zoom-in duration-200">
        <!-- Icon & Status Header -->
        <div class="flex items-center gap-4">
            <div id="modalBadgeIcon" class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl font-black shrink-0 shadow-md">
                🎉
            </div>
            <div>
                <h3 id="modalTitle" class="text-xl font-black text-navy-900">Jawaban Benar!</h3>
                <span id="modalXpBadge" class="inline-block px-3 py-1 rounded-full text-xs font-mono font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 mt-1">
                    +100 XP Diklaim
                </span>
            </div>
        </div>

        <!-- Explanation Text Box -->
        <div class="p-5 rounded-2xl bg-navy-50 border border-navy-200 space-y-1.5">
            <div class="text-[11px] font-extrabold uppercase tracking-widest text-navy-400">Penjelasan Edukasi Keamanan</div>
            <p id="modalExplanation" class="text-xs text-navy-800 font-semibold leading-relaxed">
                ...
            </p>
        </div>

        <!-- Level Up Notification -->
        <div id="modalLevelUp" class="hidden p-4 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-500 text-white font-extrabold text-xs flex items-center justify-between shadow-md">
            <span class="flex items-center gap-2">
                <span class="text-base">👑</span> LEVEL UP! Anda naik ke level <span id="newLevelName">...</span>
            </span>
        </div>

        <!-- Close Button -->
        <button onclick="closeResultModal()"
                class="w-full py-4 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-extrabold text-sm shadow-md shadow-brand-500/25 transition-all hover:scale-[1.01]">
            Lanjutkan Akademi →
        </button>
    </div>
</div>
@endsection

@section('scripts')
<script>
function filterQuizzes(cat) {
    const cards = document.querySelectorAll('.quiz-card');
    const btns = document.querySelectorAll('.quiz-filter-btn');

    btns.forEach(b => {
        b.className = 'quiz-filter-btn px-4 py-2 rounded-xl text-xs font-extrabold transition-all bg-white border border-navy-200 text-navy-700 hover:bg-navy-50';
    });

    const activeBtn = document.getElementById('btn-' + cat);
    if (activeBtn) {
        activeBtn.className = 'quiz-filter-btn px-4 py-2 rounded-xl text-xs font-extrabold transition-all bg-brand-600 text-white shadow-sm';
    }

    cards.forEach(card => {
        const cardCat = card.getAttribute('data-category');
        if (cat === 'all' || cardCat.includes(cat) || (cat === 'Malware Protection' && (cardCat.includes('Malware') || cardCat.includes('SMS') || cardCat.includes('Job')))) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

function submitAnswer(quizId, answerId) {
    fetch('{{ route("api.quiz") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ quiz_id: quizId, answer: answerId })
    })
    .then(res => res.json())
    .then(data => {
        showCustomModal(data);
    })
    .catch(err => {
        console.error(err);
        alert('Gagal mengirimkan jawaban.');
    });
}

function showCustomModal(data) {
    const modal = document.getElementById('resultModal');
    const badgeIcon = document.getElementById('modalBadgeIcon');
    const title = document.getElementById('modalTitle');
    const xpBadge = document.getElementById('modalXpBadge');
    const explanation = document.getElementById('modalExplanation');
    const levelUp = document.getElementById('modalLevelUp');

    if (data.is_correct) {
        badgeIcon.className = "w-14 h-14 rounded-2xl flex items-center justify-center text-2xl font-black shrink-0 shadow-md bg-emerald-50 text-emerald-600 border border-emerald-200";
        badgeIcon.textContent = "🎉";
        title.textContent = "Jawaban Tepat!";
        
        if (data.earned_points > 0) {
            xpBadge.className = "inline-block px-3 py-1 rounded-full text-xs font-mono font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 mt-1";
            xpBadge.textContent = "+" + data.earned_points + " XP Berhasil Diklaim!";
        } else {
            xpBadge.className = "inline-block px-3 py-1 rounded-full text-xs font-mono font-extrabold bg-brand-50 text-brand-700 border border-brand-200 mt-1";
            xpBadge.textContent = "✓ Jawaban Benar (Kuis Sudah Pernah Selesai)";
        }
    } else {
        badgeIcon.className = "w-14 h-14 rounded-2xl flex items-center justify-center text-2xl font-black shrink-0 shadow-md bg-rose-50 text-rose-600 border border-rose-200";
        badgeIcon.textContent = "❌";
        title.textContent = "Jawaban Kurang Tepat";
        xpBadge.className = "inline-block px-3 py-1 rounded-full text-xs font-mono font-extrabold bg-rose-50 text-rose-700 border border-rose-200 mt-1";
        xpBadge.textContent = "+0 XP — Silakan Coba Lagi";
    }

    explanation.textContent = data.explanation;

    if (data.level_up) {
        levelUp.classList.remove('hidden');
        document.getElementById('newLevelName').textContent = data.level_title || ('Level ' + data.level);
    } else {
        levelUp.classList.add('hidden');
    }

    modal.classList.remove('hidden');
}

function closeResultModal() {
    window.location.reload();
}
</script>
@endsection
