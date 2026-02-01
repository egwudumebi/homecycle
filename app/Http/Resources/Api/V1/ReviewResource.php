<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rating' => (int) $this->rating,
            'title' => $this->title,
            'body' => $this->body,
            'status' => $this->status,
            'verified_purchase' => true,
            'created_at' => optional($this->created_at)->toISOString(),
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'avatar' => $this->user->avatar,
            ]),
            'images' => $this->whenLoaded('images', function () {
                return $this->images->map(function ($img) {
                    $url = Storage::disk('public')->url($img->image_path);
                    return [
                        'id' => $img->id,
                        'url' => URL::to($url),
                    ];
                })->values();
            }, []),
        ];
    }
}
