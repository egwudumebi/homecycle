<?php

namespace App\Domain\Listings\Queries;

use App\Models\Listing;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ListingIndexQuery
{
    public function build(Request $request): Builder
    {
        $query = Listing::query()
            ->with([
                'images',
                'subCategory.category',
                'state',
                'city',
            ])
            ->where('status', 'active');

        if ($request->filled('q')) {
            $q = trim((string) $request->string('q'));
            $query->where(function (Builder $q1) use ($q) {
                $q1->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category_slug')) {
            $categorySlug = (string) $request->string('category_slug');
            $query->whereHas('subCategory.category', function (Builder $q1) use ($categorySlug) {
                $q1->where('slug', $categorySlug);
            });
        }

        if ($request->filled('sub_category_slug')) {
            $subCategorySlug = (string) $request->string('sub_category_slug');
            $query->whereHas('subCategory', function (Builder $q1) use ($subCategorySlug) {
                $q1->where('slug', $subCategorySlug);
            });
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        if ($request->filled('state_slug')) {
            $stateSlug = (string) $request->string('state_slug');
            $query->whereHas('state', function (Builder $q1) use ($stateSlug) {
                $q1->where('slug', $stateSlug);
            });
        }

        if ($request->filled('city_slug')) {
            $citySlug = (string) $request->string('city_slug');
            $query->whereHas('city', function (Builder $q1) use ($citySlug) {
                $q1->where('slug', $citySlug);
            });
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        $sort = (string) $request->string('sort', 'created_at');
        $direction = strtolower((string) $request->string('direction', 'desc'));
        $direction = in_array($direction, ['asc', 'desc'], true) ? $direction : 'desc';

        if (!in_array($sort, ['created_at', 'price'], true)) {
            $sort = 'created_at';
        }

        return $query->orderBy($sort, $direction);
    }
}
