<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Spot extends Model
{
    protected $fillable = [
        'city_id',
        'name',
        'description',
        'address',
        'category',
        'sub_category',
        'date_visited',
    ];

    public function city(): BelongsTo {
        return $this->belongsTo(City::class);
    }
}
