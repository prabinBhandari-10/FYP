@extends('layouts.app')

@section('title', 'Manage Claims | Lost and Found')

@section('content')
    <style>
        :root {
            --bg-dark: #080d1a;
            --bg-card: rgba(10, 16, 30, 0.72);
            --barca-blue: #1f3a8a;
            --barca-maroon: #7a1026;
            --barca-gold: #d1a74a;
            --text-main: #e7eaf4;
            --text-soft: #a5aec3;
        }

        .lf-page {
            min-height: calc(100vh - 120px);
            padding: 36px 12px 64px;
            background:
                radial-gradient(1100px 520px at 85% -15%, rgba(31, 58, 138, 0.25), transparent 60%),
                radial-gradient(900px 420px at -10% 25%, rgba(122, 16, 38, 0.22), transparent 60%),
                var(--bg-dark);
            color: var(--text-main);
        }

        .lf-container {
            width: min(1120px, 100%);
            margin: 0 auto;
            display: grid;
            gap: 18px;
        }

        .lf-card {
            background: var(--bg-card);
            border: 1px solid rgba(31, 58, 138, 0.25);
            border-radius: 16px;
            padding: 18px;
            display: grid;
            gap: 12px;
        }

        .lf-table {
            width: 100%;
            border-collapse: collapse;
        }

        .lf-table th,
        .lf-table td {
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid rgba(31, 58, 138, 0.2);
            font-size: 13px;
            color: var(--text-soft);
        }

        .lf-table th {
            color: var(--text-main);
            font-weight: 600;
        }

        .lf-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(209, 167, 74, 0.12);
            border: 1px solid rgba(209, 167, 74, 0.28);
            color: var(--barca-gold);
        }

        .lf-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .lf-btn {
            padding: 8px 14px;
            border-radius: 10px;
            border: 1px solid rgba(31, 58, 138, 0.35);
            cursor: pointer;
            color: var(--text-main);
            background: linear-gradient(135deg, rgba(31, 58, 138, 0.28), rgba(122, 16, 38, 0.28));
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .lf-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 36px rgba(31, 58, 138, 0.35);
        }

        .lf-btn-primary {
            border: none;
            font-weight: 600;
            background: linear-gradient(135deg, var(--barca-blue), var(--barca-maroon));
        }

        .lf-empty {
            background: var(--bg-card);
            border: 1px solid rgba(31, 58, 138, 0.25);
            border-radius: 16px;
            padding: 24px;
            color: var(--text-soft);
            text-align: center;
        }
    </style>

    <section class="lf-page">
        <div class="lf-container">
            <div>
                <h2>Manage Claims</h2>
                <p class="lf-meta">Review and approve or reject claim requests.</p>
            </div>

            @if (session('success'))
                <div class="lf-card">
                    <div class="lf-meta">{{ session('success') }}</div>
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
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($claims as $claim)
                                <tr>
                                    <td>{{ $claim->report?->title ?? 'Item' }}</td>
                                    <td>{{ $claim->report?->user?->name ?? 'Unknown' }}</td>
                                    <td>{{ $claim->user?->name ?? 'User' }}</td>
                                    <td><span class="lf-badge">{{ ucfirst($claim->status) }}</span></td>
                                    <td>
                                        <div class="lf-actions">
                                            <a class="lf-btn" href="{{ $claim->report ? route('items.show', $claim->report) : '#' }}">View</a>
                                            @if ($claim->status === 'pending')
                                                <form method="POST" action="{{ route('admin.claims.approve', $claim) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="lf-btn lf-btn-primary" type="submit">Approve</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.claims.reject', $claim) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button class="lf-btn" type="submit">Reject</button>
                                                </form>
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
