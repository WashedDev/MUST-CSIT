Hello {{ $booking->user->name }},

This is a reminder that **{{ $booking->event->title }}** is happening soon!

- **Date:** {{ $booking->event->date->format('M d, Y H:i') }}
- **Location:** {{ $booking->event->location }}

@if($booking->event->location)
  Don't forget to bring your student ID.
@endif

[View Event]({{ route('events.index') }})

---
MUST CSIT Society
