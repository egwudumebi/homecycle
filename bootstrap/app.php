<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\WebEnsureAdmin;
use App\Http\Middleware\WebEnsureApiAuthenticated;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'api/v1/cart*',
            'api/v1/checkout*',
            'api/v1/orders*',
            'api/v1/paystack*',
        ]);

        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
            'setLocale' => SetLocale::class,
            'web.auth' => WebEnsureApiAuthenticated::class,
            'web.admin' => WebEnsureAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
