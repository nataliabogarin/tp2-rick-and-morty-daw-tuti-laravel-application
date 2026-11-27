@extends('layout.layout')

@section('titulo', 'Mi perfil')

@section('contenido')
<div class="pagina pagina-angosta">
    <div class="panel">
        <h1 class="titulo-pagina">Mi perfil</h1>

        @if(auth()->user()->is_admin)
            <p><a href="{{ route('users.index') }}" class="btn btn-secundario btn-chico">Panel de administración</a></p>
        @endif

        {{-- Mensaje que deja el AdminMiddleware cuando alguien sin permisos entra al panel --}}
        @if (session('error'))
            <div class="alerta alerta-error">{{ session('error') }}</div>
        @endif
        @if (session('status'))
            <div class="alerta alerta-exito">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="formulario">
            @csrf
            @method('PUT')

            <div class="campo">
                <label for="name">Nombre</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name') <span class="error-campo">{{ $message }}</span> @enderror
            </div>

            <div class="campo">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email') <span class="error-campo">{{ $message }}</span> @enderror
            </div>

            <div class="campo">
                <label for="password">Nueva contraseña (dejar en blanco para no cambiarla)</label>
                <input type="password" id="password" name="password">
                @error('password') <span class="error-campo">{{ $message }}</span> @enderror
            </div>

            <div class="campo">
                <label for="password_confirmation">Confirmar nueva contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation">
            </div>

            <button type="submit" class="btn">Guardar cambios</button>
        </form>
    </div>
</div>
@endsection
