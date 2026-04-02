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
            --primary: #4744d8;
            --primary-hover: #3f3bc7;
            --secondary: #20a3e8;
            --text-dark: #0e1737;
            --text-gray: #435676;
            --text-light: #94a3b8;
            --bg-color: #f1f5f9;
            --border-color: #dbe3ee;
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
            padding: 26px 52px;
            background-color: #ffffff;
            border-bottom: 1px solid var(--border-color);
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
            line-height: 1;
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
            line-height: 1;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 30px;
            border-radius: 9999px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 15px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            line-height: 1;
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

        .hero-wrap {
            flex: 1;
            padding: 40px 20px 80px;
        }

        .hero {
            width: min(1320px, 100%);
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding-top: 120px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 20px;
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
            font-size: clamp(58px, 6.3vw, 122px);
            font-weight: 800;
            line-height: 1.05;
            color: var(--text-dark);
            margin-bottom: 28px;
            letter-spacing: -2.6px;
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
            max-width: 680px;
        }

        .hero-actions {
            display: flex;
            gap: 18px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .hero-main-btn {
            padding: 14px 30px;
            font-size: 15px;
            min-height: auto;
        }

        .hero-outline-btn {
            background: #ffffff;
            border: 1px solid #d6deea;
            color: var(--text-dark);
            padding: 14px 30px;
            font-size: 15px;
            min-height: auto;
        }

        @media (max-width: 1200px) {
            .logo { font-size: 24px; }
            .logo-subtitle { font-size: 12px; }
            .btn { font-size: 15px; }
            .header-btn { font-size: 14px; }
            .status-badge { font-size: 14px; }
            .hero-main-btn,
            .hero-outline-btn { font-size: 15px; }
        }

        @media (max-width: 768px) {
            .hero-title { font-size: 48px; letter-spacing: -1px; }
            .hero-subtitle { font-size: 19px; }
            .header { padding: 16px 20px; }
            .logo { font-size: 23px; }
            .logo-subtitle { font-size: 14px; padding-left: 20px; }
            .status-badge { font-size: 14px; margin-bottom: 36px; }
            .status-dot { width: 8px; height: 8px; }
            .hero { padding-top: 70px; }
            .hero-main-btn,
            .hero-outline-btn {
                font-size: 16px;
                min-height: 50px;
                padding: 14px 24px;
            }
        }
        @media (max-width: 480px) {
            .hero-title { font-size: 38px; }
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

    <div class="hero-wrap">
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
                Report something lost or found and let our community help reunite
                it with their owners
            </p>

            <div class="hero-actions">
                <a href="{{ route('reports.lost.create') }}" class="btn btn-primary hero-main-btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Report a lost item
                </a>

                <a href="{{ route('items.index') }}" class="btn hero-outline-btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    Browse items
                </a>
            </div>
        </main>
    </div>

</body>
</html>
