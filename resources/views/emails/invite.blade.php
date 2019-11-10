@component('mail::message')
    # {{ $heading }}

    {{ $description }}

    @component('mail::button', ['url' => $url]){{ $action }}@endcomponent

    @component('mail::panel'){{ $notice  }}@endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
