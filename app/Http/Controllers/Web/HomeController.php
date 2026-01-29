<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Http\Resources\Api\V1\ListingResource;
use App\Services\CategoryService;
use App\Services\ListingService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request, ListingService $listingService, CategoryService $categoryService)
    {
        $featuredModels = $listingService->getFeatured(12);
        $latestModels = $listingService->getLatest(12);
        $categoryModels = $categoryService->getActiveWithSubCategories();

        $featured = ListingResource::collection($featuredModels)->resolve($request);
        $latest = ListingResource::collection($latestModels)->resolve($request);
        $categories = CategoryResource::collection($categoryModels)->resolve($request);

        return view('web.home', [
            'featured' => $featured,
            'latest' => $latest,
            'categories' => $categories,
        ]);
    }
}
