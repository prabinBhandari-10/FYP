<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Services\KhaltiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(protected KhaltiService $khaltiService)
    {
    }

    public function initiate(Request $request, Claim $claim): RedirectResponse
    {
        $claim->loadMissing('report');

        if ((int) $claim->user_id !== (int) $request->user()->id) {
            abort(403);
        }

        if (! $claim->payment_required || $claim->status !== 'awaiting_payment') {
            return back()->withErrors([
                'payment' => 'This claim is not waiting for payment.',
            ]);
        }

        if (! $claim->payment_amount || $claim->payment_amount < 1000) {
            return back()->withErrors([
                'payment' => 'Invalid payment amount set by admin.',
            ]);
        }

        if (! $claim->report || $claim->report->type !== 'found') {
            return back()->withErrors([
                'payment' => 'Payment is only available for found item claims.',
            ]);
        }

        $payload = [
            'return_url' => route('payment.callback', ['claim' => $claim->id]),
            'website_url' => config('app.url'),
            'amount' => (int) $claim->payment_amount,
            'purchase_order_id' => 'claim-' . $claim->id,
            'purchase_order_name' => 'Found item claim #' . $claim->id,
            'customer_info' => [
                'name' => (string) $request->user()->name,
                'email' => (string) $request->user()->email,
            ],
        ];

        try {
            $response = $this->khaltiService->initiatePayment($payload);
            $paymentUrl = $response['payment_url'] ?? null;
            $pidx = $response['pidx'] ?? null;

            if (! $paymentUrl) {
                Log::warning('Khalti initiate response missing payment_url.', ['response' => $response]);

                return back()->withErrors([
                    'payment' => 'Unable to initialize Khalti payment. Please try again.',
                ]);
            }

            if ($pidx) {
                $claim->update([
                    'payment_status' => 'initiated',
                    'payment_pidx' => (string) $pidx,
                ]);
            }

            return redirect()->away($paymentUrl);
        } catch (\Throwable $e) {
            Log::error('Khalti payment initiation failed.', [
                'message' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'payment' => 'Payment initiation failed. Please try again later.',
            ]);
        }
    }

    public function callback(Request $request): RedirectResponse
    {
        $claimId = (int) $request->query('claim');
        $claim = Claim::query()->with('report')->find($claimId);
        $pidx = (string) $request->query('pidx', '');

        if (! $claim) {
            return redirect()->route('claims.index')->withErrors(['payment' => 'Claim not found.']);
        }

        if ((int) $claim->user_id !== (int) optional($request->user())->id) {
            abort(403);
        }

        if ($pidx === '' || $claim->status !== 'awaiting_payment') {
            return redirect()->route('claims.index')->withErrors([
                'payment' => 'Missing or invalid Khalti payment reference.',
            ]);
        }

        try {
            $verification = $this->khaltiService->lookupPayment($pidx);
            $status = strtoupper((string) ($verification['status'] ?? ''));

            if ($status === 'COMPLETED') {
                $claim->update([
                    'status' => 'under_verification',
                    'payment_status' => 'completed',
                    'payment_pidx' => $pidx,
                    'payment_completed_at' => now(),
                    'held_at' => null,
                ]);

                return redirect()->route('claims.index')->with('success', '✓ Congratulations! Your payment has been verified. Your item claim is now under final admin review. Once approved, you will be able to connect with the item finder.');
            }

            $claim->update([
                'payment_status' => strtolower($status ?: 'failed'),
                'payment_pidx' => $pidx,
            ]);

            return redirect()->route('claims.index')->withErrors([
                'payment' => 'Payment verification failed. Current status: ' . ($verification['status'] ?? 'unknown'),
            ]);
        } catch (\Throwable $e) {
            Log::error('Khalti payment verification failed.', [
                'claim_id' => $claimId,
                'pidx' => $pidx,
                'message' => $e->getMessage(),
            ]);

            $claim->update([
                'payment_status' => 'error',
                'payment_pidx' => $pidx ?: $claim->payment_pidx,
            ]);

            return redirect()->route('claims.index')->withErrors([
                'payment' => 'Unable to verify Khalti payment at the moment.',
            ]);
        }
    }
}
