<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Domain\Listings\Actions\CreateListing;
use App\Domain\Listings\Actions\UpdateListing;
use App\Domain\Listings\Actions\UploadListingImages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\StoreListingRequest;
use App\Http\Requests\Api\V1\Admin\UpdateListingRequest;
use App\Http\Requests\Api\V1\Admin\UpdateListingStatusRequest;
use App\Http\Resources\Api\V1\ListingDetailResource;
use App\Models\Listing;
use Illuminate\Http\Request;

class ListingsController extends Controller
{
    public function store(StoreListingRequest $request, CreateListing $createListing)
    {
        $listing = $createListing->execute($request->validated());

        return (new ListingDetailResource($listing->load(['images', 'subCategory.category', 'state', 'city'])))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateListingRequest $request, Listing $listing, UpdateListing $updateListing)
    {
        $listing = $updateListing->execute($listing, $request->validated());

        return new ListingDetailResource($listing->load(['images', 'subCategory.category', 'state', 'city']));
    }

    public function destroy(Listing $listing)
    {
        $listing->delete();

        return response()->json(['message' => 'Deleted']);
    }

    public function uploadImages(Request $request, Listing $listing, UploadListingImages $uploadListingImages)
    {
        $request->validate([
            'images' => ['required', 'array', 'max:12'],
            'images.*' => ['file', 'image', 'max:5120'],
        ]);

        $uploadListingImages->execute($listing, $request->file('images'));

        return new ListingDetailResource($listing->load(['images', 'subCategory.category', 'state', 'city']));
    }

    public function updateStatus(UpdateListingStatusRequest $request, Listing $listing)
    {
        $listing->status = $request->validated()['status'];
        $listing->save();

        return new ListingDetailResource($listing->load(['images', 'subCategory.category', 'state', 'city']));
    }
}
