@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('session.create') }}
@endsection

@section('content')
    @include('session.form', [
        'action' => url('/sessions/store'),
        'method' => 'POST',
        'session' => isset($session) ? $session : null,
        'course' => $course,
        'courses' => isset($courses) ? $courses : null,
        'forms' => isset($forms) ? $forms : null,
        'messages' => $messages,
        'button' => [
            'title' => 'Create Session' . (isset($course) ? 'for ' . $course->code : ''),
            'label' => 'Create',
        ],
    ])
@endsection
