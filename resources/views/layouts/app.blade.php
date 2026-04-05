<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Lost & Found')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-page: #f4f8ff;
            --bg-elevated: #ffffff;
            --bg-soft: #f6f9ff;
            --text-main: #10213f;
            --text-muted: #4f6285;
            --text-soft: #7184a8;
            --line: #d9e4f6;
            --primary: #3f51d9;
            --primary-strong: #2f3fba;
            --primary-tint: #eef1ff;
            --accent: #10a6c6;
            --danger: #d0355f;
            --success: #0f9b6f;
            --radius-lg: 20px;
            --radius-md: 16px;
            --radius-sm: 12px;
            --shadow-soft: 0 10px 30px rgba(16, 33, 63, 0.07);
            --shadow-hover: 0 16px 34px rgba(16, 33, 63, 0.12);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background:
                radial-gradient(900px 340px at -10% -10%, rgba(63, 81, 217, 0.11), transparent 60%),
                radial-gradient(760px 300px at 110% 8%, rgba(16, 166, 198, 0.08), transparent 62%),
                var(--bg-page);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1, h2, h3, h4 {
            font-family: 'Sora', sans-serif;
            letter-spacing: -0.02em;
            color: var(--text-main);
        }

        .site-header {
            position: sticky;
            top: 0;
            z-index: 60;
            border-bottom: 1px solid rgba(217, 228, 246, 0.9);
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 8px 22px rgba(16, 33, 63, 0.06);
        }

        .site-nav {
            width: min(1160px, 100% - 32px);
            margin: 0 auto;
            min-height: 74px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--text-main);
            font-family: 'Sora', sans-serif;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .brand-logo {
            height: 40px;
            width: auto;
            display: block;
            border-radius: 10px;
            transition: transform 0.2s ease, filter 0.2s ease, box-shadow 0.2s ease;
        }

        .brand:hover .brand-logo {
            transform: scale(1.04);
            filter: brightness(1.05);
            box-shadow: 0 8px 22px rgba(63, 81, 217, 0.28), 0 0 0 2px rgba(84, 231, 204, 0.26);
            animation: logoPulse 1.1s ease-in-out infinite alternate;
        }

        .brand:focus-visible .brand-logo,
        .brand:focus-within .brand-logo {
            transform: scale(1.04);
            filter: brightness(1.05);
            box-shadow: 0 8px 22px rgba(63, 81, 217, 0.28), 0 0 0 2px rgba(84, 231, 204, 0.26);
        }

        @keyframes logoPulse {
            from {
                box-shadow: 0 8px 20px rgba(63, 81, 217, 0.2), 0 0 0 1px rgba(84, 231, 204, 0.22);
            }
            to {
                box-shadow: 0 10px 26px rgba(63, 81, 217, 0.34), 0 0 0 3px rgba(84, 231, 204, 0.32);
            }
        }

        .brand-name {
            line-height: 1;
        }

        .brand span {
            color: var(--primary);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .nav-link {
            padding: 9px 14px;
            border-radius: 999px;
            text-decoration: none;
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            color: var(--primary-strong);
            background: var(--primary-tint);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid transparent;
            border-radius: 999px;
            padding: 11px 18px;
            font-size: 14px;
            font-weight: 800;
            line-height: 1;
            text-decoration: none;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
            font-family: 'Sora', sans-serif;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            color: #fff;
            background: linear-gradient(135deg, var(--primary), #5568e6);
            box-shadow: 0 10px 20px rgba(63, 81, 217, 0.25);
        }

        .btn-primary:hover {
            box-shadow: 0 14px 24px rgba(63, 81, 217, 0.3);
        }

        .btn-outline {
            color: var(--primary-strong);
            border-color: #c6d4ef;
            background: #fff;
        }

        .btn-outline:hover {
            border-color: #9db1db;
            background: #f8fbff;
        }

        .btn-ghost {
            color: var(--text-muted);
            border-color: transparent;
            background: transparent;
        }

        .btn-ghost:hover {
            color: var(--primary-strong);
            background: var(--primary-tint);
        }

        .main-content {
            width: min(1160px, 100% - 32px);
            margin: 26px auto 0;
            flex: 1;
        }

        .page-title {
            font-size: clamp(28px, 3.5vw, 40px);
            margin-bottom: 12px;
        }

        .page-subtitle {
            color: var(--text-muted);
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .card {
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            background: var(--bg-elevated);
            box-shadow: var(--shadow-soft);
            padding: 24px;
        }

        .card-soft {
            background: var(--bg-soft);
        }

        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .section-head h2 {
            font-size: 24px;
        }

        .section-note {
            color: var(--text-soft);
            font-size: 14px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .grid-4 {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-main);
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            border: 1px solid #cdd9ef;
            border-radius: 14px;
            background: #fff;
            color: var(--text-main);
            font: inherit;
            font-size: 14px;
            padding: 12px 14px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-input::placeholder,
        .form-textarea::placeholder {
            color: #8ea0bf;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(63, 81, 217, 0.14);
            background: #fcfdff;
        }

        .form-row {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .alert {
            margin-bottom: 16px;
            padding: 12px 14px;
            border-radius: 14px;
            font-size: 14px;
            line-height: 1.5;
            border: 1px solid transparent;
        }

        .alert-success {
            background: #effcf6;
            color: #0f7a57;
            border-color: #bfeeda;
        }

        .alert-error {
            background: #fff4f6;
            color: #a61f49;
            border-color: #f4bfcd;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 5px 10px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .badge-lost {
            color: #b8324f;
            background: #ffeef2;
            border: 1px solid #f9ccda;
        }

        .badge-found {
            color: #0e7f90;
            background: #ebfbff;
            border: 1px solid #c5eef7;
        }

        .badge-neutral {
            color: #486083;
            background: #f1f6ff;
            border: 1px solid #d2def2;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
        }

        .stat-card {
            border-radius: var(--radius-md);
            border: 1px solid var(--line);
            padding: 18px;
            background: #fff;
        }

        .stat-value {
            font-family: 'Sora', sans-serif;
            font-size: 28px;
            font-weight: 800;
            color: var(--text-main);
        }

        .stat-label {
            margin-top: 6px;
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 600;
        }

        .table-wrap {
            overflow-x: auto;
        }

        nav[aria-label="Pagination Navigation"] {
            margin-top: 6px;
        }

        nav[aria-label="Pagination Navigation"] .w-5,
        nav[aria-label="Pagination Navigation"] .h-5 {
            width: 20px;
            height: 20px;
            display: block;
            flex: 0 0 20px;
        }

        nav[aria-label="Pagination Navigation"] .sm\:hidden {
            display: flex;
            gap: 8px;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        nav[aria-label="Pagination Navigation"] .sm\:flex-1 {
            display: none;
        }

        @media (min-width: 640px) {
            nav[aria-label="Pagination Navigation"] .sm\:hidden {
                display: none;
            }

            nav[aria-label="Pagination Navigation"] .sm\:flex-1 {
                display: flex;
                flex: 1 1 0%;
                align-items: center;
                justify-content: space-between;
                gap: 8px;
                flex-wrap: wrap;
            }
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 11px 10px;
            border-bottom: 1px solid var(--line);
            text-align: left;
            vertical-align: top;
            font-size: 14px;
        }

        th {
            font-weight: 800;
            color: var(--text-main);
        }

        td {
            color: var(--text-muted);
        }

        .auth-wrap {
            min-height: calc(100vh - 250px);
            display: grid;
            place-items: center;
            padding: 18px 0 30px;
        }

        .auth-card {
            width: min(520px, 100%);
            border: 1px solid #d4e1f6;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.93);
            box-shadow: 0 18px 40px rgba(16, 33, 63, 0.1);
            padding: 30px;
        }

        .split-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.4fr) minmax(0, 0.9fr);
            gap: 24px;
            align-items: start;
        }

        .sticky-panel {
            position: sticky;
            top: 95px;
            align-self: start;
            max-height: calc(100vh - 110px);
            overflow-y: auto;
            overscroll-behavior: contain;
        }

        .empty-state {
            text-align: center;
            padding: 46px 24px;
            color: var(--text-soft);
            border: 1px dashed #bfd0ee;
            border-radius: var(--radius-lg);
            background: #fafcff;
        }

        .site-footer-quick {
            margin-top: 40px;
            border-top: 1px solid var(--line);
            background: #eef4ff;
            padding: 26px 18px 14px;
        }

        .site-footer-inner {
            width: min(1160px, 100%);
            margin: 0 auto;
            display: grid;
            grid-template-columns: minmax(220px, 1.2fr) minmax(0, 1.8fr);
            gap: 24px;
        }

        .site-footer-brand h3 {
            margin: 0 0 10px;
            font-size: 26px;
        }

        .site-footer-brand p {
            margin: 0;
            color: var(--text-muted);
            line-height: 1.6;
            font-size: 14px;
        }

        .site-footer-links-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(120px, 1fr));
            gap: 16px;
        }

        .site-footer-col h4 {
            margin: 0 0 8px;
            font-size: 14px;
            color: var(--text-main);
        }

        .site-footer-col a {
            display: block;
            margin-bottom: 7px;
            text-decoration: none;
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 600;
        }

        .site-footer-col a:hover {
            color: var(--primary);
        }

        .site-footer-bottom {
            width: min(1160px, 100%);
            margin: 16px auto 0;
            padding-top: 10px;
            border-top: 1px solid #d6e2f5;
            color: var(--text-muted);
            font-size: 13px;
        }

        .social-links {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-top: 12px;
        }

        .social-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-tint);
            color: var(--primary);
            text-decoration: none;
            font-size: 20px;
            transition: all 0.2s ease;
        }

        .social-link:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        .welcome-toast {
            position: fixed;
            top: 88px;
            right: 18px;
            z-index: 1200;
            max-width: min(420px, calc(100vw - 36px));
            background: #ffffff;
            border: 1px solid #d6e2f5;
            box-shadow: var(--shadow-soft);
            border-radius: 14px;
            padding: 12px 14px;
            transform: translateY(-10px);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.24s ease, transform 0.24s ease;
        }

        .welcome-toast.is-visible {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }

        .welcome-toast h4 {
            margin: 0 0 4px;
            font-size: 16px;
        }

        .welcome-toast p {
            margin: 0;
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .welcome-toast-close {
            position: absolute;
            top: 8px;
            right: 10px;
            background: transparent;
            border: 0;
            color: var(--text-soft);
            font-size: 18px;
            line-height: 1;
            cursor: pointer;
        }

        .welcome-toast-close:hover {
            color: var(--text-main);
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 280px minmax(0, 1.4fr) minmax(0, 320px);
            gap: 20px;
            margin-bottom: 20px;
        }

        @media (max-width: 1024px) {
            .grid-4 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .split-layout {
                grid-template-columns: 1fr;
            }

            .sticky-panel {
                position: static;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
        }

        @media (max-width: 760px) {
            .site-nav {
                width: calc(100% - 20px);
                padding: 10px 0;
                align-items: flex-start;
                flex-direction: column;
            }

            .main-content {
                width: calc(100% - 20px);
            }

            .form-row,
            .grid-2,
            .grid-3,
            .grid-4,
            .site-footer-inner,
            .site-footer-links-grid {
                grid-template-columns: 1fr;
            }

            .auth-card {
                padding: 24px 18px;
            }

            .nav-links,
            .nav-actions {
                width: 100%;
            }

            .brand {
                gap: 8px;
            }

            .brand-logo {
                height: 34px;
            }

            .brand-name {
                font-size: 20px;
            }
        }

        @media (max-width: 420px) {
            .brand-name {
                display: none;
            }
        }
    </style>
</head>
<body>
    @php
        $isAdmin = auth()->check() && auth()->user()->role === 'admin';
    @endphp

    <header class="site-header">
        <nav class="site-nav" aria-label="Main">
            <a href="{{ route('home') }}" class="brand" aria-label="Lost and Found Home">
                <img src="{{ asset('images/logo.png') }}" alt="Lost and Found logo" class="brand-logo">
                <span class="brand-name">Lost <span>&amp;</span> Found</span>
            </a>

            <div class="nav-links">
                <a href="{{ route('home') }}" class="nav-link">Home</a>
                <a href="{{ route('items.index') }}" class="nav-link">Browse</a>
                <a href="{{ route('reports.track.form') }}" class="nav-link">Track Report</a>
                <a href="{{ route('about') }}" class="nav-link">About</a>
                <a href="{{ route('contact') }}" class="nav-link">Contact</a>
                @auth
                    @if (! $isAdmin)
                        <a href="{{ route('reports.lost.create') }}" class="nav-link">Report Lost</a>
                        <a href="{{ route('reports.found.create') }}" class="nav-link">Report Found</a>
                    @endif
                @endauth
            </div>

            <div class="nav-actions">
                @auth
                    <a href="{{ route('notifications.index') }}" class="btn btn-ghost" style="position: relative;" title="Notifications">
                        🔔
                        @php
                            $unreadCount = 0;
                            try {
                                $unreadCount = auth()->user()->notifications()->where('is_read', false)->count();
                            } catch (\Exception $e) {
                                // Notifications table may not exist yet
                            }
                        @endphp
                        @if ($unreadCount > 0)
                            <span style="position: absolute; top: -8px; right: -8px; background: var(--danger); color: white; border-radius: 999px; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 800;">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        @endif
                    </a>
                    @if (! $isAdmin)
                        <a href="{{ route('profile') }}" class="btn btn-outline">Profile</a>
                    @else
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">Dashboard</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn btn-ghost">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Create Account</a>
                @endauth
            </div>
        </nav>
    </header>

    <main class="main-content">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any() && ! request()->routeIs('login') && ! request()->routeIs('register'))
            <div class="alert alert-error">
                <ul style="padding-left: 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <div id="firstVisitWelcome" class="welcome-toast" role="status" aria-live="polite" aria-atomic="true" style="display: none;">
        <button type="button" class="welcome-toast-close" aria-label="Close welcome message" onclick="closeWelcomeToast()">&times;</button>
        <h4>Welcome to FYP Lost and Found System</h4>
        <p>Report, browse, and reconnect lost items quickly and safely.</p>
    </div>

    @include('partials.site-footer')

    <script>
        function closeWelcomeToast() {
            const toast = document.getElementById('firstVisitWelcome');
            if (!toast) {
                return;
            }

            toast.classList.remove('is-visible');
            window.setTimeout(() => {
                toast.style.display = 'none';
            }, 240);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const isHome = @json(request()->routeIs('home'));
            if (!isHome) {
                return;
            }

            const key = 'lf_welcome_seen';
            if (sessionStorage.getItem(key)) {
                return;
            }

            const toast = document.getElementById('firstVisitWelcome');
            if (!toast) {
                return;
            }

            toast.style.display = 'block';
            window.requestAnimationFrame(() => {
                toast.classList.add('is-visible');
            });

            sessionStorage.setItem(key, '1');
            window.setTimeout(closeWelcomeToast, 3600);
        });
    </script>
</body>
</html>
