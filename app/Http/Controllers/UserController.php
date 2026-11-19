<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        // Pasarlo correctamente a la vista
        return view('users.edit', compact('user'));
    }

   
  public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Evita que el administrador actual se quite su propio permiso por accidente
        if (Auth::id() !== $user->id) {
            $user->is_admin = $request->boolean('is_admin');
        }

        $user->save();

        return redirect()->route('users.index')->with('status', '¡Usuario actualizado por el administrador!');
    }
    
    public function destroy(User $user)
    {
        // Evitar que el admin se elimine a sí mismo por accidente
        if (Auth::id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('status', '¡Usuario eliminado con éxito!');
    }
}
