<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    public function morceaux(): BelongsToMany
    {
        return $this->belongsToMany(Morceau::class);
    }
}
