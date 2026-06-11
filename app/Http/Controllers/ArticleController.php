<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type'); // news or tech
        $articles = Article::when($type, fn($q, $v) => $q->where('type', $v))
            ->latest('published_at')
            ->with('author')
            ->paginate(10);

        return view('articles.index', compact('articles', 'type'));
    }

    public function create()
    {
        return view('articles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
            'type'  => 'required|in:news,tech',
        ]);

        $data['user_id'] = auth()->id();
        $data['published_at'] = now();

        Article::create($data);

        // Notify all members
        User::chunk(100, function ($users) use ($data) {
            foreach ($users as $user) {
                Notification::notify(
                    $user->id,
                    'new_article',
                    $data['title'],
                    'A new article has been published.',
                    route('articles.index')
                );
            }
        });

        return redirect()->route('articles.index')
            ->with('success', 'Article published.');
    }

    public function show(Article $article)
    {
        return view('articles.show', compact('article'));
    }

    public function details(Article $article)
    {
        $html = view('articles.modal-content', compact('article'))->render();

        return response()->json(['html' => $html]);
    }
}
