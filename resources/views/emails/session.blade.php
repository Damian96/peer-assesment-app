@php
/**
 * @var \App\User $user
 * @var \App\Course $course
 */
@endphp

@component('mail::message')
# A new Session has opened on Course {{ $course->code }}

@component('mail::panel')
Please login to <a href="{{ url('/login') }}" title="Login to {{ config('app.name') }}" aria-label="Login to {{ config('app.name') }}">{{ config('app.name') }}</a> to complete the PA Form before the deadline closes!
@endcomponent

@component('mail::button', ['url' => url('/login')])
Login to {{ config('app.name') }}
@endcomponent

Thanks,<br>{{ config('app.name') }}
@endcomponent
