@extends('web.layouts.app')

@section('title', ($listing['title'] ?? 'Listing').' - Marketplace')

@section('content')
    @if(!$listing)
        <div class="flex min-h-[400px] flex-col items-center justify-center rounded-3xl border border-slate-200 bg-white p-12 text-center shadow-sm">
            <div class="rounded-full bg-slate-50 p-4">
                <svg class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <h2 class="mt-4 text-lg font-bold text-slate-900 dark:text-slate-100">Listing not found</h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-300">The item you're looking for might have been sold or removed.</p>
            <a href="{{ route('web.search') }}" class="mt-6 text-sm font-bold text-indigo-600 hover:text-indigo-700">Back to Marketplace</a>
        </div>
    @else
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            
            <section class="lg:col-span-8 space-y-6">
                
                <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    @php
                        $images = $listing['images'] ?? [];
                        if ($images instanceof \Illuminate\Support\Collection) {
                            $images = $images->values()->all();
                        } elseif (is_object($images) && method_exists($images, 'toArray')) {
                            $images = $images->toArray();
                        }
                    @endphp
                    
                    @if(count($images) > 0)
                        <div class="sm:hidden">
                            <div class="p-2">
                                <img src="{{ $images[0]['url'] }}" class="h-[300px] w-full rounded-2xl object-cover" alt="{{ $listing['title'] }}" />
                            </div>
                            @if(count($images) > 1)
                                <div class="flex gap-2 overflow-x-auto px-2 pb-2">
                                    @foreach($images as $img)
                                        <img src="{{ $img['url'] }}" class="h-20 w-20 flex-none rounded-2xl object-cover" alt="" />
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="hidden sm:grid grid-cols-4 grid-rows-2 gap-2 p-2 h-[300px] sm:h-[450px]">
                            <div class="col-span-4 row-span-2 sm:col-span-3 sm:row-span-2 overflow-hidden rounded-2xl">
                                <img src="{{ $images[0]['url'] }}" class="h-full w-full object-cover transition hover:scale-105 duration-500" alt="{{ $listing['title'] }}" />
                            </div>
                            @foreach(array_slice($images, 1, 2) as $img)
                                <div class="col-span-1 row-span-1 overflow-hidden rounded-2xl">
                                    <img src="{{ $img['url'] }}" class="h-full w-full object-cover transition hover:scale-105" alt="" />
                                </div>
                            @endforeach
                            @if(count($images) > 3)
                                <div class="flex relative col-span-1 row-span-1 overflow-hidden rounded-2xl items-center justify-center bg-slate-900 dark:bg-slate-800">
                                    <img src="{{ $images[3]['url'] }}" class="absolute inset-0 h-full w-full object-cover opacity-40" alt="" />
                                    <span class="relative z-10 text-sm font-bold text-white">+{{ count($images) - 3 }} more</span>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="flex aspect-video items-center justify-center bg-slate-50 dark:bg-slate-800">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="mt-2 text-sm font-medium text-slate-400 dark:text-slate-300">No images available</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 border-b border-slate-100 pb-6">
                        <div>
                            <nav class="flex mb-3 text-[10px] font-bold uppercase tracking-widest text-indigo-600">
                                <span>{{ $listing['category']['category']['name'] ?? 'General' }}</span>
                                <span class="mx-2 text-slate-300">/</span>
                                <span class="text-slate-500 dark:text-slate-300">{{ $listing['category']['sub_category']['name'] ?? 'Other' }}</span>
                            </nav>
                            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight dark:text-slate-100">{{ $listing['title'] }}</h1>
                            <div class="mt-2 flex items-center gap-1.5 text-sm font-medium text-slate-500 dark:text-slate-300">
                                @php
                                    $storeName = (string) ($listing['seller']['name'] ?? config('app.store.name', 'HomeCycle'));
                                @endphp
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v6H3V3zm0 8h18v10H3V11z" /></svg>
                                Sold & shipped by {{ $storeName }}
                            </div>
                        </div>
                        <div class="text-left sm:text-right">
                            <div class="text-3xl font-black text-slate-900 dark:text-slate-100">₦{{ number_format((float) ($listing['price'] ?? 0), 2) }}</div>
                            <div class="mt-2 inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-[10px] font-bold uppercase tracking-wide text-emerald-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                {{ $listing['status'] ?? 'active' }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-900 dark:text-slate-100">Description</h3>
                        <div class="mt-4 text-base leading-relaxed text-slate-600 whitespace-pre-line dark:text-slate-300">
                            {{ $listing['description'] ?? 'No description provided.' }}
                        </div>
                    </div>
                </div>

                <div id="hcListingReviewsRoot" data-listing-id="{{ (int) ($listing['id'] ?? 0) }}" class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="min-w-0">
                        <div class="text-xs font-black uppercase tracking-widest text-slate-500">Reviews</div>
                        <div class="mt-2 flex items-end gap-4">
                            <div id="hcReviewsAvg" class="text-4xl font-black text-slate-900 dark:text-slate-100">{{ number_format((float) ($listing['avg_rating'] ?? 0), 1) }}</div>
                            <div class="pb-1">
                                <div id="hcReviewsStars" class="flex items-center gap-1"></div>
                                <div id="hcReviewsCount" class="mt-1 text-xs font-semibold text-slate-500">{{ (int) ($listing['reviews_count'] ?? 0) }} reviews</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-12">
                        <div class="lg:col-span-4">
                            <div id="hcReviewsBreakdown" class="space-y-2"></div>
                        </div>
                        <div class="lg:col-span-8">
                            <div id="hcReviewsError" class="hidden rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-700"></div>
                            <div id="hcReviewsEmpty" class="hidden rounded-2xl border border-slate-200 bg-white p-6 text-sm font-semibold text-slate-500 dark:border-slate-800 dark:bg-slate-950">No reviews yet.</div>
                            <div id="hcReviewsList" class="space-y-4"></div>
                            <div id="hcReviewsPager" class="mt-6 flex items-center justify-between"></div>
                        </div>
                    </div>
                </div>
            </section>

            <aside class="lg:col-span-4">
                <div class="sticky top-8 space-y-6">
                    
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <button type="button" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-indigo-600 px-4 py-3.5 text-sm font-black text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700 active:scale-95 dark:shadow-none" onclick="if (!window.hcCart || (window.hcCart.isPending && window.hcCart.isPending('add:{{ (int) ($listing['id'] ?? 0) }}'))) return; window.hcCart.add({{ (int) ($listing['id'] ?? 0) }}, 1).catch(() => {})">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m13-9l2 9m-5-4a1 1 0 100 2 1 1 0 000-2zM9 20a1 1 0 100 2 1 1 0 000-2z" />
                            </svg>
                            Add to cart
                        </button>
                    </div>

                    @php
                        $storeName = (string) config('app.store.name');
                        $phone = (string) config('app.store.phone');
                        $wa = (string) config('app.store.whatsapp_phone');
                    @endphp

                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950">
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 rounded-2xl bg-white flex items-center justify-center text-lg font-black text-slate-500 ring-1 ring-slate-200 dark:bg-slate-900 dark:text-slate-200 dark:ring-slate-800">
                                {{ strtoupper(substr($storeName ?: 'S', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-black text-slate-900 dark:text-slate-100">{{ $storeName }}</div>
                                <div class="mt-0.5 text-xs font-semibold text-slate-500 dark:text-slate-300">Platform-owned inventory</div>
                            </div>
                        </div>

                        <div class="mt-5 grid gap-3">
                            @if($phone)
                                <a href="tel:{{ $phone }}" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-900 px-4 py-3.5 text-sm font-black text-white shadow-sm transition hover:bg-slate-800 active:scale-95 dark:bg-white dark:text-slate-900 dark:hover:bg-slate-200">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    Call Us
                                </a>
                            @endif

                            @if($wa)
                                <a target="_blank" href="https://wa.me/{{ preg_replace('/\D+/', '', $wa) }}" class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-4 py-3.5 text-sm font-black text-white shadow-sm transition hover:bg-emerald-700 active:scale-95">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.72.94 3.659 1.437 5.634 1.437h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    WhatsApp Store
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="rounded-3xl bg-indigo-50 p-6 border border-indigo-100 dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <h4 class="text-sm font-bold text-indigo-900 dark:text-slate-100">Safety Tips</h4>
                    </div>
                    <ul class="text-xs space-y-2 text-indigo-700 font-medium dark:text-slate-300">
                        <li class="flex items-start gap-2"><span>•</span> Inspect the item on delivery or pickup.</li>
                        <li class="flex items-start gap-2"><span>•</span> Keep your order details for warranty/support.</li>
                        <li class="flex items-start gap-2"><span>•</span> Confirm delivery fees and timelines before payment.</li>
                    </ul>
                </div>

                <a href="{{ route('web.search', ['category_slug' => $listing['category']['category']['slug'] ?? null]) }}" class="flex items-center justify-center gap-2 text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors">
                    View more in {{ $listing['category']['category']['name'] ?? 'Category' }}
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script src="{{ url('js/reviews.js') }}" defer></script>
@endpush