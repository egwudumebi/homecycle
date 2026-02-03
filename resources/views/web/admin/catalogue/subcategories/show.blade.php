@extends('web.admin.layout')

@section('title', 'Subcategory Details')
@section('admin_title', 'Subcategory Details')

@section('admin_content')
    <div class="mb-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.15em] text-slate-400">Catalogue</p>
                <h2 class="mt-1 text-xl font-extrabold tracking-tight text-slate-900">{{ $subCategory->name }}</h2>
                <p class="mt-2 text-sm text-slate-600 font-mono">{{ $subCategory->slug }}</p>
                <p class="mt-2 text-sm text-slate-700">Category:
                    @if($subCategory->category)
                        <a class="font-bold text-indigo-600 hover:underline" href="{{ route('admin.catalogue.categories.show', ['category' => $subCategory->category->id]) }}">{{ $subCategory->category->name }}</a>
                    @else
                        <span class="text-slate-500">N/A</span>
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.catalogue.subcategories.index') }}" class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-xs font-bold text-slate-800 hover:bg-slate-50">Back</a>
                <a href="{{ route('admin.listings.index', array_filter(['sub_category_id' => $subCategory->id])) }}" class="inline-flex h-10 items-center justify-center rounded-xl bg-indigo-600 px-4 text-xs font-bold text-white shadow-sm shadow-indigo-200 hover:brightness-105 active:scale-[0.99]">View listings</a>
            </div>
        </div>

        <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <p class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Listings</p>
                <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ number_format((int) ($subCategory->listings_count ?? 0)) }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <p class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Sort order</p>
                <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ (int) $subCategory->sort_order }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <p class="text-[11px] font-extrabold uppercase tracking-widest text-slate-400">Status</p>
                <div class="mt-2">
                    @if($subCategory->is_active)
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
@endsection
