<?php

namespace App\Services;

use App\Domain\Orders\OrderStatus;
use App\Models\Listing;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\ReviewImage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ReviewService
{
    public function checkEligibility(User $user, Listing $listing): array
    {
        $orderItem = OrderItem::query()
            ->where('listing_id', $listing->id)
            ->whereHas('order', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->where('status', OrderStatus::Delivered->value);
            })
            ->whereDoesntHave('review', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->orderByDesc('id')
            ->first();

        return [
            'can_review' => (bool) $orderItem,
            'order_item_id' => $orderItem ? (int) $orderItem->id : null,
        ];
    }

    /**
     * @param UploadedFile[] $images
     */
    public function createReview(User $user, Listing $listing, int $orderItemId, int $rating, ?string $title, string $body, array $images = []): Review
    {
        return DB::transaction(function () use ($user, $listing, $orderItemId, $rating, $title, $body, $images) {
            /** @var OrderItem $orderItem */
            $orderItem = OrderItem::query()->with(['order'])->lockForUpdate()->findOrFail($orderItemId);

            if ((int) $orderItem->listing_id !== (int) $listing->id) {
                throw ValidationException::withMessages([
                    'listing_id' => 'Listing does not match the purchased item.',
                ]);
            }

            if (!$orderItem->order || (int) $orderItem->order->user_id !== (int) $user->id) {
                throw ValidationException::withMessages([
                    'order_item_id' => 'You can only review items from your own orders.',
                ]);
            }

            if ((string) $orderItem->order->status !== OrderStatus::Delivered->value) {
                throw ValidationException::withMessages([
                    'order_item_id' => 'You can only review items from delivered orders.',
                ]);
            }

            $exists = Review::query()
                ->where('user_id', $user->id)
                ->where('order_item_id', $orderItem->id)
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'order_item_id' => 'You have already reviewed this item.',
                ]);
            }

            /** @var Listing $lockedListing */
            $lockedListing = Listing::query()->whereKey($listing->id)->lockForUpdate()->firstOrFail();

            $review = Review::query()->create([
                'user_id' => $user->id,
                'listing_id' => $lockedListing->id,
                'order_item_id' => $orderItem->id,
                'rating' => $rating,
                'title' => $title,
                'body' => $body,
                'status' => 'published',
            ]);

            foreach ($images as $img) {
                if (!$img instanceof UploadedFile) {
                    continue;
                }

                $path = Storage::disk('public')->putFile('reviews', $img);

                ReviewImage::query()->create([
                    'review_id' => $review->id,
                    'image_path' => $path,
                ]);
            }

            $this->recalculateListingAggregates($lockedListing);

            return $review->load(['user', 'images']);
        });
    }

    public function recalculateListingAggregates(Listing $listing): void
    {
        $stats = Review::query()
            ->where('listing_id', $listing->id)
            ->where('status', 'published')
            ->selectRaw('COUNT(*) as c, AVG(rating) as a')
            ->first();

        $count = (int) ($stats->c ?? 0);
        $avg = $count > 0 ? (float) ($stats->a ?? 0) : 0.0;

        $listing->reviews_count = $count;
        $listing->avg_rating = round($avg, 2);
        $listing->save();
    }
}
