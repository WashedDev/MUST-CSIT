<x-mail::message>
# Reset Your Password

Click the button below to reset your password. This link expires in 1 hour.

<x-mail::button :url="$url">
Reset Password
</x-mail::button>

If you did not request a password reset, no further action is required.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
