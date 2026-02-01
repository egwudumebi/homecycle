<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ReviewResource;
use App\Models\Listing;
use App\Models\Review;
use Illuminate\Http\Request;

class ListingReviewsController extends Controller
{
    public function index(Request $request, Listing $listing)
    {
        $perPage = (int) $request->input('per_page', 10);
        $perPage = max(1, min(50, $perPage));

        $paginator = Review::query()
            ->where('listing_id', $listing->id)
            ->where('status', 'published')
            ->with(['user', 'images'])
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        $breakdown = Review::query()
            ->where('listing_id', $listing->id)
            ->where('status', 'published')
            ->selectRaw('rating, COUNT(*) as c')
            ->groupBy('rating')
            ->pluck('c', 'rating');

        return response()->json([
            'average_rating' => (float) $listing->avg_rating,
            'reviews_count' => (int) $listing->reviews_count,
            'rating_breakdown' => [
                5 => (int) ($breakdown[5] ?? 0),
                4 => (int) ($breakdown[4] ?? 0),
                3 => (int) ($breakdown[3] ?? 0),
                2 => (int) ($breakdown[2] ?? 0),
                1 => (int) ($breakdown[1] ?? 0),
            ],
            'reviews' => ReviewResource::collection($paginator),
        ]);
    }
}
