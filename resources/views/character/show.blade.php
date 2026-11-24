@extends('layout.layout')

@section('contenido')
       
    <h1>{{ $character->name }}</h1>
    <img src="{{ $character->image }}" alt="{{ $character->name}}" width="200"onerror="this.onerror=null; this.src='{{ asset('images/fuck-smith-cartoon-sanchez-rick-hd-wallpaper.jpg') }}';">

    <p><strong>Estado:</strong> {{ $character->status }}</p>
    <p><strong>Especie:</strong> {{ $character->species }}</p>
    <p><strong>Género:</strong> {{ $character->gender }}</p>

    {{-- Mostramos las relaciones de ubicación --}}
    <p><strong>Origen:</strong> 
        {{ $character->originLocation ? $character->originLocation->name : 'Desconocido' }}
    </p>
    <p><strong>Ubicación Actual:</strong> 
        {{ $character->currentLocation ? $character->currentLocation->name : 'Desconocida' }}
    </p>

    <form action="{{ route('favorites.store', ['type' => 'character', 'id' => $character->id_character]) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">⭐ Guardar Personaje</button>
    </form>



    {{-- O tambien puedes usar el método de coleccion: {{ $character->episode->count()}} esto es un comentario--}}
    {{-- Aquí sí usas @foreach pero solo para listar sus episodios --}}
    <h3>Episodios en los que aparece:</h3>
    <p><strong>Total de episodios:</strong> {{ count($character->episode) }}</p>
    <ul>
        @forelse($character->episode ??[] as $episode)
            <li>
                <!-- {{ $episode->name }} ({{ $episode->episode }}) -->
            <a href="{{ route('episodes.show', $episode->id_episode) }}">
                <strong>{{$episode->episode}}: </strong>{{ $episode->name }}
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
    
