@extends('layout.layout')
@section('contenido')
    

    <h1>Listado de Episodios de Rick and Morty</h1>

    <p><strong>Total de episodios registrados:</strong> {{ count($episodes) }}</p>

    @php
        // Agrupamos los episodios usando los primeros 3 caracteres del código (ej. "S01", "S02")
            $seasons = $episodes->groupBy(function ($item) {
            return substr($item->episode, 0, 3); // Agrupa por "S01", "S02", etc.
        });
    @endphp

    @foreach($seasons as $seasonName => $episodesInSeason)
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
        <!-- Espacio o línea divisoria adicional entre temporadas -->
            <hr style="border: 0; border-top: 1px dashed #eee; margin: 20px 0;">
        @endforeach
      @endsection
    <!-- <ul> -->
        <!-- @forelse($episodes as $episode) -->
            <!-- <li> -->
                <!-- Si más adelante creas una ruta para ver el detalle de cada episodio, puedes usar route('episodes.show', $episode->id_episode) -->
                <!-- <strong>{{ $episode->episode }}</strong>: {{ $episode->name }}  -->
                <!-- <span style="color: gray;">(Emitido el: {{ $episode->air_date }})</span> -->
                
            <!-- </li> -->
        <!-- @empty -->
            <!-- <li>No hay episodios registrados en la base de datos.</li> -->
        <!-- @endforelse -->
    <!-- </ul> -->



