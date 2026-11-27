@extends('layout.layout')
@section('contenido')

    <p><a href="{{ route('locations.index') }}">← Volver al listado de ubicaciones</a></p>

    <h1>{{ $location->name }}</h1>
    <p><strong>Tipo:</strong> {{ $location->type }}</p>
    <p><strong>Dimensión:</strong> {{ $location->dimension }}</p>

    @auth
        <form action="{{ route('favorites.store', ['type' => 'location', 'id' => $location->id_location]) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">⭐ Guardar Locación</button>
        </form>
    @endauth

    <hr>

    <h2>Personajes con origen en esta ubicación ({{ $originCharacters->total() }})</h2>
    <ul>
        @forelse($originCharacters as $character)
            <li>
                <a href="{{ route('characters.show', $character->id_character) }}">
                    <strong>{{ $character->name }}</strong>
                </a>
            </li>
        @empty
            <li>No hay personajes con este origen.</li>
        @endforelse
    </ul>
    {{ $originCharacters->links() }}

    <h2>Personajes ubicados aquí actualmente ({{ $currentCharacters->total() }})</h2>
    <ul>
        @forelse($currentCharacters as $character)
            <li>
                <a href="{{ route('characters.show', $character->id_character) }}">
                    <strong>{{ $character->name }}</strong>
                </a>
            </li>
        @empty
            <li>No hay personajes ubicados aquí actualmente.</li>
        @endforelse
    </ul>
    {{ $currentCharacters->links() }}

@endsection
