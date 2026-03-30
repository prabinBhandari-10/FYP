@extends('layouts.app')

@section('title', 'Admin Dashboard | Lost and Found')

@section('content')
    <section class="card" style="width: min(100%, 860px);">
        <h2>Admin Dashboard</h2>
        <p class="subtitle">Welcome back {{ auth()->user()->name }}. You are signed in as administrator.</p>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="dashboard-grid">
            <div class="stat">
                <div class="value">0</div>
                <div class="label">Total users</div>
            </div>

            <div class="stat">
                <div class="value">0</div>
                <div class="label">Pending reports</div>
            </div>

            <div class="stat">
                <div class="value">0</div>
                <div class="label">Resolved claims</div>
            </div>
        </div>

        <p class="helper-text" style="margin-top: 18px;">
            This is the admin area. Next, we can add management tools for users, reports, and moderation.
        </p>
    </section>
@endsection
