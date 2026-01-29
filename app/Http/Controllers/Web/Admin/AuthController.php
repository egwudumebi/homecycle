<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('web.admin.login_single');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        /** @var AuthService $authService */
        $authService = app(AuthService::class);
        $result = $authService->attemptLogin($data['email'], $data['password']);

        if (!is_array($result)) {
            return back()->withErrors(['email' => 'Login failed'])->withInput();
        }

        $token = $result['token'] ?? null;
        $user = $result['user'] ?? null;

        if (!is_string($token) || !is_array($user) || (($user['role'] ?? null) !== 'admin')) {
            return back()->withErrors(['email' => 'Admin access required'])->withInput();
        }

        $request->session()->put('api_token', $token);
        $request->session()->put('api_user', $user);

        return redirect()->route('admin.overview');
    }

    public function logout(Request $request)
    {
        $token = $request->session()->get('api_token');

        if (is_string($token)) {
            /** @var AuthService $authService */
            $authService = app(AuthService::class);
            $authService->revokeToken($token);
        }

        $request->session()->forget(['api_token', 'api_user']);

        return redirect()->route('admin.login');
    }
}
