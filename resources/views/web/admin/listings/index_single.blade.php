<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listings | HomeCycle Admin</title>
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
        }
        *{box-sizing:border-box}
        body{margin:0;font-family:'Inter',-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,sans-serif;background:var(--bg);color:var(--text);line-height:1.6;font-size:14px}
        a{color:inherit;text-decoration:none}
        .wrap{display:flex;min-height:100vh}
        .overlay{display:none;position:fixed;inset:0;background:rgba(15,23,42,.45);backdrop-filter:blur(2px);z-index:40}
        .overlay.show{display:block}
        .aside{width:280px;flex:0 0 auto;background:var(--card);border-right:1px solid var(--border);padding:24px 16px;position:sticky;top:0;height:100vh;overflow:auto;display:flex;flex-direction:column}
        .brand{display:flex;align-items:center;gap:12px;padding:12px 12px 20px;border-bottom:1px solid var(--border-light)}
        .aside-close{margin-left:auto}
        .logo{height:44px;width:44px;border-radius:12px;background:linear-gradient(135deg, var(--primary) 0%, #4f46e5 100%);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:16px;flex-shrink:0}
        .brand b{display:block;font-size:14px;font-weight:700;letter-spacing:-.5px}
        .brand small{display:block;color:var(--text-secondary);font-weight:600;font-size:11px;letter-spacing:.1em;text-transform:uppercase;margin-top:2px}
        .nav{margin-top:20px;display:flex;flex-direction:column;gap:6px;flex:1}
        .nav a{display:flex;align-items:center;justify-content:space-between;gap:10px;border-radius:10px;border:1px solid transparent;padding:11px 14px;font-weight:600;color:var(--text-secondary);font-size:13px;transition:all 0.2s ease}
        .nav a:hover{background:var(--primary-light);color:var(--primary)}
        .nav a.active{background:var(--primary-light);border-color:var(--primary);color:var(--primary)}
        .nav a span{font-size:12px;opacity:0.6}
        .user-section{margin-top:auto;border-top:1px solid var(--border-light);padding-top:16px}
        .user-card{display:flex;align-items:center;gap:12px;padding:12px;border-radius:12px;background:var(--bg-secondary);margin-bottom:12px}
        .user-avatar{height:40px;width:40px;border-radius:10px;background:var(--primary-light);display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--primary);flex-shrink:0;font-size:12px}
        .user-info{min-width:0;flex:1}
        .user-name{font-size:12px;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;line-height:1.2}
        .user-role{font-size:11px;color:var(--text-secondary);font-weight:500;margin-top:2px}
        .btn-logout{width:100%;border-radius:10px;border:1px solid rgba(239,68,68,0.3);background:rgba(239,68,68,0.08);color:#dc2626;padding:11px 12px;font-weight:600;cursor:pointer;font-size:12px;transition:all 0.2s ease}
        .btn-logout:hover{background:rgba(239,68,68,0.12);border-color:rgba(239,68,68,0.4)}

        .main{flex:1;min-width:0;display:flex;flex-direction:column}
        .top{position:sticky;top:0;z-index:10;backdrop-filter:blur(12px);background:rgba(255,255,255,0.9);border-bottom:1px solid var(--border)}
        html.dark .top{background:rgba(15,23,42,0.9)}
        .topin{display:flex;align-items:center;justify-content:space-between;gap:14px;padding:18px 28px}
        .top-left{display:flex;flex-direction:column;gap:4px}
        .breadcrumb{font-size:11px;font-weight:600;letter-spacing:.1em;text-transform:uppercase;color:var(--text-secondary)}
        .ttl{margin:0;font-size:26px;font-weight:800;letter-spacing:-.02em;line-height:1.2}
        .top-right{display:flex;align-items:center;gap:12px}

        .btn-icon{height:40px;width:40px;border-radius:10px;border:1px solid var(--border);background:var(--card);display:inline-flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.2s ease;color:var(--text-secondary)}
        .btn-icon:hover{background:var(--bg-secondary);border-color:var(--primary);color:var(--primary)}
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:10px;border-radius:10px;border:1px solid var(--border);background:var(--card);padding:11px 14px;font-weight:700;transition:all 0.2s ease}
        .btn:hover{background:var(--bg-secondary);border-color:var(--primary);color:var(--primary)}
        .btn-primary{background:var(--primary);border-color:transparent;color:#fff;box-shadow:var(--shadow)}
        .btn-primary:hover{filter:brightness(1.05);background:var(--primary)}

        .mobile-only{display:none}
        .desktop-only{display:inline-flex}

        .content{flex:1;padding:32px 28px;overflow:auto;max-width:1200px;margin:0 auto;width:100%}
        .row{display:flex;gap:12px;align-items:center;justify-content:space-between;flex-wrap:wrap;margin-bottom:14px}
        .search{position:relative;min-width:260px;flex:1;max-width:520px}
        .search input{width:100%;border:1px solid var(--border);background:var(--card);border-radius:12px;padding:12px 12px 12px 38px;outline:none;color:var(--text);transition:all .2s ease}
        .search input:focus{border-color:var(--primary);box-shadow:0 0 0 4px rgba(99,102,241,0.12)}
        .search svg{position:absolute;left:12px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:var(--text-secondary)}

        .table{border:1px solid var(--border);background:var(--card);border-radius:14px;overflow:hidden;box-shadow:var(--shadow)}
        table{width:100%;border-collapse:collapse}
        th,td{padding:14px 24px;border-bottom:1px solid var(--border);text-align:left}
        th{font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:var(--text-secondary);font-weight:700;background:var(--bg-secondary)}
        td{font-size:13px;font-weight:500}
        tbody tr:hover{background:var(--bg-secondary)}
        .td-id{font-family:'SF Mono',Monaco,Menlo,Courier,monospace;color:var(--text-secondary);font-weight:700;font-size:12px}
        .td-title{font-weight:700;line-height:1.4}
        .td-slug{color:var(--text-secondary);font-size:12px;font-family:'SF Mono',Monaco,Menlo,Courier,monospace;margin-top:3px}
        .td-right{text-align:right}

        .pager{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;padding:14px 18px;background:var(--bg-secondary)}
        .pill{border:1px solid var(--border);background:var(--card);padding:10px 12px;border-radius:10px;font-weight:700;color:var(--text-secondary);transition:all 0.2s ease}
        .pill:hover{color:var(--primary);border-color:var(--primary)}
        .empty-state{color:var(--text-secondary);padding:28px 18px;text-align:center;font-size:13px}
        .col-store{white-space:nowrap}

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
            .row{flex-direction:column;align-items:stretch}
            .search{min-width:0;max-width:none}
            th,td{padding:12px 14px}
            .td-slug{display:none}
            .col-store{display:none}
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
            window.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });
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
            <a data-sidebar-close href="{{ route('admin.overview') }}">Overview <span>→</span></a>
            <a class="active" data-sidebar-close href="{{ route('admin.listings.index') }}">Listings <span>→</span></a>
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
                    <div class="breadcrumb">Admin</div>
                    <h1 class="ttl">Listings</h1>
                </div>
                <div class="top-right">
                    <button type="button" class="btn-icon mobile-only" data-sidebar-toggle aria-label="Open menu">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12h18"/><path d="M3 6h18"/><path d="M3 18h18"/></svg>
                    </button>
                    <a class="btn btn-primary desktop-only" href="{{ route('admin.listings.create') }}">+ New Listing</a>
                    <button type="button" class="btn-icon" data-theme-toggle aria-label="Toggle theme">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364-.707.707M6.343 17.657l-.707.707m12.728 0-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>
                    </button>
                </div>
            </div>
        </header>

        <main class="content">
            <div class="row">
                <form class="search" method="GET" action="{{ route('admin.listings.index') }}">
                    @foreach(['status', 'featured', 'category_id', 'sub_category_id'] as $k)
                        @if(request()->filled($k))
                            <input type="hidden" name="{{ $k }}" value="{{ request($k) }}" />
                        @endif
                    @endforeach
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input name="q" value="{{ request('q') }}" placeholder="Search by title or ID..." />
                </form>

                <a class="btn btn-primary mobile-only" href="{{ route('admin.listings.create') }}">+ New Listing</a>
            </div>

            <div class="table">
                <div style="overflow:auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Ref ID</th>
                                <th>Product details</th>
                                <th>Price</th>
                                <th class="col-store">Store</th>
                                <th class="td-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse(($listings ?? []) as $item)
                                <tr>
                                    <td><span class="td-id">#{{ $item['id'] ?? '' }}</span></td>
                                    <td>
                                        <div class="td-title">{{ $item['title'] ?? 'Untitled Listing' }}</div>
                                        <div class="td-slug">/{{ $item['slug'] ?? '' }}</div>
                                    </td>
                                    <td style="font-weight:700;">₦{{ number_format((float) ($item['price'] ?? 0), 2) }}</td>
                                    <td class="col-store" style="color:var(--text-secondary);font-weight:600;">{{ config('app.store.name', 'HomeCycle') }}</td>
                                    <td class="td-right"><a class="pill" href="{{ route('admin.listings.edit', ['id' => $item['id']]) }}">Edit</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="empty-state">No listings found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(is_array($meta ?? null))
                    @php
                        $cp = (int) ($meta['current_page'] ?? 1);
                        $lp = (int) ($meta['last_page'] ?? 1);
                    @endphp
                    <div class="pager">
                        <div style="font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--text-secondary);">Page <span style="color:var(--text);">{{ $meta['current_page'] ?? 1 }}</span> of {{ $meta['last_page'] ?? 1 }}</div>
                        <div style="display:flex;gap:10px;">
                            <a class="pill" href="{{ $cp > 1 ? route('admin.listings.index', array_merge(request()->query(), ['page' => $cp - 1])) : '#' }}" style="{{ $cp > 1 ? '' : 'opacity:.45;pointer-events:none;' }}">← Prev</a>
                            <a class="pill" href="{{ $cp < $lp ? route('admin.listings.index', array_merge(request()->query(), ['page' => $cp + 1])) : '#' }}" style="{{ $cp < $lp ? '' : 'opacity:.45;pointer-events:none;' }}">Next →</a>
                        </div>
                    </div>
                @endif
            </div>
        </main>
    </div>
</div>
</body>
</html>
