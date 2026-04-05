@extends('layouts.app')

@section('title', 'Admin Dashboard | Lost & Found')

@section('content')
<section class="card" style="margin-bottom: 20px; background: linear-gradient(135deg, #ffffff 0%, #f4faff 100%);">
    <div class="section-head">
        <div>
            <h1 style="font-size: 34px; margin-bottom: 6px;">Admin Dashboard</h1>
            <p class="section-note">Monitor platform activity, manage claims, and keep data organized.</p>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a class="btn btn-primary" href="{{ route('admin.users.index') }}">Manage Users</a>
            <a class="btn btn-outline" href="{{ route('admin.reports.index') }}">Manage Reports</a>
            <a class="btn btn-outline" href="{{ route('admin.claims.index') }}">Manage Claims</a>
        </div>
    </div>
</section>

<section class="stats-grid" style="margin-bottom: 20px;">
    <article class="stat-card"><div class="stat-value">{{ $stats['totalUsers'] }}</div><div class="stat-label">Total Users</div></article>
    <article class="stat-card"><div class="stat-value">{{ $stats['totalReports'] }}</div><div class="stat-label">Total Reports</div></article>
    <article class="stat-card"><div class="stat-value">{{ $stats['openReports'] }}</div><div class="stat-label">Open Reports</div></article>
    <article class="stat-card"><div class="stat-value">{{ $stats['pendingClaims'] }}</div><div class="stat-label">Pending Claims</div></article>
    <article class="stat-card"><div class="stat-value">{{ $stats['approvedClaims'] }}</div><div class="stat-label">Approved Claims</div></article>
    <article class="stat-card"><div class="stat-value">{{ $stats['heldClaims'] }}</div><div class="stat-label">Held Claims</div></article>
</section>

<section class="grid-2" style="margin-bottom: 20px;">
    <div class="card">
        <div class="section-head">
            <h2 style="font-size: 22px;">Recent Reports</h2>
        </div>

        @if ($recentReports->isEmpty())
            <div class="empty-state">No recent reports available.</div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Reporter</th>
                            <th>Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentReports as $report)
                            <tr>
                                <td>{{ $report->title }}</td>
                                <td><span class="badge {{ $report->type === 'lost' ? 'badge-lost' : 'badge-found' }}">{{ ucfirst($report->type) }}</span></td>
                                <td>{{ $report->user?->name ?? 'Unknown' }}</td>
                                <td>{{ $report->created_at?->diffForHumans() ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="card">
        <div class="section-head">
            <h2 style="font-size: 22px;">Recent Claims</h2>
        </div>

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
    </div>
</section>
@endsection
