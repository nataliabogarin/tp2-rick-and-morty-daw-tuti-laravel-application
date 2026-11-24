<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Solo crea el administrador si todavía no existe ninguno en la base de datos
        if (!User::where('is_admin', true)->exists()) {
            User::forceCreate([
                'name' => 'Administrador',
                'email' => 'admin@rickandmorty.test',
                'password' => Hash::make('admin1234'),
                'is_admin' => true,
            ]);
        }
    }
}