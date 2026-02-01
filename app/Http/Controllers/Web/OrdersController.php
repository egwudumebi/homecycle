<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrdersController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        if (!auth()->check()) {
            return redirect()->route('auth.google.redirect', [
                'intent' => 'orders',
                'return_url' => route('web.orders.index'),
            ]);
        }

        return view('web.orders.index');
    }

    public function show(Request $request, int $order): View|RedirectResponse
    {
        if (!auth()->check()) {
            return redirect()->route('auth.google.redirect', [
                'intent' => 'orders',
                'return_url' => route('web.orders.show', ['order' => $order]),
            ]);
        }

        return view('web.orders.show', [
            'orderId' => $order,
        ]);
    }
}
