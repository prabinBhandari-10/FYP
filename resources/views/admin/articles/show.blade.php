@extends('layouts.app')

@section('title', $article->title . ' | Admin')

@section('content')
<style>
    .view-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .view-header {
        background: white;
        padding: 24px;
        margin-bottom: 24px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .view-header h1 {
        font-size: 28px;
        font-weight: 800;
        color: #2c3e50;
        margin: 0;
    }

    .header-meta {
        display: flex;
        gap: 16px;
        margin-top: 12px;
        flex-wrap: wrap;
        font-size: 13px;
        color: #6c757d;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-published {
        background-color: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .status-draft {
        background-color: #f3f4f6;
        color: #4b5563;
        border: 1px solid #d1d5db;
    }

    .view-content {
        background: white;
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .article-image {
        margin-bottom: 24px;
    }

    .article-image img {
        max-width: 100%;
        height: auto;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .article-meta {
        background: #f8f9fa;
        padding: 16px;
        border-radius: 6px;
        margin-bottom: 24px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
    }

    .meta-item {
        font-size: 13px;
    }

    .meta-label {
        color: #6c757d;
        font-weight: 600;
    }

    .meta-value {
        color: #2c3e50;
        margin-top: 4px;
    }

    .article-description {
        background: #f0f7ff;
        padding: 16px;
        border-left: 4px solid #0066cc;
        border-radius: 4px;
        margin-bottom: 24px;
        font-size: 14px;
        line-height: 1.6;
    }

    .article-content {
        font-size: 14px;
        line-height: 1.8;
        color: #2c3e50;
    }

    .article-content p {
        margin-bottom: 16px;
    }

    .article-actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
        padding-top: 24px;
        border-top: 1px solid #dee2e6;
    }

    .article-actions a,
    .article-actions button {
        padding: 10px 16px;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
    }
</style>

<div class="view-container">
    <section class="view-header">
        <h1>{{ $article->title }}</h1>
        <div class="header-meta">
            <span class="status-badge status-{{ $article->status }}">{{ ucfirst($article->status) }}</span>
            <span>📅 {{ $article->created_at->format('M d, Y') }}</span>
            <span>✏️ Updated {{ $article->updated_at->format('M d, Y') }}</span>
        </div>
    </section>

    <section class="view-content">
        @if ($article->image)
            <div class="article-image">
                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}">
            </div>
        @endif

        <div class="article-meta">
            <div class="meta-item">
                <div class="meta-label">Written by</div>
                <div class="meta-value">{{ $article->author->name }}</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Status</div>
                <div class="meta-value" style="text-transform: capitalize;">{{ $article->status }}</div>
            </div>
            <div class="meta-item">
                <div class="meta-label">Created</div>
                <div class="meta-value">{{ $article->created_at->format('M d, Y H:i') }}</div>
            </div>
        </div>

        <div class="article-description">
            <strong>Summary:</strong> {{ $article->short_description }}
        </div>

        <div class="article-content">
            {!! nl2br(e($article->content)) !!}
        </div>

        <div class="article-actions">
            <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-primary">✏️ Edit Article</a>
            <a href="{{ route('admin.articles.index') }}" class="btn btn-outline">← Back to Articles</a>
            <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" style="display: inline;" onsubmit="return confirm('Delete this article? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-ghost" style="color: #dc3545;">🗑 Delete</button>
            </form>
        </div>
    </section>
</div>
@endsection
