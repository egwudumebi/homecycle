<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ListingDetailResource;
use App\Http\Resources\Api\V1\ListingResource;
use App\Services\ListingService;
use Illuminate\Http\Request;

class ListingsController extends Controller
{
    public function index(Request $request, ListingService $listingService)
    {
        $perPage = (int) $request->input('per_page', 20);
        $paginator = $listingService->paginatePublic($request, $perPage);

        return ListingResource::collection($paginator);
    }

    public function show(Request $request, string $slug, ListingService $listingService)
    {
        $listing = $listingService->findPublicBySlug($slug);

        if (!$listing) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return new ListingDetailResource($listing);
    }
}
