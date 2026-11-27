<?php

namespace App\Http\Controllers;

use App\Models\Episode;

class EpisodeController extends Controller
{
    /**
     * Listado público de episodios, paginado de a 20.
     */
    public function index()
    {
        // Ordenamos por id para que los episodios salgan en orden de emisión
        // y paginamos de a 20 para no cargar todo en una sola pantalla
        $episodes = Episode::orderBy('id_episode')->paginate(20);

        return view('episode.index', compact('episodes'));
    }

    /**
     * Detalle de un episodio con los personajes que aparecen.
     */
    public function show(string $id)
    {
        $episode = Episode::with('characters')->findOrFail($id);

        return view('episode.show', compact('episode'));
    }
}
