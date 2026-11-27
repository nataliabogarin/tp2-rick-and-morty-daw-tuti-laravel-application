@extends('layout.layout')

@section('titulo', 'Registrarse')

@section('contenido')
<div class="pagina pagina-angosta">
    <div class="panel">
        <h1 class="titulo-pagina">Crear cuenta</h1>

        @if($errors->any())
            <div class="alerta alerta-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="formulario">
            @csrf
            <div class="campo">
                <label for="name">Nombre</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
            </div>
            <div class="campo">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="campo">
                <label for="password">Contraseña (mínimo 8 caracteres)</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="campo">
                <label for="password_confirmation">Confirmar contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn">Registrarse</button>
        </form>

        <p class="enlaces-formulario">¿Ya tenés cuenta? <a href="{{ route('login') }}">Iniciá sesión</a></p>
    </div>
</div>
@endsection
