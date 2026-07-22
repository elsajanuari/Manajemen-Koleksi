@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center justify-between">
        <p class="text-sm text-gray-600">
            Menampilkan
            <span class="font-semibold text-gray-900">{{ $paginator->firstItem() ?? 0 }}–{{ $paginator->lastItem() ?? 0 }}</span>
            dari
            <span class="font-semibold text-gray-900">{{ $paginator->total() }}</span>
        </p>

        <ul class="inline-flex items-center gap-1 rounded-full border border-gray-200 bg-white p-1 text-sm shadow-sm">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                    <span class="inline-flex h-9 min-w-[3rem] items-center justify-center rounded-full bg-gray-100 px-3 text-gray-500">‹</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex h-9 min-w-[3rem] items-center justify-center rounded-full px-3 text-gray-700 hover:bg-gray-100 hover:text-gray-900">‹</a>
                </li>
            @endif

            @php
                $current = $paginator->currentPage();
                $last = $paginator->lastPage();
                $start = max(1, $current - 1);
                $end = min($last, $current + 1);
            @endphp

            @if ($start > 1)
                <li>
                    <a href="{{ $paginator->url(1) }}" class="inline-flex h-9 min-w-[3rem] items-center justify-center rounded-full px-3 text-gray-700 hover:bg-gray-100 hover:text-gray-900">1</a>
                </li>
                @if ($start > 2)
                    <li><span class="inline-flex h-9 min-w-[3rem] items-center justify-center rounded-full px-3 text-gray-500">…</span></li>
                @endif
            @endif

            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $current)
                    <li aria-current="page">
                        <span class="inline-flex h-9 min-w-[3rem] items-center justify-center rounded-full bg-indigo-600 px-3 text-white">{{ $page }}</span>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->url($page) }}" class="inline-flex h-9 min-w-[3rem] items-center justify-center rounded-full px-3 text-gray-700 hover:bg-gray-100 hover:text-gray-900">{{ $page }}</a>
                    </li>
                @endif
            @endfor

            @if ($end < $last)
                @if ($end < $last - 1)
                    <li><span class="inline-flex h-9 min-w-[3rem] items-center justify-center rounded-full px-3 text-gray-500">…</span></li>
                @endif
                <li>
                    <a href="{{ $paginator->url($last) }}" class="inline-flex h-9 min-w-[3rem] items-center justify-center rounded-full px-3 text-gray-700 hover:bg-gray-100 hover:text-gray-900">{{ $last }}</a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex h-9 min-w-[3rem] items-center justify-center rounded-full px-3 text-gray-700 hover:bg-gray-100 hover:text-gray-900">›</a>
                </li>
            @else
                <li aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                    <span class="inline-flex h-9 min-w-[3rem] items-center justify-center rounded-full bg-gray-100 px-3 text-gray-500">›</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
