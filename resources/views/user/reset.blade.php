@component('mail::message')
# {{ config('auth.resetting.strings.heading') }}

{{ config('auth.resetting.strings.description') }}

<pre style="font-size: 25px;font-weight: bold;margin: 16px 0;">{{ $code }}</pre>

@component('mail::panel'){{ config('auth.resetting.strings.notice')  }}@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
