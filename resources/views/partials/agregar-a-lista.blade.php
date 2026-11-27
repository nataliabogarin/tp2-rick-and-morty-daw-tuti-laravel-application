{{-- Bloque "Mis listas" que comparten la ficha de personaje y la de episodio.
     Recibe: $listas (con items_count y contiene), $tipo ('character' o 'episode'),
     $itemId (id del personaje o episodio) y $nombre (para los textos).
     1) "Guardado en": etiquetas de las listas donde ya está, cada una con su botón para quitar.
     2) "Agregar a una lista": un desplegable con las listas donde todavía no está.
     Cada acción se aplica al instante, no hay que confirmar nada aparte. --}}
@php
    $tipoTexto = $tipo === 'character' ? 'personajes' : 'episodios';
    $listasConElemento = $listas->where('contiene', true);
    $listasDisponibles = $listas->where('contiene', false);
@endphp

<div class="panel">
    <h3>📋 Mis listas</h3>

    @if(session('success'))
        <div class="alerta alerta-exito">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alerta alerta-error">{{ session('error') }}</div>
    @endif
    @error('item_id')
        <div class="alerta alerta-error">{{ $message }}</div>
    @enderror

    {{-- 1) Dónde está guardado --}}
    <div class="bloque-listas">
        <p class="titulo-bloque">Guardado en</p>
        @if($listasConElemento->isEmpty())
            <p class="texto-vacio">{{ $nombre }} todavía no está en ninguna de tus listas.</p>
        @else
            <div class="etiquetas-listas">
                @foreach($listasConElemento as $lista)
                    <span class="etiqueta-lista">
                        <a href="{{ route('listas.show', $lista) }}">{{ $lista->name }}</a>
                        <form action="{{ route('listas.items.remove', [$lista, $itemId]) }}" method="POST" class="form-en-linea">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="quitar-etiqueta" title="Quitar de {{ $lista->name }}" aria-label="Quitar de {{ $lista->name }}">✕</button>
                        </form>
                    </span>
                @endforeach
            </div>
        @endif
    </div>

    {{-- 2) Agregar a otra lista existente --}}
    @if($listasDisponibles->isNotEmpty())
        <div class="bloque-listas">
            <p class="titulo-bloque">Agregar a una lista</p>
            <form action="{{ route('listas.items.add', $listasDisponibles->first()) }}" method="POST" class="formulario form-fila" id="form-agregar-lista">
                @csrf
                <input type="hidden" name="item_id" value="{{ $itemId }}">
                <div class="campo">
                    <label for="lista-destino" class="oculto-visual">Lista de destino</label>
                    {{-- Cada opción guarda la URL de su lista; el JS cambia el action del form al elegir --}}
                    <select id="lista-destino" onchange="this.form.action = this.value">
                        @foreach($listasDisponibles as $lista)
                            <option value="{{ route('listas.items.add', $lista) }}">{{ $lista->name }} ({{ $lista->items_count }})</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn">Agregar</button>
            </form>
        </div>
    @elseif($listas->isNotEmpty())
        <p class="texto-vacio">Ya está en todas tus listas de {{ $tipoTexto }}.</p>
    @endif

    {{-- 3) Crear una lista nueva y guardar el elemento en un solo paso --}}
    {{-- Va en una caja aparte para que se distinga de "Agregar a una lista" --}}
    <div class="crear-lista-rapida">
        <h4 class="titulo-crear-lista">Crear una lista nueva</h4>
        <p class="descripcion-crear-lista">¿No tenés la lista que buscás? Creala y {{ $nombre }} se guarda ahí directamente.</p>
        <form action="{{ route('listas.store') }}" method="POST" class="formulario form-fila">
            @csrf
            <input type="hidden" name="type" value="{{ $tipo }}">
            <input type="hidden" name="item_id" value="{{ $itemId }}">
            <div class="campo">
                {{-- La etiqueta queda solo para lectores de pantalla; el placeholder ya indica qué escribir --}}
                <label for="nueva-lista" class="oculto-visual">Nombre de la lista</label>
                <input type="text" id="nueva-lista" name="name" required maxlength="100" placeholder="Ej: Mis {{ $tipoTexto }} preferidos">
                @error('name') <span class="error-campo">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn btn-secundario">Crear y guardar</button>
        </form>
    </div>
</div>
