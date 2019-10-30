@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            @include('course.form', [
                'method' => 'POST',
                'action' => url('/courses/store'),
                'errors' => $errors
            ])
        </div>
    </div>
@endsection
