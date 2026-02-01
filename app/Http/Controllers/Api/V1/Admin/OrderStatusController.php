<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Domain\Orders\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\UpdateOrderStatusRequest;
use App\Models\Order;
use App\Services\OrderTrackingService;
use InvalidArgumentException;

class OrderStatusController extends Controller
{
    public function update(UpdateOrderStatusRequest $request, Order $order, OrderTrackingService $orderTrackingService)
    {
        $data = $request->validated();

        try {
            $order = $orderTrackingService->transitionStatus($order, OrderStatus::from($data['status']));
        } catch (InvalidArgumentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json([
            'data' => [
                'order_id' => $order->id,
                'order_status' => $order->status,
                'tracking_status' => $order->tracking_status,
                'delivered_at' => optional($order->delivered_at)->toISOString(),
            ],
        ]);
    }
}
