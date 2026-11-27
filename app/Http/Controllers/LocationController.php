<?php

namespace App\Http\Controllers;

use App\Models\Location;

class LocationController extends Controller
{
    /**
     * Listado público de ubicaciones, paginado de a 20.
     */
    public function index()
    {
        $locations = Location::orderBy('name')->paginate(20);

        return view('location.index', compact('locations'));
    }

    /**
     * Detalle de una ubicación con los personajes relacionados.
     */
    public function show(string $id)
    {
        $location = Location::findOrFail($id);

        // Algunas ubicaciones (como la Tierra) tienen cientos de personajes, por eso
        // paginamos las dos listas. Cada una usa su propio parámetro en la URL
        // para poder avanzar de página en una sin mover la otra.
        $originCharacters = $location->originCharacters()
            ->orderBy('name')
            ->paginate(20, ['*'], 'origen_page');

        $currentCharacters = $location->currentCharacters()
            ->orderBy('name')
            ->paginate(20, ['*'], 'actuales_page');

        return view('location.show', compact('location', 'originCharacters', 'currentCharacters'));
    }
}
