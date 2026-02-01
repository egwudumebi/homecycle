<?php

namespace App\Services;

use App\Domain\Orders\OrderStatus;
use App\Domain\Orders\TrackingStatusKey;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\OrderTrackingEvent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutService
{
    public function createOrderFromCart(Request $request, User $user, CartService $cartService, array $delivery): Order
    {
        $payload = $cartService->getCartPayload($request);
        $items = (array) ($payload['items'] ?? []);
        $subtotal = (string) ($payload['subtotal'] ?? '0.00');

        return DB::transaction(function () use ($user, $items, $subtotal, $delivery) {
            $order = new Order();
            $order->user_id = $user->id;
            $order->order_number = $this->generateOrderNumber();
            $order->currency = 'NGN';
            $order->subtotal = $subtotal;
            $order->total = $subtotal;
            $order->status = OrderStatus::Pending->value;
            $order->tracking_status = TrackingStatusKey::OrderCreated->value;
            $order->delivery_name = (string) ($delivery['delivery_name'] ?? '');
            $order->delivery_phone = (string) ($delivery['delivery_phone'] ?? '');
            $order->delivery_address = (string) ($delivery['delivery_address'] ?? '');
            $order->delivery_state = (string) ($delivery['delivery_state'] ?? '');
            $order->delivery_city = (string) ($delivery['delivery_city'] ?? '');
            $order->delivery_notes = array_key_exists('delivery_notes', $delivery) ? ($delivery['delivery_notes'] !== null ? (string) $delivery['delivery_notes'] : null) : null;
            $order->failed_at = null;
            $order->cancelled_at = null;
            $order->save();

            OrderStatusHistory::query()->create([
                'order_id' => $order->id,
                'from_status' => null,
                'to_status' => OrderStatus::Pending->value,
                'note' => 'Order created',
            ]);

            OrderTrackingEvent::query()->create([
                'order_id' => $order->id,
                'status_key' => TrackingStatusKey::OrderCreated->value,
                'title' => 'Order created',
                'description' => null,
                'metadata' => null,
                'created_at' => now(),
            ]);

            foreach ($items as $row) {
                $listing = (array) ($row['listing'] ?? []);
                $qty = (int) ($row['quantity'] ?? 0);
                if ($qty < 1) {
                    continue;
                }

                $unit = (string) ($row['price_at_time'] ?? ($listing['price'] ?? '0.00'));
                $line = (string) ($row['subtotal'] ?? $this->mulMoney($unit, $qty));

                $title = (string) ($listing['title'] ?? 'Item');

                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'listing_id' => isset($listing['id']) ? (int) $listing['id'] : null,
                    'title_snapshot' => $title,
                    'price_snapshot' => $unit,
                    'title' => $title,
                    'quantity' => $qty,
                    'unit_price' => $unit,
                    'subtotal' => $line,
                ]);
            }

            return $order;
        });
    }

    public function clearCartForUser(User $user): void
    {
        DB::transaction(function () use ($user) {
            $cart = $user->cart;
            if ($cart) {
                $cart->items()->delete();
            }
        });
    }

    public function generatePaymentReference(Order $order): string
    {
        return 'HC-'.$order->id.'-'.Str::upper(Str::random(12));
    }

    private function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');

        return 'HC-'.$date.'-'.Str::upper(Str::random(8));
    }

    private function mulMoney(string $unit, int $qty): string
    {
        $n = (float) $unit;
        $line = $n * $qty;

        return number_format($line, 2, '.', '');
    }
}
