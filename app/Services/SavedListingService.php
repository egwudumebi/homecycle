<?php

namespace App\Services;

use App\Models\Listing;
use App\Models\SavedListing;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class SavedListingService
{
    public function getForUser(User $user): Collection
    {
        $savedListingIds = SavedListing::query()
            ->where('user_id', $user->id)
            ->latest()
            ->pluck('listing_id');

        return Listing::query()
            ->with(['images', 'subCategory.category', 'state', 'city'])
            ->whereIn('id', $savedListingIds)
            ->get();
    }

    public function save(User $user, int $listingId): bool
    {
        $listing = Listing::query()->where('id', $listingId)->first();

        if (!$listing) {
            return false;
        }

        SavedListing::query()->firstOrCreate([
            'user_id' => $user->id,
            'listing_id' => $listing->id,
        ]);

        return true;
    }

    public function remove(User $user, int $listingId): void
    {
        SavedListing::query()
            ->where('user_id', $user->id)
            ->where('listing_id', $listingId)
            ->delete();
    }
}
