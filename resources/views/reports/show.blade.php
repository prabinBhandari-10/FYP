@extends('layouts.app')

@section('title', $report->title . ' | Lost & Found')

@section('content')
@php
    $isAdmin = auth()->check() && auth()->user()->role === 'admin';
    [$locationBlock, $locationPlace] = array_pad(explode(' - ', (string) $report->location, 2), 2, '');
    if ($locationPlace === '') {
        $locationPlace = $locationBlock;
        $locationBlock = 'Not specified';
    }
@endphp

<section style="margin-bottom: 16px;">
    <a href="{{ route('items.index') }}" class="btn btn-ghost" style="padding-left: 0;">Back to Browse</a>
</section>

@if (session('show_matches'))
    <section class="alert alert-success" style="margin-bottom: 14px; border-radius: 16px;">
        Smart matching is active. Review the <strong>Possible Matches</strong> section below for similar {{ $report->type === 'lost' ? 'found' : 'lost' }} items.
    </section>
@endif

<section class="split-layout" style="margin-bottom: 20px;">
    <div style="display: grid; gap: 16px;">
        <article class="card" style="padding: 0; overflow: hidden;">
            @if ($report->image)
                <div style="height: 360px; background: #f4f7ff; display: flex; align-items: center; justify-content: center;">
                    <img src="{{ asset('storage/' . $report->image) }}" alt="{{ $report->title }}" style="width: 100%; height: 100%; object-fit: contain; object-position: center; display: block;">
                </div>
            @else
                <div style="height: 260px; background: linear-gradient(130deg, #e8efff 0%, #dae7ff 100%);"></div>
            @endif

            <div style="padding: 22px;">
                <div style="display: flex; gap: 8px; margin-bottom: 10px; flex-wrap: wrap;">
                    <span class="badge {{ $report->type === 'lost' ? 'badge-lost' : 'badge-found' }}">{{ $report->type === 'lost' ? 'Lost Item' : 'Found Item' }}</span>
                    <span class="badge badge-neutral" style="text-transform: capitalize;">{{ $report->status }}</span>
                </div>
                <h1 style="font-size: 30px; margin-bottom: 8px;">{{ $report->title }}</h1>
                <p class="section-note" style="margin-bottom: 16px;">Reported on {{ $report->created_at->format('F d, Y') }}</p>
                <h2 style="font-size: 20px; margin-bottom: 10px;">Description</h2>
                <p style="font-size: 15px; line-height: 1.7; color: var(--text-muted); white-space: pre-wrap;">{{ $report->description }}</p>
            </div>
        </article>

        @if ($report->type === 'lost' && $report->sightings->count() > 0)
            <article class="card card-soft">
                <h3 style="font-size: 21px; margin-bottom: 12px;">Recent Sightings</h3>
                <div style="display: grid; gap: 10px;">
                    @foreach($report->sightings->latest()->take(5) as $sighting)
                        <div style="padding: 12px; border-radius: 14px; background: #fff; border: 1px solid var(--line);">
                            <p style="font-size: 14px; font-weight: 800; margin-bottom: 4px;">{{ $sighting->user?->name ?? $sighting->reporter_name }}</p>
                            @if($sighting->location)
                                <p class="section-note" style="margin-bottom: 4px;">{{ $sighting->location }}</p>
                            @endif
                            <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 4px;">{{ $sighting->message }}</p>
                            <p class="section-note">{{ $sighting->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                </div>
            </article>
        @endif

        <article class="card card-soft">
            <h3 style="font-size: 21px; margin-bottom: 10px;">Possible Matches</h3>
            <p class="section-note" style="margin-bottom: 12px;">
                Smart suggestions based on category, title similarity, keywords, location, and report date.
            </p>

            @if (($potentialMatches ?? collect())->isEmpty())
                <div class="empty-state" style="padding: 22px;">
                    No close matches found right now. New reports will be checked automatically.
                </div>
            @else
                <div style="display: grid; gap: 10px;">
                    @foreach ($potentialMatches as $match)
                        <a href="{{ route('items.show', $match['report']) }}" class="card card-hover" style="padding: 14px; text-decoration: none; display: block; color: inherit;">
                            <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 8px; flex-wrap: wrap;">
                                <span class="badge {{ $match['report']->type === 'lost' ? 'badge-lost' : 'badge-found' }}">{{ ucfirst($match['report']->type) }}</span>
                                <span class="badge badge-neutral">Match {{ $match['score'] }}%</span>
                            </div>
                            <h4 style="font-size: 18px; margin-bottom: 4px;">{{ $match['report']->title }}</h4>
                            <p class="section-note" style="margin-bottom: 2px;">{{ $match['report']->location }}</p>
                            <p class="section-note">{{ $match['report']->date?->format('M d, Y') }} | {{ $match['report']->category }}</p>
                        </a>
                    @endforeach
                </div>
            @endif
        </article>
    </div>

    <aside style="display: grid; gap: 16px;">
        <article class="card sticky-panel">
            <h3 style="font-size: 22px; margin-bottom: 12px;">Item Details</h3>
            <div style="display: grid; gap: 10px;">
                <div><p class="section-note">Report UID</p><p style="font-weight: 800;">{{ $report->report_uid ?? 'Not assigned' }}</p></div>
                <div><p class="section-note">Block</p><p style="font-weight: 700;">{{ $locationBlock }}</p></div>
                <div><p class="section-note">Place</p><p style="font-weight: 700;">{{ $locationPlace }}</p></div>
                <div><p class="section-note">Date</p><p style="font-weight: 700;">{{ $report->date?->format('F d, Y') }}</p></div>
                <div><p class="section-note">Category</p><p style="font-weight: 700;">{{ $report->category }}</p></div>
                <div><p class="section-note">Reporter</p><p style="font-weight: 700;">{{ $report->is_anonymous && ! $isAdmin ? 'Anonymous' : ($report->reporter_name ?? ($report->user?->name ?? 'Unknown')) }}</p></div>
                @if ($isAdmin || ! $report->is_anonymous)
                    <div><p class="section-note">Phone</p><p style="font-weight: 700;">{{ $report->reporter_phone ?? 'Not provided' }}</p></div>
                    <div><p class="section-note">Email</p><p style="font-weight: 700;">{{ $report->reporter_email ?? 'Not provided' }}</p></div>
                @else
                    <div><p class="section-note">Contact</p><p style="font-weight: 700;">Hidden for anonymous reports</p></div>
                @endif
                @if ($report->report_uid)
                    <div>
                        <a href="{{ route('reports.track.show', $report->report_uid) }}" class="btn btn-outline" style="padding: 8px 12px; font-size: 12px;">Track Using UID</a>
                    </div>
                @endif
            </div>

            <button type="button" onclick="openShareModal()" class="btn btn-outline" style="width: 100%; margin-top: 14px;">Share Listing</button>
        </article>

        @if($report->type === 'found')
            <article class="card">
                <h3 style="font-size: 21px; margin-bottom: 10px;">Claim Request</h3>
                @auth
                    @if ($errors->has('claim'))
                        <div class="alert alert-error">{{ $errors->first('claim') }}</div>
                    @endif

                    @if ($existingClaim)
                        <div class="card card-soft" style="padding: 16px;">
                            <p style="font-weight: 800; margin-bottom: 6px;">Claim Submitted</p>
                            <p class="section-note" style="margin-bottom: 10px;">Status: {{ ucfirst($existingClaim->status) }}</p>
                            <a href="{{ route('claims.index') }}" class="btn btn-outline" style="width: 100%;">View My Claims</a>
                        </div>
                    @else
                        <form method="POST" action="{{ route('claims.store', $report) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label class="form-label" for="message">Why is this item yours?</label>
                                <textarea class="form-textarea" id="message" name="message" placeholder="Include details such as brand, marks, color, or serial number" required>{{ old('message') }}</textarea>
                                @error('message')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="citizenship_document">Citizenship or ID Document</label>
                                <input class="form-input" type="file" id="citizenship_document" name="citizenship_document" accept=".jpg,.jpeg,.png,.pdf" required>
                                @error('citizenship_document')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="proof_text">Proof Description</label>
                                <textarea class="form-textarea" id="proof_text" name="proof_text" placeholder="Any additional ownership proof">{{ old('proof_text') }}</textarea>
                                @error('proof_text')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="proof_photo">Proof Photo (optional)</label>
                                <input class="form-input" type="file" id="proof_photo" name="proof_photo" accept=".jpg,.jpeg,.png">
                                @error('proof_photo')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
                            </div>

                            <div class="alert alert-error" style="margin-bottom: 10px;">Fake claims may result in account action.</div>
                            <button type="submit" class="btn btn-primary" style="width: 100%;">Submit Claim</button>
                        </form>
                    @endif
                @else
                    <p class="section-note" style="margin-bottom: 10px;">Please log in to submit a claim.</p>
                    <a href="{{ route('login') }}" class="btn btn-primary" style="width: 100%;">Login to Claim</a>
                @endauth
            </article>
        @endif

        @if($report->type === 'lost')
            <article class="card">
                <h3 style="font-size: 21px; margin-bottom: 10px;">Report Sighting</h3>
                <form method="POST" action="{{ route('sightings.store', $report) }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="sighting_message">Message</label>
                        <textarea class="form-textarea" id="sighting_message" name="message" placeholder="Tell the owner where and when you saw the item" required>{{ old('message') }}</textarea>
                        @error('message')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="sighting_location">Location (optional)</label>
                        <input class="form-input" type="text" id="sighting_location" name="location" value="{{ old('location') }}" placeholder="Where did you see it?">
                        @error('location')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
                    </div>

                    @guest
                        <div class="form-group">
                            <label class="form-label" for="sighting_name">Your Name</label>
                            <input class="form-input" type="text" id="sighting_name" name="reporter_name" value="{{ old('reporter_name') }}" required>
                            @error('reporter_name')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="sighting_email">Your Email</label>
                            <input class="form-input" type="email" id="sighting_email" name="reporter_email" value="{{ old('reporter_email') }}" required>
                            @error('reporter_email')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
                        </div>
                    @endguest

                    <button type="submit" class="btn btn-primary" style="width: 100%;">Send Sighting Report</button>
                </form>
            </article>
        @endif
    </aside>
</section>

@include('partials.share-report')
@endsection
