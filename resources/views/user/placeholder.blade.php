@extends('layouts.app')

@section('title', ($title ?? 'Coming Soon') . ' | Lost and Found')

@section('content')
    <section class="card" style="width: min(100%, 760px);">
        <h2>{{ $title ?? 'Coming Soon' }}</h2>
        <p class="subtitle">{{ $message ?? 'This module will be available soon.' }}</p>

        <p class="helper-text" style="margin-top: 8px; margin-bottom: 20px;">
            This page is connected to your dashboard quick actions so navigation already works.
        </p>

        <a class="btn btn-primary" href="{{ route('dashboard') }}">Back to Dashboard</a>
    </section>
@endsection
