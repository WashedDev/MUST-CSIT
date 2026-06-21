<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class AdminArticleController extends Controller
{
    public function pending()
    {
        $articles = Article::where('status', 'published')
            ->where('approved', false)
            ->with('author')
            ->latest()
            ->paginate(20);

        return view('admin.articles.pending', compact('articles'));
    }

    public function approve(Article $article)
    {
        $article->forceFill([
            'approved'     => true,
            'reviewed_by'  => auth()->id(),
            'reviewed_at'  => now(),
        ])->save();

        User::chunk(100, function ($users) use ($article) {
            foreach ($users as $user) {
                Notification::notify(
                    $user->id,
                    'new_article',
                    $article->title,
                    'A new article has been published.',
                    route('articles.show', $article)
                );
            }
        });

        return back()->with('success', 'Article approved and published.');
    }

    public function reject(Request $request, Article $article)
    {
        $data = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $article->forceFill([
            'status'       => 'draft',
            'approved'     => false,
            'reviewed_by'  => auth()->id(),
            'reviewed_at'  => now(),
        ])->save();

        if ($article->author) {
            Notification::notify(
                $article->author->id,
                'article_rejected',
                $article->title,
                $data['reason'] ?? 'Your article was not approved.',
                route('articles.edit', $article)
            );
        }

        return back()->with('success', 'Article rejected.');
    }
}
