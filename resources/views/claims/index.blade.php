@extends('layouts.app')

@section('title', 'My Claims | Lost and Found')

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

        .lf-card {
            background: var(--bg-card);
            border: 1px solid rgba(31, 58, 138, 0.25);
            border-radius: 16px;
            padding: 18px;
            display: grid;
            gap: 12px;
        }

        .lf-card h3 {
            margin: 0;
            font-size: 18px;
        }

        .lf-meta {
            color: var(--text-soft);
            font-size: 13px;
        }

        .lf-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(209, 167, 74, 0.12);
            border: 1px solid rgba(209, 167, 74, 0.28);
            color: var(--barca-gold);
        }

        .lf-list {
            display: grid;
            gap: 12px;
        }

        .lf-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .lf-btn {
            padding: 10px 16px;
            border-radius: 12px;
            border: 1px solid rgba(31, 58, 138, 0.35);
            cursor: pointer;
            color: var(--text-main);
            background: linear-gradient(135deg, rgba(31, 58, 138, 0.28), rgba(122, 16, 38, 0.28));
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .lf-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 36px rgba(31, 58, 138, 0.45);
        }

        .lf-empty {
            background: var(--bg-card);
            border: 1px solid rgba(31, 58, 138, 0.25);
            border-radius: 16px;
            padding: 24px;
            color: var(--text-soft);
            text-align: center;
        }
    </style>

    <section class="lf-page">
        <div class="lf-container">
            <div>
                <h2>My Claims</h2>
                <p class="lf-meta">Track your active and resolved claims in one place.</p>
            </div>

            @if (session('success'))
                <div class="lf-card">
                    <div class="lf-meta">{{ session('success') }}</div>
                </div>
            @endif

            @if ($claims->count() === 0)
                <div class="lf-empty">You have not submitted any claims yet.</div>
            @else
                <div class="lf-list">
                    @foreach ($claims as $claim)
                        <div class="lf-card">
                            <span class="lf-badge">{{ ucfirst($claim->status) }}</span>
                            <h3>{{ $claim->report?->title ?? 'Item' }}</h3>
                            <div class="lf-meta">{{ $claim->report?->location }} · {{ $claim->report?->date?->format('M d, Y') }}</div>
                            <div class="lf-meta">Message: {{ $claim->message }}</div>
                            <div class="lf-actions">
                                @if ($claim->report)
                                    <a class="lf-btn" href="{{ route('items.show', $claim->report) }}">View Item</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="margin-top: 12px;">
                    {{ $claims->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
