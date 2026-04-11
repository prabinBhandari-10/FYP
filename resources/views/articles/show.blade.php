@extends('layouts.app')

@section('title', $article->title)

@section('content')
<style>
    .article-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .article-header {
        background: linear-gradient(135deg, #ffffff 0%, #f4faff 100%);
        padding: 40px 24px;
        margin-bottom: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .article-title {
        font-size: 32px;
        font-weight: 800;
        color: #2c3e50;
        margin: 0 0 16px 0;
        line-height: 1.3;
    }

    .article-meta {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        font-size: 13px;
        color: #6c757d;
    }

    .article-content-wrapper {
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .article-image {
        width: 100%;
        height: auto;
        border-radius: 6px;
        margin-bottom: 30px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .article-description {
        background: #f0f7ff;
        padding: 20px;
        border-left: 4px solid #0066cc;
        border-radius: 4px;
        margin-bottom: 30px;
        font-size: 15px;
        line-height: 1.6;
        color: #2c3e50;
    }

    .article-body {
        font-size: 15px;
        line-height: 1.8;
        color: #2c3e50;
    }

    .article-body p {
        margin-bottom: 20px;
    }

    .article-body h2 {
        font-size: 22px;
        font-weight: 700;
        color: #2c3e50;
        margin: 30px 0 15px 0;
    }

    .article-body h3 {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
        margin: 25px 0 12px 0;
    }

    .article-body blockquote {
        border-left: 4px solid #0066cc;
        padding-left: 20px;
        margin: 20px 0;
        color: #666;
        font-style: italic;
    }

    .article-footer {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .article-footer a {
        padding: 10px 16px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-back {
        background: #f8f9fa;
        color: #2c3e50;
        border: 1px solid #dee2e6;
    }

    .btn-back:hover {
        background-color: #e9ecef;
    }
</style>

<div class="article-container">
    <section class="article-header">
        <h1 class="article-title">{{ $article->title }}</h1>
        <div class="article-meta">
            <span>📅 {{ $article->created_at->format('M d, Y') }}</span>
            <span>✍️ By {{ $article->author->name }}</span>
        </div>
    </section>

    <section class="article-content-wrapper">
        @if ($article->image)
            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="article-image">
        @endif

        <div class="article-description">
            <strong>Summary:</strong> {{ $article->short_description }}
        </div>

        <div class="article-body">
            {!! nl2br(e($article->content)) !!}
        </div>

        <div class="article-footer">
            <a href="{{ route('articles.index') }}" class="article-footer btn-back">← Back to Articles</a>
        </div>
    </section>
</div>
@endsection
