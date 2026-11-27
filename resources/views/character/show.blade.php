@extends('layout.layout')

@section('titulo', $character->name)

{{-- Para visitantes, la ficha ya muestra "Registrarse" con su explicación: no lo repetimos arriba --}}
@guest
    @section('sin-registro-en-encabezado', true)
@endguest

@section('contenido')
<div class="pagina">
    <a href="{{ route('characters.index') }}" class="btn btn-secundario btn-chico volver">← Volver a personajes</a>

    {{-- Misma ficha que el panel de detalle del TP1, pero con los datos navegables --}}
    <div class="panel ficha">
        <div class="foto">
            {{-- Si la imagen de la API no carga, mostramos una imagen local de respaldo --}}
            <img src="{{ $character->image }}" alt="{{ $character->name }}" onerror="this.onerror=null; this.src='{{ asset('images/personaje-sin-imagen.jpg') }}';">
        </div>
        <div class="info">
            <h2>{{ $character->name }}</h2>
            <div class="dato"><span class="etiqueta">Estado</span><span class="badge {{ $character->status }}">{{ $character->status }}</span></div>
            <div class="dato"><span class="etiqueta">Especie</span><span class="valor">{{ $character->species }}</span></div>
            <div class="dato"><span class="etiqueta">Género</span><span class="valor">{{ $character->gender }}</span></div>
            <div class="dato"><span class="etiqueta">Tipo</span><span class="valor">{{ $character->type ?: 'Ninguno' }}</span></div>
            {{-- Si la ubicación existe mostramos un link a su detalle, si no (origen "unknown" en la API) solo el texto --}}
            <div class="dato"><span class="etiqueta">Origen</span>
                @if($character->originLocation)
                    <a href="{{ route('locations.show', $character->originLocation->id_location) }}">{{ $character->originLocation->name }}</a>
                @else
                    <span class="valor">Desconocido</span>
                @endif
            </div>
            <div class="dato"><span class="etiqueta">Ubicación actual</span>
                @if($character->currentLocation)
                    <a href="{{ route('locations.show', $character->currentLocation->id_location) }}">{{ $character->currentLocation->name }}</a>
                @else
                    <span class="valor">Desconocida</span>
                @endif
            </div>

            @auth
                @include('partials.boton-favorito', ['modelo' => $character, 'tipo' => 'character'])
            @else
                {{-- Visitante: le explicamos para qué sirve registrarse --}}
                @include('partials.invitacion-registro', ['nombre' => $character->name, 'conListas' => true])
            @endauth
        </div>
    </div>

    @auth
        @include('partials.agregar-a-lista', [
            'listas' => $listas,
            'tipo' => 'character',
            'itemId' => $character->id_character,
            'nombre' => $character->name,
        ])
    @endauth

    <div class="panel">
        <h3>Episodios en los que aparece ({{ $character->episode->count() }})</h3>
        <p class="subtitulo">Hacé clic en un episodio para ver su detalle y el resto de los personajes.</p>
        <ul class="lista-enlaces">
            @forelse($character->episode as $episode)
                <li>
                    <a href="{{ route('episodes.show', $episode->id_episode) }}"><strong>{{ $episode->episode }}</strong> · {{ $episode->name }}</a>
                    <span class="detalle">{{ $episode->air_date }}</span>
                </li>
            @empty
                <li>No aparece en ningún episodio registrado.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
