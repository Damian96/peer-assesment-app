@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('session.edit', $session) }}
@endsection

@section('content')
    @include('session.form', [
        'action' => url("/sessions/{$session->id}/update"),
        'method' => 'POST',
        'courses' => isset($courses) ? $courses : null,
        'forms' => isset($forms) ? $forms : null,
        'messages' => $messages,
        'button' => [
            'title' => sprintf("Edit Session %s",$session->title),
            'label' => 'Edit',
        ],
    ])
@endsection
