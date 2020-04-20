@php
/**
 * @var \App\User $student
 * @var \App\User $instructor
 * @var \App\Course $course
 * @var \App\Session $session
 */
@endphp

@component('mail::message')
# A new Session has opened on Course {{ $course->code }}

@component('mail::panel')
Please login to <a href="{{ url('/login') }}" title="Login to {{ config('app.name') }}" aria-label="Login to {{ config('app.name') }}">{{ config('app.name') }}</a> to complete the PA Form before the deadline closes!
@endcomponent

@component('mail::panel')
Session: {{ $session->title }}<br>
Department: {{ $session->department_title }}<br>
Instructor: {{ $instructor->name }}
@endcomponent

@component('mail::button', ['url' => url('/login')])
Login to {{ config('app.name') }}
@endcomponent

Thanks,<br>{{ config('app.name') }}
@endcomponent
