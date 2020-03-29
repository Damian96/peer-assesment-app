@php
/**
 * @var Course $course
 */
@endphp

@component('mail::message')
# You have been enrolled to Course {!! $course->code !!}

@component('mail::panel')
You have been enrolled to <strong id="course-title">{{ $course->title }}</strong><br>,
{{ $course->semester }} Semester of Academic Year {{ $course->ac_year_pair }}.
@endcomponent

@if($course->hasOpenedSessions())
@component('mail::panel')
This Course currently has <i>{{ $course->sessions()->get(['sessions.*'])->count() }} opened Session(s)</i>.
Please <a href="{{ url('/login/') }}" title="WPES login" aria-label="WPES login" target="_blank">login to WPES</a> now, and submit any pending PA Forms.
@endcomponent
@endif

@component('vendor.mail.html.footer-notice')@endcomponent

Thanks,<br><strong>{{ config('mail.from.name') }}</strong>
@endcomponent
