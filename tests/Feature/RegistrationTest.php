<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_is_logged_in(): void
    {
        $this->post('/register', [
            'name' => 'Morty Smith',
            'email' => 'morty@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect('/');

        $this->assertAuthenticated();
    }

    public function test_validation_messages_are_in_spanish(): void
    {
        User::factory()->create(['email' => 'rick@example.com']);

        // Forzamos el idioma por si el .env de quien corre los tests está en inglés
        app()->setLocale('es');

        $this->post('/register', [
            'name' => '',
            'email' => 'rick@example.com',
            'password' => 'corta',
            'password_confirmation' => 'otra',
        ])->assertSessionHasErrors([
            'name' => 'El campo nombre es obligatorio.',
            'email' => 'El correo electrónico ya está registrado.',
        ]);
    }
}
