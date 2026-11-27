@extends('layout.layout')

@section('titulo', 'Mis listas')

@section('contenido')
<div class="pagina">
    <div class="panel">
        <h1 class="titulo-pagina">Mis listas personalizadas</h1>
        <p class="subtitulo">Organizá personajes o episodios en listas propias.</p>

        @if(session('success'))
            <div class="alerta alerta-exito">{{ session('success') }}</div>
        @endif

        <h3>Crear nueva lista</h3>
        <form action="{{ route('listas.store') }}" method="POST" class="formulario form-fila">
            @csrf
            <div class="campo">
                <label for="name">Nombre de la lista</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required maxlength="100" placeholder="Ej: Favoritos Temporada 1">
                @error('name') <span class="error-campo">{{ $message }}</span> @enderror
            </div>
            <div class="campo">
                <label for="type">Tipo de contenido</label>
                <select name="type" id="type" required>
                    <option value="character" @selected(old('type') === 'character')>Personajes</option>
                    <option value="episode" @selected(old('type') === 'episode')>Episodios</option>
                </select>
                @error('type') <span class="error-campo">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn">Crear lista</button>
        </form>
    </div>

    <div class="panel">
        <h3>Tus listas</h3>
        @if($lists->isEmpty())
            <p>Todavía no creaste ninguna lista.</p>
        @else
            <ul class="lista-enlaces">
                @foreach($lists as $list)
                    <li>
                        <span>
                            <a href="{{ route('listas.show', $list) }}"><strong>{{ $list->name }}</strong></a>
                            <span class="etiqueta-tipo">{{ $list->type === 'character' ? 'Personajes' : 'Episodios' }}</span>
                            <span class="detalle">{{ $list->items_count }} elementos</span>
                        </span>
                        <span>
                            <a href="{{ route('listas.show', $list) }}" class="btn btn-secundario btn-chico">Ver / renombrar</a>
                            <form action="{{ route('listas.destroy', $list) }}" method="POST" class="form-en-linea" onsubmit="return confirm('¿Eliminar la lista {{ $list->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-peligro btn-chico">Eliminar</button>
                            </form>
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
