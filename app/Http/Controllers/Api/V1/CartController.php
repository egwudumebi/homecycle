<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreCartItemRequest;
use App\Http\Requests\Api\V1\UpdateCartItemRequest;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show(Request $request, CartService $cartService)
    {
        return response()->json($cartService->getCartPayload($request));
    }

    public function storeItem(StoreCartItemRequest $request, CartService $cartService)
    {
        $data = $request->validated();

        $result = $cartService->addItem(
            $request,
            (int) $data['listing_id'],
            (int) ($data['quantity'] ?? 1)
        );

        if (isset($result['error'])) {
            return response()->json(['message' => $result['error']], (int) ($result['status'] ?? 422));
        }

        return response()->json($result, 201);
    }

    public function updateItem(UpdateCartItemRequest $request, string $id, CartService $cartService)
    {
        $data = $request->validated();

        $result = $cartService->updateQuantity($request, $id, (int) $data['quantity']);

        if (isset($result['error'])) {
            return response()->json(['message' => $result['error']], (int) ($result['status'] ?? 422));
        }

        return response()->json($result);
    }

    public function destroyItem(Request $request, string $id, CartService $cartService)
    {
        $result = $cartService->removeItem($request, $id);

        if (isset($result['error'])) {
            return response()->json(['message' => $result['error']], (int) ($result['status'] ?? 422));
        }

        return response()->json($result);
    }

    public function clear(Request $request, CartService $cartService)
    {
        return response()->json($cartService->clear($request));
    }
}
