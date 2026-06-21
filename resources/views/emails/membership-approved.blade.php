<x-mail::message>
# Membership Approved

Hi {{ $user->firstname }},

Your membership application for the **MUST CSIT Society** has been approved.

You now have full access to all society features including events, elections, articles, and more.

<x-mail::button :url="route('dashboard')">
Go to Dashboard
</x-mail::button>

Welcome aboard!

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
