@extends('admin.app')

@section('title', 'Kelola Scan')
@section('page-title', 'Kelola Scan')
@section('page-subtitle', 'Semua riwayat pemindaian URL dari pengguna')

@section('content')
<div class="py-6 space-y-6">

    <!-- Filter Bar -->
    <div class="glass-panel rounded-3xl p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.scans') }}" class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="🔍 Cari domain..."
                   class="flex-1 min-w-[200px] px-4 py-2.5 rounded-xl bg-navy-50 border border-navy-200 text-navy-900 text-sm font-semibold placeholder-navy-400 focus:outline-none focus:border-brand-600 focus:bg-white transition-all">
            <select name="status"
                    class="px-4 py-2.5 rounded-xl bg-navy-50 border border-navy-200 text-navy-900 text-sm font-semibold focus:outline-none focus:border-brand-600 focus:bg-white transition-all">
                <option value="">Semua Status</option>
                <option value="safe" {{ request('status') === 'safe' ? 'selected' : '' }}>✅ Aman</option>
                <option value="warning" {{ request('status') === 'warning' ? 'selected' : '' }}>⚠️ Waspada</option>
                <option value="danger" {{ request('status') === 'danger' ? 'selected' : '' }}>🚨 Berbahaya</option>
            </select>
            <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-sm font-extrabold transition-all">
                Filter
            </button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.scans') }}"
                   class="px-5 py-2.5 rounded-xl bg-navy-100 hover:bg-navy-200 text-navy-700 text-sm font-extrabold transition-all">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Scans Table -->
    <div class="glass-panel rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-navy-200 bg-navy-50">
                        <th class="px-5 py-4 text-left text-xs font-extrabold text-navy-500 uppercase tracking-wider">#</th>
                        <th class="px-5 py-4 text-left text-xs font-extrabold text-navy-500 uppercase tracking-wider">Domain / URL</th>
                        <th class="px-5 py-4 text-left text-xs font-extrabold text-navy-500 uppercase tracking-wider">Trust Score</th>
                        <th class="px-5 py-4 text-left text-xs font-extrabold text-navy-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-4 text-left text-xs font-extrabold text-navy-500 uppercase tracking-wider">IP Pengguna</th>
                        <th class="px-5 py-4 text-left text-xs font-extrabold text-navy-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-5 py-4 text-left text-xs font-extrabold text-navy-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-navy-100">
                    @forelse($scans as $scan)
                        <tr class="hover:bg-navy-50/60 transition-colors">
                            <td class="px-5 py-4 text-xs text-navy-400 font-mono">{{ $scan->id }}</td>
                            <td class="px-5 py-4">
                                <div>
                                    <p class="text-sm font-bold text-navy-900 font-mono truncate max-w-[220px]">{{ $scan->domain }}</p>
                                    <p class="text-[10px] text-navy-400 truncate max-w-[220px] mt-0.5 font-medium">{{ $scan->url }}</p>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-16 h-1.5 rounded-full bg-navy-100 overflow-hidden">
                                        <div class="h-full rounded-full"
                                             style="width: {{ $scan->trust_score }}%; background-color: {{ $scan->status_color }}"></div>
                                    </div>
                                    <span class="text-sm font-black font-mono" style="color: {{ $scan->status_color }}">
                                        {{ $scan->trust_score }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-extrabold border"
                                      style="background-color: {{ $scan->status_color }}15; color: {{ $scan->status_color }}; border-color: {{ $scan->status_color }}30;">
                                    {{ $scan->status_label }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-xs text-navy-600 font-mono font-medium">{{ $scan->ip_address ?? '—' }}</td>
                            <td class="px-5 py-4 text-xs text-navy-500 font-medium">{{ $scan->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('result', $scan->id) }}" target="_blank"
                                       class="p-2 rounded-xl bg-brand-50 hover:bg-brand-100 text-brand-600 border border-brand-200 transition-colors" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.scans.delete', $scan->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus data scan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 transition-colors" title="Hapus">
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
                            <td colspan="7" class="px-5 py-12 text-center text-navy-400 text-sm font-medium">
                                Belum ada data scan yang tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($scans->hasPages())
            <div class="px-5 py-4 border-t border-navy-200 bg-white">
                {{ $scans->links('admin.pagination') }}
            </div>
        @endif
    </div>

</div>
@endsection
