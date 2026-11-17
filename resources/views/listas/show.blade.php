@extends('layout.layout')

@section('contenido')
    <h1>Lista: {{ $customList->name }}</h1>
    <p>Tipo: <strong>{{ $customList->type === 'character' ? 'Personajes' : 'Episodios' }}</strong></p>
    <a href="{{ route('listas.index') }}">← Volver a mis listas</a>

    @if(session('success'))
        <p style="color: green;"><strong>{{ session('success') }}</strong></p>
    @endif

    <hr>

    @if($elements->isEmpty())
        <p>Esta lista está vacía. Agregá elementos desde el explorador de {{ $customList->type === 'character' ? 'personajes' : 'episodios' }}.</p>
    @else
        <ul>
            @foreach($elements as $element)
                <li>
                    @if($customList->type === 'character')
                        <img src="{{ $element->image }}" alt="{{ $element->name }}" width="60">
                        <a href="{{ route('characters.show', ['id' => $element->id_character]) }}">
                            {{ $element->name }}
                        </a>
                        <form action="{{ route('listas.items.remove', [$customList, $element->id_character]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Quitar</button>
                        </form>
                    @else
                        <a href="{{ route('episodes.show', ['id' => $element->id_episode]) }}">
                            {{ $element->episode }} - {{ $element->name }}
                        </a>
                        <form action="{{ route('listas.items.remove', [$customList, $element->id_episode]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Quitar</button>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
    
@endsection