@extends('web.layouts.app')

@section('title', 'Checkout')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-slate-100">Checkout</h1>
            <p class="mt-2 text-sm font-medium text-slate-500 dark:text-slate-300">This is a placeholder checkout page. Next step: add delivery details, payment, and order creation.</p>

            <div class="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-5 text-sm text-slate-700 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200">
                <div class="font-black">Coming next</div>
                <div class="mt-2 space-y-1">
                    <div>- Address + delivery options</div>
                    <div>- Payment integration</div>
                    <div>- Order summary</div>
                </div>
            </div>
        </div>
    </div>
@endsection
