<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Morceau;

class Style extends Model
{
    use HasFactory;

    public function morceaux(): BelongsToMany
    {
        return $this->belongsToMany(Morceau::class);
    }
}
