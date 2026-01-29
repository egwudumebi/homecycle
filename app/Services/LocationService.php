<?php

namespace App\Services;

use App\Models\LocationCity;
use App\Models\LocationState;
use Illuminate\Database\Eloquent\Collection;

class LocationService
{
    public function getStates(): Collection
    {
        return LocationState::query()->orderBy('name')->get();
    }

    public function findStateBySlug(string $slug): ?LocationState
    {
        return LocationState::query()->where('slug', $slug)->first();
    }

    public function getCitiesByState(LocationState $state): Collection
    {
        return LocationCity::query()
            ->where('state_id', $state->id)
            ->orderBy('name')
            ->get();
    }
}
