@extends('layouts.dashboard')
@section('title', 'Polls — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Opinion Polls</h1>
    <p>Share your opinion on non-binding polls.</p>
  </div>
  @if(auth()->user()->isAdmin())
    <a href="{{ route('admin.polls.create') }}" class="btn btn-primary">Create Poll</a>
  @endif
</div>

<div style="display:flex;flex-direction:column;gap:12px">
  @forelse($polls as $poll)
    <a href="{{ route('polls.show', $poll) }}" class="dash-card" style="display:block;text-decoration:none;color:inherit">
      <div style="display:flex;justify-content:space-between;align-items:center">
        <div>
          <strong>{{ $poll->question }}</strong>
          <div style="color:var(--ink-secondary);font-size:0.85rem;margin-top:4px">
            {{ $poll->votes_count }} vote(s) &middot;
            @if($poll->isActive())
              <span class="tag" style="background:#dcfce7;color:#16A34A">Active</span>
            @else
              <span class="tag">Closed</span>
            @endif
          </div>
        </div>
        <span style="color:var(--primary)">View &rarr;</span>
      </div>
    </a>
  @empty
    <div class="dash-card">
      <p class="dash-empty">No polls available.</p>
    </div>
  @endforelse
</div>

<div style="margin-top:16px">{{ $polls->links() }}</div>

@endsection
