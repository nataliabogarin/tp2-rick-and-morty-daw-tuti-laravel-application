<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Episode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

/**
 * Pantalla principal del explorador. Es la misma interfaz del index.php del TP1
 * (filtro por temporada, buscador, lista de personajes y panel de detalle),
 * pero separada en controlador, vista Blade y una ruta que devuelve JSON.
 */
class ExplorerController extends Controller
{
    public function index(Request $request)
    {
        $cantidadPersonajes = Character::count();

        // Sin datos mostramos la pantalla de bienvenida, igual que en el TP1
        if ($cantidadPersonajes === 0) {
            return view('explorador.index', ['cantidadPersonajes' => 0]);
        }

        // Temporadas disponibles a partir del código del episodio ("S01E01" -> "S01")
        $temporadas = Episode::query()
            ->selectRaw('DISTINCT SUBSTR(episode, 1, 3) AS temporada')
            ->orderBy('temporada')
            ->pluck('temporada');

        // Si la temporada pedida no existe usamos la primera
        $temporadaActual = $temporadas->contains($request->query('season'))
            ? $request->query('season')
            : $temporadas->first();

        // Personajes que aparecen en al menos un episodio de esa temporada
        $personajes = Character::query()
            ->whereHas('episode', fn ($query) => $query->where('episode', 'like', $temporadaActual.'%'))
            ->orderBy('name')
            ->get(['id_character', 'name']);

        return view('explorador.index', compact('cantidadPersonajes', 'temporadas', 'temporadaActual', 'personajes'));
    }

    /**
     * Reemplaza al "index.php?ajax_id=" del TP1: devuelve los datos de un personaje
     * en JSON para que el JavaScript llene el panel de detalle sin recargar la página.
     */
    public function character(Request $request, string $id): JsonResponse
    {
        $character = Character::with(['originLocation', 'currentLocation'])->findOrFail($id);
        $temporada = (string) $request->query('season', 'S01');

        $episodios = $character->episode()->orderBy('episode.id_episode')->get();
        $episodiosTemporada = $episodios->filter(fn ($episodio) => str_starts_with($episodio->episode, $temporada));

        $primero = $episodios->first();
        $ultimoEnTemporada = $episodiosTemporada->last();

        return response()->json([
            'name' => $character->name,
            'status' => $character->status,
            'species' => $character->species,
            'gender' => $character->gender,
            'type' => $character->type,
            'image' => $character->image,
            'origin_name' => $character->originLocation?->name,
            'location_name' => $character->currentLocation?->name,
            'first_episode' => $primero ? "{$primero->name} ({$primero->episode})" : 'Desconocido',
            'last_episode' => $ultimoEnTemporada ? "{$ultimoEnTemporada->name} ({$ultimoEnTemporada->episode})" : 'Desconocido',
            // A diferencia del TP1, desde el panel se puede ir a la ficha completa
            'url' => route('characters.show', $character->id_character),
        ]);
    }

    /**
     * Botón "Sincronizar personajes" del TP1. Reutiliza el comando de importación.
     * En el TP2 solo lo puede usar un administrador.
     */
    public function sync(Request $request)
    {
        $antes = Character::count();

        $resultado = Artisan::call('rick-morty:import');

        if ($resultado !== 0) {
            $mensaje = 'No se pudo completar la sincronización.';
        } else {
            $nuevos = Character::count() - $antes;
            $mensaje = $nuevos > 0
                ? "{$nuevos} personajes sincronizados al multiverso."
                : 'No hay personajes nuevos para sincronizar.';
        }

        return redirect()
            ->route('home', ['season' => $request->input('season')])
            ->with('mensajeSincronizacion', $mensaje);
    }
}
