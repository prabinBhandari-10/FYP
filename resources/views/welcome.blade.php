<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lost & Found | Auburn</title>
    
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
            --green: #22c55e;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
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
            padding: 24px 60px;
            background-color: white;
            border-bottom: 1px solid var(--border-color);
            position: relative;
        }

        .logo-container {
            display: flex;
            flex-direction: column;
            gap: 2px;
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

        .logo-arch {
            position: relative;
        }

        .logo-subtitle {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-gray);
            display: flex;
            align-items: flex-end;
            gap: 4px;
            padding-left: 28px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            border-radius: 9999px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-outline {
            background-color: white;
            color: var(--text-dark);
            border: 1px solid var(--border-color);
        }

        .btn-outline:hover {
            border-color: #cbd5e1;
            background-color: transparent;
        }

        .header-btn {
            padding: 10px 20px;
            font-size: 14px;
        }

        .hero {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 80px 24px;
            max-width: 800px;
            margin: 0 auto;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 9999px;
            font-size: 14px;
            color: var(--text-gray);
            margin-bottom: 40px;
            font-weight: 500;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background-color: var(--green);
            border-radius: 50%;
        }

        .hero-title {
            font-size: 64px;
            font-weight: 800;
            line-height: 1.1;
            color: var(--text-dark);
            margin-bottom: 24px;
            letter-spacing: -1.5px;
        }

        .title-highlight {
            color: var(--secondary);
        }

        .hero-subtitle {
            font-family: 'Inter', sans-serif;
            font-size: 20px;
            color: var(--text-gray);
            line-height: 1.5;
            margin-bottom: 48px;
            max-width: 630px;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {
            .hero-title { font-size: 48px; }
            .header { padding: 16px 24px; }
        }
        @media (max-width: 480px) {
            .hero-title { font-size: 40px; }
            .hero-actions { flex-direction: column; width: 100%; }
            .btn { width: 100%; justify-content: center; }
            .header-btn { display: none; }
        }
    </style>
</head>
<body>

    <header class="header">
        <div class="logo-container">
            <div class="logo">
                L
                <svg class="logo-arch" width="14" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 22A8 8 0 0 1 20 22" />
                </svg>
                ST &amp; FOUND
            </div>
            <div class="logo-subtitle">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
                Auburn
            </div>
        </div>

        <a href="{{ route('reports.lost.create') }}" class="btn btn-primary header-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Report a lost item
        </a>
    </header>

    <main class="hero">
        <div class="status-badge">
            <div class="status-dot"></div>
            Auburn's largest lost and found network
        </div>
        
        <h1 class="hero-title">
            Lost or found something<br>
            <span class="title-highlight">in Auburn?</span>
        </h1>
        
        <p class="hero-subtitle">
            Report something lost or found and let our community help reunite it with their owners
        </p>
        
        <div class="hero-actions">
            <a href="{{ route('reports.lost.create') }}" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Report a lost item
            </a>
            
            <a href="{{ route('items.index') }}" class="btn btn-outline">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                Browse items
            </a>
        </div>
    </main>

</body>
</html>
