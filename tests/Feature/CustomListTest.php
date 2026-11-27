<?php

namespace Tests\Feature;

use App\Models\Character;
use App\Models\CustomList;
use App\Models\Episode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomListTest extends TestCase
{
    use RefreshDatabase;

    private function createList(User $user, string $type): CustomList
    {
        return $user->customLists()->create(['name' => 'Mi lista', 'type' => $type]);
    }

    public function test_user_can_add_a_character_to_a_character_list(): void
    {
        $user = User::factory()->create();
        $list = $this->createList($user, 'character');
        $character = Character::factory()->create();

        $this->actingAs($user)
            ->post(route('listas.items.add', $list), ['item_id' => $character->id_character])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('custom_list_items', [
            'custom_list_id' => $list->id,
            'item_id' => $character->id_character,
        ]);
    }

    public function test_an_item_cannot_be_repeated_in_the_same_list(): void
    {
        $user = User::factory()->create();
        $list = $this->createList($user, 'character');
        $character = Character::factory()->create();

        $this->actingAs($user)->post(route('listas.items.add', $list), ['item_id' => $character->id_character]);
        $this->actingAs($user)
            ->post(route('listas.items.add', $list), ['item_id' => $character->id_character])
            ->assertSessionHas('error');

        $this->assertDatabaseCount('custom_list_items', 1);
    }

    public function test_an_episode_cannot_be_added_to_a_character_list(): void
    {
        $user = User::factory()->create();
        $list = $this->createList($user, 'character');
        // Usamos un id que existe como episodio pero no como personaje
        $episode = Episode::factory()->create(['id_episode' => 999]);

        $this->actingAs($user)
            ->post(route('listas.items.add', $list), ['item_id' => $episode->id_episode])
            ->assertSessionHasErrors('item_id');

        $this->assertDatabaseCount('custom_list_items', 0);
    }

    public function test_a_nonexistent_item_cannot_be_added(): void
    {
        $user = User::factory()->create();
        $list = $this->createList($user, 'episode');

        $this->actingAs($user)
            ->post(route('listas.items.add', $list), ['item_id' => 123456])
            ->assertSessionHasErrors('item_id');

        $this->assertDatabaseCount('custom_list_items', 0);
    }

    public function test_user_cannot_see_or_modify_another_users_list(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $list = $this->createList($owner, 'character');
        $character = Character::factory()->create();

        $this->actingAs($intruder)->get(route('listas.show', $list))->assertForbidden();
        $this->actingAs($intruder)
            ->post(route('listas.items.add', $list), ['item_id' => $character->id_character])
            ->assertForbidden();
        $this->actingAs($intruder)->delete(route('listas.destroy', $list))->assertForbidden();

        $this->assertDatabaseHas('custom_lists', ['id' => $list->id]);
    }

    public function test_user_can_rename_their_list(): void
    {
        $user = User::factory()->create();
        $list = $this->createList($user, 'character');

        $this->actingAs($user)
            ->put(route('listas.update', $list), ['name' => 'Nuevo nombre'])
            ->assertSessionHasNoErrors();

        $this->assertSame('Nuevo nombre', $list->fresh()->name);
        // El tipo no cambia al renombrar
        $this->assertSame('character', $list->fresh()->type);
    }

    public function test_user_cannot_rename_another_users_list(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $list = $this->createList($owner, 'episode');

        $this->actingAs($intruder)
            ->put(route('listas.update', $list), ['name' => 'Hackeada'])
            ->assertForbidden();

        $this->assertSame('Mi lista', $list->fresh()->name);
    }

    public function test_character_page_shows_which_lists_already_contain_it(): void
    {
        $user = User::factory()->create();
        $character = Character::factory()->create();
        $withCharacter = $user->customLists()->create(['name' => 'Lista con Rick', 'type' => 'character']);
        $withCharacter->items()->create(['item_id' => $character->id_character]);
        $user->customLists()->create(['name' => 'Lista vacia', 'type' => 'character']);

        $this->actingAs($user)
            ->get(route('characters.show', $character->id_character))
            ->assertOk()
            ->assertSee('Guardado en')
            ->assertSee('Quitar de Lista con Rick')
            // En el desplegable de "Agregar" solo aparece la lista donde todavía no está
            ->assertSee('Lista vacia (0)')
            ->assertDontSee('Lista con Rick (1)');
    }

    public function test_user_can_create_a_list_and_add_the_item_in_one_step(): void
    {
        $user = User::factory()->create();
        $episode = Episode::factory()->create();

        $this->actingAs($user)
            ->from(route('episodes.show', $episode->id_episode))
            ->post(route('listas.store'), [
                'name' => 'Episodios para ver',
                'type' => 'episode',
                'item_id' => $episode->id_episode,
            ])
            ->assertRedirect(route('episodes.show', $episode->id_episode))
            ->assertSessionHas('success');

        $list = $user->customLists()->firstOrFail();
        $this->assertSame('Episodios para ver', $list->name);
        $this->assertDatabaseHas('custom_list_items', ['custom_list_id' => $list->id, 'item_id' => $episode->id_episode]);
    }

    public function test_user_can_remove_an_item_from_a_list(): void
    {
        $user = User::factory()->create();
        $list = $this->createList($user, 'character');
        $character = Character::factory()->create();
        $list->items()->create(['item_id' => $character->id_character]);

        $this->actingAs($user)
            ->delete(route('listas.items.remove', [$list, $character->id_character]))
            ->assertSessionHas('success', 'Quitado de "Mi lista".');

        $this->assertDatabaseCount('custom_list_items', 0);
    }

    public function test_favorite_button_changes_after_saving_and_does_not_duplicate(): void
    {
        $user = User::factory()->create();
        $episode = Episode::factory()->create();
        $ficha = route('episodes.show', $episode->id_episode);
        $guardar = route('favorites.store', ['type' => 'episode', 'id' => $episode->id_episode]);

        $this->actingAs($user)->get($ficha)->assertSee('Guardar en favoritos');

        $this->actingAs($user)->from($ficha)->post($guardar)->assertSessionHas('favorito', 'Guardado en favoritos.');
        $this->actingAs($user)->from($ficha)->post($guardar)->assertSessionHas('favorito', 'Ya estaba en tus favoritos.');
        $this->assertDatabaseCount('favorites', 1);

        // Ya guardado: muestra el estado y la opción de quitarlo, no el botón de guardar
        $this->actingAs($user)->get($ficha)
            ->assertSee('Está en tus favoritos')
            ->assertSee('Quitar de favoritos')
            ->assertDontSee('Guardar en favoritos');
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('listas.index'))->assertRedirect(route('login'));
    }
}
