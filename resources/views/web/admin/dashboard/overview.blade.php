@extends('web.admin.layout')

@section('title', 'Admin Overview')
@section('admin_title', 'Dashboard Overview')

@section('admin_content')
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 lg:gap-6">
        <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Inventory</span>
            </div>
            <div class="mt-4">
                <h3 class="text-xs font-semibold text-slate-500">Total Products</h3>
                <p class="text-2xl font-bold tracking-tight text-slate-900">{{ number_format((int) ($stats['total_listings'] ?? 0)) }}</p>
            </div>
            <div class="mt-2 flex items-center text-xs font-medium text-indigo-600">
                <span class="rounded-full bg-indigo-50 px-2 py-0.5">Active: {{ number_format((int) ($stats['active_listings'] ?? 0)) }}</span>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Performance</span>
            </div>
            <div class="mt-4">
                <h3 class="text-xs font-semibold text-slate-500">Sold Products</h3>
                <p class="text-2xl font-bold tracking-tight text-slate-900">{{ number_format((int) ($stats['sold_listings'] ?? 0)) }}</p>
            </div>
            <div class="mt-2 text-xs text-slate-500">
                Sales rate: <span class="font-bold text-emerald-600">{{ number_format((float) ($stats['sales_rate'] ?? 0), 1) }}%</span>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Revenue</span>
            </div>
            <div class="mt-4">
                <h3 class="text-xs font-semibold text-slate-500">Sold Amount</h3>
                <p class="text-2xl font-bold tracking-tight text-slate-900">₦{{ number_format((float) ($stats['sold_amount'] ?? 0), 0) }}</p>
            </div>
            <div class="mt-2 text-xs text-slate-500">
                Featured rate: <span class="font-bold text-blue-600">{{ number_format((float) ($stats['featured_rate'] ?? 0), 1) }}%</span>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 transition-all hover:shadow-md">
            <div class="flex items-center justify-between">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Community</span>
            </div>
            <div class="mt-4">
                <h3 class="text-xs font-semibold text-slate-500">Total Users</h3>
                <p class="text-2xl font-bold tracking-tight text-slate-900">{{ number_format((int) ($stats['users'] ?? 0)) }}</p>
            </div>
            <div class="mt-2 text-xs text-slate-500">
                Saved Items: <span class="font-bold text-purple-600">{{ number_format((int) ($stats['saved'] ?? 0)) }}</span>
            </div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-12">
        <div class="xl:col-span-8 rounded-2xl border border-slate-200 bg-white p-6">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Activity Trends</h3>
                    <p class="text-xs text-slate-500">Published listings over the last {{ count($publishedSeries ?? []) }} days</p>
                </div>
                <div class="rounded-lg bg-slate-50 px-3 py-1.5 text-[10px] font-bold text-slate-600 border border-slate-100 uppercase">
                    Peak: {{ (int) ($maxPublished ?? 1) }}
                </div>
            </div>

            <div class="relative flex items-end gap-2 h-48 sm:gap-3">
                @foreach(($publishedSeries ?? []) as $point)
                    @php
                        $value = (int) ($point['total'] ?? 0);
                        $max = (int) ($maxPublished ?? 1);
                        $h = $max > 0 ? (int) round(($value / $max) * 100) : 0;
                    @endphp
                    <div class="group relative flex flex-1 flex-col items-center justify-end h-full">
                        <div class="absolute -top-8 left-1/2 -translate-x-1/2 rounded bg-slate-900 px-2 py-1 text-[10px] font-bold text-white opacity-0 transition-opacity group-hover:opacity-100 z-10">
                            {{ $value }}
                        </div>
                        <div class="w-full rounded-t-lg bg-indigo-50 transition-all duration-300 group-hover:bg-indigo-100 overflow-hidden relative">
                            <div class="w-full bg-indigo-600 transition-all duration-500 ease-out" style="height: {{ max(4, $h) }}%;"></div>
                        </div>
                        <div class="mt-3 text-[9px] font-bold uppercase text-slate-400 truncate w-full text-center">
                            {{ \Illuminate\Support\Str::of($point['day'] ?? '')->afterLast('-') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="xl:col-span-4 space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-6">
                <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    Catalogue
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between rounded-xl bg-slate-50 p-3 border border-slate-100">
                        <span class="text-xs font-medium text-slate-600">Categories</span>
                        <span class="text-sm font-bold text-slate-900">{{ number_format((int) ($stats['categories'] ?? 0)) }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-slate-50 p-3 border border-slate-100">
                        <span class="text-xs font-medium text-slate-600">Subcategories</span>
                        <span class="text-sm font-bold text-slate-900">{{ number_format((int) ($stats['sub_categories'] ?? 0)) }}</span>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6">
                <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Visibility
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-xl border border-slate-100 bg-white p-3 text-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase">Hidden</p>
                        <p class="text-lg font-bold text-slate-900">{{ number_format((int) ($stats['hidden_listings'] ?? 0)) }}</p>
                    </div>
                    <div class="rounded-xl border border-indigo-100 bg-indigo-50/30 p-3 text-center">
                        <p class="text-[10px] font-bold text-indigo-400 uppercase">Featured</p>
                        <p class="text-lg font-bold text-indigo-700">{{ number_format((int) ($stats['featured_listings'] ?? 0)) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Recently Added Products</h3>
                <p class="text-xs text-slate-500 mt-0.5">Summary of the latest inventory submissions</p>
            </div>
            <a href="{{ route('admin.listings.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-700 border border-slate-200 hover:bg-slate-100 transition-colors">
                View All
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Reference</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Product Details</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Status</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Price</th>
                        <th class="px-6 py-4 text-[10px] font-bold uppercase tracking-wider text-slate-400">Region</th>
                        <th class="px-6 py-4 text-right text-[10px] font-bold uppercase tracking-wider text-slate-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse(($latestListings ?? []) as $l)
                        <tr class="group hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono text-xs font-semibold text-slate-400">#{{ $l->id }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 flex-shrink-0 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-[10px]">
                                        IMG
                                    </div>
                                    <div class="min-w-0">
                                        <div class="truncate text-sm font-bold text-slate-900">{{ $l->title }}</div>
                                        <div class="truncate text-[10px] font-medium text-slate-500 italic">{{ $l->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusStyle = match(strtolower((string) $l->status)) {
                                        'active' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-100',
                                        'sold' => 'bg-blue-50 text-blue-700 border-blue-100',
                                        default => 'bg-slate-50 text-slate-700 border-slate-200'
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-md border px-2 py-0.5 text-[10px] font-bold uppercase {{ $statusStyle }}">
                                    {{ $l->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-900 text-sm">
                                ₦{{ number_format((float) $l->price, 0) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5 text-xs text-slate-600 font-medium">
                                    <svg class="h-3 w-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $l->city?->name ?? 'N/A' }}, {{ $l->state?->name ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.listings.edit', ['id' => $l->id]) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-400 hover:text-indigo-600 hover:border-indigo-100 hover:bg-indigo-50 transition-all">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                <div class="flex flex-col items-center">
                                    <svg class="h-10 w-10 text-slate-200 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    <p class="text-sm font-medium">No recent listings found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection