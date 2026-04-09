@extends('layouts.app')

@section('title', 'Admin Notifications | Lost & Found')

@section('content')
<section class="card" style="margin-bottom: 20px;">
    <div style="display: flex; justify-content: space-between; gap: 12px; align-items: center; flex-wrap: wrap;">
        <div>
            <h1 class="page-title" style="margin-bottom: 8px;">Admin Notifications</h1>
            <p class="page-subtitle">Latest system notifications and report-related alerts.</p>
        </div>
        <span class="badge badge-neutral">Unread: {{ $unreadCount }}</span>
    </div>
</section>

<section class="card">
    @if ($notifications->count())
        <div style="display: grid; gap: 12px;">
            @foreach ($notifications as $notification)
                <article class="card card-soft" style="padding: 16px; margin: 0;">
                    <div style="display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; align-items: center; margin-bottom: 8px;">
                        <h3 style="font-size: 18px; margin: 0;">{{ $notification->title }}</h3>
                        <span class="badge {{ $notification->is_read ? 'badge-neutral' : 'badge-found' }}" style="text-transform: capitalize;">{{ $notification->is_read ? 'read' : 'unread' }}</span>
                    </div>
                    <p style="margin: 0 0 8px; color: var(--text-muted); line-height: 1.7;">{{ $notification->message }}</p>
                    <p style="font-size: 12px; color: var(--text-soft); margin: 0 0 10px;">
                        {{ $notification->created_at->diffForHumans() }}
                        @if ($notification->user)
                            | User: {{ $notification->user->name }}
                        @endif
                    </p>
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        @if ($notification->report)
                            <a href="{{ route('items.show', $notification->report) }}" class="btn btn-outline" style="padding: 7px 12px; font-size: 12px;">View Item</a>
                        @endif
                        @if ($notification->claim)
                            <a href="{{ route('admin.claims.index') }}" class="btn btn-outline" style="padding: 7px 12px; font-size: 12px;">View Claim</a>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        <div style="margin-top: 16px;">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="empty-state">No notifications available.</div>
    @endif
</section>
@endsection
