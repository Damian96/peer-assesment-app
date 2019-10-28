@component('mail::message')
# {{ config('auth.password_reset.strings.heading') }}

{{ config('auth.password_reset.strings.description') }}

<pre style="font-size: 25px;font-weight: bold;margin: 16px 0;">{{ $code }}</pre>

@component('mail::panel'){{ 'If you did not perform this action, please send us an email at ' . config('admin.mails.support.address')  }}@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
