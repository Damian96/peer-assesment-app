@component('mail::message')
# {{ config('auth.password_reset.strings.heading') }}

@component('mail::button', ['url' => $url]){{ config('auth.password_reset.strings.action') }}@endcomponent

{!! config('auth.password_reset.strings.notice') !!}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
