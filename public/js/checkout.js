(() => {
    const lastRefKey = 'hc_last_paystack_reference';

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

    const el = {
        root: null,
        loading: null,
        error: null,
        deliveryError: null,
        deliveryName: null,
        deliveryPhone: null,
        deliveryAddress: null,
        deliveryState: null,
        deliveryCity: null,
        deliveryNotes: null,
        summary: null,
        items: null,
        subtotal: null,
        payBtn: null,
        payBtnText: null,
        success: null,
    };

    const state = {
        loading: true,
        paying: false,
        data: null,
        lastReference: null,
    };

    const setText = (node, text) => {
        if (!node) return;
        node.textContent = String(text ?? '');
    };

    const show = (node, yes) => {
        if (!node) return;
        node.classList.toggle('hidden', !yes);
    };

    const getDeliveryPayload = () => {
        return {
            delivery_name: el.deliveryName ? String(el.deliveryName.value || '').trim() : '',
            delivery_phone: el.deliveryPhone ? String(el.deliveryPhone.value || '').trim() : '',
            delivery_address: el.deliveryAddress ? String(el.deliveryAddress.value || '').trim() : '',
            delivery_state: el.deliveryState ? String(el.deliveryState.value || '').trim() : '',
            delivery_city: el.deliveryCity ? String(el.deliveryCity.value || '').trim() : '',
            delivery_notes: el.deliveryNotes ? String(el.deliveryNotes.value || '').trim() : '',
        };
    };

    const validateDelivery = () => {
        const d = getDeliveryPayload();

        if (!d.delivery_name) return { ok: false, message: 'Please enter your full name.' };
        if (!d.delivery_phone) return { ok: false, message: 'Please enter your phone number.' };
        if (!d.delivery_address) return { ok: false, message: 'Please enter your delivery address.' };
        if (!d.delivery_state) return { ok: false, message: 'Please enter your state.' };
        if (!d.delivery_city) return { ok: false, message: 'Please enter your city.' };

        return { ok: true, message: '' };
    };

    const renderItems = (items = []) => {
        if (!el.items) return;

        el.items.innerHTML = '';

        items.forEach((it) => {
            const row = document.createElement('div');
            row.className = 'flex items-start justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-4 dark:border-slate-800 dark:bg-slate-950';

            const left = document.createElement('div');
            left.className = 'min-w-0';

            const title = document.createElement('div');
            title.className = 'text-sm font-black text-slate-900 truncate dark:text-slate-100';
            title.textContent = it?.listing?.title || 'Item';

            const meta = document.createElement('div');
            meta.className = 'mt-1 text-xs font-semibold text-slate-500 dark:text-slate-400';
            meta.textContent = `Qty: ${it?.quantity || 0} · ₦${formatMoney(it?.price_at_time || 0)} each`;

            left.appendChild(title);
            left.appendChild(meta);

            const right = document.createElement('div');
            right.className = 'shrink-0 text-right';

            const amount = document.createElement('div');
            amount.className = 'text-sm font-black text-slate-900 dark:text-slate-100 whitespace-nowrap';
            amount.textContent = `₦${formatMoney(it?.subtotal || 0)}`;

            right.appendChild(amount);

            row.appendChild(left);
            row.appendChild(right);

            el.items.appendChild(row);
        });
    };

    const render = () => {
        show(el.loading, state.loading);
        show(el.error, false);
        show(el.summary, !state.loading);
        show(el.success, false);

        if (el.deliveryError) {
            show(el.deliveryError, false);
        }

        if (!state.data) return;

        const cart = state.data.cart || {};
        renderItems(cart.items || []);
        setText(el.subtotal, `₦${formatMoney(cart.subtotal || 0)}`);

        const hasItems = Array.isArray(cart.items) && cart.items.length > 0;
        const deliveryOk = validateDelivery().ok;
        if (el.payBtn) {
            el.payBtn.disabled = state.paying || !hasItems || !deliveryOk;
        }
        if (el.payBtnText) {
            el.payBtnText.textContent = state.paying ? 'Opening Paystack…' : 'Pay with Paystack';
        }
    };

    const setError = (message) => {
        show(el.error, true);
        setText(el.error, message || 'Something went wrong');
    };

    const loadSummary = async () => {
        state.loading = true;
        render();

        try {
            const res = await apiFetch('/api/v1/checkout/summary');
            state.data = res.data || null;
        } catch (e) {
            setError(e.message || 'Unable to load checkout summary');
        } finally {
            state.loading = false;
            render();
        }
    };

    const verifyPayment = async (reference) => {
        const res = await apiFetch('/api/v1/paystack/verify', {
            method: 'POST',
            body: JSON.stringify({ reference }),
        });

        return res.data || null;
    };

    const saveLastReference = (reference) => {
        try {
            localStorage.setItem(lastRefKey, JSON.stringify({ reference, ts: Date.now() }));
        } catch (_) {
        }
    };

    const getLastReference = () => {
        try {
            const raw = localStorage.getItem(lastRefKey);
            if (!raw) return null;
            const parsed = JSON.parse(raw);
            if (!parsed || !parsed.reference) return null;
            const ts = Number(parsed.ts || 0);
            if (ts && (Date.now() - ts) > 1000 * 60 * 60 * 24) return null;
            return String(parsed.reference);
        } catch (_) {
            return null;
        }
    };

    const clearLastReference = () => {
        try {
            localStorage.removeItem(lastRefKey);
        } catch (_) {
        }
    };

    const refreshCartUi = async () => {
        try {
            if (window.hcCart && typeof window.hcCart.refresh === 'function') {
                await window.hcCart.refresh();
            }
        } catch (_) {
        }
    };

    const handleVerifySuccess = async (out, orderNumber = '', orderId = null) => {
        show(el.success, true);
        show(el.error, false);
        if (el.success) {
            el.success.innerHTML = `
                <div class="text-sm font-black">Payment successful</div>
                <div class="mt-1 text-xs font-semibold opacity-80">${orderNumber ? `Order ${orderNumber} confirmed.` : 'Your order has been confirmed.'}</div>
            `;
        }
        clearLastReference();
        await refreshCartUi();

        const target = orderId ? `/orders/${orderId}` : '/orders';
        window.location.replace(joinUrl(getBaseUrl(), target));
    };

    const startPayment = async () => {
        if (state.paying) return;

        const dCheck = validateDelivery();
        if (!dCheck.ok) {
            if (el.deliveryError) {
                show(el.deliveryError, true);
                el.deliveryError.textContent = dCheck.message;
            } else {
                setError(dCheck.message);
            }
            return;
        }

        state.paying = true;
        render();

        try {
            const payload = getDeliveryPayload();
            if (payload.delivery_notes === '') {
                delete payload.delivery_notes;
            }

            const init = await apiFetch('/api/v1/paystack/initialize', { method: 'POST', body: JSON.stringify(payload) });
            const payment = init?.data?.payment;
            const order = init?.data?.order;

            const publicKey = state.data?.paystack_public_key;
            const email = state.data?.customer?.email;

            if (!publicKey) throw new Error('Paystack public key not configured');
            if (!email) throw new Error('Missing customer email');
            if (!payment?.reference) throw new Error('Missing payment reference');
            if (!payment?.amount_kobo) throw new Error('Missing payment amount');
            if (!payment?.access_code) throw new Error('Missing Paystack access code');

            state.lastReference = payment.reference;
            saveLastReference(payment.reference);

            if (payment.authorization_url) {
                window.location.href = payment.authorization_url;
                return;
            }

            if (!window.PaystackPop || typeof window.PaystackPop.setup !== 'function') {
                throw new Error('Paystack script not loaded');
            }

            const handler = window.PaystackPop.setup({
                key: publicKey,
                email,
                currency: payment.currency || 'NGN',
                ref: payment.reference,
                access_code: payment.access_code,
                onClose: () => {
                    state.paying = false;
                    render();
                },
                callback: async (response) => {
                    try {
                        const ref = response?.reference || payment.reference;
                        const out = await verifyPayment(ref);

                        const orderStatus = String(out?.order_status || '').toLowerCase();
                        if (out?.payment_status === 'success' && (orderStatus === 'paid' || orderStatus === 'processing')) {
                            await handleVerifySuccess(out, order?.order_number || '', out?.order_id || order?.id || null);
                        } else {
                            setError('Payment not completed. If you were charged, it will reflect shortly.');
                        }
                    } catch (e) {
                        setError(e.message || 'Unable to verify payment');
                    } finally {
                        state.paying = false;
                        render();
                    }
                },
            });

            handler.openIframe();
        } catch (e) {
            setError(e.message || 'Unable to start payment');
            state.paying = false;
            render();
        }
    };

    const init = () => {
        el.root = document.getElementById('hcCheckoutRoot');
        if (!el.root) return;

        el.loading = document.getElementById('hcCheckoutLoading');
        el.error = document.getElementById('hcCheckoutError');
        el.deliveryError = document.getElementById('hcCheckoutDeliveryError');
        el.deliveryName = document.getElementById('hcDeliveryName');
        el.deliveryPhone = document.getElementById('hcDeliveryPhone');
        el.deliveryAddress = document.getElementById('hcDeliveryAddress');
        el.deliveryState = document.getElementById('hcDeliveryState');
        el.deliveryCity = document.getElementById('hcDeliveryCity');
        el.deliveryNotes = document.getElementById('hcDeliveryNotes');
        el.summary = document.getElementById('hcCheckoutSummary');
        el.items = document.getElementById('hcCheckoutItems');
        el.subtotal = document.getElementById('hcCheckoutSubtotal');
        el.payBtn = document.getElementById('hcCheckoutPayBtn');
        el.payBtnText = document.getElementById('hcCheckoutPayBtnText');
        el.success = document.getElementById('hcCheckoutSuccess');

        [
            el.deliveryName,
            el.deliveryPhone,
            el.deliveryAddress,
            el.deliveryState,
            el.deliveryCity,
            el.deliveryNotes,
        ].forEach((node) => {
            if (!node) return;
            node.addEventListener('input', () => {
                render();
            });
        });

        if (el.payBtn) {
            el.payBtn.addEventListener('click', (e) => {
                e.preventDefault();
                startPayment();
            });
        }

        const params = new URLSearchParams(window.location.search || '');
        const refFromUrl = params.get('reference') || params.get('trxref');
        const fallbackRef = getLastReference();
        const refCandidate = refFromUrl || fallbackRef;

        loadSummary().then(async () => {
            if (!refCandidate) return;

            try {
                state.paying = true;
                render();

                const out = await verifyPayment(refCandidate);

                const orderStatus = String(out?.order_status || '').toLowerCase();
                if (out?.payment_status === 'success' && (orderStatus === 'paid' || orderStatus === 'processing')) {
                    await handleVerifySuccess(out, '', out?.order_id || null);
                } else {
                    setError('Payment not completed. If you were charged, it will reflect shortly.');
                }
            } catch (e) {
                setError(e.message || 'Unable to verify payment');
            } finally {
                state.paying = false;
                render();

                try {
                    const url = new URL(window.location.href);
                    url.searchParams.delete('reference');
                    url.searchParams.delete('trxref');
                    window.history.replaceState({}, document.title, url.toString());
                } catch (_) {
                }
            }
        });

        window.addEventListener('pageshow', () => {
            loadSummary();
            refreshCartUi();
        });
    };

    window.addEventListener('DOMContentLoaded', init);
})();
