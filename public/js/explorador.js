// JavaScript del explorador. Es el mismo del index.php del TP1, pasado a un archivo
// aparte. Cada bloque revisa si su elemento existe porque la vista cambia según haya datos o no.

// Frases que van rotando mientras se importa o sincroniza
function rotarFrases(elemento, frases) {
    let numeroFrase = 0;
    setInterval(function () {
        numeroFrase++;
        elemento.textContent = frases[numeroFrase % frases.length];
    }, 1800);
}

// Pantalla inicial: botón "Importar personajes"
const formImportar = document.getElementById('formImportar');
if (formImportar) {
    formImportar.addEventListener('submit', function () {
        const btnImportar = document.getElementById('btnImportar');
        btnImportar.disabled = true;
        btnImportar.textContent = 'Importando...';
        document.getElementById('cargaInicial').classList.remove('oculto');

        rotarFrases(document.getElementById('mensajeCarga'), [
            'Viajando por universos desconocidos...',
            'Abriendo portal interdimensional...',
            'Buscando personajes en la Ciudadela...',
            'Cargando episodios desde otra dimensión...',
            'Sincronizando realidades alternativas...',
            'Consultando al Consejo de Ricks...',
            'Atravesando la curva finita central...',
            'Revisando archivos de Mortys perdidos...'
        ]);
    });
}

// Botón "Sincronizar personajes": mostramos el overlay con el portal
const formSincronizar = document.getElementById('formSincronizar');
if (formSincronizar) {
    formSincronizar.addEventListener('submit', function () {
        document.getElementById('overlaySincronizacion').classList.remove('oculto');

        rotarFrases(document.getElementById('mensajeSincronizacionCarga'), [
            'Actualizando dimensiones conocidas...',
            'Revisando cambios en la Ciudadela...',
            'Buscando nuevos registros interdimensionales...',
            'Ordenando episodios y locaciones...',
            'Conectando con la API del multiverso...'
        ]);
    });
}

// El mensaje de sincronización desaparece a los 10 segundos
const mensajeSincronizacion = document.getElementById('mensajeSincronizacion');
if (mensajeSincronizacion) {
    setTimeout(() => mensajeSincronizacion.classList.add('oculto'), 10000);
}

const listaPersonajes = document.querySelector('.lista-personajes');
if (listaPersonajes) {
    // Scroll automático de la lista al pasar el mouse
    let scrollPersonajes;
    listaPersonajes.addEventListener('mouseenter', function () {
        scrollPersonajes = setInterval(() => { listaPersonajes.scrollTop += 1; }, 35);
    });
    listaPersonajes.addEventListener('mouseleave', () => clearInterval(scrollPersonajes));

    // Buscador: filtra la lista ignorando mayúsculas y tildes
    const normalizar = texto => texto.toLowerCase().normalize('NFD').replace(/[̀-ͯ]/g, '').trim();
    const buscador = document.getElementById('buscadorPersonaje');
    const sinResultados = document.getElementById('sinResultados');

    buscador.addEventListener('input', function () {
        const textoBuscado = normalizar(buscador.value);
        let coincidencias = 0;

        document.querySelectorAll('.lista-personajes li').forEach(item => {
            const coincide = normalizar(item.textContent).includes(textoBuscado);
            item.style.display = coincide ? '' : 'none';
            if (coincide) coincidencias++;
        });

        sinResultados.style.display = coincidencias === 0 ? 'block' : 'none';
    });

    // Al tocar un personaje pedimos sus datos en JSON a Laravel y llenamos el panel derecho
    async function verDetallePersonaje(boton) {
        document.querySelectorAll('.btn-personaje').forEach(b => b.classList.remove('activo'));
        boton.classList.add('activo');

        const respuesta = await fetch(boton.dataset.url, { headers: { 'Accept': 'application/json' } });
        if (!respuesta.ok) return;
        const datos = await respuesta.json();

        document.getElementById('panelDetalles').style.display = 'flex';
        const imagen = document.getElementById('charImg');

        imagen.onerror = function () {
           this.onerror = null;
           this.src = this.dataset.fallback;
        };

        imagen.src = datos.image;
        document.getElementById('charName').textContent = datos.name;

        const estado = document.getElementById('charStatus');
        estado.textContent = datos.status;
        estado.className = `badge ${datos.status}`; // verde, rojo o gris según el estado

        document.getElementById('charSpecies').textContent = datos.species || '-';
        document.getElementById('charGender').textContent = datos.gender || '-';
        document.getElementById('charOrigin').textContent = datos.origin_name || 'Desconocido';
        document.getElementById('charLocation').textContent = datos.location_name || 'Desconocido';
        document.getElementById('charType').textContent = datos.type || 'Ninguno';
        document.getElementById('charFirstEp').textContent = datos.first_episode;
        document.getElementById('charLastEp').textContent = datos.last_episode;
        document.getElementById('charUrl').href = datos.url;
    }

    listaPersonajes.addEventListener('click', function (evento) {
        const boton = evento.target.closest('.btn-personaje');
        if (boton) verDetallePersonaje(boton);
    });

    // Al entrar se selecciona automáticamente el primer personaje
    const primerBoton = document.querySelector('.btn-personaje');
    if (primerBoton) verDetallePersonaje(primerBoton);
}
