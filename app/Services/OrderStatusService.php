<?php

namespace App\Services;

use App\Domain\Orders\OrderStatus;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Support\Facades\DB;

class OrderStatusService
{
    public function transition(Order $order, OrderStatus $to, ?string $note = null, array $meta = []): Order
    {
        return DB::transaction(function () use ($order, $to, $note, $meta) {
            /** @var Order $locked */
            $locked = Order::query()->whereKey($order->id)->lockForUpdate()->firstOrFail();

            $from = $locked->status;
            $toValue = $to->value;

            if ($from === $toValue) {
                return $locked;
            }

            $locked->status = $toValue;

            if ($to === OrderStatus::Paid) {
                $locked->paid_at = now();
                $locked->failed_at = null;
                $locked->cancelled_at = null;
            }

            if ($to === OrderStatus::Failed) {
                $locked->failed_at = now();
            }

            if ($to === OrderStatus::Cancelled) {
                $locked->cancelled_at = now();
            }

            $locked->save();

            OrderStatusHistory::query()->create([
                'order_id' => $locked->id,
                'from_status' => $from,
                'to_status' => $toValue,
                'note' => $note,
                'meta' => $meta === [] ? null : $meta,
            ]);

            return $locked;
        });
    }
}
