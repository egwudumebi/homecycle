<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\PaystackService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function summary(Request $request, CartService $cartService, PaystackService $paystackService)
    {
        $user = $cartService->resolveUser($request);
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $payload = $cartService->getCartPayload($request);

        return response()->json([
            'data' => [
                'cart' => $payload,
                'currency' => 'NGN',
                'paystack_public_key' => $paystackService->publicKey(),
                'customer' => [
                    'email' => $user->email,
                    'name' => $user->name,
                ],
            ],
        ]);
    }
}
