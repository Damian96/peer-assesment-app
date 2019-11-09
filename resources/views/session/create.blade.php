@extends('layouts.app')

@section('content')
    @include('session.form', [
        'action' => url('/sessions/store'),
        'method' => 'POST',
        'button' => [
            'title' => 'Create Session for ' . $course->code,
            'label' => 'Create',
        ],
    ])
@endsection
