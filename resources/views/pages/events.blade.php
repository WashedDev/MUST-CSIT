@extends('layouts.dashboard')
@section('title','Events — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Events &amp; Elections</h1>
    <p>Workshops, hackathons, talks and society elections.</p>
  </div>
</div>

<div class="dash-card" style="margin-bottom:24px">
  <div class="dash-card-head">
    <h3>
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
      Upcoming Events
    </h3>
  </div>
  @forelse($events as $e)
    <div class="dash-list-item">
      <div class="dash-list-date"><strong>{{ $e->date->format('j') }}</strong>{{ $e->date->format('M') }}</div>
      <div style="flex:1">
        <a href="{{ route('events.show', $e) }}" style="color:inherit;text-decoration:none"><strong style="font-size:0.9rem">{{ $e->title }}</strong></a>
        <div class="dash-list-meta">{{ $e->location }} &middot; {{ $e->date->format('M d, Y') }}</div>
      </div>
      <span class="tag">{{ $e->tag }}</span>
    </div>
  @empty
    <p class="dash-empty">No events scheduled yet.</p>
  @endforelse
  <div style="margin-top:16px">{{ $events->links() }}</div>
</div>

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

@endsection
