@extends('layouts.app')

@section('title', 'Manage Articles | Admin')

@section('content')
<style>
    .articles-container {
        max-width: 1400px;
        margin: 0 auto;
    }

    .articles-header {
        background: white;
        padding: 24px;
        margin-bottom: 24px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }

    .header-flex h1 {
        font-size: 28px;
        font-weight: 800;
        color: #2c3e50;
        margin: 0 0 6px 0;
    }

    .header-flex p {
        font-size: 14px;
        color: #6c757d;
        margin: 0;
    }

    .articles-content {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .success-alert {
        background: #d1fae5;
        border-left: 4px solid #10b981;
        padding: 14px 16px;
        font-size: 14px;
        color: #065f46;
    }

    .articles-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .articles-table thead {
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }

    .articles-table th {
        padding: 14px 16px;
        text-align: left;
        font-weight: 700;
        color: #2c3e50;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .articles-table tbody tr {
        border-bottom: 1px solid #e9ecef;
        transition: background-color 0.2s ease;
    }

    .articles-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .articles-table td {
        padding: 12px 16px;
        vertical-align: middle;
    }

    .title-cell {
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 4px;
    }

    .desc-cell {
        font-size: 12px;
        color: #6c757d;
        line-height: 1.4;
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

    .date-cell {
        color: #6c757d;
        font-size: 13px;
    }

    .actions-cell {
        text-align: right;
    }

    .btn-group {
        display: flex;
        gap: 6px;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        background: white;
        color: #495057;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-sm:hover {
        border-color: #adb5bd;
        background-color: #f8f9fa;
    }

    .btn-delete {
        color: #dc3545;
        border-color: #f8d7da;
        background-color: #fff5f7;
    }

    .btn-delete:hover {
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }

    .pagination-wrapper {
        padding: 20px;
        text-align: center;
    }
</style>

<div class="articles-container">
    <section class="articles-header">
        <div class="header-flex">
            <div>
                <h1>Manage Articles</h1>
                <p>Create, update, and manage published news articles.</p>
            </div>
            <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">✍️ Write Article</a>
        </div>
    </section>

    <section class="articles-content">
        @if (session('success'))
            <div class="success-alert">
                {{ session('success') }}
            </div>
        @endif

        @if ($articles->count())
            <div class="table-wrapper">
                <table class="articles-table">
                    <thead>
                        <tr>
                            <th style="width: 45%;">Title</th>
                            <th style="width: 80px;">Status</th>
                            <th style="width: 100px;">Updated</th>
                            <th style="width: auto;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($articles as $article)
                            <tr>
                                <td>
                                    <div class="title-cell">{{ $article->title }}</div>
                                    <div class="desc-cell">{{ \Illuminate\Support\Str::limit($article->short_description, 80) }}</div>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ $article->status }}">
                                        {{ ucfirst($article->status) }}
                                    </span>
                                </td>
                                <td class="date-cell">{{ $article->updated_at->format('M d, Y') }}</td>
                                <td class="actions-cell">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.articles.show', $article) }}" class="btn-sm">👁 View</a>
                                        <a href="{{ route('admin.articles.edit', $article) }}" class="btn-sm">✏️ Edit</a>
                                        <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" style="display: inline;" onsubmit="return confirm('Delete this article?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-sm btn-delete">🗑 Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($articles->hasPages())
                <div class="pagination-wrapper">
                    {{ $articles->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <p style="font-size: 16px; margin-bottom: 6px;">📝 No articles yet</p>
                <p style="font-size: 13px;">Start creating published articles for your users.</p>
                <a href="{{ route('admin.articles.create') }}" class="btn btn-primary" style="margin-top: 12px; display: inline-block;">Write First Article</a>
            </div>
        @endif
    </section>
</div>
@endsection
