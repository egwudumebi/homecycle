<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LocationState extends Model
{
    protected $table = 'location_states';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function cities(): HasMany
    {
        return $this->hasMany(LocationCity::class, 'state_id');
    }
}
