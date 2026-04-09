@extends('layouts.app')

@section('title', 'Manage About Content | Admin')

@section('content')
<style>
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .admin-table thead {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 2px solid #dee2e6;
    }

    .admin-table th {
        padding: 14px 16px;
        text-align: left;
        font-weight: 700;
        color: #2c3e50;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .admin-table tbody tr {
        border-bottom: 1px solid #e9ecef;
        transition: background-color 0.2s ease;
    }

    .admin-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .admin-table td {
        padding: 12px 16px;
        vertical-align: middle;
    }

    .content-title {
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 4px;
    }

    .content-preview {
        font-size: 12px;
        color: #6c757d;
        line-height: 1.4;
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

    .action-buttons {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .action-btn {
        padding: 6px 12px;
        font-size: 12px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: white;
        color: #495057;
        font-weight: 500;
    }

    .action-btn:hover {
        border-color: #adb5bd;
        background-color: #f8f9fa;
    }

    .action-btn-view {
        color: #0066cc;
    }

    .action-btn-edit {
        color: #0066cc;
    }

    .action-btn-delete {
        color: #dc3545;
        border-color: #f8d7da;
        background-color: #fff5f7;
    }

    .action-btn-delete:hover {
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .pagination-wrapper {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }
</style>

<div style="max-width: 1400px; margin: 0 auto;">
    <section class="card" style="margin-bottom: 24px; padding: 24px;">
        <div class="admin-header">
            <div>
                <h1 style="font-size: 28px; margin: 0 0 6px; color: #2c3e50; font-weight: 800;">Manage About Content</h1>
                <p style="margin: 0; font-size: 14px; color: #6c757d;">Create, update, publish, and remove public about page sections.</p>
            </div>
            <a href="{{ route('admin.about-contents.create') }}" class="btn btn-primary" style="white-space: nowrap;">✨ Create Content</a>
        </div>
    </section>

    <section class="card" style="padding: 0; overflow: hidden;">
        @if (session('success'))
            <div style="background: #d1fae5; border-left: 4px solid #10b981; padding: 14px 16px; margin: 0; font-size: 14px; color: #065f46;">
                {{ session('success') }}
            </div>
        @endif

        @if ($contents->count())
            <div style="overflow-x: auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Order</th>
                            <th style="width: 40%;">Title</th>
                            <th style="width: 120px;">Updated</th>
                            <th style="width: auto; text-align: right; padding-right: 24px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contents as $content)
                            <tr>
                                <td style="text-align: center; font-weight: 700; color: #0066cc; font-size: 16px;">{{ $content->sort_order }}</td>
                                <td>
                                    <div class="content-title">{{ $content->title }}</div>
                                    <div class="content-preview">{{ \Illuminate\Support\Str::limit($content->body, 85) }}</div>
                                </td>
                                <td style="color: #6c757d; font-size: 13px;">{{ $content->updated_at->format('M d, Y') }}</td>
                                <td style="text-align: right;">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.about-contents.show', $content) }}" class="action-btn action-btn-view" title="View content">👁 View</a>
                                        <a href="{{ route('admin.about-contents.edit', $content) }}" class="action-btn action-btn-edit" title="Edit content">✏️ Edit</a>
                                        <form method="POST" action="{{ route('admin.about-contents.destroy', $content) }}" onsubmit="return confirm('Delete this about content section?');" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn action-btn-delete" title="Delete content">🗑 Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($contents->hasPages())
                <div class="pagination-wrapper" style="padding: 20px;">
                    {{ $contents->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <p style="font-size: 16px; margin: 0;">📄 No about content has been created yet.</p>
                <p style="font-size: 13px; margin: 6px 0 0;">Get started by creating your first section.</p>
                <a href="{{ route('admin.about-contents.create') }}" class="btn btn-primary" style="margin-top: 12px; display: inline-block;">Create Content</a>
            </div>
        @endif
    </section>
</div>
@endsection
