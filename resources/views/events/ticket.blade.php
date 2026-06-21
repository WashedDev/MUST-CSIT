@extends('layouts.dashboard')
@section('title', 'Ticket — ' . $booking->event->title)
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>Event Ticket</h1>
  </div>
</div>

<div class="dash-card" style="max-width:400px;margin:0 auto;text-align:center">
  <h2 style="margin-bottom:4px">{{ $booking->event->title }}</h2>
  <p style="color:var(--ink-secondary);margin-bottom:16px">
    {{ $booking->event->date->format('l, M d, Y H:i') }}<br>
    {{ $booking->event->location }}
  </p>

  <div style="background:var(--surface-alt);padding:20px;border-radius:var(--radius-sm);display:inline-block;margin-bottom:16px">
    {!! $qrCode !!}
  </div>

  <p style="font-size:0.85rem;color:var(--ink-secondary)">
    <strong>{{ $booking->user->name }}</strong><br>
    Booking #{{ $booking->id }}
  </p>

  <div style="margin-top:16px">
    <a href="{{ route('events.show', $booking->event) }}" class="btn btn-outline">Back to Event</a>
  </div>
</div>

@endsection
