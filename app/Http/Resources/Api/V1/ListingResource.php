<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class ListingResource extends JsonResource
{
    private function imageUrl(?string $disk, string $path): string
    {
        $disk = $disk ?: 'public';

        $raw = Storage::disk($disk)->url($path);

        if (is_string($raw) && filter_var($raw, FILTER_VALIDATE_URL)) {
            $parsed = parse_url($raw);
            $rebased = (string) ($parsed['path'] ?? '');

            if (isset($parsed['query']) && $parsed['query'] !== '') {
                $rebased .= '?'.$parsed['query'];
            }

            if (isset($parsed['fragment']) && $parsed['fragment'] !== '') {
                $rebased .= '#'.$parsed['fragment'];
            }

            return URL::to($rebased);
        }

        return URL::to($raw);
    }

    public function toArray(Request $request): array
    {
        $firstImage = $this->whenLoaded('images', function () {
            return $this->images->first();
        });

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'price' => $this->price,
            'avg_rating' => (float) ($this->avg_rating ?? 0),
            'reviews_count' => (int) ($this->reviews_count ?? 0),
            'status' => $this->status,
            'is_featured' => (bool) $this->is_featured,
            'created_at' => optional($this->created_at)->toISOString(),
            'location' => [
                'state' => $this->whenLoaded('state', fn () => [
                    'id' => $this->state->id,
                    'name' => $this->state->name,
                    'slug' => $this->state->slug,
                ]),
                'city' => $this->whenLoaded('city', fn () => [
                    'id' => $this->city->id,
                    'name' => $this->city->name,
                    'slug' => $this->city->slug,
                ]),
            ],
            'category' => $this->whenLoaded('subCategory', function () {
                return [
                    'sub_category' => [
                        'id' => $this->subCategory->id,
                        'name' => $this->subCategory->name,
                        'slug' => $this->subCategory->slug,
                    ],
                    'category' => $this->subCategory->relationLoaded('category') ? [
                        'id' => $this->subCategory->category->id,
                        'name' => $this->subCategory->category->name,
                        'slug' => $this->subCategory->category->slug,
                    ] : null,
                ];
            }),
            'image' => $firstImage ? [
                'url' => $this->imageUrl($firstImage->disk ?? null, (string) $firstImage->path),
            ] : null,
        ];
    }
}
