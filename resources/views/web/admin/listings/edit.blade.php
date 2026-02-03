@extends('web.admin.layout')

@section('title', 'Edit Listing')
@section('admin_title', 'Edit Listing')

@section('admin_content')
    <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="min-w-0">
                <p class="text-[11px] font-bold uppercase tracking-[0.15em] text-slate-400">Listings</p>
                <h2 class="mt-1 text-xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100">Edit Listing</h2>
                <div class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-2 text-xs text-slate-600 dark:text-slate-300">
                    <span class="font-mono">ID: {{ $listingId }}</span>
                    <span class="h-1 w-1 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                    <span class="font-semibold">Status:</span>
                    <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 font-bold uppercase tracking-wide text-slate-700 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $listing['status'] ?? 'active' }}</span>
                </div>
            </div>

            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-end">
                <a href="{{ route('admin.listings.index') }}" class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-xs font-bold text-slate-800 hover:bg-slate-50 active:scale-[0.99] dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800">Back</a>
                @if(!empty($listing['slug']))
                    <a href="{{ route('web.listing.show', ['slug' => $listing['slug']]) }}" target="_blank" class="inline-flex h-10 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 text-xs font-bold text-slate-800 hover:bg-slate-50 active:scale-[0.99] dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800">Preview</a>
                @endif

                <form method="POST" action="{{ route('admin.listings.status', ['id' => $listingId]) }}" class="flex items-center gap-2">
                    @csrf
                    <select name="status" class="h-10 rounded-xl border border-slate-200 bg-white px-3 text-xs font-bold text-slate-700 shadow-sm hover:border-slate-300 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                        @foreach(['active' => 'Active', 'sold' => 'Sold', 'hidden' => 'Hidden'] as $k => $v)
                            <option value="{{ $k }}" @selected(($listing['status'] ?? 'active') === $k)>{{ $v }}</option>
                        @endforeach
                    </select>
                    <button class="inline-flex h-10 items-center justify-center rounded-xl bg-indigo-600 px-4 text-xs font-bold text-white shadow-sm shadow-indigo-200 hover:brightness-105 active:scale-[0.99]">Update</button>
                </form>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.listings.update', ['id' => $listingId]) }}" enctype="multipart/form-data">
        @csrf
        @include('web.admin.listings._form', ['listing' => $listing, 'listingId' => $listingId])
    </form>
@endsection
