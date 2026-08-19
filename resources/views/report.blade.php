@extends('layouts.app')

@section('title', 'Lapor Situs Web Mencurigakan — TrustGuard')

@section('content')
<section class="py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-10">
    
    <!-- Page Header -->
    <div class="text-center space-y-3 max-w-3xl mx-auto">
        <span class="text-xs font-extrabold uppercase tracking-widest text-brand-600 bg-brand-50 border border-brand-200 px-3.5 py-1.5 rounded-full">
            Community Threat Intelligence
        </span>
        <h1 class="text-3xl sm:text-5xl font-black text-navy-900 tracking-tight">
            Lapor Situs Web Mencurigakan
        </h1>
        <p class="text-navy-600 text-base font-medium">
            Bantu lindungi sesama pengguna internet dengan melaporkan indikasi phishing, penipuan, atau pencurian data. Dapatkan +50 XP Poin Komunitas!
        </p>
    </div>

    <!-- Main Grid (Form & Community Feed) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Form Section -->
        <div class="lg:col-span-2 glass-card p-8 rounded-3xl space-y-6">
            <div class="border-b border-navy-200/80 pb-4">
                <h3 class="text-xl font-extrabold text-navy-900">Formulir Pelaporan Bahaya</h3>
                <p class="text-xs text-navy-500 font-medium">Isi detail tautan yang Anda nilai mencurigakan</p>
            </div>

            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('report.store') }}" method="POST" class="space-y-5">
                @csrf

                <div class="space-y-2">
                    <label class="block text-xs font-extrabold text-navy-700 uppercase tracking-wider">URL / Alamat Situs Mencurigakan</label>
                    <input type="text" name="url" value="{{ old('url', $initialUrl ?? '') }}" required
                           placeholder="https://situs-bank-palsu-login.com"
                           class="w-full px-4 py-3.5 rounded-xl bg-navy-50 border border-navy-200 text-navy-900 placeholder-navy-400 font-semibold text-sm focus:outline-none focus:border-brand-600 focus:bg-white transition-all">
                    @if(isset($errors) && $errors->has('url'))
                        <p class="text-xs text-rose-600 font-semibold mt-1">{{ $errors->first('url') }}</p>
                    @endif
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-extrabold text-navy-700 uppercase tracking-wider">Kategori Indikasi Ancaman</label>
                    <select name="category" required
                            class="w-full px-4 py-3.5 rounded-xl bg-navy-50 border border-navy-200 text-navy-900 font-semibold text-sm focus:outline-none focus:border-brand-600 focus:bg-white transition-all">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Phishing">Phishing (Pencurian Kata Sandi/Akun)</option>
                        <option value="Penipuan">Penipuan Toko Online / Hadiah Palsu</option>
                        <option value="Website Palsu">Website Imitasi / Brand Impersonation</option>
                        <option value="Pencurian Data">Pencurian Data Kartu Kredit/KTP</option>
                        <option value="Tautan Mencurigakan">Tautan Mencurigakan Lainnya</option>
                    </select>
                    @if(isset($errors) && $errors->has('category'))
                        <p class="text-xs text-rose-600 font-semibold mt-1">{{ $errors->first('category') }}</p>
                    @endif
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-extrabold text-navy-700 uppercase tracking-wider">Penjelasan Singkat Indikasi Bahaya</label>
                    <textarea name="description" rows="4" required
                              placeholder="Jelaskan alasan Anda mencurigai situs ini (contoh: Tampilan mirip situs resmi bank tapi domainnya berbeda)..."
                              class="w-full px-4 py-3.5 rounded-xl bg-navy-50 border border-navy-200 text-navy-900 placeholder-navy-400 font-semibold text-sm focus:outline-none focus:border-brand-600 focus:bg-white transition-all">{{ old('description') }}</textarea>
                    @if(isset($errors) && $errors->has('description'))
                        <p class="text-xs text-rose-600 font-semibold mt-1">{{ $errors->first('description') }}</p>
                    @endif
                </div>

                <button type="submit"
                        class="w-full py-4 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-extrabold text-sm shadow-md shadow-brand-500/20 transition-all hover:scale-[1.01]">
                    🚨 Kirim Laporan & Klaim +50 XP
                </button>
            </form>
        </div>

        <!-- Community Reports Feed Sidebar -->
        <div class="glass-card p-8 rounded-3xl space-y-6">
            <div class="border-b border-navy-200/80 pb-4">
                <h3 class="text-lg font-extrabold text-navy-900">Umpan Laporan Terbaru</h3>
                <p class="text-xs text-navy-500 font-medium">Laporan terkini dari komunitas</p>
            </div>

            <div class="space-y-4">
                @forelse($reports as $report)
                    <div class="p-4 rounded-2xl bg-navy-50 border border-navy-200/80 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 rounded-md text-[10px] font-extrabold bg-brand-50 text-brand-700 border border-brand-200">
                                {{ $report->category }}
                            </span>
                            <span class="text-[10px] text-navy-400 font-medium">{{ $report->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs font-mono font-bold text-navy-900 truncate">{{ $report->domain }}</p>
                        <p class="text-xs text-navy-600 line-clamp-2 font-medium">{{ $report->description }}</p>
                    </div>
                @empty
                    <p class="text-xs text-navy-400 text-center py-6">Belum ada laporan komunitas.</p>
                @endforelse
            </div>
        </div>

    </div>

</section>
@endsection
