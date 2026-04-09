@extends('layouts.app')

@section('title', 'Home | Lost & Found')

@section('content')
@php
    $activeUser = \Illuminate\Support\Facades\Auth::guard('web')->user()
        ?? \Illuminate\Support\Facades\Auth::guard('admin')->user();
    $isAuthenticated = (bool) $activeUser;
    $isAdmin = $activeUser?->role === 'admin';
@endphp

<div style="display: grid; grid-template-columns: 280px minmax(0, 1.4fr) minmax(0, 320px); gap: 20px; margin-bottom: 20px;">
    <!-- LEFT SIDEBAR: Navigation Menu -->
    <aside style="display: flex; flex-direction: column; gap: 12px;">
        <nav class="card" style="padding: 0; overflow: hidden; border-radius: 14px;">
            <div style="padding: 16px; border-bottom: 1px solid var(--line);">
                <h3 style="font-size: 16px; margin: 0; color: var(--text-main); font-weight: 800;">Navigation</h3>
            </div>
            <a href="{{ route('home') }}" style="display: flex; align-items: center; gap: 12px; padding: 14px 16px; text-decoration: none; color: var(--text-main); font-weight: 600; border-bottom: 1px solid var(--line); transition: background 0.2s ease; background: var(--primary-tint);" onmouseover="this.style.background='rgba(63,81,217,0.15)'" onmouseout="this.style.background='var(--primary-tint)'">
                <span style="font-size: 18px;"><wa-icon name="house"></wa-icon></span>
                <span>Home</span>
            </a>
            <a href="{{ route('items.index') }}" style="display: flex; align-items: center; gap: 12px; padding: 14px 16px; text-decoration: none; color: var(--text-main); font-weight: 600; border-bottom: 1px solid var(--line); transition: background 0.2s ease;" onmouseover="this.style.background='var(--bg-soft)'" onmouseout="this.style.background='transparent'">
                <span style="font-size: 18px;"><wa-icon name="magnifying-glass" variant="light"></wa-icon></span>
                <span>Browse Items</span>
            </a>
            @if ($isAuthenticated)
                @if (!$isAdmin)
                    <a href="{{ route('reports.lost.create') }}" style="display: flex; align-items: center; gap: 12px; padding: 14px 16px; text-decoration: none; color: var(--text-main); font-weight: 600; border-bottom: 1px solid var(--line); transition: background 0.2s ease;" onmouseover="this.style.background='var(--bg-soft)'" onmouseout="this.style.background='transparent'">
                        <span style="font-size: 18px;"><wa-icon name="calendar-lines-pen" variant="thin"></wa-icon></span>
                        <span>Report Lost</span>
                    </a>
                    <a href="{{ route('reports.found.create') }}" style="display: flex; align-items: center; gap: 12px; padding: 14px 16px; text-decoration: none; color: var(--text-main); font-weight: 600; border-bottom: 1px solid var(--line); transition: background 0.2s ease;" onmouseover="this.style.background='var(--bg-soft)'" onmouseout="this.style.background='transparent'">
                        <span style="font-size: 18px;"><wa-icon name="calendar-lines-pen" variant="thin"></wa-icon></span>
                        <span>Report Found</span>
                    </a>
                    <a href="{{ route('profile') }}" style="display: flex; align-items: center; gap: 12px; padding: 14px 16px; text-decoration: none; color: var(--text-main); font-weight: 600; border-bottom: 1px solid var(--line); transition: background 0.2s ease;" onmouseover="this.style.background='var(--bg-soft)'" onmouseout="this.style.background='transparent'">
                        <span style="font-size: 18px;"><wa-icon name="user" variant="regular"></wa-icon></span>
                        <span>My Profile</span>
                    </a>
                    <a href="{{ route('claims.index') }}" style="display: flex; align-items: center; gap: 12px; padding: 14px 16px; text-decoration: none; color: var(--text-main); font-weight: 600; transition: background 0.2s ease;" onmouseover="this.style.background='var(--bg-soft)'" onmouseout="this.style.background='transparent'">
                        <span style="font-size: 18px;"><wa-icon name="hand" family="sharp" variant="thin" style="color: rgb(2, 3, 4);"></wa-icon></span>
                        <span>My Claims</span>
                    </a>
                @else
                    <a href="{{ route('admin.dashboard') }}" style="display: flex; align-items: center; gap: 12px; padding: 14px 16px; text-decoration: none; color: var(--text-main); font-weight: 600; border-bottom: 1px solid var(--line); transition: background 0.2s ease;" onmouseover="this.style.background='var(--bg-soft)'" onmouseout="this.style.background='transparent'">
                        <span style="font-size: 18px;">⚙️</span>
                        <span>Admin Dashboard</span>
                    </a>
                    <a href="{{ route('reports.lost.create') }}" style="display: flex; align-items: center; gap: 12px; padding: 14px 16px; text-decoration: none; color: var(--text-main); font-weight: 600; border-bottom: 1px solid var(--line); transition: background 0.2s ease;" onmouseover="this.style.background='var(--bg-soft)'" onmouseout="this.style.background='transparent'">
                        <span style="font-size: 18px;"><wa-icon name="calendar-lines-pen" variant="thin"></wa-icon></span>
                        <span>Report Lost</span>
                    </a>
                    <a href="{{ route('reports.found.create') }}" style="display: flex; align-items: center; gap: 12px; padding: 14px 16px; text-decoration: none; color: var(--text-main); font-weight: 600; border-bottom: 1px solid var(--line); transition: background 0.2s ease;" onmouseover="this.style.background='var(--bg-soft)'" onmouseout="this.style.background='transparent'">
                        <span style="font-size: 18px;"><wa-icon name="calendar-lines-pen" variant="thin"></wa-icon></span>
                        <span>Report Found</span>
                    </a>
                    <a href="{{ route('admin.reports.index') }}" style="display: flex; align-items: center; gap: 12px; padding: 14px 16px; text-decoration: none; color: var(--text-main); font-weight: 600; border-bottom: 1px solid var(--line); transition: background 0.2s ease;" onmouseover="this.style.background='var(--bg-soft)'" onmouseout="this.style.background='transparent'">
                        <span style="font-size: 18px;">📋</span>
                        <span>Manage Reports</span>
                    </a>
                    <a href="{{ route('admin.claims.index') }}" style="display: flex; align-items: center; gap: 12px; padding: 14px 16px; text-decoration: none; color: var(--text-main); font-weight: 600; border-bottom: 1px solid var(--line); transition: background 0.2s ease;" onmouseover="this.style.background='var(--bg-soft)'" onmouseout="this.style.background='transparent'">
                        <span style="font-size: 18px;"><wa-icon name="hand" family="sharp" variant="solid" style="color: rgb(2, 3, 4);"></wa-icon></span>
                        <span>Review Claims</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" style="display: flex; align-items: center; gap: 12px; padding: 14px 16px; text-decoration: none; color: var(--text-main); font-weight: 600; transition: background 0.2s ease;" onmouseover="this.style.background='var(--bg-soft)'" onmouseout="this.style.background='transparent'">
                        <span style="font-size: 18px;">👥</span>
                        <span>Users</span>
                    </a>
                @endif
            @endif
        </nav>

        @if (!$isAdmin)
        <article class="card card-soft">
            <h3 style="font-size: 16px; margin: 0 0 10px; color: var(--text-main);"> Quick Tip</h3>
            <p style="margin: 0; font-size: 13px; line-height: 1.6; color: var(--text-muted);">
                @if ($isAuthenticated)
                    Be specific with details in your reports to help others identify items quickly.
                @else
                    <a href="{{ route('register') }}" style="color: var(--primary); font-weight: 600; text-decoration: none;">Create an account</a> to report or claim items.
                @endif
            </p>
        </article>
        @endif
    </aside>

    <!-- CENTER FEED: Main Content -->
    <main style="display: flex; flex-direction: column; gap: 16px;">
        @if ($isAuthenticated)
        <section class="card" style="background: linear-gradient(135deg, rgba(63,81,217,0.08) 0%, rgba(16,166,198,0.06) 100%); border: 1px solid var(--line);">
            <p style="margin: 0; color: var(--text-main); font-weight: 700; font-size: 15px;">
                 Welcome back, <strong>{{ $activeUser->name }}</strong>!
                @if ($isAdmin)
                    <span style="display: block; margin-top: 4px; font-size: 13px; color: var(--text-muted); font-weight: 600;">You're signed in as Administrator</span>
                @else
                    <span style="display: block; margin-top: 4px; font-size: 13px; color: var(--text-muted);">Ready to report, browse, or claim? Start below.</span>
                @endif
            </p>
        </section>
        @endif

        <section class="card">
            <div style="margin-bottom: 18px;">
                <h1 style="font-size: 36px; margin: 0 0 6px; color: var(--text-main); font-family: 'Sora', sans-serif; font-weight: 800;">
                    @if ($isAdmin)
                        Admin Workspace
                    @else
                        Lost & Found
                    @endif
                </h1>
                <p style="margin: 0; font-size: 15px; color: var(--text-muted); line-height: 1.6;">
                    @if ($isAdmin)
                        Review reports, manage claims, and maintain platform integrity.
                    @else
                        Report missing items, search for found items, and reunite belongings with their owners.
                    @endif
                </p>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                @if ($isAdmin)
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary" style="justify-content: center;">📊 Dashboard</a>
                    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline" style="justify-content: center;">📋 All Reports</a>
                    <a href="{{ route('reports.lost.create') }}" class="btn btn-primary" style="justify-content: center;"><wa-icon name="calendar-lines-pen" variant="thin"></wa-icon>&nbsp;Report Lost</a>
                    <a href="{{ route('reports.found.create') }}" class="btn btn-outline" style="justify-content: center;"><wa-icon name="calendar-lines-pen" variant="thin"></wa-icon>&nbsp;Report Found</a>
                @else
                    @if ($isAuthenticated)
                        <a href="{{ route('reports.lost.create') }}" class="btn btn-primary" style="justify-content: center;"><wa-icon name="calendar-lines-pen" variant="thin"></wa-icon>&nbsp;Report Lost</a>
                        <a href="{{ route('reports.found.create') }}" class="btn btn-outline" style="justify-content: center;"><wa-icon name="calendar-lines-pen" variant="thin"></wa-icon>&nbsp;Report Found</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary" style="justify-content: center;"> Sign In</a>
                        <a href="{{ route('register') }}" class="btn btn-outline" style="justify-content: center;"> Create Account</a>
                    @endif
                @endif
            </div>
        </section>

        <section class="card">
            <h2 style="font-size: 20px; margin: 0 0 14px; color: var(--text-main); font-family: 'Sora', sans-serif; font-weight: 800;">
                 Browse by Category
            </h2>

            @if ($categories->count())
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 10px;">
                    @foreach ($categories as $category)
                        @if ($category !== 'man')
                            <a href="{{ route('items.index', ['category' => $category]) }}" class="btn btn-outline" style="justify-content: center; font-size: 13px;">{{ $category }}</a>
                        @endif
                    @endforeach
                </div>
            @else
                <p style="text-align: center; color: var(--text-muted); margin: 0;">No categories yet</p>
            @endif
        </section>

        <section class="card">
            <h2 style="font-size: 20px; margin: 0 0 14px; color: var(--text-main); font-family: 'Sora', sans-serif; font-weight: 800;">
                Recently Added Items
            </h2>

            @if ($recentReports->count())
                <div style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px;">
                    @foreach ($recentReports as $report)
                        <a href="{{ route('items.show', $report) }}" style="text-decoration: none; display: block;">
                            <article class="card card-hover" style="padding: 0; overflow: hidden; height: 100%; display: flex; flex-direction: column;">
                                @if ($report->image)
                                    <div style="height: 140px; overflow: hidden; background: var(--bg-soft);">
                                        <img src="{{ asset('storage/' . $report->image) }}" alt="{{ $report->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                @else
                                    <div style="height: 140px; background: linear-gradient(135deg, var(--primary-tint) 0%, var(--bg-soft) 100%); display: flex; align-items: center; justify-content: center; font-size: 40px;">📦</div>
                                @endif
                                <div style="padding: 12px; flex: 1; display: flex; flex-direction: column;">
                                    <h3 style="font-size: 14px; margin: 0 0 4px; color: var(--text-main); font-weight: 700; line-height: 1.3;">{{ $report->title }}</h3>
                                    <p style="margin: 0 0 6px; font-size: 12px; color: var(--text-muted);">
                                        <span class="badge" style="text-transform: capitalize;">{{ $report->type }}</span>
                                        <span style="margin-left: 6px;">{{ $report->category }}</span>
                                    </p>
                                    <p style="margin: 0; font-size: 11px; color: var(--text-soft);">{{ \Illuminate\Support\Str::limit($report->location, 35) }}</p>
                                </div>
                            </article>
                        </a>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 30px 20px; background: var(--bg-soft); border-radius: 12px;">
                    <p style="margin: 0; color: var(--text-muted); font-size: 14px;">No items reported yet. Be the first to help the community!</p>
                </div>
            @endif
        </section>
    </main>

    <!-- RIGHT SIDEBAR: Statistics -->
    <aside class="sticky-panel" style="display: grid; gap: 14px;">
        @if ($isAuthenticated)
            @if (!$isAdmin)
                <article class="card card-soft">
                    <h3 style="font-size: 16px; margin-bottom: 10px; color: var(--text-main); display: flex; align-items: center; gap: 8px;"><i class="fa-solid fa-chart-line"></i><span>Your Activity</span></h3>
                    @php
                        $userReportsCount = $activeUser->reports()->count();
                        $userClaimsCount = $activeUser->claims()->count();
                        $userApprovedCount = $activeUser->claims()->where('status', 'approved')->count();
                    @endphp
                    <div style="display: grid; gap: 8px; font-size: 13px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--text-muted);">My Reports</span>
                            <strong style="color: var(--primary); font-size: 18px;">{{ $userReportsCount }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--text-muted);">My Claims</span>
                            <strong style="color: var(--accent); font-size: 18px;">{{ $userClaimsCount }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--text-muted);">Approved Claims</span>
                            <strong style="color: var(--success); font-size: 18px;">{{ $userApprovedCount }}</strong>
                        </div>
                    </div>
                </article>

                <article class="card card-soft">
                    <h3 style="font-size: 16px; margin-bottom: 10px; color: var(--text-main);"> Quick Actions</h3>
                    <div style="display: grid; gap: 8px;">
                        <a href="{{ route('profile') }}" class="btn btn-primary" style="font-size: 13px; justify-content: center;">My Profile</a>
                        <a href="{{ route('claims.index') }}" class="btn btn-outline" style="font-size: 13px; justify-content: center;"><wa-icon name="hand" family="sharp" variant="thin" style="color: rgb(2, 3, 4);"></wa-icon>&nbsp;My Claims</a>
                        <a href="{{ route('items.index') }}" class="btn btn-ghost" style="font-size: 13px; justify-content: center;">Browse All</a>
                    </div>
                </article>
            @else
                <article class="card card-soft">
                    <h3 style="font-size: 16px; margin-bottom: 10px; color: var(--text-main);">Platform Stats</h3>
                    @php
                        $totalReports = \App\Models\Report::count();
                        $totalClaims = \App\Models\Claim::count();
                        $totalUsers = \App\Models\User::count();
                    @endphp
                    <div style="display: grid; gap: 8px; font-size: 13px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--text-muted);">Total Reports</span>
                            <strong style="color: var(--primary); font-size: 18px;">{{ $totalReports }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--text-muted);">Total Claims</span>
                            <strong style="color: var(--accent); font-size: 18px;">{{ $totalClaims }}</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: var(--text-muted);">Active Users</span>
                            <strong style="color: var(--success); font-size: 18px;">{{ $totalUsers }}</strong>
                        </div>
                    </div>
                </article>

                <article class="card card-soft">
                    <h3 style="font-size: 16px; margin-bottom: 10px; color: var(--text-main);">⚙️ Admin Tools</h3>
                    <div style="display: grid; gap: 8px;">
                        <a href="{{ route('admin.reports.index') }}" class="btn btn-primary" style="font-size: 13px; justify-content: center;">Reports</a>
                        <a href="{{ route('admin.claims.index') }}" class="btn btn-outline" style="font-size: 13px; justify-content: center;">Claims</a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost" style="font-size: 13px; justify-content: center;">Users</a>
                    </div>
                </article>
            @endif
        @else
            <article class="card card-soft">
                <h3 style="font-size: 16px; margin-bottom: 10px; color: var(--text-main);">Get Started</h3>
                <p style="margin: 0 0 12px; font-size: 13px; line-height: 1.6; color: var(--text-muted);">
                    Create an account to report lost items, claim found items, and participate in our community.
                </p>
                <div style="display: grid; gap: 8px;">
                    <a href="{{ route('register') }}" class="btn btn-primary" style="font-size: 13px; justify-content: center;">Sign Up</a>
                    <a href="{{ route('login') }}" class="btn btn-outline" style="font-size: 13px; justify-content: center;">Sign In</a>
                </div>
            </article>

            <article class="card card-soft">
                <h3 style="font-size: 16px; margin-bottom: 10px; color: var(--text-main);">ℹ️ About lost and found</h3>
                <p style="margin: 0; font-size: 13px; line-height: 1.6; color: var(--text-muted);">
                    Lost & Found helps you find lost items and report found items in your area securely.
                </p>
                <a href="{{ route('about') }}" style="display: block; margin-top: 10px; color: var(--primary); font-weight: 600; font-size: 13px; text-decoration: none;">Learn more →</a>
            </article>
        @endif
    </aside>
