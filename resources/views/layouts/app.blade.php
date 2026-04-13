<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Lost & Found')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-page: #f4f8ff;
            --bg-elevated: #ffffff;
            --bg-soft: #f6f9ff;
            --text-main: #10213f;
            --text-muted: #4f6285;
            --text-soft: #7184a8;
            --line: #d9e4f6;
            --primary: #57b7e8;
            --primary-strong: #2f97cf;
            --primary-tint: #e6f7ff;
            --accent: #24b8df;
            --danger: #d0355f;
            --success: #0f9b6f;
            --radius-lg: 20px;
            --radius-md: 16px;
            --radius-sm: 12px;
            --shadow-soft: 0 10px 30px rgba(16, 33, 63, 0.07);
            --shadow-hover: 0 16px 34px rgba(16, 33, 63, 0.12);
        }

        /* Dark Mode Theme */
        body.dark-mode {
            --bg-page: #0f1419;
            --bg-elevated: #1a1f2e;
            --bg-soft: #252d3d;
            --text-main: #e0e6f2;
            --text-muted: #a0aac4;
            --text-soft: #7784a0;
            --line: #3d4556;
            --primary: #57b7e8;
            --primary-strong: #6ec7ef;
            --primary-tint: #1a3a4a;
            --accent: #24b8df;
            --danger: #e85d7d;
            --success: #4fd992;
            --shadow-soft: 0 10px 30px rgba(0, 0, 0, 0.3);
            --shadow-hover: 0 16px 34px rgba(0, 0, 0, 0.4);
        }

        body.dark-mode .site-header {
            background: rgba(26, 31, 46, 0.96);
            border-bottom-color: rgba(61, 69, 86, 0.9);
        }

        body.dark-mode .brand-logo {
            filter: brightness(1.2) invert(0.05);
        }

        body.dark-mode input.form-input,
        body.dark-mode textarea.form-input,
        body.dark-mode select.form-input {
            background: var(--bg-elevated);
            color: var(--text-main);
            border-color: var(--line);
        }

        body.dark-mode input.form-input::placeholder,
        body.dark-mode textarea.form-input::placeholder {
            color: var(--text-soft);
        }

        body.dark-mode input.form-input:focus,
        body.dark-mode textarea.form-input:focus,
        body.dark-mode select.form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(87, 183, 232, 0.24);
            background: var(--bg-elevated);
        }

        body.dark-mode .card {
            background: var(--bg-elevated);
            border-color: var(--line);
        }

        body.dark-mode .btn-primary {
            background: linear-gradient(135deg, #57b7e8, #2f97cf);
            box-shadow: 0 10px 20px rgba(87, 183, 232, 0.18);
        }

        body.dark-mode .btn-primary:hover {
            box-shadow: 0 14px 24px rgba(87, 183, 232, 0.24);
        }

        body.dark-mode .btn-outline {
            color: var(--primary);
            border-color: #3d4556;
            background: rgba(87, 183, 232, 0.08);
        }

        body.dark-mode .btn-outline:hover {
            border-color: #5c6d85;
            background: rgba(87, 183, 232, 0.14);
        }

        body.dark-mode .alert-success {
            background: rgba(79, 217, 146, 0.1);
            color: #4fd992;
            border-color: rgba(79, 217, 146, 0.3);
        }

        body.dark-mode .alert-error {
            background: rgba(232, 93, 125, 0.1);
            color: #e85d7d;
            border-color: rgba(232, 93, 125, 0.3);
        }

        body.dark-mode .auth-card {
            background: rgba(26, 31, 46, 0.8);
            border-color: rgba(61, 69, 86, 0.9);
        }

        body.dark-mode .badge-lost {
            color: #ff9aaf;
            background: rgba(232, 93, 125, 0.12);
            border: 1px solid rgba(232, 93, 125, 0.3);
        }

        body.dark-mode .badge-found {
            color: #72e0f0;
            background: rgba(36, 184, 223, 0.12);
            border: 1px solid rgba(36, 184, 223, 0.3);
        }

        body.dark-mode .badge-neutral {
            color: #a0aac4;
            background: rgba(160, 170, 196, 0.08);
            border: 1px solid rgba(160, 170, 196, 0.2);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background:
                radial-gradient(900px 340px at -10% -10%, rgba(87, 183, 232, 0.16), transparent 60%),
                radial-gradient(760px 300px at 110% 8%, rgba(36, 184, 223, 0.10), transparent 62%),
                var(--bg-page);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1, h2, h3, h4 {
            font-family: 'Sora', sans-serif;
            letter-spacing: -0.02em;
            color: var(--text-main);
        }

        .site-header {
            position: sticky;
            top: 0;
            z-index: 60;
            border-bottom: 1px solid rgba(217, 228, 246, 0.9);
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 8px 22px rgba(16, 33, 63, 0.06);
        }

        .site-nav {
            width: min(1160px, 100% - 32px);
            margin: 0 auto;
            min-height: 74px;
            display: grid;
            grid-template-columns: auto minmax(0, 1fr) auto;
            align-items: center;
            gap: 18px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--text-main);
            font-family: 'Sora', sans-serif;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .brand-logo {
            height: 40px;
            width: auto;
            display: block;
            border-radius: 10px;
            transition: transform 0.2s ease, filter 0.2s ease, box-shadow 0.2s ease;
        }

        .brand:hover .brand-logo {
            transform: scale(1.04);
            filter: brightness(1.05);
            box-shadow: 0 8px 22px rgba(63, 81, 217, 0.28), 0 0 0 2px rgba(84, 231, 204, 0.26);
            animation: logoPulse 1.1s ease-in-out infinite alternate;
        }

        .brand:focus-visible .brand-logo,
        .brand:focus-within .brand-logo {
            transform: scale(1.04);
            filter: brightness(1.05);
            box-shadow: 0 8px 22px rgba(63, 81, 217, 0.28), 0 0 0 2px rgba(84, 231, 204, 0.26);
        }

        @keyframes logoPulse {
            from {
                box-shadow: 0 8px 20px rgba(63, 81, 217, 0.2), 0 0 0 1px rgba(84, 231, 204, 0.22);
            }
            to {
                box-shadow: 0 10px 26px rgba(63, 81, 217, 0.34), 0 0 0 3px rgba(84, 231, 204, 0.32);
            }
        }

        .brand-name {
            line-height: 1;
        }

        .brand span {
            color: var(--primary);
        }

        .nav-links {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            flex-wrap: nowrap;
            min-width: 0;
            overflow-x: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .nav-links::-webkit-scrollbar {
            display: none;
        }

        .nav-link {
            padding: 8px 12px;
            border-radius: 999px;
            text-decoration: none;
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 700;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .nav-link:hover {
            color: var(--primary-strong);
            background: var(--primary-tint);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: nowrap;
            justify-content: flex-end;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid transparent;
            border-radius: 999px;
            padding: 11px 18px;
            font-size: 14px;
            font-weight: 800;
            line-height: 1;
            text-decoration: none;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
            font-family: 'Sora', sans-serif;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            color: #fff;
            background: linear-gradient(135deg, #6ec7ef, #57b7e8);
            box-shadow: 0 10px 20px rgba(87, 183, 232, 0.24);
        }

        .btn-primary:hover {
            box-shadow: 0 14px 24px rgba(87, 183, 232, 0.30);
        }

        .btn-outline {
            color: var(--primary-strong);
            border-color: #c6d4ef;
            background: #fff;
        }

        .btn-outline:hover {
            border-color: #9db1db;
            background: #f8fbff;
        }

        .btn-ghost {
            color: var(--text-muted);
            border-color: transparent;
            background: transparent;
        }

        .btn-ghost:hover {
            color: var(--primary-strong);
            background: var(--primary-tint);
        }

        .main-content {
            width: min(1160px, 100% - 32px);
            margin: 26px auto 0;
            flex: 1;
        }

        .page-title {
            font-size: clamp(28px, 3.5vw, 40px);
            margin-bottom: 12px;
        }

        .page-subtitle {
            color: var(--text-muted);
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .card {
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            background: var(--bg-elevated);
            box-shadow: var(--shadow-soft);
            padding: 24px;
        }

        .card-soft {
            background: var(--bg-soft);
        }

        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .section-head h2 {
            font-size: 24px;
        }

        .section-note {
            color: var(--text-soft);
            font-size: 14px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .grid-4 {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 700;
            color: var(--text-main);
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            border: 1px solid #cdd9ef;
            border-radius: 14px;
            background: #fff;
            color: var(--text-main);
            font: inherit;
            font-size: 14px;
            padding: 12px 14px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .form-textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-input::placeholder,
        .form-textarea::placeholder {
            color: #8ea0bf;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(63, 81, 217, 0.14);
            background: #fcfdff;
        }

        .form-row {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .alert {
            margin-bottom: 16px;
            padding: 12px 14px;
            border-radius: 14px;
            font-size: 14px;
            line-height: 1.5;
            border: 1px solid transparent;
        }

        .alert-success {
            background: #effcf6;
            color: #0f7a57;
            border-color: #bfeeda;
        }

        .alert-error {
            background: #fff4f6;
            color: #a61f49;
            border-color: #f4bfcd;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 5px 10px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .badge-lost {
            color: #b8324f;
            background: #ffeef2;
            border: 1px solid #f9ccda;
        }

        .badge-found {
            color: #0e7f90;
            background: #ebfbff;
            border: 1px solid #c5eef7;
        }

        .badge-neutral {
            color: #486083;
            background: #f1f6ff;
            border: 1px solid #d2def2;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
        }

        .stat-card {
            border-radius: var(--radius-md);
            border: 1px solid var(--line);
            padding: 18px;
            background: #fff;
        }

        .stat-value {
            font-family: 'Sora', sans-serif;
            font-size: 28px;
            font-weight: 800;
            color: var(--text-main);
        }

        .stat-label {
            margin-top: 6px;
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 600;
        }

        .table-wrap {
            overflow-x: auto;
        }

        nav[aria-label="Pagination Navigation"] {
            margin-top: 6px;
        }

        nav[aria-label="Pagination Navigation"] .w-5,
        nav[aria-label="Pagination Navigation"] .h-5 {
            width: 20px;
            height: 20px;
            display: block;
            flex: 0 0 20px;
        }

        nav[aria-label="Pagination Navigation"] .sm\:hidden {
            display: flex;
            gap: 8px;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        nav[aria-label="Pagination Navigation"] .sm\:flex-1 {
            display: none;
        }

        @media (min-width: 640px) {
            nav[aria-label="Pagination Navigation"] .sm\:hidden {
                display: none;
            }

            nav[aria-label="Pagination Navigation"] .sm\:flex-1 {
                display: flex;
                flex: 1 1 0%;
                align-items: center;
                justify-content: space-between;
                gap: 8px;
                flex-wrap: wrap;
            }
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 11px 10px;
            border-bottom: 1px solid var(--line);
            text-align: left;
            vertical-align: top;
            font-size: 14px;
        }

        th {
            font-weight: 800;
            color: var(--text-main);
        }

        td {
            color: var(--text-muted);
        }

        .auth-wrap {
            min-height: calc(100vh - 250px);
            display: grid;
            place-items: center;
            padding: 18px 0 30px;
        }

        .auth-card {
            width: min(520px, 100%);
            border: 1px solid #d4e1f6;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.93);
            box-shadow: 0 18px 40px rgba(16, 33, 63, 0.1);
            padding: 30px;
        }

        .split-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.4fr) minmax(0, 0.9fr);
            gap: 24px;
            align-items: start;
        }

        .sticky-panel {
            position: sticky;
            top: 95px;
            align-self: start;
            max-height: calc(100vh - 110px);
            overflow-y: auto;
            overscroll-behavior: contain;
        }

        .empty-state {
            text-align: center;
            padding: 46px 24px;
            color: var(--text-soft);
            border: 1px dashed #bfd0ee;
            border-radius: var(--radius-lg);
            background: #fafcff;
        }

        .site-footer-quick {
            margin-top: 40px;
            border-top: 0;
            background: linear-gradient(180deg, #dff3ff 0%, #cfefff 100%);
            color: #16324f;
            padding: 30px 18px 18px;
        }

        .site-footer-inner {
            width: min(1160px, 100%);
            margin: 0 auto;
            display: grid;
            grid-template-columns: minmax(260px, 1fr) minmax(0, 2fr);
            gap: 26px;
        }

        .site-footer-brand h3 {
            margin: 0 0 10px;
            font-size: 26px;
            color: #16324f;
        }

        .site-footer-brand p {
            margin: 0;
            color: #35597a;
            line-height: 1.6;
            font-size: 14px;
        }

        .site-footer-links-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 18px;
        }

        .site-footer-col h4 {
            margin: 0 0 8px;
            font-size: 16px;
            color: #16324f;
        }

        .site-footer-col a {
            display: block;
            margin-bottom: 8px;
            text-decoration: none;
            color: #35597a;
            font-size: 14px;
            font-weight: 600;
        }

        .site-footer-col a:hover {
            color: #0f2942;
        }

        .site-footer-bottom {
            width: min(1160px, 100%);
            margin: 20px auto 0;
            padding-top: 14px;
            border-top: 1px solid rgba(22, 50, 79, 0.14);
            color: #35597a;
            font-size: 13px;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .social-links {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-top: 12px;
        }

        .social-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.7);
            color: #1a4f7a;
            text-decoration: none;
            font-size: 20px;
            transition: all 0.2s ease;
        }

        .social-link:hover {
            background: #7cc8ee;
            color: #0f2942;
            transform: translateY(-2px);
        }

        .site-footer-updated p {
            color: #35597a;
            margin-bottom: 12px;
            line-height: 1.6;
        }

        .site-footer-subscribe {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .site-footer-subscribe input {
            flex: 1;
            min-width: 0;
            border: 1px solid rgba(22, 50, 79, 0.16);
            border-radius: 10px;
            padding: 11px 12px;
            background: rgba(255, 255, 255, 0.82);
            color: #16324f;
            outline: none;
        }

        .site-footer-subscribe input::placeholder {
            color: #66809c;
        }

        .site-footer-subscribe .btn-primary {
            background: linear-gradient(135deg, #7cc8ee 0%, #57b7e8 100%);
            color: #10324f;
            box-shadow: none;
            border: 1px solid rgba(22, 50, 79, 0.1);
        }

        .site-footer-subscribe .btn-primary:hover {
            background: linear-gradient(135deg, #8fd4f3 0%, #66c2ee 100%);
            color: #0f2942;
            box-shadow: none;
        }

        .welcome-toast {
            position: fixed;
            top: 88px;
            right: 18px;
            z-index: 1200;
            max-width: min(420px, calc(100vw - 36px));
            background: #ffffff;
            border: 1px solid #d6e2f5;
            box-shadow: var(--shadow-soft);
            border-radius: 14px;
            padding: 12px 14px;
            transform: translateY(-10px);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.24s ease, transform 0.24s ease;
        }

        .welcome-toast.is-visible {
            transform: translateY(0);
            opacity: 1;
            pointer-events: auto;
        }

        .welcome-toast h4 {
            margin: 0 0 4px;
            font-size: 16px;
        }

        .welcome-toast p {
            margin: 0;
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .welcome-toast-close {
            position: absolute;
            top: 8px;
            right: 10px;
            background: transparent;
            border: 0;
            color: var(--text-soft);
            font-size: 18px;
            line-height: 1;
            cursor: pointer;
        }

        .welcome-toast-close:hover {
            color: var(--text-main);
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 280px minmax(0, 1.4fr) minmax(0, 320px);
            gap: 20px;
            margin-bottom: 20px;
        }

        @media (max-width: 1024px) {
            .grid-4 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .split-layout {
                grid-template-columns: 1fr;
            }

            .sticky-panel {
                position: static;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
        }

        @media (max-width: 760px) {
            .site-nav {
                width: calc(100% - 20px);
                padding: 10px 0;
                align-items: flex-start;
                flex-direction: column;
            }

            .nav-links,
            .nav-actions {
                width: 100%;
                justify-content: flex-start;
                flex-wrap: wrap;
                overflow-x: visible;
            }

            .nav-link {
                white-space: normal;
            }

            .main-content {
                width: calc(100% - 20px);
            }

            .form-row,
            .grid-2,
            .grid-3,
            .grid-4,
            .site-footer-inner,
            .site-footer-links-grid {
                grid-template-columns: 1fr;
            }

            .site-footer-bottom,
            .site-footer-subscribe {
                flex-direction: column;
                align-items: stretch;
            }

            .auth-card {
                padding: 24px 18px;
            }

            .nav-links,
            .nav-actions {
                width: 100%;
            }

            .brand {
                gap: 8px;
            }

            .brand-logo {
                height: 34px;
            }

            .brand-name {
                font-size: 20px;
            }
        }

        @media (max-width: 420px) {
            .brand-name {
                display: none;
            }
        }
    </style>

    <!-- Quill Rich Text Editor -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
</head>
<body>
    @php
        $preferAdminGuard = request()->routeIs('admin.*');
        $activeUser = $preferAdminGuard
            ? (\Illuminate\Support\Facades\Auth::guard('admin')->user() ?? \Illuminate\Support\Facades\Auth::guard('web')->user())
            : (\Illuminate\Support\Facades\Auth::guard('web')->user() ?? \Illuminate\Support\Facades\Auth::guard('admin')->user());
        $isAuthenticated = (bool) $activeUser;
        $isAdmin = $activeUser?->role === 'admin';
        $homeRoute = $isAdmin ? route('admin.home') : route('home');
    @endphp

    <header class="site-header">
        <nav class="site-nav" aria-label="Main">
            <a href="{{ $homeRoute }}" class="brand" aria-label="Lost and Found Home">
                <img src="{{ asset('images/logo.png') }}" alt="Lost and Found logo" class="brand-logo">
                <span class="brand-name">Lost <span>&amp;</span> Found</span>
            </a>

            <div class="nav-links">
                <a href="{{ $homeRoute }}" class="nav-link">Home</a>
                <a href="{{ route('items.index') }}" class="nav-link">Browse</a>
                @if (! $isAdmin)
                    <a href="{{ route('reports.track.form') }}" class="nav-link">Track Report</a>
                @endif
                @if (! $isAdmin)
                    <a href="{{ route('about') }}" class="nav-link">About</a>
                    <a href="{{ route('contact') }}" class="nav-link">Contact</a>
                @endif
                @if ($isAuthenticated)
                    @if (! $isAdmin)
                        <a href="{{ route('reports.lost.create') }}" class="nav-link">Report Lost</a>
                        <a href="{{ route('reports.found.create') }}" class="nav-link">Report Found</a>
                    @else
                        <a href="{{ route('admin.users.index') }}" class="nav-link">Manage Users</a>
                        <a href="{{ route('admin.reports.index') }}" class="nav-link">Manage Reports</a>
                    @endif
                @endif
            </div>

            <div class="nav-actions">
                <!-- Theme Toggle Button -->
                <button id="theme-toggle" class="btn btn-ghost" style="padding: 8px 12px; border-radius: 999px; font-size: 18px;" title="Toggle dark mode" aria-label="Toggle dark mode">
                    <span id="theme-icon">🌙</span>
                </button>

                @if ($isAuthenticated)
                    <a href="{{ route('chat.index') }}" class="btn btn-ghost" title="Chats">Chats</a>
                    @if (! $isAdmin)
                        <a href="{{ route('notifications.index') }}" class="btn btn-ghost" style="position: relative; display: inline-flex; align-items: center; justify-content: center;" title="Notifications">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" xmlns="http://www.w3.org/2000/svg" style="display: inline-block;">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                            </svg>
                            @php
                                $unreadCount = 0;
                                try {
                                    $unreadCount = $activeUser->notifications()->where('is_read', false)->count();
                                } catch (\Exception $e) {
                                    // Notifications table may not exist yet
                                }
                            @endphp
                            @if ($unreadCount > 0)
                                <span style="position: absolute; top: -8px; right: -8px; background: var(--danger); color: white; border-radius: 999px; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 800;">
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('profile') }}" class="btn btn-outline">Profile</a>
                    @else
                        <a href="{{ route('admin.notifications.index') }}" class="btn btn-ghost" title="Notifications">Notifications</a>
                        <a href="{{ route('admin.payments.index') }}" class="btn btn-ghost" title="Payments">Payments</a>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">Dashboard</a>
                    @endif
                    <form action="{{ $isAdmin ? route('admin.logout') : route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn btn-ghost">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Create Account</a>
                @endif
            </div>
        </nav>
    </header>

    <main class="main-content">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @if ($errors->any() && ! request()->routeIs('login') && ! request()->routeIs('register'))
            <div class="alert alert-error">
                <ul style="padding-left: 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <div id="firstVisitWelcome" class="welcome-toast" role="status" aria-live="polite" aria-atomic="true" style="display: none;">
        <button type="button" class="welcome-toast-close" aria-label="Close welcome message" onclick="closeWelcomeToast()">&times;</button>
        <h4>Welcome to FYP Lost and Found System</h4>
        <p>Report, browse, and reconnect lost items quickly and safely.</p>
    </div>

    @include('partials.site-footer')

    <script>
        function closeWelcomeToast() {
            const toast = document.getElementById('firstVisitWelcome');
            if (!toast) {
                return;
            }

            toast.classList.remove('is-visible');
            window.setTimeout(() => {
                toast.style.display = 'none';
            }, 240);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const isHome = @json(request()->routeIs('home'));
            if (!isHome) {
                return;
            }

            const key = 'lf_welcome_seen';
            if (sessionStorage.getItem(key)) {
                return;
            }

            const toast = document.getElementById('firstVisitWelcome');
            if (!toast) {
                return;
            }

            toast.style.display = 'block';
            window.requestAnimationFrame(() => {
                toast.classList.add('is-visible');
            });

            sessionStorage.setItem(key, '1');
            window.setTimeout(closeWelcomeToast, 3600);
        });
    </script>

    <!-- Quill Rich Text Editor JS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        // Initialize Quill editors
        document.addEventListener('DOMContentLoaded', function() {
            const editorElements = document.querySelectorAll('[data-quill-editor]');
            editorElements.forEach(element => {
                const hiddenInput = element.nextElementSibling;
                const toolbar = element.dataset.quillToolbar || '#toolbar';
                
                const quill = new Quill(element, {
                    theme: 'snow',
                    placeholder: element.dataset.quillPlaceholder || 'Enter content here...',
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline'],
                            [{ 'color': [] }],
                            [{ 'background': [] }]
                        ]
                    }
                });

                // Sync content with hidden input
                quill.on('text-change', function() {
                    hiddenInput.value = quill.root.innerHTML;
                });

                // Load existing content if available
                if (hiddenInput.value) {
                    quill.root.innerHTML = hiddenInput.value;
                }
            });
        });
    </script>

    <!-- Theme Toggle Functionality -->
    <script>
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        const body = document.body;

        // Load saved theme preference
        function loadTheme() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            
            if (savedTheme === 'dark') {
                body.classList.add('dark-mode');
                themeIcon.textContent = '☀️';
            } else {
                body.classList.remove('dark-mode');
                themeIcon.textContent = '🌙';
            }
        }

        // Toggle theme
        function toggleTheme() {
            if (body.classList.contains('dark-mode')) {
                body.classList.remove('dark-mode');
                localStorage.setItem('theme', 'light');
                themeIcon.textContent = '🌙';
            } else {
                body.classList.add('dark-mode');
                localStorage.setItem('theme', 'dark');
                themeIcon.textContent = '☀️';
            }
        }

        // Check for system preference on first visit
        function initializeTheme() {
            const savedTheme = localStorage.getItem('theme');
            
            if (!savedTheme) {
                // No saved preference - check system preference
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    body.classList.add('dark-mode');
                    localStorage.setItem('theme', 'dark');
                    themeIcon.textContent = '☀️';
                } else {
                    body.classList.remove('dark-mode');
                    localStorage.setItem('theme', 'light');
                    themeIcon.textContent = '🌙';
                }
            } else {
                loadTheme();
            }
        }

        // Event listeners
        if (themeToggle) {
            themeToggle.addEventListener('click', toggleTheme);
        }

        // Initialize on page load
        window.addEventListener('DOMContentLoaded', initializeTheme);
        
        // Also run immediately in case DOM is already loaded
        initializeTheme();
    </script>
</body>
</html>
