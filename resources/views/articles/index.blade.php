@extends('layouts.app')
@section('title', 'Articles — MUST CSIT Society')
@section('content')

<div class="page-head">
  <div class="container">
    <h1>Articles</h1>
    <p>News, tech articles and announcements from the Society.</p>
  </div>
</div>

<section class="block">
  <div class="container">
    <div style="display:flex;gap:8px;margin-bottom:20px">
      <a href="{{ route('articles.index') }}" class="btn {{ !$type ? 'btn-primary' : 'btn-outline' }}">All</a>
      <a href="{{ route('articles.index', ['type' => 'news']) }}" class="btn {{ $type === 'news' ? 'btn-primary' : 'btn-outline' }}">News</a>
      <a href="{{ route('articles.index', ['type' => 'tech']) }}" class="btn {{ $type === 'tech' ? 'btn-primary' : 'btn-outline' }}">Tech</a>
      <a href="{{ route('articles.create') }}" class="btn btn-gold" style="margin-left:auto">Write Article</a>
    </div>

    @forelse($articles as $article)
      <div class="event" style="flex-direction:column;align-items:start;gap:4px">
        <div style="display:flex;justify-content:space-between;width:100%">
          <strong><a href="{{ route('articles.show', $article) }}" style="color:inherit;text-decoration:none">{{ $article->title }}</a></strong>
          <span class="tag">{{ ucfirst($article->type) }}</span>
        </div>
        <div style="color:var(--muted);font-size:.85rem">
          By {{ $article->author->name }} · {{ $article->published_at?->format('M d, Y') ?? 'Draft' }}
        </div>
      </div>
    @empty
      <p style="color:var(--muted)">No articles found.</p>
    @endforelse

    {{ $articles->links() }}
  </div>
</section>

@endsection
