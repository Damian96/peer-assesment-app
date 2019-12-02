@extends('layouts.app')

@section('content')
    @include('session.form', [
        'action' => url('/sessions/store'),
        'method' => 'POST',
        'course' => $course,
        'courses' => isset($courses) ? $courses : null,
        'messages' => $messages,
        'button' => [
            'title' => 'Create Session' . (isset($course) ? 'for ' . $course->code : ''),
            'label' => 'Create',
        ],
    ])
@endsection
