@extends('admin.app')

@section('title', 'Laporan Komunitas')
@section('page-title', 'Laporan Komunitas')
@section('page-subtitle', 'Kelola laporan URL berbahaya yang dikirim oleh pengguna')

@section('content')
<div class="py-6 space-y-6">

    <!-- Filter Bar -->
    <div class="glass-panel rounded-3xl p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.reports') }}" class="flex flex-wrap items-center gap-3">
            <select name="status"
                    class="px-4 py-2.5 rounded-xl bg-navy-50 border border-navy-200 text-navy-900 text-sm font-semibold focus:outline-none focus:border-brand-600 focus:bg-white transition-all">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Menunggu</option>
                <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>✅ Terverifikasi</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>❌ Ditolak</option>
            </select>
            <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-extrabold transition-all">
                Filter
            </button>
            @if(request('status'))
                <a href="{{ route('admin.reports') }}"
                   class="px-5 py-2.5 rounded-xl bg-navy-100 hover:bg-navy-200 text-navy-700 text-sm font-extrabold transition-all">
                    Reset
                </a>
            @endif

            @php $pendingCount = \App\Models\Report::where('status','pending')->count(); @endphp
            @if($pendingCount > 0)
                <div class="ml-auto flex items-center gap-2 px-3.5 py-2 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-extrabold">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                    {{ $pendingCount }} laporan menunggu review
                </div>
            @endif
        </form>
    </div>

    <!-- Reports Table -->
    <div class="glass-panel rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-navy-200 bg-navy-50">
                        <th class="px-5 py-4 text-left text-xs font-extrabold text-navy-500 uppercase tracking-wider">#</th>
                        <th class="px-5 py-4 text-left text-xs font-extrabold text-navy-500 uppercase tracking-wider">URL Dilaporkan</th>
                        <th class="px-5 py-4 text-left text-xs font-extrabold text-navy-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-5 py-4 text-left text-xs font-extrabold text-navy-500 uppercase tracking-wider">Pelapor</th>
                        <th class="px-5 py-4 text-left text-xs font-extrabold text-navy-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-5 py-4 text-left text-xs font-extrabold text-navy-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-4 text-left text-xs font-extrabold text-navy-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-5 py-4 text-left text-xs font-extrabold text-navy-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-navy-100">
                    @forelse($reports as $report)
                        <tr class="hover:bg-navy-50/60 transition-colors {{ $report->status === 'pending' ? 'bg-amber-50/30' : '' }}">
                            <td class="px-5 py-4 text-xs text-navy-400 font-mono">{{ $report->id }}</td>
                            <td class="px-5 py-4">
                                <div>
                                    <a href="{{ $report->url }}" target="_blank" rel="noopener noreferrer"
                                       class="text-sm font-bold text-brand-600 hover:underline font-mono truncate block max-w-[200px]">
                                        {{ parse_url($report->url, PHP_URL_HOST) ?? $report->url }}
                                    </a>
                                    <p class="text-[10px] text-navy-400 font-medium truncate max-w-[200px] mt-0.5">{{ $report->url }}</p>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-extrabold bg-navy-100 text-navy-700">
                                    {{ $report->category ?? 'Lainnya' }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div>
                                    <p class="text-xs font-bold text-navy-900">{{ $report->reporter_name ?? 'Anonim' }}</p>
                                    <p class="text-[10px] text-navy-500 font-medium">{{ $report->reporter_email ?? '—' }}</p>
                                    <p class="text-[10px] text-navy-400 font-mono">IP: {{ $report->submitter_ip ?? $report->ip_address ?? '127.0.0.1' }}</p>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-xs text-navy-600 font-medium max-w-[200px] line-clamp-2">
                                    {{ $report->description ?? '—' }}
                                </p>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold border
                                    @if($report->status === 'verified') bg-emerald-50 text-emerald-700 border-emerald-200
                                    @elseif($report->status === 'rejected') bg-navy-100 text-navy-600 border-navy-200
                                    @else bg-amber-50 text-amber-700 border-amber-200 @endif">
                                    @if($report->status === 'pending')
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>Menunggu
                                    @elseif($report->status === 'verified')
                                        ✅ Terverifikasi
                                    @else
                                        ❌ Ditolak
                                    @endif
                                </span>
                            </td>
                            <td class="px-5 py-4 text-xs text-navy-500 font-medium">{{ $report->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-1.5">
                                    @if($report->status === 'pending')
                                        <form action="{{ route('admin.reports.verify', $report->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="p-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 transition-colors" title="Verifikasi">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.reports.reject', $report->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="p-2 rounded-xl bg-navy-100 hover:bg-navy-200 text-navy-600 border border-navy-200 transition-colors" title="Tolak">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.reports.delete', $report->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus laporan ini secara permanen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 transition-colors" title="Hapus Permanen">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-navy-400 text-sm font-medium">
                                Belum ada laporan dari komunitas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reports->hasPages())
            <div class="px-5 py-4 border-t border-navy-200 bg-white">
                {{ $reports->links('admin.pagination') }}
            </div>
        @endif
    </div>

</div>
@endsection
