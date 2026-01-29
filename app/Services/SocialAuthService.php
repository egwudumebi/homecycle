<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SocialAuthService
{
    public function findOrCreateFromGoogle(array $googleUser): User
    {
        $googleId = (string) ($googleUser['id'] ?? '');
        $email = (string) ($googleUser['email'] ?? '');
        $name = (string) ($googleUser['name'] ?? '');
        $avatar = $googleUser['avatar'] ?? null;

        if ($email === '') {
            throw new \InvalidArgumentException('Google account did not provide an email address.');
        }

        $user = null;

        if ($googleId !== '') {
            $user = User::query()->where('google_id', $googleId)->first();
        }

        if (!$user && $email !== '') {
            $user = User::query()->where('email', $email)->first();
        }

        if (!$user) {
            $user = new User();
            $user->email = $email;
            $user->password = Hash::make(Str::random(64));
            $user->role = 'user';
        }

        if ($googleId !== '') {
            $user->google_id = $googleId;
        }

        if ($name !== '') {
            $user->name = $name;
        }

        if (is_string($avatar) && $avatar !== '') {
            $user->avatar = $avatar;
        }

        if (!$user->email_verified_at && $email !== '') {
            $user->email_verified_at = now();
        }

        $user->last_login_at = now();
        $user->save();

        return $user;
    }

    public function issueSanctumToken(User $user, string $tokenName = 'api'): string
    {
        return $user->createToken($tokenName)->plainTextToken;
    }
}
