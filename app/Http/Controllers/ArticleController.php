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
        $type = $request->query('type');
        $from = $request->query('from');
        $to = $request->query('to');
        $status = $request->query('status');

        $articles = Article::when($type, fn($q, $v) => $q->where('type', $v))
            ->when($from, fn($q, $v) => $q->whereDate('published_at', '>=', $v))
            ->when($to, fn($q, $v) => $q->whereDate('published_at', '<=', $v))
            ->when($status, fn($q, $v) => $q->where('status', $v))
            ->where(function ($q) {
                $q->where('status', 'published')
                  ->orWhere('user_id', auth()->id());
            })
            ->latest('published_at')
            ->with('author')
            ->paginate(10);

        return view('articles.index', compact('articles', 'type', 'from', 'to', 'status'));
    }

    public function create()
    {
        return view('articles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'  => 'required|string|max:255',
            'body'   => 'required|string',
            'type'   => 'required|in:news,tech,announcement,event',
            'status' => 'required|in:draft,published',
        ]);

        $data['user_id'] = auth()->id();
        $data['published_at'] = $data['status'] === 'published' ? now() : null;

        Article::create($data);

        if ($data['status'] === 'published') {
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
        }

        $msg = $data['status'] === 'published' ? 'Article published.' : 'Draft saved.';
        return redirect()->route('articles.index')
            ->with('success', $msg);
    }

    public function show(Article $article)
    {
        return view('articles.show', compact('article'));
    }

    public function edit(Article $article)
    {
        return view('articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $data = $request->validate([
            'title'  => 'required|string|max:255',
            'body'   => 'required|string',
            'type'   => 'required|in:news,tech,announcement,event',
            'status' => 'required|in:draft,published',
        ]);

        if ($data['status'] === 'published' && ! $article->published_at) {
            $data['published_at'] = now();
        }

        $article->update($data);

        return redirect()->route('articles.show', $article)
            ->with('success', 'Article updated.');
    }

    public function details(Article $article)
    {
        $html = view('articles.modal-content', compact('article'))->render();

        return response()->json(['html' => $html]);
    }
}
