@extends('layout.layout')

@section('titulo', 'Iniciar sesión')

@section('contenido')
<div class="pagina pagina-angosta">
    <div class="panel">
        <h1 class="titulo-pagina">Iniciar sesión</h1>

        @if (session('status'))
            <div class="alerta alerta-exito">{{ session('status') }}</div>
        @endif
        @if($errors->any())
            <div class="alerta alerta-error">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="formulario">
            @csrf
            <div class="campo">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Iniciar sesión</button>
        </form>

        <p class="enlaces-formulario">
            ¿No tenés cuenta? <a href="{{ route('register') }}">Registrate acá</a><br>
            <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
        </p>
    </div>
</div>
@endsection
