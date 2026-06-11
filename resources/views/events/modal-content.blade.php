<div class="event-modal-header">
  <h2>{{ $event->title }}</h2>
  <span class="event-modal-date">{{ $event->date->format('M d, Y H:i') }} &middot; {{ $event->location }}</span>
</div>

<div class="event-modal-body">
  <p>{{ $event->description }}</p>

  <div class="event-modal-meta">
    <span><strong>Capacity:</strong> {{ $event->hasUnlimitedCapacity() ? 'Unlimited' : $event->bookings_count . ' / ' . $event->capacity . ' booked' }}</span>
    @if($event->isPaid())
      <span class="tag" style="background:#fef9c3;color:#A16207">MWK {{ number_format((float) $event->price) }}</span>
    @endif
    @if($event->tag)
      <span class="tag">{{ $event->tag }}</span>
    @endif
  </div>

  @if($userBooking)
    @if($userBooking->status === 'pending_payment')
      <p style="color:var(--ink-500);margin:16px 0">Your booking is pending payment.</p>
      <form method="POST" action="{{ route('events.cancel', $event) }}" style="display:inline">
        @csrf
        <button class="btn btn-outline btn-sm" type="submit">Cancel Booking</button>
      </form>
    @else
      <p style="margin:16px 0">You are booked for this event <span class="tag">{{ $userBooking->status }}</span></p>
      <form method="POST" action="{{ route('events.cancel', $event) }}" style="display:inline">
        @csrf
        <button class="btn btn-outline btn-sm" type="submit">Cancel Booking</button>
      </form>
    @endif
  @elseif(!$event->date->isPast() && ($event->hasUnlimitedCapacity() || $event->availableSeats() > 0))
    <form method="POST" action="{{ route('events.book', $event) }}" style="margin-top:16px">
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
    <p style="color:var(--ink-secondary);margin-top:16px">This event has already taken place.</p>
  @else
    <p style="color:var(--ink-secondary);margin-top:16px">No seats available.</p>
  @endif
</div>
