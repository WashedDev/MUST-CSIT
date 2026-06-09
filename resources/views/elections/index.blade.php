@extends('layouts.dashboard')
@section('title', 'Elections — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Elections</h1>
    <p>Society elections and decision-making polls.</p>
  </div>
</div>

<div class="dash-card">
  @forelse($elections as $election)
    <div class="dash-list-item">
      <div style="flex:1">
        <a href="{{ route('elections.show', $election) }}" style="color:inherit;text-decoration:none"><strong style="font-size:0.9rem">{{ $election->title }}</strong></a>
        <div class="dash-list-meta">
          {{ $election->starts_at->format('M d, Y H:i') }} &mdash; {{ $election->ends_at->format('M d, Y H:i') }}
        </div>
        <div class="dash-list-meta">{{ $election->description }}</div>
      </div>
      <span class="tag">{{ ucfirst($election->status) }}</span>
      <a href="{{ route('elections.show', $election) }}" class="btn btn-primary btn-sm">View</a>
    </div>
  @empty
    <p class="dash-empty">No elections have been created yet.</p>
  @endforelse
</div>

@endsection
