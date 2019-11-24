@component('mail::message')
# You are invited to {{ config('app.name') }}

<p id="description">You have been registered to <span id="course-title">{{ $course->title }}</span></p>

<p id="credentials">Please write down your <b>Credentials</b>, and keep them somewhere safe, so you won't lose them!<br><p>Email:<br><code>{{ $user->email }}</code></p><p>Password:<br><code style="">{{ $user->password_plain }}</code></p>You can now login with the above credentials, to <a href="{{ url('/login') }}" target="_blank">{{ config('app.name') }}</a>.</p>

@component('mail::button', ['url' => $url])
Verify Email
@endcomponent

Thanks,<br>{{ config('app.name') }}
@endcomponent
