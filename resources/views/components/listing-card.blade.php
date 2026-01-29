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
        <div class="text-sm font-semibold text-slate-900 line-clamp-2 dark:text-slate-100">{{ $title }}</div>

        <div class="mt-3 flex items-center justify-between gap-3">
            <div class="text-base font-extrabold tracking-tight text-slate-900 dark:text-slate-100">₦{{ number_format($price, 2) }}</div>
            <div class="text-xs font-medium text-slate-600 truncate dark:text-slate-300">Sold by {{ $storeName }}</div>
        </div>

        <div class="mt-4">
            <button type="button" class="w-full rounded-2xl bg-slate-900 px-4 py-3 text-xs font-black text-white transition hover:bg-slate-800 active:scale-[0.99] dark:bg-white dark:text-slate-900" onclick="event.preventDefault(); event.stopPropagation(); if (!window.hcCart || (window.hcCart.isPending && window.hcCart.isPending('add:{{ (int) ($item['id'] ?? 0) }}'))) return; window.hcCart.add({{ (int) ($item['id'] ?? 0) }}, 1).catch(() => {})">
                Add to cart
            </button>
        </div>
    </div>
</a>
