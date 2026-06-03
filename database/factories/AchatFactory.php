<?php

namespace Database\Factories;

use App\Models\Achat;
use App\Models\User;
use App\Models\Morceau;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Achat>
 */
class AchatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $morceau = Morceau::factory()->create();

        return [
            'utilisateur_id' => User::factory(),
            'morceau_id' => $morceau->id,
            'prix_paye' => $morceau->prix, 
            'date_achat' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
