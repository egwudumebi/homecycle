<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ ($category['name'] ?? 'Category').' - Marketplace' }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        (() => {
            const root = document.documentElement;
            const getPreferredTheme = () => {
                const stored = window.localStorage.getItem('theme');
                if (stored === 'light' || stored === 'dark') return stored;
                return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            };
            const applyTheme = (theme) => {
                if (theme === 'dark') root.classList.add('dark');
                else root.classList.remove('dark');
                window.localStorage.setItem('theme', theme);
            };
            window.__hcTheme = { getPreferredTheme, applyTheme };
            applyTheme(getPreferredTheme());
        })();
    </script>
    
    <style>
        [x-cloak] { display: none !important; }
        :root { --accent: 79, 70, 229; }
        .dark { background-color: #020617; color: #f8fafc; }
        
        /* Shimmer Effect */
        .shimmer {
            background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0) 100%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
    </style>
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: 'rgb(var(--accent))',
                        surface: { light: '#ffffff', dark: '#0b1224' }
                    },
                    borderRadius: { 'xl-plus': '1.25rem', '2xl-plus': '1.5rem' }
                }
            }
        }
    </script>
</head>

<body x-data="{ 
    darkMode: (window.__hcTheme?.getPreferredTheme?.() ?? localStorage.getItem('theme')) === 'dark',
    loading: true 
}" x-init="setTimeout(() => loading = false, 800)"
class="bg-slate-50 dark:bg-slate-950 transition-colors duration-300">

    <header class="sticky top-0 z-50 border-b border-slate-200/60 dark:border-slate-800/60 bg-white/70 dark:bg-slate-900/70 backdrop-blur-xl">
        <div class="max-w-7xl mx-auto px-4 h-20 flex items-center gap-6">
            <a href="{{ route('web.home') }}" class="shrink-0">
                <img src="{{ asset('images/homecycle_dark_bg.png') }}" class="h-10 w-auto" alt="Logo">
            </a>

            <div class="flex-1 max-w-2xl">
                <form action="{{ route('web.search') }}" class="relative group">
                    <input name="q" value="{{ request('q') }}" 
                           class="w-full bg-slate-100 dark:bg-slate-800/50 border-0 rounded-2xl py-3 pl-12 pr-4 text-sm focus:ring-2 focus:ring-brand/20 transition-all"
                           placeholder="Search in {{ $category['name'] ?? 'this category' }}...">
                    <svg class="absolute left-4 top-3.5 h-5 w-5 text-slate-400 group-focus-within:text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </form>
            </div>

            <button @click="darkMode = !darkMode; (window.__hcTheme?.applyTheme ? window.__hcTheme.applyTheme(darkMode ? 'dark' : 'light') : localStorage.setItem('theme', darkMode ? 'dark' : 'light'))" 
                    class="p-3 rounded-2xl bg-white dark:bg-slate-800 shadow-sm border border-slate-200 dark:border-slate-700 hover:scale-105 transition-transform">
                <svg x-show="!darkMode" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                <svg x-show="darkMode" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path></svg>
            </button>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-8">
        <nav class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-4">
            <a href="{{ route('web.home') }}" class="hover:text-brand">Home</a>
            <span>/</span>
            <span class="text-slate-900 dark:text-slate-100">{{ $category['name'] ?? 'Category' }}</span>
        </nav>

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
            <div>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight">
                    {{ $category['name'] ?? 'Category' }}
                </h1>
                <p class="text-slate-500 mt-2 font-medium">Explore {{ count($listings ?? []) }} verified listings available today.</p>
            </div>
            <a href="{{ route('web.search', ['category_slug' => $category['slug'] ?? null]) }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-brand text-white font-bold rounded-2xl shadow-lg shadow-brand/20 hover:shadow-brand/40 hover:-translate-y-0.5 transition-all active:scale-95">
                Advanced Filters
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            </a>
        </div>

        <div class="grid lg:grid-cols-[280px_1fr] gap-10">
            <aside class="space-y-6">
                <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm">
                    <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-4 px-2">Sub Categories</h3>
                    <div class="space-y-1">
                        @foreach(($category['sub_categories'] ?? []) as $sub)
                            <a href="{{ route('web.search', ['sub_category_id' => $sub['id']]) }}" 
                               class="flex items-center justify-between px-3 py-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 group transition-colors">
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300 group-hover:text-brand">{{ $sub['name'] }}</span>
                                <svg class="w-4 h-4 text-slate-300 group-hover:text-brand opacity-0 group-hover:opacity-100 transition-all -translate-x-2 group-hover:translate-x-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            </aside>

            <section>
                <div x-show="loading" class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    <template x-for="i in 6">
                        <div class="bg-white dark:bg-slate-900 rounded-[2rem] p-4 border border-slate-200 dark:border-slate-800 animate-pulse">
                            <div class="aspect-[4/3] bg-slate-200 dark:bg-slate-800 rounded-2xl mb-4"></div>
                            <div class="h-4 bg-slate-200 dark:bg-slate-800 rounded w-2/3 mb-2"></div>
                            <div class="h-4 bg-slate-200 dark:bg-slate-800 rounded w-1/3"></div>
                        </div>
                    </template>
                </div>

                <div x-show="!loading" x-cloak class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse(($listings ?? []) as $item)
                        <a class="group bg-white dark:bg-slate-900 rounded-[2rem] p-3 border border-slate-100 dark:border-slate-800 shadow-sm hover:shadow-2xl hover:shadow-slate-200 dark:hover:shadow-none dark:hover:border-brand/50 transition-all duration-300" 
                           href="{{ route('web.listing.show', ['slug' => $item['slug']]) }}">
                            
                            <div class="relative aspect-[4/3] rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-800 mb-4">
                                @if($item['image']['url'] ?? null)
                                    <img src="{{ $item['image']['url'] }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" loading="lazy" />
                                @else
                                    <div class="flex items-center justify-center h-full text-slate-400 italic text-sm">No Preview</div>
                                @endif
                                <div class="absolute top-3 right-3 bg-white/90 dark:bg-slate-900/90 backdrop-blur px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter">Verified</div>
                            </div>

                            <div class="px-2 pb-2">
                                <h4 class="font-bold text-slate-900 dark:text-slate-100 group-hover:text-brand line-clamp-1 transition-colors">{{ $item['title'] ?? '' }}</h4>
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-lg font-black text-slate-900 dark:text-white">₦{{ number_format($item['price'] ?? 0, 0) }}</span>
                                    @php
                                        $storeName = (string) config('app.store.name', 'HomeCycle');
                                    @endphp
                                    <span class="text-[11px] font-bold text-slate-400 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h18v6H3V3zm0 8h18v10H3V11z" /></svg>
                                        Sold by {{ $storeName }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-20 bg-white dark:bg-slate-900 rounded-[3rem] border-2 border-dashed border-slate-200 dark:border-slate-800">
                            <span class="text-4xl mb-4">📦</span>
                            <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">No listings found in this category</p>
                        </div>
                    @endforelse
                </div>

                @if(($hasPrev ?? false) || ($hasNext ?? false))
                <div class="mt-12 flex items-center justify-center gap-4">
                    @if($hasPrev ?? false)
                        <a href="{{ request()->fullUrlWithQuery(['page' => $page - 1]) }}" 
                           class="px-6 py-3 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 font-bold text-sm hover:border-brand hover:text-brand transition-all">← Previous</a>
                    @endif
                    @if($hasNext ?? false)
                        <a href="{{ request()->fullUrlWithQuery(['page' => $page + 1]) }}" 
                           class="px-8 py-3 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold text-sm hover:scale-105 transition-all">Next Page →</a>
                    @endif
                </div>
                @endif
            </section>
        </div>
    </main>
</body>
</html>