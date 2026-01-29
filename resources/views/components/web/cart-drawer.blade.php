<div x-data x-cloak class="fixed inset-0 z-[60]" :class="($store.hcCart && $store.hcCart.open) ? '' : 'pointer-events-none'" @keydown.escape.window="window.hcCart && window.hcCart.toggle(false)">
    <div
        class="absolute inset-0 bg-slate-900/50 backdrop-blur-[1px]"
        x-show="$store.hcCart && $store.hcCart.open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="window.hcCart && window.hcCart.toggle(false)"
    ></div>

    <div
        class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-2xl ring-1 ring-slate-900/10 dark:bg-slate-950 dark:ring-white/10"
        x-show="$store.hcCart && $store.hcCart.open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
    >
        <div class="flex h-full flex-col">
            <div class="border-b border-slate-200 px-6 py-5 dark:border-slate-800">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <div class="grid h-9 w-9 place-items-center rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-200/60 dark:shadow-none">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m12-9l2 9M9 22a1 1 0 100-2 1 1 0 000 2zm10 0a1 1 0 100-2 1 1 0 000 2z"/></svg>
                            </div>
                            <div>
                                <div class="text-sm font-black tracking-tight text-slate-900 dark:text-slate-100">Cart</div>
                                <div class="text-xs font-semibold text-slate-500 dark:text-slate-400" x-text="$store.hcCart ? (($store.hcCart.total_items || 0) + ' item' + (($store.hcCart.total_items || 0) === 1 ? '' : 's')) : '0 items'"></div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="rounded-2xl border border-slate-200 bg-white px-3 py-2 text-xs font-black text-slate-700 shadow-sm hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 dark:hover:bg-slate-900" @click="window.hcCart && window.hcCart.toggle(false)">
                        Close
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto px-6 py-5">
                <template x-if="$store.hcCart && $store.hcCart.error">
                    <div class="rounded-3xl border border-red-200 bg-red-50 px-4 py-3 text-xs font-bold text-red-700" x-text="$store.hcCart.error"></div>
                </template>

                <template x-if="$store.hcCart && $store.hcCart.loading">
                    <div class="mt-2 rounded-3xl border border-slate-200 bg-white px-4 py-4 text-sm font-semibold text-slate-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-300">
                        Loading your cart…
                    </div>
                </template>

                <template x-if="$store.hcCart && !$store.hcCart.loading && ($store.hcCart.items || []).length === 0">
                    <div class="mt-2 rounded-3xl border border-dashed border-slate-200 bg-white px-6 py-10 text-center dark:border-slate-800 dark:bg-slate-950">
                        <div class="mx-auto grid h-12 w-12 place-items-center rounded-3xl bg-slate-100 text-slate-500 dark:bg-slate-900 dark:text-slate-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m12-9l2 9"/></svg>
                        </div>
                        <div class="mt-4 text-sm font-black text-slate-900 dark:text-slate-100">Your cart is empty</div>
                        <div class="mt-1 text-xs font-semibold text-slate-500 dark:text-slate-400">Add items and they’ll show up here.</div>
                    </div>
                </template>

                <div class="mt-2 space-y-3">
                    <template x-for="item in (($store.hcCart && $store.hcCart.items) ? $store.hcCart.items : [])" :key="item.id">
                        <div class="group flex gap-4 rounded-3xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                            <a class="h-16 w-16 shrink-0 overflow-hidden rounded-2xl bg-slate-100 ring-1 ring-slate-900/5 dark:bg-slate-900 dark:ring-white/10" :href="`{{ url('/listing') }}/${item.listing.slug}`">
                                <template x-if="item.listing.image_url">
                                    <img :src="item.listing.image_url" class="h-full w-full object-cover" alt="" />
                                </template>
                            </a>

                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-3">
                                    <a class="block min-w-0 text-sm font-black text-slate-900 truncate dark:text-slate-100" :href="`{{ url('/listing') }}/${item.listing.slug}`" x-text="item.listing.title"></a>
                                    <div class="shrink-0 text-right">
                                        <div class="text-xs font-extrabold text-slate-900 dark:text-slate-100">₦<span x-text="window.hcCart ? window.hcCart.formatMoney(item.price_at_time) : item.price_at_time"></span></div>
                                        <div class="mt-0.5 text-[11px] font-semibold text-slate-500 dark:text-slate-400">each</div>
                                    </div>
                                </div>

                                <div class="mt-3 flex items-center justify-between gap-3">
                                    <div class="inline-flex items-center rounded-2xl border border-slate-200 bg-white p-1 dark:border-slate-800 dark:bg-slate-950">
                                        <button type="button" class="grid h-8 w-8 place-items-center rounded-xl text-sm font-black text-slate-700 hover:bg-slate-50 disabled:opacity-40 dark:text-slate-200 dark:hover:bg-slate-900" :disabled="!window.hcCart || window.hcCart.isPending(`item:${item.id}`)" @click="window.hcCart && window.hcCart.setQty(item.id, Math.max(0, (item.quantity || 1) - 1))">−</button>
                                        <div class="grid h-8 min-w-[40px] place-items-center text-xs font-black text-slate-900 dark:text-slate-100" x-text="item.quantity"></div>
                                        <button type="button" class="grid h-8 w-8 place-items-center rounded-xl text-sm font-black text-slate-700 hover:bg-slate-50 disabled:opacity-40 dark:text-slate-200 dark:hover:bg-slate-900" :disabled="!window.hcCart || window.hcCart.isPending(`item:${item.id}`)" @click="window.hcCart && window.hcCart.setQty(item.id, (item.quantity || 1) + 1)">+</button>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <div class="text-xs font-black text-slate-900 dark:text-slate-100">₦<span x-text="window.hcCart ? window.hcCart.formatMoney((Number(item.price_at_time || 0) * Number(item.quantity || 0))) : ''"></span></div>

                                        <button type="button" class="rounded-2xl px-2 py-2 text-xs font-black text-slate-400 hover:text-red-500 disabled:opacity-40" :disabled="!window.hcCart || window.hcCart.isPending(`item:${item.id}`)" @click="window.hcCart && window.hcCart.remove(item.id)">
                                            <span x-show="!window.hcCart || !window.hcCart.isPending(`item:${item.id}`)">Remove</span>
                                            <span x-show="window.hcCart && window.hcCart.isPending(`item:${item.id}`)">Working…</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="border-t border-slate-200 px-6 py-5 dark:border-slate-800">
                <div class="rounded-3xl bg-slate-50 p-4 dark:bg-slate-900">
                    <div class="flex items-center justify-between text-sm font-black text-slate-900 dark:text-slate-100">
                        <div>Subtotal</div>
                        <div>₦<span x-text="window.hcCart && $store.hcCart ? window.hcCart.formatMoney($store.hcCart.subtotal) : ($store.hcCart ? $store.hcCart.subtotal : '0.00')"></span></div>
                    </div>
                    <div class="mt-1 text-[11px] font-semibold text-slate-500 dark:text-slate-400">Taxes & delivery calculated at checkout.</div>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3">
                    <button type="button" class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-xs font-black text-slate-700 shadow-sm hover:bg-slate-50 disabled:opacity-40 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 dark:hover:bg-slate-900" :disabled="!window.hcCart || window.hcCart.isPending('clear')" @click="window.hcCart && window.hcCart.clear()">
                        <span x-show="!window.hcCart || !window.hcCart.isPending('clear')">Clear</span>
                        <span x-show="window.hcCart && window.hcCart.isPending('clear')">Clearing…</span>
                    </button>
                    <a href="{{ route('web.checkout') }}" data-auth-intent="checkout" class="rounded-2xl bg-indigo-600 px-4 py-3 text-center text-xs font-black text-white shadow-lg shadow-indigo-200 hover:bg-indigo-700 active:scale-[0.99] dark:shadow-none">Checkout</a>
                </div>
            </div>
        </div>
    </div>
</div>
