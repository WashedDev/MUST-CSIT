@extends('layouts.dashboard')
@section('title','Dashboard — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Welcome back, {{ auth()->user()->firstname }}</h1>
    <p>Here's what's happening in the Society.</p>
  </div>
</div>

<div class="stat-grid">
  <div class="stat-card accent-gold">
    <div class="stat-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
    </div>
    <div class="stat-label">My Bookings</div>
    <div class="stat-number">{{ $stats['my_bookings'] }}</div>
  </div>
  <div class="stat-card">
    <div class="stat-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
    </div>
    <div class="stat-label">Upcoming Events</div>
    <div class="stat-number">{{ $stats['upcoming_events'] }}</div>
  </div>
  <div class="stat-card accent-red">
    <div class="stat-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
    </div>
    <div class="stat-label">Active Elections</div>
    <div class="stat-number">{{ $stats['active_elections'] }}</div>
  </div>
  <div class="stat-card accent-gold">
    <div class="stat-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
    </div>
    <div class="stat-label">New Articles</div>
    <div class="stat-number">{{ $stats['new_articles'] }}</div>
  </div>
</div>

<div class="dash-grid-2" style="margin-top:24px">
  <div class="dash-card">
    <div class="dash-card-head">
      <h3>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        Upcoming Events
      </h3>
      <a href="{{ route('events.index') }}" class="dash-link">View all &rarr;</a>
    </div>
    @forelse($upcomingEvents as $e)
      <div class="dash-list-item">
        <div class="dash-list-date"><strong>{{ $e->date->format('j') }}</strong>{{ $e->date->format('M') }}</div>
        <div style="flex:1">
          <strong style="font-size:0.9rem">{{ $e->title }}</strong>
          <div class="dash-list-meta">{{ $e->location }}</div>
        </div>
        <span class="tag">{{ $e->tag }}</span>
      </div>
    @empty
      <p class="dash-empty">No upcoming events.</p>
    @endforelse
  </div>

  <div class="dash-card">
    <div class="dash-card-head">
      <h3>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
        Latest News
      </h3>
      <a href="{{ route('articles.index') }}" class="dash-link">View all &rarr;</a>
    </div>
    @forelse($latestArticles as $a)
      <div class="dash-list-item">
        <div style="flex:1">
          <a href="{{ route('articles.show', $a) }}" style="color:inherit;text-decoration:none"><strong style="font-size:0.9rem">{{ $a->title }}</strong></a>
          <div class="dash-list-meta">{{ $a->author->name }} &middot; {{ $a->published_at?->format('M d') ?? 'Draft' }}</div>
        </div>
        <span class="tag">{{ ucfirst($a->type) }}</span>
      </div>
    @empty
      <p class="dash-empty">No articles yet.</p>
    @endforelse
  </div>
</div>

@if(count($myBookings) > 0)
  <div class="dash-card" style="margin-top:20px">
    <div class="dash-card-head">
      <h3>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        My Bookings
      </h3>
    </div>
    @foreach($myBookings as $b)
      <div class="dash-list-item">
        <div class="dash-list-date"><strong>{{ $b->event->date->format('j') }}</strong>{{ $b->event->date->format('M') }}</div>
        <div style="flex:1">
          <strong style="font-size:0.9rem">{{ $b->event->title }}</strong>
          <div class="dash-list-meta">{{ $b->status }} &middot; Booked {{ $b->created_at->format('M d') }}</div>
        </div>
        <span class="tag">{{ $b->status }}</span>
      </div>
    @endforeach
  </div>
@endif

<div style="margin-top:24px">
  <div class="dash-card-head" style="margin-bottom:12px">
    <h3>
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
      Quick Links
    </h3>
  </div>
  <div style="display:flex;flex-wrap:wrap;gap:8px">
    <a href="{{ route('events.index') }}" class="btn btn-outline btn-sm">Browse Events</a>
    <a href="{{ route('articles.index') }}" class="btn btn-outline btn-sm">Read News</a>
    <a href="{{ route('documents.index') }}" class="btn btn-outline btn-sm">View Documents</a>
    <a href="{{ route('profile') }}" class="btn btn-outline btn-sm">My Profile</a>
  </div>
</div>

@endsection
