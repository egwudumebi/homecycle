<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ ($listing['title'] ?? 'Listing').' - Marketplace' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/homecycle_favicon.png') }}">
    <style>
        :root{--bg:#f8fafc;--card:#fff;--text:#0f172a;--muted:#64748b;--border:#e2e8f0;--shadow:0 10px 30px rgba(15,23,42,.08);--shadow-sm:0 1px 2px rgba(15,23,42,.06);--indigo:#4f46e5;--emerald:#059669}
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
        .layout{display:grid;grid-template-columns:1fr;gap:18px}
        @media(min-width:1024px){.layout{grid-template-columns:2fr 1fr;gap:18px}}
        .panel{border:1px solid var(--border);background:var(--card);border-radius:18px;box-shadow:var(--shadow-sm);overflow:hidden}
        .pad{padding:16px}
        .h1{margin:0;font-size:24px;letter-spacing:-.02em}
        .muted{color:var(--muted)}
        .pill{display:inline-flex;align-items:center;gap:8px;border-radius:999px;background:rgba(5,150,105,.10);border:1px solid rgba(5,150,105,.25);color:var(--emerald);padding:6px 10px;font-size:10px;font-weight:900;letter-spacing:.14em;text-transform:uppercase}
        .price{font-size:28px;font-weight:950;letter-spacing:-.02em}
        .grid-photos{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));grid-template-rows:repeat(2,1fr);gap:8px;padding:10px;height:340px}
        @media(min-width:640px){.grid-photos{height:460px}}
        .ph-main{grid-column:1 / span 4;grid-row:1 / span 2;overflow:hidden;border-radius:14px}
        @media(min-width:640px){.ph-main{grid-column:1 / span 3}}
        .ph-small{display:none;overflow:hidden;border-radius:14px}
        @media(min-width:640px){.ph-small{display:block;grid-column:4;}}
        .ph-small.one{grid-row:1}
        .ph-small.two{grid-row:2}
        .ph{width:100%;height:100%;object-fit:cover;display:block;transition:transform .5s ease}
        .ph-main:hover .ph{transform:scale(1.03)}
        .ph-small:hover .ph{transform:scale(1.03)}
        .fallback{display:flex;align-items:center;justify-content:center;height:260px;color:var(--muted)}
        .section{margin-top:16px}
        .section h3{margin:0;font-size:12px;font-weight:950;letter-spacing:.14em;text-transform:uppercase}
        .section .desc{margin-top:10px;line-height:1.6;color:var(--muted);white-space:pre-line}
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:10px;border-radius:14px;border:1px solid var(--border);background:var(--card);padding:12px 14px;font-weight:950;box-shadow:var(--shadow-sm)}
        .btn-primary{background:var(--text);color:#fff;border-color:transparent}
        html.dark .btn-primary{background:#e2e8f0;color:#0f172a}
        .btn-outline{border-width:2px;border-color:rgba(5,150,105,.65);color:var(--emerald)}
        .btn-outline:hover{background:rgba(5,150,105,.08)}
        .tips{background:rgba(79,70,229,.08);border:1px solid rgba(79,70,229,.18);border-radius:18px;padding:16px}
        .tips h4{margin:0;font-size:13px}
        .tips ul{margin:10px 0 0;padding-left:18px;color:var(--muted);font-size:12px;line-height:1.5}
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
        <div class="layout">
            <section>
                <div class="panel">
                    @php
                        $images = $listing['images'] ?? [];
                        if ($images instanceof \Illuminate\Support\Collection) {
                            $images = $images->values()->all();
                        } elseif (is_object($images) && method_exists($images, 'toArray')) {
                            $images = $images->toArray();
                        }
                    @endphp

                    @if(count($images) > 0)
                        <div class="grid-photos">
                            <div class="ph-main">
                                <img class="ph" src="{{ $images[0]['url'] }}" alt="{{ $listing['title'] ?? '' }}" />
                            </div>
                            @if(isset($images[1]))
                                <div class="ph-small one"><img class="ph" src="{{ $images[1]['url'] }}" alt="" /></div>
                            @endif
                            @if(isset($images[2]))
                                <div class="ph-small two"><img class="ph" src="{{ $images[2]['url'] }}" alt="" /></div>
                            @endif
                        </div>
                    @else
                        <div class="fallback">No images available</div>
                    @endif
                </div>

                <div class="panel" style="margin-top:14px;">
                    <div class="pad">
                        <div style="display:flex;justify-content:space-between;gap:14px;flex-wrap:wrap;align-items:flex-start;border-bottom:1px solid var(--border);padding-bottom:14px;">
                            <div>
                                <div style="font-size:11px;font-weight:950;letter-spacing:.14em;text-transform:uppercase;color:var(--indigo);">
                                    {{ $listing['category']['category']['name'] ?? 'General' }}
                                    <span style="opacity:.5;">/</span>
                                    <span class="muted">{{ $listing['category']['sub_category']['name'] ?? 'Other' }}</span>
                                </div>
                                <h1 class="h1" style="margin-top:10px;">{{ $listing['title'] ?? '' }}</h1>
                                <div class="muted" style="margin-top:8px;font-size:13px;">
                                    @php
                                        $storeName = (string) ($listing['seller']['name'] ?? config('app.store.name', 'HomeCycle'));
                                    @endphp
                                    Sold & shipped by {{ $storeName }}
                                </div>
                            </div>
                            <div style="text-align:right;">
                                <div class="price">₦{{ number_format((float) ($listing['price'] ?? 0), 2) }}</div>
                                <div style="margin-top:10px;">
                                    <span class="pill"><span style="display:inline-block;width:8px;height:8px;border-radius:999px;background:var(--emerald);"></span>{{ $listing['status'] ?? 'active' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="section">
                            <h3>Description</h3>
                            <div class="desc">{{ $listing['description'] ?? 'No description provided.' }}</div>
                        </div>
                    </div>
                </div>
            </section>

            <aside>
                <div class="panel">
                    <div class="pad">
                        @php
                            $storeName = (string) config('app.store.name');
                            $phone = (string) config('app.store.phone');
                            $wa = (string) config('app.store.whatsapp_phone');
                        @endphp

                        <div style="font-size:11px;font-weight:950;letter-spacing:.14em;text-transform:uppercase;color:var(--muted);">Store Contact</div>

                        <div style="display:flex;gap:12px;align-items:center;margin-top:12px;">
                            <div style="height:48px;width:48px;border-radius:999px;background:rgba(148,163,184,.16);display:flex;align-items:center;justify-content:center;font-weight:950;color:var(--muted);">
                                {{ strtoupper(substr($storeName ?: 'S', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight:950;">{{ $storeName }}</div>
                                <div class="muted" style="font-size:12px;margin-top:2px;">Platform-owned inventory</div>
                            </div>
                        </div>

                        <div style="display:flex;flex-direction:column;gap:10px;margin-top:16px;">
                            @if($phone)
                                <a class="btn btn-primary" href="tel:{{ $phone }}">Call Us</a>
                            @endif
                            @if($wa)
                                <a class="btn btn-outline" target="_blank" href="https://wa.me/{{ preg_replace('/\D+/', '', $wa) }}">WhatsApp Store</a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="tips" style="margin-top:14px;">
                    <h4>Safety Tips</h4>
                    <ul>
                        <li>Inspect the item on delivery or pickup.</li>
                        <li>Keep your order details for warranty/support.</li>
                        <li>Confirm delivery fees and timelines before payment.</li>
                    </ul>
                </div>

                <div style="margin-top:14px;">
                    <a class="btn" href="{{ route('web.search', ['category_slug' => $listing['category']['category']['slug'] ?? null]) }}">View more in {{ $listing['category']['category']['name'] ?? 'Category' }} →</a>
                </div>
            </aside>
        </div>
</main>
</body>
</html>
