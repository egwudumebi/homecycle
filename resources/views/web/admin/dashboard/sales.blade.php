@extends('web.admin.layout')

@section('title', 'Sales')
@section('admin_title', 'Sales')

@section('admin_content')
    <div class="rounded-xl border border-slate-200 bg-white p-6">
        <div class="text-lg font-semibold">Sales</div>
        <div class="mt-2 text-sm text-slate-600">This page is ready for your sales module. When you add Orders/Sales tables, we can wire real revenue charts and customer breakdowns here.</div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                <div class="text-xs text-slate-600">Today</div>
                <div class="mt-2 text-2xl font-semibold">₦0.00</div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                <div class="text-xs text-slate-600">This week</div>
                <div class="mt-2 text-2xl font-semibold">₦0.00</div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                <div class="text-xs text-slate-600">This month</div>
                <div class="mt-2 text-2xl font-semibold">₦0.00</div>
            </div>
        </div>
    </div>
@endsection
