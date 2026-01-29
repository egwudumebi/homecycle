@props([
    'categories' => [],
    'query' => null,
    'categorySlug' => null,
    'action' => null,
])

@php
    $action = $action ?: route('web.search');
@endphp

<form action="{{ $action }}" method="GET" class="w-full">
    <div class="flex w-full items-stretch gap-2">
        @if(!empty($categories))
            <div class="hidden sm:block">
                <select name="category_slug" class="h-full w-44 rounded-xl border border-slate-300 bg-white px-3 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                    <option value="">{{ __('ui.all_categories') }}</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat['slug'] }}" @selected(($categorySlug ?? request('category_slug')) === $cat['slug'])>{{ $cat['name'] }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <input
            name="q"
            value="{{ $query ?? request('q') }}"
            placeholder="{{ __('ui.search_placeholder') }}"
            class="h-11 w-full rounded-xl border border-slate-300 bg-white px-4 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
        />

        <button class="h-11 shrink-0 rounded-xl bg-slate-900 px-4 text-sm font-semibold text-white hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">{{ __('ui.search') }}</button>
    </div>
</form>
