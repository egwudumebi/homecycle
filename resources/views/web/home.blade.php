@extends('web.layouts.app')

@section('title', 'Home Electronics Store')

@section('content')
<div x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 1000)" class="space-y-12">
    
    <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 px-6 py-16 sm:px-16 sm:py-24 shadow-2xl">
        <div class="absolute -top-24 -right-24 h-96 w-96 rounded-full bg-indigo-600/30 blur-[100px] animate-pulse"></div>
        <div class="absolute -bottom-24 -left-24 h-96 w-96 rounded-full bg-blue-600/20 blur-[100px]"></div>

        <div class="relative z-10 flex flex-col items-center text-center max-w-3xl mx-auto">
            <span class="inline-block px-4 py-1.5 mb-6 text-xs font-bold tracking-widest text-indigo-400 uppercase bg-indigo-500/10 border border-indigo-500/20 rounded-full">
                Premium Marketplace
            </span>
            <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-6xl lg:leading-[1.1]">
                Next-gen electronics for your <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">modern home.</span>
            </h1>
            <p class="mt-6 text-lg text-slate-400 leading-relaxed">
                Discover curated smart devices and premium household equipment with industry-leading warranties.
            </p>

            <form action="{{ route('web.search') }}" method="GET" class="mt-10 flex w-full max-w-xl items-center gap-2 rounded-2xl bg-white/10 p-2 backdrop-blur-md border border-white/10 shadow-2xl focus-within:border-indigo-500/50 transition-all">
                <div class="flex flex-1 items-center px-4">
                    <svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input name="q" placeholder="Search appliances, gadgets..." class="w-full border-none bg-transparent px-3 py-3 text-white placeholder:text-slate-500 focus:ring-0 sm:text-sm" />
                </div>
                <button type="submit" class="hidden sm:block rounded-xl bg-indigo-600 px-8 py-3 text-sm font-bold text-white transition hover:bg-indigo-500 hover:shadow-[0_0_20px_rgba(79,70,229,0.4)] active:scale-95">
                    Search
                </button>
            </form>
        </div>
    </div>

    <div class="mt-12">
        <div class="flex items-center justify-between mb-6 px-1">
            <h3 class="text-sm font-bold uppercase tracking-[0.2em] text-slate-500">Popular Categories</h3>
            <a href="{{ route('web.search') }}" class="group flex items-center gap-1 text-sm font-bold text-indigo-600">
                View All <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="no-scrollbar flex items-center gap-4 overflow-x-auto pb-4">
            @foreach(($categories ?? []) as $cat)
                <a href="{{ route('web.category.show', ['slug' => $cat['slug']]) }}" 
                   class="group flex shrink-0 items-center gap-3 rounded-2xl border border-slate-200 bg-white px-6 py-4 shadow-sm transition-all hover:border-transparent hover:ring-2 hover:ring-indigo-500/20 hover:shadow-xl dark:border-slate-800 dark:bg-slate-900">
                    <div class="h-10 w-10 rounded-xl bg-slate-50 flex items-center justify-center group-hover:bg-indigo-50 transition-colors">
                        <span class="text-xl">⚡</span>
                    </div>
                    <span class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ $cat['name'] }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <section>
        <div class="flex items-center justify-between mb-8">
            <div class="space-y-1">
                <h2 class="text-3xl font-bold text-slate-900 dark:text-white">Featured Spotlight</h2>
                <div class="h-1 w-12 bg-indigo-600 rounded-full"></div>
            </div>
        </div>

        <template x-if="loading">
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                <template x-for="i in 4">
                    <div class="animate-pulse space-y-4">
                        <div class="aspect-square bg-slate-200 dark:bg-slate-800 rounded-3xl"></div>
                        <div class="h-4 bg-slate-200 dark:bg-slate-800 rounded w-3/4"></div>
                        <div class="h-4 bg-slate-200 dark:bg-slate-800 rounded w-1/2"></div>
                    </div>
                </template>
            </div>
        </template>

        <div x-show="!loading" x-cloak class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
            @forelse(($featured ?? []) as $item)
                <div class="group relative transition-all duration-500 hover:-translate-y-2">
                    <x-listing-card :item="$item" />
                </div>
            @empty
                <div class="col-span-full rounded-[2rem] border-2 border-dashed border-slate-200 p-20 text-center">
                    <p class="text-slate-500 font-medium">No items found in this collection.</p>
                </div>
            @endforelse
        </div>
    </section>

</div>
@endsection