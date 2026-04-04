<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>fyp. lost &amp; found</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand: #4f64ff;
            --brand-deep: #3f4ee2;
            --text-main: #1f2a44;
            --text-subtle: #65708b;
            --page-bg: #f3f7ff;
            --line: #d9e5fb;
            --surface: #ffffff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-main);
            background:
                radial-gradient(circle at top right, #e8efff 0%, transparent 44%),
                linear-gradient(180deg, #ffffff 0%, var(--page-bg) 100%);
            padding: 26px 18px 42px;
        }

        .shell {
            width: min(1120px, 100%);
            margin: 0 auto;
            border-radius: 26px;
            border: 1px solid #d9e3f7;
            box-shadow: 0 22px 54px rgba(31, 42, 68, 0.12);
            background: linear-gradient(180deg, #fdfefe 0%, #f4f8ff 100%);
            overflow: hidden;
        }

        .topbar {
            padding: 16px 28px;
            border-bottom: 1px solid #e1e9fa;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            background: rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(6px);
        }

        .brand {
            text-decoration: none;
            color: var(--text-main);
            font-size: 23px;
            font-weight: 700;
            white-space: nowrap;
        }

        .brand strong {
            color: #223355;
            font-weight: 800;
        }

        .nav {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .nav a {
            text-decoration: none;
            font-size: 14px;
            color: #516283;
            font-weight: 600;
            position: relative;
            transition: color 0.2s ease;
        }

        .nav a:hover,
        .nav a.is-active {
            color: #263a63;
        }

        .nav a.is-active::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -8px;
            height: 2px;
            border-radius: 999px;
            background: var(--brand);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .avatar-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #53617d;
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: 8px 12px;
            background: #fff;
        }

        .avatar-dot {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: inline-grid;
            place-items: center;
            color: #fff;
            background: linear-gradient(145deg, var(--mint), var(--accent));
            font-size: 12px;
            font-weight: 700;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-radius: 999px;
            border: none;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            padding: 11px 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease, opacity 0.2s ease;
            cursor: pointer;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            color: #fff;
            background: linear-gradient(120deg, var(--brand) 0%, #6578ff 100%);
            box-shadow: 0 8px 20px rgba(79, 100, 255, 0.28);
        }

        .btn-primary:hover {
            box-shadow: 0 10px 22px rgba(79, 100, 255, 0.34);
        }

        .btn-found {
            color: #fff;
            background: linear-gradient(120deg, #4f64ff 0%, #6c84ff 100%);
            box-shadow: 0 8px 20px rgba(79, 100, 255, 0.24);
        }

        .btn-soft {
            color: #314567;
            border: 1px solid var(--line);
            background: #fff;
        }

        .btn-soft:hover {
            background: #f4f8ff;
        }

        .content {
            padding: 44px 28px 30px;
        }

        .hero {
            text-align: center;
            margin-bottom: 30px;
        }

        .hero h1 {
            font-size: clamp(29px, 4vw, 46px);
            font-weight: 800;
            letter-spacing: -0.7px;
            margin-bottom: 10px;
        }

        .hero p {
            font-family: 'Manrope', sans-serif;
            color: var(--text-subtle);
            font-size: 18px;
            margin-bottom: 22px;
        }

        .hero-buttons {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .panel {
            border: 1px solid var(--line);
            border-radius: 18px;
            background: var(--surface);
            box-shadow: 0 8px 22px rgba(58, 78, 109, 0.07);
            padding: 18px;
            margin-bottom: 18px;
        }

        .section-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .section-head h2 {
            font-size: 25px;
            letter-spacing: -0.3px;
        }

        .section-head a {
            text-decoration: none;
            font-size: 13px;
            color: #566aa0;
            font-weight: 700;
        }

        .section-head a:hover {
            color: #30467f;
            text-decoration: underline;
        }

        .quick-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .quick-card {
            border-radius: 14px;
            padding: 16px;
            border: 1px solid #d6e4ff;
            background: linear-gradient(170deg, #f7faff 0%, #eef4ff 100%);
            display: flex;
            flex-direction: column;
            min-height: 172px;
        }

        .quick-card h3 {
            font-size: 19px;
            margin-bottom: 6px;
        }

        .quick-card p {
            font-family: 'Manrope', sans-serif;
            color: #667592;
            line-height: 1.45;
            font-size: 14px;
            margin-bottom: 12px;
            flex: 1;
        }

        .quick-card .chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            padding: 9px 14px;
            width: fit-content;
        }

        .quick-card.lost .chip {
            background: linear-gradient(120deg, #4f64ff 0%, #6d82ff 100%);
        }

        .quick-card.found {
            background: linear-gradient(170deg, #f6f9ff 0%, #ebf2ff 100%);
            border-color: #d6e3ff;
        }

        .quick-card.found .chip {
            background: linear-gradient(120deg, #3b82f6 0%, #4f9cff 100%);
        }

        .quick-card.claim {
            background: linear-gradient(170deg, #f7faff 0%, #edf3ff 100%);
            border-color: #dbe7ff;
        }

        .quick-card.claim .chip {
            background: linear-gradient(120deg, #5d73ff 0%, #7b8fff 100%);
        }

        .search-row {
            display: grid;
            grid-template-columns: 1.4fr repeat(2, minmax(0, 0.8fr)) auto;
            gap: 10px;
        }

        .search-field,
        .search-select {
            border: 1px solid var(--line);
            border-radius: 999px;
            height: 44px;
            padding: 0 14px;
            background: #fff;
            color: #495a79;
            font-family: 'Manrope', sans-serif;
            font-size: 14px;
            width: 100%;
        }

        .search-button {
            border: 0;
            border-radius: 999px;
            color: #fff;
            padding: 0 20px;
            font-weight: 700;
            background: linear-gradient(120deg, var(--brand) 0%, #6a7cff 100%);
            box-shadow: 0 8px 20px rgba(79, 100, 255, 0.25);
        }

        .items-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }

        .item-card {
            border: 1px solid #dce7fb;
            border-radius: 14px;
            background: #fff;
            overflow: hidden;
            box-shadow: 0 5px 14px rgba(43, 63, 99, 0.06);
        }

        .item-preview {
            height: 116px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .item-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            color: #fff;
            background: #ea4343;
        }

        .item-badge.found {
            background: #3b82f6;
        }

        .item-body {
            padding: 12px;
        }

        .item-body h3 {
            font-size: 20px;
            margin-bottom: 6px;
        }

        .item-meta {
            font-family: 'Manrope', sans-serif;
            color: #6d7a94;
            font-size: 13px;
            line-height: 1.5;
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .step {
            border: 1px solid #dce7fc;
            border-radius: 14px;
            background: linear-gradient(180deg, #fbfdff 0%, #f4f8ff 100%);
            padding: 15px;
        }

        .step-num {
            width: 30px;
            height: 30px;
            border-radius: 999px;
            display: inline-grid;
            place-items: center;
            color: #fff;
            font-size: 14px;
            font-weight: 800;
            margin-bottom: 10px;
            background: linear-gradient(120deg, #7ea2ff 0%, #5c7dff 100%);
        }

        .step:nth-child(2) .step-num {
            background: linear-gradient(120deg, #8c91ff 0%, #6f75f2 100%);
        }

        .step:nth-child(3) .step-num {
            background: linear-gradient(120deg, #6ed0c2 0%, #43b6c7 100%);
        }

        .step h3 {
            font-size: 20px;
            margin-bottom: 5px;
        }

        .step p {
            font-family: 'Manrope', sans-serif;
            color: #6f7c95;
            font-size: 14px;
            line-height: 1.45;
        }

        .site-end {
            margin-top: 10px;
            font-size: 13px;
            color: #7080a0;
            text-align: center;
        }

        .site-footer-quick {
            margin-top: 20px;
            border-top: 1px solid #dbe6fb;
            border-radius: 14px;
            background: #f5f9ff;
            padding: 22px 18px 12px;
        }

        .site-footer-inner {
            display: grid;
            grid-template-columns: minmax(220px, 1.2fr) minmax(0, 1.8fr);
            gap: 20px;
        }

        .site-footer-brand h3 {
            margin: 0 0 8px;
            font-size: 24px;
            font-weight: 800;
            line-height: 1;
            color: var(--text-main);
            text-transform: uppercase;
        }

        .site-footer-brand p {
            margin: 0;
            font-family: 'Manrope', sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #60708f;
        }

        .site-footer-links-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(120px, 1fr));
            gap: 16px;
        }

        .site-footer-col h4 {
            margin: 0 0 8px;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-main);
        }

        .site-footer-col a {
            display: block;
            margin-bottom: 7px;
            color: #5a6d92;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }

        .site-footer-col a:hover {
            color: var(--brand);
            text-decoration: underline;
        }

        .site-footer-bottom {
            margin-top: 14px;
            padding-top: 10px;
            border-top: 1px solid #dbe6fb;
            color: #667aa1;
            font-size: 13px;
        }

        @media (max-width: 1040px) {
            .items-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .search-row {
                grid-template-columns: 1fr 1fr;
            }

            .search-button {
                height: 44px;
            }
        }

        @media (max-width: 860px) {
            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .site-footer-inner {
                grid-template-columns: 1fr;
            }

            .site-footer-links-grid {
                grid-template-columns: repeat(2, minmax(120px, 1fr));
            }

            .quick-grid,
            .steps-grid {
                grid-template-columns: 1fr;
            }

            .content {
                padding: 30px 18px 22px;
            }

            .section-head h2 {
                font-size: 22px;
            }

            .nav {
                gap: 12px;
            }
        }

        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .shell {
                border-radius: 14px;
            }

            .items-grid,
            .search-row {
                grid-template-columns: 1fr;
            }

            .site-footer-links-grid {
                grid-template-columns: 1fr;
            }

            .topbar {
                padding: 14px;
            }

            .hero p {
                font-size: 16px;
            }

            .topbar-right {
                width: 100%;
                justify-content: flex-start;
            }
        }
    </style>
</head>
<body>
    <main class="shell">
        <header class="topbar">
            <a class="brand" href="{{ route('home') }}"><strong>fyp.</strong> lost &amp; found</a>

            <nav class="nav" aria-label="Main navigation">
                <a class="is-active" href="{{ route('home') }}">Home</a>
                <a href="{{ route('reports.lost.create') }}">Report Lost</a>
                <a href="{{ route('reports.found.create') }}">Report Found</a>
                <a href="{{ route('items.index') }}">Browse</a>
                @auth
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                @endauth
            </nav>

            <div class="topbar-right">
                @auth
                    <span class="avatar-pill">
                        <span class="avatar-dot">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        {{ auth()->user()->name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-soft">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-soft">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Create account</a>
                @endauth
            </div>
        </header>

        <section class="content">
            <section class="hero">
                <h1>Lost something? Found something?</h1>
                <p>Report it fast and reconnect with the right owner.</p>
                <div class="hero-buttons">
                    <a href="{{ route('reports.lost.create') }}" class="btn btn-primary">Report Lost</a>
                    <a href="{{ route('reports.found.create') }}" class="btn btn-found">Report Found</a>
                    <a href="{{ route('items.index') }}" class="btn btn-soft">Browse Items</a>
                </div>
            </section>

            <section class="panel">
                <div class="section-head">
                    <h2>Quick Actions</h2>
                </div>

                <div class="quick-grid">
                    <article class="quick-card lost">
                        <h3>Report Lost</h3>
                        <p>Submit details of your missing item and where you last used it.</p>
                        <a class="chip" href="{{ route('reports.lost.create') }}">Create Lost Report</a>
                    </article>

                    <article class="quick-card found">
                        <h3>Report Found</h3>
                        <p>Share what you discovered so the rightful owner can claim it.</p>
                        <a class="chip" href="{{ route('reports.found.create') }}">Create Found Report</a>
                    </article>

                    <article class="quick-card claim">
                        <h3>Claim Item</h3>
                        <p>Use matching details and proof of ownership to reclaim your item.</p>
                        <a class="chip" href="{{ route('items.index') }}">Start Claim</a>
                    </article>
                </div>
            </section>

            <section class="panel">
                <form class="search-row" action="{{ route('items.index') }}" method="GET">
                    <input class="search-field" type="text" name="q" placeholder="Search items...">
                    <select class="search-select" name="category">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                    <select class="search-select" name="type">
                        <option value="">Lost &amp; Found</option>
                        <option value="lost">Lost</option>
                        <option value="found">Found</option>
                    </select>
                    <button class="search-button" type="submit">Search</button>
                </form>
            </section>

            <section class="panel">
                <div class="section-head">
                    <h2>Recent Items</h2>
                    <a href="{{ route('items.index') }}">Browse all items →</a>
                </div>

                <div class="items-grid">
                    @forelse ($recentReports as $report)
                        <article class="item-card">
                            <a href="{{ route('items.show', $report) }}" style="display: block; text-decoration: none; color: inherit;">
                                @if ($report->image)
                                    <div class="item-preview" style="background-image: url('{{ asset('storage/' . $report->image) }}');">
                                @else
                                    <div class="item-preview" style="background-image: linear-gradient(120deg, #dbe8ff 0%, #c9d7f5 100%);">
                                @endif
                                        <span class="item-badge {{ $report->type === 'found' ? 'found' : '' }}">{{ strtoupper($report->type) }}</span>
                                    </div>

                                <div class="item-body">
                                    <h3>{{ $report->title }}</h3>
                                    <p class="item-meta">{{ $report->location }} • {{ $report->created_at->diffForHumans() }}</p>
                                </div>
                            </a>
                        </article>
                    @empty
                        <article class="item-card" style="grid-column: 1 / -1;">
                            <div class="item-body" style="padding: 24px;">
                                <h3 style="font-size: 18px;">No recent reports yet</h3>
                                <p class="item-meta">Start by reporting a lost or found item.</p>
                            </div>
                        </article>
                    @endforelse
                </div>
            </section>

            <section class="panel">
                <div class="section-head">
                    <h2>How It Works</h2>
                </div>

                <div class="steps-grid">
                    <article class="step">
                        <span class="step-num">1</span>
                        <h3>Report Item</h3>
                        <p>Share your lost or found details with time, place, and identifying clues.</p>
                    </article>

                    <article class="step">
                        <span class="step-num">2</span>
                        <h3>System Matches</h3>
                        <p>Our matching flow links similar reports and highlights possible owners.</p>
                    </article>

                    <article class="step">
                        <span class="step-num">3</span>
                        <h3>Claim &amp; Verify</h3>
                        <p>Provide ownership proof and complete a safe handover process.</p>
                    </article>
                </div>
            </section>

            <p class="site-end">&copy; {{ now()->year }} fyp. lost &amp; found.</p>

            @include('partials.site-footer')
        </section>
    </main>
</body>
</html>
