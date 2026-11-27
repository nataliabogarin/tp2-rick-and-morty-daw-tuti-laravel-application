<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Episode;
use App\Models\Favorite;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    use AuthorizesRequests;

    /**
     * Muestra el listado de favoritos del usuario autenticado.
     */
    public function index()
    {

        /** @var User $user */
        $user = Auth::user();

        // Usamos la variable $user en lugar de llamar otra vez a Auth::user()
        $favorites = $user->favorites()->with('favorable')->get();

        return view('favorites.index', compact('favorites'));
    }

    /**
     * Almacena o alterna un elemento como favorito.
     */
    public function store(Request $request, string $type, int $id)
    {
        // 1. Mapear el string recibido por URL a su modelo correspondiente
        // 1. Mapear el string recibido por URL a su modelo correspondiente
        $modelMap = [
            'character' => Character::class,
            'location' => Location::class,
            'episode' => Episode::class,
        ];

        if (! array_key_exists($type, $modelMap)) {
            abort(404, 'Tipo de recurso no válido.');
        }

        $modelClass = $modelMap[$type];

        // Buscar el recurso
        $resource = $modelClass::findOrFail($id);

        /** @var User $user */
        $user = Auth::user();

        // 2. Crear o asegurar que existe el favorito usando la variable $user
        $favorito = $user->favorites()->firstOrCreate([
            'favorable_id' => $resource->getKey(),
            'favorable_type' => $modelClass,
        ]);

        // Usamos la clave "favorito" para que el mensaje se muestre al lado del botón en la ficha
        return back()->with('favorito', $favorito->wasRecentlyCreated
            ? 'Guardado en favoritos.'
            : 'Ya estaba en tus favoritos.');
    }

    /**
     * Elimina un favorito específico.
     */
    public function destroy(Request $request, Favorite $favorite)
    {
        // Aplicar la política de autorización (solo el dueño puede borrarlo)
        $this->authorize('delete', $favorite);

        $favorite->delete();

        // Desde la ficha mostramos el mensaje junto al botón; desde "Mis favoritos", arriba del listado
        $clave = $request->boolean('desde_ficha') ? 'favorito' : 'success';

        return back()->with($clave, 'Quitado de favoritos.');
    }
}
