<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\PaymentService;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaystackController extends Controller
{
    public function initialize(
        Request $request,
        CartService $cartService,
        CheckoutService $checkoutService,
        PaystackService $paystackService
    ) {
        $user = $cartService->resolveUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $cart = $cartService->getCartPayload($request);
        if (empty($cart['items'])) {
            return response()->json(['message' => 'Cart is empty'], 422);
        }

        $delivery = $request->validate([
            'delivery_name' => ['required', 'string', 'max:255'],
            'delivery_phone' => ['required', 'string', 'max:32'],
            'delivery_address' => ['required', 'string', 'max:1000'],
            'delivery_state' => ['required', 'string', 'max:255'],
            'delivery_city' => ['required', 'string', 'max:255'],
            'delivery_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $order = $checkoutService->createOrderFromCart($request, $user, $cartService, $delivery);
        $reference = $checkoutService->generatePaymentReference($order);

        $amountKobo = (int) round(((float) $order->total) * 100);

        $payment = DB::transaction(function () use ($order, $reference, $amountKobo) {
            return Payment::query()->create([
                'order_id' => $order->id,
                'provider' => 'paystack',
                'reference' => $reference,
                'currency' => 'NGN',
                'amount_kobo' => $amountKobo,
                'status' => 'initialized',
            ]);
        });

        $init = $paystackService->initialize(
            $user->email,
            $amountKobo,
            $reference,
            route('web.checkout'),
            [
                'order_id' => $order->id,
                'payment_id' => $payment->id,
                'order_number' => $order->order_number,
            ]
        );

        if (!($init['ok'] ?? false)) {
            $payment->status = 'failed';
            $payment->provider_payload = $init['payload'] ?? null;
            $payment->verified_at = now();
            $payment->save();

            return response()->json(['message' => $init['message'] ?? 'Unable to initialize payment'], 422);
        }

        $data = (array) ($init['data'] ?? []);
        $payment->access_code = isset($data['access_code']) ? (string) $data['access_code'] : null;
        $payment->authorization_url = isset($data['authorization_url']) ? (string) $data['authorization_url'] : null;
        $payment->provider_payload = $data;
        $payment->save();

        return response()->json([
            'data' => [
                'order' => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => $order->status,
                    'total' => (string) $order->total,
                    'currency' => $order->currency,
                ],
                'payment' => [
                    'reference' => $payment->reference,
                    'access_code' => $payment->access_code,
                    'authorization_url' => $payment->authorization_url,
                    'amount_kobo' => $payment->amount_kobo,
                    'currency' => $payment->currency,
                ],
            ],
        ], 201);
    }

    public function verify(
        Request $request,
        CartService $cartService,
        PaystackService $paystackService,
        PaymentService $paymentService,
        CheckoutService $checkoutService
    ) {
        $user = $cartService->resolveUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $reference = (string) $request->input('reference', '');
        if ($reference === '') {
            return response()->json(['message' => 'Missing reference'], 422);
        }

        /** @var Payment|null $payment */
        $payment = Payment::query()
            ->where('reference', $reference)
            ->with(['order'])
            ->first();

        if (!$payment || !$payment->order || (int) $payment->order->user_id !== (int) $user->id) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $verify = $paystackService->verify($reference);
        if (!($verify['ok'] ?? false)) {
            return response()->json(['message' => $verify['message'] ?? 'Unable to verify payment'], 422);
        }

        $payment = $paymentService->applyPaystackVerification($payment, (array) ($verify['data'] ?? []), $checkoutService);

        return response()->json([
            'data' => [
                'order_id' => $payment->order_id,
                'payment_status' => $payment->status,
                'order_status' => $payment->order ? $payment->order->status : null,
            ],
        ]);
    }
}
