<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::query()
            ->published()
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('articles.index', [
            'articles' => $articles,
        ]);
    }

    public function show(Article $article)
    {
        // Only allow viewing published articles for non-admin users
        if ($article->status !== 'published' && auth()->user()?->role !== 'admin') {
            abort(404);
        }

        return view('articles.show', [
            'article' => $article,
        ]);
    }
}
