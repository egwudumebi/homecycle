@extends('web.admin.layout')

@section('title', 'Saved Listings')
@section('admin_title', 'Saved')

@section('admin_content')
    <div class="rounded-xl border border-slate-200 bg-white p-6">
        <div class="text-lg font-semibold">Saved Listings</div>
        <div class="mt-2 text-sm text-slate-600">This section will show which products users save the most. We can expand it into a full analytics report.</div>

        <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 p-5">
            <div class="text-sm font-semibold">Tip</div>
            <div class="mt-2 text-sm text-slate-600">If you want, I can implement “Most saved listings” and “Top savers” rankings here using the existing <code>saved_listings</code> table.</div>
        </div>
    </div>
@endsection
