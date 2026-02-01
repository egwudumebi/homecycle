<?php

namespace App\Http\Resources\Api\V1;

use App\Domain\Orders\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class OrderDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $payment = $this->whenLoaded('payment');

        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'status' => $this->status,
            'tracking_status' => $this->tracking_status,
            'delivery' => [
                'delivery_name' => $this->delivery_name,
                'delivery_phone' => $this->delivery_phone,
                'delivery_address' => $this->delivery_address,
                'delivery_state' => $this->delivery_state,
                'delivery_city' => $this->delivery_city,
                'delivery_notes' => $this->delivery_notes,
            ],
            'currency' => $this->currency,
            'subtotal' => $this->subtotal,
            'total' => $this->total,
            'created_at' => optional($this->created_at)->toISOString(),
            'paid_at' => optional($this->paid_at)->toISOString(),
            'items' => $this->whenLoaded('items', function () {
                return $this->items->map(function ($it) {
                    $title = $it->title_snapshot ?: $it->title;
                    $unit = $it->price_snapshot ?: $it->unit_price;

                    $imageUrl = null;
                    if ($it->relationLoaded('listing') && $it->listing && $it->listing->relationLoaded('images')) {
                        $img = $it->listing->images->first();
                        if ($img) {
                            $imageUrl = URL::to(Storage::disk($img->disk)->url($img->path));
                        }
                    }

                    $reviewed = $it->relationLoaded('review') && $it->review;
                    $canReview = ((string) $this->status === OrderStatus::Delivered->value) && !$reviewed;

                    return [
                        'id' => $it->id,
                        'listing_id' => $it->listing_id,
                        'listing_slug' => ($it->relationLoaded('listing') && $it->listing) ? $it->listing->slug : null,
                        'title' => $title,
                        'image' => $imageUrl ? ['url' => $imageUrl] : null,
                        'unit_price' => $unit,
                        'quantity' => (int) $it->quantity,
                        'subtotal' => $it->subtotal,
                        'review' => [
                            'reviewed' => (bool) $reviewed,
                            'can_review' => (bool) $canReview,
                        ],
                    ];
                })->values();
            }),
            'payment' => $payment ? [
                'provider' => $payment->provider,
                'reference' => $payment->reference,
                'status' => $payment->status,
                'amount_kobo' => $payment->amount_kobo,
                'currency' => $payment->currency,
                'verified_at' => optional($payment->verified_at)->toISOString(),
            ] : null,
            'status_history' => $this->whenLoaded('statusHistory', function () {
                return $this->statusHistory->map(function ($h) {
                    return [
                        'id' => $h->id,
                        'from_status' => $h->from_status,
                        'to_status' => $h->to_status,
                        'note' => $h->note,
                        'created_at' => optional($h->created_at)->toISOString(),
                    ];
                })->values();
            }),
        ];
    }
}
