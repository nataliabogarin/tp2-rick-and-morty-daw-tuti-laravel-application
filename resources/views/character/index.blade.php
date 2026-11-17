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

                <p>Ubicación de origen: {{ $character->originLocation->name ?? 'Desconocida' }}</p>
                <p>Ubicación actual: {{ $character->currentLocation->name ?? 'Desconocida' }}</p>
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