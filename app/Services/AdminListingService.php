<?php

namespace App\Services;

use App\Domain\Listings\Actions\CreateListing;
use App\Domain\Listings\Actions\UpdateListing;
use App\Domain\Listings\Actions\UploadListingImages;
use App\Models\Listing;
use Illuminate\Http\UploadedFile;

class AdminListingService
{
    public function __construct(
        private readonly CreateListing $createListing,
        private readonly UpdateListing $updateListing,
        private readonly UploadListingImages $uploadListingImages,
    ) {
    }

    public function create(array $data): Listing
    {
        return $this->createListing->execute($data);
    }

    public function update(Listing $listing, array $data): Listing
    {
        return $this->updateListing->execute($listing, $data);
    }

    public function delete(Listing $listing): void
    {
        $listing->delete();
    }

    /**
     * @param array<int, UploadedFile> $files
     */
    public function uploadImages(Listing $listing, array $files): void
    {
        $this->uploadListingImages->execute($listing, $files);
    }

    public function updateStatus(Listing $listing, string $status): Listing
    {
        $listing->status = $status;
        $listing->save();

        return $listing;
    }
}
