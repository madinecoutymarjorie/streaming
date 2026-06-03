<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Morceau extends Model
{
    /** @use HasFactory<\Database\Factories\MorceauFactory> */
    use HasFactory;

    public function styles(): BelongsToMany
    {
        return $this->belongsToMany(Style::class);
    }

    public function playlists(): BelongsToMany
    {
        return $this->belongsToMany(Playlist::class);
    }
}
