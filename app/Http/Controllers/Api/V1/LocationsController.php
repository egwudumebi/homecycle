<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\LocationCityResource;
use App\Http\Resources\Api\V1\LocationStateResource;
use App\Services\LocationService;

class LocationsController extends Controller
{
    public function states(LocationService $locationService)
    {
        $states = $locationService->getStates();

        return LocationStateResource::collection($states);
    }

    public function cities(string $stateSlug, LocationService $locationService)
    {
        $state = $locationService->findStateBySlug($stateSlug);

        if (!$state) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $cities = $locationService->getCitiesByState($state);

        return LocationCityResource::collection($cities);
    }
}
