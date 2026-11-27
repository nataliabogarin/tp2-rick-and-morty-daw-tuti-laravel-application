@extends('layout.layout')

@section('titulo', 'Administración de usuarios')

@section('contenido')
<div class="pagina">
    <div class="panel">
        <h1 class="titulo-pagina">Panel de administración</h1>
        <p class="subtitulo">Usuarios registrados</p>

        @if (session('error'))
            <div class="alerta alerta-error">{{ session('error') }}</div>
        @endif
        @if (session('status'))
            <div class="alerta alerta-exito">{{ session('status') }}</div>
        @endif

        <table class="tabla">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>@if($user->is_admin)<span class="etiqueta-tipo">Admin</span>@else Usuario @endif</td>
                        <td>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-secundario btn-chico">Editar</a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="form-en-linea" onsubmit="return confirm('¿Eliminar este usuario?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-peligro btn-chico">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $users->links() }}
    </div>
</div>
@endsection
