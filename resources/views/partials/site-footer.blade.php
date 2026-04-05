<section class="site-footer-quick" aria-label="Footer quick actions">
    <div class="site-footer-inner">
        <div class="site-footer-brand">
            <h3>Lost &amp; Found</h3>
            <p>Connecting lost items with their rightful owners through a trusted community platform.</p>
            <div class="social-links">
                <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="social-link" title="Follow us on Facebook">f</a>
                <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="social-link" title="Follow us on Instagram">📷</a>
                <a href="https://wa.me/" target="_blank" rel="noopener noreferrer" class="social-link" title="Contact us on WhatsApp">💬</a>
            </div>
        </div>

        <div class="site-footer-links-grid">
            <div class="site-footer-col">
                <h4>Quick Links</h4>
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('items.index') }}">Browse</a>
                <a href="{{ route('about') }}">About Us</a>
            </div>

            <div class="site-footer-col">
                <h4>Report</h4>
                <a href="{{ route('reports.lost.create') }}">Lost Report</a>
                <a href="{{ route('reports.found.create') }}">Found Report</a>
            </div>

            <div class="site-footer-col">
                <h4>Support</h4>
                <a href="{{ route('contact') }}">Contact Us</a>
                <a href="mailto:{{ config('mail.from.address', 'admin@example.com') }}">Email Support</a>
            </div>
        </div>
    </div>

    <div class="site-footer-bottom">
        <span>&copy; {{ now()->year }} Lost &amp; Found. All rights reserved.</span>
    </div>
</section>