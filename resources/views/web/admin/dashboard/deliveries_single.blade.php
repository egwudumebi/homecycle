<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Deliveries | HomeCycle Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/homecycle_favicon.png') }}">
    <style>
        :root{--bg:#f8fafc;--card:#fff;--text:#0f172a;--muted:#64748b;--border:#e2e8f0;--shadow-sm:0 1px 2px rgba(15,23,42,.06);--indigo:#4f46e5}
        html.dark{--bg:#020617;--card:#0b1220;--text:#e2e8f0;--muted:#94a3b8;--border:rgba(148,163,184,.18);--shadow-sm:0 1px 2px rgba(0,0,0,.35)}
        *{box-sizing:border-box}
        body{margin:0;font-family:ui-sans-serif,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,"Apple Color Emoji","Segoe UI Emoji";background:var(--bg);color:var(--text)}
        a{color:inherit;text-decoration:none}
        .wrap{display:flex;min-height:100vh}
        .aside{width:280px;background:var(--card);border-right:1px solid var(--border);padding:18px 14px;position:sticky;top:0;height:100vh;overflow:auto}
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
        .btn-icon{height:40px;width:40px;border-radius:12px;border:1px solid var(--border);background:var(--card);display:inline-flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:var(--shadow-sm)}
        .content{padding:18px 18px 18px 16px}
        .panel{border:1px solid var(--border);background:var(--card);border-radius:18px;box-shadow:var(--shadow-sm);padding:14px}
        .p{margin:10px 0 0;color:var(--muted);font-size:13px;line-height:1.5}
        .k{color:var(--muted);font-weight:950;font-size:10px;letter-spacing:.14em;text-transform:uppercase}
        .grid{margin-top:14px;display:grid;grid-template-columns:1fr;gap:12px}
        @media(min-width:768px){.grid{grid-template-columns:repeat(4,1fr)}}
        .v{margin-top:8px;font-weight:950;font-size:22px}
    </style>
    <script>
        (()=>{const stored=localStorage.getItem('theme');const theme=stored==='light'||stored==='dark'?stored:(window.matchMedia&&window.matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light');if(theme==='dark')document.documentElement.classList.add('dark');})();
        window.addEventListener('DOMContentLoaded',()=>{document.querySelectorAll('[data-theme-toggle]').forEach(btn=>{btn.addEventListener('click',()=>{const root=document.documentElement;const next=root.classList.contains('dark')?'light':'dark';if(next==='dark')root.classList.add('dark');else root.classList.remove('dark');localStorage.setItem('theme',next);});});});
    </script>
</head>
<body>
<div class="wrap">
    <aside class="aside">
        <div class="brand"><div class="logo">HC</div><div><b>HomeCycle</b><small>Core Admin</small></div></div>
        <nav class="nav">
            <a href="{{ route('admin.overview') }}">Overview <span>→</span></a>
            <a href="{{ route('admin.listings.index') }}">Listings <span>→</span></a>
            <a href="{{ route('admin.sales') }}">Sales <span>→</span></a>
            <a href="{{ route('admin.orders') }}">Orders <span>→</span></a>
            <a class="active" href="{{ route('admin.deliveries') }}">Logistics <span>→</span></a>
        </nav>
        <div style="margin-top:18px;border-top:1px solid var(--border);padding-top:14px;">
            <form method="POST" action="{{ route('admin.logout') }}">@csrf
                <button style="width:100%;border-radius:14px;border:1px solid rgba(220,38,38,.25);background:rgba(220,38,38,.06);color:#b91c1c;padding:10px 12px;font-weight:950;cursor:pointer;">Sign out</button>
            </form>
        </div>
    </aside>

    <div class="main">
        <header class="top">
            <div class="topin">
                <h1 class="ttl">Track Deliveries</h1>
                <button type="button" class="btn-icon" data-theme-toggle aria-label="Toggle theme">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364-.707.707M6.343 17.657l-.707.707m12.728 0-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>
                </button>
            </div>
        </header>

        <main class="content">
            <div class="panel">
                <div class="k">Placeholder</div>
                <p class="p">This page is ready to connect to Orders/Deliveries. Once orders exist, we can track dispatch, in-transit, delivered, and failed delivery states.</p>

                <div class="grid">
                    <div class="panel" style="box-shadow:none;">
                        <div class="k">Pending</div>
                        <div class="v">0</div>
                    </div>
                    <div class="panel" style="box-shadow:none;">
                        <div class="k">Dispatched</div>
                        <div class="v">0</div>
                    </div>
                    <div class="panel" style="box-shadow:none;">
                        <div class="k">In transit</div>
                        <div class="v">0</div>
                    </div>
                    <div class="panel" style="box-shadow:none;">
                        <div class="k">Delivered</div>
                        <div class="v">0</div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
