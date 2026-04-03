<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>fyp. lost & found</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #635bff;
            --primary-hover: #534be0;
            --text-dark: #1e293b;
            --text-gray: #64748b;
            --bg-color: #f8fafc;
            --border-color: #e2e8f0;
            --found-color: #10b981;
            --found-bg: #d1fae5;
            --lost-color: #ef4444;
            --lost-bg: #fee2e2;
            --highlight: #3b82f6;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(180deg, #ffffff 0%, #f0f6ff 100%);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px 60px;
        }

        .logo {
            font-size: 24px;
            color: var(--text-dark);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .logo-bold {
            font-weight: 800;
        }

        .logo-regular {
            font-weight: 500;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 9999px; /* Pill shape */
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-outline {
            background-color: white;
            color: var(--text-dark);
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .btn-outline:hover {
            border-color: #cbd5e1;
            background-color: #f8fafc;
        }

        /* Main Hero */
        .hero-wrap {
            flex: 1;
            padding: 60px 20px 100px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
        }

        .hero-title {
            font-size: 56px;
            font-weight: 800;
            line-height: 1.15;
            color: var(--text-dark);
            margin-bottom: 24px;
            letter-spacing: -1px;
        }

        .hero-title-highlight {
            color: #4da6ff;
        }

        .hero-subtitle {
            font-family: 'Inter', sans-serif;
            font-size: 18px;
            color: var(--text-gray);
            line-height: 1.5;
            margin-bottom: 40px;
            max-width: 600px;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .hero-actions .btn {
            font-size: 16px;
            padding: 14px 32px;
        }

        .helper-text {
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            color: var(--text-gray);
            margin-bottom: 48px;
        }

        /* Recent Reports Card */
        .reports-container {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 20px;
            padding: 30px;
            width: 100%;
            max-width: 900px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            text-align: left;
            z-index: 10;
        }

        .reports-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .reports-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .browse-link {
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .browse-link:hover {
            text-decoration: underline;
        }

        .reports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .report-card {
            background-color: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            gap: 16px;
            align-items: flex-start;
            transition: all 0.2s;
        }
        
        .report-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            background-color: white;
        }

        .report-img {
            width: 72px;
            height: 96px;
            background-color: transparent;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            flex-shrink: 0;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.02);
            object-fit: contain;
        }

        .report-details {
            flex: 1;
        }

        .report-title-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 4px;
        }

        .report-title {
            font-weight: 600;
            font-size: 16px;
            color: var(--text-dark);
        }

        .badge {
            font-family: 'Inter', sans-serif;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 9999px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .badge-found {
            background-color: var(--found-bg);
            color: var(--found-color);
        }

        .badge-lost {
            background-color: var(--lost-bg);
            color: var(--lost-color);
        }

        .report-category {
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            color: var(--text-gray);
            margin-bottom: 12px;
        }

        .report-meta {
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            color: var(--text-gray);
        }

        /* Decorative Background */
        .bg-illustration {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 300px;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23f0f6ff" fill-opacity="1" d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,213.3C672,224,768,224,864,197.3C960,171,1056,117,1152,106.7C1248,96,1344,128,1392,144L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') bottom center / cover no-repeat;
            opacity: 0.5;
            z-index: 1;
            pointer-events: none;
        }

        @media (max-width: 768px) {
            .header { padding: 20px; }
            .hero-title { font-size: 40px; }
            .reports-grid { grid-template-columns: 1fr; }
            .header .btn { display: none; }
            .hero-actions { flex-direction: column; width: 100%; max-width: 300px; }
        }

        .site-footer-quick {
            margin-top: 44px;
            width: min(980px, 100%);
            border-top: 1px solid #d9e3f0;
            background: rgba(241, 245, 249, 0.92);
            border-radius: 18px;
            padding: 26px 20px 14px;
            z-index: 10;
        }

        .site-footer-inner {
            display: grid;
            grid-template-columns: minmax(240px, 1.2fr) minmax(0, 1.8fr);
            gap: 22px;
        }

        .site-footer-brand h3 {
            margin: 0 0 10px;
            font-size: 44px;
            font-weight: 800;
            letter-spacing: -0.8px;
            text-transform: uppercase;
            color: #0f172a;
            line-height: 1;
        }

        .site-footer-brand p {
            margin: 0;
            color: var(--text-gray);
            font-family: 'Inter', sans-serif;
            font-size: 17px;
            line-height: 1.55;
        }

        .site-footer-links-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(130px, 1fr));
            gap: 18px;
        }

        .site-footer-col h4 {
            margin: 0 0 10px;
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }

        .site-footer-col a {
            display: block;
            margin-bottom: 9px;
            color: #475569;
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            text-decoration: none;
        }

        .site-footer-col a:hover {
            color: var(--primary);
            text-decoration: underline;
        }

        .site-footer-bottom {
            margin-top: 18px;
            padding-top: 12px;
            border-top: 1px solid #d4deeb;
            font-family: 'Inter', sans-serif;
            color: #52637d;
            font-size: 15px;
        }

        @media (max-width: 860px) {
            .site-footer-inner {
                grid-template-columns: 1fr;
            }

            .site-footer-links-grid {
                grid-template-columns: repeat(2, minmax(130px, 1fr));
            }
        }

        @media (max-width: 520px) {
            .site-footer-links-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <header class="header">
        <a href="{{ route('dashboard') }}" class="logo">
            <span class="logo-bold">fyp.</span>
            <span class="logo-regular">lost & found</span>
        </a>

        <a href="{{ route('reports.lost.create') }}" class="btn btn-primary">
            + Report a lost item
        </a>
    </header>

    <div class="hero-wrap">
        <div class="bg-illustration"></div>
        
        <h1 class="hero-title">
            Lost something? <span class="hero-title-highlight">Found it?</span><br>
            Let's make it right.
        </h1>

        <p class="hero-subtitle">
            Report, reconnect, and reunite lost belongings with their<br>rightful owners.
        </p>

        <div class="hero-actions">
            <a href="{{ route('reports.lost.create') }}" class="btn btn-primary">
                + Report a lost item
            </a>

            <a href="{{ route('items.index') }}" class="btn btn-outline">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity: 0.7;">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                Browse items
            </a>
        </div>

        <p class="helper-text">
            Check recent reports or report something lost or found belclow.
        </p>

        <div class="reports-container">
            <div class="reports-header">
                <h2 class="reports-title">Recent reports</h2>
                <a href="{{ route('items.index') }}" class="browse-link">
                    Browse all items →
                </a>
            </div>

            <div class="reports-grid">
                <!-- Report Card 1 -->
                <div class="report-card">
                    <div class="report-img bg-blue-50">📱</div>
                    <div class="report-details">
                        <div class="report-title-row">
                            <h3 class="report-title">Smartphone</h3>
                            <span class="badge badge-found">FOUND</span>
                        </div>
                        <div class="report-category">Electronics</div>
                        <div class="report-meta">1 hour ago - Dudley Commons</div>
                    </div>
                </div>

                <!-- Report Card 2 -->
                <div class="report-card">
                    <div class="report-img bg-gray-50">🎒</div>
                    <div class="report-details">
                        <div class="report-title-row">
                            <h3 class="report-title">Black Backpack</h3>
                            <span class="badge badge-lost">LOST</span>
                        </div>
                        <div class="report-category">Bags</div>
                        <div class="report-meta">2 hours ago - Auburn Library</div>
                    </div>
                </div>
            </div>
        </div>

        @include('partials.site-footer')
    </div>

</body>
</html>
