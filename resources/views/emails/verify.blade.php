@component('mail::message')
# {{ config('auth.verification.strings.subject') }}

{{ config('auth.verification.strings.heading') }}

@component('mail::button', ['url' => $url]){{ config('auth.verification.strings.action') }}@endcomponent

@component('mail::panel'){{ 'If you did not register, please send us an email at ' . config('admin.mails.support.address')  }}@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
