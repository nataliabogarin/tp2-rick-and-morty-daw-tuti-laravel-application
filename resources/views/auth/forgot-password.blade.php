@extends('layout.layout')

@section('titulo', 'Recuperar contraseña')

@section('contenido')
<div class="pagina pagina-angosta">
    <div class="panel">
        <h1 class="titulo-pagina">Recuperar contraseña</h1>
        <p class="subtitulo">Ingresá tu correo y te enviamos un enlace para crear una nueva contraseña.</p>

        @if (session('status'))
            <div class="alerta alerta-exito">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="formulario">
            @csrf
            <div class="campo">
                <label for="email">Correo electrónico</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email') <span class="error-campo">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn">Enviar enlace de recuperación</button>
        </form>

        <p class="enlaces-formulario"><a href="{{ route('login') }}">← Volver a iniciar sesión</a></p>
    </div>
</div>
@endsection
