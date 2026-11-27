@extends('layout.layout')

@section('titulo', 'Ubicaciones')

@section('contenido')
<div class="pagina">
    <div class="panel">
        <h1 class="titulo-pagina">Ubicaciones</h1>
        {{-- total() devuelve la cantidad completa, no solo la de esta página --}}
        <p class="subtitulo">Total de ubicaciones registradas: {{ $locations->total() }}</p>

        <ul class="lista-enlaces">
            @forelse($locations as $location)
                <li>
                    <a href="{{ route('locations.show', $location->id_location) }}"><strong>{{ $location->name }}</strong></a>
                    <span class="acciones-item">
                        <span class="detalle">{{ $location->type ?: 'Tipo desconocido' }} · {{ $location->dimension ?: 'Dimensión desconocida' }}</span>
                        <a href="{{ route('locations.show', $location->id_location) }}" class="btn btn-chico">Ver más</a>
                    </span>
                </li>
            @empty
                <li>No hay ubicaciones registradas en la base de datos.</li>
            @endforelse
        </ul>

        {{ $locations->links() }}
    </div>
</div>
@endsection
