<?php

namespace Database\Factories;

use App\Models\Album;
use App\Models\Artiste;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Album>
 */
class AlbumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titre' => fake()->realText(30),
            'annee' => fake()->numberBetween(1950, 2026),
            'artiste_id' => Artiste::factory(),
        ];
    }
}
