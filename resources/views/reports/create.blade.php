@extends('layouts.app')

@section('title', ($title ?? 'Report Item') . ' | Lost & Found')

@section('content')
<section style="margin-bottom: 16px;">
    <h1 class="page-title" style="margin-bottom: 8px;">{{ $title ?? 'Report Item' }}</h1>
    <p class="page-subtitle">Submit details clearly so the community can help reconnect items quickly and safely.</p>
</section>

<section class="split-layout" style="margin-bottom: 18px;">
    <article class="card">
        <form method="POST" action="{{ $submitRoute ?? '' }}" enctype="multipart/form-data">
            @csrf

            <h2 style="font-size: 22px; margin-bottom: 14px;">Reporter Contact</h2>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="reporter_name">Full Name</label>
                    <input class="form-input" type="text" id="reporter_name" name="reporter_name" value="{{ old('reporter_name', auth()->user()?->name) }}" placeholder="Your full name" required>
                    @error('reporter_name')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="reporter_phone">Phone Number</label>
                    <input class="form-input" type="text" id="reporter_phone" name="reporter_phone" value="{{ old('reporter_phone') }}" placeholder="98XXXXXXXX" required>
                    @error('reporter_phone')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="reporter_email">Email Address</label>
                <input class="form-input" type="email" id="reporter_email" name="reporter_email" value="{{ old('reporter_email', auth()->user()?->email) }}" placeholder="you@example.com" required>
                @error('reporter_email')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
            </div>

            <hr style="border: 0; border-top: 1px solid var(--line); margin: 18px 0;">

            <h2 style="font-size: 22px; margin-bottom: 14px;">Item Details</h2>

            <div class="form-group">
                <label class="form-label" for="title">Title</label>
                <input class="form-input" type="text" id="title" name="title" value="{{ old('title') }}" placeholder="e.g. Black Wallet, Silver Keys" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="color">Item Color</label>
                <select class="form-select" id="color" name="color" required>
                    <option value="">Select a color</option>
                    @php
                        $colors = ['Black', 'White', 'Blue', 'Red', 'Green', 'Yellow', 'Pink', 'Purple', 'Brown', 'Gray', 'Silver', 'Gold', 'Multicolor', 'Other'];
                    @endphp
                    @foreach ($colors as $color)
                        <option value="{{ $color }}" @selected(old('color') === $color)>{{ $color }}</option>
                    @endforeach
                </select>
                @error('color')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Description</label>
                <textarea class="form-textarea" id="description" name="description" placeholder="Add clear identifying details and context" required>{{ old('description') }}</textarea>
            </div>

            <div class="form-row">
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
                        <option value="Unknown" @selected(old('block') === 'Unknown')>Unknown</option>
                    </select>
                    @error('block')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="location">Exact Location</label>
                    <select class="form-select" id="location" name="location" data-old-location="{{ old('location') }}">
                        <option value="">Select location</option>
                    </select>
                    @error('location')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="location_note">Approximate Location (if exact is unknown)</label>
                    <input class="form-input" type="text" id="location_note" name="location_note" value="{{ old('location_note') }}" placeholder="e.g. Near canteen, around parking area">
                    @error('location_note')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Pin Exact Spot on Map (optional)</label>
                <div style="display: flex; gap: 8px; margin-bottom: 10px; flex-wrap: wrap;">
                    <input class="form-input" type="text" id="map-search-input" placeholder="Search place on map (e.g. Lakeside, Pokhara)" style="flex: 1; min-width: 220px;">
                    <button type="button" id="map-search-btn" class="btn btn-outline">Search</button>
                </div>
                <div id="report-map" style="height: 260px; border: 1px solid var(--line); border-radius: 14px; overflow: hidden;"></div>
                <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap; margin-top: 10px;">
                    <button type="button" id="use-current-location" class="btn btn-outline">Use My Current Location</button>
                    <span id="map-coords-text" class="section-note">No pin selected yet.</span>
                </div>
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                @error('latitude')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
                @error('longitude')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="date">Date</label>
                    <input class="form-input" type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="image">Primary Image (optional)</label>
                    <input class="form-input" type="file" id="image" name="image" accept="image/*">
                    @error('image')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="images">Additional Images (up to 5 more)</label>
                <input class="form-input" type="file" id="images" name="images[]" accept="image/*" multiple max="5">
                <p class="section-note" style="margin-top: 6px;">Upload additional photos to help in identifying the item.</p>
                @error('images')<div style="color: var(--danger); font-size: 12px; margin-top: 6px;">{{ $message }}</div>@enderror
            </div>

            <div style="display: flex; align-items: center; gap: 12px; background: var(--bg-soft); padding: 14px 16px; border-radius: 12px; margin: 18px 0;">
                <input type="checkbox" id="is_anonymous" name="is_anonymous" value="1" @checked(old('is_anonymous')) style="width: 18px; height: 18px; cursor: pointer;">
                <label for="is_anonymous" style="cursor: pointer; margin: 0; font-size: 14px; font-weight: 600;">Report anonymously (your name won't be visible publicly)</label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 8px;">Submit Report</button>
        </form>
    </article>

    <aside class="sticky-panel" style="display: grid; gap: 14px;">
        <article class="card card-soft">
            <h3 style="font-size: 20px; margin-bottom: 8px;">Submission Tips</h3>
            <ul style="padding-left: 18px; color: var(--text-muted); font-size: 14px; line-height: 1.7; display: grid; gap: 4px;">
                <li>Use a clear title with color and type.</li>
                <li>Mention distinguishing marks in description.</li>
                <li>Add approximate location if exact place is unknown.</li>
                <li>Upload image if available for easier matching.</li>
            </ul>
        </article>

        <article class="card card-soft">
            <h3 style="font-size: 20px; margin-bottom: 8px;">How Claims Work</h3>
            <p class="section-note" style="line-height: 1.7;">
                Found items can be claimed after ownership verification. Clear and accurate details improve successful matching.
            </p>
        </article>
    </aside>
</section>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    (function () {
        const blockSelect = document.getElementById('block');
        const locationSelect = document.getElementById('location');
        const oldLocation = locationSelect.dataset.oldLocation || '';
        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');
        const coordsText = document.getElementById('map-coords-text');
        const useCurrentLocationBtn = document.getElementById('use-current-location');
        const mapSearchInput = document.getElementById('map-search-input');
        const mapSearchBtn = document.getElementById('map-search-btn');

        const locationByBlock = {
            'Nepal Block': ['Annapurna', 'Machapuchhre', 'Begnas', 'Rupa', 'Rara', 'Tilicho', 'Nilgiri', 'Kapuche'],
            'UK Block': ['Basketball Court', 'Library', 'Canteen', 'Parking Area', 'Table Tennis Board', 'Open Access Lab', 'Stonehenge', 'Big Ben'],
            'Pokhara City': ['Lakeside', 'Mahendrapool', 'Prithvi Chowk', 'Chipledhunga', 'New Road', 'Bagar', 'Bindhyabasini', 'Phewa Lake', 'Talchowk', 'Miyapatan', 'Batulechaur', 'Hemja', 'Srijanachowk', 'Nayabazar', 'Rambazar'],
            'Unknown': []
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

        const defaultCenter = [28.2096, 83.9856];
        const oldLat = parseFloat(latitudeInput.value);
        const oldLng = parseFloat(longitudeInput.value);
        const hasOldCoords = Number.isFinite(oldLat) && Number.isFinite(oldLng);

        const map = L.map('report-map').setView(hasOldCoords ? [oldLat, oldLng] : defaultCenter, hasOldCoords ? 15 : 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let marker = null;

        function setCoords(lat, lng) {
            latitudeInput.value = lat.toFixed(7);
            longitudeInput.value = lng.toFixed(7);
            coordsText.textContent = `Pinned: ${lat.toFixed(5)}, ${lng.toFixed(5)}`;

            if (!marker) {
                marker = L.marker([lat, lng], { draggable: true }).addTo(map);
                marker.on('dragend', function (event) {
                    const point = event.target.getLatLng();
                    setCoords(point.lat, point.lng);
                });
            } else {
                marker.setLatLng([lat, lng]);
            }
        }

        if (hasOldCoords) {
            setCoords(oldLat, oldLng);
        }

        map.on('click', function (event) {
            setCoords(event.latlng.lat, event.latlng.lng);
        });

        useCurrentLocationBtn.addEventListener('click', function () {
            if (!navigator.geolocation) {
                coordsText.textContent = 'Geolocation is not supported in this browser.';
                return;
            }

            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    map.setView([lat, lng], 16);
                    setCoords(lat, lng);
                },
                function () {
                    coordsText.textContent = 'Could not access your current location.';
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                }
            );
        });

        async function searchAndPinLocation() {
            const query = (mapSearchInput.value || '').trim();

            if (query === '') {
                coordsText.textContent = 'Type a place name to search on map.';
                mapSearchInput.focus();
                return;
            }

            const previousText = mapSearchBtn.textContent;
            mapSearchBtn.disabled = true;
            mapSearchBtn.textContent = 'Searching...';
            coordsText.textContent = 'Searching location...';

            try {
                const endpoint = 'https://nominatim.openstreetmap.org/search?format=jsonv2&limit=1&q=' + encodeURIComponent(query);
                const response = await fetch(endpoint, {
                    headers: {
                        'Accept': 'application/json',
                    },
                });

                if (!response.ok) {
                    throw new Error('Search request failed.');
                }

                const results = await response.json();

                if (!Array.isArray(results) || results.length === 0) {
                    coordsText.textContent = 'No matching place found. Try a more specific search.';
                    return;
                }

                const first = results[0];
                const lat = parseFloat(first.lat);
                const lng = parseFloat(first.lon);

                if (!Number.isFinite(lat) || !Number.isFinite(lng)) {
                    coordsText.textContent = 'Invalid coordinates returned from search.';
                    return;
                }

                map.setView([lat, lng], 16);
                setCoords(lat, lng);
                coordsText.textContent = 'Pinned from search: ' + (first.display_name || query);
            } catch (error) {
                coordsText.textContent = 'Could not search location right now. Please try again.';
            } finally {
                mapSearchBtn.disabled = false;
                mapSearchBtn.textContent = previousText;
            }
        }

        mapSearchBtn.addEventListener('click', searchAndPinLocation);
        mapSearchInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                searchAndPinLocation();
            }
        });
    })();
</script>
@endsection
