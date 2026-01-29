@extends('web.admin.layout')

@section('title', 'Deliveries')
@section('admin_title', 'Track Deliveries')

@section('admin_content')
    <div class="rounded-xl border border-slate-200 bg-white p-6">
        <div class="text-lg font-semibold">Track Deliveries</div>
        <div class="mt-2 text-sm text-slate-600">This page is ready to connect to Orders/Deliveries. Once orders exist, we can track dispatch, in-transit, delivered, and failed delivery states.</div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                <div class="text-xs text-slate-600">Pending</div>
                <div class="mt-2 text-2xl font-semibold">0</div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                <div class="text-xs text-slate-600">Dispatched</div>
                <div class="mt-2 text-2xl font-semibold">0</div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                <div class="text-xs text-slate-600">In transit</div>
                <div class="mt-2 text-2xl font-semibold">0</div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                <div class="text-xs text-slate-600">Delivered</div>
                <div class="mt-2 text-2xl font-semibold">0</div>
            </div>
        </div>
    </div>
@endsection
