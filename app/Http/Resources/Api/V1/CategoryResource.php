<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'sub_categories' => $this->whenLoaded('subCategories', function () {
                return $this->subCategories->map(fn ($sc) => [
                    'id' => $sc->id,
                    'category_id' => $sc->category_id,
                    'name' => $sc->name,
                    'slug' => $sc->slug,
                ]);
            }),
        ];
    }
}
