<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Article $article)
    {
        $data = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        Comment::create([
            'commentable_id'   => $article->id,
            'commentable_type' => Article::class,
            'user_id'           => auth()->id(),
            'body'              => $data['body'],
        ]);

        return redirect()->route('articles.show', $article)
            ->with('success', 'Comment posted.');
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== auth()->id() && ! auth()->user()->isAdmin()) {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }
}
