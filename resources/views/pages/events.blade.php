@extends('layouts.app')
@section('title','Events — MUST CSIT Society')
@section('content')

<div class="page-head">
  <div class="container">
    <h1>Society Events</h1>
    <p>Workshops, hackathons, talks and outreach programmes for members.</p>
  </div>
</div>

<section class="block">
  <div class="container">
    @forelse($events as $e)
      <div class="event">
        <div class="date"><div class="d">{{ $e->date->format('j') }}</div><div class="m">{{ $e->date->format('M') }}</div></div>
        <div>
          <a href="{{ route('events.show', $e) }}" style="color:inherit;text-decoration:none"><strong>{{ $e->title }}</strong></a>
          <div style="color:var(--muted);font-size:.9rem">{{ $e->location }} · {{ $e->date->format('M d, Y') }}</div>
        </div>
        <span class="tag">{{ $e->tag }}</span>
      </div>
    @empty
      <p style="color:var(--muted)">No events scheduled yet.</p>
    @endforelse
  </div>
</section>

@endsection
