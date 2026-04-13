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
                    <input class="form-input" id="reporter_phone" name="reporter_phone" type="tel" pattern="\d{10}" maxlength="10" inputmode="numeric" required value="{{ old('reporter_phone', $report->reporter_phone) }}" oninput="this.value = this.value.replace(/[^\d]/g, '');">
                    <div style="font-size: 12px; color: #6c757d; margin-top: 6px;">Must be a 10-digit phone number</div>
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
                <style>
                    .ql-container {
                        font-size: 14px;
                        font-family: inherit;
                    }
                    .ql-editor {
                        min-height: 250px;
                        max-height: 400px;
                        padding: 12px;
                        border-radius: 6px;
                        line-height: 1.6;
                    }
                    .ql-toolbar.ql-snow {
                        border: 1px solid #dee2e6;
                        border-bottom: none;
                        border-radius: 6px 6px 0 0;
                        background: #f8f9fa;
                    }
                    .ql-container.ql-snow {
                        border: 1px solid #dee2e6;
                        border-radius: 0 0 6px 6px;
                    }
                </style>
                <div id="description" data-quill-editor data-quill-placeholder="Enter report description..."></div>
                <input type="hidden" name="description" id="description-input" value="{{ old('description', $report->description) }}" required>
                @error('description')
                    <div style="font-size: 12px; color: #b91c1c; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="color">Item Color</label>
                <select class="form-select" id="color" name="color" required>
                    <option value="">Select a color</option>
                    @php
                        $colors = ['Black', 'White', 'Blue', 'Red', 'Green', 'Yellow', 'Pink', 'Purple', 'Brown', 'Gray', 'Silver', 'Gold', 'Multicolor', 'Other'];
                    @endphp
                    @foreach ($colors as $color)
                        <option value="{{ $color }}" @selected(old('color', $report->color) === $color)>{{ $color }}</option>
                    @endforeach
                </select>
                @error('color')
                    <div style="font-size: 12px; color: #b91c1c; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="category">Category</label>
                    <select class="form-select" id="category" name="category" required>
                        <option value="">Select a category</option>
                        @php
                            $cats = ['Electronics', 'Documents/IDs', 'Keys', 'Clothing', 'Accessories', 'Bags/Wallets', 'Books/Stationery', 'Other'];
                        @endphp
                        @foreach ($cats as $cat)
                            <option value="{{ $cat }}" @selected(old('category', $report->category) === $cat)>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <div style="font-size: 12px; color: #b91c1c; margin-top: 6px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="block">Location</label>
                    <select class="form-select" id="block" name="block" required>
                        <option value="">Select location</option>
                        <option value="Nepal Block" @selected(old('block', $report->block ?? '') === 'Nepal Block')>Nepal Block (Inside College)</option>
                        <option value="UK Block" @selected(old('block', $report->block ?? '') === 'UK Block')>UK Block (Inside College)</option>
                        <option value="Pokhara City" @selected(old('block', $report->block ?? '') === 'Pokhara City' || old('block', $report->block ?? '') === 'Pokhara')>Pokhara City (Outside College)</option>
                        <option value="Unknown" @selected(old('block', $report->block ?? '') === 'Unknown')>Unknown</option>
                    </select>
                    @error('block')
                        <div style="font-size: 12px; color: #b91c1c; margin-top: 6px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="location">Exact Location</label>
                    <select class="form-select" id="location" name="location" data-old-location="{{ old('location', $report->location ?? '') }}">
                        <option value="">Select location</option>
                    </select>
                    @error('location')
                        <div style="font-size: 12px; color: #b91c1c; margin-top: 6px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="location_note">Approximate Location (if exact is unknown)</label>
                    <input class="form-input" type="text" id="location_note" name="location_note" value="{{ old('location_note', '') }}" placeholder="e.g. Near canteen, around parking area">
                    @error('location_note')
                        <div style="font-size: 12px; color: #b91c1c; margin-top: 6px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="date">Date</label>
                    <input class="form-input" id="date" name="date" type="date" required value="{{ old('date', optional($report->date)->format('Y-m-d') ?? $report->date) }}">
                    @error('date')
                        <div style="font-size: 12px; color: #b91c1c; margin-top: 6px;">{{ $message }}</div>
                    @enderror
                </div>

                @if($isEdit)
                <div class="form-group">
                    <label class="form-label" for="status">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="pending" @selected(old('status', $report->status) === 'pending')>Pending Approval</option>
                        <option value="open" @selected(old('status', $report->status) === 'open')>Open</option>
                        <option value="closed" @selected(old('status', $report->status) === 'closed')>Closed</option>
                    </select>
                    @error('status')
                        <div style="font-size: 12px; color: #b91c1c; margin-top: 6px;">{{ $message }}</div>
                    @enderror
                </div>
                @else
                <input type="hidden" name="status" value="open">
                @endif
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
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
                @error('image')<div style="font-size: 12px; color: #b91c1c; margin-top: 6px;">{{ $message }}</div>@enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="date">Date</label>
                    <input class="form-input" id="date" name="date" type="date" required value="{{ old('date', $report->date?->format('Y-m-d')) }}">
                    @error('date')<div style="font-size: 12px; color: #b91c1c; margin-top: 6px;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="urgency">Priority Level</label>
                    <select class="form-select" id="urgency" name="urgency" required>
                        <option value="normal" @selected(old('urgency', $report->urgency ?? 'normal') === 'normal')>Normal Priority</option>
                        <option value="urgent" @selected(old('urgency', $report->urgency ?? 'normal') === 'urgent')>🔴 Urgent Priority</option>
                    </select>
                    @error('urgency')<div style="font-size: 12px; color: #b91c1c; margin-top: 6px;">{{ $message }}</div>@enderror
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 20px;">
                <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        const blockSelect = document.getElementById('block');
        const locationSelect = document.getElementById('location');
        const oldLocation = locationSelect.dataset.oldLocation || '';

        const locationByBlock = {
            'Nepal Block': ['Annapurna', 'Machapuchhre', 'Begnas', 'Rupa', 'Rara', 'Tilicho', 'Nilgiri', 'Kapuche', 'Canteen', 'Library', 'Parking Area', 'Basketball Court', 'Table Tennis Board'],
            'UK Block': ['Parking Area', 'Table Tennis Board', 'Open Access Lab', 'Stonehenge', 'Big Ben', 'kingstone'],
            'Pokhara City': ['Lakeside', 'Mahendrapool', 'Prithvi Chowk', 'Chipledhunga', 'New Road', 'Bagar', 'Bindhyabasini', 'Phewa Lake', 'Talchowk', 'Miyapatan', 'Batulechaur', 'Hemja', 'Srijanachowk', 'Nayabazar', 'Rambazar'],
            'Unknown': []
        };

        function populateLocations() {
            const selectedBlock = blockSelect.value;
            const options = locationByBlock[selectedBlock] || [];

            locationSelect.innerHTML = '<option value="">Select location</option>';
            options.forEach(option => {
                const optionElement = document.createElement('option');
                optionElement.value = option;
                optionElement.textContent = option;
                optionElement.selected = option === oldLocation;
                locationSelect.appendChild(optionElement);
            });
        }

        blockSelect.addEventListener('change', populateLocations);
        populateLocations();
    })();
</script>
@endsection
