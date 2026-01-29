@extends('web.admin.layout')

@section('title', 'Manage Listings')
@section('admin_title', 'Listings')

@section('admin_content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="relative flex-1 max-w-md">
                <form method="GET" action="{{ route('admin.listings.index') }}">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input name="q" value="{{ request('q') }}" 
                        placeholder="Search by title or ID..." 
                        class="w-full rounded-xl border border-slate-200 bg-white pl-10 pr-4 py-2.5 text-sm transition-all focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 shadow-sm" />
                </form>
            </div>

            <a href="{{ route('admin.listings.create') }}" 
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-indigo-200 transition-all hover:bg-indigo-700 active:scale-95">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                New Listing
            </a>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Ref ID</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Product details</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Price</th>
                            <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Store</th>
                            <th class="px-6 py-4 text-right text-[10px] font-bold uppercase tracking-wider text-slate-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse(($listings ?? []) as $item)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-mono text-xs font-semibold text-slate-400">#{{ $item['id'] ?? '' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-900 line-clamp-1">{{ $item['title'] ?? 'Untitled Listing' }}</span>
                                        <span class="text-[11px] font-medium text-slate-500 italic">/{{ $item['slug'] ?? '' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-slate-900">
                                        ₦{{ number_format((float) ($item['price'] ?? 0), 2) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1.5 text-xs font-medium text-slate-600">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v6H3V3zm0 8h18v10H3V11z" />
                                        </svg>
                                        {{ config('app.store.name', 'HomeCycle') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.listings.edit', ['id' => $item['id']]) }}" 
                                       class="inline-flex h-9 items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 text-xs font-bold text-slate-700 shadow-sm transition-all hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-600">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="rounded-full bg-slate-50 p-4 mb-4">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-bold text-slate-900">No listings found</p>
                                        <p class="text-xs text-slate-500 mt-1">Try adjusting your search or filters.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(is_array($meta ?? null))
                <div class="flex items-center justify-between border-t border-slate-100 bg-slate-50/30 px-6 py-4">
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-widest">
                        Page <span class="text-slate-900">{{ $meta['current_page'] ?? 1 }}</span> of {{ $meta['last_page'] ?? 1 }}
                    </div>
                    <div class="flex items-center gap-2">
                        @php
                            $cp = (int) ($meta['current_page'] ?? 1);
                            $lp = (int) ($meta['last_page'] ?? 1);
                        @endphp
                        
                        <a href="{{ $cp > 1 ? route('admin.listings.index', array_merge(request()->query(), ['page' => $cp - 1])) : '#' }}" 
                           class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition-all {{ $cp > 1 ? 'hover:bg-slate-50 hover:text-indigo-600' : 'opacity-40 cursor-not-allowed' }}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                        </a>

                        <a href="{{ $cp < $lp ? route('admin.listings.index', array_merge(request()->query(), ['page' => $cp + 1])) : '#' }}" 
                           class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition-all {{ $cp < $lp ? 'hover:bg-slate-50 hover:text-indigo-600' : 'opacity-40 cursor-not-allowed' }}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection