<?php

namespace App\Services;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    public function getActiveWithSubCategories(): Collection
    {
        return Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->with(['subCategories' => function ($q) {
                $q->where('is_active', true)->orderBy('sort_order')->orderBy('name');
            }])
            ->get();
    }

    public function findBySlug(string $slug): ?Category
    {
        return Category::query()->where('slug', $slug)->first();
    }

    public function findActiveBySlug(string $slug): ?Category
    {
        return Category::query()->where('is_active', true)->where('slug', $slug)->first();
    }

    public function getActiveSubCategories(Category $category): Collection
    {
        return $category->subCategories()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }
}
