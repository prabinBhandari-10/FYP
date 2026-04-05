@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<section class="split-layout" style="margin-bottom: 20px; gap: 20px;">
    <!-- Profile Header Card -->
    <article class="card">
        <div style="display: grid; gap: 16px;">
            <div style="padding-bottom: 16px; border-bottom: 1px solid var(--line);">
                <h1 style="font-size: 28px; margin: 0 0 8px;">{{ $user->name }}</h1>
                <p style="color: var(--text-muted); margin: 0;">{{ $user->email }}</p>
                <p style="color: var(--text-muted); margin: 4px 0 0; font-size: 13px; text-transform: capitalize;">{{ $user->role }} Account</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px;">
                <div style="background: var(--bg-soft); padding: 16px; border-radius: 12px; text-align: center;">
                    <p style="margin: 0 0 6px; font-size: 28px; font-weight: 800; color: var(--primary);">{{ $recentActivity['total_reports'] }}</p>
                    <p style="margin: 0; font-size: 13px; color: var(--text-muted);">Total Reports</p>
                </div>
                <div style="background: var(--bg-soft); padding: 16px; border-radius: 12px; text-align: center;">
                    <p style="margin: 0 0 6px; font-size: 28px; font-weight: 800; color: var(--accent);">{{ $recentActivity['total_claims'] }}</p>
                    <p style="margin: 0; font-size: 13px; color: var(--text-muted);">Total Claims</p>
                </div>
                <div style="background: #fff3cd; padding: 16px; border-radius: 12px; text-align: center;">
                    <p style="margin: 0 0 6px; font-size: 28px; font-weight: 800; color: #ff9800;">{{ $recentActivity['pending_claims'] }}</p>
                    <p style="margin: 0; font-size: 13px; color: #666;">Pending Claims</p>
                </div>
            </div>
        </div>
    </article>

    <!-- Activity Sidebar -->
    <aside class="sticky-panel" style="display: grid; gap: 14px;">
        <article class="card card-soft">
            <h3 style="font-size: 16px; margin-bottom: 10px; color: var(--text-main);">📊 Claim Status</h3>
            <div style="display: grid; gap: 8px; font-size: 13px;">
                <div style="display: flex; justify-content: space-between;">
                    <span>✓ Approved</span>
                    <strong style="color: var(--success);">{{ $recentActivity['approved_claims'] }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>✕ Rejected</span>
                    <strong style="color: var(--danger);">{{ $recentActivity['rejected_claims'] }}</strong>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span>⏳ Pending</span>
                    <strong style="color: #ff9800;">{{ $recentActivity['pending_claims'] }}</strong>
                </div>
            </div>
        </article>

        <article class="card card-soft">
            <h3 style="font-size: 16px; margin-bottom: 10px; color: var(--text-main);">🔗 Quick Links</h3>
            <div style="display: grid; gap: 8px;">
                <a href="{{ route('reports.lost.create') }}" class="btn btn-primary" style="text-align: center; font-size: 13px;">Report Lost Item</a>
                <a href="{{ route('reports.found.create') }}" class="btn btn-outline" style="text-align: center; font-size: 13px;">Report Found Item</a>
                <a href="{{ route('claims.index') }}" class="btn btn-ghost" style="text-align: center; font-size: 13px;">View My Claims</a>
            </div>
        </article>
    </aside>
</section>

<!-- My Reports Section -->
<section style="margin-bottom: 20px;">
    <h2 style="font-size: 24px; margin: 0 0 16px;">My Reports</h2>
    @if ($myReports->isEmpty())
        <article class="card card-soft" style="text-align: center; padding: 30px;">
            <p style="color: var(--text-muted); margin: 0;">You haven't reported any items yet.</p>
            <a href="{{ route('reports.lost.create') }}" class="btn btn-primary" style="margin-top: 12px; display: inline-block;">Report an Item</a>
        </article>
    @else
        <div style="display: grid; gap: 12px;">
            @foreach ($myReports as $report)
                <article class="card">
                    <div style="display: grid; grid-template-columns: auto 1fr auto; gap: 16px; align-items: start;">
                        @if ($report->image)
                            <div style="width: 80px; height: 80px; flex-shrink: 0; border-radius: 10px; overflow: hidden; background: var(--bg-soft);">
                                <img src="{{ asset('storage/' . $report->image) }}" alt="{{ $report->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @else
                            <div style="width: 80px; height: 80px; flex-shrink: 0; border-radius: 10px; background: var(--bg-soft); display: flex; align-items: center; justify-content: center; font-size: 32px;">📦</div>
                        @endif

                        <div>
                            <h3 style="font-size: 16px; margin: 0 0 6px; color: var(--text-main);">{{ $report->title }}</h3>
                            <p style="margin: 0 0 6px; font-size: 13px; color: var(--text-muted);">
                                <strong>{{ ucfirst($report->type) }}</strong> in <strong>{{ $report->category }}</strong>
                            </p>
                            <p style="margin: 0 0 6px; font-size: 13px; color: var(--text-muted);">{{ $report->location }}</p>
                            <p style="margin: 0 0 6px; font-size: 12px; color: var(--text-muted);">UID: <strong>{{ $report->report_uid ?? '-' }}</strong></p>
                            <p style="margin: 0; font-size: 12px; color: var(--text-muted);">
                                <span class="badge" style="text-transform: capitalize;">{{ $report->status }}</span>
                                {{ $report->claims()->count() }} claim(s)
                            </p>
                        </div>

                        <div style="text-align: right;">
                            <a href="{{ route('items.show', $report) }}" class="btn btn-outline" style="font-size: 13px;">View Details</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div style="margin-top: 16px;">
            {{ $myReports->links() }}
        </div>
    @endif
</section>

<!-- My Claims Section -->
<section>
    <h2 style="font-size: 24px; margin: 0 0 16px;">My Claims</h2>
    @if ($myClaims->isEmpty())
        <article class="card card-soft" style="text-align: center; padding: 30px;">
            <p style="color: var(--text-muted); margin: 0;">You haven't made any claims yet.</p>
            <a href="{{ route('items.index') }}" class="btn btn-primary" style="margin-top: 12px; display: inline-block;">Browse Items</a>
        </article>
    @else
        <div style="display: grid; gap: 12px;">
            @foreach ($myClaims as $claim)
                <article class="card">
                    <div style="display: grid; grid-template-columns: auto 1fr auto; gap: 16px; align-items: start;">
                        @if ($claim->report->image)
                            <div style="width: 80px; height: 80px; flex-shrink: 0; border-radius: 10px; overflow: hidden; background: var(--bg-soft);">
                                <img src="{{ asset('storage/' . $claim->report->image) }}" alt="{{ $claim->report->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @else
                            <div style="width: 80px; height: 80px; flex-shrink: 0; border-radius: 10px; background: var(--bg-soft); display: flex; align-items: center; justify-content: center; font-size: 32px;">📦</div>
                        @endif

                        <div>
                            <h3 style="font-size: 16px; margin: 0 0 6px; color: var(--text-main);">{{ $claim->report->title }}</h3>
                            <p style="margin: 0 0 6px; font-size: 13px; color: var(--text-muted);">
                                Claimed on <strong>{{ $claim->created_at->format('M d, Y') }}</strong>
                            </p>
                            <p style="margin: 0; font-size: 12px;">
                                @php
                                    $badgeClass = match($claim->status) {
                                        'approved' => 'badge-found',
                                        'rejected' => 'badge-danger',
                                        default => 'badge'
                                    };
                                @endphp
                                <span class="{{ $badgeClass }}" style="text-transform: capitalize;">{{ $claim->status }}</span>
                            </p>
                        </div>

                        <div style="text-align: right;">
                            <a href="{{ route('items.show', $claim->report) }}" class="btn btn-outline" style="font-size: 13px;">View Item</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div style="margin-top: 16px;">
            {{ $myClaims->links() }}
        </div>
    @endif
</section>
@endsection
