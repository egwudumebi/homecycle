<?php

namespace App\Services;

use App\Domain\Orders\OrderStatus;
use App\Domain\Orders\PaymentStatus;
use App\Domain\Orders\TrackingStatusKey;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\OrderTrackingEvent;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function applyPaystackVerification(Payment $payment, array $paystackData, CheckoutService $checkoutService): Payment
    {
        return DB::transaction(function () use ($payment, $paystackData, $checkoutService) {
            /** @var Payment $locked */
            $locked = Payment::query()
                ->whereKey($payment->id)
                ->with(['order.user'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($locked->status === 'success') {
                return $locked;
            }

            $locked->provider_payload = $paystackData;
            $locked->verified_at = now();

            $status = (string) ($paystackData['status'] ?? '');
            $currency = (string) ($paystackData['currency'] ?? '');
            $amount = (int) ($paystackData['amount'] ?? 0);

            $isSuccess = $status === 'success'
                && $currency === 'NGN'
                && $amount === (int) $locked->amount_kobo;

            /** @var Order|null $order */
            $order = $locked->order ? Order::query()->whereKey($locked->order->id)->lockForUpdate()->first() : null;

            if ($isSuccess) {
                $locked->status = PaymentStatus::Success->value;
                $locked->save();

                if ($order && $order->status !== OrderStatus::Paid->value) {
                    $from = $order->status;
                    $order->status = OrderStatus::Paid->value;
                    $order->paid_at = now();
                    $order->failed_at = null;
                    $order->cancelled_at = null;
                    $order->save();

                    OrderStatusHistory::query()->create([
                        'order_id' => $order->id,
                        'from_status' => $from,
                        'to_status' => OrderStatus::Paid->value,
                        'note' => 'Payment verified',
                    ]);
                }

                if ($order) {
                    $lastTracking = OrderTrackingEvent::query()
                        ->where('order_id', $order->id)
                        ->orderByDesc('created_at')
                        ->value('status_key');

                    if ($lastTracking !== TrackingStatusKey::PaymentConfirmed->value) {
                        OrderTrackingEvent::query()->create([
                            'order_id' => $order->id,
                            'status_key' => TrackingStatusKey::PaymentConfirmed->value,
                            'title' => 'Payment confirmed',
                            'description' => 'Your payment was successful',
                            'metadata' => null,
                            'created_at' => now(),
                        ]);
                    }

                    $order->tracking_status = TrackingStatusKey::PaymentConfirmed->value;
                    $order->save();

                    $hasDelivery = trim((string) $order->delivery_name) !== ''
                        && trim((string) $order->delivery_phone) !== ''
                        && trim((string) $order->delivery_address) !== ''
                        && trim((string) $order->delivery_state) !== ''
                        && trim((string) $order->delivery_city) !== '';

                    if ($hasDelivery && $order->status === OrderStatus::Paid->value) {
                        $from = $order->status;
                        $order->status = OrderStatus::Processing->value;
                        $order->save();

                        OrderStatusHistory::query()->create([
                            'order_id' => $order->id,
                            'from_status' => $from,
                            'to_status' => OrderStatus::Processing->value,
                            'note' => 'Order processing started',
                        ]);

                        $lastTracking = OrderTrackingEvent::query()
                            ->where('order_id', $order->id)
                            ->orderByDesc('created_at')
                            ->value('status_key');

                        if ($lastTracking !== TrackingStatusKey::OrderProcessing->value) {
                            OrderTrackingEvent::query()->create([
                                'order_id' => $order->id,
                                'status_key' => TrackingStatusKey::OrderProcessing->value,
                                'title' => 'Order processing',
                                'description' => 'We are preparing your order',
                                'metadata' => null,
                                'created_at' => now(),
                            ]);
                        }

                        $order->tracking_status = TrackingStatusKey::OrderProcessing->value;
                        $order->save();
                    }
                }

                if ($order && $order->user) {
                    $checkoutService->clearCartForUser($order->user);
                }

                return $locked;
            }

            $locked->status = PaymentStatus::Failed->value;
            $locked->save();

            if ($order && $order->status === OrderStatus::Pending->value) {
                $from = $order->status;
                $order->status = OrderStatus::Failed->value;
                $order->failed_at = now();
                $order->save();

                OrderStatusHistory::query()->create([
                    'order_id' => $order->id,
                    'from_status' => $from,
                    'to_status' => OrderStatus::Failed->value,
                    'note' => 'Payment failed',
                ]);
            }

            return $locked;
        });
    }
}
