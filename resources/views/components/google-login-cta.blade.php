@props([
    'intent' => null,
    'listingId' => null,
    'returnUrl' => null,
    'variant' => 'button',
])

@php
    $href = route('auth.google.redirect', array_filter([
        'intent' => $intent,
        'listing_id' => $listingId,
        'return_url' => $returnUrl,
    ], fn ($v) => $v !== null && $v !== ''));
@endphp

@if($variant === 'banner')
    <a href="{{ $href }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-4 py-2 text-sm font-bold text-slate-900 shadow-sm ring-1 ring-slate-200 hover:bg-slate-50 dark:bg-slate-900 dark:text-slate-100 dark:ring-slate-800">
        <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-slate-50 ring-1 ring-slate-200 dark:bg-slate-800 dark:ring-slate-700">
            <svg viewBox="0 0 24 24" class="h-4 w-4" aria-hidden="true">
                <path fill="#EA4335" d="M12 10.2v3.9h5.5c-.2 1.2-1.4 3.5-5.5 3.5-3.3 0-6-2.7-6-6s2.7-6 6-6c1.9 0 3.2.8 3.9 1.5l2.7-2.6C17.9 2.7 15.2 1.5 12 1.5 6.9 1.5 2.7 5.7 2.7 10.8S6.9 20.1 12 20.1c6.9 0 8.6-4.8 8.6-7.2 0-.5-.1-.9-.1-1.2H12z"/>
                <path fill="#34A853" d="M3.8 7.2l3.2 2.4C7.8 7.6 9.7 6.1 12 6.1c1.9 0 3.2.8 3.9 1.5l2.7-2.6C17.9 2.7 15.2 1.5 12 1.5 8.5 1.5 5.4 3.5 3.8 7.2z"/>
                <path fill="#FBBC05" d="M12 20.1c3.1 0 5.7-1 7.6-2.8l-3.5-2.7c-1 .7-2.3 1.2-4.1 1.2-3.1 0-5.7-2-6.6-4.7l-3.2 2.5c1.6 3.7 5 6.3 9.8 6.3z"/>
                <path fill="#4285F4" d="M20.6 10.8c0-.5-.1-.9-.1-1.2H12v3.9h5.5c-.3 1.4-1.3 2.6-2.7 3.4l3.5 2.7c2.1-1.9 2.3-5.1 2.3-7.8z"/>
            </svg>
        </span>
        Continue with Google
    </a>
@else
    <a href="{{ $href }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-slate-900 px-4 py-2 text-sm font-bold text-white shadow-lg shadow-slate-200 hover:bg-slate-800 active:scale-[0.99] dark:bg-white dark:text-slate-900">
        <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-white/10 ring-1 ring-white/15 dark:bg-slate-900 dark:ring-slate-800">
            <svg viewBox="0 0 24 24" class="h-4 w-4" aria-hidden="true">
                <path fill="#EA4335" d="M12 10.2v3.9h5.5c-.2 1.2-1.4 3.5-5.5 3.5-3.3 0-6-2.7-6-6s2.7-6 6-6c1.9 0 3.2.8 3.9 1.5l2.7-2.6C17.9 2.7 15.2 1.5 12 1.5 6.9 1.5 2.7 5.7 2.7 10.8S6.9 20.1 12 20.1c6.9 0 8.6-4.8 8.6-7.2 0-.5-.1-.9-.1-1.2H12z"/>
                <path fill="#34A853" d="M3.8 7.2l3.2 2.4C7.8 7.6 9.7 6.1 12 6.1c1.9 0 3.2.8 3.9 1.5l2.7-2.6C17.9 2.7 15.2 1.5 12 1.5 8.5 1.5 5.4 3.5 3.8 7.2z"/>
                <path fill="#FBBC05" d="M12 20.1c3.1 0 5.7-1 7.6-2.8l-3.5-2.7c-1 .7-2.3 1.2-4.1 1.2-3.1 0-5.7-2-6.6-4.7l-3.2 2.5c1.6 3.7 5 6.3 9.8 6.3z"/>
                <path fill="#4285F4" d="M20.6 10.8c0-.5-.1-.9-.1-1.2H12v3.9h5.5c-.3 1.4-1.3 2.6-2.7 3.4l3.5 2.7c2.1-1.9 2.3-5.1 2.3-7.8z"/>
            </svg>
        </span>
        Continue with Google
    </a>
@endif
