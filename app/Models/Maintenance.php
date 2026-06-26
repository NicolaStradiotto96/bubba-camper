<?php

namespace App\Models;

use App\Models\Camper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maintenance extends Model
{
    protected $fillable = [
        'camper_id',
        'start_date',
        'end_date',
        'reason'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function camper(): BelongsTo
    {
        return $this->belongsTo(Camper::class);
    }
}
