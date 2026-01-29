<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreSavedListingRequest;
use App\Http\Resources\Api\V1\ListingResource;
use App\Services\SavedListingService;
use Illuminate\Http\Request;

class SavedListingsController extends Controller
{
    public function index(Request $request, SavedListingService $savedListingService)
    {
        $user = $request->user();

        $listings = $savedListingService->getForUser($user);

        return ListingResource::collection($listings);
    }

    public function store(StoreSavedListingRequest $request, SavedListingService $savedListingService)
    {
        $user = $request->user();
        $data = $request->validated();

        $saved = $savedListingService->save($user, (int) $data['listing_id']);

        if (!$saved) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json(['message' => 'Saved'], 201);
    }

    public function destroy(Request $request, int $listingId, SavedListingService $savedListingService)
    {
        $user = $request->user();

        $savedListingService->remove($user, $listingId);

        return response()->json(['message' => 'Removed']);
    }
}
