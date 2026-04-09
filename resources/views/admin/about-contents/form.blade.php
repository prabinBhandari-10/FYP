@extends('layouts.app')

@section('title', $formTitle . ' | Admin')

@section('content')
<style>
    .form-container {
        max-width: 900px;
        margin: 0 auto;
        display: grid;
        gap: 20px;
    }

    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 8px;
    }

    .form-section {
        background: white;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        padding: 24px;
    }

    .form-group {
        display: grid;
        gap: 8px;
        margin-bottom: 20px;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
        font-size: 14px;
    }

    .form-input,
    .form-textarea {
        padding: 10px 14px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.2s ease;
    }

    .form-input:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #0066cc;
        box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
    }

    .form-textarea {
        resize: vertical;
        min-height: 180px;
        line-height: 1.5;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
    }

    .toggle-group {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 16px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }

    .toggle-checkbox {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: #0066cc;
    }

    .toggle-label {
        margin: 0;
        cursor: pointer;
        font-weight: 600;
        color: #2c3e50;
        flex: 1;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
    }

    .form-error {
        color: #dc3545;
        font-size: 12px;
        margin-top: 6px;
    }
</style>

<div class="form-container">
    <div class="form-header">
        <h1 style="font-size: 28px; margin: 0; color: #2c3e50; font-weight: 800;">{{ $formTitle }}</h1>
        <a href="{{ route('admin.about-contents.index') }}" class="btn btn-outline">← Back</a>
    </div>

    <form method="POST" action="{{ $formAction }}" class="form-section">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="form-group">
            <label class="form-label" for="title">📝 Section Title</label>
            <input class="form-input" id="title" name="title" type="text" required value="{{ old('title', $content->title) }}" placeholder="e.g., Our Mission, What We Provide">
            @error('title')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="body">📄 Content</label>
            <textarea class="form-textarea" id="body" name="body" required placeholder="Enter the section content. Use bullet points with • or line breaks for lists.">{{ old('body', $content->body) }}</textarea>
            @error('body')<div class="form-error">{{ $message }}</div>@enderror
            <small style="color: #6c757d; font-size: 12px; margin-top: 6px;">💡 Tip: Use line breaks to separate paragraphs and • for bullet points.</small>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label" for="sort_order">🔢 Sort Order</label>
                <input class="form-input" id="sort_order" name="sort_order" type="number" min="0" required value="{{ old('sort_order', $content->sort_order) }}" placeholder="0, 1, 2, 3...">
                @error('sort_order')<div class="form-error">{{ $message }}</div>@enderror
                <small style="color: #6c757d; font-size: 12px; margin-top: 6px;">Sections are displayed in ascending order</small>
            </div>

            <div class="form-group">
                <label class="form-label" for="color">🎨 Color Theme</label>
                <select class="form-input" id="color" name="color" required style="cursor: pointer;">
                    <option value="">Select a color...</option>
                    <option value="black" {{ old('color', $content->color) === 'black' ? 'selected' : '' }} style="background: #000; color: #fff;">● Black</option>
                    <option value="white" {{ old('color', $content->color) === 'white' ? 'selected' : '' }} style="background: #fff; color: #000;">● White</option>
                    <option value="blue" {{ old('color', $content->color) === 'blue' ? 'selected' : '' }} style="background: #3b82f6; color: #fff;">● Blue</option>
                    <option value="red" {{ old('color', $content->color) === 'red' ? 'selected' : '' }} style="background: #ef4444; color: #fff;">● Red</option>
                    <option value="green" {{ old('color', $content->color) === 'green' ? 'selected' : '' }} style="background: #10b981; color: #fff;">● Green</option>
                    <option value="yellow" {{ old('color', $content->color) === 'yellow' ? 'selected' : '' }} style="background: #fbbf24; color: #000;">● Yellow</option>
                    <option value="pink" {{ old('color', $content->color) === 'pink' ? 'selected' : '' }} style="background: #ec4899; color: #fff;">● Pink</option>
                    <option value="purple" {{ old('color', $content->color) === 'purple' ? 'selected' : '' }} style="background: #a855f7; color: #fff;">● Purple</option>
                    <option value="brown" {{ old('color', $content->color) === 'brown' ? 'selected' : '' }} style="background: #92400e; color: #fff;">● Brown</option>
                    <option value="gray" {{ old('color', $content->color) === 'gray' ? 'selected' : '' }} style="background: #6b7280; color: #fff;">● Gray</option>
                    <option value="silver" {{ old('color', $content->color) === 'silver' ? 'selected' : '' }} style="background: #d1d5db; color: #000;">● Silver</option>
                    <option value="gold" {{ old('color', $content->color) === 'gold' ? 'selected' : '' }} style="background: #d97706; color: #fff;">● Gold</option>
                    <option value="multicolor" {{ old('color', $content->color) === 'multicolor' ? 'selected' : '' }}>🌈 Multicolor</option>
                    <option value="other" {{ old('color', $content->color) === 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('color')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">📌 Publication Status</label>
            <div class="toggle-group">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $content->is_active)) class="toggle-checkbox">
                <label for="is_active" class="toggle-label">Publish this section</label>
            </div>
            @error('is_active')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.about-contents.index') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
        </div>
    </form>
</div>
@endsection
