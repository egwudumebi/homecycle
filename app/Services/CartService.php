<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CartService
{
    private const SESSION_KEY = 'hc_cart';

    public function resolveUser(Request $request): ?User
    {
        /** @var User|null $webUser */
        $webUser = $request->user();

        if ($webUser) {
            return $webUser;
        }

        /** @var User|null $sanctumUser */
        $sanctumUser = Auth::guard('sanctum')->user();

        return $sanctumUser;
    }

    public function mergeSessionIntoUserCart(Request $request, User $user): void
    {
        $sessionCart = $this->getSessionCart($request);

        if ($sessionCart === []) {
            return;
        }

        DB::transaction(function () use ($request, $user, $sessionCart) {
            $cart = $this->getOrCreateUserCart($user);

            foreach ($sessionCart as $listingId => $row) {
                $qty = (int) ($row['quantity'] ?? 0);
                if ($qty < 1) {
                    continue;
                }

                $listing = Listing::query()->whereKey((int) $listingId)->first();
                if (!$listing || $listing->status !== 'active') {
                    continue;
                }

                $item = CartItem::query()->firstOrNew([
                    'cart_id' => $cart->id,
                    'listing_id' => $listing->id,
                ]);

                $item->price_at_time = $listing->price;
                $item->quantity = (int) ($item->quantity ?? 0) + $qty;
                $item->save();
            }

            $request->session()->forget(self::SESSION_KEY);
        });
    }

    public function getCartPayload(Request $request): array
    {
        $user = $this->resolveUser($request);

        if ($user) {
            $this->mergeSessionIntoUserCart($request, $user);

            return $this->getDbCartPayload($user);
        }

        return $this->getSessionCartPayload($request);
    }

    public function addItem(Request $request, int $listingId, int $quantity = 1): array
    {
        $quantity = max(1, min(99, $quantity));

        $listing = Listing::query()->with(['images'])->whereKey($listingId)->first();
        if (!$listing || $listing->status !== 'active') {
            return [
                'error' => 'Listing not available',
                'status' => 422,
            ];
        }

        $user = $this->resolveUser($request);

        if ($user) {
            DB::transaction(function () use ($user, $listing, $quantity) {
                $cart = $this->getOrCreateUserCart($user);

                $item = CartItem::query()->firstOrNew([
                    'cart_id' => $cart->id,
                    'listing_id' => $listing->id,
                ]);

                $item->price_at_time = $listing->price;
                $item->quantity = (int) ($item->quantity ?? 0) + $quantity;
                $item->save();
            });

            return $this->getDbCartPayload($user);
        }

        $cart = $this->getSessionCart($request);
        $existing = $cart[$listingId]['quantity'] ?? 0;
        $cart[$listingId] = [
            'quantity' => (int) $existing + $quantity,
            'price_at_time' => (string) $listing->price,
        ];

        $request->session()->put(self::SESSION_KEY, $cart);

        return $this->getSessionCartPayload($request);
    }

    public function updateQuantity(Request $request, int|string $id, int $quantity): array
    {
        $quantity = max(0, min(99, $quantity));

        $user = $this->resolveUser($request);

        if ($user) {
            /** @var CartItem|null $item */
            $item = CartItem::query()
                ->whereKey((int) $id)
                ->whereHas('cart', fn ($q) => $q->where('user_id', $user->id))
                ->first();

            if (!$item) {
                return [
                    'error' => 'Item not found',
                    'status' => 404,
                ];
            }

            if ($quantity < 1) {
                $item->delete();
            } else {
                $item->quantity = $quantity;
                $item->save();
            }

            return $this->getDbCartPayload($user);
        }

        $listingId = (int) $id;
        $cart = $this->getSessionCart($request);

        if (!array_key_exists($listingId, $cart)) {
            return [
                'error' => 'Item not found',
                'status' => 404,
            ];
        }

        if ($quantity < 1) {
            unset($cart[$listingId]);
        } else {
            $cart[$listingId]['quantity'] = $quantity;
        }

        $request->session()->put(self::SESSION_KEY, $cart);

        return $this->getSessionCartPayload($request);
    }

    public function removeItem(Request $request, int|string $id): array
    {
        return $this->updateQuantity($request, $id, 0);
    }

    public function clear(Request $request): array
    {
        $user = $this->resolveUser($request);

        if ($user) {
            $cart = Cart::query()->where('user_id', $user->id)->first();
            if ($cart) {
                $cart->items()->delete();
            }

            return $this->getDbCartPayload($user);
        }

        $request->session()->forget(self::SESSION_KEY);

        return $this->getSessionCartPayload($request);
    }

    private function getOrCreateUserCart(User $user): Cart
    {
        /** @var Cart $cart */
        $cart = Cart::query()->firstOrCreate([
            'user_id' => $user->id,
        ]);

        return $cart;
    }

    private function getSessionCart(Request $request): array
    {
        $raw = $request->session()->get(self::SESSION_KEY, []);

        return is_array($raw) ? $raw : [];
    }

    private function getDbCartPayload(User $user): array
    {
        $cart = Cart::query()
            ->where('user_id', $user->id)
            ->with(['items.listing.images'])
            ->first();

        $items = [];
        $totalItems = 0;
        $subtotal = 0.0;

        if ($cart) {
            foreach ($cart->items as $item) {
                $listing = $item->listing;

                if (!$listing) {
                    continue;
                }

                $unit = (float) $item->price_at_time;
                $qty = (int) $item->quantity;
                $line = $unit * $qty;

                $items[] = [
                    'id' => $item->id,
                    'listing' => $this->listingPayload($listing),
                    'quantity' => $qty,
                    'price_at_time' => number_format($unit, 2, '.', ''),
                    'subtotal' => number_format($line, 2, '.', ''),
                ];

                $totalItems += $qty;
                $subtotal += $line;
            }
        }

        return $this->payload($items, $totalItems, $subtotal);
    }

    private function getSessionCartPayload(Request $request): array
    {
        $cart = $this->getSessionCart($request);
        $ids = array_map('intval', array_keys($cart));

        $listings = Listing::query()->with(['images'])
            ->whereIn('id', $ids)
            ->get()
            ->keyBy('id');

        $items = [];
        $totalItems = 0;
        $subtotal = 0.0;

        foreach ($cart as $listingId => $row) {
            $listing = $listings->get((int) $listingId);
            if (!$listing) {
                continue;
            }

            $qty = (int) ($row['quantity'] ?? 0);
            if ($qty < 1) {
                continue;
            }

            $unit = (float) ($row['price_at_time'] ?? $listing->price);
            $line = $unit * $qty;

            $items[] = [
                'id' => (int) $listingId,
                'listing' => $this->listingPayload($listing),
                'quantity' => $qty,
                'price_at_time' => number_format($unit, 2, '.', ''),
                'subtotal' => number_format($line, 2, '.', ''),
            ];

            $totalItems += $qty;
            $subtotal += $line;
        }

        return $this->payload($items, $totalItems, $subtotal);
    }

    private function listingPayload(Listing $listing): array
    {
        $img = $listing->relationLoaded('images') ? $listing->images->first() : null;

        return [
            'id' => $listing->id,
            'title' => $listing->title,
            'slug' => $listing->slug,
            'price' => (string) $listing->price,
            'status' => $listing->status,
            'image_url' => $img ? Storage::disk($img->disk)->url($img->path) : null,
        ];
    }

    private function payload(array $items, int $totalItems, float $subtotal): array
    {
        return [
            'items' => $items,
            'total_items' => $totalItems,
            'subtotal' => number_format($subtotal, 2, '.', ''),
            'total' => number_format($subtotal, 2, '.', ''),
        ];
    }
}
