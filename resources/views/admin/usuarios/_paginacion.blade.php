@if ($paginator->hasPages())
<nav class="paginacion-nav">
    {{-- Anterior --}}
    @if ($paginator->onFirstPage())
    <span class="paginacion-btn paginacion-btn--disabled">&laquo;</span>
    @else
    <a href="{{ $paginator->previousPageUrl() }}" class="paginacion-btn">&laquo;</a>
    @endif

    {{-- Páginas --}}
    @foreach ($elements as $element)
    @if (is_string($element))
    <span class="paginacion-btn paginacion-btn--disabled">{{ $element }}</span>
    @endif

    @if (is_array($element))
    @foreach ($element as $page => $url)
    @if ($page == $paginator->currentPage())
    <span class="paginacion-btn paginacion-btn--active">{{ $page }}</span>
    @else
    <a href="{{ $url }}" class="paginacion-btn">{{ $page }}</a>
    @endif
    @endforeach
    @endif
    @endforeach

    {{-- Siguiente --}}
    @if ($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}" class="paginacion-btn">&raquo;</a>
    @else
    <span class="paginacion-btn paginacion-btn--disabled">&raquo;</span>
    @endif
</nav>
@endif