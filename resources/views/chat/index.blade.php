@extends('layouts.app')

@section('title', 'Chats | Lost & Found')

@section('content')
<section class="card" style="margin-bottom: 16px;">
    <h1 style="font-size: 30px; margin-bottom: 6px;">My Chats</h1>
    <p class="section-note">Private conversations unlocked by admin-approved claims.</p>
</section>

@if ($conversations->isEmpty())
    <section class="empty-state">No chat conversations yet. Chats appear after an approved claim.</section>
@else
    <section style="display: grid; gap: 12px; margin-bottom: 14px;">
        @foreach ($conversations as $conversation)
            @php
                $otherUser = $conversation->finder_id === $currentUser->id
                    ? $conversation->claimant
                    : $conversation->finder;
            @endphp

            <article class="card card-hover" style="display: grid; gap: 10px;">
                <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap;">
                    <div>
                        <h3 style="font-size: 19px; margin: 0 0 4px;">{{ $conversation->claim?->report?->title ?? 'Approved Claim Chat' }}</h3>
                        <p class="section-note" style="margin: 0;">Chat with {{ $otherUser?->name ?? 'User' }}</p>
                    </div>

                    @if ($conversation->unread_count > 0)
                        <span class="badge badge-neutral">{{ $conversation->unread_count }} unread</span>
                    @endif
                </div>

                @if ($conversation->latestMessage)
                    <div style="padding: 10px 12px; border-radius: 10px; background: var(--bg-soft); border: 1px solid var(--line);">
                        <p style="margin: 0 0 4px; font-size: 13px; color: var(--text-muted);">
                            Last message by {{ $conversation->latestMessage->sender?->name ?? 'User' }}
                            on {{ $conversation->latestMessage->created_at->format('M d, Y h:i A') }}
                        </p>
                        <p style="margin: 0; font-size: 14px; color: var(--text-main);">
                            {{ Illuminate\Support\Str::limit($conversation->latestMessage->message, 120) }}
                        </p>
                    </div>
                @endif

                @if ($conversation->claim)
                    <a href="{{ route('chat.show', $conversation->claim) }}" class="btn btn-primary" style="width: fit-content;">Open Chat</a>
                @endif
            </article>
        @endforeach
    </section>

    <div style="margin-top: 10px;">
        {{ $conversations->links() }}
    </div>
@endif
@endsection