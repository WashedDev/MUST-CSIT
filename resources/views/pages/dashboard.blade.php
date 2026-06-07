@extends('layouts.app')
@section('title','Dashboard — MUST CSIT Society')
@section('content')

<div class="page-head">
  <div class="container">
    <h1>Welcome, {{ auth()->user()->name }}</h1>
    <p>Here's what's happening in the Society this week.</p>
  </div>
</div>

<div class="container">
  <div class="stat-grid">
    <div class="stat"><div class="l">Members</div><div class="n">{{ $stats['members'] }}</div></div>
    <div class="stat"><div class="l">Upcoming Events</div><div class="n">{{ $stats['upcoming_events'] }}</div></div>
    <div class="stat"><div class="l">Active Projects</div><div class="n">{{ $stats['projects'] }}</div></div>
    <div class="stat"><div class="l">Workshops</div><div class="n">{{ $stats['workshops'] }}</div></div>
  </div>
</div>

<section class="block">
  <div class="container">
    <div class="grid-2">
      <div class="card">
        <div class="icon">▣</div>
        <h3>Next Event</h3>
        <p><strong>Intro to AI Workshop</strong> — Jun 12, MUST Lab A. Limited to 40 members.</p>
        <p style="margin-top:14px"><a href="{{ route('events.index') }}" class="btn btn-primary">View all events</a></p>
      </div>
      <div class="card">
        <div class="icon">◉</div>
        <h3>Announcements</h3>
        <p>Hackathon registrations open June 1. Form your team of 3–5 members early.</p>
        <p>New mentorship cohort applications close June 20.</p>
      </div>
    </div>
  </div>
</section>

@endsection
