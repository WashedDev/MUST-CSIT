<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Document;
use App\Models\Event;
use App\Models\MerchItem;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $q = trim($request->query('q', ''));

        if (strlen($q) < 2) {
            return response()->json(['results' => []]);
        }

        $term = '%' . $q . '%';

        $events = Event::where('title', 'like', $term)
            ->orWhere('description', 'like', $term)
            ->orWhere('location', 'like', $term)
            ->latest('date')
            ->limit(5)
            ->get()
            ->map(fn ($e) => [
                'type' => 'event',
                'url'  => route('events.index'),
                'title' => $e->title,
                'meta' => $e->date->format('M d, Y') . ' @ ' . $e->location,
            ]);

        $articles = Article::where('title', 'like', $term)
            ->orWhere('body', 'like', $term)
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn ($a) => [
                'type'  => 'article',
                'url'   => route('articles.show', $a),
                'title' => $a->title,
                'meta'  => $a->created_at->format('M d, Y'),
            ]);

        $merch = MerchItem::where('is_active', true)
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                  ->orWhere('description', 'like', $term);
            })
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn ($m) => [
                'type'  => 'merch',
                'url'   => route('merch.show', $m),
                'title' => $m->name,
                'meta'  => 'MWK ' . number_format((float) $m->price),
            ]);

        $documents = Document::where('title', 'like', $term)
            ->orWhere('category', 'like', $term)
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn ($d) => [
                'type'  => 'document',
                'url'   => route('documents.download', $d),
                'title' => $d->title,
                'meta'  => ucfirst($d->category),
            ]);

        $members = User::where('firstname', 'like', $term)
            ->orWhere('lastname', 'like', $term)
            ->orWhere('email', 'like', $term)
            ->orWhere('reg_number', 'like', $term)
            ->limit(5)
            ->get()
            ->map(fn ($u) => [
                'type'  => 'member',
                'url'   => route('admin.members.edit', $u),
                'title' => $u->name,
                'meta'  => $u->email,
            ]);

        $results = collect()
            ->merge($events)
            ->merge($articles)
            ->merge($merch)
            ->merge($documents)
            ->merge($members)
            ->take(20);

        return response()->json(['results' => $results]);
    }
}
