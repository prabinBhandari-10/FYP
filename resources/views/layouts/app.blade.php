<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Lost and Found Management System')</title>
    <style>
        :root {
            color-scheme: dark;
            --bg: #0d1117;
            --bg-soft: #111827;
            --panel: #151f2f;
            --line: #2a3547;
            --text: #e6edf3;
            --muted: #9aa8bc;
            --accent: #4ade80;
            --danger: #fb7185;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top right, #1d2a42 0%, transparent 40%),
                radial-gradient(circle at bottom left, #113b2c 0%, transparent 45%),
                var(--bg);
        }

        .page {
            width: min(100%, 1100px);
            margin: 0 auto;
            padding: 24px 18px 40px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 24px;
        }

        .brand {
            margin: 0;
            font-size: 18px;
            letter-spacing: 0.4px;
        }

        .brand span {
            color: var(--accent);
        }

        .nav-links {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--line);
            color: var(--text);
            background: transparent;
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn:hover {
            border-color: #3a4a62;
            background: rgba(255, 255, 255, 0.03);
        }

        .btn-primary {
            border-color: #2563eb;
            background: #1d4ed8;
            color: #fff;
        }

        .btn-primary:hover {
            background: #1e40af;
        }

        .card {
            width: min(100%, 520px);
            margin: 0 auto;
            background: linear-gradient(160deg, rgba(255, 255, 255, 0.04), rgba(255, 255, 255, 0.02));
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 24px;
            backdrop-filter: blur(6px);
        }

        .card h1,
        .card h2 {
            margin-top: 0;
            margin-bottom: 8px;
        }

        .subtitle {
            margin-top: 0;
            margin-bottom: 20px;
            color: var(--muted);
        }

        .field {
            margin-bottom: 14px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: var(--muted);
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 10px 12px;
            background: var(--bg-soft);
            color: var(--text);
            font-size: 15px;
        }

        input[type="date"],
        input[type="file"],
        .form-textarea {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 10px 12px;
            background: var(--bg-soft);
            color: var(--text);
            font-size: 15px;
        }

        .form-textarea {
            resize: vertical;
            min-height: 120px;
        }

        input:focus {
            outline: none;
            border-color: #5b7fb5;
        }

        .row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--muted);
            font-size: 14px;
        }

        .checkbox input {
            width: auto;
            margin: 0;
        }

        .helper-text {
            color: var(--muted);
            font-size: 14px;
            margin-top: 16px;
        }

        .helper-text a {
            color: #93c5fd;
            text-decoration: none;
        }

        .helper-text a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 10px;
            padding: 10px 12px;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .alert-success {
            background: rgba(74, 222, 128, 0.12);
            border: 1px solid rgba(74, 222, 128, 0.35);
            color: #a7f3d0;
        }

        .alert-error {
            background: rgba(251, 113, 133, 0.12);
            border: 1px solid rgba(251, 113, 133, 0.35);
            color: #fecdd3;
        }

        .alert ul {
            margin: 0;
            padding-left: 18px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
            margin-top: 18px;
        }

        .dashboard-shell {
            background:
                linear-gradient(155deg, rgba(255, 255, 255, 0.06), rgba(255, 255, 255, 0.02)),
                linear-gradient(330deg, rgba(74, 222, 128, 0.08), transparent 35%);
        }

        .dashboard-heading {
            margin-bottom: 10px;
        }

        .dashboard-grid--wide {
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            margin-top: 12px;
            margin-bottom: 18px;
        }

        .dashboard-section-title {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--muted);
            margin-top: 6px;
            margin-bottom: 10px;
        }

        .stat {
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 14px;
            background: rgba(17, 24, 39, 0.65);
        }

        .stat .value {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 12px;
        }

        .action-card {
            display: block;
            text-decoration: none;
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 14px;
            color: var(--text);
            background: rgba(17, 24, 39, 0.65);
            transition: transform 0.2s ease, border-color 0.2s ease, background 0.2s ease;
        }

        .action-card:hover {
            transform: translateY(-2px);
            border-color: #4b607d;
            background: rgba(23, 33, 50, 0.85);
        }

        .action-card h3 {
            margin: 0 0 6px;
            font-size: 16px;
        }

        .action-card p {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.45;
        }

        .action-card--lost {
            border-left: 4px solid #fb7185;
        }

        .action-card--found {
            border-left: 4px solid #4ade80;
        }

        .action-card--browse {
            border-left: 4px solid #38bdf8;
        }

        .action-card--claims {
            border-left: 4px solid #facc15;
        }

        .stat .label {
            color: var(--muted);
            font-size: 13px;
        }

        @media (max-width: 640px) {
            .page {
                padding: 16px 12px 28px;
            }

            .card {
                padding: 18px;
            }

            .brand {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
<div class="page">
    <header class="topbar">
        <h1 class="brand">Lost &amp; Found <span>Management System</span></h1>

        <div class="nav-links">
            @auth
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="btn">Dashboard</a>

                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Sign up</a>
            @endauth
        </div>
    </header>

    @yield('content')
</div>
</body>
</html>