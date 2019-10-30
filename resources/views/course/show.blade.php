@extends('layouts.app')

@section('content')
    <div class="row">
        <table class="table table-striped">
            <thead>
                <tr>
                    @if (Auth::user()->isAdmin())
                        <th>ID</th>
                        <th>Owner</th>
                        <th>Title</th>
                        <th>Code</th>
                        <th>Description</th>
                    @else
                        <th>Title</th>
                        <th>Code</th>
                        <th>Description</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <tr>
                    @if (Auth::user()->isAdmin())
                        <td>{{ $course->id }}</td>
                        <td>{{ $course->instructor_name }}</td>
                        <td>{{ $course->title }}</td>
                        <td>{{ $course->code }}</td>
                        <td>{{ $course->description }}</td>
                    @else
                        <td>{{ $course->title }}</td>
                        <td>{{ $course->code }}</td>
                        <td>{{ $course->description }}</td>
                    @endif
                </tr>
            </tbody>
            @if (Auth::user()->isAdmin())
            <tfoot>
                <tr>
                    <td colspan="2">Created: {{ $course->create_date }}</td>
                    <td colspan="2">Updated: {{ $course->update_date }}</td>
                    <td colspan="1">Status: ??</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
@endsection
