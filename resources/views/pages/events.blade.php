@extends('layouts.dashboard')
@section('title','Events — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Events &amp; Elections</h1>
    <p>Workshops, hackathons, talks and society elections.</p>
  </div>
  <div class="view-toggle" role="tablist" aria-label="View mode">
    <a href="{{ route('events.index', ['view' => 'grid']) }}" role="tab" aria-selected="{{ $view === 'grid' ? 'true' : 'false' }}" class="view-toggle-btn {{ $view === 'grid' ? 'active' : '' }}" title="Grid view">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
    </a>
    <a href="{{ route('events.index', ['view' => 'calendar']) }}" role="tab" aria-selected="{{ $view === 'calendar' ? 'true' : 'false' }}" class="view-toggle-btn {{ $view === 'calendar' ? 'active' : '' }}" title="Calendar view">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
    </a>
  </div>
</div>

@if($view === 'calendar')
  @include('events._calendar', ['date' => $date, 'eventsByDay' => $events])
@elseif($events->count())
  <div class="event-showcase-grid" style="margin-bottom:32px">
    @foreach($events as $e)
      <div class="event-showcase-card" style="cursor:pointer;position:relative" data-event-id="{{ $e->id }}" role="button" tabindex="0">
        @if(!empty($e->user_booked))
          <span style="position:absolute;top:8px;right:8px;z-index:2;background:var(--primary);color:#fff;font-size:0.65rem;font-weight:700;padding:2px 8px;border-radius:999px;letter-spacing:0.04em;text-transform:uppercase">Booked</span>
        @endif
        <div class="event-showcase-date-badge">
          <div class="d">{{ $e->date->format('j') }}</div>
          <div class="m">{{ $e->date->format('M') }}</div>
        </div>
        <div class="event-showcase-image-placeholder">
          @if($e->tag === 'Hackathon')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
          @elseif($e->tag === 'Workshop')
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
          @else
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          @endif
        </div>
        <div class="event-showcase-body">
          <h3>{{ $e->title }}</h3>
          <p>{{ Str::limit($e->description, 100) }}</p>
          <div class="event-showcase-meta">
            <span>
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              {{ $e->location }} &middot; {{ $e->date->format('M d, Y H:i') }}
            </span>
            @if($e->tag)
              <span class="tag">{{ $e->tag }}</span>
            @endif
          </div>
        </div>
      </div>
    @endforeach
  </div>
  <div style="margin-bottom:32px">{{ $events->links() }}</div>
@else
  <div class="dash-card" style="margin-bottom:32px">
    <p class="dash-empty">No events scheduled yet.</p>
  </div>
@endif

<div class="dash-card">
  <div class="dash-card-head">
    <h3>
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
      Elections &amp; Polls
    </h3>
  </div>
  @forelse($elections as $el)
    <div class="dash-list-item">
      <div style="flex:1">
        <a href="{{ route('elections.show', $el) }}" style="color:inherit;text-decoration:none"><strong style="font-size:0.9rem">{{ $el->title }}</strong></a>
        <div class="dash-list-meta">
          {{ $el->starts_at->format('M d, Y') }} &mdash; {{ $el->ends_at->format('M d, Y') }}
          &middot; {{ $el->votes_count }} vote(s)
        </div>
      </div>
      <span class="tag">{{ ucfirst($el->status) }}</span>
    </div>
  @empty
    <p class="dash-empty">No elections or polls at this time.</p>
  @endforelse
</div>

<div class="modal-overlay" id="eventModal" style="display:none" role="dialog" aria-modal="true" aria-label="Event details">
  <div class="modal-container">
    <button class="modal-close" id="modalClose" aria-label="Close modal">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <div class="modal-content" id="modalContent">
      <div class="modal-loader"><div class="spinner"></div></div>
    </div>
  </div>
</div>

@push('scripts')
<script>
(function() {
  var modal = document.getElementById('eventModal');
  var modalContent = document.getElementById('modalContent');
  var closeBtn = document.getElementById('modalClose');
  var cards = document.querySelectorAll('[data-event-id]');

  window.openEventModal = function(id) {
    modalContent.innerHTML = '<div class="modal-loader"><div class="spinner"></div></div>';
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';

    fetch('/events/' + id + '/details')
      .then(function(r) { return r.json(); })
      .then(function(data) {
        modalContent.innerHTML = data.html;
        modalContent.querySelectorAll('form').forEach(function(f) {
          f.addEventListener('submit', function() {
            setTimeout(function() { closeModal(); }, 300);
          });
        });
      })
      .catch(function() {
        modalContent.innerHTML = '<p style="text-align:center;padding:40px;color:var(--accent)">Failed to load event details.</p>';
      });
  };

  function closeModal() {
    modal.style.display = 'none';
    document.body.style.overflow = '';
  }

  cards.forEach(function(card) {
    card.addEventListener('click', function() {
      window.openEventModal(card.getAttribute('data-event-id'));
    });
    card.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        window.openEventModal(card.getAttribute('data-event-id'));
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
