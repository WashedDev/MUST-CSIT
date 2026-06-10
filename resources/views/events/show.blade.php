@extends('layouts.dashboard')
@section('title', $event->title . ' — MUST CSIT Society')
@section('content')

<div class="dash-header">
  <div class="dash-header-text">
    <h1>{{ $event->title }}</h1>
    <p>{{ $event->date->format('M d, Y') }} &middot; {{ $event->location }}</p>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success" role="alert">{{ session('success') }}</div>
@endif

@if(session('info'))
  <div class="alert alert-success" role="alert">{{ session('info') }}</div>
@endif

@if($errors->any())
  <div class="alert alert-error" role="alert">{{ $errors->first() }}</div>
@endif

<div class="dash-card" style="max-width:600px">
  <p>{{ $event->description }}</p>

  <div style="margin:20px 0;padding:16px;background:var(--surface-alt);border-radius:var(--radius-sm);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px">
    <span><strong>Capacity:</strong> {{ $event->bookings_count }} / {{ $event->capacity }} booked</span>
    @if($event->isPaid())
      <span class="tag" style="background:#fef9c3;color:#A16207">MWK {{ number_format((float) $event->price) }}</span>
    @endif
    @if($event->tag) <span class="tag">{{ $event->tag }}</span> @endif
  </div>

  @if($userBooking)
    @if($userBooking->status === 'pending_payment')
      <p style="color:var(--ink-500)">Your booking is pending payment.</p>
      <form method="POST" action="{{ route('events.cancel', $event) }}" style="display:inline">
        @csrf
        <button class="btn btn-outline" type="submit">Cancel Booking</button>
      </form>
    @else
      <p>You are booked for this event <span class="tag">{{ $userBooking->status }}</span></p>
      <form method="POST" action="{{ route('events.cancel', $event) }}" style="display:inline">
        @csrf
        <button class="btn btn-outline" type="submit">Cancel Booking</button>
      </form>
    @endif

  @elseif(!$event->date->isPast() && $event->availableSeats() > 0)
    <form method="POST" action="{{ route('events.book', $event) }}">
      @csrf
      <button class="btn btn-primary" type="submit">
        @if($event->isPaid())
          Book &middot; Pay MWK {{ number_format((float) $event->price) }}
        @else
          Book a Seat
        @endif
      </button>
    </form>

  @elseif($event->date->isPast())
    <p style="color:var(--ink-secondary)">This event has already taken place.</p>

  @else
    <p style="color:var(--ink-secondary)">No seats available.</p>
  @endif
</div>

<p style="margin-top:24px"><a href="{{ route('events.index') }}">&larr; Back to events</a></p>

@endsection
