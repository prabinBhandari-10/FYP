@extends('layouts.app')

@section('title', 'About Us | Lost & Found')

@section('content')
@php
    // Fetch active about content from admin
    $managedContent = \App\Models\AboutContent::active()->orderBy('sort_order')->get();
    
    // If no managed content, use defaults
    if ($managedContent->isEmpty()) {
        $managedContent = collect([
            (object)[
                'title' => 'hero',
                'body' => 'The Lost & Found Management System is a web-based platform where students, staff, and visitors can report, search, and recover lost items in a simple and organized way. It is currently focused on college use, and it also supports nearby areas in Pokhara.',
                'sort_order' => 0,
            ],
            (object)[
                'title' => 'Our Mission',
                'body' => 'Our mission is to reduce stress when someone loses an item. We want to save time and make item recovery faster, easier, and more reliable.',
                'sort_order' => 1,
            ],
            (object)[
                'title' => 'What We Provide',
                'body' => "• Easy reporting system for lost and found items\n• Search and filter system to find items quickly\n• Secure claim verification before handover\n• Admin monitoring for safe and proper use",
                'sort_order' => 2,
            ],
            (object)[
                'title' => 'For Our College Community',
                'body' => 'This system is built as our Final Year Project to solve real problems inside the college. It is college-oriented, and it also helps users from nearby Pokhara areas.',
                'sort_order' => 3,
            ],
            (object)[
                'title' => 'Future Scope',
                'body' => "1. Beyond College\nIn the future, this system can expand beyond college use and support more public users.\n\n2. Across Pokhara\nIt can be scaled to serve different schools, offices, and communities across Pokhara.\n\n3. Long-Term Goal\nOur long-term goal is to make this platform useful all over Nepal.",
                'sort_order' => 4,
            ],
        ]);
    }
@endphp

<!-- Hero Section -->
@php
    $heroContent = $managedContent->firstWhere('title', 'hero');
    if (!$heroContent) {
        $heroContent = $managedContent->first();
    }
@endphp
<section class="card" style="margin-bottom: 20px; background: linear-gradient(135deg, #ffffff 0%, #f5f9ff 100%);">
    <h1 style="font-size: 32px; margin: 0 0 12px; color: var(--text-main); font-weight: 800;">About Us</h1>
    <p style="margin: 0; font-size: 15px; line-height: 1.8; color: var(--text-muted); max-width: 840px; white-space: pre-wrap;">{{ $heroContent?->body }}</p>
</section>

<!-- Three Column Section -->
<section class="grid-3" style="margin-bottom: 20px;">
    @php
        $missionContent = $managedContent->firstWhere('title', 'Our Mission');
        $provideContent = $managedContent->firstWhere('title', 'What We Provide');
        $communityContent = $managedContent->firstWhere('title', 'For Our College Community');
    @endphp

    @if ($missionContent)
        <article class="card">
            <h2 style="font-size: 18px; margin: 0 0 12px; color: var(--text-main); font-weight: 700;">{{ $missionContent->title }}</h2>
            <p style="margin: 0; font-size: 14px; line-height: 1.6; color: var(--text-muted); white-space: pre-wrap;">{{ $missionContent->body }}</p>
        </article>
    @endif

    @if ($provideContent)
        <article class="card">
            <h2 style="font-size: 18px; margin: 0 0 12px; color: var(--text-main); font-weight: 700;">{{ $provideContent->title }}</h2>
            <ul style="margin: 0; padding-left: 20px; font-size: 14px; line-height: 1.8; color: var(--text-muted);">
                @php
                    $items = array_filter(array_map('trim', explode("\n", $provideContent->body)));
                @endphp
                @foreach ($items as $item)
                    @if (!empty($item))
                        <li>{{ str_replace('• ', '', $item) }}</li>
                    @endif
                @endforeach
            </ul>
        </article>
    @endif

    @if ($communityContent)
        <article class="card">
            <h2 style="font-size: 18px; margin: 0 0 12px; color: var(--text-main); font-weight: 700;">{{ $communityContent->title }}</h2>
            <p style="margin: 0; font-size: 14px; line-height: 1.6; color: var(--text-muted); white-space: pre-wrap;">{{ $communityContent->body }}</p>
        </article>
    @endif
</section>

<!-- Future Scope Section -->
@php
    $futureContent = $managedContent->firstWhere('title', 'Future Scope');
@endphp
@if ($futureContent)
    <section class="grid-3" style="margin-bottom: 20px;">
        @php
            $futureItems = [];
            $currentItem = '';
            $itemTitle = '';
            
            foreach (explode("\n\n", $futureContent->body) as $paragraph) {
                $paragraph = trim($paragraph);
                if (!empty($paragraph)) {
                    $futureItems[] = $paragraph;
                }
            }
            
            if (empty($futureItems)) {
                $futureItems = [
                    "Beyond College\nIn the future, this system can expand beyond college use and support more public users.",
                    "Across Pokhara\nIt can be scaled to serve different schools, offices, and communities across Pokhara.",
                    "Long-Term Goal\nOur long-term goal is to make this platform useful all over Nepal."
                ];
            }
        @endphp
        
        @foreach ($futureItems as $index => $item)
            @php
                $lines = array_filter(array_map('trim', explode("\n", $item)));
                $itemTitle = count($lines) > 0 ? preg_replace('/^\d+\.\s*/', '', array_shift($lines)) : '';
                $itemBody = implode("\n", $lines);
            @endphp
            @if (!empty($itemTitle))
                <article class="card">
                    <h3 style="font-size: 16px; margin: 0 0 10px; color: var(--text-main); font-weight: 700;">{{ $index + 1 }}. {{ $itemTitle }}</h3>
                    <p style="margin: 0; font-size: 14px; line-height: 1.6; color: var(--text-muted); white-space: pre-wrap;">{{ $itemBody }}</p>
                </article>
            @endif
        @endforeach
    </section>
@endif

<!-- Admin Info Section -->
<section class="card card-soft">
    <h2 style="font-size: 18px; margin: 0 0 10px; color: var(--text-main); font-weight: 700;">📋 About Page Management</h2>
    <p style="margin: 0; font-size: 14px; line-height: 1.6; color: var(--text-muted);">
        This content is fully managed by administrators from the admin panel. You can create, update, publish, hide, or remove sections without editing code.
    </p>
    @if (auth('admin')->check())
        <a href="{{ route('admin.about-contents.index') }}" class="btn btn-primary" style="margin-top: 12px; display: inline-block;">Manage About Content</a>
    @endif
</section>
@endsection
