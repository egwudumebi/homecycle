@extends('web.admin.layout')

@section('title', 'Categories')
@section('admin_title', 'Categories')

@section('admin_content')
    <div class="mb-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.15em] text-slate-400">Catalogue</p>
                <h2 class="mt-1 text-xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100">Categories</h2>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Browse categories and drill into subcategories and listings.</p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-end">
                <a href="{{ route('admin.catalogue.subcategories.index') }}" class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-xs font-bold text-slate-800 hover:bg-slate-50 active:scale-[0.99] dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800">Subcategories</a>
                <a href="{{ route('admin.listings.index') }}" class="inline-flex h-10 items-center justify-center rounded-xl bg-indigo-600 px-4 text-xs font-bold text-white shadow-sm shadow-indigo-200 hover:brightness-105 active:scale-[0.99]">View listings</a>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-col gap-2 border-b border-slate-100 bg-slate-50 px-5 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-slate-800 dark:bg-slate-900">
            <div class="text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-300">All categories</div>
            <div class="text-xs font-semibold text-slate-500 dark:text-slate-300">{{ $categories->total() }} total</div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-800">
                <thead class="bg-white dark:bg-slate-900">
                <tr>
                    <th class="px-5 py-3 text-left text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Name</th>
                    <th class="px-5 py-3 text-left text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Slug</th>
                    <th class="px-5 py-3 text-left text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Subcategories</th>
                    <th class="px-5 py-3 text-left text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Listings</th>
                    <th class="px-5 py-3 text-left text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Status</th>
                    <th class="px-5 py-3 text-right text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($categories as $cat)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40">
                        <td class="px-5 py-4">
                            <div class="font-bold text-slate-900 dark:text-slate-100">{{ $cat->name }}</div>
                            <div class="mt-1 text-xs text-slate-500 dark:text-slate-300">Sort: {{ (int) $cat->sort_order }}</div>
                        </td>
                        <td class="px-5 py-4 font-mono text-xs text-slate-600 dark:text-slate-300">{{ $cat->slug }}</td>
                        <td class="px-5 py-4 text-sm font-bold text-slate-800 dark:text-slate-100">{{ number_format((int) ($cat->sub_categories_count ?? 0)) }}</td>
                        <td class="px-5 py-4 text-sm font-bold text-slate-800 dark:text-slate-100">{{ number_format((int) ($cat->listings_count ?? 0)) }}</td>
                        <td class="px-5 py-4">
                            @if($cat->is_active)
                                <span class="inline-flex items-center gap-2 rounded-full bg-green-50 px-3 py-1 text-[11px] font-extrabold uppercase tracking-wider text-green-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-[11px] font-extrabold uppercase tracking-wider text-slate-600">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('admin.catalogue.categories.show', ['category' => $cat->id]) }}" class="inline-flex h-9 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-xs font-bold text-slate-800 hover:bg-slate-50 active:scale-[0.99] dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-500 dark:text-slate-300">No categories found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 bg-white px-5 py-4 dark:border-slate-800 dark:bg-slate-900">
            <div class="[&_.pagination]:justify-center sm:[&_.pagination]:justify-end">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
@endsection
