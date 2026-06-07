@extends('layouts.dashboard')
@section('title', $article->title . ' — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <h1>{{ $article->title }}</h1>
  <p style="color:var(--muted)">
    By {{ $article->author->name }} · {{ $article->published_at?->format('M d, Y') ?? 'Draft' }}
    <span class="tag" style="margin-left:8px">{{ ucfirst($article->type) }}</span>
  </p>
</div>

<div class="dash-card" style="max-width:720px;line-height:1.7;font-size:1.05rem">
  {{ $article->body }}
</div>

<p style="margin-top:24px"><a href="{{ route('articles.index') }}">← Back to articles</a></p>

@endsection
