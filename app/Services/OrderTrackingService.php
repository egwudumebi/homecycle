<?php

namespace App\Services;

use App\Domain\Orders\OrderStatus;
use App\Domain\Orders\PaymentStatus;
use App\Domain\Orders\TrackingStatusKey;
use App\Models\Order;
use App\Models\OrderTrackingEvent;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class OrderTrackingService
{
    public function getTimeline(Order $order): array
    {
        $order->loadMissing(['trackingEvents']);

        $timeline = [];
        foreach ($order->trackingEvents as $event) {
            $timeline[] = [
                'key' => $event->status_key,
                'title' => $event->title,
                'description' => $event->description,
                'timestamp' => optional($event->created_at)->toISOString(),
            ];
        }

        return $timeline;
    }

    public function appendEvent(Order $order, TrackingStatusKey $key, string $title, ?string $description = null, array $metadata = []): Order
    {
        return DB::transaction(function () use ($order, $key, $title, $description, $metadata) {
            /** @var Order $locked */
            $locked = Order::query()
                ->whereKey($order->id)
                ->with(['payment', 'trackingEvents' => fn ($q) => $q->orderByDesc('created_at')->orderByDesc('id')->limit(1)])
                ->lockForUpdate()
                ->firstOrFail();

            $this->assertModifiable($locked);

            $lastKey = null;
            if ($locked->relationLoaded('trackingEvents') && $locked->trackingEvents->first()) {
                $lastKey = (string) $locked->trackingEvents->first()->status_key;
            }

            if ($lastKey === $key->value) {
                throw new InvalidArgumentException('Duplicate tracking event');
            }

            $this->assertTrackingKeyAllowed($locked, $key);

            OrderTrackingEvent::query()->create([
                'order_id' => $locked->id,
                'status_key' => $key->value,
                'title' => $title,
                'description' => $description,
                'metadata' => $metadata === [] ? null : $metadata,
                'created_at' => now(),
            ]);

            $locked->tracking_status = $key->value;

            $this->syncOrderStatusFromTrackingKey($locked, $key);

            $locked->save();

            return $locked;
        });
    }

    public function transitionStatus(Order $order, OrderStatus $to): Order
    {
        return DB::transaction(function () use ($order, $to) {
            /** @var Order $locked */
            $locked = Order::query()
                ->whereKey($order->id)
                ->with(['payment', 'trackingEvents' => fn ($q) => $q->orderByDesc('created_at')->orderByDesc('id')->limit(1)])
                ->lockForUpdate()
                ->firstOrFail();

            $this->assertModifiable($locked);

            $from = OrderStatus::from($locked->status);
            if ($from === $to) {
                return $locked;
            }

            $this->assertStatusTransitionAllowed($locked, $from, $to);

            $locked->status = $to->value;

            if ($to === OrderStatus::Delivered) {
                $locked->delivered_at = now();
            }

            if ($to === OrderStatus::Cancelled) {
                $locked->cancelled_at = now();
            }

            if ($to === OrderStatus::Paid) {
                $locked->paid_at = now();
            }

            $locked->save();

            $trackingKey = $this->trackingKeyForStatus($to);
            if ($trackingKey) {
                $lastKey = null;
                if ($locked->relationLoaded('trackingEvents') && $locked->trackingEvents->first()) {
                    $lastKey = (string) $locked->trackingEvents->first()->status_key;
                }

                if ($lastKey !== $trackingKey->value) {
                    $defaults = $this->defaultTrackingCopy($trackingKey);

                    OrderTrackingEvent::query()->create([
                        'order_id' => $locked->id,
                        'status_key' => $trackingKey->value,
                        'title' => $defaults['title'],
                        'description' => $defaults['description'],
                        'metadata' => null,
                        'created_at' => now(),
                    ]);
                }

                $locked->tracking_status = $trackingKey->value;
                $locked->save();
            }

            return $locked;
        });
    }

    private function assertModifiable(Order $order): void
    {
        if ($order->status === OrderStatus::Delivered->value) {
            throw new InvalidArgumentException('Delivered orders are non-modifiable');
        }

        if ($order->status === OrderStatus::Cancelled->value) {
            throw new InvalidArgumentException('Cancelled orders are non-modifiable');
        }
    }

    private function assertStatusTransitionAllowed(Order $order, OrderStatus $from, OrderStatus $to): void
    {
        $allowed = match ($from) {
            OrderStatus::Pending => [$to === OrderStatus::Paid],
            OrderStatus::Paid => [$to === OrderStatus::Processing],
            OrderStatus::Processing => [$to === OrderStatus::Shipped, $to === OrderStatus::Cancelled],
            OrderStatus::Shipped => [$to === OrderStatus::Delivered],
            default => [false],
        };

        if (!in_array(true, $allowed, true)) {
            throw new InvalidArgumentException('Invalid order status transition');
        }

        if ($to === OrderStatus::Paid) {
            if (!$order->payment || ($order->payment->status ?? null) !== PaymentStatus::Success->value) {
                throw new InvalidArgumentException('Order cannot be marked paid until payment is successful');
            }
        }

        if (in_array($to, [OrderStatus::Processing, OrderStatus::Shipped, OrderStatus::Delivered], true)) {
            $this->assertHasDeliveryDetails($order);
        }

        if ($to === OrderStatus::Delivered) {
            if (!$order->payment || ($order->payment->status ?? null) !== PaymentStatus::Success->value) {
                throw new InvalidArgumentException('Order cannot be delivered until payment is successful');
            }
            if ($from !== OrderStatus::Shipped) {
                throw new InvalidArgumentException('Order must be shipped before it can be delivered');
            }
        }
    }

    private function assertTrackingKeyAllowed(Order $order, TrackingStatusKey $key): void
    {
        $status = $order->status;

        if ($key === TrackingStatusKey::OrderCreated) {
            return;
        }

        if ($key === TrackingStatusKey::PaymentConfirmed) {
            if (!$order->payment || ($order->payment->status ?? null) !== PaymentStatus::Success->value) {
                throw new InvalidArgumentException('Payment must be successful before confirming payment');
            }
            return;
        }

        if ($key === TrackingStatusKey::OrderProcessing || $key === TrackingStatusKey::Packed) {
            $this->assertHasDeliveryDetails($order);
            if (!in_array($status, [OrderStatus::Paid->value, OrderStatus::Processing->value], true)) {
                throw new InvalidArgumentException('Order must be paid before processing');
            }
            return;
        }

        if (in_array($key, [TrackingStatusKey::Shipped, TrackingStatusKey::InTransit, TrackingStatusKey::OutForDelivery], true)) {
            $this->assertHasDeliveryDetails($order);
            if (!in_array($status, [OrderStatus::Processing->value, OrderStatus::Shipped->value], true)) {
                throw new InvalidArgumentException('Order must be processing/shipped before shipping updates');
            }
            return;
        }

        if ($key === TrackingStatusKey::Delivered) {
            $this->assertHasDeliveryDetails($order);
            if ($status !== OrderStatus::Shipped->value) {
                throw new InvalidArgumentException('Order must be shipped before it can be delivered');
            }
            if (!$order->payment || ($order->payment->status ?? null) !== PaymentStatus::Success->value) {
                throw new InvalidArgumentException('Order cannot be delivered until payment is successful');
            }
            return;
        }

        if ($key === TrackingStatusKey::Cancelled) {
            if ($status !== OrderStatus::Processing->value) {
                throw new InvalidArgumentException('Only processing orders can be cancelled');
            }
            return;
        }
    }

    private function assertHasDeliveryDetails(Order $order): void
    {
        if (
            trim((string) $order->delivery_name) === '' ||
            trim((string) $order->delivery_phone) === '' ||
            trim((string) $order->delivery_address) === '' ||
            trim((string) $order->delivery_state) === '' ||
            trim((string) $order->delivery_city) === ''
        ) {
            throw new InvalidArgumentException('Delivery details are required before shipping/tracking updates');
        }
    }

    private function syncOrderStatusFromTrackingKey(Order $order, TrackingStatusKey $key): void
    {
        if ($key === TrackingStatusKey::PaymentConfirmed) {
            if ($order->status === OrderStatus::Pending->value) {
                $this->assertStatusTransitionAllowed($order, OrderStatus::Pending, OrderStatus::Paid);
                $order->status = OrderStatus::Paid->value;
                $order->paid_at = now();
            }
            return;
        }

        if ($key === TrackingStatusKey::OrderProcessing) {
            if ($order->status === OrderStatus::Paid->value) {
                $this->assertStatusTransitionAllowed($order, OrderStatus::Paid, OrderStatus::Processing);
                $order->status = OrderStatus::Processing->value;
            }
            return;
        }

        if ($key === TrackingStatusKey::Shipped) {
            if ($order->status === OrderStatus::Processing->value) {
                $this->assertStatusTransitionAllowed($order, OrderStatus::Processing, OrderStatus::Shipped);
                $order->status = OrderStatus::Shipped->value;
            }
            return;
        }

        if ($key === TrackingStatusKey::Delivered) {
            if ($order->status === OrderStatus::Shipped->value) {
                $this->assertStatusTransitionAllowed($order, OrderStatus::Shipped, OrderStatus::Delivered);
                $order->status = OrderStatus::Delivered->value;
                $order->delivered_at = now();
            }
            return;
        }

        if ($key === TrackingStatusKey::Cancelled) {
            if ($order->status === OrderStatus::Processing->value) {
                $this->assertStatusTransitionAllowed($order, OrderStatus::Processing, OrderStatus::Cancelled);
                $order->status = OrderStatus::Cancelled->value;
                $order->cancelled_at = now();
            }
            return;
        }
    }

    private function trackingKeyForStatus(OrderStatus $status): ?TrackingStatusKey
    {
        return match ($status) {
            OrderStatus::Paid => TrackingStatusKey::PaymentConfirmed,
            OrderStatus::Processing => TrackingStatusKey::OrderProcessing,
            OrderStatus::Shipped => TrackingStatusKey::Shipped,
            OrderStatus::Delivered => TrackingStatusKey::Delivered,
            OrderStatus::Cancelled => TrackingStatusKey::Cancelled,
            default => null,
        };
    }

    private function defaultTrackingCopy(TrackingStatusKey $key): array
    {
        return match ($key) {
            TrackingStatusKey::OrderCreated => ['title' => 'Order created', 'description' => null],
            TrackingStatusKey::PaymentConfirmed => ['title' => 'Payment confirmed', 'description' => 'Your payment was successful'],
            TrackingStatusKey::OrderProcessing => ['title' => 'Order processing', 'description' => 'We are preparing your order'],
            TrackingStatusKey::Packed => ['title' => 'Packed', 'description' => 'Your order has been packed'],
            TrackingStatusKey::Shipped => ['title' => 'Shipped', 'description' => 'Your order has been shipped'],
            TrackingStatusKey::InTransit => ['title' => 'In transit', 'description' => 'Your order is on the way'],
            TrackingStatusKey::OutForDelivery => ['title' => 'Out for delivery', 'description' => 'Courier is delivering your order'],
            TrackingStatusKey::Delivered => ['title' => 'Delivered', 'description' => 'Order delivered successfully'],
            TrackingStatusKey::Cancelled => ['title' => 'Cancelled', 'description' => 'Order was cancelled'],
        };
    }
}
