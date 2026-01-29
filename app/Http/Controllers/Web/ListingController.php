<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Http\Resources\Api\V1\ListingDetailResource;
use App\Services\CategoryService;
use App\Services\ListingService;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function show(Request $request, string $slug, ListingService $listingService, CategoryService $categoryService)
    {
        $listing = $listingService->findPublicBySlug($slug);

        if (!$listing) {
            abort(404);
        }

        $payload = (new ListingDetailResource($listing))->resolve($request);
        $categories = CategoryResource::collection($categoryService->getActiveWithSubCategories())->resolve($request);

        return view('web.listing', [
            'listing' => $payload,
            'categories' => $categories,
        ]);
    }
}
