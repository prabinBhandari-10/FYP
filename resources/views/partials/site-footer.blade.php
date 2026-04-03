<section class="site-footer-quick" aria-label="Footer quick actions">
    <div class="site-footer-inner">
        <div class="site-footer-brand">
            <h3>Lost &amp; Found</h3>
            <p>Connecting lost items with their rightful owners through a trusted community platform.</p>
        </div>

        <div class="site-footer-links-grid">
            <div class="site-footer-col">
                <h4>Quick Links</h4>
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('items.index') }}">Browse</a>
            </div>

            <div class="site-footer-col">
                <h4>Report</h4>
                <a href="{{ route('reports.lost.create') }}">Lost Report</a>
                <a href="{{ route('reports.found.create') }}">Found Report</a>
            </div>

            <div class="site-footer-col">
                <h4>Support</h4>
                <a href="mailto:{{ config('mail.from.address', 'admin@example.com') }}">Contact Admin</a>
            </div>
        </div>
    </div>

    <div class="site-footer-bottom">
        <span>&copy; {{ now()->year }} Lost &amp; Found. All rights reserved.</span>
    </div>
</section>