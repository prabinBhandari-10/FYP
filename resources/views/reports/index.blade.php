@extends('layouts.app')

@section('title', 'Browse Items | Lost and Found')

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

        .lf-hero h2 {
            margin: 0 0 8px;
            font-size: clamp(26px, 2.4vw, 36px);
            letter-spacing: 0.3px;
            font-weight: 700;
        }

        .lf-hero p {
            margin: 0 0 16px;
            color: var(--text-soft);
            font-size: 15px;
        }

        .lf-filters {
            background: var(--bg-card);
            border: 1px solid rgba(31, 58, 138, 0.25);
            border-radius: 16px;
            padding: 16px;
            display: grid;
            gap: 12px;
        }

        .lf-filter-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.9fr 0.9fr auto;
            gap: 12px;
        }

        .lf-input,
        .lf-select {
            width: 100%;
            padding: 12px 14px;
            background: var(--input-bg);
            border: 1px solid rgba(31, 58, 138, 0.28);
            border-radius: 12px;
            color: var(--text-main);
            outline: none;
            transition: all 0.2s ease;
        }

        .lf-input::placeholder {
            color: rgba(165, 174, 195, 0.8);
        }

        .lf-input:focus,
        .lf-select:focus {
            border-color: rgba(31, 58, 138, 0.8);
            box-shadow: 0 0 0 4px rgba(31, 58, 138, 0.25), 0 0 18px rgba(122, 16, 38, 0.25);
        }

        .lf-btn {
            padding: 12px 18px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            color: var(--text-main);
            background: linear-gradient(135deg, var(--barca-blue), var(--barca-maroon));
            font-weight: 600;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .lf-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 36px rgba(31, 58, 138, 0.35);
        }

        .lf-cards {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .lf-card {
            background: var(--bg-card);
            border: 1px solid rgba(31, 58, 138, 0.25);
            border-radius: 16px;
            overflow: hidden;
            display: grid;
            grid-template-rows: 160px auto;
        }

        .lf-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .lf-card-body {
            padding: 16px;
            display: grid;
            gap: 8px;
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
            width: fit-content;
        }

        .lf-title {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
        }

        .lf-meta {
            font-size: 13px;
            color: var(--text-soft);
        }

        .lf-empty {
            background: var(--bg-card);
            border: 1px solid rgba(31, 58, 138, 0.25);
            border-radius: 16px;
            padding: 24px;
            color: var(--text-soft);
            text-align: center;
        }

        .lf-pagination {
            margin-top: 8px;
        }

        @media (max-width: 980px) {
            .lf-filter-grid {
                grid-template-columns: 1fr;
            }

            .lf-cards {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .lf-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <section class="lf-page">
        <div class="lf-container">
            <div class="lf-hero">
                <h2>Browse Reported Items</h2>
                <p>Search and filter lost or found reports, then explore each item in a clean card layout.</p>
            </div>

            <form class="lf-filters" method="GET" action="{{ route('items.index') }}">
                <div class="lf-filter-grid">
                    <input class="lf-input" type="text" name="q" placeholder="Search by title..." value="{{ request('q') }}">

                    <select class="lf-select" name="category">
                        <option value="">All categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                        @endforeach
                    </select>

                    <select class="lf-select" name="type">
                        <option value="">Lost & Found</option>
                        <option value="lost" @selected(request('type') === 'lost')>Lost</option>
                        <option value="found" @selected(request('type') === 'found')>Found</option>
                    </select>

                    <button class="lf-btn" type="submit">Search</button>
                </div>
            </form>

            @if ($reports->count() === 0)
                <div class="lf-empty">No items match your search yet.</div>
            @else
                <div class="lf-cards">
                    @foreach ($reports as $report)
                        <div class="lf-card">
                            @if ($report->image)
                                <img src="{{ asset('storage/' . $report->image) }}" alt="{{ $report->title }}">
                            @else
                                <img src="https://via.placeholder.com/640x360?text=No+Image" alt="No image">
                            @endif

                            <div class="lf-card-body">
                                <span class="lf-badge">{{ ucfirst($report->type) }}</span>
                                <h3 class="lf-title">{{ $report->title }}</h3>
                                <div class="lf-meta">{{ $report->category }} · {{ $report->location }}</div>
                                <div class="lf-meta">{{ $report->date?->format('M d, Y') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="lf-pagination">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
