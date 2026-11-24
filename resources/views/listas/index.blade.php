@extends('layout.layout')

@section('contenido')
    <h1>Mis Listas Personalizadas</h1>

    @if(session('success'))
        <p style="color: green;"><strong>{{ session('success') }}</strong></p>
    @endif

    <h2>Crear nueva lista</h2>
    <form action="{{ route('listas.store') }}" method="POST">
        @csrf
        <label for="name">Nombre de la lista:</label>
        <input type="text" name="name" id="name" required placeholder="Ej: Favoritos Temporada 1">

        <label for="type">Tipo de contenido:</label>
        <select name="type" id="type" required>
            <option value="character">Personajes</option>
            <option value="episode">Episodios</option>
        </select>

        <button type="submit">Crear Lista</button>
    </form>

    <hr>

    <h2>Tus listas creadas</h2>
    @if($lists->isEmpty())
        <p>Todavía no creaste ninguna lista.</p>
    @else
        <ul>
            @foreach($lists as $list)
                <li>
                    <strong>{{ $list->name }}</strong>
                    ({{ $list->type === 'character' ? 'Personajes' : 'Episodios' }} — {{ $list->items_count }} elementos)
                    | <a href="{{ route('listas.show', $list) }}">Ver contenido</a>
                    <form action="{{ route('listas.destroy', $list) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Eliminar lista</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
@endsection