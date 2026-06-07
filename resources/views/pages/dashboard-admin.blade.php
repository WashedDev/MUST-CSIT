@extends('layouts.dashboard')
@section('title','Admin Dashboard — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <h1>Admin Dashboard 👋</h1>
  <p>Welcome back, {{ auth()->user()->firstname }}. Here's the society overview.</p>
</div>

<div class="stat-grid">
  <div class="stat"><div class="l">Total Members</div><div class="n">{{ $stats['members'] }}</div></div>
  <div class="stat"><div class="l">Upcoming Events</div><div class="n">{{ $stats['upcoming_events'] }}</div></div>
  <div class="stat"><div class="l">Active Elections</div><div class="n">{{ $stats['active_elections'] }}</div></div>
  <div class="stat"><div class="l">Articles</div><div class="n">{{ $stats['articles'] }}</div></div>
</div>

<div class="dash-grid-3" style="margin-top:28px">
  <div class="dash-card">
    <div class="dash-card-head">
      <h3>Upcoming Events</h3>
      <a href="{{ route('events.index') }}" class="dash-link">View all →</a>
    </div>
    @forelse($upcomingEvents as $e)
      <div class="dash-list-item">
        <div class="dash-list-date">{{ $e->date->format('M j') }}</div>
        <div>
          <strong>{{ $e->title }}</strong>
          <div class="dash-list-meta">{{ $e->location }} · {{ $e->availableSeats() }}/{{ $e->capacity }} seats</div>
        </div>
      </div>
    @empty
      <p class="dash-empty">No upcoming events.</p>
    @endforelse
  </div>

  <div class="dash-card">
    <div class="dash-card-head">
      <h3>Latest News</h3>
      <a href="{{ route('articles.index') }}" class="dash-link">View all →</a>
    </div>
    @forelse($latestArticles as $a)
      <div class="dash-list-item">
        <div>
          <a href="{{ route('articles.show', $a) }}" style="color:inherit;text-decoration:none"><strong>{{ $a->title }}</strong></a>
          <div class="dash-list-meta">{{ $a->author->name }} · {{ $a->published_at?->format('M d') }}</div>
        </div>
        <span class="tag">{{ ucfirst($a->type) }}</span>
      </div>
    @empty
      <p class="dash-empty">No articles yet.</p>
    @endforelse
  </div>

  <div class="dash-card">
    <div class="dash-card-head">
      <h3>Recent Members</h3>
      <a href="{{ route('admin.members.index') }}" class="dash-link">Manage →</a>
    </div>
    @forelse($recentMembers as $m)
      <div class="dash-list-item">
        <div class="user-avatar sm">{{ strtoupper(substr($m->firstname,0,1)) }}{{ strtoupper(substr($m->lastname,0,1)) }}</div>
        <div>
          <strong>{{ $m->name }}</strong>
          <div class="dash-list-meta">{{ $m->email }} · {{ $m->created_at->format('M d') }}</div>
        </div>
        <span class="tag">{{ $m->role }}</span>
      </div>
    @empty
      <p class="dash-empty">No members.</p>
    @endforelse
  </div>
</div>

@endsection
