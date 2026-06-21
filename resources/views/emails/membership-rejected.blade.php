<x-mail::message>
# Membership Application Update

Hi {{ $user->firstname }},

We regret to inform you that your membership application for the **MUST CSIT Society** has not been approved at this time.

@if($reason)
**Reason:** {{ $reason }}
@endif

If you believe this is an error or would like more information, please contact the society administration.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
