<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Style extends Model
{
    public function morceaux(): BelongsToMany
    {
        return $this->belongsToMany(Morceau::class);
    }
}
