@extends('layout.layout')

@section('titulo', 'Favoritos')

@section('contenido')
<div class="pagina">
    <div class="panel">
        <h1 class="titulo-pagina">⭐ Mis favoritos</h1>
        <p class="subtitulo">Personajes, episodios y ubicaciones que guardaste.</p>

        @if(session('success'))
            <div class="alerta alerta-exito">{{ session('success') }}</div>
        @endif

        @php
            // Según el tipo de favorito armamos el link y el nombre que se muestra
            $tipos = [
                \App\Models\Character::class => ['Personaje', 'characters.show'],
                \App\Models\Episode::class => ['Episodio', 'episodes.show'],
                \App\Models\Location::class => ['Ubicación', 'locations.show'],
            ];
        @endphp

        <ul class="lista-enlaces">
            @forelse($favorites as $favorite)
                <li>
                    @if($favorite->favorable)
                        @php [$tipo, $ruta] = $tipos[$favorite->favorable_type] ?? ['Elemento', null]; @endphp
                        <span>
                            @if($ruta)
                                <a href="{{ route($ruta, $favorite->favorable->getKey()) }}"><strong>{{ $favorite->favorable->name }}</strong></a>
                            @else
                                <strong>{{ $favorite->favorable->name }}</strong>
                            @endif
                            <span class="etiqueta-tipo">{{ $tipo }}</span>
                        </span>
                    @else
                        <span class="detalle">El elemento original ya no existe.</span>
                    @endif

                    <form action="{{ route('favorites.destroy', $favorite->id) }}" method="POST" class="form-en-linea" onsubmit="return confirm('¿Eliminar este favorito?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-peligro btn-chico">Eliminar</button>
                    </form>
                </li>
            @empty
                <li>Todavía no guardaste favoritos.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
