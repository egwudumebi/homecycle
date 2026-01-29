<?php

namespace App\Domain\Listings\Actions;

use App\Models\Listing;
use App\Models\ListingImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UploadListingImages
{
    /**
     * @param array<int, UploadedFile> $images
     */
    public function execute(Listing $listing, array $images, string $disk = 'public'): Listing
    {
        $maxSort = (int) ($listing->images()->max('sort_order') ?? 0);

        foreach ($images as $file) {
            $maxSort++;

            $path = Storage::disk($disk)->putFile("listings/{$listing->id}", $file);

            ListingImage::create([
                'listing_id' => $listing->id,
                'disk' => $disk,
                'path' => $path,
                'sort_order' => $maxSort,
            ]);
        }

        return $listing->load('images');
    }
}
