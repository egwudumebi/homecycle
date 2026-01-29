<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Http\Resources\Api\V1\ListingResource;
use App\Http\Resources\Api\V1\LocationStateResource;
use App\Services\CategoryService;
use App\Services\ListingService;
use App\Services\LocationService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request, CategoryService $categoryService, LocationService $locationService, ListingService $listingService)
    {
        $categories = CategoryResource::collection($categoryService->getActiveWithSubCategories())->resolve($request);
        $states = LocationStateResource::collection($locationService->getStates())->resolve($request);

        $paginator = $listingService->paginatePublic($request, (int) $request->query('per_page', 20));
        $listings = $paginator->through(fn ($model) => (new ListingResource($model))->resolve($request));

        return view('web.search', [
            'categories' => $categories,
            'states' => $states,
            'listings' => $listings,
            'filters' => $request->query(),
        ]);
    }
}