</div>

<script>
    (function () {
        if (window.customElements && window.customElements.get('wa-icon')) {
            return;
        }

        class WaIconFallback extends HTMLElement {
            connectedCallback() {
                const iconName = (this.getAttribute('name') || '').toLowerCase();
                const iconSvg = {
                    'magnifying-glass': '<svg viewBox="0 0 24 24" width="1em" height="1em" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"></circle><path d="M20 20L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path></svg>',
                    'house': '<svg viewBox="0 0 24 24" width="1em" height="1em" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M3 10.5L12 3L21 10.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path><path d="M6.75 9.75V20H17.25V9.75" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>',
                    'user': '<svg viewBox="0 0 24 24" width="1em" height="1em" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><circle cx="12" cy="8" r="4" stroke="currentColor" stroke-width="2"></circle><path d="M5 20C5 16.134 8.134 13 12 13C15.866 13 19 16.134 19 20" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path></svg>',
                    'calendar-lines-pen': '<svg viewBox="0 0 24 24" width="1em" height="1em" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><rect x="4" y="5" width="16" height="15" rx="2" stroke="currentColor" stroke-width="2"></rect><path d="M8 3V7" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path><path d="M16 3V7" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path><path d="M7 11H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path><path d="M7 14H13" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path><path d="M15 13L17.5 15.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path><path d="M14 18L17.5 15.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path></svg>'
                };

                if (!iconSvg[iconName]) {
                    return;
                }

                this.setAttribute('aria-hidden', 'true');
                this.style.display = 'inline-flex';
                this.style.width = '1em';
                this.style.height = '1em';
                this.style.lineHeight = '1';
                this.style.alignItems = 'center';
                this.style.justifyContent = 'center';
                this.innerHTML = iconSvg[iconName];
            }
        }

        window.customElements.define('wa-icon', WaIconFallback);
    })();
</script>

@endsection
