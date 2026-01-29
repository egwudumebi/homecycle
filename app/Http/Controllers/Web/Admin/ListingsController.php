<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Http\Resources\Api\V1\ListingDetailResource;
use App\Http\Resources\Api\V1\ListingResource;
use App\Http\Resources\Api\V1\LocationStateResource;
use App\Services\AdminListingService;
use App\Services\CategoryService;
use App\Services\ListingService;
use App\Services\LocationService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ListingsController extends Controller
{
    public function index(Request $request, ListingService $listingService)
    {
        $paginator = $listingService->paginateAdmin($request, 20);
        $payload = ListingResource::collection($paginator)->response()->getData(true);

        return view('web.admin.listings.index_single', [
            'apiUser' => $request->session()->get('api_user'),
            'listings' => $payload['data'] ?? [],
            'meta' => $payload['meta'] ?? null,
        ]);
    }

    public function create(Request $request, CategoryService $categoryService, LocationService $locationService)
    {
        $categories = CategoryResource::collection($categoryService->getActiveWithSubCategories())->resolve($request);
        $states = LocationStateResource::collection($locationService->getStates())->resolve($request);

        return view('web.admin.listings.create_single', [
            'apiUser' => $request->session()->get('api_user'),
            'categories' => $categories,
            'states' => $states,
        ]);
    }

    public function store(Request $request, AdminListingService $adminListingService)
    {
        $data = $request->validate([
            'sub_category_id' => ['required', 'integer'],
            'state_id' => ['required', 'integer'],
            'city_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'status' => ['nullable', 'in:active,sold,hidden'],
            'is_featured' => ['nullable', 'boolean'],
            'images.*' => ['nullable', 'image', 'max:5120'],
        ]);

        $listing = $adminListingService->create($data);

        $this->uploadImagesIfAny($request, $adminListingService, $listing->id);

        return redirect()->route('admin.listings.edit', ['id' => $listing->id]);
    }

    public function edit(Request $request, int $id, ListingService $listingService, CategoryService $categoryService, LocationService $locationService)
    {
        $listing = $listingService->findById($id);

        if (!$listing) {
            abort(404);
        }

        $detail = (new ListingDetailResource($listing))->resolve($request);
        $categories = CategoryResource::collection($categoryService->getActiveWithSubCategories())->resolve($request);
        $states = LocationStateResource::collection($locationService->getStates())->resolve($request);

        return view('web.admin.listings.edit_single', [
            'apiUser' => $request->session()->get('api_user'),
            'listingId' => $id,
            'listing' => $detail,
            'categories' => $categories,
            'states' => $states,
        ]);
    }

    public function update(Request $request, int $id, ListingService $listingService, AdminListingService $adminListingService)
    {
        $listing = $listingService->findById($id);

        if (!$listing) {
            abort(404);
        }

        $data = $request->validate([
            'sub_category_id' => ['required', 'integer'],
            'state_id' => ['required', 'integer'],
            'city_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'status' => ['nullable', 'in:active,sold,hidden'],
            'is_featured' => ['nullable', 'boolean'],
            'images.*' => ['nullable', 'image', 'max:5120'],
        ]);

        $adminListingService->update($listing, $data);

        $this->uploadImagesIfAny($request, $adminListingService, $id);

        return redirect()->route('admin.listings.edit', ['id' => $id]);
    }

    public function updateStatus(Request $request, int $id, ListingService $listingService, AdminListingService $adminListingService)
    {
        $listing = $listingService->findById($id);

        if (!$listing) {
            abort(404);
        }

        $data = $request->validate([
            'status' => ['required', 'in:active,sold,hidden'],
        ]);

        $adminListingService->updateStatus($listing, $data['status']);

        return redirect()->route('admin.listings.edit', ['id' => $id]);
    }

    private function uploadImagesIfAny(Request $request, AdminListingService $adminListingService, int $listingId): void
    {
        $files = $request->file('images');

        if (!is_array($files) || $listingId <= 0) {
            return;
        }

        $pending = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $pending[] = $file;
            }
        }

        if ($pending === []) {
            return;
        }

        $listing = $adminListingService->updateStatus(
            \App\Models\Listing::query()->findOrFail($listingId),
            (string) (\App\Models\Listing::query()->findOrFail($listingId)->status)
        );

        $adminListingService->uploadImages($listing, $pending);
    }
}
