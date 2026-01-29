<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Search Marketplace</title>
    <link rel="icon" type="image/png" href="{{ asset('images/homecycle_favicon.png') }}">
    <style>
        :root{--bg:#f8fafc;--card:#fff;--text:#0f172a;--muted:#64748b;--border:#e2e8f0;--shadow:0 10px 30px rgba(15,23,42,.08);--shadow-sm:0 1px 2px rgba(15,23,42,.06);--indigo:#4f46e5;--danger:#dc2626}
        html.dark{--bg:#020617;--card:#0b1220;--text:#e2e8f0;--muted:#94a3b8;--border:rgba(148,163,184,.18);--shadow:0 10px 30px rgba(0,0,0,.35);--shadow-sm:0 1px 2px rgba(0,0,0,.35)}
        *{box-sizing:border-box}
        body{margin:0;font-family:ui-sans-serif,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,"Apple Color Emoji","Segoe UI Emoji";background:var(--bg);color:var(--text)}
        a{color:inherit;text-decoration:none}
        .header{position:sticky;top:0;z-index:50;backdrop-filter:blur(10px);background:rgba(255,255,255,.9);border-bottom:1px solid var(--border)}
        html.dark .header{background:rgba(2,6,23,.85)}
        .header-row{display:flex;align-items:center;gap:12px;padding:14px 16px}
        .logo img{height:64px;width:auto;display:block}
        .grow{flex:1}
        .btn-icon{height:40px;width:40px;border-radius:12px;border:1px solid var(--border);background:var(--card);display:inline-flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:var(--shadow-sm)}
        .search{display:flex;gap:10px;align-items:center;background:var(--card);border:1px solid var(--border);border-radius:14px;padding:10px 12px;box-shadow:var(--shadow-sm)}
        .search input{width:100%;border:none;outline:none;font-size:14px;color:var(--text);background:transparent}
        .search button{border:none;background:var(--text);color:#fff;font-weight:800;font-size:13px;padding:10px 14px;border-radius:12px;cursor:pointer}
        html.dark .search button{background:#e2e8f0;color:#0f172a}
        .main{padding:22px 16px 44px}
        .title{margin:0;font-size:28px;letter-spacing:-.02em}
        .subtitle{margin:6px 0 0;color:var(--muted);font-size:13px}
        .layout{display:grid;grid-template-columns:1fr;gap:16px;margin-top:18px}
        @media(min-width:1024px){.layout{grid-template-columns:320px 1fr;gap:18px}}
        .panel{border:1px solid var(--border);background:var(--card);border-radius:18px;box-shadow:var(--shadow-sm);overflow:hidden}
        .panel-h{padding:14px 16px;border-bottom:1px solid var(--border);font-weight:900;letter-spacing:.14em;text-transform:uppercase;font-size:11px;color:var(--muted)}
        .panel-b{padding:16px}
        label{display:block;font-size:11px;font-weight:900;letter-spacing:.14em;text-transform:uppercase;color:var(--muted)}
        .input,select{margin-top:8px;width:100%;border:1px solid var(--border);background:rgba(148,163,184,.08);border-radius:14px;padding:10px 12px;color:var(--text);outline:none}
        html.dark .input, html.dark select{background:rgba(148,163,184,.08)}
        .row{display:grid;grid-template-columns:1fr 1fr;gap:10px}
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;border:none;border-radius:14px;padding:12px 14px;font-weight:900;cursor:pointer}
        .btn-primary{background:var(--text);color:#fff;box-shadow:var(--shadow)}
        html.dark .btn-primary{background:#e2e8f0;color:#0f172a}
        .btn-ghost{background:transparent;color:var(--muted);font-weight:900}
        .btn-ghost:hover{color:var(--danger)}
        .chips{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px}
        .chip{border-radius:999px;padding:8px 10px;border:1px solid rgba(79,70,229,.25);background:rgba(79,70,229,.08);color:#3730a3;font-weight:900;font-size:11px}
        html.dark .chip{color:#c7d2fe}
        .grid{display:grid;grid-template-columns:repeat(1,minmax(0,1fr));gap:12px}
        @media(min-width:640px){.grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media(min-width:1280px){.grid{grid-template-columns:repeat(3,minmax(0,1fr))}}
        .card{display:block;overflow:hidden;border-radius:18px;border:1px solid var(--border);background:var(--card);box-shadow:var(--shadow-sm);transition:transform .15s ease, box-shadow .15s ease}
        .card:hover{transform:translateY(-2px);box-shadow:var(--shadow)}
        .media{aspect-ratio:4/3;background:rgba(148,163,184,.25)}
        .media img{width:100%;height:100%;object-fit:cover;display:block}
        .body{padding:14px}
        .t{font-weight:900;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .meta{margin-top:10px;display:flex;justify-content:space-between;gap:10px;align-items:baseline}
        .price{font-weight:900}
        .loc{font-size:12px;color:var(--muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .empty{border:2px dashed var(--border);border-radius:22px;padding:46px 16px;text-align:center;color:var(--muted);font-weight:900}
        .pager{margin-top:18px;display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap}
        .pager a{border:1px solid var(--border);background:var(--card);padding:10px 12px;border-radius:12px;box-shadow:var(--shadow-sm);font-weight:900;color:var(--muted)}
        .pager a:hover{color:var(--indigo)}
    </style>
    <script>
        (()=>{const stored=localStorage.getItem('theme');const theme=stored==='light'||stored==='dark'?stored:(window.matchMedia&&window.matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light');if(theme==='dark')document.documentElement.classList.add('dark');})();
        window.addEventListener('DOMContentLoaded',()=>{document.querySelectorAll('[data-theme-toggle]').forEach(btn=>{btn.addEventListener('click',()=>{const root=document.documentElement;const next=root.classList.contains('dark')?'light':'dark';if(next==='dark')root.classList.add('dark');else root.classList.remove('dark');localStorage.setItem('theme',next);});});});
    </script>
</head>
<body>
<header class="header">
        <div class="header-row">
            <a class="logo" href="{{ route('web.home') }}"><img src="{{ asset('images/homecycle_dark_bg.png') }}" alt="HomeCycle" /></a>
            <div class="grow">
                <form action="{{ route('web.search') }}" method="GET">
                    <div class="search">
                        <input name="q" value="{{ request('q') }}" placeholder="{{ __('ui.search_placeholder') }}" />
                        <button type="submit">{{ __('ui.search') }}</button>
                    </div>
                </form>
            </div>
            <button type="button" class="btn-icon" data-theme-toggle aria-label="Toggle theme">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364-.707.707M6.343 17.657l-.707.707m12.728 0-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>
            </button>
        </div>
</header>

<main class="main">
        <h1 class="title">Search Results</h1>
        <p class="subtitle">
            @if(!empty($filters['q']))
                Showing results for "{{ $filters['q'] }}"
            @else
                Showing all available listings
            @endif
        </p>

        <div class="layout">
            <aside>
                <form method="GET" action="{{ route('web.search') }}" class="panel">
                    <div class="panel-h">Filters</div>
                    <div class="panel-b">
                        <div style="margin-bottom:14px;">
                            <label>Keyword</label>
                            <input class="input" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search..." />
                        </div>

                        <div style="margin-bottom:14px;">
                            <label>Category</label>
                            <select name="category_slug">
                                <option value="">All Categories</option>
                                @foreach(($categories ?? []) as $cat)
                                    <option value="{{ $cat['slug'] }}" @selected(($filters['category_slug'] ?? '') === $cat['slug'])>{{ $cat['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div style="margin-bottom:14px;">
                            <label>Price Range (₦)</label>
                            <div class="row">
                                <input class="input" name="min_price" value="{{ $filters['min_price'] ?? '' }}" placeholder="Min" />
                                <input class="input" name="max_price" value="{{ $filters['max_price'] ?? '' }}" placeholder="Max" />
                            </div>
                        </div>

                        <div style="margin-bottom:14px;">
                            <label>Location</label>
                            <select name="state_slug">
                                <option value="">Everywhere</option>
                                @foreach(($states ?? []) as $st)
                                    <option value="{{ $st['slug'] }}" @selected(($filters['state_slug'] ?? '') === $st['slug'])>{{ $st['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div style="display:flex;flex-direction:column;gap:10px;">
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                            @if(count(array_filter($filters ?? [])) > 0)
                                <a class="btn btn-ghost" href="{{ route('web.search') }}">Clear all filters</a>
                            @endif
                        </div>
                    </div>
                </form>
            </aside>

            <section>
                @if(count(array_filter($filters ?? [])) > 0)
                    <div class="chips">
                        @foreach($filters as $key => $value)
                            @if($value && $key !== 'page')
                                <span class="chip">{{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value }}</span>
                            @endif
                        @endforeach
                    </div>
                @endif

                <div class="grid">
                    @forelse(($listings ?? []) as $item)
                        @php
                            $img = $item['image']['url'] ?? null;
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
                                <div class="t">{{ $title }}</div>
                                <div class="meta">
                                    <div class="price">₦{{ number_format($price, 2) }}</div>
                                    <div class="loc">Sold by {{ $storeName }}</div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="empty" style="grid-column:1/-1;">No matches found</div>
                    @endforelse
                </div>

                @php
                    $page = (int) request('page', 1);
                    $hasNext = is_array($meta ?? null) && isset($meta['current_page'], $meta['last_page']) && (int) $meta['current_page'] < (int) $meta['last_page'];
                    $hasPrev = $page > 1;
                @endphp

                <div class="pager">
                    @if($hasPrev)
                        <a href="{{ request()->fullUrlWithQuery(['page' => $page - 1]) }}">← Prev</a>
                    @endif
                    @if($hasNext)
                        <a href="{{ request()->fullUrlWithQuery(['page' => $page + 1]) }}">Next →</a>
                    @endif
                </div>
            </section>
        </div>
</main>
</body>
</html>
