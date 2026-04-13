<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Camper extends Model
{
    protected $fillable = [
        "name",
        "description",
        "price_per_day",
        "image_path"
    ];
}
