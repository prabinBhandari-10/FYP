@extends('layouts.app')

@section('title', 'User Dashboard | Lost and Found')

@section('content')
    <section class="card dashboard-shell" style="width: min(100%, 980px);">
        <div class="dashboard-heading">
            <h2>User Dashboard</h2>
            <p class="subtitle">Hi {{ auth()->user()->name }}, here is a quick snapshot of your activity.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="dashboard-grid dashboard-grid--wide">
            <div class="stat">
                <div class="label">Total reports</div>
                <div class="value">{{ $stats['totalReports'] ?? 0 }}</div>
            </div>

            <div class="stat">
                <div class="label">Lost reports</div>
                <div class="value">{{ $stats['lostReports'] ?? 0 }}</div>
            </div>

            <div class="stat">
                <div class="label">Found reports</div>
                <div class="value">{{ $stats['foundReports'] ?? 0 }}</div>
            </div>

            <div class="stat">
                <div class="label">Active claims</div>
                <div class="value">{{ $stats['activeClaims'] ?? 0 }}</div>
            </div>
        </div>

        <div class="dashboard-section-title">Quick actions</div>
        <div class="action-grid">
            <a href="{{ route('reports.lost.create') }}" class="action-card action-card--lost">
                <h3>Report Lost Item</h3>
                <p>Tell the community what you lost and where.</p>
            </a>

            <a href="{{ route('reports.found.create') }}" class="action-card action-card--found">
                <h3>Report Found Item</h3>
                <p>Help return an item to its rightful owner.</p>
            </a>

            <a href="{{ route('items.index') }}" class="action-card action-card--browse">
                <h3>Browse Items</h3>
                <p>Check all currently listed lost and found items.</p>
            </a>

            <a href="{{ route('claims.index') }}" class="action-card action-card--claims">
                <h3>My Claims</h3>
                <p>Review claim progress and updates quickly.</p>
            </a>
        </div>

        <p class="helper-text" style="margin-top: 20px;">
            Reports and claims modules can be extended in the next phase while keeping this dashboard as your central hub.
        </p>
    </section>
@endsection