@extends('layout.layout')

@section('titulo', $customList->name)

@section('contenido')
<div class="pagina">
    <a href="{{ route('listas.index') }}" class="btn btn-secundario btn-chico volver">← Volver a mis listas</a>

    <div class="panel">
        <h1 class="titulo-pagina">{{ $customList->name }}</h1>
        <p class="subtitulo">
            <span class="etiqueta-tipo">{{ $customList->type === 'character' ? 'Personajes' : 'Episodios' }}</span>
            {{ $elements->count() }} elementos
        </p>

        @if(session('success'))
            <div class="alerta alerta-exito">{{ session('success') }}</div>
        @endif

        <form action="{{ route('listas.update', $customList) }}" method="POST" class="formulario form-fila">
            @csrf
            @method('PUT')
            <div class="campo">
                <label for="name">Renombrar lista</label>
                <input type="text" name="name" id="name" value="{{ old('name', $customList->name) }}" required maxlength="100">
                @error('name') <span class="error-campo">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn">Guardar nombre</button>
        </form>
    </div>

    <div class="panel">
        @if($elements->isEmpty())
            <p>Esta lista está vacía. Agregá elementos desde el detalle de cada {{ $customList->type === 'character' ? 'personaje' : 'episodio' }}.</p>
        @else
            <ul class="lista-enlaces">
                @foreach($elements as $element)
                    <li>
                        @if($customList->type === 'character')
                            <a href="{{ route('characters.show', ['id' => $element->id_character]) }}">
                                <img src="{{ $element->image }}" alt="" loading="lazy">{{ $element->name }}
                            </a>
                            <form action="{{ route('listas.items.remove', [$customList, $element->id_character]) }}" method="POST" class="form-en-linea">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-peligro btn-chico">Quitar</button>
                            </form>
                        @else
                            <a href="{{ route('episodes.show', ['id' => $element->id_episode]) }}">
                                <strong>{{ $element->episode }}</strong> · {{ $element->name }}
                            </a>
                            <form action="{{ route('listas.items.remove', [$customList, $element->id_episode]) }}" method="POST" class="form-en-linea">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-peligro btn-chico">Quitar</button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
