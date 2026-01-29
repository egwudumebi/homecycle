@php
    $img = $item['image']['url'] ?? null;
    $storeName = (string) config('app.store.name', 'HomeCycle');
@endphp

<a href="{{ route('web.listing.show', ['slug' => $item['slug']]) }}" class="group block rounded-xl border border-slate-200 bg-white overflow-hidden hover:shadow-sm transition dark:border-slate-800 dark:bg-slate-900">
    <div class="aspect-[4/3] bg-slate-100 dark:bg-slate-800">
        @if($img)
            <img src="{{ $img }}" alt="" class="h-full w-full object-cover" loading="lazy" />
        @else
            <div class="h-full w-full flex items-center justify-center text-sm text-slate-500 dark:text-slate-300">No image</div>
        @endif
    </div>
    <div class="p-4">
        <div class="text-sm font-semibold text-slate-900 line-clamp-2 dark:text-slate-100">{{ $item['title'] ?? '' }}</div>
        <div class="mt-2 flex items-center justify-between">
            <div class="text-sm font-semibold dark:text-slate-100">₦{{ number_format((float) ($item['price'] ?? 0), 2) }}</div>
            <div class="text-xs text-slate-600 dark:text-slate-300">Sold by {{ $storeName }}</div>
        </div>
    </div>
</a>
