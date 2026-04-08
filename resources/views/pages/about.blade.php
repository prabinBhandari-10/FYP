@extends('layouts.app')

@section('title', 'About Us | Lost & Found')

@section('content')
<section class="card" style="margin-bottom: 20px; background: linear-gradient(135deg, #ffffff 0%, #f5f9ff 100%);">
    <h1 class="page-title" style="margin-bottom: 8px;">About Us</h1>
    <p class="page-subtitle" style="max-width: 840px;">
        The Lost &amp; Found Management System is a web-based platform where students, staff, and visitors can report, search, and recover lost items in a simple and organized way.
        It is currently focused on college use, and it also supports nearby areas in Pokhara.
    </p>
</section>

<section class="grid-3" style="margin-bottom: 20px;">
    <article class="card card-hover">
        <h2 style="font-size: 22px; margin-bottom: 10px;">Our Mission</h2>
        <p class="section-note" style="line-height: 1.7;">
            Our mission is to reduce stress when someone loses an item.
            We want to save time and make item recovery faster, easier, and more reliable.
        </p>
    </article>

    <article class="card card-hover">
        <h2 style="font-size: 22px; margin-bottom: 10px;">What We Provide</h2>
        <ul style="padding-left: 18px; color: var(--text-muted); line-height: 1.7; font-size: 14px; display: grid; gap: 4px;">
            <li>Easy reporting system for lost and found items</li>
            <li>Search and filter system to find items quickly</li>
            <li>Secure claim verification before handover</li>
            <li>Admin monitoring for safe and proper use</li>
        </ul>
    </article>

    <article class="card card-hover">
        <h2 style="font-size: 22px; margin-bottom: 10px;">For Our College Community</h2>
        <p class="section-note" style="line-height: 1.7;">
            This system is built as our Final Year Project to solve real problems inside the college.
            It is college-oriented, and it also helps users from nearby Pokhara areas.
        </p>
    </article>
</section>

<section class="card card-soft">
    <h2 style="font-size: 24px; margin-bottom: 10px;">Future Scope</h2>
    <div class="grid-3">
        <div>
            <h3 style="font-size: 18px; margin-bottom: 6px;">1. Beyond College</h3>
            <p class="section-note">In the future, this system can expand beyond college use and support more public users.</p>
        </div>
        <div>
            <h3 style="font-size: 18px; margin-bottom: 6px;">2. Across Pokhara</h3>
            <p class="section-note">It can be scaled to serve different schools, offices, and communities across Pokhara.</p>
        </div>
        <div>
            <h3 style="font-size: 18px; margin-bottom: 6px;">3. Long-Term Goal</h3>
            <p class="section-note">Our long-term goal is to make this platform useful all over Nepal.</p>
        </div>
    </div>
    <p class="section-note" style="margin-top: 12px; line-height: 1.7;">
        This project shows how simple technology can solve real-life problems in our community.
    </p>
</section>
@endsection
