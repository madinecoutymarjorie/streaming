<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Style;
use App\Models\Playlist;

class Morceau extends Model
{
    /** @use HasFactory<\Database\Factories\MorceauFactory> */
    use HasFactory;

    protected $table = 'morceaux';

    public function styles(): BelongsToMany
    {
        return $this->belongsToMany(Style::class);
    }

    public function playlists(): BelongsToMany
    {
        return $this->belongsToMany(Playlist::class);
    }
}
