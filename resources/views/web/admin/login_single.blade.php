<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | HomeCycle</title>
    <link rel="icon" type="image/png" href="{{ asset('images/homecycle_favicon.png') }}">
    <style>
        :root{--bg:#0b1220;--card:#ffffff;--text:#0f172a;--muted:#64748b;--border:#e2e8f0;--shadow:0 20px 60px rgba(0,0,0,.25);--shadow-sm:0 1px 2px rgba(15,23,42,.08);--indigo:#4f46e5}
        *{box-sizing:border-box}
        body{margin:0;min-height:100vh;display:grid;place-items:center;padding:24px;font-family:ui-sans-serif,system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,"Apple Color Emoji","Segoe UI Emoji";background:radial-gradient(800px 400px at 10% 0%, rgba(79,70,229,.35), transparent),radial-gradient(700px 500px at 90% 10%, rgba(59,130,246,.25), transparent),var(--bg)}
        .card{width:min(460px,100%);background:rgba(255,255,255,.96);border:1px solid rgba(226,232,240,.75);border-radius:18px;box-shadow:var(--shadow);overflow:hidden}
        .head{padding:18px 18px 0}
        .brand{display:flex;align-items:center;gap:10px}
        .logo{width:38px;height:38px;border-radius:12px;background:var(--indigo);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:950;letter-spacing:-.02em}
        .title{margin:0;font-size:16px;font-weight:950;letter-spacing:-.01em;color:var(--text)}
        .sub{margin:4px 0 0;color:var(--muted);font-size:13px}
        .body{padding:18px}
        label{display:block;margin-top:12px;font-size:12px;font-weight:900;color:var(--muted)}
        input{margin-top:8px;width:100%;border:1px solid var(--border);border-radius:12px;padding:12px 12px;font-size:14px;outline:none;background:#fff;color:var(--text);box-shadow:var(--shadow-sm)}
        input:focus{border-color:rgba(79,70,229,.55);box-shadow:0 0 0 5px rgba(79,70,229,.12)}
        .err{margin-top:8px;color:#dc2626;font-size:12px;font-weight:700}
        button{margin-top:16px;width:100%;border:none;border-radius:12px;padding:12px 14px;font-weight:950;background:linear-gradient(135deg, #111827, #0f172a);color:#fff;cursor:pointer;box-shadow:var(--shadow-sm)}
        button:hover{filter:brightness(1.05)}
        .foot{padding:14px 18px;border-top:1px solid rgba(226,232,240,.75);display:flex;justify-content:space-between;gap:12px;align-items:center}
        .foot a{color:rgba(79,70,229,.95);font-weight:900;font-size:12px;text-decoration:none}
        .foot a:hover{text-decoration:underline}
        .hint{color:var(--muted);font-size:12px}
    </style>
</head>
<body>
    <div class="card">
        <div class="head">
            <div class="brand">
                <div class="logo">HC</div>
                <div>
                    <h1 class="title">Admin Login</h1>
                    <p class="sub">Sign in to manage listings</p>
                </div>
            </div>
        </div>

        <div class="body">
            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                <label>Email</label>
                <input name="email" value="{{ old('email') }}" type="email" autocomplete="username" />
                @error('email')
                    <div class="err">{{ $message }}</div>
                @enderror

                <label>Password</label>
                <input name="password" type="password" autocomplete="current-password" />
                @error('password')
                    <div class="err">{{ $message }}</div>
                @enderror

                <button type="submit">Login</button>
            </form>
        </div>

        <div class="foot">
            <span class="hint">HomeCycle Admin</span>
            <a href="{{ route('web.home') }}">Back to store</a>
        </div>
    </div>
</body>
</html>
