<x-mail::message>
# A Spot Opened Up!

Hi {{ $booking->user->firstname }},

A spot has opened up for **{{ $event->title }}** and your waitlist status has been upgraded to a confirmed booking!

**Event Details:**
- **Date:** {{ $event->date->format('l, M d, Y H:i') }}
- **Location:** {{ $event->location }}

<x-mail::button :url="route('events.show', $event)">
View Event Details
</x-mail::button>

We look forward to seeing you there!

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
