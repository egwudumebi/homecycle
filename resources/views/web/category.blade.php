@extends('web.layouts.app')

@section('title', ($category['name'] ?? 'Category').' - Marketplace')

@section('content')
    <div class="relative mb-8">
        <nav class="flex mb-4 text-[10px] font-bold uppercase tracking-widest text-slate-400">
            <a href="{{ route('web.home') }}" class="hover:text-indigo-600 transition">Home</a>
            <span class="mx-2">/</span>
            <span class="text-slate-900 dark:text-slate-100">{{ $category['name'] ?? 'Category' }}</span>
        </nav>


        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="text-3xl font-black tracking-tight text-slate-900 sm:text-4xl dark:text-slate-100">
                    {{ $category['name'] ?? 'Category' }}
                </h1>
                <p class="mt-2 text-sm font-medium text-slate-500 dark:text-slate-300">
                    Discover {{ count($listings ?? []) }} active listings in this category.
                </p>
            </div>
            
            <a href="{{ route('web.search', ['category_slug' => $category['slug'] ?? null]) }}" 
               class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50 active:scale-95 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800">
                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Advanced Filters
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
        <aside class="hidden lg:col-span-3 lg:block space-y-8">
            <div>
                <h3 class="text-xs font-black uppercase tracking-widest text-slate-900 mb-4 dark:text-slate-100">Sub-categories</h3>
                <div class="space-y-2">
                    @foreach(($category['sub_categories'] ?? []) as $sub)
                        <a href="{{ route('web.search', ['sub_category_id' => $sub['id']]) }}" 
                           class="flex items-center justify-between group rounded-xl px-4 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-indigo-50 hover:text-indigo-700 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100">
                            {{ $sub['name'] }}
                            <span class="rounded-lg bg-slate-100 px-2 py-0.5 text-[10px] text-slate-400 group-hover:bg-indigo-100 group-hover:text-indigo-600">
                                →
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl bg-slate-900 p-6 shadow-xl">
                <h4 class="text-sm font-bold text-white">Need help choosing?</h4>
                <p class="mt-2 text-xs text-slate-400 leading-relaxed">Contact our store for recommendations and availability in {{ $category['name'] }}.</p>
                <a href="{{ route('admin.listings.create') }}" class="mt-4 block w-full rounded-xl bg-indigo-500 py-2.5 text-center text-xs font-bold text-white transition hover:bg-indigo-400">
                    Add Inventory
                </a>
            </div>
        </aside>

        <div class="lg:col-span-9">
            <div class="no-scrollbar mb-6 flex items-center gap-2 overflow-x-auto lg:hidden">
                @foreach(($category['sub_categories'] ?? []) as $sub)
                    <a href="{{ route('web.search', ['sub_category_id' => $sub['id']]) }}" 
                       class="shrink-0 rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-600 hover:border-indigo-300 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100">
                        {{ $sub['name'] }}
                    </a>
                @endforeach
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
                @forelse(($listings ?? []) as $item)
                    <div class="transition-all duration-300 hover:-translate-y-1">
                        <x-listing-card :item="$item" />
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-100 py-24 text-center dark:border-slate-800 dark:bg-slate-900">
                        <div class="rounded-full bg-slate-50 p-4 dark:bg-slate-800">
                            <svg class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                        </div>
                        <h3 class="mt-4 text-sm font-bold text-slate-900 uppercase tracking-widest dark:text-slate-100">No listings yet</h3>
                        <p class="mt-1 text-sm text-slate-400 dark:text-slate-300">No items available in this category right now.</p>
                        <a href="{{ route('admin.listings.create') }}" class="mt-6 rounded-xl bg-slate-900 px-6 py-2.5 text-sm font-bold text-white shadow-lg transition hover:bg-slate-800">
                            Create Listing
                        </a>
                    </div>
                @endforelse
            </div>

            @if(is_object($listings) && method_exists($listings, 'links'))
                <div class="mt-12 border-t border-slate-100 pt-8 dark:border-slate-800">
                    {{ $listings->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush