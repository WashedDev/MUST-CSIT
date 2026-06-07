@extends('layouts.dashboard')
@section('title','Events — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <h1>Events & Elections</h1>
  <p>Workshops, hackathons, talks and society elections.</p>
</div>

<div class="dash-card" style="margin-bottom:24px">
  <div class="dash-card-head">
    <h3>Upcoming Events</h3>
  </div>
  @forelse($events as $e)
    <div class="dash-list-item">
      <div class="dash-list-date"><strong>{{ $e->date->format('j') }}</strong>{{ $e->date->format('M') }}</div>
      <div style="flex:1">
        <a href="{{ route('events.show', $e) }}" style="color:inherit;text-decoration:none"><strong>{{ $e->title }}</strong></a>
        <div class="dash-list-meta">{{ $e->location }} · {{ $e->date->format('M d, Y') }}</div>
      </div>
      <span class="tag">{{ $e->tag }}</span>
    </div>
  @empty
    <p class="dash-empty">No events scheduled yet.</p>
  @endforelse
  {{ $events->links() }}
</div>

<div class="dash-card">
  <div class="dash-card-head">
    <h3>Elections & Polls</h3>
  </div>
  @forelse($elections as $el)
    <div class="dash-list-item">
      <div style="flex:1">
        <a href="{{ route('elections.show', $el) }}" style="color:inherit;text-decoration:none"><strong>{{ $el->title }}</strong></a>
        <div class="dash-list-meta">
          {{ $el->starts_at->format('M d, Y') }} — {{ $el->ends_at->format('M d, Y') }}
          · {{ $el->votes_count }} vote(s)
        </div>
      </div>
      <span class="tag">{{ ucfirst($el->status) }}</span>
    </div>
  @empty
    <p class="dash-empty">No elections or polls at this time.</p>
  @endforelse
</div>

@endsection
