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

<div class="event-showcase-grid">
  @forelse($articles as $article)
    <div class="event-showcase-card" style="cursor:pointer" data-article-id="{{ $article->id }}" role="button" tabindex="0">
      <div class="event-showcase-date-badge">
        <div class="d">{{ $article->published_at?->format('j') ?? '--' }}</div>
        <div class="m">{{ $article->published_at?->format('M') ?? 'Draft' }}</div>
      </div>
      <div class="event-showcase-image-placeholder">
        @if($article->type === 'tech')
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
        @else
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/><line x1="8" y1="7" x2="16" y2="7"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
        @endif
      </div>
      <div class="event-showcase-body">
        <h3>{{ $article->title }}</h3>
        <p>{{ Str::limit($article->body, 120) }}</p>
        <div class="event-showcase-meta">
          <span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            {{ $article->author->name }}
          </span>
          <span class="tag">{{ ucfirst($article->type) }}</span>
        </div>
      </div>
    </div>
  @empty
    <div class="dash-card">
      <p class="dash-empty">No articles found.</p>
    </div>
  @endforelse
</div>

<div style="margin-top:24px">{{ $articles->links() }}</div>

<div class="modal-overlay" id="articleModal" style="display:none" role="dialog" aria-modal="true" aria-label="Article details">
  <div class="modal-container">
    <button class="modal-close" id="articleModalClose" aria-label="Close modal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <div class="modal-content" id="articleModalContent">
<div class="modal-loader"><div class="spinner"></div></div>
    </div>
  </div>
</div>

@push('scripts')
<script>
(function() {
  var modal = document.getElementById('articleModal');
  var modalContent = document.getElementById('articleModalContent');
  var closeBtn = document.getElementById('articleModalClose');
  var cards = document.querySelectorAll('[data-article-id]');

  function openModal(id) {
    modalContent.innerHTML = '<div class="modal-loader"><div class="spinner"></div></div>';
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';

    fetch('/articles/' + id + '/details')
      .then(function(r) { return r.json(); })
      .then(function(data) {
        modalContent.innerHTML = data.html;
      })
      .catch(function() {
        modalContent.innerHTML = '<p style="text-align:center;padding:40px;color:var(--accent)">Failed to load article.</p>';
      });
  }

  function closeModal() {
    modal.style.display = 'none';
    document.body.style.overflow = '';
  }

  cards.forEach(function(card) {
    card.addEventListener('click', function() {
      openModal(card.getAttribute('data-article-id'));
    });
    card.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        openModal(card.getAttribute('data-article-id'));
      }
    });
  });

  closeBtn.addEventListener('click', closeModal);

  modal.addEventListener('click', function(e) {
    if (e.target === modal) closeModal();
  });

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && modal.style.display !== 'none') closeModal();
  });
})();
</script>
@endpush
@endsection
