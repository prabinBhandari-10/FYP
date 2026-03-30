@extends('layouts.app')

@section('title', ($title ?? 'Report Item') . ' | Lost and Found')

@section('content')
    <style>
        :root {
            --bg-dark: #080d1a;
            --bg-card: rgba(10, 16, 30, 0.72);
            --barca-blue: #1f3a8a;
            --barca-maroon: #7a1026;
            --barca-gold: #d1a74a;
            --text-main: #e7eaf4;
            --text-soft: #a5aec3;
            --border-glow: rgba(31, 58, 138, 0.35);
            --border-warm: rgba(122, 16, 38, 0.35);
            --input-bg: rgba(7, 12, 24, 0.7);
            --danger: #ef4444;
        }

        .lf-page {
            min-height: calc(100vh - 120px);
            padding: 36px 12px 64px;
            background:
                radial-gradient(1100px 520px at 85% -15%, rgba(31, 58, 138, 0.25), transparent 60%),
                radial-gradient(900px 420px at -10% 25%, rgba(122, 16, 38, 0.22), transparent 60%),
                var(--bg-dark);
            color: var(--text-main);
        }

        .lf-container {
            width: min(1120px, 100%);
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 28px;
        }

        .lf-hero h2 {
            margin: 0 0 10px;
            font-size: clamp(28px, 2.6vw, 40px);
            letter-spacing: 0.3px;
            font-weight: 700;
        }

        .lf-hero p {
            margin: 0 0 20px;
            color: var(--text-soft);
            font-size: 15px;
        }

        .lf-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: var(--barca-gold);
            background: rgba(209, 167, 74, 0.08);
            border: 1px solid rgba(209, 167, 74, 0.28);
            padding: 8px 12px;
            border-radius: 999px;
            margin-bottom: 14px;
        }

        .lf-card {
            background: var(--bg-card);
            border: 1px solid rgba(31, 58, 138, 0.25);
            border-radius: 18px;
            padding: 26px;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.06);
        }

        .lf-card::after {
            content: "";
            position: absolute;
            inset: -2px;
            background:
                radial-gradient(600px 180px at 10% -20%, rgba(31, 58, 138, 0.22), transparent 60%),
                radial-gradient(520px 140px at 90% 0%, rgba(122, 16, 38, 0.18), transparent 60%);
            pointer-events: none;
        }

        .lf-section-title {
            font-size: 12px;
            letter-spacing: 1.6px;
            text-transform: uppercase;
            color: var(--text-soft);
            margin: 0 0 8px;
        }

        .lf-form {
            display: grid;
            gap: 16px;
        }

        .lf-field {
            display: grid;
            gap: 8px;
        }

        .lf-label {
            font-size: 13px;
            color: var(--text-soft);
        }

        .lf-input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .lf-input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: var(--text-soft);
            pointer-events: none;
        }

        .lf-textarea-wrap .lf-input-icon {
            top: 16px;
            transform: none;
        }

        .lf-input,
        .lf-textarea,
        .lf-select {
            width: 100%;
            padding: 12px 14px 12px 48px;
            background: var(--input-bg);
            border: 1px solid rgba(31, 58, 138, 0.28);
            border-radius: 12px;
            color: var(--text-main);
            line-height: 1.35;
            text-shadow: none;
            outline: none;
            transition: all 0.25s ease;
            box-shadow: 0 0 0 0 transparent;
        }

        .lf-select {
            appearance: none;
            padding-right: 38px;
            background-image: linear-gradient(135deg, rgba(31, 58, 138, 0.5), rgba(122, 16, 38, 0.5));
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 10px 10px;
        }

        .lf-input::placeholder,
        .lf-textarea::placeholder {
            color: rgba(165, 174, 195, 0.8);
        }

        .lf-textarea {
            min-height: 120px;
            resize: vertical;
            padding-top: 14px;
        }

        .lf-input:focus,
        .lf-textarea:focus,
        .lf-select:focus {
            border-color: rgba(31, 58, 138, 0.8);
            box-shadow: 0 0 0 4px rgba(31, 58, 138, 0.25), 0 0 20px rgba(122, 16, 38, 0.35);
        }

        .lf-input:hover,
        .lf-textarea:hover,
        .lf-select:hover {
            border-color: rgba(122, 16, 38, 0.55);
        }

        .lf-error {
            color: var(--danger);
            font-size: 13px;
        }

        .lf-grid-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .lf-upload {
            display: grid;
            gap: 10px;
        }

        .lf-upload-box {
            border: 1px dashed rgba(31, 58, 138, 0.35);
            border-radius: 14px;
            padding: 16px;
            display: grid;
            place-items: center;
            text-align: center;
            min-height: 170px;
            background: rgba(7, 12, 24, 0.55);
            transition: all 0.25s ease;
        }

        .lf-upload-box:hover {
            border-color: rgba(122, 16, 38, 0.6);
            box-shadow: 0 0 18px rgba(31, 58, 138, 0.25);
        }

        .lf-preview {
            width: 100%;
            height: 140px;
            object-fit: cover;
            border-radius: 10px;
            display: none;
            box-shadow: 0 10px 26px rgba(0, 0, 0, 0.35);
        }

        .lf-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 6px;
            flex-wrap: wrap;
        }

        .lf-btn {
            padding: 12px 20px;
            border-radius: 12px;
            border: 1px solid rgba(31, 58, 138, 0.35);
            cursor: pointer;
            color: var(--text-main);
            background: linear-gradient(135deg, rgba(31, 58, 138, 0.28), rgba(122, 16, 38, 0.28));
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
        }

        .lf-btn-primary {
            border: none;
            font-weight: 600;
            background: linear-gradient(135deg, var(--barca-blue), var(--barca-maroon));
            box-shadow: 0 12px 30px rgba(31, 58, 138, 0.35);
        }

        .lf-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 16px 36px rgba(31, 58, 138, 0.45);
            filter: brightness(1.05);
        }

        .lf-side {
            display: grid;
            gap: 16px;
        }

        .lf-stat {
            padding: 16px;
            border-radius: 14px;
            background: rgba(8, 13, 26, 0.7);
            border: 1px solid rgba(122, 16, 38, 0.2);
        }

        .lf-stat h3 {
            margin: 0 0 6px;
            font-size: 16px;
        }

        .lf-stat p {
            margin: 0;
            color: var(--text-soft);
            font-size: 13px;
        }

        .navbar,
        .app-nav,
        .top-nav,
        header nav {
            background: rgba(8, 13, 26, 0.8) !important;
            border-bottom: 1px solid rgba(31, 58, 138, 0.3);
            backdrop-filter: blur(14px);
        }

        .navbar a,
        .app-nav a,
        .top-nav a,
        header nav a {
            color: var(--text-main);
        }

        .navbar .nav-link,
        .app-nav .nav-link,
        .top-nav .nav-link,
        header nav .nav-link {
            color: var(--text-soft);
        }

        .navbar .nav-link:hover,
        .app-nav .nav-link:hover,
        .top-nav .nav-link:hover,
        header nav .nav-link:hover {
            color: var(--barca-gold);
        }

        @media (max-width: 980px) {
            .lf-container {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <section class="lf-page">
        <div class="lf-container">
            <div>
                <div class="lf-hero">
                    <div class="lf-chip">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path d="M12 2l2.3 6.5L21 11l-6.7 2.5L12 22l-2.3-8.5L3 11l6.7-2.5L12 2z" stroke="var(--barca-gold)" stroke-width="1.5"/>
                        </svg>
                        Premium Loss Report
                    </div>
                    <h2>{{ $title }}</h2>
                    <p>Fill out the form below to submit your {{ $type }} item report.</p>
                </div>

                <div class="lf-card">
                    <p class="lf-section-title">Report Details</p>

                    @if ($errors->any())
                        <div class="alert alert-error">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="lf-form" action="{{ $submitRoute }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="lf-field">
                            <label class="lf-label" for="title">Title</label>
                            <div class="lf-input-wrap lf-textarea-wrap">
                                <span class="lf-input-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" width="18" height="18">
                                        <path d="M4 6h16M4 12h10M4 18h7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                                    </svg>
                                </span>
                                <input class="lf-input" type="text" id="title" name="title" value="{{ old('title') }}" required>
                            </div>
                            @error('title')
                                <div class="lf-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="lf-field">
                            <label class="lf-label" for="description">Description</label>
                            <div class="lf-input-wrap">
                                <span class="lf-input-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" width="18" height="18">
                                        <path d="M7 4h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.6"/>
                                        <path d="M9 9h6M9 13h6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                                    </svg>
                                </span>
                                <textarea class="lf-textarea" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                            </div>
                            @error('description')
                                <div class="lf-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="lf-grid-2">
                            <div class="lf-field">
                                <label class="lf-label" for="category">Category</label>
                                <div class="lf-input-wrap">
                                    <span class="lf-input-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" width="18" height="18">
                                            <path d="M4 6h16M6 6v12M18 6v12M4 18h16" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                                        </svg>
                                    </span>
                                    <input class="lf-input" type="text" id="category" name="category" value="{{ old('category') }}" placeholder="e.g. Electronics, Documents" required>
                                </div>
                                @error('category')
                                    <div class="lf-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="lf-field">
                                <label class="lf-label" for="location">Location</label>
                                <div class="lf-input-wrap">
                                    <span class="lf-input-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" width="18" height="18">
                                            <path d="M12 21s6-5.33 6-10a6 6 0 1 0-12 0c0 4.67 6 10 6 10Z" stroke="currentColor" stroke-width="1.6"/>
                                            <circle cx="12" cy="11" r="2.5" stroke="currentColor" stroke-width="1.6"/>
                                        </svg>
                                    </span>
                                    <input class="lf-input" type="text" id="location" name="location" value="{{ old('location') }}" required>
                                </div>
                                @error('location')
                                    <div class="lf-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="lf-grid-2">
                            <div class="lf-field">
                                <label class="lf-label" for="date">Date</label>
                                <div class="lf-input-wrap">
                                    <span class="lf-input-icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" width="18" height="18">
                                            <rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.6"/>
                                            <path d="M7 3v4M17 3v4M3 10h18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                                        </svg>
                                    </span>
                                    <input class="lf-input" type="date" id="date" name="date" value="{{ old('date') }}" required>
                                </div>
                                @error('date')
                                    <div class="lf-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="lf-field lf-upload">
                                <label class="lf-label" for="image">Image (optional)</label>
                                <div class="lf-upload-box">
                                    <img id="lfImagePreview" class="lf-preview" alt="Preview">
                                    <div id="lfUploadPrompt">
                                        <div style="margin-bottom: 8px; color: var(--text-soft);">
                                            <svg width="26" height="26" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M12 16V8M8 12h8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                                                <rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.6"/>
                                            </svg>
                                        </div>
                                        <div style="color: var(--text-soft); font-size: 13px;">Drop or select an image</div>
                                    </div>
                                </div>
                                <input class="lf-input" type="file" id="image" name="image" accept="image/*">
                                @error('image')
                                    <div class="lf-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="lf-actions">
                            <a href="{{ route('dashboard') }}" class="lf-btn">Cancel</a>
                            <button type="submit" class="lf-btn lf-btn-primary">Submit Report</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lf-side">
                <div class="lf-card">
                    <p class="lf-section-title">Preview</p>
                    <div class="lf-stat">
                        <h3>Secure Submission</h3>
                        <p>All reports are encrypted and stored securely with audit-friendly logs.</p>
                    </div>
                    <div class="lf-stat">
                        <h3>Smart Matching</h3>
                        <p>Our system connects your report to potential matches in real time.</p>
                    </div>
                    <div class="lf-stat">
                        <h3>Fast Response</h3>
                        <p>Notifications are sent instantly to authorized responders.</p>
                    </div>
                </div>

                <div class="lf-card">
                    <p class="lf-section-title">Tips</p>
                    <p style="color: var(--text-soft); font-size: 14px; margin: 0;">Add distinctive details and an image to improve recovery rate.</p>
                </div>
            </div>
        </div>
    </section>

    <script>
        const lfImageInput = document.getElementById('image');
        const lfPreview = document.getElementById('lfImagePreview');
        const lfPrompt = document.getElementById('lfUploadPrompt');

        if (lfImageInput) {
            lfImageInput.addEventListener('change', (event) => {
                const file = event.target.files && event.target.files[0];
                if (!file) {
                    lfPreview.style.display = 'none';
                    lfPrompt.style.display = 'block';
                    lfPreview.src = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = () => {
                    lfPreview.src = reader.result;
                    lfPreview.style.display = 'block';
                    lfPrompt.style.display = 'none';
                };
                reader.readAsDataURL(file);
            });
        }
    </script>
@endsection
