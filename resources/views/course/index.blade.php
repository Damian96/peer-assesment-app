@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (session()->has('message'))
                <div class="alert alert-{{ session()->get('message')['level'] }}" role="alert">
                    <h4 class="alert-heading">{{ session()->get('message')['heading'] }}</h4>
                    {!! session()->get('message')['body'] !!}
                </div>
            @endif

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($courses as $course)
                    <tr>
                        <td>{{ $course->title }}</td>
                        <td>{{ $course->code }}</td>
                        <td>{{ $course->description }}</td>
                        <td>{{ $course->created_at }}</td>
                        <td>{{ $course->updated_at }}</td>
                        <td class="action-cell">
                            <a href="{{ url('/course/' . $course->id) }}" class="material-icons">link</a>
                            <a href="{{ url('/course/edit/' . $course->id) }}" class="material-icons">edit</a>
                            <a href="{{ url('/course/delete/' . $course->id) }}" class="material-icons">delete_forever</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
