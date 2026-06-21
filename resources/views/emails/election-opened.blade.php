<x-mail::message>
# Voting is Now Open

**{{ $election->title }}** is now open for voting.

@if($election->description)
{{ $election->description }}
@endif

Voting closes on **{{ $election->ends_at->format('F j, Y \\a\\t g:i A') }}**.

<x-mail::button :url="route('elections.show', $election)">
Vote Now
</x-mail::button>

Don't miss your chance to make your voice heard.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
