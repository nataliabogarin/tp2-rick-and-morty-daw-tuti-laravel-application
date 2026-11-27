<?php

namespace App\Http\Controllers;

use App\Models\Character;

class CharacterController extends Controller
{
    /**
     * Listado público de personajes, paginado de a 20.
     */
    public function index()
    {
        // Cargamos las ubicaciones junto con los personajes para no hacer una consulta por cada uno
        $characters = Character::with(['originLocation', 'currentLocation'])
            ->orderBy('name', 'asc')
            ->paginate(20);

        return view('character.index', compact('characters'));
    }

    /**
     * Detalle de un personaje con sus ubicaciones y episodios.
     */
    public function show(string $id)
    {
        $character = Character::with(['originLocation', 'currentLocation', 'episode'])->findOrFail($id);

        // Listas de personajes del usuario, marcando en cuáles ya está este personaje
        $listas = auth()->user()?->listsOfTypeWithItem('character', $character->id_character) ?? collect();

        return view('character.show', compact('character', 'listas'));
    }
}
