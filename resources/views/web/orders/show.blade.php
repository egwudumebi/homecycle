@extends('web.layouts.app')

@section('title', 'Order Details')

@section('content')
    <div id="hcOrderRoot" data-order-id="{{ (int) ($orderId ?? 0) }}" class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <a href="{{ route('web.orders.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition-colors mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to orders
            </a>
            
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-slate-50">Order <span id="hcOrderNumber">#</span></h1>
                    <div class="flex flex-wrap items-center gap-3 mt-4">
                        <span id="hcOrderStatus" class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300">
                            <span class="w-2 h-2 rounded-full bg-indigo-600 dark:bg-indigo-400"></span>
                            pending
                        </span>
                        <span class="text-sm text-slate-600 dark:text-slate-400">Placed: <span id="hcOrderDate" class="font-semibold text-slate-900 dark:text-slate-200"></span></span>
                    </div>
                </div>
                <div class="text-left sm:text-right">
                    <div class="text-xs font-semibold uppercase tracking-widest text-slate-500 dark:text-slate-400">Total Amount</div>
                    <div id="hcOrderTotal" class="mt-2 text-3xl font-bold text-slate-900 dark:text-slate-50">₦0.00</div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div id="hcOrderLoading" class="rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 p-6 text-center">
            <div class="inline-flex items-center gap-3">
                <svg class="w-5 h-5 text-slate-400 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                </svg>
                <span class="text-slate-600 dark:text-slate-400 font-medium">Loading order…</span>
            </div>
        </div>

        <!-- Error State -->
        <div id="hcOrderError" class="hidden rounded-xl border border-red-200 dark:border-red-900/50 bg-red-50 dark:bg-red-950/30 p-4 text-sm font-semibold text-red-700 dark:text-red-400 flex items-start gap-3">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            <span id="hcOrderErrorMessage"></span>
        </div>

        <!-- Main Content -->
        <div id="hcOrderBody" class="mt-8 hidden space-y-6">
            <!-- Items Section -->
            <section class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <h2 class="text-sm font-bold uppercase tracking-widest text-slate-700 dark:text-slate-300">Items</h2>
                    </div>
                </div>
                <div id="hcOrderItems" class="divide-y divide-slate-100 dark:divide-slate-800"></div>
            </section>

            <!-- Two Column Grid: Payment & Tracking -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Payment Section -->
                <section class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h10m4 0a1 1 0 11-2 0 1 1 0 012 0zm0 0h.01M17 20H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2z" />
                            </svg>
                            <h2 class="text-sm font-bold uppercase tracking-widest text-slate-700 dark:text-slate-300">Payment</h2>
                        </div>
                    </div>
                    <div class="px-6 py-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                                </svg>
                                Status
                            </div>
                            <div id="hcOrderPaymentStatus" class="font-semibold text-slate-900 dark:text-slate-100">n/a</div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                Reference
                            </div>
                            <div id="hcOrderPaymentRef" class="font-semibold text-slate-900 dark:text-slate-100 text-sm"></div>
                        </div>
                    </div>
                </section>

                <!-- Tracking Section (Improved) -->
                <section class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h2 class="text-sm font-bold uppercase tracking-widest text-slate-700 dark:text-slate-300">Tracking</h2>
                        </div>
                    </div>
                    <div class="px-6 py-4">
                        <!-- Modern Stepper -->
                        <div id="hcOrderTrackingStepper" class="flex items-center justify-between mb-6">
                            <!-- Step items will be generated here -->
                        </div>
                        
                        <!-- Status Info -->
                        <div class="space-y-3">
                            <div id="hcOrderTracking" class="text-sm font-semibold text-slate-900 dark:text-slate-100"></div>
                            <div class="text-xs text-slate-600 dark:text-slate-400">
                                Estimated delivery: <span class="font-semibold text-slate-900 dark:text-slate-200">TBD</span>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Status History Section -->
            <section class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h2 class="text-sm font-bold uppercase tracking-widest text-slate-700 dark:text-slate-300">Status History</h2>
                    </div>
                </div>
                <div id="hcOrderHistory" class="divide-y divide-slate-100 dark:divide-slate-800"></div>
            </section>

            <!-- Tracking Timeline Section -->
            <section class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <h2 class="text-sm font-bold uppercase tracking-widest text-slate-700 dark:text-slate-300">Tracking Timeline</h2>
                    </div>
                </div>
                <div id="hcOrderTrackingTimeline" class="divide-y divide-slate-100 dark:divide-slate-800"></div>
            </section>
        </div>
    </div>

    <style>
        /* Stepper styling for tracking */
        #hcOrderTrackingStepper .step {
            @apply flex flex-col items-center flex-1 relative;
        }

        #hcOrderTrackingStepper .step.active .indicator {
            @apply bg-indigo-600 dark:bg-indigo-500 text-white;
        }

        #hcOrderTrackingStepper .step.completed .indicator {
            @apply bg-emerald-600 dark:bg-emerald-500 text-white;
        }

        #hcOrderTrackingStepper .step.pending .indicator {
            @apply bg-slate-200 dark:bg-slate-700 text-slate-500;
        }

        #hcOrderTrackingStepper .indicator {
            @apply w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all;
        }

        #hcOrderTrackingStepper .connector {
            @apply absolute top-5 left-[calc(50%+20px)] right-[calc(-50%-20px)] h-0.5 transition-all;
        }

        #hcOrderTrackingStepper .step.completed .connector {
            @apply bg-emerald-600 dark:bg-emerald-500;
        }

        #hcOrderTrackingStepper .step.pending .connector,
        #hcOrderTrackingStepper .step.active .connector {
            @apply bg-slate-200 dark:bg-slate-700;
        }

        #hcOrderTrackingStepper .label {
            @apply text-xs font-semibold mt-2 text-center text-slate-600 dark:text-slate-400;
        }
    </style>
@endsection

@push('scripts')
    <script src="{{ url('js/orders.js') }}" defer></script>
@endpush
