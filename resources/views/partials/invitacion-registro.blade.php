{{-- Aviso para visitantes en las fichas: explica para qué sirve tener cuenta
     justo donde un usuario registrado vería "Guardar en favoritos" y "Mis listas".
     Recibe: $nombre (del personaje, episodio o ubicación) y $conListas (si la ficha admite listas). --}}
<div class="invitacion-registro">
    <p>
        <strong>¿Te interesa {{ $nombre }}?</strong>
        Con una cuenta podés guardarlo en tus favoritos{{ $conListas ? ' y organizarlo en tus propias listas' : '' }}.
    </p>
    <div class="acciones-ficha">
        <a href="{{ route('register') }}" class="btn">Registrarse</a>
        <a href="{{ route('login') }}" class="btn btn-secundario">Iniciar sesión</a>
    </div>
</div>
