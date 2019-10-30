@extends('layouts.app')

@section('content')
    <div class="row">
        @if (session()->has('message'))
            <div class="alert alert-{{ session()->get('message')['level'] }}" role="alert">
                <h4 class="alert-heading">{{ session()->get('message')['heading'] }}</h4>
                {{ session()->get('message')['body'] }}
            </div>
        @endif
        <div class="col-md-8 offset-md-2">
            @include('course.form', [
                'method' => 'PUT',
                'action' => url('/courses/' . request('id', 0)),
                'errors' => $errors,
                'course' => $course
            ])
        </div>
    </div>
@endsection
