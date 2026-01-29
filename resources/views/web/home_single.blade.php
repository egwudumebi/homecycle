<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home Electronics Store</title>
    <link rel="icon" type="image/png" href="{{ asset('images/homecycle_favicon.png') }}">
    <style>
        :root{--bg:#f8fafc;--card:#fff;--text:#0f172a;--muted:#64748b;--border:#e2e8f0;--shadow:0 10px 30px rgba(15,23,42,.08);--shadow-sm:0 1px 2px rgba(15,23,42,.06);--indigo:#4f46e5;--indigo-2:#4338ca;--hero:#0b1220}
        html.dark{--bg:#020617;--card:#0b1220;--text:#e2e8f0;--muted:#94a3b8;--border:rgba(148,163,184,.18);--shadow:0 10px 30px rgba(0,0,0,.35);--shadow-sm:0 1px 2px rgba(0,0,0,.35)}
        *{box-sizing:border-box}
        body{margin:0;font-family:ui-sans-serif,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,"Apple Color Emoji","Segoe UI Emoji";background:var(--bg);color:var(--text)}
        a{color:inherit;text-decoration:none}
        .header{position:sticky;top:0;z-index:50;backdrop-filter:blur(10px);background:rgba(255,255,255,.9);border-bottom:1px solid var(--border)}
        html.dark .header{background:rgba(2,6,23,.85)}
        .header-row{display:flex;align-items:center;gap:12px;padding:14px 16px}
        .logo img{height:72px;width:auto;display:block}
        .grow{flex:1}
        .btn-icon{height:40px;width:40px;border-radius:12px;border:1px solid var(--border);background:var(--card);display:inline-flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:var(--shadow-sm)}
        .search{display:flex;gap:10px;align-items:center;background:var(--card);border:1px solid var(--border);border-radius:14px;padding:10px 12px;box-shadow:var(--shadow-sm)}
        .search input{width:100%;border:none;outline:none;font-size:14px;color:var(--text);background:transparent}
        .search button{border:none;background:var(--text);color:#fff;font-weight:700;font-size:13px;padding:10px 14px;border-radius:12px;cursor:pointer}
        html.dark .search button{background:#e2e8f0;color:#0f172a}
        .main{padding:24px 16px 48px}
        .hero{position:relative;overflow:hidden;border-radius:26px;background:linear-gradient(135deg,#0b1220 0%,#0b1730 70%,#0b1220 100%);padding:28px;box-shadow:var(--shadow)}
        .hero h1{margin:0;color:#fff;font-size:34px;line-height:1.05;letter-spacing:-.02em}
        .hero h1 span{color:#818cf8}
        .hero p{margin:14px 0 0;color:#cbd5e1;max-width:720px}
        .hero-search{margin-top:20px;max-width:520px}
        .bubble{position:absolute;width:280px;height:280px;border-radius:999px;filter:blur(40px);opacity:.6}
        .b1{right:-110px;top:-120px;background:rgba(99,102,241,.35)}
        .b2{left:-130px;bottom:-130px;background:rgba(59,130,246,.22)}
        .section-head{display:flex;align-items:end;justify-content:space-between;gap:12px;border-bottom:1px solid var(--border);padding-bottom:14px;margin-top:34px}
        .section-head h2{margin:0;font-size:22px;letter-spacing:-.01em}
        .section-head p{margin:6px 0 0;color:var(--muted);font-size:13px}
        .section-head a{color:var(--muted);font-weight:800;font-size:13px}
        .section-head a:hover{color:var(--indigo)}
        .kicker{font-size:11px;font-weight:900;letter-spacing:.18em;color:var(--muted);text-transform:uppercase}
        .pill-row{display:flex;gap:10px;overflow-x:auto;padding:14px 2px 4px}
        .pill-row::-webkit-scrollbar{display:none}
        .pill{flex:0 0 auto;display:inline-flex;align-items:center;gap:8px;border-radius:999px;border:1px solid var(--border);background:var(--card);padding:10px 14px;box-shadow:var(--shadow-sm)}
        .pill strong{font-size:13px}
        .grid{display:grid;grid-template-columns:repeat(1,minmax(0,1fr));gap:14px;margin-top:18px}
        @media(min-width:640px){.grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media(min-width:1024px){.grid{grid-template-columns:repeat(4,minmax(0,1fr))}}
        .card{display:block;overflow:hidden;border-radius:18px;border:1px solid var(--border);background:var(--card);box-shadow:var(--shadow-sm);transition:transform .15s ease,box-shadow .15s ease}
        .card:hover{transform:translateY(-2px);box-shadow:var(--shadow)}
        .media{aspect-ratio:4/3;background:rgba(148,163,184,.25)}
        .media img{width:100%;height:100%;object-fit:cover;display:block}
        .body{padding:14px}
        .title{font-weight:800;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .meta{margin-top:10px;display:flex;justify-content:space-between;gap:10px;align-items:baseline}
        .price{font-weight:900}
        .loc{font-size:12px;color:var(--muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .empty{grid-column:1/-1;border:2px dashed var(--border);border-radius:22px;padding:46px 16px;text-align:center;color:var(--muted);font-weight:900;letter-spacing:.14em;text-transform:uppercase}
        .footer{border-top:1px solid var(--border);background:var(--card);padding:22px 16px;margin-top:40px}
        .footer-row{display:flex;justify-content:space-between;gap:18px;flex-wrap:wrap}
        .footer small{color:var(--muted)}
    </style>
    <script>
        (()=>{const stored=localStorage.getItem('theme');const theme=stored==='light'||stored==='dark'?stored:(window.matchMedia&&window.matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light');if(theme==='dark')document.documentElement.classList.add('dark');})();
        window.addEventListener('DOMContentLoaded',()=>{document.querySelectorAll('[data-theme-toggle]').forEach(btn=>{btn.addEventListener('click',()=>{const root=document.documentElement;const next=root.classList.contains('dark')?'light':'dark';if(next==='dark')root.classList.add('dark');else root.classList.remove('dark');localStorage.setItem('theme',next);});});});
    </script>
</head>
<body>
<header class="header">
        <div class="header-row">
            <a class="logo" href="{{ route('web.home') }}">
                <img src="{{ asset('images/homecycle_dark_bg.png') }}" alt="HomeCycle" />
            </a>
            <div class="grow">
                <form action="{{ route('web.search') }}" method="GET">
                    <div class="search">
                        <input name="q" value="{{ request('q') }}" placeholder="{{ __('ui.search_placeholder') }}" />
                        <button type="submit">{{ __('ui.search') }}</button>
                    </div>
                </form>
            </div>
            <button type="button" class="btn-icon" data-theme-toggle aria-label="Toggle theme">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364-.707.707M6.343 17.657l-.707.707m12.728 0-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"></path>
                </svg>
            </button>
        </div>
</header>

<main class="main">
        <section class="hero">
            <div class="bubble b1"></div>
            <div class="bubble b2"></div>
            <div style="position:relative;z-index:1;">
                <h1>Shop home electronics with <span>confidence.</span></h1>
                <p>Explore our curated catalog of home appliances, electronics, smart home devices, and electrical household equipment.</p>
                <div class="hero-search">
                    <form action="{{ route('web.search') }}" method="GET">
                        <div class="search">
                            <input name="q" placeholder="What are you looking for?" />
                            <button type="submit">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <section>
            <div class="section-head">
                <div>
                    <h2>{{ __('ui.categories') }}</h2>
                    <p>Browse by category</p>
                </div>
                <a href="{{ route('web.search') }}">View all</a>
            </div>
            <div class="pill-row">
                @forelse(($categories ?? []) as $cat)
                    <a class="pill" href="{{ route('web.search', ['category' => $cat['slug']]) }}">
                        <span>{{ $cat['name'] }}</span>
                        <strong>{{ $cat['listings_count'] ?? 0 }}</strong>
                    </a>
                @empty
                    <span style="color:var(--muted);font-size:13px;">No categories available</span>
                @endforelse
            </div>
        </section>

        <section>
            <div class="section-head">
                <div>
                    <h2>{{ __('ui.featured_products') }}</h2>
                    <p>Handpicked home electronics</p>
                </div>
                <a href="{{ route('web.search') }}">View all</a>
            </div>
            <div class="grid">
                @forelse(($featured ?? []) as $item)
                    @php
                        $img = ($item['images'][0]['url'] ?? null);
                        $title = $item['title'] ?? '';
                        $price = (float) ($item['price'] ?? 0);
                        $storeName = (string) config('app.store.name', 'HomeCycle');
                    @endphp
                    <a class="card" href="{{ route('web.listing.show', ['slug' => $item['slug']]) }}">
                        <div class="media">
                            @if($img)
                                <img src="{{ $img }}" alt="" loading="lazy" />
                            @else
                                <div style="display:flex;align-items:center;justify-content:center;height:100%;color:var(--muted);font-size:13px;">No image</div>
                            @endif
                        </div>
                        <div class="body">
                            <div class="title">{{ $title }}</div>
                            <div class="meta">
                                <div class="price">₦{{ number_format($price, 2) }}</div>
                                <div class="loc">Sold by {{ $storeName }}</div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="empty" style="letter-spacing:0; text-transform:none; font-weight:700;">No items available right now. Please check back soon.</div>
                @endforelse
            </div>
        </section>

        <section>
            <div class="section-head">
                <div>
                    <h2>{{ __('ui.latest_products') }}</h2>
                    <p>Fresh arrivals</p>
                </div>
                <a href="{{ route('web.search') }}">View all</a>
            </div>
            <div class="grid">
                @forelse(($latest ?? []) as $item)
                    @php
                        $img = ($item['images'][0]['url'] ?? null);
                        $title = $item['title'] ?? '';
                        $price = (float) ($item['price'] ?? 0);
                        $storeName = (string) config('app.store.name', 'HomeCycle');
                    @endphp
                    <a class="card" href="{{ route('web.listing.show', ['slug' => $item['slug']]) }}">
                        <div class="media">
                            @if($img)
                                <img src="{{ $img }}" alt="" loading="lazy" />
                            @else
                                <div style="display:flex;align-items:center;justify-content:center;height:100%;color:var(--muted);font-size:13px;">No image</div>
                            @endif
                        </div>
                        <div class="body">
                            <div class="title">{{ $title }}</div>
                            <div class="meta">
                                <div class="price">₦{{ number_format($price, 2) }}</div>
                                <div class="loc">Sold by {{ $storeName }}</div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="empty" style="letter-spacing:0; text-transform:none; font-weight:700;">No items available right now. Please check back soon.</div>
                @endforelse
            </div>
        </section>

        <footer class="footer">
            <div class="footer-row">
                <div>
                    <div style="font-weight:900;letter-spacing:-.02em;">HomeCycle</div>
                    <small>{{ __('ui.footer_tagline') }}</small>
                </div>
                <div style="text-align:right;">
                    <div style="font-weight:800;">{{ __('ui.marketplace') }}</div>
                    <small>© {{ date('Y') }} HomeCycle</small>
                </div>
            </div>
        </footer>
</main>
</body>
</html>
