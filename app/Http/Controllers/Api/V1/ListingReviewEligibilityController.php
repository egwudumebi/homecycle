<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Services\ReviewService;
use Illuminate\Http\Request;

class ListingReviewEligibilityController extends Controller
{
    public function show(Request $request, Listing $listing, ReviewService $reviewService)
    {
        $user = $request->user();

        $out = $reviewService->checkEligibility($user, $listing);

        return response()->json($out);
    }
}
