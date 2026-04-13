@extends('layouts.app')

@section('content')
<section class="page-section" style="max-width: 800px;">
    <article class="card">
        <h1 style="font-size: 28px; margin-bottom: 8px;">Urgent Report Payment</h1>
        <p class="section-note" style="margin-bottom: 24px;">Complete the payment to feature your report at the top of search results for 7 days.</p>

        <div style="background: var(--bg-soft); padding: 20px; border-radius: 12px; margin-bottom: 24px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <p style="margin: 0 0 4px 0; color: var(--text-muted); font-size: 13px;">Report Title</p>
                    <p style="margin: 0; font-weight: 600; font-size: 15px;">{{ $report->title }}</p>
                </div>
                <div>
                    <p style="margin: 0 0 4px 0; color: var(--text-muted); font-size: 13px;">Report Type</p>
                    <p style="margin: 0; font-weight: 600; font-size: 15px; text-transform: capitalize;">{{ $report->type }}</p>
                </div>
            </div>

            <div style="border-top: 1px solid var(--line); padding-top: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <span style="font-size: 14px;">Urgent Report Fee (Minimum)</span>
                    <span style="font-weight: 600; font-size: 16px;">NPR {{ number_format($amount) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 13px; color: var(--text-muted);">Duration</span>
                    <span style="font-size: 13px; color: var(--text-muted);">7 days</span>
                </div>
            </div>
        </div>

        <div style="background: #f3f4f6; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
            <h3 style="font-size: 14px; margin: 0 0 8px 0; font-weight: 600;">Benefits of Urgent Reports:</h3>
            <ul style="padding-left: 18px; margin: 0; font-size: 13px; color: var(--text-muted); display: grid; gap: 6px;">
                <li>🔴 Featured at the top of search results</li>
                <li>📈 Increased visibility to potential claimants</li>
                <li>⏱️ Urgent tag displayed on your report</li>
                <li>Valid for 7 days from payment</li>
                <li>🔒 Secure payment via Khalti</li>
            </ul>
        </div>

        <button type="button" id="pay-button" class="btn btn-primary" style="width: 100%; padding: 12px; cursor: pointer;">
            Loading Payment System...
        </button>

        <a href="{{ route('items.show', $report) }}" style="display: block; text-align: center; margin-top: 12px; color: var(--text-muted); text-decoration: none; font-size: 14px;">
            Cancel and return to report
        </a>
    </article>
</section>

<script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2.0.0/khalti-checkout.min.js"></script>
<script>
    (function() {
        const config = {
            publicKey: '{{ $publicKey }}',
            productIdentity: 'report-{{ $report->id }}',
            productName: 'Urgent Report - {{ substr($report->title, 0, 40) }}',
            productUrl: window.location.href,
            eventHandler: {
                onSuccess(payload) {
                    console.log('Payment Success:', payload);
                    if (payload.pidx) {
                        const verifyUrl = '{{ route("payments.urgent-report.verify", $report) }}?pidx=' + payload.pidx;
                        window.location.href = verifyUrl;
                    }
                },
                onError(error) {
                    console.error('Payment Error:', error);
                    alert('Payment failed: ' + (error?.message || 'Unknown error occurred'));
                },
                onClose() {
                    console.log('Modal closed');
                }
            },
            amount: {{ $amount * 100 }}
        };

        let checkout = null;
        let sdkLoaded = false;

        function initCheckout() {
            if (!sdkLoaded && typeof window.KhaltiCheckout !== 'undefined') {
                sdkLoaded = true;
                checkout = new window.KhaltiCheckout(config);
                attachButton();
            } else if (!sdkLoaded) {
                setTimeout(initCheckout, 100);
            }
        }

        function attachButton() {
            const btn = document.getElementById('pay-button');
            if (!btn) return;

            btn.innerHTML = 'Pay NPR {{ number_format($amount) }} via Khalti';
            btn.disabled = false;
            btn.style.opacity = '1';
            btn.onclick = function(e) {
                e.preventDefault();
                if (checkout) {
                    checkout.show({amount: {{ $amount * 100 }}});
                }
            };
        }

        // Start loading as soon as script runs
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initCheckout);
        } else {
            initCheckout();
        }

        // Also try when window loads
        window.addEventListener('load', initCheckout);
    })();
</script>

@endsection
