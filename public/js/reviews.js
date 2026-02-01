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

    const apiFetch = async (path, options = {}) => {
        const url = path.startsWith('http') ? path : joinUrl(getBaseUrl(), path);

        const headers = {
            Accept: 'application/json',
            ...(options.headers || {}),
        };

        const csrf = getCsrfToken();
        if (csrf) headers['X-CSRF-TOKEN'] = csrf;

        const res = await fetch(url, {
            credentials: 'same-origin',
            ...options,
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

    const show = (el, yes) => {
        if (!el) return;
        el.classList.toggle('hidden', !yes);
    };

    const setText = (el, text) => {
        if (!el) return;
        el.textContent = String(text ?? '');
    };

    const escapeHtml = (s) => {
        return String(s ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    };

    const starSvg = (filled) => {
        return filled
            ? '<svg class="h-4 w-4 text-amber-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.965a1 1 0 00.95.69h4.17c.969 0 1.371 1.24.588 1.81l-3.375 2.453a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.539 1.118l-3.375-2.453a1 1 0 00-1.176 0l-3.375 2.453c-.784.57-1.838-.197-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.392c-.783-.57-.38-1.81.588-1.81h4.17a1 1 0 00.95-.69l1.291-3.965z"/></svg>'
            : '<svg class="h-4 w-4 text-slate-300 dark:text-slate-700" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.965a1 1 0 00.95.69h4.17c.969 0 1.371 1.24.588 1.81l-3.375 2.453a1 1 0 00-.364 1.118l1.287 3.966c.3.921-.755 1.688-1.539 1.118l-3.375-2.453a1 1 0 00-1.176 0l-3.375 2.453c-.784.57-1.838-.197-1.539-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.05 9.392c-.783-.57-.38-1.81.588-1.81h4.17a1 1 0 00.95-.69l1.291-3.965z"/></svg>';
    };

    const renderStars = (wrap, rating) => {
        if (!wrap) return;
        const r = Math.max(0, Math.min(5, Number(rating || 0)));
        wrap.innerHTML = '';
        for (let i = 1; i <= 5; i += 1) {
            const span = document.createElement('span');
            span.innerHTML = starSvg(i <= Math.round(r));
            wrap.appendChild(span);
        }
    };

    const renderBreakdown = (wrap, breakdown, total) => {
        if (!wrap) return;
        const t = Math.max(0, Number(total || 0));
        wrap.innerHTML = '';

        [5, 4, 3, 2, 1].forEach((k) => {
            const count = Number((breakdown && breakdown[k]) ? breakdown[k] : 0);
            const pct = t > 0 ? Math.round((count / t) * 100) : 0;

            const row = document.createElement('div');
            row.className = 'flex items-center gap-3';
            row.innerHTML = `
                <div class="w-10 text-xs font-black text-slate-700 dark:text-slate-200">${k}★</div>
                <div class="flex-1 h-2 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                    <div class="h-2 bg-amber-400" style="width:${pct}%"></div>
                </div>
                <div class="w-12 text-right text-xs font-semibold text-slate-500">${count}</div>
            `;
            wrap.appendChild(row);
        });
    };

    const renderReviewRow = (r) => {
        const stars = new Array(5).fill(0).map((_, idx) => starSvg(idx < (r.rating || 0))).join('');
        const images = Array.isArray(r.images) ? r.images : [];

        const gallery = images.length
            ? `<div class="mt-3 grid grid-cols-5 gap-2">${images.map((img) => `
                <a href="${escapeHtml(img.url)}" target="_blank" class="block overflow-hidden rounded-xl border border-slate-200 dark:border-slate-800">
                    <img src="${escapeHtml(img.url)}" class="h-14 w-full object-cover" loading="lazy" />
                </a>
            `).join('')}</div>`
            : '';

        return `
            <div class="rounded-2xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-950">
                <div class="flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <div class="flex items-center gap-1">${stars}</div>
                            <div class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-black uppercase tracking-wide text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
                                Verified
                            </div>
                        </div>
                        ${r.title ? `<div class="mt-2 text-sm font-black text-slate-900 dark:text-slate-100">${escapeHtml(r.title)}</div>` : ''}
                        <div class="mt-1 text-sm font-semibold text-slate-700 dark:text-slate-200 whitespace-pre-line">${escapeHtml(r.body || '')}</div>
                        ${gallery}
                    </div>
                    <div class="shrink-0 text-right">
                        <div class="text-xs font-semibold text-slate-500">${r.created_at ? escapeHtml(new Date(r.created_at).toLocaleDateString()) : ''}</div>
                        <div class="mt-2 flex items-center justify-end gap-2">
                            ${r.user && r.user.avatar ? `<img src="${escapeHtml(r.user.avatar)}" class="h-7 w-7 rounded-xl object-cover" referrerpolicy="no-referrer" />` : '<div class="h-7 w-7 rounded-xl bg-slate-100 dark:bg-slate-800"></div>'}
                            <div class="max-w-[130px] truncate text-xs font-black text-slate-700 dark:text-slate-200">${escapeHtml((r.user && r.user.name) ? r.user.name : 'Customer')}</div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    };

    const renderPager = (wrap, links, onNavigate) => {
        if (!wrap) return;
        wrap.innerHTML = '';

        const prevUrl = links && links.prev ? links.prev : null;
        const nextUrl = links && links.next ? links.next : null;

        const btnClass = 'rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-black text-slate-700 hover:bg-slate-50 disabled:opacity-50 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-200 dark:hover:bg-slate-900';

        const prev = document.createElement('button');
        prev.type = 'button';
        prev.className = btnClass;
        prev.textContent = 'Previous';
        prev.disabled = !prevUrl;
        prev.addEventListener('click', () => {
            if (!prevUrl) return;
            onNavigate(prevUrl);
        });

        const next = document.createElement('button');
        next.type = 'button';
        next.className = btnClass;
        next.textContent = 'Next';
        next.disabled = !nextUrl;
        next.addEventListener('click', () => {
            if (!nextUrl) return;
            onNavigate(nextUrl);
        });

        wrap.appendChild(prev);
        wrap.appendChild(next);
    };

    const init = () => {
        const root = document.getElementById('hcListingReviewsRoot');
        if (!root) return;

        const listingId = root.getAttribute('data-listing-id');
        if (!listingId) return;

        const el = {
            avg: document.getElementById('hcReviewsAvg'),
            stars: document.getElementById('hcReviewsStars'),
            count: document.getElementById('hcReviewsCount'),
            breakdown: document.getElementById('hcReviewsBreakdown'),
            list: document.getElementById('hcReviewsList'),
            empty: document.getElementById('hcReviewsEmpty'),
            error: document.getElementById('hcReviewsError'),
            pager: document.getElementById('hcReviewsPager'),
        };

        let currentReviewsUrl = `/api/v1/listings/${listingId}/reviews`;

        const setError = (msg) => {
            if (!el.error) return;
            el.error.textContent = String(msg || 'Unable to load reviews');
            show(el.error, true);
        };

        const clearError = () => {
            show(el.error, false);
            if (el.error) el.error.textContent = '';
        };

        const loadReviews = async (url) => {
            clearError();
            if (el.list) el.list.innerHTML = '';
            show(el.empty, false);

            try {
                const res = await apiFetch(url);

                setText(el.avg, Number(res.average_rating || 0).toFixed(1));
                setText(el.count, `${Number(res.reviews_count || 0)} reviews`);
                renderStars(el.stars, res.average_rating);
                renderBreakdown(el.breakdown, res.rating_breakdown || {}, res.reviews_count || 0);

                const payload = res.reviews || {};
                const reviews = payload.data || [];

                if (!Array.isArray(reviews) || reviews.length === 0) {
                    show(el.empty, true);
                    renderPager(el.pager, { prev: null, next: null }, () => {});
                    return;
                }

                if (el.list) {
                    el.list.innerHTML = reviews.map(renderReviewRow).join('');
                }

                currentReviewsUrl = url;
                renderPager(el.pager, payload.links || {}, (nextUrl) => {
                    loadReviews(nextUrl);
                });
            } catch (e) {
                setError(e.message || 'Unable to load reviews');
            }
        };

        loadReviews(currentReviewsUrl);
    };

    window.addEventListener('DOMContentLoaded', init);
})();
