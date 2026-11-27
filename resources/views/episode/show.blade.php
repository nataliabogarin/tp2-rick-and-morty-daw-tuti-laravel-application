@extends('layout.layout')
@section('contenido')   
  
        
        
        <h1>Nombre del episodio: <br> {{ $episode->name }}</h1>
        <p><strong>Código:</strong> {{ $episode->episode }}</p>
        <p><strong>Fecha de emisión:</strong> {{ $episode->air_date }}</p>
        
        @auth
            <form action="{{ route('favorites.store', ['type' => 'episode', 'id' => $episode->id_episode]) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">⭐ Guardar Episodio</button>
            </form>
        @endauth
    <hr>
 <br>
    <a href="{{ url()->previous()}} " >Volver a la página anterior </a>

    <h3>Personajes que aparecen en este episodio ({{ $episode->characters->count() }}):</h3>

    <ul>
        @forelse($episode->characters as $character)
            <li>
                <a href="{{ route('characters.show', $character->id_character) }}">
                    {{ $character->name }}
                </a>
            </li>
        @empty
            <li>No hay personajes registrados para este episodio.</li>
        @endforelse
    </ul>
    @auth
    <hr>
    <h3>Agregar a una lista de episodios</h3>

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
        $listasEpisodios = auth()->user()->customLists()->where('type', 'episode')->get();
    @endphp

    @if($listasEpisodios->isEmpty())
        <p>No tenés listas de episodios. <a href="{{ route('listas.index') }}">Creá una acá</a>.</p>
    @else
        @foreach($listasEpisodios as $lista)
            <form action="{{ route('listas.items.add', $lista) }}" method="POST" style="display:inline-block; margin-right: 8px;">
                @csrf
                <input type="hidden" name="item_id" value="{{ $episode->id_episode }}">
                <button type="submit">+ {{ $lista->name }}</button>
            </form>
        @endforeach
    @endif
@endauth
@endsection
    
