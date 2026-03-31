@extends('layouts.app')

@section('title', 'User Dashboard | Lost and Found')

@section('content')
    <style>
        :root {
            --bg-dark: #020617;
            --bg-card: rgba(10, 18, 38, 0.55);
            --barca-blue: #2563eb;
            --barca-maroon: #7f1d1d;
            --barca-gold: #fbbf24;
            --text-main: #e5eefc;
            --text-soft: #a9b6cc;
        }

        .dash-page {
            min-height: calc(100vh - 120px);
            padding: 28px 14px 64px;
            background:
                radial-gradient(1200px 520px at 85% -15%, rgba(37, 99, 235, 0.18), transparent 60%),
                radial-gradient(900px 420px at -10% 25%, rgba(127, 29, 29, 0.2), transparent 60%),
                linear-gradient(160deg, #020617 0%, #071226 100%);
            color: var(--text-main);
            animation: dashFade 0.6s ease-out both;
        }

        @keyframes dashFade {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dash-container {
            width: min(1200px, 100%);
            margin: 0 auto;
            display: grid;
            gap: 20px;
        }

        .dash-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 18px;
            border-radius: 18px;
            background: rgba(10, 18, 38, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(14px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.3);
        }

        .dash-logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .dash-logo-badge {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.6), rgba(127, 29, 29, 0.6));
            color: var(--barca-gold);
            font-size: 16px;
        }

        .dash-nav-links {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .dash-nav-link {
            color: var(--text-soft);
            text-decoration: none;
            font-size: 13px;
            padding: 8px 12px;
            border-radius: 12px;
            border: 1px solid transparent;
            transition: all 0.2s ease;
        }

        .dash-nav-link:hover {
            color: var(--text-main);
            border-color: rgba(37, 99, 235, 0.35);
            background: rgba(37, 99, 235, 0.1);
        }

        .dash-grid {
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 20px;
        }

        .glass-card {
            background: var(--bg-card);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.3);
            padding: 20px;
        }

        .dash-hero {
            display: grid;
            gap: 8px;
            border: 1px solid rgba(37, 99, 235, 0.25);
            position: relative;
            overflow: hidden;
        }

        .dash-hero::after {
            content: "";
            position: absolute;
            inset: -2px;
            background:
                radial-gradient(420px 120px at 10% -20%, rgba(37, 99, 235, 0.2), transparent 60%),
                radial-gradient(420px 120px at 90% 0%, rgba(127, 29, 29, 0.2), transparent 60%);
            pointer-events: none;
        }

        .dash-hero h1 {
            margin: 0;
            font-size: clamp(26px, 2.6vw, 36px);
            font-weight: 700;
        }

        .dash-hero p {
            margin: 0;
            color: var(--text-soft);
            font-size: 14px;
        }

        .dash-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: var(--barca-gold);
            background: rgba(251, 191, 36, 0.12);
            border: 1px solid rgba(251, 191, 36, 0.3);
            padding: 6px 10px;
            border-radius: 999px;
            width: fit-content;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .stat-card {
            background: rgba(10, 18, 38, 0.7);
            border: 1px solid rgba(37, 99, 235, 0.2);
            border-radius: 18px;
            padding: 16px;
            display: grid;
            gap: 8px;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            border-color: rgba(127, 29, 29, 0.4);
            box-shadow: 0 18px 36px rgba(2, 6, 23, 0.6);
        }

        .stat-icon {
            width: 34px;
            height: 34px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: rgba(37, 99, 235, 0.2);
            color: var(--barca-gold);
            font-size: 16px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
        }

        .stat-label {
            color: var(--text-soft);
            font-size: 12px;
        }

        .quick-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .action-card {
            display: grid;
            gap: 8px;
            padding: 16px;
            border-radius: 18px;
            border: 1px solid rgba(37, 99, 235, 0.2);
            background: rgba(10, 18, 38, 0.7);
            text-decoration: none;
            color: var(--text-main);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .action-card:hover {
            transform: scale(1.02);
            border-color: rgba(37, 99, 235, 0.4);
            box-shadow: 0 18px 36px rgba(2, 6, 23, 0.6);
        }

        .action-title {
            font-size: 16px;
            margin: 0;
        }

        .action-sub {
            color: var(--text-soft);
            font-size: 13px;
            margin: 0;
        }

        .action-btn {
            margin-top: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--barca-blue), var(--barca-maroon));
            color: var(--text-main);
            font-size: 12px;
            font-weight: 600;
            box-shadow: 0 12px 30px rgba(37, 99, 235, 0.3);
        }

        .activity-list {
            display: grid;
            gap: 12px;
        }

        .activity-item {
            display: flex;
            gap: 12px;
            align-items: center;
            padding: 12px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            background: rgba(10, 18, 38, 0.6);
            transition: background 0.2s ease, border-color 0.2s ease;
        }

        .activity-item:hover {
            background: rgba(37, 99, 235, 0.08);
            border-color: rgba(37, 99, 235, 0.2);
        }

        .activity-icon {
            width: 34px;
            height: 34px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: rgba(127, 29, 29, 0.2);
            color: var(--barca-gold);
        }

        .activity-title {
            font-size: 14px;
            margin: 0;
        }

        .activity-time {
            font-size: 12px;
            color: var(--text-soft);
        }

        .notice-card {
            display: grid;
            gap: 6px;
            padding: 12px;
            border-radius: 16px;
            background: rgba(10, 18, 38, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .notice-title {
            font-size: 13px;
            margin: 0;
        }

        .notice-meta {
            font-size: 12px;
            color: var(--text-soft);
        }

        .notice-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 999px;
            background: rgba(251, 191, 36, 0.12);
            border: 1px solid rgba(251, 191, 36, 0.3);
            color: var(--barca-gold);
            width: fit-content;
        }

        @media (max-width: 980px) {
            .dash-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .quick-grid {
                grid-template-columns: 1fr;
            }

            .dash-nav {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }
        }

        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .dash-nav-links {
                width: 100%;
            }

            .dash-nav-link {
                flex: 1 1 auto;
                text-align: center;
            }
        }
    </style>

    <section class="dash-page">
        <div class="dash-container">
            <nav class="dash-nav">
                <div class="dash-logo">
                    <span class="dash-logo-badge">⚽</span>
                    <span>Lost &amp; Found</span>
                </div>
                <div class="dash-nav-links">
                    <a class="dash-nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                    <a class="dash-nav-link" href="{{ route('items.index') }}">Notifications</a>
                    <a class="dash-nav-link" href="{{ route('claims.index') }}">Profile</a>
                    <a class="dash-nav-link" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                </div>
            </nav>

            <div class="dash-grid">
                <div>
                    <div class="glass-card dash-hero">
                        <span class="dash-badge">Premium User</span>
                        <h1>Welcome back, {{ Auth::user()->name }}</h1>
                        <p>Here is a clean snapshot of your lost and found activity.</p>
                    </div>

                    @if (session('success'))
                        <div class="glass-card" style="margin-top: 16px;">
                            <div class="notice-title">{{ session('success') }}</div>
                        </div>
                    @endif

                    <div class="stats-grid" style="margin-top: 16px;">
                        <div class="stat-card">
                            <div class="stat-icon">📦</div>
                            <div class="stat-value">{{ $stats['totalReports'] ?? 0 }}</div>
                            <div class="stat-label">Total Reports</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">🔵</div>
                            <div class="stat-value">{{ $stats['lostReports'] ?? 0 }}</div>
                            <div class="stat-label">Lost Reports</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">🔴</div>
                            <div class="stat-value">{{ $stats['foundReports'] ?? 0 }}</div>
                            <div class="stat-label">Found Reports</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon">✅</div>
                            <div class="stat-value">{{ $stats['activeClaims'] ?? 0 }}</div>
                            <div class="stat-label">Active Claims</div>
                        </div>
                    </div>

                    <div class="glass-card" style="margin-top: 16px;">
                        <h2 style="margin: 0 0 12px; font-size: 18px;">Quick Actions</h2>
                        <div class="quick-grid">
                            <a href="{{ route('reports.lost.create') }}" class="action-card">
                                <h3 class="action-title">Report Lost Item</h3>
                                <p class="action-sub">Tell the community what you lost and where.</p>
                                <span class="action-btn">Start Report</span>
                            </a>
                            <a href="{{ route('reports.found.create') }}" class="action-card">
                                <h3 class="action-title">Report Found Item</h3>
                                <p class="action-sub">Help return an item to its rightful owner.</p>
                                <span class="action-btn">Start Report</span>
                            </a>
                            <a href="{{ route('items.index') }}" class="action-card">
                                <h3 class="action-title">Browse Items</h3>
                                <p class="action-sub">Check all currently listed lost and found items.</p>
                                <span class="action-btn">Explore Items</span>
                            </a>
                            <a href="{{ route('claims.index') }}" class="action-card">
                                <h3 class="action-title">My Claims</h3>
                                <p class="action-sub">Review claim progress and updates quickly.</p>
                                <span class="action-btn">View Claims</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="glass-card">
                        <h2 style="margin: 0 0 12px; font-size: 18px;">Recent Activity</h2>
                        <div class="activity-list">
                            <div class="activity-item">
                                <div class="activity-icon">📝</div>
                                <div>
                                    <p class="activity-title">Lost report submitted</p>
                                    <span class="activity-time">Today · 10:24 AM</span>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon">📍</div>
                                <div>
                                    <p class="activity-title">Location updated on a report</p>
                                    <span class="activity-time">Yesterday · 6:12 PM</span>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon">🛡️</div>
                                <div>
                                    <p class="activity-title">Claim request submitted</p>
                                    <span class="activity-time">Mar 28 · 3:40 PM</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="glass-card" style="margin-top: 16px;">
                        <h2 style="margin: 0 0 12px; font-size: 18px;">Notifications</h2>
                        <div class="notice-card">
                            <span class="notice-badge">Info</span>
                            <p class="notice-title">Your claim is under review.</p>
                            <span class="notice-meta">Expected response within 24 hours.</span>
                        </div>
                        <div class="notice-card" style="margin-top: 10px;">
                            <span class="notice-badge">Success</span>
                            <p class="notice-title">Report verified by admin.</p>
                            <span class="notice-meta">Your item is visible to all users.</span>
                        </div>
                        <div class="notice-card" style="margin-top: 10px;">
                            <span class="notice-badge">Warning</span>
                            <p class="notice-title">Add proof to improve claim approval.</p>
                            <span class="notice-meta">Upload receipts or photos if available.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection