@if ($paginator->hasPages())
    <nav class="gallery-pagination" role="navigation" aria-label="Pagination">
        @if ($paginator->onFirstPage())
            <span class="page-btn disabled" aria-disabled="true">Sebelumnya</span>
        @else
            <a class="page-btn" href="{{ $paginator->previousPageUrl() }}" rel="prev">Sebelumnya</a>
        @endif

        <div class="page-numbers">
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="page-ellipsis">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="page-num current" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="page-num" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        @if ($paginator->hasMorePages())
            <a class="page-btn" href="{{ $paginator->nextPageUrl() }}" rel="next">Berikutnya</a>
        @else
            <span class="page-btn disabled" aria-disabled="true">Berikutnya</span>
        @endif
    </nav>
@endif
