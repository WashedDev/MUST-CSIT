@extends('layouts.dashboard')
@section('title', $article->title . ' — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>{{ $article->title }}</h1>
    <p style="color:var(--ink-secondary)">
      By {{ $article->author->name }} &middot; {{ $article->published_at?->format('M d, Y') ?? 'Not published' }}
      <span class="tag" style="margin-left:8px">{{ ucfirst($article->type) }}</span>
      @if($article->status === 'draft')
        <span class="tag" style="background:#fef9c3;color:#A16207">Draft</span>
      @endif
      <span style="margin-left:8px;color:var(--ink-secondary);font-size:0.85rem">&middot; {{ $article->read_time }}</span>
      @if(auth()->id() === $article->user_id || auth()->user()->isAdmin())
        <a href="{{ route('articles.edit', $article) }}" class="btn btn-ghost btn-sm" style="margin-left:12px">Edit</a>
      @endif
    </p>
  </div>
</div>

<div class="dash-card" style="max-width:720px;line-height:1.7;font-size:1.05rem">
  {{ $article->body }}
</div>

<p style="margin-top:24px"><a href="{{ route('articles.index') }}">&larr; Back to articles</a></p>

@endsection
