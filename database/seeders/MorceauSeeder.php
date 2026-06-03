<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Morceau;

class MorceauSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Morceau::factory(50)->create()->each(function ($morceau) {
            $styleIds = Style::inRandomOrder()->limit(rand(1, 3))->pluck('id');
            $morceau->styles()->attach($styleIds);
        });
    }
}
