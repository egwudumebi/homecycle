<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ListingDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $storeName = (string) config('app.store.name');
        $storePhone = (string) config('app.store.phone');
        $storeWhatsApp = (string) config('app.store.whatsapp_phone');

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'status' => $this->status,
            'is_featured' => (bool) $this->is_featured,
            'published_at' => optional($this->published_at)->toISOString(),
            'created_at' => optional($this->created_at)->toISOString(),

            'seller' => [
                'name' => $storeName,
                'phone' => $storePhone,
                'whatsapp_phone' => $storeWhatsApp,
            ],

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

            'images' => $this->whenLoaded('images', function () {
                return $this->images->map(fn ($img) => [
                    'id' => $img->id,
                    'url' => Storage::disk($img->disk)->url($img->path),
                    'sort_order' => $img->sort_order,
                ]);
            }, []),
        ];
    }
}
