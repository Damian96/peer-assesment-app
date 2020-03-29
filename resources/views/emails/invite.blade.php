@php
/**
 * @var \App\User $user
 * @var \App\Course $course
 * @var string $url
 */
@endphp

@component('mail::message')
# You are invited to {{ config('app.name') }}

@component('mail::panel')
You have been enrolled to <strong id="course-title">{{ $course->title }}</strong><br>,
{{ $course->semester }} Semester of Academic Year {{ $course->ac_year_pair }}. <br>
Please write down your <b>Credentials</b>, and keep them somewhere safe, so you won't lose them!
@endcomponent

@component('vendor.mail.html.code', ['label' => 'Email:']){{ $user->email }}@endcomponent

@component('vendor.mail.html.code', ['label' => 'Password:']){{ $user->password_plain }}@endcomponent

@component('mail::panel')
You can now login with the above credentials, to <a href="{{ url('/login') }}" target="_blank">login
to {{ config('app.name') }}</a>.
@endcomponent

@component('mail::button', ['url' => $url])
Verify Email
@endcomponent

@component('vendor.mail.html.footer-notice')@endcomponent

Thanks,<br>{{ config('app.name') }}
@endcomponent
