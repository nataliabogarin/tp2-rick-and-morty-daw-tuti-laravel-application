@extends('layout.layout')
@section('contenido')

    <h1>Listado de Episodios de Rick and Morty</h1>

    <p><strong>Total de episodios registrados:</strong> {{ $episodes->total() }}</p>

    @php
        // Agrupamos solo los episodios de la página actual por temporada,
        // usando los primeros 3 caracteres del código (ej. "S01", "S02")
        $seasons = $episodes->getCollection()->groupBy(function ($item) {
            return substr($item->episode, 0, 3);
        });
    @endphp

    @forelse($seasons as $seasonName => $episodesInSeason)
        <h2 style="margin-top: 40px; border-bottom: 2px solid #ccc; padding-bottom: 5px;">
            Temporada {{ str_replace('S', '', $seasonName) }}
        </h2>
        <ul>
            @foreach($episodesInSeason as $episode)
                <li>
                    <a href="{{ route('episodes.show', $episode->id_episode) }}">
                        <strong>{{ $episode->episode }}</strong>: {{ $episode->name }}
                    </a>
                    <span style="color: gray;">(Emitido el: {{ $episode->air_date }})</span>
                </li>
            @endforeach
        </ul>
        <hr style="border: 0; border-top: 1px dashed #eee; margin: 20px 0;">
    @empty
        <p>No hay episodios registrados en la base de datos.</p>
    @endforelse

    {{ $episodes->links() }}
@endsection
