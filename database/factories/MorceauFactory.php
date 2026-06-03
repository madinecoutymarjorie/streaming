<?php

namespace Database\Factories;

use App\Models\Morceau;
use App\Models\Album;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Morceau>
 */
class MorceauFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $estGratuit = fake()->boolean(30); 
        $prix = $estGratuit ? 0.00 : fake()->randomFloat(2, 0.99, 2.99);

        return [
            'titre' => fake()->sentence(3),
            'duree' => fake()->numberBetween(120, 360),
            'prix' => $prix,
            'album_id' => Album::factory(),
        ];
    }
}
