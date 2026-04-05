@extends('layouts.app')

@section('title', 'Register | Lost & Found')

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <h1 style="font-size: 32px; margin-bottom: 6px;">Create Account</h1>
        <p class="page-subtitle" style="margin-bottom: 20px;">Join the platform to report and claim items securely.</p>

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

        <form action="{{ route('register.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label" for="name">Full Name</label>
                <input class="form-input" type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Your full name" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input class="form-input" type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input class="form-input" type="password" id="password" name="password" placeholder="Create a password" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirm Password</label>
                <input class="form-input" type="password" id="password_confirmation" name="password_confirmation" placeholder="Repeat your password" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Create Account</button>
        </form>

        <p style="margin-top: 18px; color: var(--text-muted); font-size: 14px; text-align: center;">
            Already have an account?
            <a href="{{ route('login') }}" style="color: var(--primary); text-decoration: none; font-weight: 700;">Sign in here</a>
        </p>
    </div>
</div>
@endsection
