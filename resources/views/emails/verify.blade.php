@component('mail::message')
# {{ config('auth.verification.strings.subject') }}

{{ config('auth.verification.strings.heading') }}

@component('mail::button', ['url' => $url])
    {{ config('auth.verification.strings.action') }}
@endcomponent

@component('mail::panel')
    {{ config('auth.verification.strings.notice') }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
