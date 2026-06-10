@extends('layouts.dashboard')
@section('title','Admin Dashboard — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Admin Dashboard</h1>
    <p>Welcome back, {{ auth()->user()->firstname }}. Here's the society overview.</p>
  </div>
  <div>
    <span class="tag" style="background:var(--primary-100);color:var(--primary);font-size:0.75rem;padding:4px 12px">
      <span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:#16A34A;margin-right:6px"></span>
      System Online
    </span>
  </div>
</div>

<div class="admin-stat-grid">
  <div class="stat-card accent-cyan">
    <div class="stat-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
    </div>
    <div class="stat-label">Total Members</div>
    <div class="stat-number">{{ $stats['members'] }}</div>
    <div class="stat-change up">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>
      Active
    </div>
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
    <div class="stat-label">Articles</div>
    <div class="stat-number">{{ $stats['articles'] }}</div>
  </div>
</div>

<div class="dash-grid-3" style="margin-top:24px">
  <div class="dash-card">
    <div class="dash-card-head">
      <h3>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        Upcoming Events
      </h3>
      <a href="{{ route('admin.events.index') }}" class="dash-link">Manage &rarr;</a>
    </div>
    @forelse($upcomingEvents as $e)
      <div class="dash-list-item">
        <div class="dash-list-date"><strong>{{ $e->date->format('j') }}</strong>{{ $e->date->format('M') }}</div>
        <div style="flex:1">
          <strong style="font-size:0.9rem">{{ $e->title }}</strong>
          <div class="dash-list-meta">{{ $e->location }} &middot; {{ $e->availableSeats() }}/{{ $e->capacity }} seats</div>
        </div>
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
          <div class="dash-list-meta">{{ $a->author->name }} &middot; {{ $a->published_at?->format('M d') }}</div>
        </div>
        <span class="tag">{{ ucfirst($a->type) }}</span>
      </div>
    @empty
      <p class="dash-empty">No articles yet.</p>
    @endforelse
  </div>

  <div class="dash-card">
    <div class="dash-card-head">
      <h3>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        Recent Members
      </h3>
      <a href="{{ route('admin.members.index') }}" class="dash-link">Manage &rarr;</a>
    </div>
    @forelse($recentMembers as $m)
      <div class="dash-list-item">
        <div class="user-avatar user-avatar-sm">{{ strtoupper(substr($m->firstname,0,1)) }}{{ strtoupper(substr($m->lastname,0,1)) }}</div>
        <div style="flex:1;min-width:0">
          <strong style="font-size:0.88rem">{{ $m->name }}</strong>
          <div class="dash-list-meta">{{ $m->email }} &middot; {{ $m->created_at->format('M d') }}</div>
        </div>
        <span class="tag">{{ $m->role }}</span>
      </div>
    @empty
      <p class="dash-empty">No members.</p>
    @endforelse
  </div>
</div>

<div class="dash-grid-2" style="margin-top:24px">
  <div class="dash-card">
    <div class="dash-card-head">
      <h3>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/></svg>
        System Health
      </h3>
    </div>
    <div class="system-health">
      <div class="health-item">
        <span class="health-dot ok" aria-label="Online"></span>
        <span class="health-label">Application Server</span>
      </div>
      <div class="health-item">
        <span class="health-dot ok" aria-label="Healthy"></span>
        <span class="health-label">Database</span>
      </div>
      <div class="health-item">
        <span class="health-dot ok" aria-label="Active"></span>
        <span class="health-label">Member Sessions</span>
      </div>
    </div>
  </div>

  <div class="dash-card">
    <div class="dash-card-head">
      <h3>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        Quick Actions
      </h3>
    </div>
    <div style="display:flex;flex-wrap:wrap;gap:8px">
      <a href="{{ route('admin.events.create') }}" class="btn btn-primary btn-sm">Create Event</a>
      <a href="{{ route('articles.create') }}" class="btn btn-outline btn-sm">Write Article</a>
      <a href="{{ route('documents.create') }}" class="btn btn-outline btn-sm">Upload Document</a>
      <a href="{{ route('admin.members.index') }}" class="btn btn-outline btn-sm">Manage Members</a>
    </div>
  </div>
</div>

@endsection
