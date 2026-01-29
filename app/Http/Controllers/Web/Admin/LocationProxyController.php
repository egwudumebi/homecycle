<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\LocationCityResource;
use App\Services\LocationService;
use Illuminate\Http\Request;

class LocationProxyController extends Controller
{
    public function cities(Request $request, string $stateSlug, LocationService $locationService)
    {
        $state = $locationService->findStateBySlug($stateSlug);

        if (!$state) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $cities = $locationService->getCitiesByState($state);

        return LocationCityResource::collection($cities);
    }
}
