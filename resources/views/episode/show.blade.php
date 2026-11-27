@extends('layout.layout')

@section('titulo', $episode->name)

{{-- Para visitantes, la ficha ya muestra "Registrarse" con su explicación: no lo repetimos arriba --}}
@guest
    @section('sin-registro-en-encabezado', true)
@endguest

@section('contenido')
<div class="pagina">
    <a href="{{ route('episodes.index') }}" class="btn btn-secundario btn-chico volver">← Volver a episodios</a>

    <div class="panel">
        <div class="info">
            <h2>{{ $episode->name }}</h2>
            <div class="dato"><span class="etiqueta">Código</span><span class="valor">{{ $episode->episode }}</span></div>
            <div class="dato"><span class="etiqueta">Fecha de emisión</span><span class="valor">{{ $episode->air_date }}</span></div>
            <div class="dato"><span class="etiqueta">Personajes</span><span class="valor">{{ $episode->characters->count() }}</span></div>
        </div>

        @auth
            @include('partials.boton-favorito', ['modelo' => $episode, 'tipo' => 'episode'])
        @else
            {{-- Visitante: le explicamos para qué sirve registrarse --}}
            @include('partials.invitacion-registro', ['nombre' => $episode->name, 'conListas' => true])
        @endauth
    </div>

    @auth
        @include('partials.agregar-a-lista', [
            'listas' => $listas,
            'tipo' => 'episode',
            'itemId' => $episode->id_episode,
            'nombre' => $episode->name,
        ])
    @endauth

    <div class="panel">
        <h3>Personajes que aparecen en este episodio</h3>
        <p class="subtitulo">Hacé clic en el nombre de un personaje para ver su ficha completa.</p>
        <ul class="lista-enlaces">
            @forelse($episode->characters as $character)
                <li>
                    <a href="{{ route('characters.show', $character->id_character) }}">
                        <img src="{{ $character->image }}" alt="" loading="lazy">{{ $character->name }}
                    </a>
                    <span class="badge {{ $character->status }}">{{ $character->status }}</span>
                </li>
            @empty
                <li>No hay personajes registrados para este episodio.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
