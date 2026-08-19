@extends('admin.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan statistik & aktivitas terkini TrustGuard')

@section('content')
<div class="py-6 space-y-8">

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Scan -->
        <div class="glass-panel rounded-3xl p-6 space-y-3 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold text-navy-500 uppercase tracking-wider">Total Scan</span>
                <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 border border-brand-200 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-navy-900 font-mono tracking-tight">{{ number_format($totalScans) }}</div>
            <p class="text-xs text-navy-500 font-medium">URL telah dianalisis</p>
        </div>

        <!-- Aman -->
        <div class="glass-panel rounded-3xl p-6 space-y-3 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold text-navy-500 uppercase tracking-wider">Aman</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-emerald-600 font-mono tracking-tight">{{ number_format($safeScans) }}</div>
            <p class="text-xs text-navy-500 font-medium">Score ≥ 80</p>
        </div>

        <!-- Waspada -->
        <div class="glass-panel rounded-3xl p-6 space-y-3 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold text-navy-500 uppercase tracking-wider">Waspada</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 border border-amber-200 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-amber-600 font-mono tracking-tight">{{ number_format($warningScans) }}</div>
            <p class="text-xs text-navy-500 font-medium">Score 60–79</p>
        </div>

        <!-- Bahaya -->
        <div class="glass-panel rounded-3xl p-6 space-y-3 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold text-navy-500 uppercase tracking-wider">Berbahaya</span>
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 border border-rose-200 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-black text-rose-600 font-mono tracking-tight">{{ number_format($dangerScans) }}</div>
            <p class="text-xs text-navy-500 font-medium">Score &lt; 60</p>
        </div>
    </div>

    <!-- Report Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="glass-panel rounded-3xl p-6 space-y-3 shadow-sm">
            <span class="text-xs font-extrabold text-navy-500 uppercase tracking-wider">Total Laporan</span>
            <div class="text-3xl font-black text-navy-900 font-mono tracking-tight">{{ number_format($totalReports) }}</div>
            <p class="text-xs text-navy-500 font-medium">Dari komunitas pengguna</p>
        </div>
        <div class="glass-panel rounded-3xl p-6 space-y-3 shadow-sm border-l-4 border-l-amber-500">
            <span class="text-xs font-extrabold text-navy-500 uppercase tracking-wider">Menunggu Review</span>
            <div class="text-3xl font-black text-amber-600 font-mono tracking-tight">{{ number_format($pendingReports) }}</div>
            <p class="text-xs font-bold text-amber-600">
                <a href="{{ route('admin.reports', ['status' => 'pending']) }}" class="hover:underline">→ Tinjau Sekarang</a>
            </p>
        </div>
        <div class="glass-panel rounded-3xl p-6 space-y-3 shadow-sm border-l-4 border-l-emerald-500">
            <span class="text-xs font-extrabold text-navy-500 uppercase tracking-wider">Terverifikasi</span>
            <div class="text-3xl font-black text-emerald-600 font-mono tracking-tight">{{ number_format($verifiedReports) }}</div>
            <p class="text-xs text-navy-500 font-medium">Dikonfirmasi berbahaya</p>
        </div>
    </div>

    <!-- Latest Activity Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Latest Scans -->
        <div class="glass-panel rounded-3xl p-7 space-y-5 shadow-sm">
            <div class="flex items-center justify-between border-b border-navy-200 pb-3">
                <h2 class="text-base font-extrabold text-navy-900">Scan Terbaru</h2>
                <a href="{{ route('admin.scans') }}" class="text-xs font-bold text-brand-600 hover:underline">Lihat Semua →</a>
            </div>
            <div class="space-y-3">
                @forelse($latestScans as $scan)
                    <div class="flex items-center justify-between p-3.5 rounded-2xl bg-navy-50 border border-navy-200/80">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-2.5 h-2.5 rounded-full shrink-0
                                @if($scan->status === 'safe') bg-emerald-500
                                @elseif($scan->status === 'warning') bg-amber-500
                                @else bg-rose-500 @endif"></div>
                            <span class="text-xs font-bold font-mono text-navy-900 truncate max-w-[180px]">{{ $scan->domain }}</span>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="text-xs font-black font-mono" style="color: {{ $scan->status_color }}">
                                {{ $scan->trust_score }}/100
                            </span>
                            <span class="text-[10px] text-navy-400 font-medium">{{ $scan->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-navy-400 text-center py-4">Belum ada data scan.</p>
                @endforelse
            </div>
        </div>

        <!-- Latest Reports -->
        <div class="glass-panel rounded-3xl p-7 space-y-5 shadow-sm">
            <div class="flex items-center justify-between border-b border-navy-200 pb-3">
                <h2 class="text-base font-extrabold text-navy-900">Laporan Terbaru</h2>
                <a href="{{ route('admin.reports') }}" class="text-xs font-bold text-brand-600 hover:underline">Kelola →</a>
            </div>
            <div class="space-y-3">
                @forelse($latestReports as $report)
                    <div class="flex items-center justify-between p-3.5 rounded-2xl bg-navy-50 border border-navy-200/80">
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-navy-900 font-mono truncate max-w-[180px]">{{ $report->url }}</p>
                            <p class="text-[10px] text-navy-500 font-medium mt-0.5">{{ $report->category ?? 'Tidak dikategorikan' }}</p>
                        </div>
                        <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-extrabold border
                            @if($report->status === 'verified') bg-emerald-50 text-emerald-700 border-emerald-200
                            @elseif($report->status === 'rejected') bg-navy-100 text-navy-600 border-navy-200
                            @else bg-amber-50 text-amber-700 border-amber-200 @endif">
                            @if($report->status === 'pending') Pending
                            @elseif($report->status === 'verified') Verified
                            @else Ditolak @endif
                        </span>
                    </div>
                @empty
                    <p class="text-xs text-navy-400 text-center py-4">Belum ada laporan komunitas.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
