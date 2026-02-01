<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ReviewResource;
use App\Models\Listing;
use App\Services\ReviewService;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    public function store(Request $request, ReviewService $reviewService)
    {
        $user = $request->user();

        $data = $request->validate([
            'order_item_id' => ['required', 'integer'],
            'listing_id' => ['required', 'integer'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:5000'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        /** @var Listing $listing */
        $listing = Listing::query()->findOrFail((int) $data['listing_id']);

        $review = $reviewService->createReview(
            $user,
            $listing,
            (int) $data['order_item_id'],
            (int) $data['rating'],
            isset($data['title']) ? (string) $data['title'] : null,
            (string) $data['body'],
            (array) $request->file('images', [])
        );

        return (new ReviewResource($review))
            ->response()
            ->setStatusCode(201);
    }
}
