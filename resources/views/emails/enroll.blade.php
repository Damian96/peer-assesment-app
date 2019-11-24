@component('mail::message')
# You have been enrolled to {!! $course->code !!}

<p id="description">You have been registered to <span id="course-title">{{ $course->title }}</span></p>

Thanks,<br>{{ config('app.name') }}
@endcomponent
