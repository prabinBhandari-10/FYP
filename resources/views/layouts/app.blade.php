<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Lost & Found')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #4338ca;
            --primary-hover: #3730a3;
            --secondary: #0ea5e9;
            --text-dark: #0f172a;
            --text-gray: #475569;
            --text-light: #94a3b8;
            --bg-color: #f8fafc;
            --border-color: #e2e8f0;
            --input-bg: #ffffff;
            --green: #22c55e;
            --danger: #ef4444;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 48px;
            background-color: white;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .logo-container {
            display: flex;
            flex-direction: column;
            gap: 2px;
            text-decoration: none;
            outline: none;
        }

        .logo {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 2px;
            color: var(--text-dark);
            text-transform: uppercase;
        }

        .logo-arch {
            position: relative;
        }

        .logo-subtitle {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-gray);
            display: flex;
            align-items: flex-end;
            gap: 4px;
            padding-left: 24px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 9999px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-outline {
            background-color: white;
            color: var(--text-dark);
            border-color: var(--border-color);
        }

        .btn-outline:hover {
            border-color: #cbd5e1;
            background-color: #f8fafc;
        }

        .btn-ghost {
            background-color: transparent;
            color: var(--text-gray);
        }
        
        .btn-ghost:hover {
            color: var(--text-dark);
            background-color: #f1f5f9;
        }

        main.content {
            flex: 1;
            padding: 40px 24px;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
        }

        @media (max-width: 768px) {
            .header {
                padding: 16px 20px;
            }
            .nav-links {
                gap: 8px;
            }
        }
        
        /* Utility classes for content specific pages */
        .page-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 32px;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 32px;
            letter-spacing: -0.5px;
        }

        .card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 10px 14px;
            background-color: var(--input-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 14.5px;
            color: var(--text-dark);
            transition: all 0.2s;
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 56, 202, 0.1);
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .alert-success { background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error { background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* General Badge */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-lost { background-color: #fef2f2; color: #ef4444; }
        .badge-found { background-color: #f0fdf4; color: #22c55e; }
        .badge-neutral { background-color: #f1f5f9; color: #64748b; }
    </style>
</head>
<body>

    <header class="header">
        <a href="{{ route('home') }}" class="logo-container">
            <div class="logo">
                L
                <svg class="logo-arch" width="12" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 22A8 8 0 0 1 20 22" />
                </svg>
                ST &amp; FOUND
            </div>
        </a>

        <div class="nav-links">
            <a href="{{ route('items.index') }}" class="btn btn-ghost">Browse</a>
            
            @auth
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="btn btn-outline">Dashboard</a>

                <form action="{{ route('logout') }}" method="POST" style="margin: 0; display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-ghost">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-ghost">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Sign up</a>
            @endauth
        </div>
    </header>

    <main class="content">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any() && !request()->routeIs('login') && !request()->routeIs('register'))
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>