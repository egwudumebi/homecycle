<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard') | HomeCycle</title>
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

    @php
        $hasBuild = file_exists(public_path('build/manifest.json'))
            && is_dir(public_path('build/assets'))
            && count(glob(public_path('build/assets/*'))) > 0;
    @endphp
    @if($hasBuild)
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
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
                window.addEventListener('DOMContentLoaded', () => {
                    document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
                        btn.addEventListener('click', () => {
                            const next = root.classList.contains('dark') ? 'light' : 'dark';
                            (window.__hcTheme?.applyTheme ? window.__hcTheme.applyTheme(next) : applyTheme(next));
                        });
                    });
                });
            })();
        </script>
    @endif

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .nav-link-active { background-color: rgb(238 242 255); color: rgb(67 56 202); box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05); box-sizing: border-box; outline: 1px solid rgb(199 210 254 / 0.5); outline-offset: 0; }
        .nav-link-inactive { color: rgb(71 85 105); }
        .nav-link-inactive:hover { background-color: rgb(248 250 252); color: rgb(15 23 42); }

        @media (max-width: 1023px) {
            .sidebar-closed { transform: translateX(-100%); }
            .sidebar-open { transform: translateX(0); }
        }
    </style>
</head>
<body class="h-full bg-slate-50 bg-[url('/images/hc-background-light-pattern.png')] bg-repeat bg-[length:200px_200px] bg-fixed text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100">

<div class="flex min-h-screen overflow-hidden">
    <div id="adminOverlay" class="fixed inset-0 z-40 hidden bg-slate-900/60 backdrop-blur-sm transition-opacity lg:hidden"></div>

    <aside id="adminDrawer" class="sidebar-closed fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-slate-200 bg-white transition-transform duration-300 ease-in-out lg:static lg:flex lg:transform-none">
        <div class="flex h-16 items-center justify-between border-b border-slate-100 px-6">
            <a href="{{ route('admin.overview') }}" class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-lg shadow-indigo-200">
                    <span class="text-sm font-bold">HC</span>
                </div>
                <div>
                    <span class="block text-sm font-bold tracking-tight text-slate-900">HomeCycle</span>
                    <span class="block text-[10px] font-semibold uppercase tracking-wider text-indigo-500">Core Admin</span>
                </div>
            </a>
            
            <button id="closeDrawerBtn" class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 lg:hidden">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto p-4 space-y-8">
            <div>
                <h3 class="px-4 text-[11px] font-bold uppercase tracking-[0.15em] text-slate-400">Main Menu</h3>
                <div class="mt-4 space-y-1">
                    @php
                        $navItems = [
                            ['route' => 'admin.overview', 'label' => 'Overview', 'icon' => 'M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 12a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1v-7z'],
                            ['route' => 'admin.products', 'label' => 'Products', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                            ['route' => 'admin.sales', 'label' => 'Sales', 'icon' => 'M7 12l3-3 3 2 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z'],
                            ['route' => 'admin.orders', 'label' => 'Orders', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                            ['route' => 'admin.deliveries', 'label' => 'Logistics', 'icon' => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8h4l3 3V16a1 1 0 01-1 1h-1'],
                        ];
                    @endphp

                    @foreach($navItems as $item)
                    <a href="{{ route($item['route']) }}" 
                       class="group flex items-center gap-3 rounded-xl px-4 py-2.5 text-sm font-medium transition-all {{ request()->routeIs($item['route']) ? 'nav-link-active' : 'nav-link-inactive' }}">
                        <svg class="h-5 w-5 {{ request()->routeIs($item['route']) ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                        </svg>
                        {{ $item['label'] }}
                    </a>
                    @endforeach
                </div>
            </div>

            <div class="mt-auto pt-4 border-t border-slate-100">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-8 w-8 rounded-lg bg-indigo-600 flex items-center justify-center font-bold text-white text-[10px]">
                            {{ substr($apiUser['name'] ?? 'AD', 0, 2) }}
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-xs font-bold text-slate-900">{{ $apiUser['name'] ?? 'Admin' }}</p>
                            <p class="truncate text-[10px] text-slate-500 font-medium">Administrator</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="w-full rounded-lg bg-white border border-slate-200 py-2 text-[11px] font-bold text-red-600 hover:bg-red-50 hover:border-red-200 transition-all active:scale-[0.98]">
                            SIGN OUT
                        </button>
                    </form>
                </div>
            </div>
        </nav>
    </aside>

    <div class="flex flex-1 flex-col min-w-0">
        <header class="h-16 border-b border-slate-200 bg-white/80 backdrop-blur-md sticky top-0 z-30 flex items-center justify-between px-4 lg:px-8 dark:border-slate-800 dark:bg-slate-950/80">
            <div class="flex items-center gap-4">
                <button id="adminMenuBtn" class="flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 lg:hidden">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <div class="min-w-0">
                    <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest lg:hidden">Management</p>
                    <h1 class="text-base lg:text-lg font-bold text-slate-900 truncate">@yield('admin_title')</h1>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                @php
                    $supportedLocales = (array) config('app.supported_locales', []);
                    $currentLocale = app()->getLocale();
                @endphp

                @if(!empty($supportedLocales))
                    <select class="hidden sm:block h-10 rounded-xl border border-slate-300 bg-white px-3 text-xs font-bold text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100"
                            onchange="window.location.href='{{ route('web.locale.switch', ['locale' => '__LOCALE__']) }}'.replace('__LOCALE__', this.value)">
                        @foreach($supportedLocales as $code => $meta)
                            <option value="{{ $code }}" @selected($currentLocale === $code)>{{ $meta['name'] ?? strtoupper($code) }}</option>
                        @endforeach
                    </select>
                @endif

                <button type="button" data-theme-toggle class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" /></svg>
                </button>

                <div class="hidden sm:flex items-center gap-2 px-3 py-1 rounded-full bg-green-50 border border-green-100">
                    <span class="h-1.5 w-1.5 rounded-full bg-green-500 animate-pulse"></span>
                    <span class="text-[10px] font-bold text-green-700 uppercase">System Live</span>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 lg:p-8">
            <div class="max-w-7xl mx-auto">
                @yield('admin_content')
            </div>
        </main>
    </div>
</div>

<script>
    const adminMenuBtn = document.getElementById('adminMenuBtn');
    const closeDrawerBtn = document.getElementById('closeDrawerBtn');
    const adminDrawer = document.getElementById('adminDrawer');
    const adminOverlay = document.getElementById('adminOverlay');

    function openMenu() {
        adminDrawer.classList.replace('sidebar-closed', 'sidebar-open');
        adminOverlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; 
    }

    function closeMenu() {
        adminDrawer.classList.replace('sidebar-open', 'sidebar-closed');
        adminOverlay.classList.add('hidden');
        document.body.style.overflow = ''; 
    }

    adminMenuBtn.addEventListener('click', openMenu);
    closeDrawerBtn.addEventListener('click', closeMenu);
    adminOverlay.addEventListener('click', closeMenu);

    adminDrawer.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 1024) closeMenu();
        });
    });
</script>
</body>
</html>