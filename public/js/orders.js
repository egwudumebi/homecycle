(() => {
    const getBaseUrl = () => {
        const el = document.querySelector('meta[name="app-base-url"]');
        const v = el ? el.getAttribute('content') : '';
        return (v || '').replace(/\/+$/, '');
    };

    const joinUrl = (base, path) => {
        const b = (base || '').replace(/\/+$/, '');
        const p = (path || '').replace(/^\/+/, '');
        return `${b}/${p}`;
    };

    const getCsrfToken = () => {
        const el = document.querySelector('meta[name="csrf-token"]');
        return el ? el.getAttribute('content') : '';
    };

    const apiFetch = async (path) => {
        const url = path.startsWith('http') ? path : joinUrl(getBaseUrl(), path);

        const headers = {
            Accept: 'application/json',
            'Content-Type': 'application/json',
        };

        const csrf = getCsrfToken();
        if (csrf) headers['X-CSRF-TOKEN'] = csrf;

        const res = await fetch(url, {
            credentials: 'same-origin',
            headers,
        });

        const json = await res.json().catch(() => ({}));
        if (!res.ok) {
            const msg = (json && json.message) ? json.message : 'Request failed';
            const err = new Error(msg);
            err.status = res.status;
            err.payload = json;
            throw err;
        }

        return json;
    };

    const apiFetchMultipart = async (path, formData) => {
        const url = path.startsWith('http') ? path : joinUrl(getBaseUrl(), path);

        const headers = {
            Accept: 'application/json',
        };

        const csrf = getCsrfToken();
        if (csrf) headers['X-CSRF-TOKEN'] = csrf;

        const res = await fetch(url, {
            method: 'POST',
            credentials: 'same-origin',
            headers,
            body: formData,
        });

        const json = await res.json().catch(() => ({}));
        if (!res.ok) {
            const msg = (json && json.message) ? json.message : 'Request failed';
            const err = new Error(msg);
            err.status = res.status;
            err.payload = json;
            throw err;
        }

        return json;
    };

    const formatMoney = (v) => {
        const n = Number(v || 0);
        return n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    };

    const formatDate = (iso) => {
        if (!iso) return '';
        try {
            const d = new Date(iso);
            return d.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
        } catch (_) {
            return String(iso);
        }
    };

    const toTitle = (s) => {
        return String(s || '')
            .replace(/_/g, ' ')
            .replace(/\b\w/g, (c) => c.toUpperCase());
    };

    const normalizeTrackingKeyToStepKey = (key) => {
        const k = String(key || '').toLowerCase();
        if (k === 'packed') return 'order_processing';
        if (k === 'in_transit' || k === 'out_for_delivery') return 'shipped';
        return k;
    };

    const trackingIconForKey = (key) => {
        const k = String(key || '').toLowerCase();

        if (k === 'order_created') {
            return {
                bg: 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200',
                svg: '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>',
            };
        }

        if (k === 'payment_confirmed') {
            return {
                bg: 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
                svg: '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>',
            };
        }

        if (k === 'order_processing') {
            return {
                bg: 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300',
                svg: '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V3m0 18v-3m9-6h-3M6 12H3m15.364 6.364l-2.121-2.121M8.757 8.757L6.636 6.636m12.728 0l-2.121 2.121M8.757 15.243l-2.121 2.121"/></svg>',
            };
        }

        if (k === 'packed') {
            return {
                bg: 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300',
                svg: '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12V8a2 2 0 00-1-1.732l-6-3.464a2 2 0 00-2 0l-6 3.464A2 2 0 004 8v4a2 2 0 001 1.732l6 3.464a2 2 0 002 0l6-3.464A2 2 0 0020 12z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22V12"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12L4.5 8.5"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12l7.5-3.5"/></svg>',
            };
        }

        if (k === 'shipped' || k === 'in_transit' || k === 'out_for_delivery') {
            return {
                bg: 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
                svg: '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h11v12H3V5zm11 5h4l3 4v3h-7"/></svg>',
            };
        }

        if (k === 'delivered') {
            return {
                bg: 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
                svg: '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9v9a2 2 0 01-2 2h-4a2 2 0 01-2-2V15a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-9z"/></svg>',
            };
        }

        if (k === 'cancelled') {
            return {
                bg: 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200',
                svg: '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
            };
        }

        if (k === 'failed') {
            return {
                bg: 'bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-300',
                svg: '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>',
            };
        }

        return {
            bg: 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200',
            svg: '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20a8 8 0 100-16 8 8 0 000 16z"/></svg>',
        };
    };

    const renderTrackingStepper = (wrap, timeline, currentKey) => {
        if (!wrap) return;

        const steps = [
            {
                key: 'order_created',
                label: 'Created',
                icon: '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>',
            },
            {
                key: 'payment_confirmed',
                label: 'Paid',
                icon: '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>',
            },
            {
                key: 'order_processing',
                label: 'Processing',
                icon: '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V3m0 18v-3m9-6h-3M6 12H3m15.364 6.364l-2.121-2.121M8.757 8.757L6.636 6.636m12.728 0l-2.121 2.121M8.757 15.243l-2.121 2.121"/></svg>',
            },
            {
                key: 'shipped',
                label: 'Shipped',
                icon: '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h11v12H3V5zm11 5h4l3 4v3h-7"/></svg>',
            },
            {
                key: 'delivered',
                label: 'Delivered',
                icon: '<svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9v9a2 2 0 01-2 2h-4a2 2 0 01-2-2V15a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-9z"/></svg>',
            },
        ];

        const seenKeys = Array.isArray(timeline) ? timeline.map((t) => t && t.key).filter(Boolean) : [];
        const seen = new Set(seenKeys.map((k) => normalizeTrackingKeyToStepKey(k)));

        const currentStepKey = normalizeTrackingKeyToStepKey(currentKey);
        const currentIndex = Math.max(0, steps.findIndex((s) => s.key === currentStepKey));

        wrap.innerHTML = '';

        steps.forEach((s, idx) => {
            const isActive = idx === currentIndex;
            const isCompleted = seen.has(s.key) || idx < currentIndex;
            const isPending = !isActive && !isCompleted;

            const step = document.createElement('div');
            step.className = 'flex flex-col items-center flex-1 relative';

            const indicator = document.createElement('div');
            indicator.className = `w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all ${isCompleted ? 'bg-emerald-600 dark:bg-emerald-500 text-white' : isActive ? 'bg-indigo-600 dark:bg-indigo-500 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-300'}`;
            indicator.innerHTML = s.icon;

            step.appendChild(indicator);

            if (idx < steps.length - 1) {
                const connector = document.createElement('div');
                connector.className = `absolute top-5 left-[calc(50%+20px)] right-[calc(-50%-20px)] h-0.5 transition-all ${isCompleted ? 'bg-emerald-600 dark:bg-emerald-500' : 'bg-slate-200 dark:bg-slate-700'}`;
                step.appendChild(connector);
            }

            const label = document.createElement('div');
            label.className = `text-xs font-semibold mt-2 text-center ${isPending ? 'text-slate-500 dark:text-slate-400' : 'text-slate-900 dark:text-slate-200'}`;
            label.textContent = s.label;
            step.appendChild(label);

            wrap.appendChild(step);
        });
    };

    const renderTrackingTimeline = (wrap, timeline) => {
        if (!wrap) return;

        wrap.innerHTML = '';

        const items = Array.isArray(timeline) ? timeline : [];
        if (items.length === 0) {
            const row = document.createElement('div');
            row.className = 'text-sm font-semibold text-slate-500';
            row.textContent = 'No tracking events yet.';
            wrap.appendChild(row);
            return;
        }

        items.forEach((ev) => {
            const icon = trackingIconForKey(ev && ev.key ? ev.key : '');
            const row = document.createElement('div');
            row.className = 'rounded-2xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-950';
            row.innerHTML = `
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0 flex items-start gap-3">
                        <div class="mt-0.5 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl ${icon.bg}">${icon.svg}</div>
                        <div class="min-w-0">
                            <div class="text-xs font-black uppercase tracking-widest text-slate-500">${toTitle(ev && ev.key ? ev.key : '')}</div>
                            <div class="mt-1 text-sm font-black text-slate-900 dark:text-slate-100">${(ev && ev.title) ? String(ev.title) : ''}</div>
                            ${(ev && ev.description) ? `<div class="mt-1 text-xs font-semibold text-slate-500">${String(ev.description)}</div>` : ''}
                        </div>
                    </div>
                    <div class="shrink-0 text-right text-xs font-semibold text-slate-500">${formatDate(ev && ev.timestamp ? ev.timestamp : '')}</div>
                </div>
            `;
            wrap.appendChild(row);
        });
    };

    const statusBadgeClass = (status) => {
        switch (String(status || '').toLowerCase()) {
            case 'paid':
                return 'bg-emerald-50 text-emerald-700 border-emerald-200';
            case 'failed':
                return 'bg-red-50 text-red-700 border-red-200';
            case 'cancelled':
                return 'bg-slate-100 text-slate-700 border-slate-200';
            case 'shipped':
                return 'bg-indigo-50 text-indigo-700 border-indigo-200';
            case 'delivered':
                return 'bg-blue-50 text-blue-700 border-blue-200';
            default:
                return 'bg-amber-50 text-amber-700 border-amber-200';
        }
    };

    const statusBadgeIcon = (status) => {
        const s = String(status || '').toLowerCase();
        if (s === 'paid') {
            return '<svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
        }

        if (s === 'processing') {
            return '<svg class="h-3.5 w-3.5 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V3m0 18v-3m9-6h-3M6 12H3m15.364 6.364l-2.121-2.121M8.757 8.757L6.636 6.636m12.728 0l-2.121 2.121M8.757 15.243l-2.121 2.121"/></svg>';
        }

        if (s === 'shipped') {
            return '<svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h11v12H3V5zm11 5h4l3 4v3h-7"/></svg>';
        }

        if (s === 'delivered') {
            return '<svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9v9a2 2 0 01-2 2h-4a2 2 0 01-2-2V15a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-9z"/></svg>';
        }

        if (s === 'failed') {
            return '<svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>';
        }

        if (s === 'cancelled') {
            return '<svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
        }

        // pending / unknown
        return '<svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
    };

    const renderOrdersList = async () => {
        const root = document.getElementById('hcOrdersRoot');
        if (!root) return;

        const loading = document.getElementById('hcOrdersLoading');
        const error = document.getElementById('hcOrdersError');
        const empty = document.getElementById('hcOrdersEmpty');
        const list = document.getElementById('hcOrdersList');
        const pager = document.getElementById('hcOrdersPager');

        const show = (el, yes) => el && el.classList.toggle('hidden', !yes);
        const setText = (el, text) => { if (el) el.textContent = String(text ?? ''); };

        let currentUrl = '/api/v1/orders';

        const load = async (url) => {
            show(loading, true);
            show(error, false);
            show(empty, false);
            if (list) list.innerHTML = '';
            if (pager) pager.innerHTML = '';

            try {
                const res = await apiFetch(url);
                const orders = res.data || [];

                if (!Array.isArray(orders) || orders.length === 0) {
                    show(empty, true);
                    return;
                }

                orders.forEach((o) => {
                    const row = document.createElement('a');
                    row.href = joinUrl(getBaseUrl(), `/orders/${o.id}`);
                    row.className = 'block rounded-3xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-800 dark:bg-slate-900';

                    row.innerHTML = `
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="text-xs font-black uppercase tracking-widest text-slate-500">Order</div>
                                <div class="mt-1 text-sm font-black text-slate-900 dark:text-slate-100">${o.order_number || `#${o.id}`}</div>
                                <div class="mt-1 text-xs font-semibold text-slate-500">${formatDate(o.created_at)}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs font-black uppercase tracking-widest text-slate-500">Total</div>
                                <div class="mt-1 text-base font-black text-slate-900 dark:text-slate-100">₦${formatMoney(o.total)}</div>
                                <div class="mt-2 inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-[10px] font-black uppercase tracking-wide ${statusBadgeClass(o.status)}">${statusBadgeIcon(o.status)}<span>${o.status || 'pending'}</span></div>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between gap-4">
                            <div class="text-xs font-semibold text-slate-500">Items: ${o.item_count || 0}</div>
                            <div class="text-xs font-semibold text-slate-500">Payment: ${o.payment_status || 'n/a'}</div>
                        </div>
                    `;

                    list && list.appendChild(row);
                });

                const links = res.meta && res.meta.links ? res.meta.links : (res.links ? Object.values(res.links) : null);
                if (pager && res.links && (res.links.prev || res.links.next)) {
                    const wrap = document.createElement('div');
                    wrap.className = 'flex items-center justify-between';

                    const prev = document.createElement('button');
                    prev.type = 'button';
                    prev.className = 'rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-black text-slate-700 hover:bg-slate-50 disabled:opacity-50 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 dark:hover:bg-slate-900';
                    prev.textContent = 'Previous';
                    prev.disabled = !res.links.prev;
                    prev.addEventListener('click', () => {
                        if (!res.links.prev) return;
                        currentUrl = res.links.prev;
                        load(currentUrl);
                    });

                    const next = document.createElement('button');
                    next.type = 'button';
                    next.className = prev.className;
                    next.textContent = 'Next';
                    next.disabled = !res.links.next;
                    next.addEventListener('click', () => {
                        if (!res.links.next) return;
                        currentUrl = res.links.next;
                        load(currentUrl);
                    });

                    wrap.appendChild(prev);
                    wrap.appendChild(next);
                    pager.appendChild(wrap);
                }

            } catch (e) {
                show(error, true);
                setText(error, e.message || 'Unable to load orders');
            } finally {
                show(loading, false);
            }
        };

        load(currentUrl);
    };

    const renderOrderDetail = async () => {
        const root = document.getElementById('hcOrderRoot');
        if (!root) return;

        const orderId = root.getAttribute('data-order-id');
        if (!orderId) return;

        const loading = document.getElementById('hcOrderLoading');
        const error = document.getElementById('hcOrderError');
        const body = document.getElementById('hcOrderBody');

        const show = (el, yes) => el && el.classList.toggle('hidden', !yes);
        const setHtml = (el, html) => { if (el) el.innerHTML = html; };
        const setText = (el, text) => { if (el) el.textContent = String(text ?? ''); };

        show(loading, true);
        show(error, false);
        show(body, false);

        try {
            const res = await apiFetch(`/api/v1/orders/${orderId}`);
            const o = res.data;

            if (!o) throw new Error('Order not found');

            setText(document.getElementById('hcOrderNumber'), o.order_number || `#${o.id}`);

            const statusEl = document.getElementById('hcOrderStatus');
            if (statusEl) {
                const base = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wide border';
                const cls = statusBadgeClass(o.status);
                statusEl.className = `${base} ${cls}`;
                statusEl.innerHTML = `${statusBadgeIcon(o.status)}<span>${o.status || 'pending'}</span>`;
            }
            setText(document.getElementById('hcOrderDate'), formatDate(o.created_at));
            setText(document.getElementById('hcOrderTotal'), `₦${formatMoney(o.total)}`);

            const itemsWrap = document.getElementById('hcOrderItems');
            if (itemsWrap) {
                itemsWrap.innerHTML = '';
                (o.items || []).forEach((it) => {
                    const row = document.createElement('div');
                    row.className = 'rounded-2xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-950';

                    const thumb = it.image && it.image.url ? String(it.image.url) : '';

                    const safeTitle = (it.title || 'Item').replace(/\"/g, '&quot;');
                    const listingSlug = it.listing_slug ? String(it.listing_slug) : '';
                    const productUrl = listingSlug ? joinUrl(getBaseUrl(), `/listing/${listingSlug}#hcListingReviewsRoot`) : null;

                    const canReview = it.review && it.review.can_review;
                    const reviewed = it.review && it.review.reviewed;
                    const reviewPanelId = `hcReviewPanel_${it.id}`;
                    const reviewFormId = `hcReviewForm_${it.id}`;

                    row.innerHTML = `
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0 flex items-start gap-3">
                                <div class="h-14 w-14 shrink-0 overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-900">
                                    ${thumb ? `<img src="${thumb}" alt="${safeTitle}" class="h-full w-full object-cover" loading="lazy" />` : `<div class="h-full w-full flex items-center justify-center text-slate-400"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5zm4 10l2-2 3 3 5-5 3 3"/></svg></div>`}
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-black text-slate-900 truncate dark:text-slate-100">${it.title || 'Item'}</div>
                                    <div class="mt-1 text-xs font-semibold text-slate-500">Qty: ${it.quantity} · ₦${formatMoney(it.unit_price)} each</div>
                                    ${productUrl ? `<a class="mt-2 inline-flex items-center gap-2 text-xs font-black text-indigo-600 hover:text-indigo-700" href="${productUrl}">View product reviews</a>` : ''}
                                </div>
                            </div>
                            <div class="shrink-0 text-right">
                                <div class="text-sm font-black text-slate-900 whitespace-nowrap dark:text-slate-100">₦${formatMoney(it.subtotal)}</div>
                                ${reviewed ? `<div class="mt-2 inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-[10px] font-black uppercase tracking-wide text-emerald-700">Reviewed</div>` : ''}
                                ${canReview ? `<button type="button" class="mt-2 inline-flex items-center justify-center rounded-2xl bg-slate-900 px-3 py-2 text-xs font-black text-white hover:bg-slate-800 dark:bg-white dark:text-slate-900" data-toggle-review="${it.id}">Leave review</button>` : ''}
                            </div>
                        </div>

                        ${canReview ? `
                            <div id="${reviewPanelId}" class="mt-4 hidden rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-900">
                                <form id="${reviewFormId}" class="space-y-3">
                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                        <div>
                                            <label class="text-xs font-black uppercase tracking-widest text-slate-500">Rating</label>
                                            <select name="rating" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200" required>
                                                <option value="">Select</option>
                                                <option value="5">5 - Excellent</option>
                                                <option value="4">4 - Good</option>
                                                <option value="3">3 - Okay</option>
                                                <option value="2">2 - Poor</option>
                                                <option value="1">1 - Terrible</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-xs font-black uppercase tracking-widest text-slate-500">Title (optional)</label>
                                            <input name="title" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200" placeholder="Short summary" />
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-xs font-black uppercase tracking-widest text-slate-500">Review</label>
                                        <textarea name="body" rows="4" class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200" placeholder="Share your experience" required></textarea>
                                    </div>

                                    <div>
                                        <label class="text-xs font-black uppercase tracking-widest text-slate-500">Images (optional, max 5)</label>
                                        <input name="images" type="file" accept="image/*" multiple class="mt-2 block w-full text-sm" />
                                        <div class="mt-2 grid grid-cols-5 gap-2" data-preview></div>
                                    </div>

                                    <div class="hidden rounded-2xl border border-red-200 bg-red-50 p-3 text-xs font-semibold text-red-700" data-error></div>

                                    <button type="submit" class="w-full rounded-2xl bg-indigo-600 px-4 py-2.5 text-sm font-black text-white hover:bg-indigo-700">Submit review</button>
                                </form>
                            </div>
                        ` : ''}
                    `;
                    itemsWrap.appendChild(row);

                    const toggleBtn = row.querySelector(`[data-toggle-review="${it.id}"]`);
                    const panel = row.querySelector(`#${reviewPanelId}`);
                    if (toggleBtn && panel) {
                        toggleBtn.addEventListener('click', () => {
                            panel.classList.toggle('hidden');
                        });
                    }

                    const form = row.querySelector(`#${reviewFormId}`);
                    if (form) {
                        const preview = form.querySelector('[data-preview]');
                        const errorEl = form.querySelector('[data-error]');
                        const fileInput = form.querySelector('input[name="images"]');

                        const showError = (msg) => {
                            if (!errorEl) return;
                            errorEl.textContent = String(msg || 'Unable to submit review');
                            errorEl.classList.remove('hidden');
                        };
                        const clearError = () => {
                            if (!errorEl) return;
                            errorEl.textContent = '';
                            errorEl.classList.add('hidden');
                        };

                        if (fileInput) {
                            fileInput.addEventListener('change', () => {
                                if (!preview) return;
                                preview.innerHTML = '';
                                const files = Array.from(fileInput.files || []).slice(0, 5);
                                files.forEach((f) => {
                                    const url = URL.createObjectURL(f);
                                    const a = document.createElement('a');
                                    a.href = url;
                                    a.target = '_blank';
                                    a.className = 'block overflow-hidden rounded-xl border border-slate-200 dark:border-slate-800';
                                    a.innerHTML = `<img src="${url}" class="h-12 w-full object-cover" />`;
                                    preview.appendChild(a);
                                });
                            });
                        }

                        form.addEventListener('submit', async (e) => {
                            e.preventDefault();
                            clearError();

                            const fd = new FormData();
                            fd.append('listing_id', String(it.listing_id));
                            fd.append('order_item_id', String(it.id));
                            fd.append('rating', String(form.rating.value || ''));
                            fd.append('title', String(form.title.value || ''));
                            fd.append('body', String(form.body.value || ''));

                            const files = fileInput ? Array.from(fileInput.files || []).slice(0, 5) : [];
                            files.forEach((f) => fd.append('images[]', f));

                            try {
                                await apiFetchMultipart('/api/v1/reviews', fd);

                                // Reload order to update reviewed/can_review state.
                                await renderOrderDetail();
                            } catch (err) {
                                showError((err && err.message) ? err.message : 'Unable to submit review');
                            }
                        });
                    }
                });
            }

            const pay = o.payment;
            setText(document.getElementById('hcOrderPaymentStatus'), pay ? (pay.status || 'n/a') : 'n/a');
            setText(document.getElementById('hcOrderPaymentRef'), pay ? (pay.reference || '') : '');

            const tracking = document.getElementById('hcOrderTracking');
            if (tracking) {
                tracking.textContent = 'Loading tracking…';
            }

            try {
                const tr = await apiFetch(`/api/v1/orders/${orderId}/tracking`);

                const stepper = document.getElementById('hcOrderTrackingStepper');
                const timelineWrap = document.getElementById('hcOrderTrackingTimeline');

                const currentKey = tr && tr.current_status ? String(tr.current_status) : '';
                const timeline = tr && tr.timeline ? tr.timeline : [];

                renderTrackingStepper(stepper, timeline, currentKey);
                renderTrackingTimeline(timelineWrap, timeline);

                if (tracking) {
                    tracking.textContent = currentKey ? toTitle(currentKey) : 'No tracking updates yet.';
                }
            } catch (e) {
                const stepper = document.getElementById('hcOrderTrackingStepper');
                const timelineWrap = document.getElementById('hcOrderTrackingTimeline');

                if (stepper) stepper.innerHTML = '';
                if (timelineWrap) timelineWrap.innerHTML = '';

                if (tracking) {
                    tracking.textContent = 'Unable to load tracking.';
                }
            }

            const histWrap = document.getElementById('hcOrderHistory');
            if (histWrap) {
                histWrap.innerHTML = '';
                const hist = Array.isArray(o.status_history) ? o.status_history : [];

                if (hist.length === 0) {
                    const row = document.createElement('div');
                    row.className = 'text-sm font-semibold text-slate-500';
                    row.textContent = 'No status history available yet.';
                    histWrap.appendChild(row);
                } else {
                    hist.forEach((h) => {
                        const row = document.createElement('div');
                        row.className = 'flex items-start justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-950';
                        row.innerHTML = `
                            <div>
                                <div class="text-xs font-black uppercase tracking-widest text-slate-500">${formatDate(h.created_at)}</div>
                                <div class="mt-1 text-sm font-black text-slate-900 dark:text-slate-100">${h.to_status || ''}</div>
                                ${h.note ? `<div class="mt-1 text-xs font-semibold text-slate-500">${h.note}</div>` : ''}
                            </div>
                        `;
                        histWrap.appendChild(row);
                    });
                }
            }

            show(body, true);
        } catch (e) {
            show(error, true);
            setText(error, e.message || 'Unable to load order');
        } finally {
            show(loading, false);
        }
    };

    window.addEventListener('DOMContentLoaded', () => {
        renderOrdersList();
        renderOrderDetail();
    });
})();
