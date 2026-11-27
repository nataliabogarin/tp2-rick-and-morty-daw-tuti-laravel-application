{{-- Vista de paginación propia. Laravel le pasa $paginator y $elements (los números de página) --}}
@if ($paginator->hasPages())
    <nav class="paginacion" aria-label="Paginación">
        <p class="paginacion-info">
            Mostrando {{ $paginator->firstItem() }} a {{ $paginator->lastItem() }} de {{ $paginator->total() }} resultados
        </p>

        <ul>
            @if ($paginator->onFirstPage())
                <li><span class="deshabilitado">&laquo; Anterior</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo; Anterior</a></li>
            @endif

            @foreach ($elements as $element)
                {{-- Cuando hay muchas páginas Laravel manda "..." como texto --}}
                @if (is_string($element))
                    <li><span class="deshabilitado">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><span class="actual" aria-current="page">{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">Siguiente &raquo;</a></li>
            @else
                <li><span class="deshabilitado">Siguiente &raquo;</span></li>
            @endif
        </ul>
    </nav>
@endif
