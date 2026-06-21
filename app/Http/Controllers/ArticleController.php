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
                $q->where(function ($q) {
                    $q->where('status', 'published')
                      ->where('published_at', '<=', now());
                })->orWhere('user_id', auth()->id());
            })
            ->where(function ($q) {
                $q->where('approved', true)
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
            'title'       => 'required|string|max:255',
            'body'        => 'required|string',
            'type'        => 'required|in:news,tech,announcement,event',
            'status'      => 'required|in:draft,published,scheduled',
            'scheduled_at'=> 'nullable|date|after:now',
        ]);

        $data['user_id'] = auth()->id();
        $user = auth()->user();

        if ($data['status'] === 'published' && ! $user->isAdmin()) {
            $data['status'] = 'pending';
            $data['approved'] = false;
            $data['published_at'] = null;
        } elseif ($data['status'] === 'published') {
            $data['approved'] = true;
            $data['reviewed_by'] = $user->id;
            $data['reviewed_at'] = now();
            $data['published_at'] = now();
        } elseif ($data['status'] === 'scheduled') {
            $data['published_at'] = $data['scheduled_at'] ?? now()->addDay();
            $data['status'] = 'draft';
        } else {
            $data['published_at'] = null;
        }

        unset($data['scheduled_at']);

        $approved = $data['approved'] ?? false;
        $reviewedBy = $data['reviewed_by'] ?? null;
        $reviewedAt = $data['reviewed_at'] ?? null;
        $publishedAt = $data['published_at'] ?? null;
        unset($data['approved'], $data['reviewed_by'], $data['reviewed_at'], $data['published_at']);

        $article = Article::create($data);
        $article->forceFill([
            'approved'     => $approved,
            'reviewed_by'  => $reviewedBy,
            'reviewed_at'  => $reviewedAt,
            'published_at' => $publishedAt,
        ])->save();

        if (($data['status'] ?? 'draft') === 'published') {
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

        $msg = $data['status'] === 'published' ? 'Article published.' : ($data['status'] === 'pending' ? 'Article submitted for review.' : 'Draft saved.');
        return redirect()->route('articles.index')
            ->with('success', $msg);
    }

    public function show(Article $article)
    {
        if ($article->user_id !== auth()->id() && ! auth()->user()->isAdmin()) {
            if (! $article->approved || $article->status !== 'published') {
                abort(403);
            }
        }
        return view('articles.show', compact('article'));
    }

    public function edit(Article $article)
    {
        if ($article->user_id !== auth()->id() && ! auth()->user()->isAdmin()) {
            abort(403);
        }
        return view('articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        if ($article->user_id !== auth()->id() && ! auth()->user()->isAdmin()) {
            abort(403);
        }

        $data = $request->validate([
            'title'  => 'required|string|max:255',
            'body'   => 'required|string',
            'type'   => 'required|in:news,tech,announcement,event',
            'status' => 'required|in:draft,published',
        ]);

        $user = auth()->user();

        if ($data['status'] === 'published' && ! $user->isAdmin()) {
            $data['status'] = 'pending';
            $data['approved'] = false;
            $data['reviewed_by'] = null;
            $data['reviewed_at'] = null;
        }

        if ($data['status'] === 'published' && $user->isAdmin()) {
            $data['approved'] = true;
            $data['reviewed_by'] = $user->id;
            $data['reviewed_at'] = now();
        }

        if ($data['status'] === 'published' && ! $article->published_at) {
            $data['published_at'] = now();
        }

        if ($data['status'] !== 'published') {
            $data['approved'] = false;
        }

        $approved = $data['approved'] ?? $article->approved;
        $reviewedBy = $data['reviewed_by'] ?? $article->reviewed_by;
        $reviewedAt = $data['reviewed_at'] ?? $article->reviewed_at;
        $publishedAt = $data['published_at'] ?? $article->published_at;
        unset($data['approved'], $data['reviewed_by'], $data['reviewed_at'], $data['published_at']);

        $article->forceFill([
            'approved'     => $approved,
            'reviewed_by'  => $reviewedBy,
            'reviewed_at'  => $reviewedAt,
            'published_at' => $publishedAt,
        ])->save();
        $article->update($data);

        return redirect()->route('articles.show', $article)
            ->with('success', 'Article updated.');
    }

    public function details(Article $article)
    {
        if ($article->user_id !== auth()->id() && ! auth()->user()->isAdmin()) {
            if (! $article->approved || $article->status !== 'published') {
                abort(403);
            }
        }
        $html = view('articles.modal-content', compact('article'))->render();

        return response()->json(['html' => $html]);
    }
}
