@extends('layouts.app')

@section('title', 'View Message | Admin')

@section('content')
<section class="card" style="margin-bottom: 20px;">
    <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-ghost" style="padding-left: 0;">← Back</a>
</section>

<section class="split-layout" style="margin-bottom: 20px;">
    <article class="card">
        <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid var(--line);">
            <h1 style="font-size: 28px; margin-bottom: 8px;">{{ $message->subject }}</h1>
            <p class="section-note" style="margin-bottom: 12px;">From: <strong>{{ $message->name }}</strong> ({{ $message->email }})</p>
            <p class="section-note">Received: {{ $message->created_at->format('F d, Y h:i A') }}</p>
        </div>

        <div style="margin-bottom: 26px;">
            <h3 style="font-size: 16px; margin-bottom: 10px; color: var(--text-main);">Message</h3>
            <p style="white-space: pre-wrap; line-height: 1.8; color: var(--text-muted); margin: 0;">{{ $message->message }}</p>
        </div>

        @if ($message->admin_response)
            <div style="background: var(--bg-soft); padding: 16px; border-radius: 12px; border-left: 4px solid var(--primary); margin-bottom: 20px;">
                <h3 style="font-size: 16px; margin: 0 0 8px; color: var(--text-main);">✓ Admin Response</h3>
                <p style="margin: 0 0 6px; font-size: 13px; color: var(--text-muted);">Responded by admin on {{ $message->responded_at->format('F d, Y h:i A') }}</p>
                <p style="white-space: pre-wrap; line-height: 1.8; color: var(--text-muted); margin-top: 6px;">{{ $message->admin_response }}</p>
            </div>
        @endif
    </article>

    <aside class="sticky-panel" style="display: grid; gap: 14px;">
        <article class="card">
            <h3 style="font-size: 18px; margin-bottom: 12px; color: var(--text-main);">Actions</h3>

            <form method="POST" action="{{ route('admin.contact-messages.respond', $message) }}" style="display: grid; gap: 12px;">
                @csrf
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Your Response</label>
                    <textarea class="form-textarea" name="admin_response" placeholder="Write your response..." required style="min-height: 140px; font-size: 13px;">{{ old('admin_response', $message->admin_response) }}</textarea>
                    @error('admin_response')<div style="color: var(--danger); font-size: 12px; margin-top: 4px;">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">{{ $message->admin_response ? 'Update Response' : 'Send Response' }}</button>
            </form>

            <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline" style="width: 100%; margin-top: 8px;">Back to Messages</a>

            <form method="POST" action="{{ route('admin.contact-messages.delete', $message) }}" onsubmit="return confirm('Delete this message?');" style="margin: 0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn" style="width: 100%; margin-top: 8px; background: #fff4f6; color: var(--danger); border: 1px solid var(--danger); border-radius: 999px; padding: 11px 18px; font-size: 14px; font-weight: 800; cursor: pointer;">Delete Message</button>
            </form>
        </article>

        <article class="card card-soft">
            <h3 style="font-size: 16px; margin-bottom: 8px; color: var(--text-main);">Message Info</h3>
            <div style="display: grid; gap: 8px; font-size: 13px;">
                <p style="margin: 0;"><strong>Status:</strong> <span class="badge" style="text-transform: capitalize;">{{ $message->status }}</span></p>
                <p style="margin: 0;"><strong>Email:</strong> {{ $message->email }}</p>
                <p style="margin: 0;"><strong>Created:</strong> {{ $message->created_at->format('M d, Y') }}</p>
            </div>
        </article>
    </aside>
</section>
@endsection
