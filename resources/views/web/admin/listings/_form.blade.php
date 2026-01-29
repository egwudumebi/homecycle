@php
    $isEdit = isset($listingId) && $listingId;
    $stateSlug = $listing['location']['state']['slug'] ?? null;
    $cityId = $listing['location']['city']['id'] ?? null;
    $subCategoryId = $listing['category']['sub_category']['id'] ?? null;
@endphp

<div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
    <div class="space-y-6 lg:col-span-8">
        <!-- General Information Card -->
        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm transition-all hover:shadow-md">
            <div class="mb-6 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100">
                    <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">General Information</h3>
            </div>
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-tight text-slate-600 mb-2">Product Title</label>
                    <input name="title" value="{{ old('title', $listing['title'] ?? '') }}" 
                        placeholder="e.g. Samsung 55-Inch Smart 4K TV"
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm transition-all placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10" />
                    @error('title') <p class="mt-2 text-xs font-medium text-red-500 flex items-center gap-1"><svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/></svg> {{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-tight text-slate-600 mb-2">Detailed Description</label>
                    <textarea name="description" rows="8" 
                        placeholder="Describe the product's condition, features, and history..."
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm transition-all placeholder:text-slate-400 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10">{{ old('description', $listing['description'] ?? '') }}</textarea>
                    @error('description') <p class="mt-2 text-xs font-medium text-red-500 flex items-center gap-1"><svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/></svg> {{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Product Gallery Card -->
        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm transition-all hover:shadow-md">
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100">
                        <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Product Gallery</h3>
                </div>
                <span class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 px-3 py-1.5 text-[10px] font-bold text-indigo-700">
                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/></svg>
                    JPEG, PNG, WEBP
                </span>
            </div>

            <!-- Existing Images Display -->
            @if($isEdit && !empty($listing['images']))
                <div class="mb-8">
                    <p class="text-xs font-semibold text-slate-600 mb-4">Current Images</p>
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                        @foreach($listing['images'] as $img)
                            <div class="group relative aspect-square overflow-hidden rounded-xl border border-slate-200 bg-slate-50 shadow-sm transition-all hover:shadow-md">
                                <img src="{{ $img['url'] }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" alt="" />
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-0 transition-opacity duration-200 group-hover:opacity-100 flex items-end p-3">
                                    <span class="text-[10px] font-bold text-white uppercase tracking-tighter">Current Image</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Image Upload Area -->
            <div class="relative">
                <div class="relative rounded-2xl border-2 border-dashed border-slate-200 bg-gradient-to-br from-slate-50 to-slate-50/50 p-8 transition-all hover:border-indigo-300 hover:bg-indigo-50/30" id="dropZone">
                    <input name="images[]" type="file" multiple accept="image/*" class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0" id="imageInput" />
                    <div class="flex flex-col items-center justify-center text-center">
                        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-white text-indigo-400 shadow-md ring-1 ring-indigo-100">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <p class="text-sm font-bold text-slate-700">Click or drag to upload new images</p>
                        <p class="mt-1 text-xs text-slate-500">You can select multiple files at once (JPEG, PNG, WEBP)</p>
                    </div>
                </div>
            </div>

            <!-- New Images Preview -->
            <div id="previewContainer" class="mt-8 hidden">
                <p class="text-xs font-semibold text-slate-600 mb-4">New Images Preview</p>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4" id="previewGrid"></div>
            </div>

            @error('images.*') <p class="mt-3 text-xs font-medium text-red-500 flex items-center gap-1"><svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/></svg> {{ $message }}</p> @enderror
        </div>
    </div>

    <!-- Sidebar -->
    <aside class="space-y-6 lg:col-span-4 lg:sticky lg:top-24 h-fit">
        <!-- Publishing Card -->
        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm transition-all hover:shadow-md">
            <div class="mb-6 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100">
                    <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Publishing</h3>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-2">Base Price (₦)</label>
                    <input name="price" value="{{ old('price', $listing['price'] ?? '') }}" 
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-900 transition-all focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10" />
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-2">Inventory Status</label>
                    <select name="status" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-900 transition-all focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10">
                        @foreach(['active' => 'Active', 'sold' => 'Sold', 'hidden' => 'Hidden'] as $k => $v)
                            <option value="{{ $k }}" @selected(old('status', $listing['status'] ?? 'active') === $k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>

                <label class="relative flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-slate-50/50 p-4 transition-all hover:border-indigo-300 hover:bg-indigo-50/50">
                    <input id="is_featured" name="is_featured" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer" @checked(old('is_featured', $listing['is_featured'] ?? false)) />
                    <span class="flex flex-col flex-1">
                        <span class="text-xs font-bold text-slate-900">Featured Listing</span>
                        <span class="text-[10px] text-slate-500">Show on homepage spotlight</span>
                    </span>
                    <svg class="h-5 w-5 text-indigo-500 opacity-0 transition-opacity group-hover:opacity-100" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/></svg>
                </label>
            </div>

            <div class="mt-6 flex flex-col gap-3">
                <button type="submit" class="w-full rounded-xl bg-slate-900 py-3 text-sm font-bold text-white shadow-lg transition-all hover:bg-slate-800 active:scale-95 flex items-center justify-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save Changes
                </button>
                @if($isEdit)
                    <a href="{{ route('web.listing.show', ['slug' => $listing['slug'] ?? '']) }}" target="_blank" class="flex items-center justify-center gap-2 rounded-xl border border-slate-200 py-2.5 text-xs font-bold text-slate-600 transition-all hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Preview Live Listing
                    </a>
                @endif
            </div>
        </div>

        <!-- Classification Card -->
        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm transition-all hover:shadow-md">
            <div class="mb-6 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100">
                    <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Classification</h3>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-2">Category</label>
                    <select id="category_select" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-900 transition-all focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10">
                        <option value="">Select Category</option>
                        @foreach(($categories ?? []) as $cat)
                            <option value="{{ $cat['slug'] }}">{{ $cat['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-2">Sub-Category</label>
                    <select name="sub_category_id" id="sub_category_select" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-900 transition-all focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10" required>
                        <option value="">Select Sub-Category</option>
                    </select>
                    @error('sub_category_id') <p class="mt-2 text-xs text-red-600 flex items-center gap-1"><svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/></svg> {{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Location Card -->
        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm transition-all hover:shadow-md">
            <div class="mb-6 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-orange-100">
                    <svg class="h-5 w-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Location</h3>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-2">State</label>
                    <select name="state_id" id="state_select" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-900 transition-all focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10" required>
                        <option value="">Select State</option>
                        @foreach(($states ?? []) as $st)
                            <option value="{{ $st['id'] }}" data-slug="{{ $st['slug'] }}">{{ $st['name'] }}</option>
                        @endforeach
                    </select>
                    @error('state_id') <p class="mt-2 text-xs text-red-600 flex items-center gap-1"><svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/></svg> {{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-slate-600 mb-2">City / LGA</label>
                    <select name="city_id" id="city_select" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-900 transition-all focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10" required>
                        <option value="">Select City</option>
                    </select>
                    @error('city_id') <p class="mt-2 text-xs text-red-600 flex items-center gap-1"><svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/></svg> {{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Store Contact Card -->
        <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm transition-all hover:shadow-md">
            @php
                $storeName = (string) config('app.store.name');
                $storePhone = (string) config('app.store.phone');
                $storeWhatsApp = (string) config('app.store.whatsapp_phone');
            @endphp

            <div class="mb-6 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-100">
                    <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3v2a3 3 0 006 0v-2c0-1.657-1.343-3-3-3z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 11a6 6 0 0112 0v2a6 6 0 01-12 0v-2z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Store Contact</h3>
            </div>

            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between gap-4">
                    <div class="text-slate-600 font-semibold">Store</div>
                    <div class="text-slate-900 font-bold">{{ $storeName }}</div>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <div class="text-slate-600 font-semibold">Phone</div>
                    <div class="text-slate-900 font-bold">{{ $storePhone }}</div>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <div class="text-slate-600 font-semibold">WhatsApp</div>
                    <div class="text-slate-900 font-bold">{{ $storeWhatsApp }}</div>
                </div>
            </div>
        </div>
    </aside>
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

        // Image Preview Functionality
        function handleImageSelection(files) {
            previewGrid.innerHTML = '';
            
            if (files.length === 0) {
                previewContainer.classList.add('hidden');
                return;
            }

            previewContainer.classList.remove('hidden');
            Array.from(files).forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'group relative aspect-square overflow-hidden rounded-xl border border-slate-200 bg-slate-50 shadow-sm transition-all hover:shadow-md';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105" alt="Preview ${index + 1}" />
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent opacity-0 transition-opacity duration-200 group-hover:opacity-100 flex items-end p-3">
                            <span class="text-[10px] font-bold text-white uppercase tracking-tighter">${index + 1}</span>
                        </div>
                    `;
                    previewGrid.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        imageInput.addEventListener('change', (e) => handleImageSelection(e.target.files));

        // Drag and Drop
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-indigo-500', 'bg-indigo-50');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
            imageInput.files = e.dataTransfer.files;
            handleImageSelection(e.dataTransfer.files);
        });

        // Category to Sub-Category
        function fillSubCategories(categorySlug) {
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

        // City Loading
        async function fillCitiesByStateSlug(stateSlug) {
            citySelect.innerHTML = '<option value="">Loading Cities...</option>';
            try {
                const url = `{{ route('admin.api.locations.cities', ['stateSlug' => 'STATE_SLUG']) }}`.replace('STATE_SLUG', stateSlug);
                const res = await fetch(url, {
                    headers: { 'Accept': 'application/json' }
                });
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

        // Event Listeners
        categorySelect.addEventListener('change', () => fillSubCategories(categorySelect.value));

        stateSelect.addEventListener('change', () => {
            const opt = stateSelect.options[stateSelect.selectedIndex];
            const slug = opt ? opt.getAttribute('data-slug') : null;
            if (slug) fillCitiesByStateSlug(slug);
        });

        // Initialization
        if (initialSubCategoryId) {
            const owning = categories.find(c => (c.sub_categories || []).some(sc => Number(sc.id) === Number(initialSubCategoryId)));
            if (owning) {
                categorySelect.value = owning.slug;
                fillSubCategories(owning.slug);
            }
        }

        if (initialStateSlug) {
            const opt = Array.from(stateSelect.options).find(o => o.getAttribute('data-slug') === initialStateSlug);
            if (opt) {
                opt.selected = true;
                fillCitiesByStateSlug(initialStateSlug);
            }
        }
    })();
</script>
