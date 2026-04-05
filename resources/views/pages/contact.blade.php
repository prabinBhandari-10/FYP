@extends('layouts.app')

@section('title', 'Contact Us | Lost & Found')

@section('content')
<section class="card" style="margin-bottom: 20px;">
    <h1 class="page-title" style="margin-bottom: 8px;">Contact Us</h1>
    <p class="page-subtitle">Need help with a report or claim? Reach out and we will assist you.</p>
</section>

<section class="split-layout" style="margin-bottom: 20px;">
    <article class="card">
        <h2 style="font-size: 24px; margin-bottom: 14px;">Send a Message</h2>

        <form method="POST" action="{{ route('contact.store') }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="contact_name">Full Name</label>
                    <input class="form-input" type="text" id="contact_name" name="name" placeholder="Your full name" value="{{ old('name') }}" required>
                    @error('name')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="contact_email">Email Address</label>
                    <input class="form-input" type="email" id="contact_email" name="email" placeholder="you@example.com" value="{{ old('email') }}" required>
                    @error('email')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="contact_subject">Subject</label>
                <input class="form-input" type="text" id="contact_subject" name="subject" placeholder="How can we help?" value="{{ old('subject') }}" required>
                @error('subject')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="contact_message">Message</label>
                <textarea class="form-textarea" id="contact_message" name="message" placeholder="Write your message" required>{{ old('message') }}</textarea>
                @error('message')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-primary">Send Message</button>
        </form>
    </article>

    <aside class="sticky-panel" style="display: grid; gap: 14px;">
        <article class="card card-soft">
            <h3 style="font-size: 20px; margin-bottom: 8px;">Support Information</h3>
            <p class="section-note" style="line-height: 1.7; margin-bottom: 10px;">
                You can contact the Lost &amp; Found support team for report updates, claim questions, and account help.
            </p>
            <p style="font-size: 14px; color: var(--text-muted); line-height: 1.8;">
                Email: {{ config('mail.from.address', 'admin@example.com') }}<br>
                Office Hours: Sunday-Friday, 10:00 AM - 4:00 PM<br>
                Location: College Administration Office
            </p>
            <div style="margin-top: 16px;">
                <h4 style="font-size: 14px; font-weight: 700; margin-bottom: 8px;">Follow Us</h4>
                <div class="social-links">
                    <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="social-link" title="Follow us on Facebook">f</a>
                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="social-link" title="Follow us on Instagram">📷</a>
                    <a href="https://wa.me/" target="_blank" rel="noopener noreferrer" class="social-link" title="Contact us on WhatsApp">💬</a>
                </div>
            </div>
        </article>

        <article class="card card-soft">
            <h3 style="font-size: 20px; margin-bottom: 8px;">Tips for Faster Help</h3>
            <ul style="padding-left: 18px; color: var(--text-muted); line-height: 1.7; font-size: 14px; display: grid; gap: 4px;">
                <li>Include your report ID if available</li>
                <li>Share item title and date clearly</li>
                <li>Use the same email linked to your account</li>
            </ul>
        </article>
    </aside>
</section>
@endsection
