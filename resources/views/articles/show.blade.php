@extends('layouts.app')
@section('title', $article->title . ' — MUST CSIT Society')
@section('content')

<div class="page-head">
  <div class="container">
    <h1>{{ $article->title }}</h1>
    <p style="color:var(--muted)">
      By {{ $article->author->name }} · {{ $article->published_at?->format('M d, Y') ?? 'Draft' }}
      <span class="tag" style="margin-left:8px">{{ ucfirst($article->type) }}</span>
    </p>
  </div>
</div>

<section class="block">
  <div class="container">
    <div style="max-width:720px;line-height:1.7;font-size:1.05rem">
      {{ $article->body }}
    </div>
    <p style="margin-top:24px"><a href="{{ route('articles.index') }}">← Back to articles</a></p>
  </div>
</section>

@endsection
