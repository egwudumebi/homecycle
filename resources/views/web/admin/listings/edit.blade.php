@extends('web.admin.layout')

@section('title', 'Edit Listing')
@section('admin_title', 'Edit Listing')

@section('admin_content')
    <div class="mb-4 flex items-center justify-between">
        <div class="text-sm text-slate-600">ID: {{ $listingId }}</div>

        <form method="POST" action="{{ route('admin.listings.status', ['id' => $listingId]) }}" class="flex items-center gap-2">
            @csrf
            <select name="status" class="rounded-lg border border-slate-300 px-3 py-2 text-sm">
                @foreach(['active' => 'Active', 'sold' => 'Sold', 'hidden' => 'Hidden'] as $k => $v)
                    <option value="{{ $k }}" @selected(($listing['status'] ?? 'active') === $k)>{{ $v }}</option>
                @endforeach
            </select>
            <button class="rounded-lg border border-slate-300 px-3 py-2 text-sm hover:bg-slate-50">Update Status</button>
        </form>
    </div>

    <form method="POST" action="{{ route('admin.listings.update', ['id' => $listingId]) }}" enctype="multipart/form-data">
        @csrf
        @include('web.admin.listings._form', ['listing' => $listing, 'listingId' => $listingId])
    </form>
@endsection
