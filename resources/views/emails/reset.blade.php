@component('mail::message')
    # {{ config('auth.password_reset.strings.subject') }}

    {{ config('auth.password_reset.strings.heading') }}

    @component('mail::button', ['url' => $url])
        {{ config('auth.password_reset.strings.action') }}
    @endcomponent

    @component('mail::panel')
        {{ config('auth.password_reset.strings.notice') }}
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
