<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Http\Resources\Api\V1\ListingResource;
use App\Services\CategoryService;
use App\Services\ListingService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Request $request, string $slug, CategoryService $categoryService, ListingService $listingService)
    {
        $categoryModel = $categoryService->findActiveBySlug($slug);

        if (!$categoryModel) {
            abort(404);
        }

        $category = (new CategoryResource($categoryModel->load(['subCategories' => function ($q) {
            $q->where('is_active', true)->orderBy('sort_order')->orderBy('name');
        }])))->resolve($request);

        $request->merge(['category_slug' => $slug]);
        $paginator = $listingService->paginatePublic($request, (int) $request->query('per_page', 20));
        $listings = $paginator->through(fn ($model) => (new ListingResource($model))->resolve($request));

        $categories = CategoryResource::collection($categoryService->getActiveWithSubCategories())->resolve($request);

        return view('web.category', [
            'category' => $category,
            'categories' => $categories,
            'listings' => $listings,
        ]);
    }
}
