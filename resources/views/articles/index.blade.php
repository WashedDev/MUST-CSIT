@extends('layouts.dashboard')
@section('title', 'Articles — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>News &amp; Articles</h1>
    <p>News, tech articles and announcements from the Society.</p>
  </div>
</div>

<div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;align-items:center">
  <a href="{{ route('articles.index') }}" class="btn {{ !$type ? 'btn-primary' : 'btn-outline' }} btn-sm">All</a>
  <a href="{{ route('articles.index', ['type' => 'news']) }}" class="btn {{ $type === 'news' ? 'btn-primary' : 'btn-outline' }} btn-sm">News</a>
  <a href="{{ route('articles.index', ['type' => 'tech']) }}" class="btn {{ $type === 'tech' ? 'btn-primary' : 'btn-outline' }} btn-sm">Tech</a>
  <a href="{{ route('articles.create') }}" class="btn btn-accent btn-sm" style="margin-left:auto">Write Article</a>
</div>

<div class="dash-card">
  @forelse($articles as $article)
    <div class="dash-list-item">
      <div style="flex:1">
        <a href="{{ route('articles.show', $article) }}" style="color:inherit;text-decoration:none"><strong style="font-size:0.9rem">{{ $article->title }}</strong></a>
        <div class="dash-list-meta">By {{ $article->author->name }} &middot; {{ $article->published_at?->format('M d, Y') ?? 'Draft' }}</div>
      </div>
      <span class="tag">{{ ucfirst($article->type) }}</span>
    </div>
  @empty
    <p class="dash-empty">No articles found.</p>
  @endforelse
</div>

<div style="margin-top:16px">{{ $articles->links() }}</div>

@endsection
