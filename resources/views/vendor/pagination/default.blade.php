@if ($paginator->hasPages())
<nav class="d-flex justify-content-center">
    <div class="btn-group" role="group">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
        <button type="button" class="btn btn-outline-secondary" disabled aria-label="@lang('pagination.previous')">
            &laquo;
        </button>
        @else
        <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-outline-secondary" rel="prev" aria-label="@lang('pagination.previous')">
            &laquo;
        </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
        <button type="button" class="btn btn-outline-secondary" disabled>
            {{ $element }}
        </button>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
        @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
        <button type="button" class="btn btn-gold" disabled>
            {{ $page }}
        </button>
        @else
        <a href="{{ $url }}" class="btn btn-outline-secondary">
            {{ $page }}
        </a>
        @endif
        @endforeach
        @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-outline-secondary" rel="next" aria-label="@lang('pagination.next')">
            &raquo;
        </a>
        @else
        <button type="button" class="btn btn-outline-secondary" disabled aria-label="@lang('pagination.next')">
            &raquo;
        </button>
        @endif
    </div>
</nav>
@endif