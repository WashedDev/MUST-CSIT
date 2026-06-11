<div class="event-modal-header">
  <h2>{{ $article->title }}</h2>
  <span class="event-modal-date">
    By {{ $article->author->name }} &middot; {{ $article->published_at?->format('M d, Y') ?? 'Draft' }}
    <span class="tag" style="margin-left:8px">{{ ucfirst($article->type) }}</span>
  </span>
</div>

<div class="event-modal-body">
  <div style="line-height:1.7;font-size:1.02rem">
    {{ $article->body }}
  </div>
</div>
