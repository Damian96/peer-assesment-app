@component('mail::message')
# {{ config('auth.verification.strings.subject') }}

{{ config('auth.verification.strings.heading') }}

@component('mail::button', ['url' => $url]){{ config('auth.verification.strings.action') }}@endcomponent

{{ 'If you did not perform this action, please send us an email at ' . config('admin.mails.support.address')  }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
