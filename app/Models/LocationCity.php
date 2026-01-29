<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LocationCity extends Model
{
    protected $table = 'location_cities';

    protected $fillable = [
        'state_id',
        'name',
        'slug',
    ];

    public function state(): BelongsTo
    {
        return $this->belongsTo(LocationState::class, 'state_id');
    }

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class, 'city_id');
    }
}
