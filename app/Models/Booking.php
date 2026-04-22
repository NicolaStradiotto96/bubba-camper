<?php

namespace App\Models;

use App\Models\Camper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'customer_first_name',
        'customer_last_name',
        'customer_email',
        'customer_phone',
        'camper_id',
        'start_date',
        'end_date',
        'total_price',
        'status',
        'payment_status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function camper(): BelongsTo
    {
        return $this->belongsTo(Camper::class);
    }
}
