@extends('layouts.app')

@section('title', ($title ?? 'Report Item') . ' | Lost & Found Auburn')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    
    <div style="margin-bottom: 24px;">
        <span class="badge badge-neutral" style="background: white; border: 1px solid var(--border-color); color: var(--text-gray); margin-bottom: 24px; padding: 6px 12px; font-weight: 500;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;">
                <path d="M12 2v20"></path><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
            Premium Loss Report
        </span>
        
        <h1 class="page-title" style="margin-bottom: 8px;">{{ $title ?? 'Report Item' }}</h1>
        <p style="color: var(--text-gray); font-size: 15px; margin-bottom: 32px;">Fill out the form below to submit your {{ $type ?? 'lost' }} item report.</p>
    </div>

    <!-- Grid -->
    <div style="display: grid; grid-template-columns: minmax(0, 1fr) 280px; gap: 32px; align-items: start;">
        
        <!-- Form Section -->
        <div class="card" style="padding: 32px; margin: 0;">
            <h3 style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-light); margin-bottom: 24px;">Report Details</h3>
            
            <form method="POST" action="{{ $submitRoute ?? '' }}" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <label class="form-label" for="title">Title</label>
                    <input 
                        class="form-input" 
                        type="text" 
                        id="title" 
                        name="title" 
                        placeholder="e.g. Blue Hydro Flask, Keys on Auburn Lanyard" 
                        value="{{ old('title') }}" 
                        required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="description">Description</label>
                    <textarea 
                        class="form-textarea" 
                        id="description" 
                        name="description" 
                        placeholder="Detailed description of the item, context of how it was lost/found..." 
                        required>{{ old('description') }}</textarea>
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
                                <option value="{{ $cat }}" @selected(old('category') === $cat)>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="block">Block</label>
                        <select class="form-select" id="block" name="block" required>
                            <option value="">Select block</option>
                            <option value="Nepal Block" @selected(old('block') === 'Nepal Block')>Nepal Block (Inside College)</option>
                            <option value="UK Block" @selected(old('block') === 'UK Block')>UK Block (Inside College)</option>
                            <option value="Pokhara City" @selected(old('block') === 'Pokhara City' || old('block') === 'Pokhara')>Pokhara City (Outside College)</option>
                        </select>
                        @error('block')
                            <div style="font-size: 13px; color: #c0392b; margin-top: 6px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label" for="location">Location</label>
                        <select class="form-select" id="location" name="location" required data-old-location="{{ old('location') }}">
                            <option value="">Select location</option>
                        </select>
                        @error('location')
                            <div style="font-size: 13px; color: #c0392b; margin-top: 6px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div></div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="form-group">
                        <label class="form-label" for="date">Date</label>
                        <input class="form-input" type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="image">Image (optional)</label>
                        <input class="form-input" type="file" id="image" name="image" accept="image/*" style="padding: 7px 14px;">
                    </div>
                </div>
                
                <div style="margin-top: 32px; display: flex; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary" style="padding: 12px 32px;">Submit Report</button>
                </div>
            </form>
        </div>

        <!-- Sidebar / Features preview -->
        <div class="card" style="padding: 24px; background-color: var(--bg-color); border: 1px solid var(--border-color); box-shadow: none;">
            <p style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-light); margin-bottom: 24px;">Preview</p>
            
            <div style="margin-bottom: 24px;">
                <h4 style="font-size: 15px; font-weight: 700; color: var(--text-dark); margin-bottom: 6px;">Secure Submission</h4>
                <p style="font-size: 13.5px; color: var(--text-gray); line-height: 1.5;">All reports are encrypted and stored securely with audit-friendly logs.</p>
            </div>
            
            <div style="margin-bottom: 24px;">
                <h4 style="font-size: 15px; font-weight: 700; color: var(--text-dark); margin-bottom: 6px;">Smart Matching</h4>
                <p style="font-size: 13.5px; color: var(--text-gray); line-height: 1.5;">Our system connects your report to potential matches in real time.</p>
            </div>
            
            <div>
                <h4 style="font-size: 15px; font-weight: 700; color: var(--text-dark); margin-bottom: 6px;">Fast Response</h4>
                <p style="font-size: 13.5px; color: var(--text-gray); line-height: 1.5;">Notifications are sent instantly to authorized responders.</p>
            </div>
        </div>
        
    </div>

</div>

<!-- Auto-collapse behavior for mobile -->
<style>
@media (max-width: 768px) {
    div[style*="grid-template-columns: minmax"] {
        grid-template-columns: 1fr !important;
    }
}
</style>

<script>
    (function () {
        const blockSelect = document.getElementById('block');
        const locationSelect = document.getElementById('location');
        const oldLocation = locationSelect.dataset.oldLocation || '';

        const locationByBlock = {
            'Nepal Block': [
                'Annapurna',
                'Machapuchhre',
                'Begnas',
                'Rupa',
                'Rara',
                'Tilicho',
                'Nilgiri',
                'Kapuche'
            ],
            'UK Block': [
                'Basketball Court',
                'Library',
                'Canteen',
                'Parking Area',
                'Table Tennis Board'
            ],
            'Pokhara City': [
                'Lakeside',
                'Mahendrapool',
                'Prithvi Chowk',
                'Chipledhunga',
                'New Road',
                'Bagar',
                'Bindhyabasini',
                'Phewa Lake',
                'Talchowk',
                'Miyapatan',
                'Batulechaur',
                'Hemja',
                'Srijanachowk',
                'Nayabazar',
                'Rambazar'
            ]
        };

        function populateLocations() {
            const selectedBlock = blockSelect.value;
            const options = locationByBlock[selectedBlock] || [];

            locationSelect.innerHTML = '<option value="">Select location</option>';

            options.forEach((place) => {
                const option = document.createElement('option');
                option.value = place;
                option.textContent = place;

                if (oldLocation === place) {
                    option.selected = true;
                }

                locationSelect.appendChild(option);
            });
        }

        blockSelect.addEventListener('change', function () {
            locationSelect.dataset.oldLocation = '';
            populateLocations();
        });

        populateLocations();
    })();
</script>
@endsection