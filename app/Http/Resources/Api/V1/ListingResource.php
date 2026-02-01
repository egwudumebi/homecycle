<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class ListingResource extends JsonResource
{
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
                'url' => URL::to(Storage::disk($firstImage->disk)->url($firstImage->path)),
            ] : null,
        ];
    }
}
