(() => {
    const storageKey = 'hc_intent';
    const softPromptDismissKey = 'hc_soft_login_dismissed';

    const getGoogleRedirectUrl = () => {
        const url = window.__hcAuth && window.__hcAuth.routes && window.__hcAuth.routes.googleRedirect;
        return typeof url === 'string' && url.length ? url : '/auth/google';
    };

    const getAuthState = () => {
        return Boolean(window.__hcAuth && window.__hcAuth.authenticated);
    };

    const setIntent = (intent) => {
        try {
            localStorage.setItem(storageKey, JSON.stringify(intent));
        } catch (_) {
        }
    };

    const getIntent = () => {
        try {
            const raw = localStorage.getItem(storageKey);
            return raw ? JSON.parse(raw) : null;
        } catch (_) {
            return null;
        }
    };

    const clearIntent = () => {
        try {
            localStorage.removeItem(storageKey);
        } catch (_) {
        }
    };

    const requireGoogleLogin = (intent) => {
        setIntent(intent);

        const params = new URLSearchParams();
        params.set('intent', intent.intent || 'unknown');
        if (intent.listing_id) params.set('listing_id', String(intent.listing_id));
        params.set('return_url', intent.return_url || window.location.href);

        window.location.href = `${getGoogleRedirectUrl()}?${params.toString()}`;
    };

    const showSoftPrompt = () => {
        if (getAuthState()) return;

        try {
            if (localStorage.getItem(softPromptDismissKey) === '1') return;
        } catch (_) {
        }

        const header = document.querySelector('header');
        if (!header) return;

        const wrap = document.createElement('div');
        wrap.className = 'mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-3';
        wrap.innerHTML = `
            <div class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur px-4 py-3 shadow-sm dark:border-slate-800 dark:bg-slate-950/70">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="text-sm font-semibold text-slate-800 dark:text-slate-100">Continue with Google for faster checkout and saved items.</div>
                    <div class="flex items-center gap-2">
                        <a href="${getGoogleRedirectUrl()}" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white hover:bg-slate-800 dark:bg-white dark:text-slate-900">Continue with Google</a>
                        <button type="button" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-bold text-slate-700 hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200" data-soft-auth-dismiss>Not now</button>
                    </div>
                </div>
            </div>
        `;

        header.insertAdjacentElement('afterend', wrap);

        const dismiss = wrap.querySelector('[data-soft-auth-dismiss]');
        if (dismiss) {
            dismiss.addEventListener('click', () => {
                try {
                    localStorage.setItem(softPromptDismissKey, '1');
                } catch (_) {
                }
                wrap.remove();
            });
        }
    };

    const resumeIntentIfPossible = () => {
        if (!getAuthState()) return;

        const intent = getIntent();
        if (!intent) return;

        if (intent.return_url && window.location.href !== intent.return_url) {
            clearIntent();
            window.location.href = intent.return_url;
            return;
        }

        clearIntent();
    };

    window.hcAuth = {
        isAuthenticated: getAuthState,
        requireGoogleLogin,
        setIntent,
        getIntent,
        clearIntent,
    };

    const bindIntentGates = () => {
        document.querySelectorAll('[data-auth-intent]').forEach((el) => {
            el.addEventListener('click', (e) => {
                if (getAuthState()) return;

                e.preventDefault();
                e.stopPropagation();

                const intent = el.getAttribute('data-auth-intent') || 'unknown';
                const listingId = el.getAttribute('data-listing-id');

                let returnUrl = window.location.href;
                if (el instanceof HTMLAnchorElement) {
                    const href = el.getAttribute('href');
                    if (href && href !== '#') {
                        try {
                            returnUrl = new URL(href, window.location.origin).toString();
                        } catch (_) {
                        }
                    }
                }

                requireGoogleLogin({
                    intent,
                    listing_id: listingId || null,
                    return_url: returnUrl,
                });
            }, { capture: true });
        });
    };

    window.addEventListener('DOMContentLoaded', () => {
        showSoftPrompt();
        resumeIntentIfPossible();
        bindIntentGates();
    });
})();
