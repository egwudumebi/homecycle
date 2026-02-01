<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\CheckoutService;
use App\Services\PaymentService;
use App\Services\PaystackService;
use Illuminate\Http\Request;

class PaystackWebhookController extends Controller
{
    public function handle(Request $request, PaystackService $paystackService, PaymentService $paymentService, CheckoutService $checkoutService)
    {
        $raw = $request->getContent();
        $sig = $request->header('x-paystack-signature');

        if (!$paystackService->verifyWebhookSignature($raw, $sig)) {
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $reference = (string) data_get($request->all(), 'data.reference', '');
        if ($reference === '') {
            return response()->json(['ok' => true]);
        }

        /** @var Payment|null $payment */
        $payment = Payment::query()->where('reference', $reference)->first();
        if (!$payment) {
            return response()->json(['ok' => true]);
        }

        $verify = $paystackService->verify($reference);
        if (!($verify['ok'] ?? false)) {
            return response()->json(['ok' => true]);
        }

        $paymentService->applyPaystackVerification($payment, (array) ($verify['data'] ?? []), $checkoutService);

        return response()->json(['ok' => true]);
    }
}
