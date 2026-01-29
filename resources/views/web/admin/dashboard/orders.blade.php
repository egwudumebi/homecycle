@extends('web.admin.layout')

@section('title', 'Orders')
@section('admin_title', 'Orders')

@section('admin_content')
    <div class="rounded-xl border border-slate-200 bg-white p-6">
        <div class="text-lg font-semibold">Orders</div>
        <div class="mt-2 text-sm text-slate-600">This is a placeholder for an Orders module. When you introduce an orders table, we can list orders, payment status, customer info, and fulfillment here.</div>

        <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 p-5">
            <div class="text-sm font-semibold">Next step</div>
            <div class="mt-2 text-sm text-slate-600">Add an orders table (or tell me your schema) and I’ll connect this page to real data.</div>
        </div>
    </div>
@endsection
