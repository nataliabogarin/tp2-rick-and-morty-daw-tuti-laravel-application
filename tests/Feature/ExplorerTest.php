<?php

namespace Tests\Feature;

use App\Models\Character;
use App\Models\Episode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExplorerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Crea un personaje que aparece en el episodio indicado.
     */
    private function characterInEpisode(string $name, string $code): Character
    {
        $character = Character::factory()->create(['name' => $name]);
        $episode = Episode::factory()->create(['episode' => $code]);
        $character->episode()->attach($episode->id_episode);

        return $character;
    }

    public function test_home_shows_welcome_screen_when_there_is_no_data(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Todavía no hay personajes cargados.')
            ->assertSee('rick-container', false)
            ->assertSee('morty-container', false)
            ->assertDontSee('portal-decorativo', false)
            ->assertDontSee('mascota-cuerpo', false);
    }

    public function test_home_filters_characters_by_season(): void
    {
        $this->characterInEpisode('Rick de la temporada uno', 'S01E01');
        $this->characterInEpisode('Morty de la temporada dos', 'S02E01');

        // Sin parámetro se usa la primera temporada
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Temporada 1')
            ->assertSee('Temporada 2')
            ->assertSee('Rick de la temporada uno')
            ->assertDontSee('Morty de la temporada dos');

        $this->get(route('home', ['season' => 'S02']))
            ->assertSee('Morty de la temporada dos')
            ->assertDontSee('Rick de la temporada uno');
    }

    public function test_character_detail_endpoint_returns_json(): void
    {
        $character = $this->characterInEpisode('Rick', 'S01E01');

        $this->getJson(route('explorer.character', ['id' => $character->id_character, 'season' => 'S01']))
            ->assertOk()
            ->assertJson([
                'name' => 'Rick',
                'origin_name' => $character->originLocation->name,
                'url' => route('characters.show', $character->id_character),
            ])
            ->assertJsonPath('first_episode', fn ($value) => str_contains($value, 'S01E01'));
    }

    public function test_explorer_is_public_and_private_sections_require_login(): void
    {
        $character = $this->characterInEpisode('Rick', 'S01E01');

        // Un visitante sin cuenta puede usar todo el explorador (consigna 1.2 y 1.3)
        $this->get(route('home'))->assertOk()->assertSee('Rick')->assertSee('¿Querés armar tus propias listas?');
        $this->getJson(route('explorer.character', $character->id_character))->assertOk();
        $this->get(route('characters.show', $character->id_character))->assertOk();
        $this->get(route('episodes.index'))->assertOk();
        $this->get(route('locations.index'))->assertOk();

        // Lo personal sí pide iniciar sesión
        $this->get(route('listas.index'))->assertRedirect(route('login'));
        $this->get(route('favorites.index'))->assertRedirect(route('login'));
        $this->get(route('profile.edit'))->assertRedirect(route('login'));
    }

    public function test_guests_see_why_to_register_on_detail_pages(): void
    {
        $character = $this->characterInEpisode('Rick', 'S01E01');

        // El visitante ve la explicación y no los botones de favoritos/listas
        $this->get(route('characters.show', $character->id_character))
            ->assertOk()
            ->assertSee('¿Te interesa Rick?', false)
            ->assertSee('organizarlo en tus propias listas')
            ->assertDontSee('Guardar en favoritos');

        // "Registrarse" aparece una sola vez (en el aviso), no también en el encabezado
        $html = $this->get(route('characters.show', $character->id_character))->getContent();
        $this->assertSame(1, substr_count($html, '>Registrarse<'));

        // Un usuario registrado ve las acciones y no la invitación
        $this->actingAs(User::factory()->create())
            ->get(route('characters.show', $character->id_character))
            ->assertSee('Guardar en favoritos')
            ->assertDontSee('¿Te interesa Rick?', false);
    }

    public function test_only_admins_can_sync(): void
    {
        $this->post(route('explorer.sync'))->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())
            ->post(route('explorer.sync'))
            ->assertRedirect(route('profile.edit'));
    }
}
