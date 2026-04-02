@extends('layouts.app')

@section('title', 'Register | Lost & Found Auburn')

@section('content')
<div style="min-height: calc(100vh - 120px); display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 20px;">
    
    <div class="card" style="width: 100%; max-width: 440px; padding: 40px; margin: 0; box-shadow: 0 10px 25px rgba(0,0,0,0.03);">
        
        <div style="text-align: center; margin-bottom: 32px;">
            <div style="display: inline-flex; align-items: center; gap: 10px; margin-bottom: 16px;">
                <div style="width: 40px; height: 40px; border-radius: 12px; background-color: var(--primary); display: grid; place-items: center; color: white;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path>
                        <path d="M2 12h20"></path>
                    </svg>
                </div>
            </div>
            <h1 style="font-size: 24px; font-weight: 800; color: var(--text-dark); margin: 0 0 8px 0; letter-spacing: -0.02em;">Create an account</h1>
            <p style="font-size: 15px; color: var(--text-gray); margin: 0;">Join to report or claim lost items.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success" style="margin-bottom: 24px;">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error" style="margin-bottom: 24px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
            @csrf

            <div class="form-group">
                <label class="form-label" for="name">Full name</label>
                <input class="form-input" type="text" id="name" name="name" value="{{ old('name') }}" placeholder="John Doe" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email address</label>
                <input class="form-input" type="email" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input class="form-input" type="password" id="password" name="password" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="password_confirmation">Confirm password</label>
                <input class="form-input" type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 12px; margin-top: 8px;">Create account</button>
        </form>

        <div style="text-align: center; margin-top: 24px; border-top: 1px solid var(--border-color); padding-top: 24px;">
            <p style="font-size: 14px; color: var(--text-gray); margin: 0;">
                Already have an account? 
                <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 600; text-decoration: none;">Sign in here</a>
            </p>
        </div>
    </div>
    
    <div style="margin-top: 32px;">
        <a href="{{ url('/') }}" style="color: var(--text-gray); font-size: 14px; font-weight: 500; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to home
        </a>
    </div>

</div>
@endsection