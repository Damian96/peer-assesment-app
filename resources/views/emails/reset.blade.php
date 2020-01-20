@component('mail::message')
# {{ config('auth.password_reset.strings.heading') }}

{{ config('auth.password_reset.strings.description') }}

@component('mail::button', ['url' => $url])
{{ config('auth.password_reset.strings.action') }}
@endcomponent

@component('mail::panel')
This link will only be available for the next hour!
@endcomponent

@component('mail::panel')
{{ config('auth.password_reset.strings.notice')  }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
