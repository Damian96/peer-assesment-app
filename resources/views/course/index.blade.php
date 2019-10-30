@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (!empty($courses))
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Created At</th>
                        @if(Auth::user()->isAdmin())<th>Instructor</th>@endif
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
                            @if(Auth::user()->isAdmin())<td>{{ $course->instructor_name }}</td>@endif
                            <td class="action-cell">
                                <a href="{{ url('/courses/' . $course->id . '/view') }}" class="material-icons">link</a>
                                <a href="{{ url('/courses/' . $course->id . '/edit') }}" class="material-icons">edit</a>
                                {{--                                <a href="{{ url('/courses/' . $course->id . '/delete') }}" class="material-icons">delete_forever</a>--}}
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            @else
                <h2>{{ 'You do not have any Courses yet!' }}</h2>
            @endif
        </div>
    </div>
@endsection
