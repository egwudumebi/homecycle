<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Overview | HomeCycle</title>
    <link rel="icon" type="image/png" href="{{ asset('images/homecycle_favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{
            --bg:#f9fafb;
            --bg-secondary:#f3f4f6;
            --card:#ffffff;
            --text:#111827;
            --text-secondary:#6b7280;
            --border:#e5e7eb;
            --border-light:#f3f4f6;
            --shadow:0 4px 6px -1px rgba(0,0,0,0.05),0 2px 4px -1px rgba(0,0,0,0.03);
            --shadow-lg:0 20px 25px -5px rgba(0,0,0,0.08),0 10px 10px -5px rgba(0,0,0,0.04);
            --primary:#6366f1;
            --primary-light:#eef2ff;
            --success:#10b981;
            --success-light:#ecfdf5;
            --accent:#f59e0b;
            --accent-light:#fffbf0;
        }
        html.dark{
            --bg:#0f172a;
            --bg-secondary:#1e293b;
            --card:#1e293b;
            --text:#f1f5f9;
            --text-secondary:#cbd5e1;
            --border:#334155;
            --border-light:#475569;
            --shadow:0 4px 6px -1px rgba(0,0,0,0.2);
            --shadow-lg:0 20px 25px -5px rgba(0,0,0,0.3);
            --primary-light:#312e81;
            --success-light:#064e3b;
            --accent-light:#78350f;
        }
        *{box-sizing:border-box}
        body{
            margin:0;
            font-family:'Inter',-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,sans-serif;
            background:var(--bg);
            color:var(--text);
            line-height:1.6;
            font-size:14px;
        }
        a{color:inherit;text-decoration:none}
        .wrap{display:flex;min-height:100vh}
        .overlay{display:none;position:fixed;inset:0;background:rgba(15,23,42,.45);backdrop-filter:blur(2px);z-index:40}
        .overlay.show{display:block}
        .aside{
            width:280px;
            flex:0 0 auto;
            background:var(--card);
            border-right:1px solid var(--border);
            padding:24px 16px;
            position:sticky;
            top:0;
            height:100vh;
            overflow-y:auto;
            display:flex;
            flex-direction:column;
        }
        .brand{
            display:flex;
            align-items:center;
            gap:12px;
            padding:12px 12px 20px;
            border-bottom:1px solid var(--border-light);
        }
        .aside-close{margin-left:auto}
        .logo{
            height:44px;
            width:44px;
            border-radius:12px;
            background:linear-gradient(135deg, var(--primary) 0%, #4f46e5 100%);
            color:#fff;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:800;
            font-size:16px;
            flex-shrink:0;
        }
        .brand b{display:block;font-size:14px;font-weight:700;letter-spacing:-.5px}
        .brand small{display:block;color:var(--text-secondary);font-weight:600;font-size:11px;letter-spacing:.1em;text-transform:uppercase;margin-top:2px}
        .nav{
            margin-top:20px;
            display:flex;
            flex-direction:column;
            gap:6px;
            flex:1;
        }
        .nav a{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
            border-radius:10px;
            border:1px solid transparent;
            padding:11px 14px;
            font-weight:600;
            color:var(--text-secondary);
            font-size:13px;
            transition:all 0.2s ease;
        }
        .nav a:hover{
            background:var(--primary-light);
            color:var(--primary);
        }
        .nav a.active{
            background:var(--primary-light);
            border-color:var(--primary);
            color:var(--primary);
        }
        .nav a span{font-size:12px;opacity:0.6}
        .user-section{
            margin-top:auto;
            border-top:1px solid var(--border-light);
            padding-top:16px;
        }
        .user-card{
            display:flex;
            align-items:center;
            gap:12px;
            padding:12px;
            border-radius:12px;
            background:var(--bg-secondary);
            margin-bottom:12px;
        }
        .user-avatar{
            height:40px;
            width:40px;
            border-radius:10px;
            background:var(--primary-light);
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:700;
            color:var(--primary);
            flex-shrink:0;
            font-size:12px;
        }
        .user-info{min-width:0;flex:1}
        .user-name{font-size:12px;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;line-height:1.2}
        .user-role{font-size:11px;color:var(--text-secondary);font-weight:500;margin-top:2px}
        .btn-logout{
            width:100%;
            border-radius:10px;
            border:1px solid rgba(239,68,68,0.3);
            background:rgba(239,68,68,0.08);
            color:#dc2626;
            padding:11px 12px;
            font-weight:600;
            cursor:pointer;
            font-size:12px;
            transition:all 0.2s ease;
        }
        .btn-logout:hover{
            background:rgba(239,68,68,0.12);
            border-color:rgba(239,68,68,0.4);
        }
        .main{flex:1;min-width:0;display:flex;flex-direction:column}
        .top{
            position:sticky;
            top:0;
            z-index:10;
            backdrop-filter:blur(12px);
            background:rgba(255,255,255,0.9);
            border-bottom:1px solid var(--border);
        }
        html.dark .top{background:rgba(15,23,42,0.9)}
        .topin{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:20px;
            padding:18px 28px;
        }
        .top-left{display:flex;flex-direction:column;gap:4px}
        .breadcrumb{
            font-size:11px;
            font-weight:600;
            letter-spacing:.1em;
            text-transform:uppercase;
            color:var(--text-secondary);
        }
        .ttl{
            margin:0;
            font-size:28px;
            font-weight:800;
            letter-spacing:-.02em;
            line-height:1.2;
        }
        .top-right{display:flex;align-items:center;gap:16px}
        .status-badge{
            display:inline-flex;
            align-items:center;
            gap:8px;
            border-radius:20px;
            border:1px solid rgba(16,185,129,0.25);
            background:rgba(16,185,129,0.08);
            color:var(--success);
            padding:8px 12px;
            font-size:12px;
            font-weight:600;
        }
        .status-dot{
            display:inline-block;
            width:6px;
            height:6px;
            border-radius:50%;
            background:var(--success);
            animation:pulse 2s ease-in-out infinite;
        }
        @keyframes pulse{0%,100%{opacity:1}50%{opacity:0.5}}
        .btn-icon{
            height:40px;
            width:40px;
            border-radius:10px;
            border:1px solid var(--border);
            background:var(--card);
            display:inline-flex;
            align-items:center;
            justify-content:center;
            cursor:pointer;
            transition:all 0.2s ease;
            color:var(--text-secondary);
        }
        .btn-icon:hover{
            background:var(--bg-secondary);
            border-color:var(--primary);
            color:var(--primary);
        }
        .mobile-only{display:none}
        .desktop-only{display:inline-flex}
        .content{
            flex:1;
            padding:32px 28px;
            overflow-y:auto;
        }
        .cards{
            display:grid;
            grid-template-columns:repeat(1,minmax(0,1fr));
            gap:16px;
            margin-bottom:32px;
        }
        @media(min-width:640px){.cards{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media(min-width:1024px){.cards{grid-template-columns:repeat(3,minmax(0,1fr))}}
        @media(min-width:1280px){.cards{grid-template-columns:repeat(4,minmax(0,1fr))}}
        .card{
            border:1px solid var(--border);
            background:var(--card);
            border-radius:14px;
            box-shadow:var(--shadow);
            padding:20px;
            transition:all 0.3s ease;
        }
        .card:hover{
            border-color:var(--primary);
            box-shadow:var(--shadow-lg);
            transform:translateY(-2px);
        }
        .card .k{
            display:flex;
            align-items:center;
            justify-content:space-between;
            color:var(--text-secondary);
            font-weight:600;
            font-size:11px;
            letter-spacing:.1em;
            text-transform:uppercase;
        }
        .card .v{
            margin-top:12px;
            font-size:32px;
            font-weight:800;
            letter-spacing:-.02em;
            line-height:1;
        }
        .card-meta{
            margin-top:14px;
            color:var(--text-secondary);
            font-size:12px;
            font-weight:500;
        }
        .card-meta strong{color:var(--primary);font-weight:700}
        .badge{
            margin-top:12px;
            display:inline-flex;
            align-items:center;
            gap:6px;
            border-radius:16px;
            padding:6px 10px;
            font-weight:600;
            font-size:11px;
            border:1px solid var(--primary);
            background:var(--primary-light);
            color:var(--primary);
        }
        .grid2{
            margin-bottom:32px;
            display:grid;
            grid-template-columns:1fr;
            gap:20px;
        }
        @media(min-width:1280px){.grid2{grid-template-columns:2fr 1fr}}
        .panel{
            border:1px solid var(--border);
            background:var(--card);
            border-radius:14px;
            box-shadow:var(--shadow);
            padding:24px;
        }
        .panel h3{
            margin:0;
            font-size:16px;
            font-weight:700;
            line-height:1.2;
        }
        .panel p{
            margin:6px 0 0;
            color:var(--text-secondary);
            font-size:13px;
            font-weight:500;
        }
        .panel-header{
            display:flex;
            align-items:flex-end;
            justify-content:space-between;
            gap:16px;
            margin-bottom:20px;
        }
        .bars{
            height:200px;
            display:flex;
            align-items:flex-end;
            gap:6px;
            margin-top:16px;
        }
        .bar{
            flex:1;
            border-radius:8px 8px 0 0;
            background:var(--primary-light);
            position:relative;
            overflow:hidden;
            min-height:8px;
            transition:all 0.3s ease;
        }
        .bar:hover{background:var(--primary)}
        .bar > span{
            position:absolute;
            left:0;
            right:0;
            bottom:0;
            background:var(--primary);
            border-radius:8px 8px 0 0;
        }
        .catalogue-grid{
            margin-top:16px;
            display:grid;
            grid-template-columns:1fr;
            gap:12px;
        }
        .catalogue-item{
            padding:14px 16px;
            border-radius:12px;
            border:1px solid var(--border-light);
            background:var(--bg-secondary);
            display:flex;
            align-items:center;
            justify-content:space-between;
            transition:all 0.2s ease;
        }
        .catalogue-item:hover{border-color:rgba(99,102,241,0.35);background:var(--card);transform:translateY(-1px)}
        .catalogue-item:focus-visible{outline:2px solid rgba(99,102,241,0.7);outline-offset:2px}
        .catalogue-label{
            font-size:12px;
            color:var(--text-secondary);
            font-weight:600;
            text-transform:uppercase;
            letter-spacing:.08em;
        }
        .catalogue-value{
            font-size:20px;
            font-weight:800;
        }
        .catalogue-right{display:flex;align-items:center;gap:10px}
        .catalogue-chevron{width:16px;height:16px;color:var(--text-secondary)}
        .catalogue-item:hover .catalogue-chevron{color:var(--primary)}
        .table{
            margin-top:0;
            border:1px solid var(--border);
            border-radius:14px;
            overflow:hidden;
            background:var(--card);
            box-shadow:var(--shadow);
        }
        .table-header{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:16px;
            padding:20px 24px;
            border-bottom:1px solid var(--border);
            background:var(--bg-secondary);
        }
        .table-header-content h3{
            margin:0;
            font-size:16px;
            font-weight:700;
        }
        .table-header-content p{
            margin:6px 0 0;
            color:var(--text-secondary);
            font-size:13px;
            font-weight:500;
        }
        .table-header-link{
            font-weight:600;
            color:var(--primary);
            font-size:13px;
            transition:color 0.2s ease;
        }
        .table-header-link:hover{color:#4f46e5}
        table{
            width:100%;
            border-collapse:collapse;
        }
        th,td{
            padding:14px 24px;
            border-bottom:1px solid var(--border);
            text-align:left;
        }
        th{
            font-size:11px;
            letter-spacing:.1em;
            text-transform:uppercase;
            color:var(--text-secondary);
            font-weight:700;
            background:var(--bg-secondary);
        }
        td{font-size:13px;font-weight:500}
        tbody tr:hover{background:var(--bg-secondary)}
        .td-id{
            font-family:'SF Mono',Monaco,Menlo,Courier,monospace;
            color:var(--text-secondary);
            font-weight:600;
            font-size:12px;
        }
        .td-title{font-weight:700;line-height:1.4}
        .td-slug{
            color:var(--text-secondary);
            font-size:12px;
            font-family:'SF Mono',Monaco,Menlo,Courier,monospace;
            margin-top:3px;
        }
        .status-chip{
            display:inline-flex;
            border-radius:8px;
            border:1px solid rgba(107,112,128,0.3);
            background:var(--bg-secondary);
            color:var(--text-secondary);
            padding:6px 10px;
            font-size:11px;
            font-weight:600;
            text-transform:uppercase;
            letter-spacing:.08em;
        }
        .td-right{text-align:right}
        .td-link{
            font-weight:700;
            color:var(--primary);
            transition:color 0.2s ease;
            cursor:pointer;
        }
        .td-link:hover{color:#4f46e5}
        .empty-state{
            color:var(--text-secondary);
            padding:40px 24px;
            text-align:center;
            font-size:13px;
        }
        html.dark .top-right .status-badge{
            border-color:rgba(16,185,129,0.4);
            background:rgba(16,185,129,0.15);
        }

        @media(max-width: 900px){
            .aside{position:fixed;left:0;top:0;bottom:0;height:100vh;z-index:50;box-shadow:var(--shadow-lg);transform:translateX(-102%);transition:transform .25s ease}
            .aside.open{transform:translateX(0)}
            .main{width:100%}
            .topin{padding:14px 16px}
            .ttl{font-size:22px}
            .content{padding:18px 16px}
            .mobile-only{display:inline-flex}
            .desktop-only{display:none}
        }

        @media(max-width: 640px){
            .top-right{gap:10px}
            .status-badge{padding:7px 10px;font-size:11px}
            .btn-icon{height:38px;width:38px}
            .card{padding:16px}
            .card .v{font-size:28px}
            .panel{padding:18px}
            .bars{height:160px}
            th,td{padding:12px 14px}
            .td-slug{display:none}
            .col-region{display:none}
        }
    </style>
    <script>
        (()=>{const stored=localStorage.getItem('theme');const theme=stored==='light'||stored==='dark'?stored:(window.matchMedia&&window.matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light');if(theme==='dark')document.documentElement.classList.add('dark');})();
        window.addEventListener('DOMContentLoaded',()=>{
            document.querySelectorAll('[data-theme-toggle]').forEach(btn=>{btn.addEventListener('click',()=>{const root=document.documentElement;const next=root.classList.contains('dark')?'light':'dark';if(next==='dark')root.classList.add('dark');else root.classList.remove('dark');localStorage.setItem('theme',next);});});

            const aside = document.querySelector('.aside');
            const overlay = document.querySelector('[data-sidebar-overlay]');
            const open = () => { if (!aside || !overlay) return; aside.classList.add('open'); overlay.classList.add('show'); };
            const close = () => { if (!aside || !overlay) return; aside.classList.remove('open'); overlay.classList.remove('show'); };

            document.querySelectorAll('[data-sidebar-toggle]').forEach(btn => btn.addEventListener('click', () => open()));
            if (overlay) overlay.addEventListener('click', () => close());
            document.querySelectorAll('[data-sidebar-close]').forEach(btn => btn.addEventListener('click', () => close()));

            window.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') close();
            });
        });
    </script>
</head>
<body>
<div class="wrap">
    <div class="overlay" data-sidebar-overlay></div>
    <aside class="aside">
        <div class="brand">
            <div class="logo">HC</div>
            <div>
                <b>HomeCycle</b>
                <small>Core Admin</small>
            </div>
            <button type="button" class="btn-icon aside-close mobile-only" data-sidebar-close aria-label="Close menu">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18L18 6"/><path d="M6 6l12 12"/></svg>
            </button>
        </div>
        <nav class="nav">
            <a class="active" data-sidebar-close href="{{ route('admin.overview') }}">Overview <span>→</span></a>
            <a data-sidebar-close href="{{ route('admin.listings.index') }}">Listings <span>→</span></a>
            <a data-sidebar-close href="{{ route('admin.catalogue.categories.index') }}">Catalogue <span>→</span></a>
            <a data-sidebar-close href="{{ route('admin.sales') }}">Sales <span>→</span></a>
            <a data-sidebar-close href="{{ route('admin.orders') }}">Orders <span>→</span></a>
            <a data-sidebar-close href="{{ route('admin.deliveries') }}">Logistics <span>→</span></a>
        </nav>
        <div class="user-section">
            <div class="user-card">
                <div class="user-avatar">{{ substr($apiUser['name'] ?? 'AD', 0, 2) }}</div>
                <div class="user-info">
                    <div class="user-name">{{ $apiUser['name'] ?? 'Admin' }}</div>
                    <div class="user-role">Administrator</div>
                </div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="btn-logout">Sign out</button>
            </form>
        </div>
    </aside>

    <div class="main">
        <header class="top">
            <div class="topin">
                <div class="top-left">
                    <div class="breadcrumb">Dashboard</div>
                    <h1 class="ttl">Dashboard Overview</h1>
                </div>
                <div class="top-right">
                    <button type="button" class="btn-icon mobile-only" data-sidebar-toggle aria-label="Open menu">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12h18"/><path d="M3 6h18"/><path d="M3 18h18"/></svg>
                    </button>
                    <span class="status-badge desktop-only"><span class="status-dot"></span>System Live</span>
                    <span class="status-badge mobile-only"><span class="status-dot"></span>Live</span>
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
                    <div class="card-meta">Sales rate: <strong style="color:var(--success);">{{ number_format((float) ($stats['sales_rate'] ?? 0), 1) }}%</strong></div>
                </div>
                <div class="card">
                    <div class="k">Revenue <span>Sold Amount</span></div>
                    <div class="v">₦{{ number_format((float) ($stats['sold_amount'] ?? 0), 0) }}</div>
                    <div class="card-meta">Featured rate: <strong style="color:var(--accent);">{{ number_format((float) ($stats['featured_rate'] ?? 0), 1) }}%</strong></div>
                </div>
                <div class="card">
                    <div class="k">Community <span>Users</span></div>
                    <div class="v">{{ number_format((int) ($stats['users'] ?? 0)) }}</div>
                    <div class="card-meta">Saved: <strong style="color:var(--primary);">{{ number_format((int) ($stats['saved'] ?? 0)) }}</strong></div>
                </div>
            </section>

            <section class="grid2">
                <div class="panel">
                    <div class="panel-header">
                        <div>
                            <h3>Activity Trends</h3>
                            <p>Published listings over the last {{ count($publishedSeries ?? []) }} days</p>
                        </div>
                        <div class="status-badge" style="border-color:rgba(107,112,128,0.2);background:var(--bg-secondary);color:var(--text-secondary);"><span style="background:var(--text-secondary);" class="status-dot"></span>Peak: {{ (int) ($maxPublished ?? 1) }}</div>
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
                    <div class="catalogue-grid">
                        <a class="catalogue-item" href="{{ route('admin.catalogue.categories.index') }}" aria-label="View categories">
                            <div class="catalogue-label">Categories</div>
                            <div class="catalogue-right">
                                <div class="catalogue-value">{{ number_format((int) ($stats['categories'] ?? 0)) }}</div>
                                <svg class="catalogue-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                            </div>
                        </a>
                        <a class="catalogue-item" href="{{ route('admin.catalogue.subcategories.index') }}" aria-label="View subcategories">
                            <div class="catalogue-label">Subcategories</div>
                            <div class="catalogue-right">
                                <div class="catalogue-value">{{ number_format((int) ($stats['sub_categories'] ?? 0)) }}</div>
                                <svg class="catalogue-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                            </div>
                        </a>
                        <a class="catalogue-item" href="{{ route('admin.listings.index', ['status' => 'hidden']) }}" aria-label="View hidden listings">
                            <div class="catalogue-label">Hidden</div>
                            <div class="catalogue-right">
                                <div class="catalogue-value">{{ number_format((int) ($stats['hidden_listings'] ?? 0)) }}</div>
                                <svg class="catalogue-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                            </div>
                        </a>
                        <a class="catalogue-item" href="{{ route('admin.listings.index', ['featured' => 1]) }}" aria-label="View featured listings">
                            <div class="catalogue-label">Featured</div>
                            <div class="catalogue-right">
                                <div class="catalogue-value">{{ number_format((int) ($stats['featured_listings'] ?? 0)) }}</div>
                                <svg class="catalogue-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                            </div>
                        </a>
                    </div>
                </div>
            </section>

            <section class="table">
                <div class="table-header">
                    <div class="table-header-content">
                        <h3>Recently Added Products</h3>
                        <p>Latest inventory submissions</p>
                    </div>
                    <a href="{{ route('admin.listings.index') }}" class="table-header-link">View all →</a>
                </div>
                <div style="overflow:auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Reference</th>
                                <th>Product Details</th>
                                <th>Status</th>
                                <th>Price</th>
                                <th class="col-region">Region</th>
                                <th class="td-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($latestListings ?? []) as $l)
                                <tr>
                                    <td><span class="td-id">#{{ $l->id }}</span></td>
                                    <td>
                                        <div class="td-title">{{ $l->title }}</div>
                                        <div class="td-slug">{{ $l->slug }}</div>
                                    </td>
                                    <td><span class="status-chip">{{ $l->status }}</span></td>
                                    <td style="font-weight:700;">₦{{ number_format((float) $l->price, 0) }}</td>
                                    <td class="col-region" style="color:var(--text-secondary);">{{ $l->city?->name ?? 'N/A' }}, {{ $l->state?->name ?? 'N/A' }}</td>
                                    <td class="td-right"><a href="{{ route('admin.listings.edit', ['id' => $l->id]) }}" class="td-link">Edit</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="empty-state">No recent listings found.</td></tr>
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