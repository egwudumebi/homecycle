<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $payment = $this->whenLoaded('payment');

        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'status' => $this->status,
            'subtotal' => $this->subtotal,
            'total' => $this->total,
            'currency' => $this->currency,
            'created_at' => optional($this->created_at)->toISOString(),
            'item_count' => (int) ($this->items_count ?? 0),
            'payment_status' => $payment ? ($payment->status ?? null) : null,
            'tracking_status' => $this->tracking_status,
        ];
    }
}
