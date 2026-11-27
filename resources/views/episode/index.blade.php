@extends('layout.layout')

@section('titulo', 'Episodios')

@section('contenido')
<div class="pagina">
    <div class="panel">
        <h1 class="titulo-pagina">Episodios</h1>
        <p class="subtitulo">Total de episodios registrados: {{ $episodes->total() }}</p>

        @php
            // Agrupamos solo los episodios de la página actual por temporada,
            // usando los primeros 3 caracteres del código (ej. "S01", "S02")
            $seasons = $episodes->getCollection()->groupBy(function ($item) {
                return substr($item->episode, 0, 3);
            });
        @endphp

        @forelse($seasons as $seasonName => $episodesInSeason)
            <h2 class="titulo-temporada">Temporada {{ (int) str_replace('S', '', $seasonName) }}</h2>
            <ul class="lista-enlaces">
                @foreach($episodesInSeason as $episode)
                    <li>
                        <a href="{{ route('episodes.show', $episode->id_episode) }}"><strong>{{ $episode->episode }}</strong> · {{ $episode->name }}</a>
                        <span class="acciones-item">
                            <span class="detalle">Emitido el {{ $episode->air_date }}</span>
                            <a href="{{ route('episodes.show', $episode->id_episode) }}" class="btn btn-chico">Ver más</a>
                        </span>
                    </li>
                @endforeach
            </ul>
        @empty
            <p>No hay episodios registrados en la base de datos.</p>
        @endforelse

        {{ $episodes->links() }}
    </div>
</div>
@endsection
