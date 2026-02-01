@extends('web.layouts.app')

@section('title', 'Checkout')

@section('content')
    <div id="hcCheckoutRoot" class="mx-auto max-w-3xl">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-start justify-between gap-6">
                <div>
                    <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-slate-100">Checkout</h1>
                    <p class="mt-2 text-sm font-medium text-slate-500 dark:text-slate-300">Pay securely with Paystack.</p>
                </div>
                <div class="hidden sm:block rounded-2xl bg-indigo-50 px-4 py-3 text-xs font-black text-indigo-700 dark:bg-slate-950 dark:text-indigo-300">
                    NGN payments
                </div>
            </div>

            <div id="hcCheckoutLoading" class="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-5 text-sm font-semibold text-slate-600 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200">
                Loading checkout…
            </div>

            <div id="hcCheckoutError" class="mt-6 hidden rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-bold text-red-700"></div>

            <div id="hcCheckoutSuccess" class="mt-6 hidden rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800"></div>

            <div id="hcCheckoutSummary" class="mt-8 hidden">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
                    <div class="text-sm font-black text-slate-900 dark:text-slate-100">Delivery details</div>
                    <div class="mt-1 text-xs font-semibold text-slate-500 dark:text-slate-400">Required before payment</div>

                    <div id="hcCheckoutDeliveryError" class="mt-4 hidden rounded-2xl border border-red-200 bg-red-50 p-4 text-xs font-bold text-red-700"></div>

                    <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="hcDeliveryName" class="block text-xs font-black uppercase tracking-widest text-slate-500">Full name</label>
                            <input id="hcDeliveryName" type="text" autocomplete="name" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-indigo-500 dark:focus:ring-indigo-500/20" placeholder="John Doe" />
                        </div>

                        <div class="sm:col-span-2">
                            <label for="hcDeliveryPhone" class="block text-xs font-black uppercase tracking-widest text-slate-500">Phone</label>
                            <input id="hcDeliveryPhone" type="tel" autocomplete="tel" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-indigo-500 dark:focus:ring-indigo-500/20" placeholder="0800 000 0000" />
                        </div>

                        <div class="sm:col-span-2">
                            <label for="hcDeliveryAddress" class="block text-xs font-black uppercase tracking-widest text-slate-500">Address</label>
                            <textarea id="hcDeliveryAddress" rows="3" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-indigo-500 dark:focus:ring-indigo-500/20" placeholder="Street address, house number, etc."></textarea>
                        </div>

                        <div>
                            <label for="hcDeliveryState" class="block text-xs font-black uppercase tracking-widest text-slate-500">State</label>
                            <input id="hcDeliveryState" type="text" autocomplete="address-level1" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-indigo-500 dark:focus:ring-indigo-500/20" placeholder="Lagos" />
                        </div>

                        <div>
                            <label for="hcDeliveryCity" class="block text-xs font-black uppercase tracking-widest text-slate-500">City</label>
                            <input id="hcDeliveryCity" type="text" autocomplete="address-level2" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-indigo-500 dark:focus:ring-indigo-500/20" placeholder="Ikeja" />
                        </div>

                        <div class="sm:col-span-2">
                            <label for="hcDeliveryNotes" class="block text-xs font-black uppercase tracking-widest text-slate-500">Delivery notes (optional)</label>
                            <textarea id="hcDeliveryNotes" rows="2" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-indigo-500 dark:focus:ring-indigo-500/20" placeholder="Landmark, gate code, preferred delivery time..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-black text-slate-900 dark:text-slate-100">Order summary</div>
                        <div class="text-xs font-semibold text-slate-500 dark:text-slate-400">Server-calculated</div>
                    </div>

                    <div id="hcCheckoutItems" class="mt-4 space-y-3"></div>

                    <div class="mt-6 flex items-center justify-between rounded-2xl bg-white p-4 ring-1 ring-slate-900/5 dark:bg-slate-900 dark:ring-white/10">
                        <div>
                            <div class="text-xs font-bold text-slate-500 dark:text-slate-400">Total</div>
                            <div class="mt-1 text-[11px] font-semibold text-slate-400 dark:text-slate-500">Delivery fee coming later</div>
                        </div>
                        <div id="hcCheckoutSubtotal" class="text-lg font-black text-slate-900 dark:text-slate-100">₦0.00</div>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <a href="{{ route('web.home') }}" class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-center text-sm font-black text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 dark:hover:bg-slate-900">Continue shopping</a>
                    <button id="hcCheckoutPayBtn" type="button" class="rounded-2xl bg-indigo-600 px-4 py-3 text-sm font-black text-white shadow-lg shadow-indigo-200 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed active:scale-[0.99] dark:shadow-none">
                        <span id="hcCheckoutPayBtnText">Pay with Paystack</span>
                    </button>
                </div>

                <div class="mt-6 text-xs font-semibold text-slate-500 dark:text-slate-400">
                    Your cart will only be cleared after payment is verified.
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script src="{{ url('js/checkout.js') }}" defer></script>
@endpush
