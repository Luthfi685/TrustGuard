@if ($paginator->hasPages())
    <nav class="flex items-center justify-between gap-2" role="navigation">
        <div class="flex items-center gap-1">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1.5 rounded-lg bg-navy-100 text-navy-400 text-xs font-semibold cursor-not-allowed">‹ Prev</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="px-3 py-1.5 rounded-lg bg-navy-100 hover:bg-navy-200 text-navy-700 text-xs font-semibold transition-colors">‹ Prev</a>
            @endif

            {{-- Page Numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-3 py-1.5 text-navy-400 text-xs font-medium">{{ $element }}</span>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3 py-1.5 rounded-lg bg-brand-600 text-white text-xs font-extrabold shadow-sm">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                               class="px-3 py-1.5 rounded-lg bg-navy-100 hover:bg-navy-200 text-navy-700 text-xs font-semibold transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="px-3 py-1.5 rounded-lg bg-navy-100 hover:bg-navy-200 text-navy-700 text-xs font-semibold transition-colors">Next ›</a>
            @else
                <span class="px-3 py-1.5 rounded-lg bg-navy-100 text-navy-400 text-xs font-semibold cursor-not-allowed">Next ›</span>
            @endif
        </div>

        <p class="text-xs text-navy-500 font-medium">
            Halaman {{ $paginator->currentPage() }} dari {{ $paginator->lastPage() }}
        </p>
    </nav>
@endif
