@extends('layouts.app')

@section('title', 'My Claims | Lost & Found')

@section('content')
<section class="card" style="margin-bottom: 18px;">
    <h1 style="font-size: 32px; margin-bottom: 8px;">My Claims</h1>
    <p class="section-note">Track your submitted claims and review their latest status.</p>
</section>

@if ($claims->count() === 0)
    <div class="empty-state">You have not submitted any claims yet.</div>
@else
    <section style="display: grid; gap: 12px; margin-bottom: 14px;">
        @foreach ($claims as $claim)
            <article class="card card-hover">
                <div style="display: flex; justify-content: space-between; gap: 10px; flex-wrap: wrap; margin-bottom: 8px; align-items: center;">
                    <h3 style="font-size: 20px;">{{ $claim->report?->title ?? 'Item' }}</h3>
                    <span class="badge badge-neutral" style="text-transform: capitalize;">{{ $claim->status }}</span>
                </div>
                <p class="section-note" style="margin-bottom: 8px;">{{ $claim->report?->location }} | {{ $claim->report?->date?->format('M d, Y') }}</p>
                <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 12px;">{{ $claim->message }}</p>
                @if ($claim->report)
                    <a class="btn btn-outline" href="{{ route('items.show', $claim->report) }}">View Item</a>
                @endif

                @if ($claim->status === 'approved')
                    <div style="margin-top: 12px; padding: 12px; border-radius: 12px; background: var(--bg-soft); border: 1px solid var(--line);">
                        <p style="margin: 0 0 8px; font-size: 13px; color: var(--text-muted);">Chat is available because this claim has been approved by admin.</p>
                        <a class="btn btn-primary" href="{{ route('chat.show', $claim) }}">Start Chat</a>
                    </div>
                @elseif ($claim->status === 'pending')
                    <p style="margin-top: 12px; font-size: 13px; color: var(--text-muted);">Chat is locked until admin reviews and approves your claim.</p>
                @elseif ($claim->status === 'rejected')
                    <p style="margin-top: 12px; font-size: 13px; color: var(--text-muted);">Chat is not available because this claim was rejected.</p>
                @endif
            </article>
        @endforeach
    </section>

    <div style="margin-top: 10px;">
        {{ $claims->links() }}
    </div>
@endif
@endsection
