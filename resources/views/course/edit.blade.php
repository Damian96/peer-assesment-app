@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('course.edit', $course) }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            @include('course.form', [
                'method' => 'PUT',
                'action' => url('/courses/' . $course->id),
                'errors' => $errors,
                'course' => $course,
                'button' => [
                    'title' => 'Update Course ' . $course->code,
                    'label' => 'Update'
                ],
            ])
        </div>
    </div>
@endsection
