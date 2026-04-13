<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::query()
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('admin.articles.index', [
            'articles' => $articles,
        ]);
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:5120'],
            'status' => ['required', 'in:draft,published'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('articles', 'public');
        }

        $validated['created_by'] = auth()->id();

        $article = Article::create($validated);

        // Notify all users if article is published
        if ($article->status === 'published') {
            try {
                $users = \App\Models\User::where('role', 'user')->get();
                
                foreach ($users as $user) {
                    // Create DB notification record
                    \App\Models\Notification::create([
                        'user_id' => $user->id,
                        'type' => 'new_article_published',
                        'title' => 'New Article Published',
                        'message' => "A new article '{$article->title}' has been published on the platform.",
                        'is_read' => false,
                        'is_email_sent' => false,
                    ]);
                    
                    // Send email notification
                    $user->notify(new \App\Notifications\NewArticlePublishedNotification($article));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to create user notifications for article publication: ' . $e->getMessage());
            }
        }

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Article created successfully.' . ($article->status === 'published' ? ' Users have been notified.' : ''));
    }

    public function show(Article $article)
    {
        return view('admin.articles.show', [
            'article' => $article,
        ]);
    }

    public function edit(Article $article)
    {
        return view('admin.articles.edit', [
            'article' => $article,
        ]);
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:5120'],
            'status' => ['required', 'in:draft,published'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('articles', 'public');
        }

        // Check if article is transitioning from draft to published
        $isTransitioningToPublished = $article->status === 'draft' && $validated['status'] === 'published';

        $article->update($validated);

        // Notify all users if article is transitioning to published
        if ($isTransitioningToPublished) {
            try {
                $users = \App\Models\User::where('role', 'user')->get();
                
                foreach ($users as $user) {
                    // Create DB notification record
                    \App\Models\Notification::create([
                        'user_id' => $user->id,
                        'type' => 'new_article_published',
                        'title' => 'New Article Published',
                        'message' => "A new article '{$article->title}' has been published on the platform.",
                        'is_read' => false,
                        'is_email_sent' => false,
                    ]);
                    
                    // Send email notification
                    $user->notify(new \App\Notifications\NewArticlePublishedNotification($article));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to create user notifications for article publication: ' . $e->getMessage());
            }
        }

        return redirect()
            ->route('admin.articles.show', $article)
            ->with('success', 'Article updated successfully.' . ($isTransitioningToPublished ? ' Users have been notified.' : ''));
    }

    public function destroy(Article $article)
    {
        $article->delete();

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Article deleted successfully.');
    }
}
