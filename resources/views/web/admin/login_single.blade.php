<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | HomeCycle</title>
    <link rel="icon" type="image/png" href="{{ asset('images/homecycle_favicon.png') }}">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg: #0f172a;
            --card-bg: rgba(255, 255, 255, 0.98);
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --radius: 12px;
            --shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        * { box-sizing: border-box; transition: all 0.2s ease; }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--bg);
            background-image: 
                radial-gradient(at 0% 0%, rgba(79, 70, 229, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(59, 130, 246, 0.15) 0px, transparent 50%);
            padding: 20px;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: var(--card-bg);
            border-radius: 24px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(255, 255, 255, 0.7);
            overflow: hidden;
            padding: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-box {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .logo-box img {
            height: 52px;
            width: auto;
            display: block;
        }

        h1 {
            font-size: 24px;
            color: var(--text-main);
            margin: 0;
            letter-spacing: -0.025em;
        }

        .subtitle {
            color: var(--text-muted);
            font-size: 14px;
            margin-top: 8px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 8px;
            margin-left: 4px;
        }

        input {
            width: 100%;
            padding: 12px 16px;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            font-size: 15px;
            background: #f8fafc;
            outline: none;
        }

        input:focus {
            background: #fff;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-pw {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
        }

        .toggle-pw:hover { color: var(--primary); }

        .btn-submit {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            padding: 14px;
            border-radius: var(--radius);
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
        }

        .btn-submit:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
        }

        .btn-submit:active { transform: translateY(0); }

        .footer {
            margin-top: 32px;
            text-align: center;
            border-top: 1px solid var(--border);
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
            font-size: 13px;
        }

        .footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .footer a:hover { text-decoration: underline; }

        .error-msg {
            color: #ef4444;
            font-size: 12px;
            margin-top: 6px;
            margin-left: 4px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="header">
        <div class="logo-box">
            <img src="{{ asset('images/homecycle_dark_bg.png') }}" alt="HomeCycle" />
        </div>
        <h1>Admin Portal</h1>
        <p class="subtitle">Enter your credentials to continue</p>
    </div>

    <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="admin@homecycle.com" required autocomplete="username">
            @error('email')
                <div class="error-msg">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="hcAdminPassword">Password</label>
            <div class="password-wrapper">
                <input type="password" id="hcAdminPassword" name="password" placeholder="••••••••" required autocomplete="current-password">
                <button type="button" id="hcAdminPwToggle" class="toggle-pw" aria-label="Toggle password visibility">
                    <svg class="icon-show" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg class="icon-hide" style="display:none" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a10.056 10.056 0 012.602-4.192M9.88 9.88A3 3 0 0114.12 14.12M6.228 6.228L3 3m3.228 3.228l14.544 14.544M21 21l-3.228-3.228M17.772 17.772A10.057 10.057 0 0021.543 12c-1.274-4.057-5.065-7-9.543-7-1.01 0-1.99.149-2.915.427"/></svg>
                </button>
            </div>
            @error('password')
                <div class="error-msg">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-submit">Sign In</button>
    </form>

    <div class="footer">
        <span style="color: var(--text-muted)">HomeCycle &copy; {{ date('Y') }}</span>
        <a href="{{ route('web.home') }}">Return to Shop</a>
    </div>
</div>

<script>
    const passwordInput = document.getElementById('hcAdminPassword');
    const toggleBtn = document.getElementById('hcAdminPwToggle');
    const iconShow = toggleBtn.querySelector('.icon-show');
    const iconHide = toggleBtn.querySelector('.icon-hide');

    toggleBtn.addEventListener('click', () => {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        iconShow.style.display = isPassword ? 'none' : 'block';
        iconHide.style.display = isPassword ? 'block' : 'none';
    });
</script>

</body>
</html>