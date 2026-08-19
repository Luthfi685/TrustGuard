@extends('layouts.app')

@section('title', '404 — Halaman Tidak Ditemukan | TrustGuard')

@section('content')
<section class="min-h-[70vh] flex items-center justify-center px-4 py-20">
    <div class="text-center max-w-2xl mx-auto space-y-8">

        <!-- Glitch 404 Number -->
        <div class="relative">
            <div class="text-[10rem] sm:text-[13rem] font-black font-mono leading-none select-none tracking-tight"
                 style="background: linear-gradient(135deg, #1E3A8A 0%, #2563EB 50%, #0EA5E9 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                404
            </div>
        </div>

        <!-- Shield Error Icon -->
        <div class="flex justify-center">
            <div class="w-20 h-20 rounded-3xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center shadow-sm">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>

        <div class="space-y-3">
            <h1 class="text-2xl sm:text-4xl font-black text-navy-900">
                Halaman Tidak Ditemukan
            </h1>
            <p class="text-navy-600 text-base max-w-md mx-auto leading-relaxed font-medium">
                Seperti URL mencurigakan yang kami blokir — halaman yang Anda cari tidak ada, telah dipindahkan, atau mungkin tidak pernah ada.
            </p>
        </div>

        <!-- Error Code Tag -->
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-navy-200 font-mono text-xs text-navy-600 shadow-sm">
            <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
            ERROR_CODE: PAGE_NOT_FOUND — STATUS: 404
        </div>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 px-8 py-3.5 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-extrabold text-sm shadow-md shadow-brand-500/20 transition-all hover:scale-105">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Kembali ke Beranda
            </a>
            <a href="{{ route('scan') }}"
               class="inline-flex items-center gap-2 px-8 py-3.5 rounded-2xl bg-white border border-navy-200 hover:bg-navy-50 text-navy-800 font-bold text-sm shadow-sm transition-all">
                <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Pindai URL Sekarang
            </a>
        </div>

    </div>
</section>
@endsection
