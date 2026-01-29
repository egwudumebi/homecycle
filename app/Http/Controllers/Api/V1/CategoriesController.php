<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index(Request $request, CategoryService $categoryService)
    {
        $categories = $categoryService->getActiveWithSubCategories();

        return CategoryResource::collection($categories);
    }

    public function subCategories(string $categorySlug, CategoryService $categoryService)
    {
        $category = $categoryService->findBySlug($categorySlug);

        if (!$category) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $subCategories = $categoryService->getActiveSubCategories($category);

        return response()->json([
            'data' => $subCategories->map(fn ($sc) => [
                'id' => $sc->id,
                'category_id' => $sc->category_id,
                'name' => $sc->name,
                'slug' => $sc->slug,
            ]),
        ]);
    }
}
