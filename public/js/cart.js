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
            'Content-Type': 'application/json',
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

    const formatMoney = (v) => {
        const n = Number(v || 0);
        return n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    };

    const uid = () => `${Date.now()}-${Math.random().toString(16).slice(2)}`;

    const CartStore = {
        state: {
            open: false,
            loading: false,
            pending: {},
            items: [],
            total_items: 0,
            subtotal: '0.00',
            total: '0.00',
            error: null,
            toasts: [],
        },

        applyState(data) {
            if (!data || typeof data !== 'object') return;
            Object.assign(this.state, data);
        },

        toast(type, message) {
            const id = uid();
            this.state.toasts = [...(this.state.toasts || []), { id, type, message }];

            window.setTimeout(() => {
                this.state.toasts = (this.state.toasts || []).filter((t) => t.id !== id);
            }, 3000);
        },

        setPending(key, val) {
            this.state.pending = { ...(this.state.pending || {}), [key]: Boolean(val) };
        },

        isPending(key) {
            return Boolean(this.state.pending && this.state.pending[key]);
        },

        async refresh() {
            this.state.loading = true;
            this.state.error = null;
            try {
                const data = await apiFetch('/api/v1/cart');
                this.applyState(data);
            } catch (e) {
                this.state.error = e.message || 'Unable to load cart';
                this.toast('error', this.state.error);
            } finally {
                this.state.loading = false;
            }
        },

        toggle(open) {
            this.state.open = typeof open === 'boolean' ? open : !this.state.open;
            if (this.state.open && this.state.items.length === 0) {
                this.refresh();
            }
        },

        async add(listingId, qty = 1) {
            this.state.error = null;
            const key = `add:${listingId}`;
            this.setPending(key, true);
            try {
                const data = await apiFetch('/api/v1/cart/items', {
                    method: 'POST',
                    body: JSON.stringify({ listing_id: listingId, quantity: qty }),
                });
                this.applyState(data);
                this.toast('success', 'Added to cart');
            } catch (e) {
                this.state.error = e.message || 'Unable to add item';
                this.toast('error', this.state.error);
                throw e;
            } finally {
                this.setPending(key, false);
            }
        },

        async setQty(itemId, qty) {
            this.state.error = null;
            const key = `item:${itemId}`;
            this.setPending(key, true);
            try {
                const data = await apiFetch(`/api/v1/cart/items/${itemId}`, {
                    method: 'PATCH',
                    body: JSON.stringify({ quantity: qty }),
                });
                this.applyState(data);
            } catch (e) {
                this.state.error = e.message || 'Unable to update cart';
                this.toast('error', this.state.error);
                throw e;
            } finally {
                this.setPending(key, false);
            }
        },

        async remove(itemId) {
            this.state.error = null;
            const key = `item:${itemId}`;
            this.setPending(key, true);
            try {
                const data = await apiFetch(`/api/v1/cart/items/${itemId}`, { method: 'DELETE' });
                this.applyState(data);
                this.toast('success', 'Removed from cart');
            } catch (e) {
                this.state.error = e.message || 'Unable to remove item';
                this.toast('error', this.state.error);
                throw e;
            } finally {
                this.setPending(key, false);
            }
        },

        async clear() {
            this.state.error = null;
            const key = 'clear';
            this.setPending(key, true);
            try {
                const data = await apiFetch('/api/v1/cart/clear', { method: 'DELETE' });
                this.applyState(data);
                this.toast('success', 'Cart cleared');
            } catch (e) {
                this.state.error = e.message || 'Unable to clear cart';
                this.toast('error', this.state.error);
                throw e;
            } finally {
                this.setPending(key, false);
            }
        },

        formatMoney,
    };

    window.hcCart = CartStore;

    const initAlpineCartStore = () => {
        if (!window.Alpine) return false;

        try {
            const existing = window.Alpine.store('hcCart');
            if (!existing) {
                window.Alpine.store('hcCart', window.Alpine.reactive(CartStore.state));
            }
            CartStore.state = window.Alpine.store('hcCart');
            return true;
        } catch (_) {
            return false;
        }
    };

    document.addEventListener('alpine:init', () => {
        initAlpineCartStore();
    });

    window.addEventListener('DOMContentLoaded', () => {
        initAlpineCartStore();
        CartStore.refresh();
    });
})();
