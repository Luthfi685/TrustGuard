@extends('layouts.app')

@section('title', 'Executive Security Dashboard — TrustGuard')

@section('content')
<section class="py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8">
    
    <!-- Dashboard Header Banner -->
    <div class="glass-card p-8 rounded-3xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-2">
            <span class="px-3.5 py-1.5 rounded-full bg-brand-50 text-brand-700 border border-brand-200 text-xs font-extrabold tracking-wider uppercase">
                Executive Intel Dashboard
            </span>
            <h1 class="text-3xl font-black text-navy-900 tracking-tight">
                Ringkasan Intelijen Keamanan Siber
            </h1>
            <p class="text-navy-600 text-sm font-medium max-w-xl">
                Pantau tren pemindaian domain, distribusi status risiko, dan aktivitas pelaporan komunitas secara real-time.
            </p>
        </div>

        <a href="{{ route('scan') }}"
           class="px-6 py-3.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-extrabold text-sm shadow-md shadow-brand-500/20 transition-all hover:scale-105 shrink-0 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Pindai URL Baru
        </a>
    </div>

    <!-- Overview Metric Cards Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">

        <!-- Card 1: Websites Checked -->
        <div class="glass-card p-6 rounded-3xl space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold text-navy-500 uppercase tracking-wider">Total Diinspeksi</span>
                <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 border border-brand-200 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-navy-900 font-mono tracking-tight">{{ number_format($totalScans) }}</div>
            <p class="text-xs text-navy-500 font-medium">Domain telah diaudit</p>
        </div>

        <!-- Card 2: Trusted Sites -->
        <div class="glass-card p-6 rounded-3xl space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold text-navy-500 uppercase tracking-wider">Domain Aman</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-emerald-600 font-mono tracking-tight">{{ number_format($safeScans) }}</div>
            <p class="text-xs text-navy-500 font-medium">Trust Score ≥ 80</p>
        </div>

        <!-- Card 3: Warning Sites -->
        <div class="glass-card p-6 rounded-3xl space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold text-navy-500 uppercase tracking-wider">Waspada</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-amber-600 font-mono tracking-tight">{{ number_format($warningScans) }}</div>
            <p class="text-xs text-navy-500 font-medium">Trust Score 60–79</p>
        </div>

        <!-- Card 4: Risk Detected -->
        <div class="glass-card p-6 rounded-3xl space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold text-navy-500 uppercase tracking-wider">Risiko Tinggi</span>
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 border border-rose-200 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-rose-600 font-mono tracking-tight">{{ number_format($dangerScans) }}</div>
            <p class="text-xs text-navy-500 font-medium">Trust Score &lt; 60</p>
        </div>

    </div>

    <!-- Charts Section Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Doughnut Distribution Chart -->
        <div class="glass-card p-8 rounded-3xl space-y-4">
            <h3 class="text-base font-extrabold text-navy-900">Distribusi Keamanan Situs</h3>
            <div class="relative h-64 flex items-center justify-center">
                <canvas id="distributionChart"></canvas>
            </div>
        </div>

        <!-- Line Trend Chart -->
        <div class="lg:col-span-2 glass-card p-8 rounded-3xl space-y-4">
            <h3 class="text-base font-extrabold text-navy-900">Tren Pemindaian Real-Time Bulanan {{ now()->year }}</h3>
            <div class="relative h-64">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Recent Scans Activity Table -->
    <div class="glass-card p-8 rounded-3xl space-y-6">
        <div class="flex items-center justify-between border-b border-navy-200/80 pb-4">
            <div>
                <h3 class="text-lg font-extrabold text-navy-900">Riwayat Pemindaian Terbaru</h3>
                <p class="text-xs text-navy-500 font-medium">Aktivitas audit URL teranyar oleh sistem TrustGuard</p>
            </div>
            <a href="{{ route('scan') }}" class="text-xs font-bold text-brand-600 hover:underline">Pindai URL →</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-navy-200 text-xs font-extrabold text-navy-500 uppercase tracking-wider">
                        <th class="pb-3">Domain</th>
                        <th class="pb-3">Trust Score</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3">Waktu Inspeksi</th>
                        <th class="pb-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-navy-100">
                    @forelse($recentScans as $scan)
                        <tr class="hover:bg-navy-50/50 transition-colors">
                            <td class="py-4 font-mono font-bold text-navy-900">{{ $scan->domain }}</td>
                            <td class="py-4 font-mono font-black" style="color: {{ $scan->status_color }}">
                                {{ $scan->trust_score }}/100
                            </td>
                            <td class="py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-extrabold border"
                                      style="background-color: {{ $scan->status_color }}15; color: {{ $scan->status_color }}; border-color: {{ $scan->status_color }}30;">
                                    {{ $scan->status_label }}
                                </span>
                            </td>
                            <td class="py-4 text-xs text-navy-500 font-medium">{{ $scan->created_at->diffForHumans() }}</td>
                            <td class="py-4 text-right">
                                <a href="{{ route('result', $scan->id) }}" class="text-xs font-bold text-brand-600 hover:underline">
                                    Detail →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-navy-400 text-xs font-medium">Belum ada riwayat pemindaian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Doughnut Chart
    const ctxDist = document.getElementById('distributionChart').getContext('2d');
    new Chart(ctxDist, {
        type: 'doughnut',
        data: {
            labels: ['Aman (≥80)', 'Waspada (60-79)', 'Risiko (<60)'],
            datasets: [{
                data: [{{ $safeScans }}, {{ $warningScans }}, {{ $dangerScans }}],
                backgroundColor: ['#22C55E', '#F59E0B', '#EF4444'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { font: { family: 'Plus Jakarta Sans', weight: 'bold' } } }
            }
        }
    });

    // Line Chart — data real dari database per bulan tahun berjalan
    const ctxTrend = document.getElementById('trendChart').getContext('2d');
    new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyLabels) !!},
            datasets: [{
                label: 'Jumlah Pemindaian',
                data: {!! json_encode($monthlyData) !!},
                borderColor: '#2563EB',
                backgroundColor: 'rgba(37, 99, 235, 0.08)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointBackgroundColor: '#2563EB',
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { grid: { color: '#E2E8F0' }, ticks: { color: '#64748B', font: { family: 'Plus Jakarta Sans' } } },
                x: { grid: { display: false }, ticks: { color: '#64748B', font: { family: 'Plus Jakarta Sans' } } }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>
@endsection
