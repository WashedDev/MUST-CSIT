@extends('layouts.app')
@section('title', 'Elections — MUST CSIT Society')
@section('content')

<div class="page-head">
  <div class="container">
    <h1>Elections</h1>
    <p>Society elections and decision-making polls.</p>
  </div>
</div>

<section class="block">
  <div class="container">
    @forelse($elections as $election)
      <div class="event">
        <div>
          <strong>{{ $election->title }}</strong>
          <div style="color:var(--muted);font-size:.9rem">
            {{ $election->starts_at->format('M d, Y H:i') }} — {{ $election->ends_at->format('M d, Y H:i') }}
          </div>
          <div style="color:var(--muted);font-size:.9rem">{{ $election->description }}</div>
        </div>
        <div style="display:flex;align-items:center;gap:10px">
          <span class="tag">{{ ucfirst($election->status) }}</span>
          <a href="{{ route('elections.show', $election) }}" class="btn btn-primary">View</a>
        </div>
      </div>
    @empty
      <p style="color:var(--muted)">No elections have been created yet.</p>
    @endforelse
  </div>
</section>

@endsection
