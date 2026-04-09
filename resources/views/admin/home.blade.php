@extends('layouts.app')

@section('title', 'Admin Home | Lost & Found')

@section('content')
@php
    $adminName = auth()->guard('admin')->user()?->name ?? auth()->guard('web')->user()?->name ?? 'Admin';
@endphp

<section class="card" style="margin-bottom: 20px; background: linear-gradient(135deg, #ffffff 0%, #f4faff 100%);">
    <div style="display: flex; justify-content: space-between; gap: 14px; align-items: center; flex-wrap: wrap;">
        <div>
            <h1 style="font-size: 34px; margin-bottom: 6px;">Welcome back, {{ $adminName }}</h1>
            <p class="section-note">Quick control page for daily admin tasks, short summaries, and urgent actions.</p>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a class="btn btn-primary" href="{{ route('admin.users.index') }}">Manage Users</a>
            <a class="btn btn-outline" href="{{ route('admin.reports.index') }}">Manage Reports</a>
            <a class="btn btn-outline" href="{{ route('admin.claims.index') }}">Manage Claims</a>
            <a class="btn btn-outline" href="{{ route('admin.payments.index') }}">Payments</a>
            <a class="btn btn-outline" href="{{ route('admin.notifications.index') }}">Notifications</a>
            <a class="btn btn-outline" href="{{ route('admin.about-contents.index') }}">About Page</a>
            <a class="btn btn-outline" href="{{ route('admin.contact-messages.index') }}">Contact Page</a>
        </div>
    </div>
</section>

<section class="grid-4" style="margin-bottom: 20px;">
    <article class="stat-card"><div class="stat-value">{{ $stats['totalUsers'] }}</div><div class="stat-label">Total Users</div></article>
    <article class="stat-card"><div class="stat-value">{{ $stats['totalReports'] }}</div><div class="stat-label">Total Reports</div></article>
    <article class="stat-card"><div class="stat-value">{{ $stats['pendingClaims'] }}</div><div class="stat-label">Pending Claims</div></article>
    <article class="stat-card"><div class="stat-value">{{ $stats['reportsToday'] }}</div><div class="stat-label">Reports Today</div></article>
</section>

<section class="grid-3" style="margin-bottom: 20px;">
    <article class="card card-soft">
        <h2 style="font-size: 20px; margin-bottom: 12px;">Latest Report</h2>
        @if ($latestReport)
            <p style="font-size: 16px; font-weight: 800; margin-bottom: 6px;">{{ $latestReport->title }}</p>
            <p class="section-note" style="margin-bottom: 6px;">{{ ucfirst($latestReport->type) }} | {{ $latestReport->category }}</p>
            <p class="section-note" style="margin-bottom: 6px;">{{ $latestReport->location }}</p>
            <p style="font-size: 13px; color: var(--text-muted); margin: 0;">{{ $latestReport->created_at?->diffForHumans() }}</p>
        @else
            <div class="empty-state">No report available yet.</div>
        @endif
    </article>

    <article class="card card-soft">
        <h2 style="font-size: 20px; margin-bottom: 12px;">Latest Claim</h2>
        @if ($latestClaim)
            <p style="font-size: 16px; font-weight: 800; margin-bottom: 6px;">{{ $latestClaim->report?->title ?? 'Item' }}</p>
            <p class="section-note" style="margin-bottom: 6px;">Claimant: {{ $latestClaim->user?->name ?? 'Unknown' }}</p>
            <p class="section-note" style="margin-bottom: 6px; text-transform: capitalize;">Status: {{ $latestClaim->status }}</p>
            <p style="font-size: 13px; color: var(--text-muted); margin: 0;">{{ $latestClaim->created_at?->diffForHumans() }}</p>
        @else
            <div class="empty-state">No claim available yet.</div>
        @endif
    </article>

    <article class="card card-soft">
        <h2 style="font-size: 20px; margin-bottom: 12px;">Latest Notification</h2>
        @if ($latestNotification)
            <p style="font-size: 16px; font-weight: 800; margin-bottom: 6px;">{{ $latestNotification->title }}</p>
            <p class="section-note" style="margin-bottom: 6px;">{{ $latestNotification->message }}</p>
            <p style="font-size: 13px; color: var(--text-muted); margin: 0;">{{ $latestNotification->created_at?->diffForHumans() }}</p>
        @else
            <div class="empty-state">No notification available yet.</div>
        @endif
    </article>
</section>

<section class="grid-3" style="margin-bottom: 20px;">
    <article class="card card-soft">
        <h2 style="font-size: 20px; margin-bottom: 10px;">Attention Needed</h2>
        <div style="display: grid; gap: 10px; font-size: 14px;">
            <div style="display: flex; justify-content: space-between; gap: 10px;"><span>Claims waiting for review</span><strong>{{ $stats['pendingClaims'] }}</strong></div>
            <div style="display: flex; justify-content: space-between; gap: 10px;"><span>Urgent reports</span><strong>{{ $urgentReports->count() }}</strong></div>
            <div style="display: flex; justify-content: space-between; gap: 10px;"><span>Payment pending items</span><strong>0</strong></div>
        </div>
        @if ($urgentReports->isNotEmpty())
            <div style="margin-top: 12px; display: grid; gap: 8px;">
                @foreach ($urgentReports as $report)
                    <div style="padding: 10px 12px; border-radius: 12px; background: #fff7ed; border: 1px solid #fed7aa;">
                        <div style="font-weight: 700;">{{ $report->title }}</div>
                        <div style="font-size: 12px; color: var(--text-muted);">Pending since {{ $report->created_at->format('M d, Y') }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="section-note" style="margin-top: 12px;">No urgent reports right now.</p>
        @endif
    </article>

    <article class="card card-soft">
        <h2 style="font-size: 20px; margin-bottom: 10px;">Pending Claims</h2>
        @if ($pendingClaims->isEmpty())
            <div class="empty-state">No pending claims.</div>
        @else
            <div style="display: grid; gap: 8px;">
                @foreach ($pendingClaims as $claim)
                    <div style="padding: 10px 12px; border-radius: 12px; background: #fff; border: 1px solid var(--line);">
                        <div style="font-weight: 700; margin-bottom: 4px;">{{ $claim->report?->title ?? 'Item' }}</div>
                        <div style="font-size: 12px; color: var(--text-muted);">{{ $claim->user?->name ?? 'Unknown' }} | {{ $claim->created_at->diffForHumans() }}</div>
                    </div>
                @endforeach
            </div>
        @endif
    </article>

    <article class="card card-soft">
        <h2 style="font-size: 20px; margin-bottom: 10px;">Platform Summary</h2>
        <p class="section-note" style="line-height: 1.7; margin: 0 0 10px;">
            This page is a quick landing area for daily admin work. Use it to jump into the main management sections without digging into analytics.
        </p>
        <div style="display: grid; gap: 8px;">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline" style="justify-content: center;">Open Dashboard</a>
            <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline" style="justify-content: center;">Review Contact Messages</a>
            <a href="{{ route('admin.about-contents.index') }}" class="btn btn-outline" style="justify-content: center;">Manage About Content</a>
        </div>
    </article>
</section>
@endsection
