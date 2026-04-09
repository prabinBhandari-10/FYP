@extends('layouts.app')

@section('title', 'About Content Details | Admin')

@section('content')
<style>
    .show-container {
        max-width: 900px;
        margin: 0 auto;
        display: grid;
        gap: 20px;
    }

    .show-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 8px;
    }

    .show-card {
        background: white;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        padding: 24px;
    }

    .meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .meta-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .meta-label {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
    }

    .meta-value {
        font-size: 15px;
        color: #2c3e50;
        font-weight: 500;
    }

    .divider {
        border: 0;
        border-top: 1px solid #e9ecef;
        margin: 24px 0;
    }

    .content-body {
        margin: 0;
        white-space: pre-wrap;
        line-height: 1.8;
        color: #495057;
        font-size: 14px;
    }

    .actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-active {
        background-color: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .badge-hidden {
        background-color: #f3f4f6;
        color: #4b5563;
        border: 1px solid #d1d5db;
    }
</style>

<div class="show-container">
    <div class="show-header">
        <div>
            <h1 style="font-size: 28px; margin: 0 0 6px; color: #2c3e50; font-weight: 800;">Content Details</h1>
            <p style="margin: 0; font-size: 14px; color: #6c757d;">Review how this section appears on the public about page</p>
        </div>
        <div class="actions">
            <a href="{{ route('admin.about-contents.edit', $content) }}" class="btn btn-primary">✏️ Edit</a>
            <a href="{{ route('admin.about-contents.index') }}" class="btn btn-outline">← Back</a>
        </div>
    </div>

    @if (session('success'))
        <div style="background: #d1fae5; border-left: 4px solid #10b981; padding: 14px 16px; border-radius: 6px; font-size: 14px; color: #065f46;">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="show-card">
        <div class="meta-grid">
            <div class="meta-item">
                <span class="meta-label">📝 Title</span>
                <span class="meta-value">{{ $content->title }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">🎨 Color Theme</span>
                <span class="meta-value" style="text-transform: capitalize;">{{ $content->color ?? 'blue' }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">🔢 Sort Order</span>
                <span class="meta-value">{{ $content->sort_order }}</span>
            </div>
        </div>

        <div class="meta-grid">
            <div class="meta-item">
                <span class="meta-label">📅 Created</span>
                <span class="meta-value">{{ $content->created_at->format('M d, Y') }}</span>
                <span style="font-size: 12px; color: #6c757d;">{{ $content->created_at->format('h:i A') }}</span>
            </div>
            <div class="meta-item">
                <span class="meta-label">🔄 Last Updated</span>
                <span class="meta-value">{{ $content->updated_at->format('M d, Y') }}</span>
                <span style="font-size: 12px; color: #6c757d;">{{ $content->updated_at->format('h:i A') }}</span>
            </div>
        </div>

        <hr class="divider">

        <div>
            <h2 style="font-size: 18px; margin: 0 0 14px; color: #2c3e50; font-weight: 700;">Content Preview</h2>
            <p class="content-body">{{ $content->body }}</p>
        </div>

        <hr class="divider">

        <div style="background: #f8f9fa; padding: 16px; border-radius: 6px; border-left: 4px solid #fbbf24;">
            <p style="margin: 0; font-size: 13px; color: #6c757d;">
                💡 <strong>Tip:</strong> This content will appear on the public about page. Hidden sections won't be visible to visitors.
            </p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.about-contents.destroy', $content) }}" onsubmit="return confirm('Are you sure you want to delete this section? This action cannot be undone.');" class="show-card" style="padding: 0; background: #fff5f7; border-color: #f8d7da;">
        @csrf
        @method('DELETE')
        <div style="padding: 20px;">
            <p style="margin: 0 0 14px; font-size: 14px; color: #721c24;">
                ⚠️ <strong>Delete this content section?</strong>
            </p>
            <button type="submit" class="btn" style="background: #dc3545; color: white; border: 1px solid #dc3545; padding: 10px 16px; font-weight: 600;">🗑 Delete Content</button>
        </div>
    </form>
</div>
@endsection
