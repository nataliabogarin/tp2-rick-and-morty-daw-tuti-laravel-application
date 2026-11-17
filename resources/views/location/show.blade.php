@extends('layout.layout')
@section('contenido')
    
        @php
        $characterName = request('character_name');
        @endphp
<!--         
        <div style="margin-bottom: 20px;">
            @if($characterName)
            <a href="{{ url()->previous() }}">← Volver a {{ $characterName }}</a>
            @else
            <a href="{{ route('locations.index') }}">← Volver al listado de ubicaciones</a>
            @endif
        </div> -->
        
        <h1>{{ $location->name }}</h1>
        <p><strong>Tipo:</strong> {{ $location->type }}</p>
        <p><strong>Dimensión:</strong> {{ $location->dimension }}</p>
        
        <form action="{{ route('favorites.store', ['type' => 'location', 'id' => $location->id_location]) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">⭐ Guardar Locación</button>
        </form>
        
        <hr>
<!-- Personajes con origen -->
<h2>Personajes con origen en esta ubicación ({{ ($location->originCharacters ?? collect())->count() }})</h2>
<ul>
    @forelse($location->originCharacters ?? [] as $character)
        <li>
            <a href="{{ route('characters.show', $character->id_character) }}">
                <strong>{{ $character->name }}</strong>
            </a>
        </li>
    @empty
        <li>No hay personajes con este origen.</li>
    @endforelse
</ul>

<!-- Personajes actuales -->
<h2>Personajes ubicados aquí actualmente ({{ ($location->currentCharacters ?? collect())->count() }})</h2>
<ul>
    @forelse($location->currentCharacters ?? [] as $character)
        <li>
            <a href="{{ route('characters.show', $character->id_character) }}">
                <strong>{{ $character->name }}</strong>
            </a>
        </li>
    @empty
        <li>No hay personajes ubicados aquí actualmente.</li>
    @endforelse
</ul>

@endsection