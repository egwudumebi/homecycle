@extends('web.admin.layout')

@section('title', 'Category Details')
@section('admin_title', 'Category Details')

@section('admin_content')
    <div class="mb-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.15em] text-slate-400">Catalogue</p>
                <h2 class="mt-1 text-xl font-extrabold tracking-tight text-slate-900">{{ $category->name }}</h2>
                <p class="mt-2 text-sm text-slate-600 font-mono">{{ $category->slug }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.catalogue.categories.index') }}" class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-xs font-bold text-slate-800 hover:bg-slate-50">Back</a>
                <a href="{{ route('admin.listings.index', array_filter(['category_id' => $category->id])) }}" class="inline-flex h-10 items-center justify-center rounded-xl bg-indigo-600 px-4 text-xs font-bold text-white shadow-sm shadow-indigo-200 hover:brightness-105 active:scale-[0.99]">View listings</a>
            </div>
        </div>

        <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <p class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Subcategories</p>
                <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ number_format((int) ($category->sub_categories_count ?? 0)) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <p class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Listings</p>
                <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ number_format((int) ($category->listings_count ?? 0)) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <p class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Status</p>
                <div class="mt-2">
                    @if($category->is_active)
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
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between gap-3 border-b border-slate-100 bg-slate-50 px-5 py-4">
            <div>
                <div class="text-xs font-bold uppercase tracking-widest text-slate-500">Subcategories</div>
                <div class="mt-1 text-sm font-semibold text-slate-700">Drill down into a subcategory to see its listings.</div>
            </div>
            <a href="{{ route('admin.catalogue.subcategories.index') }}" class="inline-flex h-9 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-xs font-bold text-slate-800 hover:bg-slate-50">All subcategories</a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-white">
                <tr>
                    <th class="px-5 py-3 text-left text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Name</th>
                    <th class="px-5 py-3 text-left text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Slug</th>
                    <th class="px-5 py-3 text-left text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Listings</th>
                    <th class="px-5 py-3 text-left text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Status</th>
                    <th class="px-5 py-3 text-right text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                @forelse(($category->subCategories ?? []) as $sc)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-4">
                            <div class="font-bold text-slate-900">{{ $sc->name }}</div>
                            <div class="mt-1 text-xs text-slate-500">Sort: {{ (int) $sc->sort_order }}</div>
                        </td>
                        <td class="px-5 py-4 font-mono text-xs text-slate-600">{{ $sc->slug }}</td>
                        <td class="px-5 py-4 text-sm font-bold text-slate-800">{{ number_format((int) ($sc->listings_count ?? 0)) }}</td>
                        <td class="px-5 py-4">
                            @if($sc->is_active)
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
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.listings.index', array_filter(['sub_category_id' => $sc->id])) }}" class="inline-flex h-9 items-center justify-center rounded-xl bg-indigo-600 px-4 text-xs font-bold text-white shadow-sm shadow-indigo-200 hover:brightness-105 active:scale-[0.99]">Listings</a>
                                <a href="{{ route('admin.catalogue.subcategories.show', ['subCategory' => $sc->id]) }}" class="inline-flex h-9 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-xs font-bold text-slate-800 hover:bg-slate-50">Details</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-sm text-slate-500">No subcategories found for this category.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
