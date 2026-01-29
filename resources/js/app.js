import './bootstrap';

(() => {
    const root = document.documentElement;

    const getPreferredTheme = () => {
        const stored = window.localStorage.getItem('theme');
        if (stored === 'light' || stored === 'dark') return stored;
        return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    };

    const applyTheme = (theme) => {
        if (theme === 'dark') root.classList.add('dark');
        else root.classList.remove('dark');
        window.localStorage.setItem('theme', theme);
    };

    applyTheme(getPreferredTheme());

    document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const next = root.classList.contains('dark') ? 'light' : 'dark';
            applyTheme(next);
        });
    });
})();

(() => {
    const btn = document.getElementById('adminMenuBtn');
    const drawer = document.getElementById('adminDrawer');
    const overlay = document.getElementById('adminOverlay');

    if (!btn || !drawer || !overlay) return;

    /**
     * Toggles the drawer state. 
     * Uses a data attribute for styling (better for CSS transitions)
     */
    const toggleDrawer = (forceClose = false) => {
        const isOpen = drawer.getAttribute('data-state') === 'open';
        const shouldClose = forceClose || isOpen;

        if (shouldClose) {
            drawer.setAttribute('data-state', 'closed');
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        } else {
            drawer.setAttribute('data-state', 'open');
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    };

    // Click Events
    btn.addEventListener('click', () => toggleDrawer());
    overlay.addEventListener('click', () => toggleDrawer(true));

    // Keyboard Accessibility (Esc Key)
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && drawer.getAttribute('data-state') === 'open') {
            toggleDrawer(true);
        }
    });

    // Handle Link Clicks (Mobile-only auto-close)
    drawer.addEventListener('click', (e) => {
        if (e.target.closest('a') && window.innerWidth < 1024) {
            toggleDrawer(true);
        }
    });
})();