@extends('layouts.app')

@section('title', 'Admin Payments | Lost & Found')

@section('content')
<section class="card" style="margin-bottom: 20px;">
    <div style="display: flex; justify-content: space-between; gap: 12px; align-items: center; flex-wrap: wrap;">
        <div>
            <h1 class="page-title" style="margin-bottom: 8px;">Payments</h1>
            <p class="page-subtitle">A simple placeholder for payment tracking and future payment module integration.</p>
        </div>
        <a href="{{ route('admin.home') }}" class="btn btn-outline">Back to Home</a>
    </div>
</section>

<section class="grid-2" style="margin-bottom: 20px;">
    <article class="card card-soft">
        <h2 style="font-size: 22px; margin-bottom: 10px;">Payment Status</h2>
        <p style="font-size: 32px; font-weight: 800; margin: 0 0 6px; color: var(--primary);">{{ $pendingPayments }}</p>
        <p class="section-note" style="margin: 0;">Pending payment items</p>
    </article>

    <article class="card card-soft">
        <h2 style="font-size: 22px; margin-bottom: 10px;">Module Status</h2>
        <p class="section-note" style="line-height: 1.7; margin: 0;">
            Payments are not connected to a live gateway in this project yet. This page is ready for future extension without changing the rest of the admin layout.
        </p>
    </article>
</section>

<section class="card">
    <h2 style="font-size: 22px; margin-bottom: 10px;">Recent Activity Notes</h2>
    @if ($recentNotes->count())
        <div style="display: grid; gap: 10px;">
            @foreach ($recentNotes as $note)
                <div style="padding: 12px 14px; border-radius: 12px; border: 1px solid var(--line); background: #fff;">
                    <strong>{{ $note->title }}</strong>
                    <p class="section-note" style="margin: 4px 0 0;">{{ $note->message }}</p>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">No payment notes available yet.</div>
    @endif
</section>
@endsection
