@component('mail::message')
# A new Session has opened on Course {!! $course->code !!}

<p id="description">Please login to <a href="{{ url('/login') }}">{{ config('app.name') }}</a> to complete the PA Form before the deadline closes!</p>

Thanks,<br>{{ config('app.name') }}
@endcomponent
