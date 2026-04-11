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
                    <span style="font-size: 14px;">Urgent Report Fee</span>
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
                <li>Featured at the top of search results</li>
                <li>Increased visibility to potential claimants</li>
                <li>Valid for 7 days from payment</li>
                <li>Secure payment via Khalti</li>
            </ul>
        </div>

        <div id="khalti-payment-section" style="display: none;">
            <div id="khalti-payment-container" style="margin-bottom: 24px;"></div>
        </div>

        <button type="button" id="pay-button" class="btn btn-primary" style="width: 100%; padding: 12px;">
            Pay NPR {{ number_format($amount) }} via Khalti
        </button>

        <a href="{{ route('items.show', $report) }}" style="display: block; text-align: center; margin-top: 12px; color: var(--text-muted); text-decoration: none; font-size: 14px;">
            Cancel and return to report
        </a>
    </article>
</section>

<script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2.0.0/khalti-checkout.iffe.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reportId = '{{ $report->id }}';
        const amount = {{ $amount * 100 }}; // Convert to paisa
        const publicKey = '{{ $publicKey }}';

        const config = {
            publicKey: publicKey,
            productIdentity: 'report-' + reportId,
            productName: 'Urgent Report - {{ substr($report->title, 0, 50) }}',
            productUrl: '{{ route('items.show', $report) }}',
            eventHandler: {
                onSuccess(payload) {
                    // Send verification request to backend
                    verifyPayment(payload.tidx);
                },
                onError(error) {
                    console.error('Payment error:', error);
                    alert('Payment failed. Please try again.');
                },
                onClose() {
                    console.log('Khalti payment closed');
                }
            },
            amount: amount,
        };

        const checkout = new KhaltiCheckout(config);

        document.getElementById('pay-button').addEventListener('click', function() {
            checkout.show({amount: amount});
        });

        function verifyPayment(pidx) {
            fetch('{{ route('payments.urgent-report.verify', $report) }}?pidx=' + pidx)
                .then(response => {
                    if (response.ok) {
                        window.location.href = response.url;
                    } else {
                        alert('Payment verification failed. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Verification error:', error);
                    alert('An error occurred during verification.');
                });
        }
    });
</script>
@endsection
