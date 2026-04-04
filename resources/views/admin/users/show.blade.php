@extends('layouts.app')

@section('title', 'User Details | Lost and Found')

@section('content')
<div style="max-width: 1160px; margin: 0 auto; display: grid; gap: 18px;">
    <div style="display: flex; justify-content: space-between; gap: 12px; align-items: center; flex-wrap: wrap;">
        <div>
            <h1 class="page-title" style="margin: 0 0 6px;">User Details</h1>
            <p style="font-size: 14px; color: var(--text-gray);">Complete profile, reports, and claim activity for this account.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Back to Manage Users</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success" style="margin-bottom: 0;">{{ session('success') }}</div>
    @endif

    @if ($errors->has('admin'))
        <div class="alert alert-error" style="margin-bottom: 0;">{{ $errors->first('admin') }}</div>
    @endif

    <div style="display: grid; grid-template-columns: minmax(0, 1fr) 340px; gap: 18px; align-items: start;">
        <div style="display: grid; gap: 14px;">
            <div class="card" style="margin: 0;">
                <h3 style="font-size: 17px; font-weight: 800; margin-bottom: 12px;">Submitted Reports</h3>

                @if ($managedUser->reports->count() === 0)
                    <p style="font-size: 14px; color: var(--text-gray);">No reports submitted by this user.</p>
                @else
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; min-width: 760px;">
                            <thead>
                                <tr>
                                    <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Type</th>
                                    <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Title</th>
                                    <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Status</th>
                                    <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Date</th>
                                    <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($managedUser->reports->take(15) as $report)
                                    <tr>
                                        <td style="padding: 11px 10px; border-bottom: 1px solid var(--border-color);">
                                            @if ($report->type === 'lost')
                                                <span class="badge badge-lost">Lost</span>
                                            @else
                                                <span class="badge badge-found">Found</span>
                                            @endif
                                        </td>
                                        <td style="padding: 11px 10px; border-bottom: 1px solid var(--border-color); color: var(--text-dark); font-weight: 600;">{{ $report->title }}</td>
                                        <td style="padding: 11px 10px; border-bottom: 1px solid var(--border-color);">
                                            <span class="badge badge-neutral" style="text-transform: capitalize;">{{ $report->status }}</span>
                                        </td>
                                        <td style="padding: 11px 10px; border-bottom: 1px solid var(--border-color); color: var(--text-gray);">{{ $report->created_at?->format('M d, Y') }}</td>
                                        <td style="padding: 11px 10px; border-bottom: 1px solid var(--border-color);">
                                            <a href="{{ route('admin.reports.show', $report) }}" class="btn btn-outline" style="padding: 7px 11px;">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="card" style="margin: 0;">
                <h3 style="font-size: 17px; font-weight: 800; margin-bottom: 12px;">Submitted Claims</h3>

                @if ($managedUser->claims->count() === 0)
                    <p style="font-size: 14px; color: var(--text-gray);">No claims submitted by this user.</p>
                @else
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; min-width: 760px;">
                            <thead>
                                <tr>
                                    <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Claim ID</th>
                                    <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Item</th>
                                    <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Status</th>
                                    <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Submitted</th>
                                    <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($managedUser->claims->take(15) as $claim)
                                    <tr>
                                        <td style="padding: 11px 10px; border-bottom: 1px solid var(--border-color); color: var(--text-dark); font-weight: 600;">#{{ $claim->id }}</td>
                                        <td style="padding: 11px 10px; border-bottom: 1px solid var(--border-color); color: var(--text-gray);">{{ $claim->report?->title ?? 'Item removed' }}</td>
                                        <td style="padding: 11px 10px; border-bottom: 1px solid var(--border-color);">
                                            <span class="badge badge-neutral" style="text-transform: capitalize;">{{ $claim->status }}</span>
                                        </td>
                                        <td style="padding: 11px 10px; border-bottom: 1px solid var(--border-color); color: var(--text-gray);">{{ $claim->created_at?->format('M d, Y') }}</td>
                                        <td style="padding: 11px 10px; border-bottom: 1px solid var(--border-color);">
                                            <a href="{{ route('admin.claims.index') }}" class="btn btn-outline" style="padding: 7px 11px;">Open Claims</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <div style="display: grid; gap: 14px;">
            <div class="card" style="margin: 0;">
                <h3 style="font-size: 17px; font-weight: 800; margin-bottom: 12px;">Profile</h3>
                <div style="display: grid; gap: 8px; font-size: 14px; color: var(--text-gray);">
                    <div><strong style="color: var(--text-dark);">Name:</strong> {{ $managedUser->name }}</div>
                    <div><strong style="color: var(--text-dark);">Email:</strong> {{ $managedUser->email }}</div>
                    <div><strong style="color: var(--text-dark);">Role:</strong> {{ ucfirst($managedUser->role) }}</div>
                    <div><strong style="color: var(--text-dark);">Status:</strong> {{ $managedUser->is_blocked ? 'Blocked' : 'Active' }}</div>
                    <div><strong style="color: var(--text-dark);">Joined:</strong> {{ $managedUser->created_at?->format('F d, Y') }}</div>
                </div>
            </div>

            <div class="card" style="margin: 0;">
                <h3 style="font-size: 17px; font-weight: 800; margin-bottom: 12px;">User Stats</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div style="padding: 10px; border: 1px solid var(--border-color); border-radius: 8px;">
                        <div style="font-size: 22px; font-weight: 800; color: var(--text-dark);">{{ $stats['totalReports'] }}</div>
                        <div style="font-size: 12px; color: var(--text-gray);">Total Reports</div>
                    </div>
                    <div style="padding: 10px; border: 1px solid var(--border-color); border-radius: 8px;">
                        <div style="font-size: 22px; font-weight: 800; color: var(--text-dark);">{{ $stats['totalClaims'] }}</div>
                        <div style="font-size: 12px; color: var(--text-gray);">Total Claims</div>
                    </div>
                    <div style="padding: 10px; border: 1px solid var(--border-color); border-radius: 8px;">
                        <div style="font-size: 22px; font-weight: 800; color: var(--text-dark);">{{ $stats['lostReports'] }}</div>
                        <div style="font-size: 12px; color: var(--text-gray);">Lost Reports</div>
                    </div>
                    <div style="padding: 10px; border: 1px solid var(--border-color); border-radius: 8px;">
                        <div style="font-size: 22px; font-weight: 800; color: var(--text-dark);">{{ $stats['foundReports'] }}</div>
                        <div style="font-size: 12px; color: var(--text-gray);">Found Reports</div>
                    </div>
                    <div style="padding: 10px; border: 1px solid var(--border-color); border-radius: 8px;">
                        <div style="font-size: 22px; font-weight: 800; color: var(--text-dark);">{{ $stats['pendingClaims'] }}</div>
                        <div style="font-size: 12px; color: var(--text-gray);">Pending Claims</div>
                    </div>
                    <div style="padding: 10px; border: 1px solid var(--border-color); border-radius: 8px;">
                        <div style="font-size: 22px; font-weight: 800; color: var(--text-dark);">{{ $stats['rejectedClaims'] }}</div>
                        <div style="font-size: 12px; color: var(--text-gray);">Rejected Claims</div>
                    </div>
                </div>
            </div>

            @if ($managedUser->role !== 'admin')
                <div class="card" style="margin: 0;">
                    <h3 style="font-size: 17px; font-weight: 800; margin-bottom: 12px;">Moderation</h3>
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        @if (! $managedUser->is_blocked)
                            <form method="POST" action="{{ route('admin.users.block', $managedUser) }}">
                                @csrf
                                @method('PATCH')
                                <button class="btn" type="submit" style="border-color: #fecaca; background: #fef2f2; color: #b91c1c;">Block User</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.users.unblock', $managedUser) }}">
                                @csrf
                                @method('PATCH')
                                <button class="btn" type="submit" style="border-color: #bbf7d0; background: #f0fdf4; color: #166534;">Unblock User</button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('admin.users.destroy', $managedUser) }}" onsubmit="return confirm('Delete this user account? This should be used only for repeated fake activity.');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline" type="submit">Delete User</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
@media (max-width: 920px) {
    div[style*="grid-template-columns: minmax(0, 1fr) 340px"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
