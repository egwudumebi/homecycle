<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService
{
    public function attemptLogin(string $email, string $password): ?array
    {
        /** @var User|null $user */
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        $token = $user->createToken('api')->plainTextToken;

        return [
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
            ],
        ];
    }

    public function revokeToken(string $plainTextToken): void
    {
        $token = PersonalAccessToken::findToken($plainTextToken);

        $token?->delete();
    }
}
