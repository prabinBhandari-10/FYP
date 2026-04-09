@extends('layouts.app')

@section('title', 'Login | Lost & Found')

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <h1 style="font-size: 32px; margin-bottom: 6px;">Welcome to Lost & Found</h1>
        <p class="page-subtitle" style="margin-bottom: 20px;">Sign in to manage reports, claims, and updates.</p>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <ul style="padding-left: 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.attempt') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input class="form-input" type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input class="form-input" type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 18px; flex-wrap: wrap;">
                <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 14px; color: var(--text-muted);">
                    <input type="checkbox" id="remember" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                    Remember me
                </label>
                <a href="{{ route('password.request') }}" style="color: var(--primary); text-decoration: none; font-weight: 700; font-size: 14px;">Forgot password?</a>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Sign In</button>
        </form>

        <p style="margin-top: 18px; color: var(--text-muted); font-size: 14px; text-align: center;">
            New here?
            <a href="{{ route('register') }}" style="color: var(--primary); text-decoration: none; font-weight: 700;">Create an account</a>
        </p>
    </div>
</div>
@endsection
