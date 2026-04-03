<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Report Lost Item | Lost &amp; Found</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-dark: #040912;
            --bg-mid: #07162d;
            --bg-soft: #0d2344;
            --card-bg: rgba(9, 21, 43, 0.65);
            --card-line: rgba(120, 151, 196, 0.28);
            --input-bg: rgba(8, 19, 38, 0.82);
            --input-line: rgba(138, 165, 201, 0.28);
            --text-main: #eaf1ff;
            --text-soft: #9cb1cf;
            --text-muted: #7f93b3;
            --accent-blue: #2a83ff;
            --accent-cyan: #1fd7e8;
            --accent-glow: rgba(31, 215, 232, 0.25);
            --danger-bg: rgba(249, 95, 132, 0.12);
            --danger-line: rgba(249, 95, 132, 0.38);
            --danger-text: #ffd3de;
            --success-bg: rgba(54, 205, 168, 0.14);
            --success-line: rgba(54, 205, 168, 0.4);
            --success-text: #c2f8e8;
            --radius-lg: 16px;
            --radius-md: 13px;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            min-height: 100%;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            color: var(--text-main);
            background:
                radial-gradient(circle at 10% -5%, rgba(50, 126, 255, 0.25), transparent 35%),
                radial-gradient(circle at 95% 5%, rgba(16, 176, 208, 0.2), transparent 30%),
                radial-gradient(circle at 50% 110%, rgba(35, 82, 170, 0.22), transparent 42%),
                linear-gradient(140deg, var(--bg-dark), var(--bg-mid) 55%, var(--bg-soft));
        }

        .page-shell {
            min-height: 100vh;
            padding: 16px 14px 30px;
            position: relative;
            overflow: hidden;
        }

        .page-shell::before,
        .page-shell::after {
            content: "";
            position: absolute;
            border-radius: 999px;
            filter: blur(60px);
            z-index: 0;
            pointer-events: none;
        }

        .page-shell::before {
            width: 260px;
            height: 260px;
            top: -70px;
            right: -70px;
            background: rgba(42, 131, 255, 0.25);
        }

        .page-shell::after {
            width: 300px;
            height: 300px;
            bottom: -140px;
            left: -70px;
            background: rgba(31, 215, 232, 0.15);
        }

        .top-nav {
            width: min(1080px, 100%);
            margin: 0 auto;
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 14px;
            border-radius: 14px;
            border: 1px solid rgba(128, 162, 210, 0.22);
            background: rgba(8, 18, 37, 0.58);
            backdrop-filter: blur(10px);
        }

        .nav-brand {
            margin: 0;
            font-size: 0.94rem;
            font-weight: 700;
            letter-spacing: 0.2px;
            color: #d4e5ff;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-link,
        .logout-btn {
            border: 1px solid rgba(130, 163, 210, 0.34);
            border-radius: 10px;
            background: rgba(12, 25, 48, 0.62);
            color: #d5e5ff;
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 8px 12px;
            transition: all 180ms ease;
        }

        .nav-link:hover,
        .logout-btn:hover {
            border-color: rgba(79, 174, 255, 0.9);
            box-shadow: 0 0 0 3px rgba(56, 138, 255, 0.16);
            transform: translateY(-1px);
        }

        .logout-btn {
            cursor: pointer;
        }

        .content-area {
            width: min(860px, 100%);
            margin: 24px auto 0;
            position: relative;
            z-index: 2;
            animation: cardEntrance 450ms ease-out;
        }

        .glass-card {
            border-radius: var(--radius-lg);
            border: 1px solid var(--card-line);
            background: var(--card-bg);
            box-shadow:
                0 22px 55px rgba(2, 8, 20, 0.5),
                inset 0 1px 0 rgba(190, 213, 247, 0.07),
                0 0 0 1px rgba(45, 121, 232, 0.06);
            backdrop-filter: blur(14px);
            padding: 24px;
        }

        .report-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 18px;
        }

        .header-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            border: 1px solid rgba(88, 174, 255, 0.45);
            background: rgba(30, 85, 158, 0.35);
            color: #cde3ff;
            font-size: 0.74rem;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        .header-badge svg {
            width: 14px;
            height: 14px;
        }

        .header-title {
            margin: 8px 0 5px;
            font-size: clamp(1.45rem, 2.4vw, 1.95rem);
            line-height: 1.2;
            letter-spacing: 0.2px;
        }

        .header-subtitle {
            margin: 0;
            color: var(--text-soft);
            font-size: 0.95rem;
            line-height: 1.55;
        }

        .alert {
            border-radius: 12px;
            border: 1px solid transparent;
            padding: 10px 12px;
            margin-bottom: 14px;
            font-size: 0.9rem;
        }

        .alert-success {
            background: var(--success-bg);
            border-color: var(--success-line);
            color: var(--success-text);
        }

        .alert-error {
            background: var(--danger-bg);
            border-color: var(--danger-line);
            color: var(--danger-text);
        }

        .alert ul {
            margin: 0;
            padding-left: 18px;
        }

        .type-toggle {
            margin: 4px 0 18px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 6px;
            border: 1px solid rgba(129, 160, 207, 0.33);
            border-radius: 14px;
            background: rgba(8, 18, 36, 0.7);
        }

        .toggle-btn {
            border: 0;
            border-radius: 10px;
            background: transparent;
            color: #a9c0e4;
            padding: 9px 14px;
            font-size: 0.86rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 160ms ease;
        }

        .toggle-btn.active {
            background: linear-gradient(120deg, rgba(44, 131, 255, 0.92), rgba(30, 208, 232, 0.88));
            color: #f3f8ff;
            box-shadow: 0 10px 22px rgba(27, 109, 219, 0.34);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .field-full {
            grid-column: 1 / -1;
        }

        .field-label {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 0.86rem;
            font-weight: 600;
            color: #c8d9f0;
        }

        .field-label svg {
            width: 15px;
            height: 15px;
            color: #8fc9ff;
            opacity: 0.95;
        }

        .control-wrap {
            position: relative;
        }

        .control-icon {
            width: 16px;
            height: 16px;
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #82a6d7;
            pointer-events: none;
        }

        .control,
        .control-textarea,
        .control-file,
        .control-select {
            width: 100%;
            border: 1px solid var(--input-line);
            border-radius: var(--radius-md);
            background: var(--input-bg);
            color: var(--text-main);
            font-size: 0.93rem;
            transition: border-color 170ms ease, box-shadow 170ms ease, transform 170ms ease;
        }

        .control,
        .control-select,
        .control-file {
            height: 46px;
            padding: 0 12px 0 38px;
        }

        .control-textarea {
            min-height: 135px;
            padding: 11px 12px;
            resize: vertical;
            line-height: 1.55;
        }

        .control:focus,
        .control-select:focus,
        .control-textarea:focus,
        .control-file:focus {
            outline: none;
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px var(--accent-glow);
            transform: translateY(-1px);
        }

        .control::placeholder,
        .control-textarea::placeholder {
            color: var(--text-muted);
        }

        .control-select {
            appearance: none;
            background-image: linear-gradient(45deg, transparent 50%, #8eb3e5 50%), linear-gradient(135deg, #8eb3e5 50%, transparent 50%);
            background-position: calc(100% - 17px) calc(50% - 3px), calc(100% - 11px) calc(50% - 3px);
            background-size: 6px 6px, 6px 6px;
            background-repeat: no-repeat;
        }

        .location-row {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 8px;
        }

        .geo-btn {
            border: 1px solid rgba(120, 170, 230, 0.48);
            background: rgba(18, 45, 84, 0.56);
            color: #d2e6ff;
            border-radius: 12px;
            min-width: 128px;
            padding: 0 12px;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 170ms ease;
        }

        .geo-btn:hover {
            border-color: rgba(109, 182, 255, 0.9);
            transform: translateY(-1px);
        }

        .upload-area {
            border: 1px dashed rgba(127, 162, 206, 0.5);
            border-radius: 13px;
            background: rgba(7, 17, 33, 0.62);
            padding: 10px;
        }

        .preview-box {
            margin-top: 8px;
            min-height: 150px;
            border-radius: 10px;
            background: rgba(6, 16, 30, 0.72);
            display: grid;
            place-items: center;
            overflow: hidden;
        }

        .preview-box img {
            width: 100%;
            max-height: 230px;
            object-fit: cover;
            display: none;
        }

        .preview-text {
            margin: 0;
            color: #7d93b5;
            font-size: 0.84rem;
            text-align: center;
            padding: 0 10px;
        }

        .field-error {
            margin: 0;
            color: #ffbfd0;
            font-size: 0.81rem;
        }

        .actions {
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            text-decoration: none;
            border-radius: 12px;
            height: 45px;
            padding: 0 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.89rem;
            font-weight: 700;
            letter-spacing: 0.2px;
            transition: all 180ms ease;
        }

        .btn-secondary {
            border: 1px solid rgba(137, 167, 211, 0.4);
            background: rgba(10, 23, 43, 0.6);
            color: #d4e3f8;
        }

        .btn-secondary:hover {
            border-color: rgba(95, 176, 255, 0.85);
            transform: translateY(-1px);
        }

        .btn-primary {
            border: 0;
            cursor: pointer;
            color: #f7fcff;
            background: linear-gradient(120deg, var(--accent-blue), var(--accent-cyan));
            box-shadow: 0 14px 30px rgba(28, 115, 228, 0.32);
        }

        .btn-primary:hover {
            transform: translateY(-1px) scale(1.015);
            box-shadow: 0 18px 34px rgba(28, 115, 228, 0.42);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .site-footer-quick {
            width: min(860px, 100%);
            margin: 16px auto 0;
            position: relative;
            z-index: 2;
            border-radius: 14px;
            border: 1px solid rgba(130, 165, 210, 0.35);
            background: rgba(11, 25, 48, 0.72);
            padding: 18px 14px 10px;
        }

        .site-footer-inner {
            display: grid;
            grid-template-columns: minmax(220px, 1.2fr) minmax(0, 1.8fr);
            gap: 20px;
        }

        .site-footer-brand h3 {
            margin: 0 0 8px;
            font-size: 24px;
            line-height: 1;
            text-transform: uppercase;
            color: #e9f3ff;
            letter-spacing: -0.4px;
        }

        .site-footer-brand p {
            margin: 0;
            color: #abc2e4;
            font-size: 0.86rem;
            line-height: 1.5;
        }

        .site-footer-links-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(120px, 1fr));
            gap: 14px;
        }

        .site-footer-col h4 {
            margin: 0 0 8px;
            color: #d7e7ff;
            font-size: 0.86rem;
            font-weight: 700;
        }

        .site-footer-col a {
            display: block;
            margin-bottom: 7px;
            color: #acc5e8;
            text-decoration: none;
            font-size: 0.82rem;
            font-weight: 600;
        }

        .site-footer-col a:hover {
            color: #f3f8ff;
            text-decoration: underline;
        }

        .site-footer-bottom {
            margin-top: 14px;
            padding-top: 10px;
            border-top: 1px solid rgba(123, 159, 206, 0.32);
            color: #9cb5d9;
            font-size: 0.8rem;
        }

        @keyframes cardEntrance {
            from {
                opacity: 0;
                transform: translateY(14px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 820px) {
            .content-area {
                margin-top: 16px;
            }

            .glass-card {
                padding: 18px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .location-row {
                grid-template-columns: 1fr;
            }

            .geo-btn {
                height: 42px;
            }

            .actions {
                flex-direction: column-reverse;
                align-items: stretch;
            }

            .site-footer-inner {
                grid-template-columns: 1fr;
            }

            .site-footer-links-grid {
                grid-template-columns: repeat(2, minmax(120px, 1fr));
            }
        }

        @media (max-width: 560px) {
            .top-nav {
                padding: 10px;
            }

            .nav-brand {
                font-size: 0.82rem;
            }

            .nav-link,
            .logout-btn {
                padding: 7px 9px;
                font-size: 0.75rem;
            }

            .report-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .toggle-btn {
                padding: 8px 11px;
                font-size: 0.8rem;
            }

            .site-footer-links-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="page-shell">
    <nav class="top-nav">
        <h1 class="nav-brand">Lost &amp; Found Management System</h1>

        <div class="nav-links">
            <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>

            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </nav>

    <main class="content-area">
        <section class="glass-card">
            <div class="report-header">
                <div>
                    <span class="header-badge">
                        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a7 7 0 0 0-7 7c0 4.6 4.6 10 6.1 11.6a1.1 1.1 0 0 0 1.8 0C14.4 19 19 13.6 19 9a7 7 0 0 0-7-7Zm0 9.3A2.3 2.3 0 1 1 12 6.7a2.3 2.3 0 0 1 0 4.6Z"/></svg>
                        Secure Item Report
                    </span>
                    <h2 class="header-title" id="formTitle">Report Lost Item</h2>
                    <p class="header-subtitle" id="formSubtitle">Help us reconnect you with your belongings.</p>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
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

            <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" id="itemForm">
                @csrf

                @php
                    [$oldBlock, $oldPlace] = array_pad(explode(' - ', (string) old('location'), 2), 2, '');
                @endphp

                <input type="hidden" name="type" id="typeField" value="{{ old('type', 'lost') }}">

                <div class="type-toggle" role="tablist" aria-label="Report type">
                    <button type="button" class="toggle-btn" data-type="lost">Lost Item</button>
                    <button type="button" class="toggle-btn" data-type="found">Found Item</button>
                </div>

                <div class="form-grid">
                    <div class="field field-full">
                        <label for="title" class="field-label">
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4 5a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5Zm3 3v2h10V8H7Zm0 4v2h7v-2H7Z"/></svg>
                            Item Title
                        </label>
                        <div class="control-wrap">
                            <svg class="control-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M5 4h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z"/></svg>
                            <input type="text" id="title" name="title" class="control" value="{{ old('title') }}" placeholder="Example: Black Laptop Bag" required>
                        </div>
                        @error('title')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field field-full">
                        <label for="description" class="field-label">
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Zm3 5v2h8V8H8Zm0 4v2h8v-2H8Z"/></svg>
                            Description
                        </label>
                        <textarea id="description" name="description" class="control-textarea" placeholder="Describe color, brand, size, and special marks" required>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="category" class="field-label">
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M10 3H4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1Zm10 0h-6a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1ZM10 13H4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-6a1 1 0 0 0-1-1Zm10 0h-6a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-6a1 1 0 0 0-1-1Z"/></svg>
                            Category
                        </label>
                        <div class="control-wrap">
                            <svg class="control-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4 4h7v7H4V4Zm9 0h7v7h-7V4ZM4 13h7v7H4v-7Zm9 0h7v7h-7v-7Z"/></svg>
                            <select id="category" name="category" class="control-select" required>
                                <option value="">Select category</option>
                                @foreach (['Electronics', 'Documents', 'Wallet', 'Bag', 'Keys', 'ID Card', 'Mobile', 'Laptop', 'Other'] as $option)
                                    <option value="{{ $option }}" {{ old('category') === $option ? 'selected' : '' }}>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('category')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="date" class="field-label">
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 2a1 1 0 0 1 1 1v1h8V3a1 1 0 1 1 2 0v1h1a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V7a3 3 0 0 1 3-3h1V3a1 1 0 0 1 1-1Zm12 9H5v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-8Z"/></svg>
                            Date
                        </label>
                        <div class="control-wrap">
                            <svg class="control-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 2h2v2h6V2h2v2h2a2 2 0 0 1 2 2v13a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V6a2 2 0 0 1 2-2h2V2Zm12 8H5v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-9Z"/></svg>
                            <input type="date" id="date" name="date" class="control" value="{{ old('date') }}" required>
                        </div>
                        @error('date')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field field-full">
                        <label for="block" class="field-label">
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a7 7 0 0 0-7 7c0 4.8 5.2 10.8 6.2 11.9a1 1 0 0 0 1.6 0C13.8 19.8 19 13.8 19 9a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5Z"/></svg>
                            Block
                        </label>
                        <div class="control-wrap">
                            <svg class="control-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4 4h7v7H4V4Zm9 0h7v7h-7V4ZM4 13h7v7H4v-7Zm9 0h7v7h-7v-7Z"/></svg>
                            <select id="block" class="control-select" required data-old-block="{{ old('block', $oldBlock) }}">
                                <option value="">Select block</option>
                                <option value="Nepal Block">Nepal Block (Inside College)</option>
                                <option value="UK Block">UK Block (Inside College)</option>
                                <option value="Pokhara City">Pokhara City (Outside College)</option>
                            </select>
                        </div>
                    </div>

                    <div class="field field-full">
                        <label for="place" class="field-label">
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a7 7 0 0 0-7 7c0 4.8 5.2 10.8 6.2 11.9a1 1 0 0 0 1.6 0C13.8 19.8 19 13.8 19 9a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5Z"/></svg>
                            Place
                        </label>
                        <div class="control-wrap">
                            <svg class="control-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a7 7 0 0 0-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 0 0-7-7Z"/></svg>
                            <select id="place" class="control-select" required data-old-place="{{ old('place', $oldPlace) }}">
                                <option value="">Select place</option>
                            </select>
                        </div>

                        <input type="hidden" id="location" name="location" value="{{ old('location') }}">
                        @error('location')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field field-full">
                        <label for="image" class="field-label">
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M5 3h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Zm1 2v10l3.4-3.4a1 1 0 0 1 1.4 0L13 13.8l2.6-2.6a1 1 0 0 1 1.4 0L19 13V5H6Zm3 5a1.6 1.6 0 1 0 0-3.2A1.6 1.6 0 0 0 9 10Z"/></svg>
                            Image Upload
                        </label>

                        <div class="upload-area">
                            <div class="control-wrap">
                                <svg class="control-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 3 8 7h3v6h2V7h3l-4-4Zm-7 9h2v7h10v-7h2v8a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-8Z"/></svg>
                                <input type="file" id="image" name="image" class="control-file" accept="image/*">
                            </div>

                            <div class="preview-box">
                                <img id="previewImage" alt="Preview image">
                                <p class="preview-text" id="previewText">Choose an image to preview it here</p>
                            </div>
                        </div>

                        @error('image')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="actions">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Submit Report</button>
                </div>
            </form>
        </section>
    </main>

    @include('partials.site-footer')
</div>

<script>
    (function () {
        const typeField = document.getElementById('typeField');
        const toggleButtons = document.querySelectorAll('.toggle-btn');
        const formTitle = document.getElementById('formTitle');
        const formSubtitle = document.getElementById('formSubtitle');
        const imageInput = document.getElementById('image');
        const previewImage = document.getElementById('previewImage');
        const previewText = document.getElementById('previewText');
        const blockSelect = document.getElementById('block');
        const placeSelect = document.getElementById('place');
        const locationInput = document.getElementById('location');

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

        function updateHiddenLocation() {
            if (blockSelect.value && placeSelect.value) {
                locationInput.value = `${blockSelect.value} - ${placeSelect.value}`;
                return;
            }

            locationInput.value = '';
        }

        function populatePlaces() {
            const block = blockSelect.value;
            const options = locationByBlock[block] || [];
            const oldPlace = placeSelect.dataset.oldPlace || '';

            placeSelect.innerHTML = '<option value="">Select place</option>';

            options.forEach((place) => {
                const option = document.createElement('option');
                option.value = place;
                option.textContent = place;

                if (place === oldPlace) {
                    option.selected = true;
                }

                placeSelect.appendChild(option);
            });

            updateHiddenLocation();
        }

        function applyType(type) {
            typeField.value = type;

            toggleButtons.forEach((button) => {
                button.classList.toggle('active', button.dataset.type === type);
            });

            if (type === 'found') {
                formTitle.textContent = 'Report Found Item';
                formSubtitle.textContent = 'Share details to help the real owner find this item quickly.';
            } else {
                formTitle.textContent = 'Report Lost Item';
                formSubtitle.textContent = 'Help us reconnect you with your belongings.';
            }
        }

        toggleButtons.forEach((button) => {
            button.addEventListener('click', function () {
                applyType(this.dataset.type);
            });
        });

        applyType(typeField.value || 'lost');

        if (blockSelect.dataset.oldBlock) {
            blockSelect.value = blockSelect.dataset.oldBlock;
        }
        populatePlaces();

        blockSelect.addEventListener('change', function () {
            placeSelect.dataset.oldPlace = '';
            populatePlaces();
        });

        placeSelect.addEventListener('change', updateHiddenLocation);

        imageInput.addEventListener('change', function () {
            const file = this.files && this.files[0];

            if (!file) {
                previewImage.removeAttribute('src');
                previewImage.style.display = 'none';
                previewText.style.display = 'block';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (event) {
                previewImage.src = event.target.result;
                previewImage.style.display = 'block';
                previewText.style.display = 'none';
            };

            reader.readAsDataURL(file);
        });

    })();
</script>
</body>
</html>
