<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Lost and Found Management System</title>
    <style>
        :root {
            --bg-main: #061323;
            --bg-soft: #0d1f35;
            --surface: rgba(255, 255, 255, 0.08);
            --surface-line: rgba(255, 255, 255, 0.18);
            --text-main: #ebf4ff;
            --text-soft: #9fb5d4;
            --blue: #2f8cff;
            --teal: #11c9b4;
            --danger-bg: rgba(255, 103, 132, 0.12);
            --danger-line: rgba(255, 103, 132, 0.4);
            --danger-text: #ffc2d0;
            --success-bg: rgba(64, 214, 164, 0.12);
            --success-line: rgba(64, 214, 164, 0.4);
            --success-text: #b8ffe7;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            min-height: 100%;
            font-family: "Segoe UI", "Inter", "Helvetica Neue", Arial, sans-serif;
            color: var(--text-main);
            background: radial-gradient(circle at 20% 20%, #123a66 0%, transparent 34%),
                        radial-gradient(circle at 80% 0%, #0b5d63 0%, transparent 28%),
                        radial-gradient(circle at 90% 80%, #194080 0%, transparent 34%),
                        var(--bg-main);
        }

        .page-shell {
            position: relative;
            min-height: 100vh;
            overflow: hidden;
        }

        .page-shell::before,
        .page-shell::after {
            content: "";
            position: absolute;
            border-radius: 999px;
            filter: blur(50px);
            opacity: 0.45;
            pointer-events: none;
        }

        .page-shell::before {
            width: 380px;
            height: 380px;
            top: -120px;
            right: -80px;
            background: #2a75f3;
        }

        .page-shell::after {
            width: 320px;
            height: 320px;
            bottom: -130px;
            left: -60px;
            background: #11c9b4;
        }

        .auth-layout {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
        }

        .hero-side {
            padding: 38px 54px;
            background: linear-gradient(145deg, rgba(16, 53, 93, 0.74), rgba(6, 31, 58, 0.7));
            border-right: 1px solid rgba(255, 255, 255, 0.12);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
            animation: floatIn 700ms ease-out;
        }

        .brand-mark {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: linear-gradient(150deg, rgba(47, 140, 255, 0.95), rgba(17, 201, 180, 0.9));
            box-shadow: 0 0 20px rgba(17, 201, 180, 0.28);
        }

        .brand-mark svg {
            width: 22px;
            height: 22px;
            fill: #f7fbff;
        }

        .brand-title {
            font-size: 16px;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.3px;
        }

        .brand-subtitle {
            margin: 2px 0 0;
            font-size: 12px;
            color: var(--text-soft);
        }

        .hero-content {
            max-width: 620px;
            animation: riseIn 820ms ease-out;
        }

        .hero-content h1 {
            margin: 0 0 16px;
            font-size: clamp(2rem, 4vw, 3rem);
            line-height: 1.15;
            letter-spacing: 0.3px;
            text-wrap: balance;
        }

        .hero-content p {
            margin: 0;
            color: var(--text-soft);
            line-height: 1.75;
            max-width: 560px;
            font-size: 1rem;
        }

        .feature-list {
            margin: 28px 0 0;
            padding: 0;
            list-style: none;
            display: grid;
            gap: 14px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(2px);
        }

        .feature-icon {
            width: 36px;
            height: 36px;
            border-radius: 11px;
            display: grid;
            place-items: center;
            background: linear-gradient(150deg, rgba(47, 140, 255, 0.22), rgba(17, 201, 180, 0.2));
            border: 1px solid rgba(47, 140, 255, 0.4);
            flex-shrink: 0;
        }

        .feature-icon svg {
            width: 18px;
            height: 18px;
            fill: #ccedff;
        }

        .feature-item span {
            font-size: 0.95rem;
            color: #dcecff;
        }

        .hero-footer {
            margin-top: 30px;
            color: var(--text-soft);
            font-size: 0.9rem;
            animation: fadeIn 1s ease-out;
        }

        .login-side {
            padding: 32px 24px;
            display: grid;
            place-items: center;
        }

        .login-card {
            width: min(100%, 430px);
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.11), rgba(255, 255, 255, 0.05));
            border: 1px solid var(--surface-line);
            border-radius: 24px;
            padding: 28px 24px;
            backdrop-filter: blur(16px);
            box-shadow: 0 20px 50px rgba(3, 10, 21, 0.45);
            animation: cardIn 700ms ease-out;
        }

        .card-title {
            margin: 0;
            font-size: 1.7rem;
            letter-spacing: 0.2px;
        }

        .card-subtitle {
            margin: 6px 0 20px;
            color: var(--text-soft);
            font-size: 0.95rem;
        }

        .alert {
            border-radius: 12px;
            padding: 10px 12px;
            margin-bottom: 12px;
            font-size: 0.9rem;
            border: 1px solid transparent;
        }

        .alert-success {
            background: var(--success-bg);
            border-color: var(--success-line);
            color: var(--success-text);
        }

        .alert-error {
            background: var(--danger-bg);
            border-color: var(--danger-line);
            color: var(--danger-text);
        }

        .alert ul {
            margin: 0;
            padding-left: 18px;
        }

        .form-group {
            margin-bottom: 14px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.88rem;
            color: #d5e3f8;
            letter-spacing: 0.1px;
        }

        .form-input {
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 14px;
            padding: 11px 13px;
            font-size: 0.95rem;
            color: var(--text-main);
            background: rgba(11, 31, 55, 0.65);
            transition: border-color 180ms ease, box-shadow 180ms ease, transform 180ms ease;
        }

        .form-input:focus {
            outline: none;
            border-color: rgba(17, 201, 180, 0.9);
            box-shadow: 0 0 0 3px rgba(17, 201, 180, 0.18);
            transform: translateY(-1px);
        }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 12px 0 18px;
            gap: 8px;
        }

        .remember-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--text-soft);
            font-size: 0.9rem;
            cursor: pointer;
        }

        .remember-label input {
            accent-color: var(--teal);
            width: 16px;
            height: 16px;
        }

        .login-button {
            width: 100%;
            border: 0;
            border-radius: 14px;
            padding: 12px 14px;
            color: #f7fdff;
            background: linear-gradient(120deg, #2f8cff, #11c9b4);
            font-size: 0.98rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(18, 116, 214, 0.38);
            transition: transform 180ms ease, box-shadow 180ms ease, filter 180ms ease;
        }

        .login-button:hover {
            transform: translateY(-2px);
            filter: brightness(1.05);
            box-shadow: 0 14px 35px rgba(19, 132, 226, 0.45);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .register-link {
            margin: 18px 0 0;
            text-align: center;
            color: var(--text-soft);
            font-size: 0.92rem;
        }

        .register-link a {
            color: #7edcff;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 1000px) {
            .auth-layout {
                grid-template-columns: 1fr;
            }

            .hero-side {
                padding: 26px 20px 18px;
                border-right: 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            }

            .hero-content h1 {
                font-size: clamp(1.65rem, 6.2vw, 2.4rem);
            }

            .feature-list {
                margin-top: 20px;
                gap: 10px;
            }

            .hero-footer {
                margin-top: 20px;
            }

            .login-side {
                padding: 20px 14px 28px;
            }

            .login-card {
                padding: 22px 18px;
                border-radius: 20px;
            }
        }

        @keyframes riseIn {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes cardIn {
            from {
                opacity: 0;
                transform: translateY(22px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes floatIn {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
<div class="page-shell">
    <main class="auth-layout">
        <section class="hero-side">
            <div>
                <header class="brand">
                    <div class="brand-mark" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M10.1 3.2a2.7 2.7 0 0 1 3.8 0l1.5 1.5a1 1 0 0 1-1.4 1.4l-1.5-1.5a.7.7 0 0 0-1 0L7.1 9a.7.7 0 0 0 0 1l6 6a.7.7 0 0 0 1 0l4.4-4.4a.7.7 0 0 0 0-1L17 9.1a1 1 0 0 1 1.4-1.4l1.5 1.5a2.7 2.7 0 0 1 0 3.8l-4.4 4.4a2.7 2.7 0 0 1-3.8 0l-6-6a2.7 2.7 0 0 1 0-3.8l4.4-4.4Z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="brand-title">Lost and Found Management System</p>
                        <p class="brand-subtitle">Community Recovery Platform</p>
                    </div>
                </header>

                <div class="hero-content">
                    <h1>Find what was lost. Return what was found.</h1>
                    <p>
                        A secure, community-focused platform that helps students and residents report lost belongings,
                        share found items, and reconnect people with what matters to them.
                    </p>

                    <ul class="feature-list">
                        <li class="feature-item">
                            <div class="feature-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24">
                                    <path d="M12 2a7 7 0 0 0-7 7c0 4.6 7 12.5 7 12.5S19 13.6 19 9a7 7 0 0 0-7-7Zm0 9.2a2.2 2.2 0 1 1 0-4.4 2.2 2.2 0 0 1 0 4.4Z"/>
                                </svg>
                            </div>
                            <span>Report lost items quickly</span>
                        </li>
                        <li class="feature-item">
                            <div class="feature-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24">
                                    <path d="M3 12a1 1 0 0 1 1-1h4.6l1.2-2.4a1 1 0 0 1 1.8 0L13 11h7a1 1 0 1 1 0 2h-7.6l-1.2 2.4a1 1 0 0 1-1.8 0L8.2 13H4a1 1 0 0 1-1-1Z"/>
                                </svg>
                            </div>
                            <span>Connect owners with finders</span>
                        </li>
                        <li class="feature-item">
                            <div class="feature-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24">
                                    <path d="M12 3a9 9 0 0 1 9 9 1 1 0 1 1-2 0 7 7 0 0 0-12.4-4.5H9a1 1 0 0 1 0 2H4.5a1 1 0 0 1-1-1V4a1 1 0 1 1 2 0v2A9 9 0 0 1 12 3Zm8.5 10.5a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1H15a1 1 0 1 1 0-2h3.1A7 7 0 0 1 6 16.5H3.5a1 1 0 1 1 0-2H8a1 1 0 0 1 1 1V20a1 1 0 1 1-2 0v-2A9 9 0 0 0 20 13.5Z"/>
                                </svg>
                            </div>
                            <span>Community powered recovery</span>
                        </li>
                    </ul>
                </div>
            </div>

            <p class="hero-footer">Helping communities reconnect lost belongings.</p>
        </section>

        <section class="login-side">
            <div class="login-card">
                <h2 class="card-title">Welcome back</h2>
                <p class="card-subtitle">Login to continue to your dashboard.</p>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login.attempt') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input class="form-input" type="email" id="email" name="email" value="{{ old('email') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input class="form-input" type="password" id="password" name="password" required>
                    </div>

                    <div class="remember-row">
                        <label class="remember-label" for="remember">
                            <input type="checkbox" id="remember" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                            Remember me
                        </label>
                    </div>

                    <button type="submit" class="login-button">Login</button>
                </form>

                <p class="register-link">
                    New here?
                    <a href="{{ route('register') }}">Create an account</a>
                </p>
            </div>
        </section>
    </main>
</div>
</body>
</html>