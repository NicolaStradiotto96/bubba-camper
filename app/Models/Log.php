<?php

namespace App\Models;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Log extends Model
{
    const UPDATED_AT = null;

    protected $fillable = ['booking_id', 'type', 'message', 'context'];

    protected $casts = [
        'context' => 'array',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
