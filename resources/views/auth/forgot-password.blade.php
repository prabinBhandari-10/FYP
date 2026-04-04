<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password | Lost & Found</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #4338ca;
            --primary-hover: #3730a3;
            --text-dark: #0f172a;
            --text-gray: #475569;
            --text-light: #94a3b8;
            --bg-color: #f8fafc;
            --border-color: #e2e8f0;
            --input-bg: #ffffff;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .auth-container { width: 100%; max-width: 440px; }
        .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2px;
            margin-bottom: 32px;
            text-decoration: none;
        }
        .logo {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 2px;
            color: var(--text-dark);
            text-transform: uppercase;
        }
        .logo-subtitle { font-size: 13px; font-weight: 600; color: var(--text-gray); }
        .card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 40px 36px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
            width: 100%;
        }
        .card-header { text-align: center; margin-bottom: 32px; }
        .card-title { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 22px; font-weight: 700; color: var(--text-dark); margin-bottom: 6px; letter-spacing: -0.5px; }
        .card-subtitle { font-size: 14.5px; color: var(--text-gray); line-height: 1.5; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 14px; font-weight: 600; color: var(--text-dark); margin-bottom: 8px; }
        .form-input {
            width: 100%;
            padding: 12px 14px;
            background-color: var(--input-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            color: var(--text-dark);
            transition: all 0.2s;
        }
        .form-input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(67, 56, 202, 0.1); }
        .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding: 12px 24px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: background-color 0.2s;
            text-decoration: none;
        }
        .btn:hover { background-color: var(--primary-hover); }
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; font-size: 14px; }
        .alert-success { background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error { background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .card-footer { margin-top: 24px; text-align: center; font-size: 14px; color: var(--text-gray); }
        .card-footer a { color: var(--primary); text-decoration: none; font-weight: 600; }
        .card-footer a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="auth-container">
        <a href="{{ route('home') }}" class="logo-container">
            <div class="logo">
                L
                <svg class="logo-arch" width="14" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 22A8 8 0 0 1 20 22" />
                </svg>
                ST &amp; FOUND
            </div>
            <div class="logo-subtitle">Reset your password</div>
        </a>

        <div class="card">
            <div class="card-header">
                <h1 class="card-title">Forgot password?</h1>
                <p class="card-subtitle">Enter your email and we will send a reset link.</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Email address</label>
                    <input class="form-input" type="email" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
                </div>

                <button type="submit" class="btn">Send Reset Link</button>
            </form>

            <div class="card-footer">
                <a href="{{ route('login') }}">Back to login</a>
            </div>
        </div>
    </div>
</body>
</html>
