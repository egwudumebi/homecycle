<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        if (!auth()->check()) {
            return redirect()->route('auth.google.redirect', [
                'intent' => 'checkout',
                'return_url' => route('web.checkout'),
            ]);
        }

        return view('web.checkout');
    }
}
