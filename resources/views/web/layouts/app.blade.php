<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-base-url" content="{{ url('/') }}">
    <title>@yield('title', 'Marketplace') | HomeCycle</title>
    
    <link rel="icon" type="image/png" href="{{ asset('images/homecycle_favicon.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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

    <script>
        document.addEventListener('alpine:init', () => {
            if (!window.Alpine) return;
            try {
                if (!window.Alpine.store('hcCart')) {
                    window.Alpine.store('hcCart', window.Alpine.reactive({
                        open: false,
                        loading: false,
                        pending: {},
                        items: [],
                        total_items: 0,
                        subtotal: '0.00',
                        total: '0.00',
                        error: null,
                        toasts: [],
                    }));
                }
            } catch (_) {
            }
        });
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui'] },
                    colors: { brand: '#4f46e5' },
                    borderRadius: { '2xl': '1rem', '3xl': '1.5rem' }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        body { text-rendering: optimizeLegibility; -webkit-font-smoothing: antialiased; }
        
        /* Custom Scrollbar for modern browsers */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { 
            background: #e2e8f0; 
            border-radius: 10px; 
            border: 2px solid transparent;
            background-clip: content-box;
        }
        .dark ::-webkit-scrollbar-thumb { background: #1e293b; }
    </style>
</head>

<body x-data="{ 
    scrolled: false, 
    darkMode: (window.__hcTheme?.getPreferredTheme?.() ?? localStorage.getItem('theme')) === 'dark'
}" 
x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 20)"
class="min-h-screen font-sans bg-[#fbfcfd] bg-[url('/images/hc-background-light-pattern.png')] bg-repeat bg-[length:200px_200px] bg-fixed text-slate-900 selection:bg-indigo-100 selection:text-indigo-700 dark:bg-[#020617] dark:text-slate-100 transition-colors duration-300">

    <div class="fixed top-4 right-4 z-[70] space-y-2" x-data>
        <template x-for="t in ($store.hcCart ? ($store.hcCart.toasts || []) : [])" :key="t.id">
            <div class="w-[320px] max-w-[90vw] rounded-2xl border px-4 py-3 shadow-lg backdrop-blur bg-white/90 dark:bg-slate-950/90"
                 :class="t.type === 'error' ? 'border-red-200 text-red-700 dark:border-red-900/50 dark:text-red-200' : 'border-emerald-200 text-emerald-700 dark:border-emerald-900/50 dark:text-emerald-200'">
                <div class="text-xs font-black uppercase tracking-widest" x-text="t.type === 'error' ? 'Error' : 'Success'"></div>
                <div class="mt-1 text-sm font-semibold text-slate-900 dark:text-slate-100" x-text="t.message"></div>
            </div>
        </template>
    </div>

    <script>
        window.__hcAuth = {
            authenticated: @json(auth()->check()),
            routes: {
                googleRedirect: @json(route('auth.google.redirect')),
            },
        };
    </script>

    <header 
        :class="scrolled ? 'border-b border-slate-200/60 bg-white/80 dark:border-slate-800/60 dark:bg-slate-950/80 shadow-sm' : 'bg-transparent'"
        class="sticky top-0 z-50 transition-all duration-300 backdrop-blur-xl">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-20 items-center justify-between gap-8">
                
                <a href="{{ route('web.home') }}" class="shrink-0 transition hover:opacity-80">
                    <img src="{{ asset('images/homecycle_dark_bg.png') }}" alt="HomeCycle" class="h-12 sm:h-14 w-auto" />
                </a>

                <div class="hidden md:block flex-1 max-w-2xl group">
                    <x-search-bar :categories="$categories ?? []" />
                </div>

                <div class="flex items-center gap-3">
                    @if(!empty($supportedLocales))
                        <div class="hidden lg:flex items-center bg-slate-100 dark:bg-slate-900 p-1 rounded-xl">
                            @foreach($supportedLocales as $code => $meta)
                                <button 
                                    onclick="window.location.href='{{ route('web.locale.switch', ['locale' => $code]) }}'"
                                    class="px-3 py-1.5 text-[11px] font-bold uppercase rounded-lg transition-all {{ app()->getLocale() === $code ? 'bg-white shadow-sm text-indigo-600 dark:bg-slate-800 dark:text-indigo-400' : 'text-slate-500 hover:text-slate-700' }}">
                                    {{ $code }}
                                </button>
                            @endforeach
                        </div>
                    @endif

                    @guest
                        <div class="hidden sm:block">
                            <x-google-login-cta variant="banner" />
                        </div>
                    @endguest

                    @auth
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button type="button" title="Account" @click="open = !open" class="flex items-center gap-2 rounded-2xl bg-white border border-slate-200 shadow-sm p-1.5 sm:px-2 sm:py-1.5 dark:bg-slate-900 dark:border-slate-800">
                                @if(!empty(auth()->user()->avatar))
                                    <img
                                        src="{{ auth()->user()->avatar }}"
                                        alt="{{ auth()->user()->name }}"
                                        class="h-8 w-8 rounded-xl object-cover"
                                        referrerpolicy="no-referrer"
                                    />
                                @else
                                    <div class="h-8 w-8 rounded-xl bg-slate-100 dark:bg-slate-800"></div>
                                @endif
                                <div class="hidden sm:block max-w-[140px] truncate text-sm font-bold text-slate-700 dark:text-slate-200">
                                    {{ auth()->user()->name }}
                                </div>
                                <svg class="hidden sm:block h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-cloak x-show="open" x-transition class="absolute right-0 mt-2 w-56 rounded-2xl border border-slate-200 bg-white shadow-lg dark:border-slate-800 dark:bg-slate-950 overflow-hidden">
                                <a href="{{ route('auth.google.redirect', ['select_account' => 1, 'return_url' => url()->full()]) }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-900">
                                    <svg class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Switch account
                                </a>

                                <form method="POST" action="{{ route('web.logout') }}" class="border-t border-slate-100 dark:border-slate-800">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-left text-sm font-semibold text-red-700 hover:bg-red-50 dark:text-red-300 dark:hover:bg-red-950/30">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/></svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth

                    <a href="{{ route('web.orders.index') }}" data-auth-intent="orders" class="relative group p-2.5 rounded-2xl bg-white border border-slate-200 shadow-sm text-slate-600 hover:border-indigo-500 hover:text-indigo-600 dark:bg-slate-900 dark:border-slate-800 dark:text-slate-400 dark:hover:text-indigo-400 transition-all" title="My Orders">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m-6-8h6M7 20h10a2 2 0 002-2V6a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </a>

                    @if(Route::has('web.saved.index'))
                        <a href="{{ route('web.saved.index') }}" class="relative group p-2.5 rounded-2xl bg-white border border-slate-200 shadow-sm text-slate-600 hover:border-indigo-500 hover:text-indigo-600 dark:bg-slate-900 dark:border-slate-800 dark:text-slate-400 dark:hover:text-indigo-400 transition-all">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </a>
                    @endif

                    <button 
                        @click="darkMode = !darkMode; (window.__hcTheme?.applyTheme ? window.__hcTheme.applyTheme(darkMode ? 'dark' : 'light') : localStorage.setItem('theme', darkMode ? 'dark' : 'light'))" 
                        class="p-2.5 rounded-2xl bg-white border border-slate-200 shadow-sm text-slate-600 hover:rotate-12 dark:bg-slate-900 dark:border-slate-800 transition-all">
                        <svg x-show="!darkMode" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        <svg x-show="darkMode" class="h-5 w-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364-.707.707M6.343 17.657l-.707.707m12.728 0-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" /></svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 bg-white/80 backdrop-blur-sm rounded-3xl shadow-sm shadow-slate-900/5 dark:bg-transparent dark:backdrop-blur-0 dark:shadow-none">
        @yield('content')
    </main>

    <button type="button" class="fixed bottom-5 right-5 z-[55] inline-flex items-center justify-center h-14 w-14 rounded-2xl bg-slate-900 text-white shadow-xl shadow-slate-900/20 dark:bg-white dark:text-slate-900" onclick="window.hcCart && window.hcCart.toggle(true)">
        <span class="sr-only">Open cart</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m12-9l2 9M9 22a1 1 0 100-2 1 1 0 000 2zm10 0a1 1 0 100-2 1 1 0 000 2z"/></svg>
        <span class="absolute -top-2 -right-2 inline-flex h-6 min-w-[24px] items-center justify-center rounded-full bg-indigo-600 px-1.5 text-[10px] font-black text-white" x-data x-text="$store.hcCart ? ($store.hcCart.total_items || 0) : 0"></span>
    </button>

    <x-web.cart-drawer />

    <footer class="mt-20 border-t border-slate-200 dark:border-slate-800/50 bg-white/50 dark:bg-slate-950/50">
        <div class="max-w-7xl mx-auto px-4 py-16">
            <div class="flex flex-col md:flex-row justify-between items-start gap-12">
                <div class="max-w-xs">
                    <div class="text-xl font-black tracking-tighter text-indigo-600">HomeCycle</div>
                    <p class="mt-4 text-sm text-slate-500 leading-relaxed">
                        Curating the finest home electronics with a commitment to quality and sustainable recycling.
                    </p>
                </div>
                
                <div class="grid grid-cols-2 gap-16">
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-widest text-slate-900 dark:text-white mb-6">Marketplace</h4>
                        <ul class="space-y-4 text-sm text-slate-500">
                            <li><a href="#" class="hover:text-indigo-600 transition">Browse All</a></li>
                            <li><a href="#" class="hover:text-indigo-600 transition">Categories</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-widest text-slate-900 dark:text-white mb-6">Legal</h4>
                        <ul class="space-y-4 text-sm text-slate-500">
                            <li><a href="#" class="hover:text-indigo-600 transition">Privacy</a></li>
                            <li><a href="#" class="hover:text-indigo-600 transition">Terms</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="mt-16 pt-8 border-t border-slate-100 dark:border-slate-800/50 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-xs text-slate-400">&copy; {{ date('Y') }} HomeCycle Marketplace. All rights reserved.</p>
                <div class="flex gap-6">
                    <div class="h-2 w-2 rounded-full bg-slate-200 dark:bg-slate-800"></div>
                    <div class="h-2 w-2 rounded-full bg-slate-200 dark:bg-slate-800"></div>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ url('js/auth.js') }}" defer></script>
    <script src="{{ url('js/cart.js') }}" defer></script>
    @stack('scripts')
</body>
</html>