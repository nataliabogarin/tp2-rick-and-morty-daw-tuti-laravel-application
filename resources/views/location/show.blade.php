@extends('layout.layout')

@section('titulo', $location->name)

{{-- Para visitantes, la ficha ya muestra "Registrarse" con su explicación: no lo repetimos arriba --}}
@guest
    @section('sin-registro-en-encabezado', true)
@endguest

@section('contenido')
<div class="pagina">
    <a href="{{ route('locations.index') }}" class="btn btn-secundario btn-chico volver">← Volver a ubicaciones</a>

    <div class="panel">
        <div class="info">
            <h2>{{ $location->name }}</h2>
            <div class="dato"><span class="etiqueta">Tipo</span><span class="valor">{{ $location->type ?: 'Desconocido' }}</span></div>
            <div class="dato"><span class="etiqueta">Dimensión</span><span class="valor">{{ $location->dimension ?: 'Desconocida' }}</span></div>
        </div>

        @auth
            @include('partials.boton-favorito', ['modelo' => $location, 'tipo' => 'location'])
        @else
            {{-- Visitante: le explicamos para qué sirve registrarse --}}
            @include('partials.invitacion-registro', ['nombre' => $location->name, 'conListas' => false])
        @endauth
    </div>

    <div class="panel">
        <h3>Personajes con origen en esta ubicación ({{ $originCharacters->total() }})</h3>
        <p class="subtitulo">Hacé clic en el nombre de un personaje para ver su ficha completa.</p>
        <ul class="lista-enlaces">
            @forelse($originCharacters as $character)
                <li>
                    <a href="{{ route('characters.show', $character->id_character) }}">
                        <img src="{{ $character->image }}" alt="" loading="lazy">{{ $character->name }}
                    </a>
                    <span class="badge {{ $character->status }}">{{ $character->status }}</span>
                </li>
            @empty
                <li>No hay personajes con este origen.</li>
            @endforelse
        </ul>
        {{ $originCharacters->links() }}
    </div>

    <div class="panel">
        <h3>Personajes ubicados aquí actualmente ({{ $currentCharacters->total() }})</h3>
        <p class="subtitulo">Hacé clic en el nombre de un personaje para ver su ficha completa.</p>
        <ul class="lista-enlaces">
            @forelse($currentCharacters as $character)
                <li>
                    <a href="{{ route('characters.show', $character->id_character) }}">
                        <img src="{{ $character->image }}" alt="" loading="lazy">{{ $character->name }}
                    </a>
                    <span class="badge {{ $character->status }}">{{ $character->status }}</span>
                </li>
            @empty
                <li>No hay personajes ubicados aquí actualmente.</li>
            @endforelse
        </ul>
        {{ $currentCharacters->links() }}
    </div>
</div>
@endsection
