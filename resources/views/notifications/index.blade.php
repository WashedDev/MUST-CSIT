@extends('layouts.dashboard')
@section('title', 'Notifications — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Notifications</h1>
    <p>Stay up to date with your society activity.</p>
  </div>
  <form method="POST" action="{{ route('notifications.read-all') }}">@csrf
    <button class="btn btn-ghost btn-sm" type="submit">Mark all as read</button>
  </form>
</div>

@if($notifications->count())
  <div class="dash-card" style="margin-top:24px">
    @foreach($notifications as $n)
      <div class="dash-list-item" style="{{ $n->read ? '' : 'background:var(--primary-50);margin:0 -16px;padding:12px 16px;border-radius:var(--radius-sm)' }}">
        <div style="flex:1">
          @if($n->url)
            <a href="{{ route('notifications.read', $n) }}" style="color:inherit;text-decoration:none">
              <strong>{{ $n->title }}</strong>
          @else
            <strong>{{ $n->title }}</strong>
          @endif
          @if($n->body)
            <p style="margin:4px 0 0;font-size:0.82rem;color:var(--ink-secondary)">{{ $n->body }}</p>
          @endif
          <div style="font-size:0.75rem;color:var(--ink-400);margin-top:4px">{{ $n->created_at->diffForHumans() }}</div>
          @if($n->url)</a>@endif
        </div>
        <span class="tag" style="font-size:0.7rem">{{ str_replace('_', ' ', $n->type) }}</span>
      </div>
    @endforeach
  </div>
  <div style="margin-top:24px">{{ $notifications->links() }}</div>
@else
  <div class="dash-card" style="text-align:center;padding:48px;margin-top:24px">
    <p style="color:var(--ink-400)">No notifications yet.</p>
  </div>
@endif

@endsection
