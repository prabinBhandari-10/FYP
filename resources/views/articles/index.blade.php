@extends('layouts.app')

@section('title', 'Articles & News')

@section('content')
<style>
    .articles-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .articles-header {
        background: linear-gradient(135deg, #ffffff 0%, #f4faff 100%);
        padding: 40px 24px;
        margin-bottom: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .articles-header h1 {
        font-size: 32px;
        font-weight: 800;
        color: #2c3e50;
        margin: 0 0 10px 0;
    }

    .articles-header p {
        font-size: 14px;
        color: #6c757d;
        margin: 0;
    }

    .articles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .article-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .article-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }

    .article-image {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #f0f7ff 0%, #e0efff 100%);
        object-fit: cover;
    }

    .article-content {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .article-title {
        font-size: 16px;
        font-weight: 700;
        color: #2c3e50;
        margin: 0 0 10px 0;
        line-height: 1.4;
    }

    .article-description {
        font-size: 13px;
        color: #6c757d;
        margin: 0 0 12px 0;
        line-height: 1.6;
        flex-grow: 1;
    }

    .article-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 12px;
        border-top: 1px solid #e9ecef;
        font-size: 12px;
        color: #999;
    }

    .article-date {
        font-size: 12px;
        color: #999;
    }

    .article-link {
        color: #0066cc;
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        transition: color 0.2s ease;
    }

    .article-link:hover {
        color: #0052a3;
        text-decoration: underline;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
    }

    .empty-state p {
        font-size: 16px;
        margin-bottom: 10px;
    }

    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 30px;
    }
</style>

<div class="articles-container">
    <section class="articles-header">
        <h1>📰 Articles & News</h1>
        <p>Stay updated with the latest news announcements about our Lost & Found system.</p>
    </section>

    @if ($articles->isEmpty())
        <div class="empty-state">
            <p style="font-size: 16px; margin: 0 0 10px 0;">📝 No articles available yet</p>
            <p style="font-size: 13px; margin: 0;">Check back soon for updates!</p>
        </div>
    @else
        <div class="articles-grid">
            @foreach ($articles as $article)
                <article class="article-card">
                    @if ($article->image)
                        <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="article-image">
                    @else
                        <div class="article-image" style="display: flex; align-items: center; justify-content: center; font-size: 48px;">📰</div>
                    @endif
                    <div class="article-content">
                        <h3 class="article-title">{{ $article->title }}</h3>
                        <p class="article-description">{{ $article->short_description }}</p>
                        <div class="article-meta">
                            <span class="article-date">{{ $article->created_at->format('M d, Y') }}</span>
                            <a href="{{ route('articles.show', $article) }}" class="article-link">Read More →</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        @if ($articles->hasPages())
            <div class="pagination-wrapper">
                {{ $articles->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
