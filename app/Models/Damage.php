<?php

namespace App\Models;

use App\Models\DamagePhoto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Damage extends Model
{
    protected $fillable = ['booking_id', 'amount', 'description', 'status', 'receipt_path',];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(DamagePhoto::class);
    }
}
