<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = $request->password;
        }

        // Impide que el administrador se quite sus propios privilegios
        if (Auth::id() !== $user->id) {
            $user->is_admin = $request->boolean('is_admin');
        }

        $user->save();

        return redirect()->route('users.index')->with('status', '¡Usuario actualizado correctamente!');
    }

    public function destroy(User $user)
    {
        // Impide que el administrador elimine su propio usuario
        if (Auth::id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'No podés eliminar tu propio usuario.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('status', '¡Usuario eliminado correctamente!');
    }
}