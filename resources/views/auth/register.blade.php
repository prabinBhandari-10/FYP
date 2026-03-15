@extends('layouts.app')

@section('title', 'Register | Lost and Found')

@section('content')
    <section class="card">
        <h2>Create your account</h2>
        <p class="subtitle">Start reporting and recovering lost items.</p>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.store') }}" method="POST">
            @csrf

            <div class="field">
                <label for="name">Full name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="field">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="field">
                <label for="password_confirmation">Confirm password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Create account</button>
        </form>

        <p class="helper-text">
            Already have an account?
            <a href="{{ route('login') }}">Login here</a>
        </p>
    </section>
@endsection