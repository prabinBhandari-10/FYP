@extends('layouts.app')

@section('title', 'Dashboard | Lost and Found')

@section('content')
    <section class="card" style="width: min(100%, 860px);">
        <h2>Dashboard</h2>
        <p class="subtitle">Hi {{ auth()->user()->name }}, welcome to your Lost and Found workspace.</p>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="dashboard-grid">
            <div class="stat">
                <div class="value">0</div>
                <div class="label">Lost reports</div>
            </div>

            <div class="stat">
                <div class="value">0</div>
                <div class="label">Found reports</div>
            </div>

            <div class="stat">
                <div class="value">0</div>
                <div class="label">Claims submitted</div>
            </div>
        </div>

        <p class="helper-text" style="margin-top: 18px;">
            This is a starter dashboard for Phase 1. In the next phase, we can add report and claim modules.
        </p>
    </section>
@endsection