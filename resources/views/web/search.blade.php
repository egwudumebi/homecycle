@extends('web.layouts.app')

@section('title', 'Search Marketplace')

@section('content')
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-4xl font-black tracking-tight text-slate-900 dark:text-white">Search Results</h1>
            <p class="mt-2 text-sm font-medium text-slate-500 dark:text-slate-400">
                @if(!empty($filters['q']))
                    Found results for <span class="px-2 py-0.5 rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 italic">"{{ $filters['q'] }}"</span>
                @else
                    Exploring all available treasures
                @endif
            </p>
        </div>
        <div class="flex items-center gap-2 text-xs font-bold text-slate-400 uppercase tracking-widest">
            <span>Sort by:</span>
            <select class="bg-transparent border-none text-slate-900 dark:text-slate-100 focus:ring-0 cursor-pointer">
                <option>Newest First</option>
                <option>Price: Low to High</option>
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-10 lg:grid-cols-12">
        <aside class="lg:col-span-3">
            <div class="sticky top-24">
                <form method="GET" action="{{ route('web.search') }}" id="filterForm" 
                      x-data="filterComponent()" 
                      class="space-y-6 rounded-[2.5rem] border border-slate-200 bg-white p-8 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    
                    <div class="space-y-3">
                        <label class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">Keyword</label>
                        <div class="relative group">
                            <input name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search..." 
                                   class="w-full rounded-2xl border-slate-100 bg-slate-50 py-3.5 pl-4 pr-10 text-sm focus:bg-white focus:ring-4 focus:ring-indigo-500/5 transition-all dark:bg-slate-950 dark:border-slate-800" />
                            <svg class="absolute right-4 top-3.5 h-5 w-5 text-slate-300 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">Categories</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(($categories ?? []) as $cat)
                                <label class="cursor-pointer group">
                                    <input type="checkbox" name="categories[]" value="{{ $cat['slug'] }}" class="hidden peer" @checked(in_array($cat['slug'], (array)($filters['categories'] ?? [])))>
                                    <span class="inline-block px-4 py-2 rounded-xl border border-slate-100 bg-slate-50 text-xs font-bold text-slate-500 transition-all peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 dark:bg-slate-950 dark:border-slate-800 dark:peer-checked:bg-indigo-500">
                                        {{ $cat['name'] }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- NEW PRICE UI START --}}
                    <div class="space-y-4">
                        <label class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-400">Quick Price</label>
                        
                        <div class="grid grid-cols-2 gap-2">
                            <template x-for="preset in presets">
                                <button type="button" 
                                        @click="setPreset(preset.min, preset.max)"
                                        :class="(minPrice == preset.min && maxPrice == preset.max) ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-slate-50 text-slate-500 border-slate-100 dark:bg-slate-950 dark:border-slate-800'"
                                        class="py-2.5 px-3 rounded-xl border text-[10px] font-bold transition-all hover:border-indigo-300">
                                    <span x-text="preset.label"></span>
                                </button>
                            </template>
                        </div>

                        <div class="flex items-center gap-3 mt-4">
                            <div class="flex-1 relative">
                                <span class="absolute left-3 top-2.5 text-slate-400 text-[10px] font-bold">MIN</span>
                                <input type="number" name="min_price" x-model="minPrice" class="w-full pl-10 pr-3 py-2.5 rounded-xl border-slate-100 bg-slate-50 text-xs font-bold dark:bg-slate-950 dark:border-slate-800 focus:ring-2 focus:ring-indigo-500/20">
                            </div>
                            <div class="flex-1 relative">
                                <span class="absolute left-3 top-2.5 text-slate-400 text-[10px] font-bold">MAX</span>
                                <input type="number" name="max_price" x-model="maxPrice" class="w-full pl-10 pr-3 py-2.5 rounded-xl border-slate-100 bg-slate-50 text-xs font-bold dark:bg-slate-950 dark:border-slate-800 focus:ring-2 focus:ring-indigo-500/20">
                            </div>
                        </div>
                    </div>
                    {{-- NEW PRICE UI END --}}

                    <button type="submit" class="w-full rounded-2xl bg-indigo-600 py-4 text-sm font-black text-white shadow-xl shadow-indigo-200 transition-all hover:bg-indigo-700 hover:-translate-y-0.5 active:scale-95 dark:shadow-none">
                        Apply Filters
                    </button>
                    
                    @if(count(array_filter($filters ?? [])) > 0)
                        <a href="{{ route('web.search') }}" class="block text-center text-[10px] font-black uppercase tracking-[0.15em] text-slate-400 hover:text-red-500 transition-colors">
                            Reset Filters
                        </a>
                    @endif
                </form>
            </div>
        </aside>

        <section class="lg:col-span-9">
            <div class="mb-6 flex flex-wrap gap-2">
                @foreach(request()->except('page') as $key => $value)
                    @if($value)
                        @foreach((is_array($value) ? $value : [$value]) as $val)
                            <span class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-xs font-bold text-slate-600 border border-slate-200 dark:bg-slate-900 dark:border-slate-800 dark:text-slate-300">
                                {{ $val }}
                                <a href="#" class="text-slate-300 hover:text-red-500 transition-colors">×</a>
                            </span>
                        @endforeach
                    @endif
                @endforeach
            </div>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 xl:grid-cols-3">
                @forelse(($listings ?? []) as $item)
                    <x-listing-card :item="$item" />
                @empty
                @endforelse
            </div>

            <div class="mt-16">
                {{ $listings->links() }}
            </div>
        </section>
    </div>
@endsection

@push('scripts')
<script>
    function filterComponent() {
        return {
            minPrice: {{ $filters['min_price'] ?? 0 }},
            maxPrice: {{ $filters['max_price'] ?? 10000 }},
            presets: [
                { label: 'Under ₦1k', min: 0, max: 1000 },
                { label: '₦1k - ₦5k', min: 1000, max: 5000 },
                { label: '₦5k - ₦10k', min: 5000, max: 10000 },
                { label: 'Any Price', min: 0, max: 100000 },
            ],
            setPreset(min, max) {
                this.minPrice = min;
                this.maxPrice = max;
            }
        }
    }
</script>
@endpush