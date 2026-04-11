@extends('layouts.app')

@section('title', 'Edit Article | Admin')

@section('content')
<style>
    .form-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .form-header {
        background: white;
        padding: 24px;
        margin-bottom: 24px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .form-header h1 {
        font-size: 28px;
        font-weight: 800;
        color: #2c3e50;
        margin: 0;
    }

    .form-content {
        background: white;
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #2c3e50;
        font-size: 14px;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
    }

    .form-group textarea {
        min-height: 200px;
        resize: vertical;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: #0066cc;
        box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
    }

    .form-error {
        color: #dc3545;
        font-size: 13px;
        margin-top: 6px;
    }

    .form-hint {
        color: #6c757d;
        font-size: 13px;
        margin-top: 6px;
    }

    .status-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .radio-option {
        display: flex;
        align-items: center;
        padding: 12px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .radio-option input {
        width: auto;
        margin-right: 10px;
    }

    .radio-option input:checked ~ label {
        font-weight: 700;
    }

    .radio-option:has(input:checked) {
        border-color: #0066cc;
        background-color: #f0f7ff;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
        padding-top: 24px;
        border-top: 1px solid #dee2e6;
    }

    .form-actions button,
    .form-actions a {
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .image-preview {
        margin-top: 12px;
    }

    .image-preview img {
        max-width: 200px;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
</style>

<div class="form-container">
    <section class="form-header">
        <h1>✏️ Edit Article: {{ $article->title }}</h1>
    </section>

    <section class="form-content">
        @if ($errors->any())
            <div style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 14px 16px; border-radius: 6px; margin-bottom: 20px;">
                <strong>Please fix the following errors:</strong>
                <ul style="padding-left: 20px; margin-top: 8px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.articles.update', $article) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title">Title *</label>
                <input type="text" id="title" name="title" value="{{ old('title', $article->title) }}" required>
                @error('title')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="short_description">Short Description *</label>
                <textarea id="short_description" name="short_description" required>{{ old('short_description', $article->short_description) }}</textarea>
                <div class="form-hint">This will appear on the articles list</div>
                @error('short_description')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="content">Full Content *</label>
                <textarea id="content" name="content" required>{{ old('content', $article->content) }}</textarea>
                <div class="form-hint">Detailed article content for the full article page</div>
                @error('content')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label for="image">Featured Image (Optional)</label>
                <input type="file" id="image" name="image" accept="image/*">
                <div class="form-hint">JPG, PNG, or GIF (Max 5MB)</div>
                @if ($article->image)
                    <div class="image-preview">
                        <p style="font-size: 13px; color: #6c757d; margin-bottom: 8px;">Current image:</p>
                        <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}">
                    </div>
                @endif
                @error('image')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label>Status *</label>
                <div class="status-group">
                    <label class="radio-option">
                        <input type="radio" name="status" value="draft" {{ old('status', $article->status) === 'draft' ? 'checked' : '' }}>
                        <span>Draft</span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="status" value="published" {{ old('status', $article->status) === 'published' ? 'checked' : '' }}>
                        <span>Published</span>
                    </label>
                </div>
                @error('status')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Save Changes</button>
                <a href="{{ route('admin.articles.show', $article) }}" class="btn btn-outline">← Back to Article</a>
            </div>
        </form>
    </section>
</div>
@endsection
