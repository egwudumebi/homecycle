<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Domain\Orders\TrackingStatusKey;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\StoreOrderTrackingEventRequest;
use App\Models\Order;
use App\Services\OrderTrackingService;
use InvalidArgumentException;

class OrderTrackingController extends Controller
{
    public function store(StoreOrderTrackingEventRequest $request, Order $order, OrderTrackingService $orderTrackingService)
    {
        $data = $request->validated();

        try {
            $order = $orderTrackingService->appendEvent(
                $order,
                TrackingStatusKey::from($data['status_key']),
                (string) $data['title'],
                $data['description'] ?? null,
                (array) ($data['metadata'] ?? [])
            );
        } catch (InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'data' => [
                'order_id' => $order->id,
                'order_status' => $order->status,
                'tracking_status' => $order->tracking_status,
            ],
        ], 201);
    }
}
