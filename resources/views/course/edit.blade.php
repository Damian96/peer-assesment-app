@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            @include('course.form', [
                'method' => 'PUT',
                'action' => url('/courses/' . request('id', 0)),
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
