@extends('layouts.app')

@section('title', 'Manage Claims | Lost and Found')

@section('content')
    <style>
        .lf-page {
            min-height: calc(100vh - 120px);
            padding: 20px 0 36px;
        }

        .lf-container {
            width: min(1160px, 100%);
            margin: 0 auto;
            display: grid;
            gap: 14px;
        }

        .lf-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 18px;
            display: grid;
            gap: 12px;
            overflow-x: auto;
        }

        .lf-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1200px;
        }

        .lf-table th,
        .lf-table td {
            text-align: left;
            padding: 10px 8px;
            border-bottom: 1px solid var(--border-color);
            font-size: 13px;
            color: var(--text-gray);
            vertical-align: top;
        }

        .lf-table th:last-child,
        .lf-table td:last-child {
            padding: 12px 8px;
            min-width: 420px;
        }

        .lf-table th {
            color: var(--text-dark);
            font-weight: 600;
        }

        .lf-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            padding: 6px 10px;
            border-radius: 999px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1d4ed8;
        }

        .lf-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            align-items: flex-start;
        }

        .lf-actions form {
            margin: 0;
            display: flex;
            gap: 6px;
        }

        .lf-actions form:has(input[type="number"]),
        .lf-actions form:has(input[type="text"]) {
            display: grid;
            gap: 6px;
            grid-template-columns: auto auto auto auto;
            align-items: center;
        }

        .lf-btn {
            padding: 8px 14px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            cursor: pointer;
            color: var(--text-dark);
            background: #ffffff;
            text-decoration: none;
            transition: background-color 0.2s ease, border-color 0.2s ease;
            font-size: 12px;
            position: relative;
            z-index: 1;
        }

        .lf-btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .lf-btn-primary {
            font-weight: 600;
            background: var(--primary);
            border-color: var(--primary);
            color: #ffffff;
        }

        .lf-btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .lf-empty {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 24px;
            color: var(--text-gray);
            text-align: center;
        }

        .lf-meta {
            color: var(--text-gray);
            font-size: 13px;
        }

        .lf-table tbody tr.rejected-claim {
            background-color: #fee2e2;
            border-left: 4px solid #dc2626;
        }

        .lf-table tbody tr.rejected-claim td {
            color: #7f1d1d;
        }
    </style>

    <section class="lf-page">
        <div class="lf-container">
            <div>
                <h2>Manage Claims</h2>
                <p class="lf-meta">View all claims, check uploaded documents, and take moderation actions.</p>
            </div>

            @if (session('success'))
                <div class="lf-card">
                    <div class="lf-meta">{{ session('success') }}</div>
                </div>
            @endif

            @if ($errors->any())
                <div class="lf-card">
                    @foreach ($errors->all() as $error)
                        <div class="lf-meta" style="color: #b91c1c;">{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if ($claims->count() === 0)
                <div class="lf-empty">No claims submitted yet.</div>
            @else
                <div class="lf-card">
                    <table class="lf-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Reporter</th>
                                <th>Claimant</th>
                                <th>Status</th>
                                <th>Documents</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($claims as $claim)
                                <tr @if($claim->status === 'rejected') class="rejected-claim" @endif>
                                    <td>{{ $claim->report?->title ?? 'Item' }}</td>
                                    <td>{{ $claim->report?->user?->name ?? 'Unknown' }}</td>
                                    <td>
                                        <div>{{ $claim->user?->name ?? 'User' }}</div>
                                        <div class="lf-meta" style="font-size: 11px;">
                                            Rejected claims: {{ $rejectedCounts[$claim->user_id] ?? 0 }}
                                        </div>
                                    </td>
                                    <td>
                                        @if ($claim->status === 'pending' && $claim->held_at)
                                            <span class="lf-badge">On hold</span>
                                        @elseif ($claim->status === 'awaiting_payment')
                                            <span class="lf-badge" style="background: #fff7ed; border-color: #fdba74; color: #9a3412;">Awaiting Payment</span>
                                        @elseif ($claim->status === 'under_verification')
                                            <span class="lf-badge" style="background: #ecfeff; border-color: #a5f3fc; color: #155e75;">Under Verification</span>
                                        @else
                                            <span class="lf-badge">{{ ucfirst($claim->status) }}</span>
                                        @endif

                                        @if ($claim->status === 'awaiting_payment')
                                            <div class="lf-meta" style="margin-top: 6px; font-size: 11px;">
                                                Amount: NPR {{ number_format(((int) $claim->payment_amount) / 100, 2) }}
                                            </div>
                                            <div class="lf-meta" style="margin-top: 4px; font-size: 11px; max-width: 220px;">
                                                Reason: {{ $claim->payment_reason }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="lf-actions">
                                            @if ($claim->citizenship_document_path)
                                                <a class="lf-btn" target="_blank" href="{{ Illuminate\Support\Facades\Storage::url($claim->citizenship_document_path) }}">Citizenship</a>
                                            @endif

                                            @if ($claim->proof_photo_path)
                                                <a class="lf-btn" target="_blank" href="{{ Illuminate\Support\Facades\Storage::url($claim->proof_photo_path) }}">Proof Photo</a>
                                            @endif

                                            @if (! $claim->citizenship_document_path && ! $claim->proof_photo_path)
                                                <span class="lf-meta">No files</span>
                                            @endif
                                        </div>

                                        @if ($claim->proof_text)
                                            <div class="lf-meta" style="margin-top: 8px; max-width: 280px;">
                                                {{ Illuminate\Support\Str::limit($claim->proof_text, 80) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="lf-actions">
                                            <a class="lf-btn" href="{{ $claim->report ? route('items.show', $claim->report) : '#' }}">View</a>
                                            @if ($claim->status === 'pending')
                                                <form method="POST" action="{{ route('admin.claims.approve', $claim) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="payment_required" value="0">
                                                    <button class="lf-btn lf-btn-primary" type="submit">Move to Verification</button>
                                                </form>

                                                <form method="POST" action="{{ route('admin.claims.approve', $claim) }}" style="display: grid; gap: 6px;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="payment_required" value="1">
                                                    <input class="lf-btn" type="number" name="payment_amount" min="1000" step="1" placeholder="Amount in paisa" required>
                                                    <input class="lf-btn" type="text" name="payment_reason" maxlength="255" placeholder="Reason for payment" required>
                                                    <button class="lf-btn" type="submit">Require Payment</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.claims.hold', $claim) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="lf-btn" type="submit">Hold</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.claims.reject', $claim) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="lf-btn" type="submit">Reject</button>
                                                </form>
                                            @elseif ($claim->status === 'under_verification')
                                                <form method="POST" action="{{ route('admin.claims.final-approve', $claim) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="lf-btn lf-btn-primary" type="submit">Final Approve</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.claims.reject', $claim) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="lf-btn" type="submit">Reject</button>
                                                </form>
                                            @elseif ($claim->status === 'awaiting_payment')
                                                <form method="POST" action="{{ route('admin.claims.reject', $claim) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="lf-btn" type="submit">Reject</button>
                                                </form>
                                            @endif

                                            @if ($claim->user && $claim->user->role !== 'admin')
                                                @if (! $claim->user->is_blocked)
                                                    <form method="POST" action="{{ route('admin.users.block', $claim->user) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button class="lf-btn" type="submit">Block user</button>
                                                    </form>
                                                @endif

                                                @if (($rejectedCounts[$claim->user_id] ?? 0) >= 3)
                                                    <form method="POST" action="{{ route('admin.users.destroy', $claim->user) }}" onsubmit="return confirm('Delete this user for repeated fake claims?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="lf-btn" type="submit">Delete user</button>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 12px;">
                    {{ $claims->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
