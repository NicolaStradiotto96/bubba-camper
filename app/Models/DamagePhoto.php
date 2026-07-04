<?php

namespace App\Models;

use App\Models\Damage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DamagePhoto extends Model
{
    protected $fillable = ['damage_id', 'path'];

    public function damage(): BelongsTo
    {
        return $this->belongsTo(Damage::class);
    }
}
