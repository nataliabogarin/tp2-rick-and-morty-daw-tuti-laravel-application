@extends('layout.layout')

@section('contenido')
    <h1>Lista de Personajes</h1>
    
    <p><strong>Total de personajes:</strong> {{ $characters->total() }}</p>
    <ul>
        @foreach($characters as $character)
            <li>
                <img src="{{ $character->image }}" alt="{{ $character->name }}" width="100">
                <h2>{{ $character->name }}</h2>
                <p>Especie: {{ $character->species }}</p>
                <p>Estado: {{ $character->status }}</p>

                <p>Ubicación de origen:
                    @if($character->originLocation)
                        <a href="{{ route('locations.show', $character->originLocation->id_location) }}">{{ $character->originLocation->name }}</a>
                    @else
                        Desconocida
                    @endif
                </p>
                <p>Ubicación actual:
                    @if($character->currentLocation)
                        <a href="{{ route('locations.show', $character->currentLocation->id_location) }}">{{ $character->currentLocation->name }}</a>
                    @else
                        Desconocida
                    @endif
                </p>
                <a href="{{ route('characters.show', ['id' => $character->id_character]) }}">Ver detalles: {{ $character->name }}</a>
                <hr>
            </li>
        @endforeach    
    </ul>

    {{-- Controles de paginación: siempre debajo de </ul> y antes de @endsection --}}
    <div class="mt-6">
        {{ $characters->links() }}
    </div>
@endsection