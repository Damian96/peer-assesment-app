@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('course.create') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            @include('course.form', [
                'method' => 'POST',
                'action' => url('/courses/store'),
                'errors' => $errors,
                'course' => null,
                'button' => [
                    'title' => 'Create a Course',
                    'label' => 'Create'
                ],
            ])
        </div>
    </div>
@endsection
