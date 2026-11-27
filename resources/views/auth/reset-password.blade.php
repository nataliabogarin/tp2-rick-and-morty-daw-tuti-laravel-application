@extends('layout.layout')

@section('titulo', 'Nueva contraseña')

@section('contenido')
<div class="pagina pagina-angosta">
    <div class="panel">
        <h1 class="titulo-pagina">Nueva contraseña</h1>

        <form method="POST" action="{{ route('password.update') }}" class="formulario">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="campo">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" required>
                @error('email') <span class="error-campo">{{ $message }}</span> @enderror
            </div>
            <div class="campo">
                <label for="password">Nueva contraseña</label>
                <input type="password" id="password" name="password" required>
                @error('password') <span class="error-campo">{{ $message }}</span> @enderror
            </div>
            <div class="campo">
                <label for="password_confirmation">Confirmar contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn">Restablecer contraseña</button>
        </form>
    </div>
</div>
@endsection
