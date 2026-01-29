<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Listing | HomeCycle Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/homecycle_favicon.png') }}">
    <style>
        :root{--bg:#f8fafc;--card:#fff;--text:#0f172a;--muted:#64748b;--border:#e2e8f0;--shadow:0 10px 30px rgba(15,23,42,.08);--shadow-sm:0 1px 2px rgba(15,23,42,.06);--indigo:#4f46e5;--danger:#dc2626;--ok:#059669}
        html.dark{--bg:#020617;--card:#0b1220;--text:#e2e8f0;--muted:#94a3b8;--border:rgba(148,163,184,.18);--shadow:0 10px 30px rgba(0,0,0,.35);--shadow-sm:0 1px 2px rgba(0,0,0,.35)}
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
        .content{padding:18px;max-width:1200px;margin:0 auto}

        .grid{display:grid;grid-template-columns:1fr;gap:14px}
        @media(min-width:1024px){.grid{grid-template-columns:2fr 1fr;align-items:start}}

        .panel{border:1px solid var(--border);background:var(--card);border-radius:18px;box-shadow:var(--shadow-sm);padding:14px}
        .panel h3{margin:0;font-size:12px;font-weight:950;letter-spacing:.14em;text-transform:uppercase;color:var(--muted)}
        .row{display:grid;grid-template-columns:1fr;gap:12px;margin-top:12px}
        label{display:block;font-size:12px;font-weight:950;color:var(--muted);margin-top:12px}
        input,textarea,select{width:100%;margin-top:8px;border:1px solid var(--border);background:rgba(148,163,184,.08);border-radius:14px;padding:11px 12px;outline:none;color:var(--text)}
        textarea{resize:vertical;min-height:140px}
        input:focus,textarea:focus,select:focus{border-color:rgba(79,70,229,.55);box-shadow:0 0 0 5px rgba(79,70,229,.12)}
        .err{margin-top:8px;color:var(--danger);font-size:12px;font-weight:800}

        .upload{border:2px dashed rgba(148,163,184,.35);border-radius:18px;padding:16px;position:relative;background:linear-gradient(135deg, rgba(148,163,184,.10), transparent)}
        .upload.drag{border-color:rgba(79,70,229,.65);background:rgba(79,70,229,.08)}
        .upload input{position:absolute;inset:0;opacity:0;cursor:pointer;margin:0}
        .upload .hint{color:var(--muted);font-size:13px;margin-top:6px}
        .previews{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;margin-top:12px}
        @media(min-width:640px){.previews{grid-template-columns:repeat(4,minmax(0,1fr))}}
        .prev{border:1px solid var(--border);border-radius:14px;overflow:hidden;aspect-ratio:1/1;background:rgba(148,163,184,.12)}
        .prev img{width:100%;height:100%;object-fit:cover;display:block}

        .btn{display:inline-flex;align-items:center;justify-content:center;gap:10px;border-radius:14px;border:1px solid var(--border);background:var(--card);padding:11px 14px;font-weight:950;box-shadow:var(--shadow-sm);cursor:pointer}
        .btn-primary{background:var(--text);color:#fff;border-color:transparent;box-shadow:var(--shadow)}
        html.dark .btn-primary{background:#e2e8f0;color:#0f172a}
        .btn-primary:hover{filter:brightness(1.05)}
        .btn-subtle{background:rgba(79,70,229,.08);border-color:rgba(79,70,229,.18);color:var(--indigo)}

        .check{display:flex;align-items:center;gap:10px;margin-top:12px;padding:10px 12px;border-radius:14px;border:1px solid var(--border);background:rgba(148,163,184,.06)}
        .check input{width:auto;margin:0}
        .meta{color:var(--muted);font-size:12px;margin-top:6px}

        .sticky{position:sticky;top:86px}
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
            <a class="active" href="{{ route('admin.listings.index') }}">Listings <span>→</span></a>
            <a href="{{ route('admin.sales') }}">Sales <span>→</span></a>
            <a href="{{ route('admin.orders') }}">Orders <span>→</span></a>
            <a href="{{ route('admin.deliveries') }}">Logistics <span>→</span></a>
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
                <h1 class="ttl">Create Listing</h1>
                <div style="display:flex;gap:10px;align-items:center;">
                    <a class="btn btn-subtle" href="{{ route('admin.listings.index') }}">Back</a>
                    <button type="button" class="btn-icon" data-theme-toggle aria-label="Toggle theme">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364-.707.707M6.343 17.657l-.707.707m12.728 0-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>
                    </button>
                </div>
            </div>
        </header>

        <main class="content">
            @php
                $listing = null;
                $listingId = null;
                $isEdit = false;
                $stateSlug = null;
                $cityId = null;
                $subCategoryId = null;
            @endphp

            <form method="POST" action="{{ route('admin.listings.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="grid">
                    <section>
                        <div class="panel">
                            <h3>General</h3>

                            <label>Product Title</label>
                            <input name="title" value="{{ old('title', '') }}" placeholder="e.g. Samsung 55-Inch Smart 4K TV" />
                            @error('title')<div class="err">{{ $message }}</div>@enderror

                            <label>Description</label>
                            <textarea name="description" placeholder="Describe the product's condition, features, and history...">{{ old('description', '') }}</textarea>
                            @error('description')<div class="err">{{ $message }}</div>@enderror
                        </div>

                        <div class="panel" style="margin-top:14px;">
                            <h3>Images</h3>

                            <div id="dropZone" class="upload">
                                <input id="imageInput" name="images[]" type="file" multiple accept="image/*" />
                                <div style="font-weight:950;">Click or drag to upload images</div>
                                <div class="hint">You can select multiple files (JPEG/PNG/WEBP). Max 5MB each.</div>
                            </div>
                            @error('images.*')<div class="err">{{ $message }}</div>@enderror

                            <div id="previewContainer" style="display:none;">
                                <div class="meta" style="margin-top:12px;font-weight:950;">New images preview</div>
                                <div id="previewGrid" class="previews"></div>
                            </div>
                        </div>
                    </section>

                    <aside class="sticky">
                        <div class="panel">
                            <h3>Publishing</h3>

                            <label>Price (₦)</label>
                            <input name="price" value="{{ old('price', '') }}" />
                            @error('price')<div class="err">{{ $message }}</div>@enderror

                            <label>Status</label>
                            <select name="status">
                                @foreach(['active' => 'Active', 'sold' => 'Sold', 'hidden' => 'Hidden'] as $k => $v)
                                    <option value="{{ $k }}" @selected(old('status', 'active') === $k)>{{ $v }}</option>
                                @endforeach
                            </select>
                            @error('status')<div class="err">{{ $message }}</div>@enderror

                            <div class="check">
                                <input id="is_featured" name="is_featured" type="checkbox" value="1" @checked(old('is_featured', false)) />
                                <div>
                                    <div style="font-weight:950;">Featured</div>
                                    <div class="meta">Show on homepage spotlight</div>
                                </div>
                            </div>

                            <div style="display:flex;gap:10px;flex-direction:column;margin-top:14px;">
                                <button class="btn btn-primary" type="submit">Save Listing</button>
                            </div>
                        </div>

                        <div class="panel" style="margin-top:14px;">
                            <h3>Classification</h3>

                            <label>Category</label>
                            <select id="category_select">
                                <option value="">Select Category</option>
                                @foreach(($categories ?? []) as $cat)
                                    <option value="{{ $cat['slug'] }}">{{ $cat['name'] }}</option>
                                @endforeach
                            </select>

                            <label>Sub-Category</label>
                            <select name="sub_category_id" id="sub_category_select" required>
                                <option value="">Select Sub-Category</option>
                            </select>
                            @error('sub_category_id')<div class="err">{{ $message }}</div>@enderror
                        </div>

                        <div class="panel" style="margin-top:14px;">
                            <h3>Location</h3>

                            <label>State</label>
                            <select name="state_id" id="state_select" required>
                                <option value="">Select State</option>
                                @foreach(($states ?? []) as $st)
                                    <option value="{{ $st['id'] }}" data-slug="{{ $st['slug'] }}">{{ $st['name'] }}</option>
                                @endforeach
                            </select>
                            @error('state_id')<div class="err">{{ $message }}</div>@enderror

                            <label>City / LGA</label>
                            <select name="city_id" id="city_select" required>
                                <option value="">Select City</option>
                            </select>
                            @error('city_id')<div class="err">{{ $message }}</div>@enderror
                        </div>

                        <div class="panel" style="margin-top:14px;">
                            <h3>Store Contact</h3>
                            @php
                                $storeName = (string) config('app.store.name');
                                $storePhone = (string) config('app.store.phone');
                                $storeWhatsApp = (string) config('app.store.whatsapp_phone');
                            @endphp
                            <div class="meta" style="margin-top:10px;">
                                <div><b>Store:</b> {{ $storeName }}</div>
                                <div style="margin-top:6px;"><b>Phone:</b> {{ $storePhone }}</div>
                                <div style="margin-top:6px;"><b>WhatsApp:</b> {{ $storeWhatsApp }}</div>
                            </div>
                        </div>
                    </aside>
                </div>
            </form>
        </main>
    </div>
</div>

<script>
    (function () {
        const categories = @json($categories ?? []);
        const initialSubCategoryId = @json($subCategoryId);
        const initialStateSlug = @json($stateSlug);
        const initialCityId = @json($cityId);

        const categorySelect = document.getElementById('category_select');
        const subCategorySelect = document.getElementById('sub_category_select');
        const stateSelect = document.getElementById('state_select');
        const citySelect = document.getElementById('city_select');
        const imageInput = document.getElementById('imageInput');
        const dropZone = document.getElementById('dropZone');
        const previewContainer = document.getElementById('previewContainer');
        const previewGrid = document.getElementById('previewGrid');

        function handleImageSelection(files) {
            previewGrid.innerHTML = '';
            if (!files || files.length === 0) {
                previewContainer.style.display = 'none';
                return;
            }

            previewContainer.style.display = 'block';
            Array.from(files).forEach((file) => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const div = document.createElement('div');
                    div.className = 'prev';
                    div.innerHTML = `<img src="${e.target.result}" alt="" />`;
                    previewGrid.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        if (imageInput) {
            imageInput.addEventListener('change', (e) => handleImageSelection(e.target.files));
        }

        if (dropZone && imageInput) {
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('drag');
            });
            dropZone.addEventListener('dragleave', () => {
                dropZone.classList.remove('drag');
            });
            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('drag');
                imageInput.files = e.dataTransfer.files;
                handleImageSelection(e.dataTransfer.files);
            });
        }

        function fillSubCategories(categorySlug) {
            if (!subCategorySelect) return;
            subCategorySelect.innerHTML = '<option value="">Select Sub-Category</option>';
            const cat = categories.find(c => c.slug === categorySlug);
            const subs = (cat && cat.sub_categories) ? cat.sub_categories : [];
            subs.forEach(sc => {
                const opt = document.createElement('option');
                opt.value = sc.id;
                opt.textContent = sc.name;
                if (initialSubCategoryId && Number(initialSubCategoryId) === Number(sc.id)) {
                    opt.selected = true;
                }
                subCategorySelect.appendChild(opt);
            });
        }

        async function fillCitiesByStateSlug(stateSlug) {
            if (!citySelect) return;
            citySelect.innerHTML = '<option value="">Loading Cities...</option>';
            try {
                const url = `{{ route('admin.api.locations.cities', ['stateSlug' => 'STATE_SLUG']) }}`.replace('STATE_SLUG', stateSlug);
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const payload = await res.json();
                const cities = payload.data || [];
                citySelect.innerHTML = '<option value="">Select City</option>';
                cities.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.id;
                    opt.textContent = c.name;
                    if (initialCityId && Number(initialCityId) === Number(c.id)) {
                        opt.selected = true;
                    }
                    citySelect.appendChild(opt);
                });
            } catch (e) {
                citySelect.innerHTML = '<option value="">Failed to load cities</option>';
            }
        }

        if (categorySelect) {
            categorySelect.addEventListener('change', () => fillSubCategories(categorySelect.value));
        }

        if (stateSelect) {
            stateSelect.addEventListener('change', () => {
                const opt = stateSelect.options[stateSelect.selectedIndex];
                const slug = opt ? opt.getAttribute('data-slug') : null;
                if (slug) fillCitiesByStateSlug(slug);
            });
        }

        if (initialSubCategoryId) {
            const owning = categories.find(c => (c.sub_categories || []).some(sc => Number(sc.id) === Number(initialSubCategoryId)));
            if (owning && categorySelect) {
                categorySelect.value = owning.slug;
                fillSubCategories(owning.slug);
            }
        }

        if (initialStateSlug && stateSelect) {
            const opt = Array.from(stateSelect.options).find(o => o.getAttribute('data-slug') === initialStateSlug);
            if (opt) {
                opt.selected = true;
                fillCitiesByStateSlug(initialStateSlug);
            }
        }
    })();
</script>
</body>
</html>
