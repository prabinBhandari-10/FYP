@extends('layouts.app')

@section('title', 'Manage Reports | Lost and Found')

@section('content')
<div style="max-width: 1160px; margin: 0 auto; display: grid; gap: 18px;">
    <div class="card" style="margin: 0;">
        <div style="display: flex; justify-content: space-between; gap: 14px; align-items: center; flex-wrap: wrap; margin-bottom: 14px;">
            <div>
                <h2 style="font-size: 24px; font-weight: 800; margin-bottom: 6px;">Manage Reports</h2>
                <p style="font-size: 14px; color: var(--text-gray);">Admins can create, update, delete, and inspect all user-entered details for lost and found reports.</p>
            </div>
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                <a href="{{ route('admin.reports.create', ['type' => 'lost']) }}" class="btn btn-primary">Create Lost Report</a>
                <a href="{{ route('admin.reports.create', ['type' => 'found']) }}" class="btn btn-outline">Create Found Report</a>
                <a href="{{ route('admin.reports.export.csv', request()->query()) }}" class="btn btn-outline">Export CSV</a>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.reports.index') }}" style="display: grid; grid-template-columns: minmax(0, 1fr) 170px 170px auto; gap: 10px; align-items: center;">
            <input class="form-input" type="text" name="q" value="{{ $filters['q'] }}" placeholder="Search title, description, reporter...">
            <select class="form-select" name="type">
                <option value="">All types</option>
                <option value="lost" @selected($filters['type'] === 'lost')>Lost</option>
                <option value="found" @selected($filters['type'] === 'found')>Found</option>
            </select>
            <select class="form-select" name="status">
                <option value="">All status</option>
                <option value="open" @selected($filters['status'] === 'open')>Open</option>
                <option value="closed" @selected($filters['status'] === 'closed')>Closed</option>
            </select>
            <button class="btn btn-outline" type="submit">Filter</button>
        </form>
    </div>

    @if (session('success'))
        <div class="alert alert-success" style="margin-bottom: 0;">{{ session('success') }}</div>
    @endif

    <div class="card" style="margin: 0; overflow-x: auto;">
        @if ($reports->count() === 0)
            <p style="color: var(--text-gray);">No reports found for the selected filters.</p>
        @else
            <table style="width: 100%; border-collapse: collapse; min-width: 980px;">
                <thead>
                    <tr>
                        <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Type</th>
                        <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Title</th>
                        <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Reporter Contact</th>
                        <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Submitted By</th>
                        <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Location</th>
                        <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Status</th>
                        <th style="text-align: left; padding: 10px; border-bottom: 1px solid var(--border-color);">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                        <tr>
                            <td style="padding: 12px 10px; border-bottom: 1px solid var(--border-color);">
                                @if ($report->type === 'lost')
                                    <span class="badge badge-lost">Lost</span>
                                @else
                                    <span class="badge badge-found">Found</span>
                                @endif
                            </td>
                            <td style="padding: 12px 10px; border-bottom: 1px solid var(--border-color);">
                                <div style="font-weight: 700; color: var(--text-dark);">{{ $report->title }}</div>
                                <div style="font-size: 12px; color: var(--text-gray);">{{ $report->category }} | {{ $report->date?->format('M d, Y') }}</div>
                            </td>
                            <td style="padding: 12px 10px; border-bottom: 1px solid var(--border-color); font-size: 13px; color: var(--text-gray);">
                                <div>{{ $report->reporter_name }}</div>
                                <div>{{ $report->reporter_email }}</div>
                                <div>{{ $report->reporter_phone }}</div>
                            </td>
                            <td style="padding: 12px 10px; border-bottom: 1px solid var(--border-color); font-size: 13px; color: var(--text-gray);">
                                {{ $report->user?->name ?? 'Unknown' }}
                            </td>
                            <td style="padding: 12px 10px; border-bottom: 1px solid var(--border-color); font-size: 13px; color: var(--text-gray);">
                                {{ $report->location }}
                            </td>
                            <td style="padding: 12px 10px; border-bottom: 1px solid var(--border-color);">
                                <span class="badge badge-neutral" style="text-transform: capitalize;">{{ $report->status }}</span>
                            </td>
                            <td style="padding: 12px 10px; border-bottom: 1px solid var(--border-color);">
                                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                    <a class="btn btn-outline" style="padding: 7px 12px;" href="{{ route('admin.reports.show', $report) }}">View</a>
                                    <a class="btn btn-outline" style="padding: 7px 12px;" href="{{ route('admin.reports.edit', $report) }}">Edit</a>
                                    <form method="POST" action="{{ route('admin.reports.destroy', $report) }}" onsubmit="return confirm('Delete this lost report?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn" type="submit" style="padding: 7px 12px; color: #b91c1c; border-color: #fecaca; background: #fef2f2;">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 16px;">
                {{ $reports->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
