<x-mail::message>
# Booking Confirmed

Hi {{ $booking->user->firstname }},

Your booking for **{{ $booking->event->title }}** has been confirmed.

**Event Details:**
- **Date:** {{ $booking->event->date->format('l, M d, Y H:i') }}
- **Location:** {{ $booking->event->location }}
- **Status:** {{ ucfirst($booking->status) }}

<x-mail::button :url="route('events.show', $booking->event)">
View Event
</x-mail::button>

If you need to cancel, please do so before the event starts.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
