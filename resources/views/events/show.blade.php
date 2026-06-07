@extends('layouts.dashboard')
@section('title', $event->title . ' — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <h1>{{ $event->title }}</h1>
  <p>{{ $event->date->format('M d, Y') }} · {{ $event->location }}</p>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
  <div class="alert alert-error">{{ $errors->first() }}</div>
@endif

<div class="dash-card" style="max-width:600px">
  <p>{{ $event->description }}</p>

  <div style="margin:20px 0;padding:16px;background:var(--bg);border-radius:8px">
    <strong>Capacity:</strong> {{ $event->bookings_count }} / {{ $event->capacity }} booked
    @if($event->tag) · <span class="tag">{{ $event->tag }}</span> @endif
  </div>

  @if($userBooking)
    <p>You are booked for this event <span class="tag">{{ $userBooking->status }}</span></p>
    <form method="POST" action="{{ route('events.cancel', $event) }}" style="display:inline">
      @csrf
      <button class="btn btn-outline" type="submit">Cancel Booking</button>
    </form>

  @elseif(!$event->date->isPast() && $event->availableSeats() > 0)
    <form method="POST" action="{{ route('events.book', $event) }}">
      @csrf
      <button class="btn btn-primary" type="submit">Book a Seat</button>
    </form>

  @elseif($event->date->isPast())
    <p style="color:var(--muted)">This event has already taken place.</p>

  @else
    <p style="color:var(--muted)">No seats available.</p>
  @endif
</div>

<p style="margin-top:24px"><a href="{{ route('events.index') }}">← Back to events</a></p>

@endsection
