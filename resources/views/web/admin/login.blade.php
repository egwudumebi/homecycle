@extends('web.layouts.app')

@section('title', 'Admin Login')

@section('content')
    <div class="max-w-md mx-auto">
        <div class="rounded-xl border border-slate-200 bg-white p-6">
            <h1 class="text-lg font-semibold">Admin Login</h1>
            <div class="mt-1 text-sm text-slate-600">Sign in to manage listings</div>

            <form method="POST" action="{{ route('admin.login.submit') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label class="text-xs font-medium text-slate-600">Email</label>
                    <input name="email" value="{{ old('email') }}" type="email" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                    @error('email')
                        <div class="mt-1 text-xs text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="text-xs font-medium text-slate-600">Password</label>
                    <input name="password" type="password" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                    @error('password')
                        <div class="mt-1 text-xs text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <button class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white">Login</button>
            </form>
        </div>
    </div>
@endsection
