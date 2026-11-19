<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Character;

class CharacterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    $characters = Character::with(['originLocation', 'currentLocation'])
        ->orderBy('name', 'asc')
        ->paginate(20);

    return view('character.index', compact('characters'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $character = Character::with(['originLocation', 'currentLocation','episode'])->findOrFail($id);
        // dd($character->episode);
        return view('character.show', compact('character')); // O puedes apuntar a tu vista actual
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
