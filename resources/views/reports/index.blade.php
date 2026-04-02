@extends('layouts.app')

@section('title', 'Browse Items | Lost & Found Auburn')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">

    <div style="margin-bottom: 32px;">
        <h1 class="page-title" style="margin-bottom: 8px;">Browse Reported Items</h1>
        <p style="color: var(--text-gray); font-size: 15.5px;">Search and filter lost or found reports, then explore each item.</p>
    </div>

    <div class="card" style="padding: 24px; margin-bottom: 32px; border-radius: 16px;">
        <form method="GET" action="{{ route('items.index') }}" style="display: flex; gap: 16px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 250px;">
                <input 
                    class="form-input" 
                    type="text" 
                    name="q" 
                    placeholder="Search by title..." 
                    value="{{ request('q') }}"
                    style="border-radius: 999px; padding: 12px 20px;">
            </div>

            <div style="flex: 0 1 200px;">
                <select class="form-select" name="category" style="border-radius: 999px; padding: 12px 20px;">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            <div style="flex: 0 1 160px;">
                <select class="form-select" name="type" style="border-radius: 999px; padding: 12px 20px;">
                    <option value="">Lost & Found</option>
                    <option value="lost" @selected(request('type') === 'lost')>Lost</option>
                    <option value="found" @selected(request('type') === 'found')>Found</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="padding: 12px 28px;">Search</button>
        </form>
        
        @guest
            <div style="background-color: var(--bg-color); border: 1px solid var(--border-color); border-radius: 8px; padding: 14px; margin-top: 24px; text-align: center; color: var(--text-gray); font-size: 13.5px;">
                Browsing is public. Log in to report items or submit a claim.
            </div>
        @endguest
    </div>

    @if ($reports->count() === 0)
        <div style="text-align: center; padding: 64px 24px; color: var(--text-light);">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 16px;">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <h3 style="font-size: 18px; font-weight: 600; color: var(--text-dark); margin-bottom: 8px;">No items found</h3>
            <p>We couldn't find anything matching your search criteria.</p>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px;">
            @foreach ($reports as $report)
                <a href="{{ route('items.show', $report) }}" class="card" style="display: flex; flex-direction: column; padding: 0; overflow: hidden; text-decoration: none; transition: transform 0.2s, box-shadow 0.2s; margin-bottom: 0;">
                    
                    @if ($report->image)
                        <img src="{{ asset('storage/' . $report->image) }}" alt="{{ $report->title }}" style="width: 100%; height: 200px; object-fit: cover;">
                    @else
                        <div style="width: 100%; height: 200px; background-color: var(--bg-color); display: grid; place-items: center; border-bottom: 1px solid var(--border-color); color: var(--text-light);">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                        </div>
                    @endif

                    <div style="padding: 24px; display: flex; flex-direction: column; gap: 12px;">
                        <div>
                            @if($report->type === 'lost')
                                <span class="badge badge-lost">Lost</span>
                            @else
                                <span class="badge badge-found">Found</span>
                            @endif
                        </div>
                        
                        <h3 style="font-size: 18px; font-weight: 700; color: var(--text-dark); margin: 0; line-height: 1.3;">{{ $report->title }}</h3>
                        
                        <div style="display: flex; align-items: center; gap: 6px; color: var(--text-gray); font-size: 14px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            {{ $report->location }}
                        </div>
                        
                        <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid var(--border-color); padding-top: 16px; margin-top: 8px;">
                            <span style="font-size: 13px; font-weight: 600; color: var(--text-gray);">{{ $report->category }}</span>
                            <span style="font-size: 13px; color: var(--text-light);">{{ $report->date?->format('M d, Y') }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div style="margin-top: 40px;">
            {{ $reports->links() }}
        </div>
    @endif
</div>

<style>
    /* Add hover state to cards via a class */
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02) !important;
    }
</style>
@endsection
