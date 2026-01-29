<div x-data class="fixed inset-0 z-[60]" x-show="window.hcCart?.state?.open" x-cloak>
    <div class="absolute inset-0 bg-slate-900/40" @click="window.hcCart.toggle(false)"></div>

    <div class="absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-2xl dark:bg-slate-950">
        <div class="flex items-center justify-between border-b border-slate-200 p-5 dark:border-slate-800">
            <div>
                <div class="text-sm font-black tracking-tight text-slate-900 dark:text-slate-100">Your Cart</div>
                <div class="text-xs font-semibold text-slate-500 dark:text-slate-400" x-text="`${window.hcCart.state.total_items || 0} items`"></div>
            </div>
            <button type="button" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold text-slate-700 dark:border-slate-800 dark:text-slate-200" @click="window.hcCart.toggle(false)">Close</button>
        </div>

        <div class="p-5 space-y-4 overflow-y-auto" style="height: calc(100% - 170px);">
            <template x-if="window.hcCart.state.error">
                <div class="rounded-2xl border border-red-200 bg-red-50 p-3 text-xs font-bold text-red-700" x-text="window.hcCart.state.error"></div>
            </template>

            <template x-if="window.hcCart.state.loading">
                <div class="text-sm font-semibold text-slate-500">Loading...</div>
            </template>

            <template x-if="!window.hcCart.state.loading && (window.hcCart.state.items || []).length === 0">
                <div class="rounded-3xl border border-slate-200 p-8 text-center dark:border-slate-800">
                    <div class="text-sm font-bold text-slate-900 dark:text-slate-100">Your cart is empty</div>
                    <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">Add items to see them here.</div>
                </div>
            </template>

            <template x-for="item in (window.hcCart.state.items || [])" :key="item.id">
                <div class="flex gap-3 rounded-3xl border border-slate-200 bg-white p-3 dark:border-slate-800 dark:bg-slate-900">
                    <a class="h-16 w-16 shrink-0 overflow-hidden rounded-2xl bg-slate-100 dark:bg-slate-800" :href="`/listing/${item.listing.slug}`">
                        <template x-if="item.listing.image_url">
                            <img :src="item.listing.image_url" class="h-full w-full object-cover" alt="" />
                        </template>
                    </a>

                    <div class="min-w-0 flex-1">
                        <a class="block text-sm font-bold text-slate-900 truncate dark:text-slate-100" :href="`/listing/${item.listing.slug}`" x-text="item.listing.title"></a>
                        <div class="mt-1 text-xs font-bold text-slate-600 dark:text-slate-300">₦<span x-text="window.hcCart.formatMoney(item.price_at_time)"></span></div>

                        <div class="mt-3 flex items-center justify-between gap-2">
                            <div class="inline-flex items-center rounded-2xl border border-slate-200 dark:border-slate-800">
                                <button type="button" class="px-3 py-2 text-sm font-black" @click="window.hcCart.setQty(item.id, Math.max(0, (item.quantity || 1) - 1))">−</button>
                                <div class="px-3 py-2 text-xs font-black" x-text="item.quantity"></div>
                                <button type="button" class="px-3 py-2 text-sm font-black" @click="window.hcCart.setQty(item.id, (item.quantity || 1) + 1)">+</button>
                            </div>

                            <button type="button" class="text-xs font-black text-slate-400 hover:text-red-500" @click="window.hcCart.remove(item.id)">Remove</button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div class="border-t border-slate-200 p-5 dark:border-slate-800">
            <div class="flex items-center justify-between text-sm font-black text-slate-900 dark:text-slate-100">
                <div>Subtotal</div>
                <div>₦<span x-text="window.hcCart.formatMoney(window.hcCart.state.subtotal)"></span></div>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-2">
                <button type="button" class="rounded-2xl border border-slate-200 px-4 py-3 text-xs font-black text-slate-700 dark:border-slate-800 dark:text-slate-200" @click="window.hcCart.clear()">Clear</button>
                <button type="button" class="rounded-2xl bg-slate-900 px-4 py-3 text-xs font-black text-white dark:bg-white dark:text-slate-900">Checkout</button>
            </div>
        </div>
    </div>
</div>
