<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Overview | HomeCycle</title>
    <link rel="icon" type="image/png" href="{{ asset('images/homecycle_favicon.png') }}">
    <style>
        :root{--bg:#f8fafc;--card:#fff;--text:#0f172a;--muted:#64748b;--border:#e2e8f0;--shadow:0 10px 30px rgba(15,23,42,.08);--shadow-sm:0 1px 2px rgba(15,23,42,.06);--indigo:#4f46e5;--emerald:#059669;--blue:#2563eb;--purple:#7c3aed}
        html.dark{--bg:#020617;--card:#0b1220;--text:#e2e8f0;--muted:#94a3b8;--border:rgba(148,163,184,.18);--shadow:0 10px 30px rgba(0,0,0,.35);--shadow-sm:0 1px 2px rgba(0,0,0,.35)}
        *{box-sizing:border-box}
        body{margin:0;font-family:ui-sans-serif,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,"Apple Color Emoji","Segoe UI Emoji";background:var(--bg);color:var(--text)}
        a{color:inherit;text-decoration:none}
        .wrap{display:flex;min-height:100vh}
        .aside{width:280px;flex:0 0 auto;background:var(--card);border-right:1px solid var(--border);padding:18px 14px;position:sticky;top:0;height:100vh;overflow:auto}
        .brand{display:flex;align-items:center;gap:10px;padding:10px 10px 16px;border-bottom:1px solid var(--border)}
        .logo{height:38px;width:38px;border-radius:14px;background:var(--indigo);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:950}
        .brand b{display:block;font-size:13px}
        .brand small{display:block;color:var(--muted);font-weight:900;font-size:10px;letter-spacing:.14em;text-transform:uppercase}
        .nav{margin-top:14px;display:flex;flex-direction:column;gap:8px}
        .nav a{display:flex;align-items:center;justify-content:space-between;gap:10px;border-radius:14px;border:1px solid transparent;padding:10px 12px;font-weight:950;color:var(--muted)}
        .nav a:hover{background:rgba(79,70,229,.08);color:var(--indigo)}
        .nav a.active{background:rgba(79,70,229,.10);border-color:rgba(79,70,229,.18);color:var(--indigo)}
        .main{flex:1;min-width:0}
        .top{position:sticky;top:0;z-index:10;backdrop-filter:blur(10px);background:rgba(248,250,252,.85);border-bottom:1px solid var(--border)}
        html.dark .top{background:rgba(2,6,23,.85)}
        .topin{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:14px 18px}
        .ttl{margin:0;font-size:16px;font-weight:950;letter-spacing:-.01em}
        .chip{display:inline-flex;align-items:center;gap:8px;border-radius:999px;border:1px solid rgba(5,150,105,.25);background:rgba(5,150,105,.10);color:var(--emerald);padding:7px 10px;font-size:11px;font-weight:950;letter-spacing:.14em;text-transform:uppercase}
        .btn-icon{height:40px;width:40px;border-radius:12px;border:1px solid var(--border);background:var(--card);display:inline-flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:var(--shadow-sm)}
        .content{padding:18px 18px 18px 16px}
        .cards{display:grid;grid-template-columns:repeat(1,minmax(0,1fr));gap:12px}
        @media(min-width:640px){.cards{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media(min-width:1280px){.cards{grid-template-columns:repeat(4,minmax(0,1fr))}}
        .card{border:1px solid var(--border);background:var(--card);border-radius:18px;box-shadow:var(--shadow-sm);padding:14px}
        .card .k{display:flex;align-items:center;justify-content:space-between;color:var(--muted);font-weight:950;font-size:10px;letter-spacing:.14em;text-transform:uppercase}
        .card .v{margin-top:10px;font-size:26px;font-weight:950;letter-spacing:-.02em}
        .badge{margin-top:10px;display:inline-flex;align-items:center;gap:8px;border-radius:999px;padding:7px 10px;font-weight:950;font-size:11px;border:1px solid rgba(79,70,229,.18);background:rgba(79,70,229,.08);color:var(--indigo)}
        .grid2{margin-top:14px;display:grid;grid-template-columns:1fr;gap:12px}
        @media(min-width:1280px){.grid2{grid-template-columns:2fr 1fr}}
        .panel{border:1px solid var(--border);background:var(--card);border-radius:18px;box-shadow:var(--shadow-sm);padding:14px}
        .panel h3{margin:0;font-size:13px;font-weight:950}
        .panel p{margin:6px 0 0;color:var(--muted);font-size:12px}
        .bars{height:180px;display:flex;align-items:flex-end;gap:8px;margin-top:14px}
        .bar{flex:1;border-radius:10px 10px 0 0;background:rgba(79,70,229,.10);position:relative;overflow:hidden}
        .bar > span{position:absolute;left:0;right:0;bottom:0;background:var(--indigo)}
        .table{margin-top:14px;border:1px solid var(--border);border-radius:18px;overflow:hidden;background:var(--card);box-shadow:var(--shadow-sm)}
        table{width:100%;border-collapse:collapse}
        th,td{padding:12px 14px;border-bottom:1px solid var(--border);text-align:left}
        th{font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:var(--muted)}
        td{font-size:13px}
        .right{text-align:right}
        .mono{font-family:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace}
    </style>
    <script>
        (()=>{const stored=localStorage.getItem('theme');const theme=stored==='light'||stored==='dark'?stored:(window.matchMedia&&window.matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light');if(theme==='dark')document.documentElement.classList.add('dark');})();
        window.addEventListener('DOMContentLoaded',()=>{document.querySelectorAll('[data-theme-toggle]').forEach(btn=>{btn.addEventListener('click',()=>{const root=document.documentElement;const next=root.classList.contains('dark')?'light':'dark';if(next==='dark')root.classList.add('dark');else root.classList.remove('dark');localStorage.setItem('theme',next);});});});
    </script>
</head>
<body>
<div class="wrap">
    <aside class="aside">
        <div class="brand">
            <div class="logo">HC</div>
            <div>
                <b>HomeCycle</b>
                <small>Core Admin</small>
            </div>
        </div>
        <nav class="nav">
            <a class="active" href="{{ route('admin.overview') }}">Overview <span>→</span></a>
            <a href="{{ route('admin.listings.index') }}">Listings <span>→</span></a>
            <a href="{{ route('admin.sales') }}">Sales <span>→</span></a>
            <a href="{{ route('admin.orders') }}">Orders <span>→</span></a>
            <a href="{{ route('admin.deliveries') }}">Logistics <span>→</span></a>
        </nav>
        <div style="margin-top:18px;border-top:1px solid var(--border);padding-top:14px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="height:38px;width:38px;border-radius:14px;background:rgba(79,70,229,.12);display:flex;align-items:center;justify-content:center;font-weight:950;color:var(--indigo);">
                    {{ substr($apiUser['name'] ?? 'AD', 0, 2) }}
                </div>
                <div style="min-width:0;">
                    <div style="font-size:12px;font-weight:950;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $apiUser['name'] ?? 'Admin' }}</div>
                    <div style="font-size:11px;color:var(--muted);font-weight:900;">Administrator</div>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}" style="margin-top:12px;">
                @csrf
                <button style="width:100%;border-radius:14px;border:1px solid rgba(220,38,38,.25);background:rgba(220,38,38,.06);color:#b91c1c;padding:10px 12px;font-weight:950;cursor:pointer;">Sign out</button>
            </form>
        </div>
    </aside>

    <div class="main">
        <header class="top">
            <div class="topin">
                <div>
                    <div style="font-size:11px;font-weight:950;letter-spacing:.14em;text-transform:uppercase;color:var(--muted);">Dashboard</div>
                    <h1 class="ttl">Dashboard Overview</h1>
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span class="chip"><span style="display:inline-block;width:8px;height:8px;border-radius:999px;background:var(--emerald);"></span>System Live</span>
                    <button type="button" class="btn-icon" data-theme-toggle aria-label="Toggle theme">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364-.707.707M6.343 17.657l-.707.707m12.728 0-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>
                    </button>
                </div>
            </div>
        </header>

        <main class="content">
            <section class="cards">
                <div class="card">
                    <div class="k">Inventory <span>Products</span></div>
                    <div class="v">{{ number_format((int) ($stats['total_listings'] ?? 0)) }}</div>
                    <div class="badge">Active: {{ number_format((int) ($stats['active_listings'] ?? 0)) }}</div>
                </div>
                <div class="card">
                    <div class="k">Performance <span>Sold</span></div>
                    <div class="v">{{ number_format((int) ($stats['sold_listings'] ?? 0)) }}</div>
                    <div style="margin-top:10px;color:var(--muted);font-size:12px;font-weight:900;">Sales rate: <span style="color:var(--emerald);">{{ number_format((float) ($stats['sales_rate'] ?? 0), 1) }}%</span></div>
                </div>
                <div class="card">
                    <div class="k">Revenue <span>Sold Amount</span></div>
                    <div class="v">₦{{ number_format((float) ($stats['sold_amount'] ?? 0), 0) }}</div>
                    <div style="margin-top:10px;color:var(--muted);font-size:12px;font-weight:900;">Featured rate: <span style="color:var(--blue);">{{ number_format((float) ($stats['featured_rate'] ?? 0), 1) }}%</span></div>
                </div>
                <div class="card">
                    <div class="k">Community <span>Users</span></div>
                    <div class="v">{{ number_format((int) ($stats['users'] ?? 0)) }}</div>
                    <div style="margin-top:10px;color:var(--muted);font-size:12px;font-weight:900;">Saved: <span style="color:var(--purple);">{{ number_format((int) ($stats['saved'] ?? 0)) }}</span></div>
                </div>
            </section>

            <section class="grid2">
                <div class="panel">
                    <div style="display:flex;align-items:flex-end;justify-content:space-between;gap:12px;">
                        <div>
                            <h3>Activity Trends</h3>
                            <p>Published listings over the last {{ count($publishedSeries ?? []) }} days</p>
                        </div>
                        <div class="chip" style="border-color:rgba(148,163,184,.18);background:rgba(148,163,184,.10);color:var(--muted);">Peak: {{ (int) ($maxPublished ?? 1) }}</div>
                    </div>
                    <div class="bars">
                        @foreach(($publishedSeries ?? []) as $point)
                            @php
                                $value = (int) ($point['total'] ?? 0);
                                $max = (int) ($maxPublished ?? 1);
                                $h = $max > 0 ? (int) round(($value / $max) * 100) : 0;
                            @endphp
                            <div class="bar" title="{{ $value }}">
                                <span style="height: {{ max(4, $h) }}%;"></span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="panel">
                    <h3>Catalogue</h3>
                    <p>Quick totals</p>
                    <div style="margin-top:12px;display:grid;grid-template-columns:1fr;gap:10px;">
                        <div class="card" style="padding:12px;box-shadow:none;">
                            <div class="k">Categories</div>
                            <div class="v" style="font-size:20px;">{{ number_format((int) ($stats['categories'] ?? 0)) }}</div>
                        </div>
                        <div class="card" style="padding:12px;box-shadow:none;">
                            <div class="k">Subcategories</div>
                            <div class="v" style="font-size:20px;">{{ number_format((int) ($stats['sub_categories'] ?? 0)) }}</div>
                        </div>
                        <div class="card" style="padding:12px;box-shadow:none;">
                            <div class="k">Hidden</div>
                            <div class="v" style="font-size:20px;">{{ number_format((int) ($stats['hidden_listings'] ?? 0)) }}</div>
                        </div>
                        <div class="card" style="padding:12px;box-shadow:none;">
                            <div class="k">Featured</div>
                            <div class="v" style="font-size:20px;">{{ number_format((int) ($stats['featured_listings'] ?? 0)) }}</div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="table">
                <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;padding:14px 14px;border-bottom:1px solid var(--border);">
                    <div>
                        <h3 style="margin:0;font-size:13px;font-weight:950;">Recently Added Products</h3>
                        <p style="margin:6px 0 0;color:var(--muted);font-size:12px;">Latest inventory submissions</p>
                    </div>
                    <a href="{{ route('admin.listings.index') }}" style="font-weight:950;color:var(--muted);">View all →</a>
                </div>
                <div style="overflow:auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Reference</th>
                                <th>Product Details</th>
                                <th>Status</th>
                                <th>Price</th>
                                <th>Region</th>
                                <th class="right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($latestListings ?? []) as $l)
                                <tr>
                                    <td><span class="mono" style="color:var(--muted);font-weight:900;">#{{ $l->id }}</span></td>
                                    <td>
                                        <div style="font-weight:950;">{{ $l->title }}</div>
                                        <div style="color:var(--muted);font-size:11px;" class="mono">{{ $l->slug }}</div>
                                    </td>
                                    <td><span class="chip" style="border-color:rgba(148,163,184,.18);background:rgba(148,163,184,.10);color:var(--muted);">{{ $l->status }}</span></td>
                                    <td style="font-weight:950;">₦{{ number_format((float) $l->price, 0) }}</td>
                                    <td style="color:var(--muted);font-weight:900;">{{ $l->city?->name ?? 'N/A' }}, {{ $l->state?->name ?? 'N/A' }}</td>
                                    <td class="right"><a href="{{ route('admin.listings.edit', ['id' => $l->id]) }}" style="font-weight:950;color:var(--indigo);">Edit</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" style="color:var(--muted);padding:20px;">No recent listings found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</div>
</body>
</html>
