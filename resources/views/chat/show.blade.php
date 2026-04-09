@extends('layouts.app')

@section('title', 'Private Chat | Lost & Found')

@section('content')
@if ($errors->any())
    <section class="card" style="margin-bottom: 14px; background: #fee2e2; border-left: 4px solid var(--danger);">
        <div>
            @foreach ($errors->all() as $error)
                <p style="margin: 0; font-size: 14px; color: var(--danger);">{{ $error }}</p>
            @endforeach
        </div>
    </section>
@endif

@if (session('success'))
    <section class="card" style="margin-bottom: 14px; background: #dcfce7; border-left: 4px solid var(--success);">
        <p style="margin: 0; font-size: 14px; color: var(--success);">{{ session('success') }}</p>
    </section>
@endif

<section class="card" style="margin-bottom: 18px;">
    <div style="display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; align-items: start;">
        <div>
            <h1 style="font-size: 30px; margin-bottom: 6px;">Private Chat</h1>
            <p class="section-note">Chat with {{ $otherUser->name }} about the approved claim for <strong>{{ $claim->report?->title }}</strong>.</p>
        </div>
        <div style="text-align: right;">
            <p style="margin: 0; font-size: 13px; color: var(--text-muted);">Unread messages</p>
            <strong style="font-size: 22px; color: var(--primary);">{{ $unreadCount }}</strong>
        </div>
    </div>
</section>

<section class="card" style="margin-bottom: 14px; background: var(--bg-soft); border-left: 4px solid var(--success);">
    <p style="margin: 0; font-size: 14px; color: var(--text-main);">Chat is available because this claim has been approved by admin.</p>
</section>

<section class="card" style="margin-bottom: 14px;">
    <div style="display: grid; gap: 12px; max-height: 520px; overflow-y: auto; padding-right: 4px;">
        @forelse ($messages as $message)
            <div style="display: flex; justify-content: {{ $message->sender_id === $currentUser->id ? 'flex-end' : 'flex-start' }};">
                <div style="max-width: min(78%, 680px); padding: 12px 14px; border-radius: 16px; background: {{ $message->sender_id === $currentUser->id ? 'var(--primary)' : '#f3f4f6' }}; color: {{ $message->sender_id === $currentUser->id ? '#ffffff' : 'var(--text-main)' }};">
                    <div style="display: flex; justify-content: space-between; gap: 10px; margin-bottom: 6px; font-size: 12px; opacity: 0.85;">
                        <strong>{{ $message->sender->name }}</strong>
                        <span>{{ $message->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    <p style="margin: 0; white-space: pre-wrap; line-height: 1.6;">{{ $message->message }}</p>
                </div>
            </div>
        @empty
            <div class="empty-state">No messages yet. Start the conversation below.</div>
        @endforelse
    </div>
</section>

<section class="card">
    <form method="POST" action="{{ route('chat.store', $claim) }}">
        @csrf
        <div class="form-group">
            <label class="form-label" for="message">Write a message</label>
            <textarea class="form-textarea" id="message" name="message" rows="4" placeholder="Type your message here" required>{{ old('message') }}</textarea>
            @error('message')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-primary">Send Message</button>
    </form>
</section>
@endsection