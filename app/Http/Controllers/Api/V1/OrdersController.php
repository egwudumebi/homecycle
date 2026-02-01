<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\OrderDetailResource;
use App\Http\Resources\Api\V1\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $perPage = (int) $request->input('per_page', 10);
        $perPage = max(1, min(50, $perPage));

        $paginator = Order::query()
            ->where('user_id', $user->id)
            ->withCount('items')
            ->with(['payment'])
            ->orderByDesc('id')
            ->paginate($perPage);

        return OrderResource::collection($paginator);
    }

    public function show(Request $request, Order $order)
    {
        $this->authorize('view', $order);

        $order->load([
            'items.listing.images',
            'items.review',
            'payment',
            'statusHistory',
        ]);

        return new OrderDetailResource($order);
    }
}
