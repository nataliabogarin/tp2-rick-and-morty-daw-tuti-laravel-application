@extends('layout.layout')

@section('contenido')
       
    <h1>{{ $character->name }}</h1>
    {{-- Si la imagen de la API no carga, mostramos una imagen local de respaldo --}}
    <img src="{{ $character->image }}" alt="{{ $character->name }}" width="200" onerror="this.onerror=null; this.src='{{ asset('images/personaje-sin-imagen.jpg') }}';">

    <p><strong>Estado:</strong> {{ $character->status }}</p>
    <p><strong>Especie:</strong> {{ $character->species }}</p>
    <p><strong>Género:</strong> {{ $character->gender }}</p>

    {{-- Si la ubicación existe mostramos un link a su detalle, si no (origen "unknown" en la API) solo el texto --}}
    <p><strong>Origen:</strong>
        @if($character->originLocation)
            <a href="{{ route('locations.show', $character->originLocation->id_location) }}">{{ $character->originLocation->name }}</a>
        @else
            Desconocido
        @endif
    </p>
    <p><strong>Ubicación Actual:</strong>
        @if($character->currentLocation)
            <a href="{{ route('locations.show', $character->currentLocation->id_location) }}">{{ $character->currentLocation->name }}</a>
        @else
            Desconocida
        @endif
    </p>

    {{-- Favoritos solo para usuarios logueados --}}
    @auth
        <form action="{{ route('favorites.store', ['type' => 'character', 'id' => $character->id_character]) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">⭐ Guardar Personaje</button>
        </form>
    @endauth

    <h3>Episodios en los que aparece:</h3>
    <p><strong>Total de episodios:</strong> {{ $character->episode->count() }}</p>
    <ul>
        @forelse($character->episode as $episode)
            <li>
                <a href="{{ route('episodes.show', $episode->id_episode) }}">
                    <strong>{{ $episode->episode }}:</strong> {{ $episode->name }}
                </a>
            </li>
        @empty
            <li>No aparece en ningún episodio registrado.</li>
        @endforelse
    </ul>
    @auth
    <hr>
    <h3>Agregar a una lista de personajes</h3>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif
    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif
    @error('item_id')
        <p style="color: red;">{{ $message }}</p>
    @enderror

    @php
        $listasPersonajes = auth()->user()->customLists()->where('type', 'character')->get();
    @endphp

    @if($listasPersonajes->isEmpty())
        <p>No tenés listas de personajes. <a href="{{ route('listas.index') }}">Creá una acá</a>.</p>
    @else
        @foreach($listasPersonajes as $lista)
            <form action="{{ route('listas.items.add', $lista) }}" method="POST" style="display:inline-block; margin-right: 8px;">
                @csrf
                <input type="hidden" name="item_id" value="{{ $character->id_character }}">
                <button type="submit">+ {{ $lista->name }}</button>
            </form>
        @endforeach
    @endif
@endauth
    @endsection
    
