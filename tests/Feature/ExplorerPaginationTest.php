<?php

namespace Tests\Feature;

use App\Models\Character;
use App\Models\Episode;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExplorerPaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_episodes_index_is_paginated(): void
    {
        Episode::factory()->count(25)->create();

        $response = $this->get(route('episodes.index'));

        // Con 25 episodios y 20 por página tiene que haber una segunda página
        $response->assertOk();
        $this->assertCount(20, $response->viewData('episodes'));
        $response->assertSee('Mostrando 1 a 20 de 25 resultados');

        $this->get(route('episodes.index', ['page' => 2]))
            ->assertOk()
            ->assertViewHas('episodes', fn ($episodes) => $episodes->count() === 5);
    }

    public function test_locations_index_is_paginated(): void
    {
        Location::factory()->count(25)->create();

        $response = $this->get(route('locations.index'));

        $response->assertOk();
        $this->assertCount(20, $response->viewData('locations'));
        $response->assertSee('Mostrando 1 a 20 de 25 resultados');
    }

    public function test_character_pages_link_to_their_locations(): void
    {
        $character = Character::factory()->create();
        $originUrl = route('locations.show', $character->id_origin_location);
        $currentUrl = route('locations.show', $character->id_current_location);

        $this->get(route('characters.show', $character->id_character))
            ->assertOk()
            ->assertSee($originUrl)
            ->assertSee($currentUrl);

        $this->get(route('characters.index'))
            ->assertOk()
            ->assertSee($originUrl)
            ->assertSee($currentUrl);
    }

    public function test_location_show_paginates_its_characters(): void
    {
        $location = Location::factory()->create();
        Character::factory()->count(25)->create([
            'id_origin_location' => $location->id_location,
            'id_current_location' => $location->id_location,
        ]);

        $response = $this->get(route('locations.show', $location->id_location));

        $response->assertOk();
        $this->assertCount(20, $response->viewData('originCharacters'));
        $this->assertCount(20, $response->viewData('currentCharacters'));

        // Cada lista usa su propio parámetro de página
        $this->get(route('locations.show', ['id' => $location->id_location, 'origen_page' => 2]))
            ->assertViewHas('originCharacters', fn ($characters) => $characters->count() === 5)
            ->assertViewHas('currentCharacters', fn ($characters) => $characters->count() === 20);
    }
}
