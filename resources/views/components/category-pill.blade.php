@props([
    'href',
    'active' => false,
])

<a href="{{ $href }}" class="inline-flex items-center rounded-full border px-3 py-1.5 text-sm font-medium transition {{ $active ? 'border-slate-900 bg-slate-900 text-white' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50' }}">
    {{ $slot }}
</a>
