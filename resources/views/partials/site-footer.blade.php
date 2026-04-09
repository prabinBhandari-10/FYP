<footer class="site-footer-quick" aria-label="Footer">
    <div class="site-footer-inner">
        <div class="site-footer-brand">
            <h3>Lost &amp; Found</h3>
            <p>
                A simple platform to help people report lost items, submit found items, and reconnect belongings safely.
            </p>
            <div class="social-links" aria-label="Social links">
                <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="social-link" title="Facebook" aria-label="Facebook"><x-bi-facebook /></a>
                <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="social-link" title="Instagram" aria-label="Instagram"><x-bi-instagram /></a>
                <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" class="social-link" title="Twitter / X" aria-label="Twitter"><x-bi-twitter /></a>
            </div>
        </div>

        <div class="site-footer-links-grid">
            <div class="site-footer-col">
                <h4>Quick Links</h4>
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('items.index') }}">Browse</a>
                <a href="{{ route('about') }}">About</a>
                <a href="{{ route('contact') }}">Contact</a>
            </div>

            <div class="site-footer-col">
                <h4>Actions</h4>
                <a href="{{ route('reports.lost.create') }}">Report Lost</a>
                <a href="{{ route('reports.found.create') }}">Report Found</a>
                <a href="{{ route('reports.track.form') }}">Track Report</a>
                <a href="{{ route('chat.index') }}">Chats</a>
            </div>

            <div class="site-footer-col">
                <h4>Support</h4>
                <a href="mailto:{{ config('mail.from.address', 'admin@example.com') }}">Email Support</a>
                <a href="{{ route('contact') }}">Help Center</a>
                <a href="{{ route('login') }}">Login</a>
            </div>

            <div class="site-footer-col site-footer-updated">
                <h4>Stay Updated</h4>
                <p>Get updates about reports, claims, and platform changes.</p>
            </div>
        </div>
    </div>

    <div class="site-footer-bottom">
        <span>© {{ now()->year }} Lost &amp; Found. All rights reserved.</span>
        <span>Style with Lost &amp; Found</span>
    </div>
</footer>