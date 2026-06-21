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

<div class="dash-card" style="max-width:720px;margin-top:16px">
  <h2 style="margin-bottom:16px">Comments ({{ $article->comments->count() }})</h2>

  @forelse($article->comments as $comment)
    <div style="padding:10px 0;border-bottom:1px solid var(--outline)">
      <div style="display:flex;justify-content:space-between;align-items:center">
        <strong style="font-size:0.85rem">{{ $comment->user->name }}</strong>
        <span style="font-size:0.75rem;color:var(--ink-secondary)">
          {{ $comment->created_at->diffForHumans() }}
          @if($comment->user_id === auth()->id() || auth()->user()->isAdmin())
            <form method="POST" action="{{ route('comments.destroy', $comment) }}" style="display:inline" onsubmit="return confirm('Delete this comment?')">
              @csrf @method('DELETE')
              <button class="btn btn-ghost btn-sm" style="font-size:0.75rem;margin-left:8px">Delete</button>
            </form>
          @endif
        </span>
      </div>
      <p style="margin:4px 0 0;font-size:0.95rem">{{ $comment->body }}</p>
    </div>
  @empty
    <p class="dash-empty">No comments yet.</p>
  @endforelse

  <form method="POST" action="{{ route('articles.comments.store', $article) }}" style="margin-top:16px">
    @csrf
    <div class="form-group">
      <label for="body">Leave a comment</label>
      <textarea id="body" name="body" rows="3" maxlength="2000" required>{{ old('body') }}</textarea>
      @error('body') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <button class="btn btn-primary" type="submit">Post Comment</button>
  </form>
</div>

<p style="margin-top:24px"><a href="{{ route('articles.index') }}">&larr; Back to articles</a></p>

@endsection
