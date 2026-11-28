@extends('layout.layout')

@section('titulo', 'Editar usuario')

@section('contenido')
<div class="pagina pagina-angosta">
    <a href="{{ route('users.index') }}" class="btn btn-secundario btn-chico volver">← Volver al listado</a>

    <div class="panel">
        <h1 class="titulo-pagina">Editar usuario</h1>
        <p class="subtitulo">{{ $user->name }}</p>

        <form method="POST" action="{{ route('users.update', $user->id) }}" class="formulario">
            @csrf
            @method('PUT')

            <div class="campo">
                <label for="name">Nombre</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required maxlength="255">
                @error('name') <span class="error-campo">{{ $message }}</span> @enderror
            </div>

            <div class="campo">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required maxlength="255">
                @error('email') <span class="error-campo">{{ $message }}</span> @enderror
            </div>

            <div class="campo">
                <label for="password">Nueva contraseña (mínimo 8 caracteres, opcional)</label>
                <input type="password" id="password" name="password" minlength="8">
                @error('password') <span class="error-campo">{{ $message }}</span> @enderror
            </div>

            <div class="campo">
                <label for="password_confirmation">Confirmar contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation" minlength="8">
            </div>

            {{-- El admin no puede quitarse su propio permiso --}}
            <div class="campo check">
                @if(auth()->id() !== $user->id)
                    <input type="hidden" name="is_admin" value="0">
                @endif

                <input type="checkbox" name="is_admin" id="is_admin" value="1"
                    @checked(old('is_admin', $user->is_admin))
                    @disabled(auth()->id() === $user->id)>
                <label for="is_admin">Permisos de administrador</label>
            </div>

            <button type="submit" class="btn">Actualizar usuario</button>
        </form>
    </div>
</div>
@endsection
