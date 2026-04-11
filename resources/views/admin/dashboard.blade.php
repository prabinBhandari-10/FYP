@extends('layouts.app')

@section('title', 'Admin Dashboard | Lost & Found')

@section('content')
@php
    $maxDailyReports = max(array_column($dailyReportTrend ?: [['count' => 0]], 'count')) ?: 1;
    $reportTypeTotal = max($reportTypeBreakdown->sum('total'), 1);
    $claimStatusTotal = max($claimStatusBreakdown->sum('total'), 1);
@endphp

<section class="card" style="margin-bottom: 20px; background: linear-gradient(135deg, #ffffff 0%, #f4faff 100%);">
    <div style="display: flex; justify-content: space-between; gap: 14px; align-items: center; flex-wrap: wrap;">
        <div>
            <h1 style="font-size: 34px; margin-bottom: 6px;">Admin Dashboard</h1>
            <p class="section-note">Monitoring view for trends, status breakdowns, and recent system activity.</p>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a class="btn btn-outline" href="{{ route('admin.home') }}">Home</a>
            <a class="btn btn-primary" href="{{ route('admin.users.index') }}">Manage Users</a>
            <a class="btn btn-outline" href="{{ route('admin.reports.index') }}">Manage Reports</a>
            <a class="btn btn-outline" href="{{ route('admin.claims.index') }}">Manage Claims</a>
            <a class="btn btn-outline" href="{{ route('reports.track.form') }}">Track Report</a>
            <a class="btn btn-outline" href="{{ route('admin.about-contents.index') }}">About Page</a>
            <a class="btn btn-outline" href="{{ route('admin.articles.index') }}">Articles</a>
            <a class="btn btn-outline" href="{{ route('admin.contact-messages.index') }}">Contact Page</a>
        </div>
    </div>
</section>

<section style="margin-bottom: 20px;">
    <h2 style="font-size: 18px; margin-bottom: 12px;">Quick Create Lost & Found Report</h2>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="{{ route('admin.reports.create', 'lost') }}" class="btn btn-outline">Create Lost Report</a>
        <a href="{{ route('admin.reports.create', 'found') }}" class="btn btn-outline">Create Found Report</a>
    </div>
</section>

<section class="stats-grid" style="margin-bottom: 20px;">
    <article class="stat-card"><div class="stat-value">{{ $stats['totalUsers'] }}</div><div class="stat-label">Total Users</div></article>
    <article class="stat-card"><div class="stat-value">{{ $stats['totalReports'] }}</div><div class="stat-label">Total Reports</div></article>
    <article class="stat-card"><div class="stat-value">{{ $stats['openReports'] }}</div><div class="stat-label">Open Reports</div></article>
    <article class="stat-card"><div class="stat-value">{{ $stats['closedReports'] }}</div><div class="stat-label">Closed Reports</div></article>
    <article class="stat-card"><div class="stat-value">{{ $stats['pendingClaims'] }}</div><div class="stat-label">Pending Claims</div></article>
    <article class="stat-card"><div class="stat-value">{{ $stats['reportsToday'] }}</div><div class="stat-label">Reports Today</div></article>
</section>

<section class="grid-2" style="margin-bottom: 20px;">
    <article class="card card-soft">
        <h2 style="font-size: 22px; margin-bottom: 10px;">Report Type Breakdown</h2>
        @if ($reportTypeBreakdown->isEmpty())
            <div class="empty-state">No report data available.</div>
        @else
            <div style="display: grid; gap: 12px;">
                @foreach ($reportTypeBreakdown as $item)
                    @php $width = round(($item->total / $reportTypeTotal) * 100); @endphp
                    <div>
                        <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 6px;">
                            <span>{{ ucfirst($item->type) }}</span>
                            <strong>{{ $item->total }}</strong>
                        </div>
                        <div style="height: 10px; border-radius: 999px; background: var(--bg-soft); overflow: hidden;">
                            <div style="width: {{ $width }}%; height: 100%; background: {{ $item->type === 'lost' ? '#f59e0b' : '#3b82f6' }};"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </article>

    <article class="card card-soft">
        <h2 style="font-size: 22px; margin-bottom: 10px;">Claim Status Breakdown</h2>
        @if ($claimStatusBreakdown->isEmpty())
            <div class="empty-state">No claim data available.</div>
        @else
            <div style="display: grid; gap: 12px;">
                @foreach ($claimStatusBreakdown as $item)
                    @php $width = round(($item->total / $claimStatusTotal) * 100); @endphp
                    <div>
                        <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 6px; text-transform: capitalize;">
                            <span>{{ $item->status }}</span>
                            <strong>{{ $item->total }}</strong>
                        </div>
                        <div style="height: 10px; border-radius: 999px; background: var(--bg-soft); overflow: hidden;">
                            <div style="width: {{ $width }}%; height: 100%; background: {{ $item->status === 'approved' ? '#16a34a' : ($item->status === 'rejected' ? '#dc2626' : '#f59e0b') }};"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </article>
