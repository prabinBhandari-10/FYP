@extends('layouts.app')

@section('title', 'My Claims | Lost & Found')

@section('content')
<section class="card" style="margin-bottom: 18px;">
    <h1 style="font-size: 32px; margin-bottom: 8px; display: flex; align-items: center; gap: 10px;">
        <span style="font-size: 24px; line-height: 1;"><wa-icon name="hand" family="sharp" variant="solid" style="color: rgb(2, 3, 4);"></wa-icon></span>
        <span>My Claims</span>
    </h1>
    <p class="section-note">Track your submitted claims and review their latest status.</p>
</section>

@if (session('success'))
    <section class="alert alert-success" style="margin-bottom: 14px; border-radius: 16px; background: #dcfce7; border: 1px solid #86efac; color: #166534;">
        {{ session('success') }}
    </section>
@endif

@if ($errors->has('payment') || $errors->has('claim'))
    <section class="alert alert-error" style="margin-bottom: 14px; border-radius: 16px;">
        {{ $errors->first('payment') ?? $errors->first('claim') }}
    </section>
@endif

@if ($claims->count() === 0)
    <div class="empty-state">You have not submitted any claims yet.</div>
@else
    <section style="display: grid; gap: 12px; margin-bottom: 14px;">
        @foreach ($claims as $claim)
            <article class="card">
                <div style="display: flex; justify-content: space-between; gap: 10px; flex-wrap: wrap; margin-bottom: 12px; align-items: start;">
                    <div style="flex: 1; min-width: 200px;">
                        <h3 style="font-size: 20px; margin-bottom: 4px;">{{ $claim->report?->title ?? 'Item' }}</h3>
                        <p class="section-note" style="margin-bottom: 4px;">{{ $claim->report?->location }} • {{ $claim->report?->date?->format('M d, Y') }}</p>
                    </div>
                    <span class="badge badge-neutral" style="text-transform: capitalize; white-space: nowrap;">{{ str_replace('_', ' ', $claim->status) }}</span>
                </div>

                <p style="font-size: 14px; color: var(--text-muted); margin-bottom: 14px; line-height: 1.6;">{{ $claim->message }}</p>

                @if ($claim->report)
                    <a class="btn btn-outline" style="margin-bottom: 12px;" href="{{ route('items.show', $claim->report) }}">View Item Details</a>
                @endif

                {{-- Pending State --}}
                @if ($claim->status === 'pending')
                    <div style="margin-top: 12px; padding: 14px; border-radius: 12px; background: #eff6ff; border: 1px solid #bfdbfe;">
                        <div style="display: flex; gap: 8px; margin-bottom: 8px; align-items: center;">
                            <span style="font-size: 20px;">⏳</span>
                            <p style="margin: 0; font-size: 13px; font-weight: 700; color: #1e40af;">Awaiting Admin Review</p>
                        </div>
                        <p style="margin: 0; font-size: 13px; color: #1e3a8a; line-height: 1.5;">Admin is currently reviewing your claim. You will be notified about the next steps soon.</p>
                    </div>
                @elseif ($claim->status === 'awaiting_payment')
                    <div style="margin-top: 12px; padding: 14px; border-radius: 12px; background: #fff7ed; border: 1px solid #fed7aa;">
                        <div style="display: flex; gap: 8px; margin-bottom: 8px; align-items: center;">
                            <span style="font-size: 20px;">💳</span>
                            <p style="margin: 0; font-size: 13px; font-weight: 700; color: #9a3412;">Payment Required</p>
                        </div>
                        <p style="margin: 0 0 8px; font-size: 13px; color: #9a3412;">Admin requires payment to continue with your claim verification.</p>
                        <p style="margin: 0 0 10px; font-size: 14px; color: #9a3412; font-weight: 700;">Amount: NPR {{ number_format(((int) $claim->payment_amount) / 100, 2) }}</p>
                        @if ($claim->payment_reason)
                            <p style="margin: 0 0 10px; font-size: 13px; color: #9a3412;">
                                <strong>Reason:</strong> {{ $claim->payment_reason }}
                            </p>
                        @endif
                        <form method="POST" action="{{ route('payment.initiate', $claim) }}" style="display: inline;">
                            @csrf
                            <button class="btn btn-primary" type="submit">💳 Pay Now</button>
                        </form>
                    </div>
                @elseif ($claim->status === 'under_verification')
                    <div style="margin-top: 12px; padding: 14px; border-radius: 12px; background: #f0fdf4; border: 1px solid #bbf7d0;">
                        <div style="display: flex; gap: 8px; margin-bottom: 8px; align-items: center;">
                            <span style="font-size: 20px;">✓</span>
                            <p style="margin: 0; font-size: 13px; font-weight: 700; color: #15803d;">Payment Verified</p>
                        </div>
                        <p style="margin: 0; font-size: 13px; color: #166534; line-height: 1.5;">Great! Your payment has been confirmed. Your claim is now under final admin verification. Once approved, you'll be able to connect with the item finder.</p>
                    </div>
                @elseif ($claim->status === 'approved')
                    <div style="margin-top: 12px; padding: 14px; border-radius: 12px; background: #fef3c7; border: 1px solid #fcd34d;">
                        <div style="display: flex; gap: 8px; margin-bottom: 10px; align-items: center;">
                            <span style="font-size: 20px;">🎉</span>
                            <p style="margin: 0; font-size: 13px; font-weight: 700; color: #b45309;">Claim Approved!</p>
                        </div>
                        <p style="margin: 0 0 10px; font-size: 13px; color: #92400e; line-height: 1.5;">Congratulations! Your claim has been approved by admin. You can now connect with the item finder and discuss the details.</p>
                        <a class="btn btn-primary" href="{{ route('chat.show', $claim) }}">💬 Start Conversation</a>
                    </div>
                @elseif ($claim->status === 'rejected')
                    <div style="margin-top: 12px; padding: 14px; border-radius: 12px; background: #fee2e2; border: 1px solid #fecaca;">
                        <div style="display: flex; gap: 8px; margin-bottom: 8px; align-items: center;">
                            <span style="font-size: 20px;">✕</span>
                            <p style="margin: 0; font-size: 13px; font-weight: 700; color: #991b1b;">Claim Rejected</p>
                        </div>
                        <p style="margin: 0; font-size: 13px; color: #7f1d1d; line-height: 1.5;">Unfortunately, your claim was not approved by admin. You may submit another claim or contact support if you believe this was a mistake.</p>
                    </div>
                @endif
            </article>
        @endforeach
    </section>

    <div style="margin-top: 14px;">
        {{ $claims->links() }}
    </div>
@endif
@endsection
