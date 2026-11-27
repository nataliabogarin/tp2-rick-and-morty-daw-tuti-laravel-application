@extends('layout.layout')
@section('contenido')

    <h1>Listado de Locaciones de Rick and Morty</h1>

    {{-- total() devuelve la cantidad completa, no solo la de esta página --}}
    <p><strong>Total de locaciones registradas:</strong> {{ $locations->total() }}</p>

    <ul>
        @forelse($locations as $location)
            <li>
                <a href="{{ route('locations.show', $location->id_location) }}">
                    <strong>{{ $location->name }}</strong>
                </a>
                <span style="color: gray;">(Tipo: {{ $location->type }} - Dimensión: {{ $location->dimension }})</span>
            </li>
        @empty
            <li>No hay locaciones registradas en la base de datos.</li>
        @endforelse
    </ul>

    {{ $locations->links() }}
@endsection
