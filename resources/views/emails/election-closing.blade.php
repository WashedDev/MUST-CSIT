<x-mail::message>
# Election Closing Reminder

**{{ $election->title }}** will close on **{{ $election->ends_at->format('F j, Y \\a\\t g:i A') }}**.

If you haven't voted yet, now is your chance to make your voice heard.

<x-mail::button :url="route('elections.show', $election)">
Vote Now
</x-mail::button>

Don't miss this opportunity to participate.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
