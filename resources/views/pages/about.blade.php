@extends('layouts.app')

@section('title', 'About Us | Lost & Found')

@section('content')
<section class="card" style="margin-bottom: 20px; background: linear-gradient(135deg, #ffffff 0%, #f5f9ff 100%);">
    <h1 class="page-title" style="margin-bottom: 8px;">About Us</h1>
    <p class="page-subtitle" style="max-width: 840px;">
        Lost &amp; Found Management System is a college-focused platform designed to help students, staff, and visitors quickly report and recover missing items in a safe and organized way.
    </p>
</section>

<section class="grid-3" style="margin-bottom: 20px;">
    <article class="card card-hover">
        <h2 style="font-size: 22px; margin-bottom: 10px;">Our Mission</h2>
        <p class="section-note" style="line-height: 1.7;">
            Reduce stress and confusion when items are lost by providing a structured process for reporting, searching, and claiming.
        </p>
    </article>

    <article class="card card-hover">
        <h2 style="font-size: 22px; margin-bottom: 10px;">What We Provide</h2>
        <ul style="padding-left: 18px; color: var(--text-muted); line-height: 1.7; font-size: 14px; display: grid; gap: 4px;">
            <li>Simple lost and found report submission</li>
            <li>Powerful search and filters for quick discovery</li>
            <li>Secure claim verification workflow</li>
            <li>Admin moderation for platform trust and safety</li>
        </ul>
    </article>

    <article class="card card-hover">
        <h2 style="font-size: 22px; margin-bottom: 10px;">For Our College Community</h2>
        <p class="section-note" style="line-height: 1.7;">
            This project was built as a final year project to solve a real campus problem with a practical, user-friendly digital system.
        </p>
    </article>
</section>

<section class="card card-soft">
    <h2 style="font-size: 24px; margin-bottom: 10px;">How It Works</h2>
    <div class="grid-3">
        <div>
            <h3 style="font-size: 18px; margin-bottom: 6px;">1. Report</h3>
            <p class="section-note">Users submit lost or found item details with location and date.</p>
        </div>
        <div>
            <h3 style="font-size: 18px; margin-bottom: 6px;">2. Match</h3>
            <p class="section-note">Others search listings and find potential matches using filters.</p>
        </div>
        <div>
            <h3 style="font-size: 18px; margin-bottom: 6px;">3. Claim</h3>
            <p class="section-note">Ownership is verified through the claim process before handover.</p>
        </div>
    </div>
</section>
@endsection
