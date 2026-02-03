<?php

namespace App\Services;

use App\Domain\Listings\Queries\ListingIndexQuery;
use App\Models\Listing;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ListingService
{
    public function __construct(private readonly ListingIndexQuery $listingIndexQuery)
    {
    }

    private function publicBaseQuery(): Builder
    {
        return Listing::query()
            ->with(['images', 'subCategory.category', 'state', 'city'])
            ->where('status', 'active');
    }

    public function getFeatured(int $limit = 12): Collection
    {
        return $this->publicBaseQuery()
            ->where('is_featured', true)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function getLatest(int $limit = 12): Collection
    {
        return $this->publicBaseQuery()
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function paginatePublic(Request $request, int $perPage = 20): LengthAwarePaginator
    {
        $perPage = max(1, min(50, $perPage));

        return $this->listingIndexQuery
            ->build($request)
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findPublicBySlug(string $slug): ?Listing
    {
        return $this->publicBaseQuery()->where('slug', $slug)->first();
    }

    public function paginateAdmin(Request $request, int $perPage = 20): LengthAwarePaginator
    {
        $perPage = max(1, min(50, $perPage));

        $query = Listing::query()->with(['images', 'subCategory.category', 'state', 'city']);

        if ($request->filled('category_id')) {
            $categoryId = (int) $request->input('category_id');
            if ($categoryId > 0) {
                $query->whereHas('subCategory', function (Builder $q1) use ($categoryId) {
                    $q1->where('category_id', $categoryId);
                });
            }
        }

        if ($request->filled('sub_category_id')) {
            $subCategoryId = (int) $request->input('sub_category_id');
            if ($subCategoryId > 0) {
                $query->where('sub_category_id', $subCategoryId);
            }
        }

        if ($request->filled('status')) {
            $status = strtolower(trim((string) $request->string('status')));
            if (in_array($status, ['active', 'sold', 'hidden'], true)) {
                $query->where('status', $status);
            }
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        if ($request->filled('q')) {
            $q = trim((string) $request->string('q'));
            $query->where(function (Builder $q1) use ($q) {
                $q1->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        return $query->orderByDesc('created_at')->paginate($perPage)->withQueryString();
    }

    public function findById(int $id): ?Listing
    {
        return Listing::query()->with(['images', 'subCategory.category', 'state', 'city'])->find($id);
    }
}
