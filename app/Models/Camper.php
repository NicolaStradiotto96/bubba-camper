<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Camper extends Model
{
    protected $fillable = [
        "name",
        "slug",
        "description",
        "price_per_day",
        "image_path", 
        'images'
    ];

    protected $casts = [
        'images' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($camper) {
            if (empty($camper->slug)) {
                $camper->slug = Str::slug($camper->name);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
