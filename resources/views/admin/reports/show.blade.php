@extends('layouts.app')

@section('title', ucfirst($report->type) . ' Report Details | Lost and Found')

@section('content')
<div style="max-width: 1100px; margin: 0 auto; display: grid; gap: 18px;">
    <div style="display: flex; justify-content: space-between; gap: 12px; align-items: center; flex-wrap: wrap;">
        <div>
            <h1 class="page-title" style="margin: 0 0 6px;">{{ ucfirst($report->type) }} Report Details</h1>
            <p style="font-size: 14px; color: var(--text-gray);">Full details submitted by user/reporter.</p>
        </div>
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            @if ($report->status === 'pending')
                <form method="POST" action="{{ route('admin.reports.approve', $report) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn" style="color: #166534; border-color: #bbf7d0; background: #f0fdf4;">Approve</button>
                </form>
                <form method="POST" action="{{ route('admin.reports.reject', $report) }}" onsubmit="return confirm('Reject this report and keep it hidden?');">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn" style="color: #b91c1c; border-color: #fecaca; background: #fef2f2;">Reject</button>
                </form>
            @endif
            <a href="{{ route('admin.reports.index') }}" class="btn btn-outline">Back</a>
            <a href="{{ route('admin.reports.edit', $report) }}" class="btn btn-primary">Edit Report</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success" style="margin-bottom: 0;">{{ session('success') }}</div>
    @endif

    <div style="display: grid; grid-template-columns: minmax(0, 1fr) 340px; gap: 18px; align-items: start;">
        <div class="card" style="margin: 0; padding: 0; overflow: hidden;">
            @if ($report->image)
                <img src="{{ asset('storage/' . $report->image) }}" alt="{{ $report->title }}" style="width: 100%; max-height: 420px; object-fit: cover; border-bottom: 1px solid var(--border-color);">
            @endif
            <div style="padding: 24px;">
                <h2 style="font-size: 24px; font-weight: 800; margin-bottom: 10px;">{{ $report->title }}</h2>
                <div style="display: flex; gap: 8px; margin-bottom: 14px; flex-wrap: wrap;">
                    @if ($report->type === 'lost')
                        <span class="badge badge-lost">Lost</span>
                    @else
                        <span class="badge badge-found">Found</span>
                    @endif
                    <span class="badge badge-neutral" style="text-transform: capitalize;">{{ $report->status }}</span>
                    <span class="badge badge-neutral">{{ $report->category }}</span>
                </div>
                <p style="font-size: 15px; color: var(--text-gray); line-height: 1.65; white-space: pre-wrap;">{{ $report->description }}</p>
            </div>
        </div>

        <div style="display: grid; gap: 14px;">
            <div class="card" style="margin: 0;">
                <h3 style="font-size: 16px; font-weight: 800; margin-bottom: 12px;">Reporter Details Entered</h3>
                <div style="display: grid; gap: 8px; font-size: 14px; color: var(--text-gray);">
                    <div><strong style="color: var(--text-dark);">Name:</strong> {{ $report->reporter_name }}</div>
                    <div><strong style="color: var(--text-dark);">Email:</strong> {{ $report->reporter_email }}</div>
                    <div><strong style="color: var(--text-dark);">Phone:</strong> {{ $report->reporter_phone }}</div>
                </div>
            </div>

            <div class="card" style="margin: 0;">
                <h3 style="font-size: 16px; font-weight: 800; margin-bottom: 12px;">System Details</h3>
                <div style="display: grid; gap: 8px; font-size: 14px; color: var(--text-gray);">
                    <div><strong style="color: var(--text-dark);">Report UID:</strong> {{ $report->report_uid ?? '-' }}</div>
                    <div><strong style="color: var(--text-dark);">Submitted By User:</strong> {{ $report->user?->name ?? 'Unknown' }}</div>
                    <div><strong style="color: var(--text-dark);">User Email:</strong> {{ $report->user?->email ?? '-' }}</div>
                    <div><strong style="color: var(--text-dark);">Location:</strong> {{ $report->location }}</div>
                    <div><strong style="color: var(--text-dark);">Date:</strong> {{ $report->date?->format('F d, Y') }}</div>
                    <div><strong style="color: var(--text-dark);">Coordinates:</strong> {{ $report->latitude ?? '-' }}, {{ $report->longitude ?? '-' }}</div>
                    <div><strong style="color: var(--text-dark);">Created At:</strong> {{ $report->created_at?->format('Y-m-d H:i') }}</div>
                </div>
            </div>

            <div class="card" style="margin: 0;">
                <h3 style="font-size: 16px; font-weight: 800; margin-bottom: 12px;">Related Activity</h3>
                <div style="display: grid; gap: 8px; font-size: 14px; color: var(--text-gray);">
                    <div><strong style="color: var(--text-dark);">Claims:</strong> {{ $report->claims->count() }}</div>
                    <div><strong style="color: var(--text-dark);">Sightings:</strong> {{ $report->sightings->count() }}</div>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.reports.destroy', $report) }}" onsubmit="return confirm('Delete this lost report? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button class="btn" type="submit" style="width: 100%; color: #b91c1c; border: 1px solid #fecaca; background: #fef2f2;">Delete This Lost Report</button>
            </form>
        </div>
    </div>
</div>

<style>
@media (max-width: 900px) {
    div[style*="grid-template-columns: minmax(0, 1fr) 340px"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
