@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('course.show', $course) }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                <tr>
                    @if (Auth::user()->isAdmin())
                        <th>ID</th>
                        <th>Owner</th>
                        <th>Title</th>
                        <th class="text-center">Code</th>
                        <th class="text-center">Academic Year</th>
                    @else
                        <th>Title</th>
                        <th class="text-center">Code</th>
                        <th class="text-center">Academic Year</th>
                    @endif
                    <th class="text-right">Links</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    @if (Auth::user()->isAdmin())
                        <td>{{ $course->id }}</td>
                        <td>{{ $course->instructor_name }}</td>
                        <td>{{ $course->title }}</td>
                        <td class="text-center">{{ $course->code }}</td>
                        <td class="text-center">{{ $course->ac_year_full }}</td>
                    @else
                        <td>{{ $course->title }}</td>
                        <td class="text-center">{{ $course->code }}</td>
                        <td class="text-center">{{ $course->ac_year_full }}</td>
                        <td class="action-cell text-right">
                            @if(Auth::user()->can('course.edit', ['id'=>$course->id]))
                                <a href="{{ url('/courses/' . $course->id . '/edit') }}" class="material-icons"
                                   title="Update Course {{ $course->code }}"
                                   aria-label="Update Course {{ $course->code }}">edit</a>
                            @endif
                            @if(Auth::user()->can('session.index', ['cid'=>$course->id]))
                                <a href="{{ url('/courses/' . $course->id . '/sessions') }}"
                                   class="material-icons"
                                   title="View Sessions of {{ $course->code }}"
                                   aria-label="View Sessions of {{ $course->code }}">assignment</a>
                            @endif
                            {{--                                <a href="{{ url('/courses/' . $course->id . '/delete') }}" class="material-icons">delete_forever</a>--}}
                        </td>
                    @endif
                </tr>
                <tr class="separator">
                    <td class="text-left" colspan="4">Courses with same Code</td>
                </tr>
                @foreach($similars as $course)
                    <tr>
                        <td>{{ $course->title }}</td>
                        <td class="text-center">``</td>
                        <td class="text-center">{{ $course->ac_year_full }}</td>
                        <td class="action-cell text-right">
                            <a href="{{ url('/courses/' . $course->id . '/view') }}" class="material-icons"
                               title="Show Course {{ $course->code }}"
                               aria-label="Show Course {{ $course->code }}">link</a>
                            @if(Auth::user()->can('course.edit', ['id'=>$course->id]))
                                <a href="{{ url('/courses/' . $course->id . '/edit') }}" class="material-icons"
                                   title="Update Course {{ $course->code }}"
                                   aria-label="Update Course {{ $course->code }}">edit</a>
                            @endif
                            @if(Auth::user()->can('session.index', ['cid'=>$course->id]))
                                <a href="{{ url('/courses/' . $course->id . '/sessions') }}"
                                   class="material-icons"
                                   title="View Sessions of {{ $course->code }}"
                                   aria-label="View Sessions of {{ $course->code }}">assignment</a>
                            @endif
                            {{--                                <a href="{{ url('/courses/' . $course->id . '/delete') }}" class="material-icons">delete_forever</a>--}}
                        </td>
                    </tr>
                @endforeach
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
    </div>
    <div class="row">
        <div class="col-md-12">
            <a href="{{ url('/courses/' . $course->id . '/add-student') }}"
               class="btn btn-md btn-primary"
               title="Add Students to {{ $course->code }}"
               aria-roledescription="Add Students to {{ $course->code }}">
                Add Students to {{ $course->code }}
            </a>
        </div>
    </div>
@endsection