</section>

<section class="card" style="margin-bottom: 20px;">
    <h2 style="font-size: 22px; margin-bottom: 14px;">7 Day Report Trend</h2>
    @if (empty($dailyReportTrend))
        <div class="empty-state">No trend data available.</div>
    @else
        <div style="display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); gap: 10px; align-items: end; min-height: 220px;">
            @foreach ($dailyReportTrend as $day)
                @php $height = round(($day['count'] / $maxDailyReports) * 160); @endphp
                <div style="display: grid; gap: 8px; text-align: center;">
                    <div style="display: flex; align-items: end; justify-content: center; min-height: 170px;">
                        <div style="width: 100%; max-width: 56px; height: {{ $height }}px; border-radius: 14px 14px 6px 6px; background: linear-gradient(180deg, var(--primary) 0%, var(--accent) 100%);"></div>
                    </div>
                    <div style="font-size: 12px; color: var(--text-muted);">{{ $day['label'] }}</div>
                    <div style="font-size: 12px; font-weight: 800; color: var(--text-main);">{{ $day['count'] }}</div>
                </div>
            @endforeach
        </div>
    @endif
</section>

<section class="grid-2" style="margin-bottom: 20px;">
    <article class="card card-soft">
        <h2 style="font-size: 22px; margin-bottom: 12px;">Top Categories</h2>
        @if ($topCategories->isEmpty())
            <div class="empty-state">No categories yet.</div>
        @else
            <div style="display: grid; gap: 10px;">
                @foreach ($topCategories as $category)
                    <div style="padding: 10px 12px; border-radius: 12px; border: 1px solid var(--line); background: #fff; display: flex; justify-content: space-between; gap: 10px;">
                        <span>{{ $category->category }}</span>
                        <strong>{{ $category->total }}</strong>
                    </div>
                @endforeach
            </div>
        @endif
    </article>

    <article class="card card-soft">
        <h2 style="font-size: 22px; margin-bottom: 12px;">Top Locations</h2>
        @if ($topLocations->isEmpty())
            <div class="empty-state">No location data yet.</div>
        @else
            <div style="display: grid; gap: 10px;">
                @foreach ($topLocations as $location)
                    <div style="padding: 10px 12px; border-radius: 12px; border: 1px solid var(--line); background: #fff; display: flex; justify-content: space-between; gap: 10px;">
                        <span>{{ $location->location }}</span>
                        <strong>{{ $location->total }}</strong>
                    </div>
                @endforeach
            </div>
        @endif
    </article>
</section>

<section class="grid-2" style="margin-bottom: 20px;">
    <article class="card">
        <h2 style="font-size: 22px; margin-bottom: 12px;">Recent Reports</h2>
        @if ($recentReports->isEmpty())
            <div class="empty-state">No recent reports available.</div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentReports as $report)
                            <tr>
                                <td>{{ $report->title }}</td>
                                <td><span class="badge {{ $report->type === 'lost' ? 'badge-lost' : 'badge-found' }}">{{ ucfirst($report->type) }}</span></td>
                                <td><span class="badge badge-neutral" style="text-transform: capitalize;">{{ $report->status }}</span></td>
                                <td>{{ $report->created_at?->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </article>

    <article class="card">
        <h2 style="font-size: 22px; margin-bottom: 12px;">Recent Claims</h2>
        @if ($recentClaims->isEmpty())
            <div class="empty-state">No recent claims available.</div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Claimant</th>
                            <th>Status</th>
                            <th>Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentClaims as $claim)
                            <tr>
                                <td>{{ $claim->report?->title ?? 'Item removed' }}</td>
                                <td>{{ $claim->user?->name ?? 'Unknown' }}</td>
                                <td>
                                    @php
                                        $label = $claim->status === 'pending' && $claim->held_at ? 'on hold' : $claim->status;
                                    @endphp
                                    <span class="badge badge-neutral" style="text-transform: capitalize;">{{ $label }}</span>
                                </td>
                                <td>{{ $claim->created_at?->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </article>
</section>

<section class="card" style="margin-bottom: 20px;">
    <h2 style="font-size: 22px; margin-bottom: 12px;">Recent User Registrations</h2>
    @if ($recentUsers->isEmpty())
        <div class="empty-state">No recent users available.</div>
    @else
        <div style="display: grid; gap: 10px;">
            @foreach ($recentUsers as $user)
                <div style="padding: 12px 14px; border-radius: 12px; border: 1px solid var(--line); background: #fff; display: flex; justify-content: space-between; gap: 10px; flex-wrap: wrap;">
                    <div>
                        <div style="font-weight: 700;">{{ $user->name }}</div>
                        <div style="font-size: 13px; color: var(--text-muted);">{{ $user->email }} | {{ ucfirst($user->role) }}</div>
                    </div>
                    <div style="font-size: 12px; color: var(--text-soft);">{{ $user->created_at?->diffForHumans() }}</div>
                </div>
            @endforeach
        </div>
    @endif
</section>
@endsection
