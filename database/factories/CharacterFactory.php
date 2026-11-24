<?php

namespace Database\Factories;

use App\Models\Character;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Character>
 */
class CharacterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_character' => fake()->unique()->numberBetween(1, 100000),
            'name' => fake()->name(),
            'status' => fake()->randomElement(['Alive', 'Dead', 'unknown']),
            'species' => fake()->randomElement(['Human', 'Alien']),
            'type' => '',
            'gender' => fake()->randomElement(['Female', 'Male', 'Genderless', 'unknown']),
            'image' => 'https://example.com/character.jpg',
            'id_origin_location' => Location::factory(),
            'id_current_location' => Location::factory(),
        ];
    }
}
