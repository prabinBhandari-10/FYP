@extends('layouts.app')

@section('title', $report->title . ' | Lost and Found')

@section('content')
    <style>
        :root {
            --bg-dark: #080d1a;
            --bg-card: rgba(10, 16, 30, 0.72);
            --barca-blue: #1f3a8a;
            --barca-maroon: #7a1026;
            --barca-gold: #d1a74a;
            --text-main: #e7eaf4;
            --text-soft: #a5aec3;
            --input-bg: rgba(7, 12, 24, 0.7);
        }

        .lf-page {
            min-height: calc(100vh - 120px);
            padding: 36px 12px 64px;
            background:
                radial-gradient(1100px 520px at 85% -15%, rgba(31, 58, 138, 0.25), transparent 60%),
                radial-gradient(900px 420px at -10% 25%, rgba(122, 16, 38, 0.22), transparent 60%),
                var(--bg-dark);
            color: var(--text-main);
        }

        .lf-container {
            width: min(1120px, 100%);
            margin: 0 auto;
            display: grid;
            gap: 20px;
        }

        .lf-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 20px;
        }

        .lf-card {
            background: var(--bg-card);
            border: 1px solid rgba(31, 58, 138, 0.25);
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.06);
        }

        .lf-hero h2 {
            margin: 0 0 8px;
            font-size: clamp(26px, 2.4vw, 36px);
            letter-spacing: 0.3px;
            font-weight: 700;
        }

        .lf-hero p {
            margin: 0;
            color: var(--text-soft);
            font-size: 15px;
        }

        .lf-image {
            width: 100%;
            height: 320px;
            object-fit: cover;
            border-radius: 16px;
            border: 1px solid rgba(31, 58, 138, 0.25);
        }

        .lf-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(209, 167, 74, 0.12);
            border: 1px solid rgba(209, 167, 74, 0.28);
            color: var(--barca-gold);
        }

        .lf-meta {
            color: var(--text-soft);
            font-size: 13px;
        }

        .lf-section {
            display: grid;
            gap: 10px;
        }

        .lf-title {
            font-size: 20px;
            margin: 0;
        }

        .lf-description {
            color: var(--text-soft);
            line-height: 1.6;
            margin: 0;
        }

        .lf-info-list {
            display: grid;
            gap: 10px;
        }

        .lf-info-row {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 10px;
            font-size: 14px;
            color: var(--text-main);
        }

        .lf-info-row span {
            color: var(--text-soft);
        }

        .lf-actions {
            display: flex;
            gap: 12px;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .lf-btn {
            padding: 12px 18px;
            border-radius: 12px;
            border: 1px solid rgba(31, 58, 138, 0.35);
            cursor: pointer;
            color: var(--text-main);
            background: linear-gradient(135deg, rgba(31, 58, 138, 0.28), rgba(122, 16, 38, 0.28));
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .lf-btn-primary {
            border: none;
            font-weight: 600;
            background: linear-gradient(135deg, var(--barca-blue), var(--barca-maroon));
            box-shadow: 0 12px 30px rgba(31, 58, 138, 0.35);
        }

        .lf-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 36px rgba(31, 58, 138, 0.45);
        }

        .lf-input,
        .lf-textarea {
            width: 100%;
            padding: 12px 14px;
            background: var(--input-bg);
            border: 1px solid rgba(31, 58, 138, 0.28);
            border-radius: 12px;
            color: var(--text-main);
            outline: none;
            transition: all 0.2s ease;
        }

        .lf-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .lf-input::placeholder,
        .lf-textarea::placeholder {
            color: rgba(165, 174, 195, 0.8);
        }

        .lf-input:focus,
        .lf-textarea:focus {
            border-color: rgba(31, 58, 138, 0.8);
            box-shadow: 0 0 0 4px rgba(31, 58, 138, 0.25), 0 0 18px rgba(122, 16, 38, 0.25);
        }

        .lf-alert {
            background: rgba(122, 16, 38, 0.12);
            border: 1px solid rgba(122, 16, 38, 0.35);
            color: var(--text-main);
            padding: 10px 12px;
            border-radius: 12px;
            font-size: 13px;
        }

        @media (max-width: 980px) {
            .lf-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <section class="lf-page">
        <div class="lf-container">
            <div class="lf-hero">
                <h2>{{ $report->title }}</h2>
                <p>Reported {{ $report->type }} item details and status.</p>
            </div>

            <div class="lf-grid">
                <div class="lf-card">
                    @if ($report->image)
                        <img class="lf-image" src="{{ asset('storage/' . $report->image) }}" alt="{{ $report->title }}">
                    @else
                        <img class="lf-image" src="https://via.placeholder.com/900x520?text=No+Image" alt="No image">
                    @endif

                    <div class="lf-section" style="margin-top: 16px;">
                        <span class="lf-badge">{{ ucfirst($report->type) }}</span>
                        <h3 class="lf-title">Description</h3>
                        <p class="lf-description">{{ $report->description }}</p>
                    </div>
                </div>

                <div class="lf-card">
                    <div class="lf-section">
                        <h3 class="lf-title">Item Details</h3>
                        <div class="lf-info-list">
                            <div class="lf-info-row"><span>Location</span>{{ $report->location }}</div>
                            <div class="lf-info-row"><span>Date</span>{{ $report->date?->format('M d, Y') }}</div>
                            <div class="lf-info-row"><span>Status</span>{{ ucfirst($report->status) }}</div>
                            <div class="lf-info-row"><span>Category</span>{{ $report->category }}</div>
                        </div>
                    </div>

                    <div class="lf-section" style="margin-top: 18px;">
                        <h3 class="lf-title">Reporter</h3>
                        <div class="lf-info-list">
                            <div class="lf-info-row"><span>Name</span>{{ $report->user?->name ?? 'Unknown' }}</div>
                            <div class="lf-info-row"><span>Email</span>{{ $report->user?->email ?? 'Not available' }}</div>
                        </div>
                    </div>

                    <div class="lf-section" style="margin-top: 18px;">
                        <h3 class="lf-title">Claim Request</h3>

                        @auth
                            @if ($errors->has('claim'))
                                <div class="lf-alert">{{ $errors->first('claim') }}</div>
                            @endif

                            @if ($existingClaim)
                                <div class="lf-alert">
                                    Claim already submitted. Status: {{ ucfirst($existingClaim->status) }}
                                </div>
                            @else
                                <form method="POST" action="{{ route('claims.store', $report) }}">
                                    @csrf
                                    <div class="lf-section" style="margin-top: 10px;">
                                        <label class="lf-meta" for="message">Message</label>
                                        <textarea class="lf-textarea" id="message" name="message" placeholder="Explain why this item is yours...">{{ old('message') }}</textarea>
                                        @error('message')
                                            <div class="lf-meta" style="color: var(--barca-gold);">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="lf-section" style="margin-top: 10px;">
                                        <label class="lf-meta" for="proof_text">Proof (optional)</label>
                                        <textarea class="lf-textarea" id="proof_text" name="proof_text" placeholder="Add any proof details (serial number, receipts, etc.)">{{ old('proof_text') }}</textarea>
                                        @error('proof_text')
                                            <div class="lf-meta" style="color: var(--barca-gold);">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="lf-actions">
                                        <button type="submit" class="lf-btn lf-btn-primary">Submit Claim</button>
                                        <a class="lf-btn" href="{{ route('items.index') }}">Back to Browse</a>
                                    </div>
                                </form>
                            @endif
                        @else
                            <div class="lf-alert">
                                Please sign in to submit a claim for this item.
                            </div>
                            <div class="lf-actions">
                                <a class="lf-btn lf-btn-primary" href="{{ route('login') }}">Login to Claim</a>
                                <a class="lf-btn" href="{{ route('items.index') }}">Back to Browse</a>
                            </div>
                        @endauth
                    </div>

                    @if (auth()->check() && $existingClaim)
                        <div class="lf-actions">
                            <a class="lf-btn" href="{{ route('items.index') }}">Back to Browse</a>
                            <a class="lf-btn" href="{{ route('claims.index') }}">View My Claims</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
