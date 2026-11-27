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

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('listas.index'))->assertRedirect(route('login'));
    }
}
