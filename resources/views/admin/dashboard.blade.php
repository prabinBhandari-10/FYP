@extends('layouts.app')

@section('title', 'Admin Dashboard | Lost and Found')

@section('content')
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
            margin-top: 18px;
        }

        .stat {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 16px;
            background: #fff;
        }

        .stat .value {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 30px;
            line-height: 1;
            font-weight: 800;
            color: var(--text-dark);
        }

        .stat .label {
            margin-top: 8px;
            font-size: 13px;
            color: var(--text-gray);
        }

        .subtitle {
            color: var(--text-gray);
        }

        .admin-sections {
            display: grid;
            gap: 18px;
            margin-top: 18px;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }

        .admin-table th,
        .admin-table td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid var(--border-color);
            font-size: 14px;
        }

        .admin-table th {
            color: var(--text-dark);
            font-weight: 600;
        }

        .admin-table td {
            color: var(--text-gray);
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            text-transform: capitalize;
        }

        .status-pending {
            background: #fff7ed;
            color: #c2410c;
        }

        .status-approved {
            background: #f0fdf4;
            color: #166534;
        }

        .status-rejected {
            background: #fef2f2;
            color: #b91c1c;
        }

        .actions-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 16px;
        }
    </style>

    <section class="card" style="width: min(100%, 860px);">
        <h2>Admin Dashboard</h2>
        <p class="subtitle">Welcome back {{ auth()->user()->name }}. You are signed in as administrator.</p>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="dashboard-grid">
            <div class="stat">
                <div class="value">{{ $stats['totalUsers'] }}</div>
                <div class="label">Total users</div>
            </div>

            <div class="stat">
                <div class="value">{{ $stats['totalReports'] }}</div>
                <div class="label">Total reports</div>
            </div>

            <div class="stat">
                <div class="value">{{ $stats['openReports'] }}</div>
                <div class="label">Open reports</div>
            </div>

            <div class="stat">
                <div class="value">{{ $stats['pendingClaims'] }}</div>
                <div class="label">Pending claims</div>
            </div>

            <div class="stat">
                <div class="value">{{ $stats['approvedClaims'] }}</div>
                <div class="label">Approved claims</div>
            </div>

            <div class="stat">
                <div class="value">{{ $stats['heldClaims'] }}</div>
                <div class="label">Held claims</div>
            </div>
        </div>

        <div class="actions-row">
            <a class="btn btn-primary" href="{{ route('admin.users.index') }}">Manage Users</a>
            <a class="btn btn-primary" href="{{ route('admin.reports.index') }}">Manage Reports (Lost & Found)</a>
            <a class="btn btn-primary" href="{{ route('admin.claims.index') }}">Manage Claims</a>
            <a class="btn btn-outline" href="{{ route('admin.audit-logs.index') }}">View Audit Logs</a>
            <a class="btn btn-outline" href="{{ route('items.index') }}">Browse Reports</a>
        </div>
    </section>

    <section class="card" style="width: min(100%, 860px);">
        <h3 style="margin-bottom: 12px;">Recent Reports</h3>

        @if ($recentReports->isEmpty())
            <p class="subtitle">No reports found yet.</p>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Reporter</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentReports as $report)
                        <tr>
                            <td>{{ $report->title }}</td>
                            <td>{{ ucfirst($report->type) }}</td>
                            <td>{{ $report->user?->name ?? 'Unknown' }}</td>
                            <td>{{ $report->created_at?->diffForHumans() ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </section>

    <section class="card" style="width: min(100%, 860px);">
        <h3 style="margin-bottom: 12px;">Recent Claims</h3>

        @if ($recentClaims->isEmpty())
            <p class="subtitle">No claims found yet.</p>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Claim ID</th>
                        <th>Item</th>
                        <th>Claimant</th>
                        <th>Status</th>
                        <th>Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentClaims as $claim)
                        <tr>
                            <td>#{{ $claim->id }}</td>
                            <td>{{ $claim->report?->title ?? 'Item removed' }}</td>
                            <td>{{ $claim->user?->name ?? 'Unknown' }}</td>
                            <td>
                                @php
                                    $statusClass = $claim->status;
                                    $statusLabel = $claim->status;
                                    if ($claim->status === 'pending' && $claim->held_at) {
                                        $statusClass = 'pending';
                                        $statusLabel = 'on hold';
                                    }
                                @endphp
                                <span class="status-pill status-{{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($claim->created_at)->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    </section>
@endsection
