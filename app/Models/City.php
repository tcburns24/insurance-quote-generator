<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $fillable = [
        'name',
        'country',
        'population'
    ];

    public function spots(): HasMany {
        return $this->hasMany(Spot::class);
    }
}
