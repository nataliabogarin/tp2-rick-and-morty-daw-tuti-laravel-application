@extends('layout.layout')

@section('titulo', 'Personajes')

@section('contenido')
<div class="pagina">
    <div class="panel">
        <h1 class="titulo-pagina">Personajes</h1>
        <p class="subtitulo">Total de personajes: {{ $characters->total() }}</p>

        <ul class="grilla-personajes">
            @foreach($characters as $character)
                {{-- Toda la card es clickeable: el link del nombre se "estira" sobre la card con CSS.
                     Los links de ubicación y el botón quedan por encima para seguir funcionando. --}}
                <li class="tarjeta-personaje">
                    <img src="{{ $character->image }}" alt="{{ $character->name }}" loading="lazy">
                    <div class="cuerpo">
                        <h2><a href="{{ route('characters.show', ['id' => $character->id_character]) }}" class="enlace-tarjeta">{{ $character->name }}</a></h2>
                        <p><span class="badge {{ $character->status }}">{{ $character->status }}</span> {{ $character->species }}</p>
                        <p>Origen:
                            @if($character->originLocation)
                                <a href="{{ route('locations.show', $character->originLocation->id_location) }}" class="enlace-interno">{{ $character->originLocation->name }}</a>
                            @else
                                Desconocida
                            @endif
                        </p>
                        <p>Ubicación actual:
                            @if($character->currentLocation)
                                <a href="{{ route('locations.show', $character->currentLocation->id_location) }}" class="enlace-interno">{{ $character->currentLocation->name }}</a>
                            @else
                                Desconocida
                            @endif
                        </p>
                    </div>
                    <div class="pie-tarjeta">
                        <a href="{{ route('characters.show', ['id' => $character->id_character]) }}" class="btn btn-chico enlace-interno">Ver más</a>
                    </div>
                </li>
            @endforeach
        </ul>

        {{ $characters->links() }}
    </div>
</div>
@endsection
