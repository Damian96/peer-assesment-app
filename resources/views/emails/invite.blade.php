@component('mail::message')
# You are invited to {!! config('app.name') !!}

You have been registered to <span style="font-weight: bold;">{{ $course->title }}</span>

Please write down your <b>Credentials</b>, and keep them somewhere safe, so you won't lose them!

@component('vendor.mail.html.code', ['label' => 'Email:'])
{{ $user->email }}
@endcomponent

@component('vendor.mail.html.code', ['label' => 'Password:'])
{{ $user->password_plain }}
@endcomponent

You can now login with the above credentials, to <a href="{{ url('/login') }}" target="_blank">login
to {{ config('app.name') }}</a>.

@component('mail::button', ['url' => $url])
Verify Email
@endcomponent

Thanks,<br>{{ config('app.name') }}
@endcomponent
