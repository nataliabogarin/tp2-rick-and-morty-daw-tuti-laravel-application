<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Explorador Interdimensional') - Rick and Morty</title>

    {{-- Mismos estilos que el TP1. El ?v= cambia cada vez que se modifica el archivo,
         así el navegador no se queda con una versión vieja en caché --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/rick-morty-animations.css') }}?v={{ filemtime(public_path('css/rick-morty-animations.css')) }}">
</head>
{{-- Cada vista puede definir la clase del body (en el TP1 eran body-inicio y body-personajes) --}}
<body class="@yield('body-class')">

{{-- Encabezado: logo centrado arriba y menú abajo, todo dentro del mismo panel --}}
<header class="encabezado-sitio">
    <a href="{{ route('home') }}" class="logo-enlace" title="Ir al explorador">
        <img src="{{ asset('images/logo_Rick_and_Morty.svg.webp') }}" alt="Rick and Morty" class="logo-encabezado">
    </a>

    <nav class="barra-navegacion">
        <div class="menu">
            <a href="{{ route('home') }}" @class(['activo' => request()->routeIs('home')])>Explorador</a>
            <a href="{{ route('characters.index') }}" @class(['activo' => request()->routeIs('characters.*')])>Personajes</a>
            <a href="{{ route('episodes.index') }}" @class(['activo' => request()->routeIs('episodes.*')])>Episodios</a>
            <a href="{{ route('locations.index') }}" @class(['activo' => request()->routeIs('locations.*')])>Ubicaciones</a>
            @auth
                <a href="{{ route('listas.index') }}" @class(['activo' => request()->routeIs('listas.*')])>Mis listas</a>
                <a href="{{ route('favorites.index') }}" @class(['activo' => request()->routeIs('favorites.*')])>Favoritos</a>
            @endauth
        </div>

        <div class="menu-usuario">
            @guest
                <a href="{{ route('login') }}" class="btn btn-secundario btn-chico">Iniciar sesión</a>
                {{-- En el explorador el botón "Registrarse" ya está en la franja de bienvenida, no lo repetimos --}}
                @unless(View::hasSection('sin-registro-en-encabezado'))
                    <a href="{{ route('register') }}" class="btn btn-chico">Registrarse</a>
                @endunless
            @else
                <a href="{{ route('profile.edit') }}" title="Ver mi perfil">Hola, <strong>{{ Auth::user()->name }}</strong></a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-secundario btn-chico">Cerrar sesión</button>
                </form>
            @endguest
        </div>
    </nav>
</header>

<main class="contenido-principal">
    @yield('contenido')
</main>

@stack('scripts')
</body>
</html>
