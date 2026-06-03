<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Playlist;
use App\Models\Morceau;

class PlaylistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Playlist::factory(20)->create()->each(function ($playlist) {
            $morceauIds = Morceau::inRandomOrder()->limit(rand(3, 8))->pluck('id');
            $playlist->morceaux()->attach($morceauIds);
        });
    }
}
