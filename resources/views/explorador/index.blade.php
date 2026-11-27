@extends('layout.layout')

@section('body-class', $cantidadPersonajes === 0 ? 'body-inicio' : 'body-personajes')

@section('contenido')
    @if ($cantidadPersonajes === 0)
        {{-- Pantalla de bienvenida cuando todavía no hay datos (igual que en el TP1) --}}
        <div class="intro">
            <p>Todavía no hay personajes cargados.</p>

            @if (auth()->user()?->is_admin)
                <p>Para empezar, importá los datos desde la API.</p>
                <form method="POST" action="{{ route('explorer.sync') }}" id="formImportar">
                    @csrf
                    <button type="submit" class="btn-sincronizar" id="btnImportar">
                        Importar personajes
                    </button>
                </form>
                <div class="carga-inicial oculto" id="cargaInicial">
                    <p>Importando personajes...</p>
                    <p id="mensajeCarga">Viajando por universos desconocidos...</p>
                    <div class="portal-carga"></div>
                </div>
            @else
                {{-- En el TP2 la importación la hace un administrador o se corre por consola --}}
                <p>Un administrador tiene que importar los datos, o se puede ejecutar <code>php artisan rick-morty:import</code>.</p>
            @endif

            @if (session('mensajeSincronizacion'))
                <p class="mensaje-sincronizacion" id="mensajeSincronizacion">{{ session('mensajeSincronizacion') }}</p>
            @endif
        </div>
    @else
        @section('sin-registro-en-encabezado', true)

        {{-- Franja de bienvenida (el logo ya está en el encabezado del sitio) --}}
        <section class="encabezado-explorador">
            <div class="animacion-info">
                <strong>Explorador interdimensional activo</strong>
                <span>Seleccioná un personaje para ver su información.</span>
                @guest
                    {{-- El explorador es público; la cuenta solo hace falta para listas y favoritos --}}
                    <span class="invitacion">¿Querés armar tus propias listas? <a href="{{ route('register') }}" class="btn btn-chico">Registrarse</a></span>
                @endguest
            </div>

            <div class="acciones-app">
                {{-- El botón de sincronizar solo lo ve un administrador --}}
                @if (auth()->user()?->is_admin)
                    <form method="POST" action="{{ route('explorer.sync') }}" class="form-sincronizar" id="formSincronizar">
                        @csrf
                        <input type="hidden" name="season" value="{{ $temporadaActual }}">
                        <button type="submit" class="btn-sincronizar">Sincronizar personajes</button>
                    </form>
                @endif
                @if (session('mensajeSincronizacion'))
                    <p class="mensaje-sincronizacion" id="mensajeSincronizacion">{{ session('mensajeSincronizacion') }}</p>
                @endif
            </div>
        </section>

        <div class="contenedor">
            {{-- Panel izquierdo: filtros y lista de personajes --}}
            <div class="panel-izquierdo">
                <h2 class="titulo-panel">Filtros</h2>
                <label class="etiqueta label-formulario" for="seasonSelect">Seleccionar Temporada:</label>
                <form method="GET" action="{{ route('home') }}" class="form-temporada">
                    {{-- Al cambiar la opción se envía el formulario solo --}}
                    <select id="seasonSelect" name="season" onchange="this.form.submit()">
                        @foreach ($temporadas as $codigo)
                            <option value="{{ $codigo }}" @selected($codigo === $temporadaActual)>
                                Temporada {{ (int) str_replace('S', '', $codigo) }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <label class="etiqueta label-formulario" for="buscadorPersonaje">Personaje:</label>
                <input type="text" id="buscadorPersonaje" placeholder="Buscar personaje...">

                <div class="lista-personajes-contenedor">
                    <ul class="lista-personajes">
                        @forelse ($personajes as $personaje)
                            <li>
                                {{-- La URL del JSON la arma Laravel con route(), así el JS no la escribe a mano --}}
                                <button type="button" class="btn-personaje"
                                        data-url="{{ route('explorer.character', ['id' => $personaje->id_character, 'season' => $temporadaActual]) }}">
                                    {{ $personaje->name }}
                                </button>
                            </li>
                        @empty
                            <li class="sin-resultados-item">Sin resultados</li>
                        @endforelse
                    </ul>
                </div>
                <p id="sinResultados" class="oculto sin-resultados-item">Sin resultados</p>
            </div>

            {{-- Panel derecho: empieza oculto y lo llena el JavaScript --}}
            <div class="panel-derecho oculto" id="panelDetalles">
                <div class="foto">
                    <img id="charImg" src="" alt="Foto del personaje">
                </div>
                <div class="info">
                    <h2 id="charName">-</h2>
                    <div class="dato"><span class="etiqueta">Estado</span><span id="charStatus" class="badge"></span></div>
                    <div class="dato"><span class="etiqueta">Especie</span><span id="charSpecies" class="valor"></span></div>
                    <div class="dato"><span class="etiqueta">Género</span><span id="charGender" class="valor"></span></div>
                    <div class="dato"><span class="etiqueta">Origen</span><span id="charOrigin" class="valor"></span></div>
                    <div class="dato"><span class="etiqueta">Última ubicación conocida</span><span id="charLocation" class="valor"></span></div>
                    <div class="dato"><span class="etiqueta">Tipo</span><span id="charType" class="valor"></span></div>
                    <div class="dato"><span class="etiqueta">Primer episodio</span><span id="charFirstEp" class="valor"></span></div>
                    <div class="dato"><span class="etiqueta">Último episodio en la temporada</span><span id="charLastEp" class="valor"></span></div>
                    <a href="#" id="charUrl" class="btn ver-ficha">Ver ficha completa →</a>
                </div>
            </div>
        </div>

        <div class="overlay-sincronizacion oculto" id="overlaySincronizacion">
            <div class="overlay-contenido">
                <p>Sincronizando personajes...</p>
                <p id="mensajeSincronizacionCarga">Actualizando dimensiones conocidas...</p>
                <div class="portal-carga portal-sincronizacion"></div>
            </div>
        </div>
    @endif

    {{-- Rick y Morty se muestran siempre en el inicio, haya o no datos importados. --}}
    @include('partials.mascotas')
@endsection

@push('scripts')
    <script src="{{ asset('js/explorador.js') }}"></script>
@endpush
