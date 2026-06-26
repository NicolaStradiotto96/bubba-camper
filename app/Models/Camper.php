<?php

namespace App\Models;

use App\Models\Maintenance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Camper extends Model
{
    protected $fillable = [
        "name",
        "slug",
        "description",
        'prices',
        "image_path",
        'images',
        'attributes',
        'is_active'
    ];

    protected $casts = [
        'prices' => 'array',
        'images' => 'array',
        'attributes' => 'array',
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

    public function getPriceForDate($date = null)
    {
        $date = $date ? Carbon::parse($date) : now();
        $month = $date->month;

        if (in_array($month, [7, 8])) {
            return $this->prices['high'] ?? $this->prices['low'] ?? 0;
        }

        if (in_array($month, [4, 5, 6, 9, 10])) {
            return $this->prices['mid'] ?? $this->prices['low'] ?? 0;
        }

        return $this->prices['low'] ?? 0;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class);
    }
}
