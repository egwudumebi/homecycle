@props(['item'])

@php
    $img = $item['image']['url'] ?? null;
    $title = $item['title'] ?? '';
    $price = (float) ($item['price'] ?? 0);
    $storeName = (string) config('app.store.name', 'HomeCycle');
@endphp

<a href="{{ route('web.listing.show', ['slug' => $item['slug']]) }}" class="group block overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md active:scale-[0.99] dark:border-slate-800 dark:bg-slate-900">
    <div class="relative aspect-[4/3] bg-slate-100 dark:bg-slate-800">
        @if($img)
            <img src="{{ $img }}" alt="" class="h-full w-full object-cover" loading="lazy" />
        @else
            <div class="flex h-full w-full items-center justify-center text-sm text-slate-500 dark:text-slate-300">No image</div>
        @endif
    </div>

    <div class="p-4 sm:p-5">
        <div class="text-xs sm:text-sm font-semibold text-slate-900 truncate dark:text-slate-100">{{ $title }}</div>

        <div class="mt-3 flex items-center justify-between gap-3">
            <div class="text-base font-extrabold tracking-tight text-slate-900 dark:text-slate-100">₦{{ number_format($price, 2) }}</div>
            <div class="text-xs font-medium text-slate-600 truncate dark:text-slate-300">Sold by {{ $storeName }}</div>
        </div>

        <div class="mt-4">
            <button type="button" class="w-full inline-flex items-center justify-center gap-2 rounded-2xl bg-slate-900 px-4 py-3 text-xs font-black text-white transition hover:bg-slate-800 active:scale-[0.99] dark:bg-white dark:text-slate-900" onclick="event.preventDefault(); event.stopPropagation(); if (!window.hcCart || (window.hcCart.isPending && window.hcCart.isPending('add:{{ (int) ($item['id'] ?? 0) }}'))) return; window.hcCart.add({{ (int) ($item['id'] ?? 0) }}, 1).catch(() => {})">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m12-9l2 9M9 22a1 1 0 100-2 1 1 0 000 2zm10 0a1 1 0 100-2 1 1 0 000 2z"/>
                </svg>
                Add to cart
            </button>
        </div>
    </div>
</a>
