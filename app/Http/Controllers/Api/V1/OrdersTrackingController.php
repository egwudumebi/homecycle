<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderTrackingService;
use Illuminate\Http\Request;

class OrdersTrackingController extends Controller
{
    public function show(Request $request, Order $order, OrderTrackingService $orderTrackingService)
    {
        $this->authorize('view', $order);

        $timeline = $orderTrackingService->getTimeline($order);

        $current = $order->tracking_status;
        if (!$current && !empty($timeline)) {
            $current = (string) ($timeline[count($timeline) - 1]['key'] ?? null);
        }

        return response()->json([
            'order_id' => $order->id,
            'order_status' => $order->status,
            'current_status' => $current,
            'delivered_at' => optional($order->delivered_at)->toISOString(),
            'timeline' => $timeline,
        ]);
    }
}
