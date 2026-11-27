{{-- Botón de favoritos que comparten las fichas de personaje, episodio y ubicación.
     Recibe: $modelo (el personaje, episodio o ubicación) y $tipo ('character', 'episode' o 'location').
     Si ya está en favoritos muestra el estado y la opción de quitarlo, así no se puede
     volver a guardar lo mismo. --}}
@php
    $favorito = auth()->user()->favorites()
        ->where('favorable_type', get_class($modelo))
        ->where('favorable_id', $modelo->getKey())
        ->first();
@endphp

<div class="acciones-ficha">
    @if($favorito)
        <span class="estado-favorito">⭐ Está en tus favoritos</span>
        <form action="{{ route('favorites.destroy', $favorito->id) }}" method="POST" class="form-en-linea">
            @csrf
            @method('DELETE')
            {{-- Avisamos que viene de la ficha para mostrar el mensaje al lado del botón --}}
            <input type="hidden" name="desde_ficha" value="1">
            <button type="submit" class="btn btn-secundario">Quitar de favoritos</button>
        </form>
    @else
        <form action="{{ route('favorites.store', ['type' => $tipo, 'id' => $modelo->getKey()]) }}" method="POST" class="form-en-linea">
            @csrf
            <button type="submit" class="btn">☆ Guardar en favoritos</button>
        </form>
    @endif
</div>

@if(session('favorito'))
    <div class="alerta alerta-exito mensaje-favorito">{{ session('favorito') }}</div>
@endif
