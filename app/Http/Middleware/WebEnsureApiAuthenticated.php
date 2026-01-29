<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebEnsureApiAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('api_token')) {
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
