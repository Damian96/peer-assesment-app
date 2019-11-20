@component('mail::message')
    # {{ $heading }}

    {{ $description }}

    <p style="color: #212529 !important;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;text-align: justify !important;margin-top: 0;margin-bottom: 1rem;box-sizing: border-box;font-size: 1rem;line-height: 1.5;">
        Write down your password to a safe place, so you won't lose it.<br>
        <label
            style="color: #fff !important;display: block !important;background-color: #6c757d !important;width: 100%;height: calc(2.25rem + 2px);padding: .375rem .75rem;background-clip: padding-box;border: 1px solid #ced4da;border-radius: .25rem;transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;margin-bottom: .5rem;box-sizing: border-box;white-space: nowrap;text-align: justify !important;">Password:<code
                style="color: #dc3545 !important;margin-left: .5rem !important;font-size: 87.5%;word-break: break-word;">{{ $user->password }}</code></label>
        You can now login with your email and password, to <a
            style="color: #007bff;text-decoration: none;background-color: transparent;box-sizing: border-box;white-space: nowrap;text-align: justify !important;"
            href="{{ url('/login') }}" target="_blank">{{ config('app.name') }}</a>.
    </p>

    @component('mail::button', ['url' => $url]){{ $action }}@endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
