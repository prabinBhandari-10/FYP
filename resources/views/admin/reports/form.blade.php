@extends('layouts.app')

@section('title', $formTitle . ' | Lost and Found')

@section('content')
<div style="max-width: 860px; margin: 0 auto; display: grid; gap: 18px;">
    <div style="display: flex; justify-content: space-between; gap: 12px; align-items: center; flex-wrap: wrap;">
        <h1 class="page-title" style="margin: 0;">{{ $formTitle }}</h1>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-outline">Back to Manage Reports</a>
    </div>

    <div class="card" style="margin: 0;">
        <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data">
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="reporter_name">Reporter Name</label>
                    <input class="form-input" id="reporter_name" name="reporter_name" type="text" required value="{{ old('reporter_name', $report->reporter_name) }}">
                    @error('reporter_name')
                        <div style="font-size: 12px; color: #b91c1c; margin-top: 6px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="reporter_phone">Reporter Phone</label>
                    <input class="form-input" id="reporter_phone" name="reporter_phone" type="text" required value="{{ old('reporter_phone', $report->reporter_phone) }}">
                    @error('reporter_phone')
                        <div style="font-size: 12px; color: #b91c1c; margin-top: 6px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="reporter_email">Reporter Email</label>
                <input class="form-input" id="reporter_email" name="reporter_email" type="email" required value="{{ old('reporter_email', $report->reporter_email) }}">
                @error('reporter_email')
                    <div style="font-size: 12px; color: #b91c1c; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 10px 0 20px;">

            <div class="form-group">
                <label class="form-label" for="type">Report Type</label>
                <select class="form-select" id="type" name="type" required>
                    <option value="lost" @selected(old('type', $report->type) === 'lost')>Lost</option>
                    <option value="found" @selected(old('type', $report->type) === 'found')>Found</option>
                </select>
                @error('type')
                    <div style="font-size: 12px; color: #b91c1c; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="title">Title</label>
                <input class="form-input" id="title" name="title" type="text" required value="{{ old('title', $report->title) }}">
                @error('title')
                    <div style="font-size: 12px; color: #b91c1c; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Description</label>
                <textarea class="form-textarea" id="description" name="description" required>{{ old('description', $report->description) }}</textarea>
                @error('description')
                    <div style="font-size: 12px; color: #b91c1c; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="category">Category</label>
                    <input class="form-input" id="category" name="category" type="text" required value="{{ old('category', $report->category) }}">
                    @error('category')
                        <div style="font-size: 12px; color: #b91c1c; margin-top: 6px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="status">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="open" @selected(old('status', $report->status) === 'open')>Open</option>
                        <option value="closed" @selected(old('status', $report->status) === 'closed')>Closed</option>
                    </select>
                    @error('status')
                        <div style="font-size: 12px; color: #b91c1c; margin-top: 6px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="location">Location (Block - Place)</label>
                <input class="form-input" id="location" name="location" type="text" required value="{{ old('location', $report->location) }}" placeholder="Nepal Block - Annapurna">
                @error('location')
                    <div style="font-size: 12px; color: #b91c1c; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="date">Date</label>
                    <input class="form-input" id="date" name="date" type="date" required value="{{ old('date', optional($report->date)->format('Y-m-d') ?? $report->date) }}">
                    @error('date')
                        <div style="font-size: 12px; color: #b91c1c; margin-top: 6px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="latitude">Latitude</label>
                    <input class="form-input" id="latitude" name="latitude" type="text" value="{{ old('latitude', $report->latitude) }}" placeholder="28.2096">
                    @error('latitude')
                        <div style="font-size: 12px; color: #b91c1c; margin-top: 6px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="longitude">Longitude</label>
                    <input class="form-input" id="longitude" name="longitude" type="text" value="{{ old('longitude', $report->longitude) }}" placeholder="83.9856">
                    @error('longitude')
                        <div style="font-size: 12px; color: #b91c1c; margin-top: 6px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="image">Image (optional)</label>
                <input class="form-input" id="image" name="image" type="file" accept="image/*" style="padding: 8px;">
                @if ($isEdit && $report->image)
                    <div style="margin-top: 8px; font-size: 13px; color: var(--text-gray);">Current image: {{ $report->image }}</div>
                @endif
                @error('image')
                    <div style="font-size: 12px; color: #b91c1c; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
                <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
