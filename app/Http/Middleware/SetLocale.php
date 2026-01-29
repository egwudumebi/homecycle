<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $supported = array_keys((array) config('app.supported_locales', []));
        $locale = (string) session('locale', config('app.locale'));

        if (!empty($supported) && !in_array($locale, $supported, true)) {
            $locale = (string) config('app.locale');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
