@extends('web.layouts.app')

@section('title', 'My Orders')

@section('content')
    <div id="hcOrdersRoot" class="mx-auto max-w-4xl">
        <div class="flex items-start justify-between gap-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100">My Orders</h1>
                <p class="mt-2 text-sm font-medium text-slate-500 dark:text-slate-300">Track your recent purchases and payment status.</p>
            </div>
            <a href="{{ route('web.home') }}" class="hidden sm:inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-black text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 dark:hover:bg-slate-900">Continue shopping</a>
        </div>

        <div id="hcOrdersLoading" class="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-5 text-sm font-semibold text-slate-600 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200">
            Loading orders…
        </div>

        <div id="hcOrdersError" class="mt-6 hidden rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-bold text-red-700"></div>

        <div id="hcOrdersEmpty" class="mt-8 hidden rounded-3xl border-2 border-dashed border-slate-200 bg-white p-10 text-center dark:border-slate-800 dark:bg-slate-900">
            <div class="text-lg font-black text-slate-900 dark:text-slate-100">You haven’t placed any orders yet</div>
            <div class="mt-2 text-sm font-medium text-slate-500 dark:text-slate-300">When you complete checkout, your orders will appear here.</div>
            <a href="{{ route('web.home') }}" class="mt-6 inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-black text-white hover:bg-slate-800 dark:bg-white dark:text-slate-900">Continue shopping</a>
        </div>

        <div id="hcOrdersList" class="mt-8 space-y-4"></div>

        <div id="hcOrdersPager" class="mt-8"></div>
    </div>
@endsection

@push('scripts')
    <script src="{{ url('js/orders.js') }}" defer></script>
@endpush
