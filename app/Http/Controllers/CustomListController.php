<?php

namespace App\Http\Controllers;

use App\Models\CustomList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomListController extends Controller
{
    // Muestra todas las listas del usuario autenticado
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $lists = $user->customLists()->withCount('items')->get();
        return view('listas.index', compact('lists'));
    }

    // Crea una nueva lista (de personajes o de episodios)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'in:character,episode'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        $user->customLists()->create($validated);

        return redirect()->route('listas.index')->with('success', 'Lista creada correctamente.');
    }

    // Muestra el contenido de una lista específica
    public function show(CustomList $customList)
    {
        abort_if($customList->user_id !== Auth::id(), 403, 'No tenés permiso para ver esta lista.');

        $customList->load('items');
        $elements = $customList->resolved_items;

        return view('listas.show', compact('customList', 'elements'));
    }

    // Elimina una lista completa del usuario
    public function destroy(CustomList $customList)
    {
        abort_if($customList->user_id !== Auth::id(), 403);

        $customList->delete();

        return redirect()->route('listas.index')->with('success', 'Lista eliminada.');
    }

    // Agrega un personaje o episodio a una lista (evitando duplicados)
    public function addItem(Request $request, CustomList $customList)
    {
        abort_if($customList->user_id !== Auth::id(), 403);

        // Valida la existencia del elemento según el tipo de lista
        $request->validate([
            'item_id' => [
                'required',
                'integer',
                $customList->type === 'character'
                    ? 'exists:characters,id_character'
                    : 'exists:episode,id_episode',
            ],
        ]);

        $item = $customList->items()->firstOrCreate([
            'item_id' => $request->item_id,
        ]);

        if (!$item->wasRecentlyCreated) {
            return back()->with('error', 'Ese elemento ya estaba agregado en esta lista.');
        }

        return back()->with('success', 'Elemento agregado a la lista.');
    }

    // Quita un personaje o episodio de una lista
    public function removeItem(CustomList $customList, int $itemId)
    {
        abort_if($customList->user_id !== Auth::id(), 403);

        $deleted = $customList->items()->where('item_id', $itemId)->delete();

        // Verifica que el elemento haya sido eliminado
        if ($deleted === 0) {
            return back()->with('error', 'El elemento no se encuentra en esta lista.');
        }

        return back()->with('success', 'Elemento quitado de la lista.');
    }
}