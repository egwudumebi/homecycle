<?php

namespace App\Http\Controllers;

use App\Services\SocialAuthService;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;

class GoogleAuthController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        $intent = $this->normalizeIntent($request);

        if ($intent !== null) {
            $request->session()->put('auth_intent', $intent);
        }

        $driver = Socialite::driver('google');

        if ($request->boolean('select_account')) {
            $driver = $driver->with([
                'prompt' => 'select_account',
            ]);
        }

        return $driver->redirect();
    }

    public function callback(Request $request, SocialAuthService $socialAuthService, CartService $cartService): Response
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            $intent = $request->session()->pull('auth_intent');
            $returnUrl = is_array($intent) ? ($intent['return_url'] ?? null) : null;

            return redirect()->to(is_string($returnUrl) && $returnUrl !== '' ? $returnUrl : route('web.home'));
        }

        try {
            $user = $socialAuthService->findOrCreateFromGoogle([
                'id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'User',
                'avatar' => $googleUser->getAvatar(),
            ]);
        } catch (\Throwable $e) {
            $intent = $request->session()->pull('auth_intent');
            $returnUrl = is_array($intent) ? ($intent['return_url'] ?? null) : null;

            return redirect()->to(is_string($returnUrl) && $returnUrl !== '' ? $returnUrl : route('web.home'));
        }

        Auth::login($user, true);

        $cartService->mergeSessionIntoUserCart($request, $user);

        $intent = $request->session()->pull('auth_intent');
        if (!is_array($intent)) {
            $intent = $this->normalizeIntent($request);
        }

        if (is_array($intent) && is_string($intent['return_url'] ?? null) && $intent['return_url'] !== '') {
            $url = $intent['return_url'];
            $query = Arr::except($intent, ['return_url']);
            $query['auth'] = 1;

            return redirect()->to($this->appendQuery($url, $query));
        }

        return redirect()->to(route('web.home'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->to(route('web.home'));
    }

    public function apiRedirect(Request $request): Response
    {
        $redirect = Socialite::driver('google')
            ->stateless()
            ->redirectUrl(url('/api/v1/auth/google/callback'))
            ->redirect();

        return response()->json([
            'data' => [
                'url' => $redirect->getTargetUrl(),
            ],
        ]);
    }

    public function apiCallback(Request $request, SocialAuthService $socialAuthService): Response
    {
        try {
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->redirectUrl(url('/api/v1/auth/google/callback'))
                ->user();
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Google authentication cancelled or failed',
            ], 422);
        }

        try {
            $user = $socialAuthService->findOrCreateFromGoogle([
                'id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'User',
                'avatar' => $googleUser->getAvatar(),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Unable to link Google account',
            ], 422);
        }

        $token = $socialAuthService->issueSanctumToken($user, 'api');

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
            ],
        ]);
    }

    private function normalizeIntent(Request $request): ?array
    {
        $intent = $request->query('intent');

        if (!is_string($intent) || $intent === '') {
            return null;
        }

        $listingId = $request->query('listing_id');
        $returnUrl = $request->query('return_url');

        return [
            'intent' => $intent,
            'listing_id' => is_string($listingId) ? $listingId : (is_numeric($listingId) ? (string) $listingId : null),
            'return_url' => is_string($returnUrl) && $returnUrl !== '' ? $returnUrl : url()->previous(),
        ];
    }

    private function appendQuery(string $url, array $query): string
    {
        $parts = parse_url($url);
        $base = $url;
        $existing = [];

        if (is_array($parts) && isset($parts['query'])) {
            parse_str((string) $parts['query'], $existing);
        }

        $merged = array_merge($existing, $query);

        $sep = str_contains($base, '?') ? '&' : '?';

        return $base.$sep.http_build_query($merged);
    }
}
