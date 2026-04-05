@extends('layouts.app')

@section('title', 'Browse Items | Lost & Found')

@section('content')
<style>
    @media (max-width: 980px) {
        .browse-filters {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<section class="card" style="margin-bottom: 20px;">
    <h1 style="font-size: 32px; margin-bottom: 8px;">Browse Reported Items</h1>
    <p class="section-note" style="margin-bottom: 16px;">Use search and filters to quickly find lost and found records.</p>

    <form method="GET" action="{{ route('items.index') }}" class="browse-filters" style="display: grid; grid-template-columns: minmax(0, 1.2fr) minmax(160px, 220px) minmax(140px, 180px) auto; gap: 10px; align-items: center;">
        <input class="form-input" type="text" name="q" value="{{ request('q') }}" placeholder="Search title, location, or category">

        <select class="form-select" name="category">
            <option value="">All categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
            @endforeach
        </select>

        <select class="form-select" name="type">
            <option value="">Lost and Found</option>
            <option value="lost" @selected(request('type') === 'lost')>Lost</option>
            <option value="found" @selected(request('type') === 'found')>Found</option>
        </select>

        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</section>

@if ($reports->count() === 0)
    <div class="empty-state">No items matched your filters.</div>
@else
    <section class="grid-3" style="margin-bottom: 12px;">
        @foreach ($reports as $report)
            <article class="card card-hover" style="padding: 0; overflow: hidden; position: relative;">
                <a href="{{ route('items.show', $report) }}" style="text-decoration: none; color: inherit; display: block;">
                    @if ($report->image)
                        <img src="{{ asset('storage/' . $report->image) }}" alt="{{ $report->title }}" style="width: 100%; height: 190px; object-fit: cover;">
                    @else
                        <div style="height: 190px; background: linear-gradient(130deg, #e8efff 0%, #dae7ff 100%);"></div>
                    @endif

                    <div style="padding: 16px; display: grid; gap: 8px;">
                        <span class="badge {{ $report->type === 'lost' ? 'badge-lost' : 'badge-found' }}" style="width: fit-content;">{{ strtoupper($report->type) }}</span>
                        <h3 style="font-size: 19px;">{{ $report->title }}</h3>
                        <p class="section-note">{{ $report->location }}</p>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px; border-top: 1px solid var(--line); padding-top: 8px;">
                            <span style="font-size: 13px; font-weight: 700; color: var(--text-muted);">{{ $report->category }}</span>
                            <span style="font-size: 13px; color: var(--text-soft);">{{ $report->date?->format('M d, Y') }}</span>
                        </div>
                    </div>
                </a>
            </article>
        @endforeach
    </section>

    <div style="margin-top: 12px;">
        {{ $reports->links() }}
    </div>
@endif
@endsection
