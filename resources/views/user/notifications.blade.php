@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<section class="card" style="margin-bottom: 20px;">
    <div style="display: grid; grid-template-columns: 1fr auto; gap: 12px; align-items: center;">
        <div>
            <h1 style="font-size: 28px; margin: 0;">Notifications</h1>
            <p class="section-note" style="margin-top: 4px;">{{ Auth::user()->notifications()->where('is_read', false)->count() }} unread</p>
        </div>
        @if (Auth::user()->notifications()->where('is_read', false)->exists())
            <form method="POST" action="{{ route('notifications.read-all') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="btn btn-outline">Mark all as read</button>
            </form>
        @endif
    </div>
</section>

<section>
    @if ($notifications->isEmpty())
        <article class="card card-soft" style="text-align: center; padding: 40px 20px;">
            <p style="font-size: 16px; color: var(--text-muted); margin: 0;">No notifications yet</p>
        </article>
    @else
        <div style="display: grid; gap: 12px;">
            @foreach ($notifications as $notification)
                <article class="card {{ !$notification->is_read ? 'notification-unread' : '' }}" style="@if (!$notification->is_read) background: var(--bg-soft); border-left: 4px solid var(--primary); @endif">
                    <div style="display: grid; grid-template-columns: 1fr auto; gap: 12px; align-items: start;">
                        <div>
                            <h3 style="font-size: 16px; margin: 0 0 6px; color: var(--text-main);">{{ $notification->title }}</h3>
                            <p style="margin: 0 0 8px; color: var(--text-muted);">{{ $notification->message }}</p>
                            <p style="margin: 0; font-size: 12px; color: var(--text-muted);">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <div style="display: flex; gap: 8px;">
                            @if (!$notification->is_read)
                                <form method="POST" action="{{ route('notifications.read', $notification) }}" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="btn btn-ghost" style="padding: 8px 12px; font-size: 12px;">Mark read</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('notifications.delete', $notification) }}" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-ghost" style="padding: 8px 12px; font-size: 12px; color: var(--danger);">Delete</button>
                            </form>
                        </div>
                    </div>

                    @if ($notification->related_report_id)
                        <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--line);">
                            <a href="{{ route('items.show', $notification->related_report_id) }}" class="btn btn-outline" style="font-size: 13px;">View Item</a>
                        </div>
                    @endif

                    @if ($notification->related_claim_id)
                        <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--line);">
                            <a href="{{ route('claims.index') }}" class="btn btn-outline" style="font-size: 13px;">View Claims</a>
                        </div>
                    @endif
                </article>
            @endforeach
        </div>

        <div style="margin-top: 20px;">
            {{ $notifications->links() }}
        </div>
    @endif
</section>
@endsection
