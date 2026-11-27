<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rick & Morty Explorer</title>

    {{-- Vinculamos correctamente el archivo CSS externo desde la carpeta public --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<nav style="display: flex; justify-content: space-between; align-items: center; height: 65px; background-color: #1f2937; padding: 0 30px; color: white; font-family: sans-serif; box-shadow: 0 2px 4px rgba(0,0,0,0.1); box-sizing: border-box;">
    
    {{-- Izquierda: Logo y Secciones principales --}}
    <div style="display: flex; align-items: center; gap: 20px; height: 100%;">
        <a href="{{ url('/') }}" style="color: #63b3ed; text-decoration: none; font-weight: bold; font-size: 18px;">
            🧪 Rick & Morty
        </a>
        
        <div style="display: flex; align-items: center; gap: 15px; height: 100%;">
            <a href="{{ route('characters.index') }}" style="color: #e2e8f0; text-decoration: none; font-weight: 500;">Personajes</a>
            <a href="{{ route('locations.index') }}" style="color: #e2e8f0; text-decoration: none; font-weight: 500;">Locaciones</a>
            <a href="{{ route('episodes.index') }}" style="color: #e2e8f0; text-decoration: none; font-weight: 500;">Episodios</a>
            
           @auth
                <a href="{{ route('listas.index') }}" style="color: #68d391; text-decoration: none; font-weight: bold;">📋 Mis Listas</a>
                <a href="{{ route('favorites.index') }}" style="color: #f6ad55; text-decoration: none; font-weight: bold;">⭐ Favoritos</a>
            @endauth
        </div>
    </div>

    {{-- Derecha: Login / Logout dinámico --}}
    <div style="display: flex; align-items: center; gap: 12px; height: 100%;">
        @guest
            <a href="{{ route('login') }}" style="color: white; text-decoration: none; padding: 8px 16px; border: 1px solid #4a5568; border-radius: 4px; font-weight: 500; font-size: 14px; box-sizing: border-box; display: inline-flex; align-items: center;">
                Login
            </a>
            <a href="{{ route('register') }}" style="background-color: #3182ce; color: white; text-decoration: none; padding: 8px 16px; border-radius: 4px; font-weight: 500; font-size: 14px; box-sizing: border-box; display: inline-flex; align-items: center;">
                Registro
            </a>
        @else
            <span style="color: #cbd5e0; font-size: 14px;">
                Hola, 
                <a href="{{ route('profile.edit') }}" style="color: inherit; text-decoration: none;" title="Ver mi perfil">
        <strong>{{ Auth::user()?->name }}</strong>
    </a>
            </span>
            
            <form action="{{ route('logout') }}" method="POST" style="margin: 0; display: flex; align-items: center;">
                @csrf
                <button type="submit" style="background-color: #e53e3e; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-weight: bold; font-size: 14px; box-sizing: border-box;">
                    Logout
                </button>
            </form>
        @endguest
    </div>
</nav>
<main>
@yield('contenido')
</main>

</body>
</html>