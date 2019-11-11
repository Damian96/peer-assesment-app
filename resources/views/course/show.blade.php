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
                        <th scope="col">ID</th>
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
                        <th scope="row">{{ $course->id }}</th>
                        <td>{{ $course->instructor_name }}</td>
                        <td>{{ $course->title }}</td>
                        <td class="text-center">{{ $course->code }}</td>
                        <td class="text-center">{{ $course->ac_year_full }}</td>
                    @else
                        <td>{{ $course->title }}</td>
                        <td class="text-center">{{ $course->code }}</td>
                        <td class="text-center">{{ $course->ac_year_full }}</td>
                    @endif
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
                </tr>
                <tr class="separator">
                    <td class="text-left" colspan="4">Courses with same Code</td>
                </tr>
                @foreach($similars as $similar)
                    <tr>
                        @if (Auth::user()->isAdmin())
                            <th scope="row">{{ $similar->id }}</th>
                            <td>{{ $similar->instructor_name }}</td>
                            <td>{{ $similar->title }}</td>
                            <td class="text-center">``</td>
                            <td class="text-center">{{ $similar->ac_year_full }}</td>
                        @else
                            <td>{{ $similar->title }}</td>
                            <td class="text-center">``</td>
                            <td class="text-center">{{ $similar->ac_year_full }}</td>
                        @endif
                        <td class="action-cell text-right">
                            <a href="{{ url('/courses/' . $similar->id . '/view') }}" class="material-icons"
                               title="Show Course {{ $similar->code }}"
                               aria-label="Show Course {{ $similar->code }}">link</a>
                            @if(Auth::user()->can('course.edit', ['id'=>$similar->id]))
                                <a href="{{ url('/courses/' . $similar->id . '/edit') }}" class="material-icons"
                                   title="Update Course {{ $similar->code }}"
                                   aria-label="Update Course {{ $similar->code }}">edit</a>
                            @endif
                            @if(Auth::user()->can('session.index', ['cid'=>$similar->id]))
                                <a href="{{ url('/courses/' . $similar->id . '/sessions') }}"
                                   class="material-icons"
                                   title="View Sessions of {{ $similar->code }}"
                                   aria-label="View Sessions of {{ $similar->code }}">assignment</a>
                            @endif
                            {{--                                <a href="{{ url('/courses/' . $course->id . '/delete') }}" class="material-icons">delete_forever</a>--}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
                @if (Auth::user()->isAdmin())
                    <tfoot>
                    <tr>
                        <td colspan="2">Created: {{ $similar->create_date }}</td>
                        <td colspan="2">Updated: {{ $similar->update_date }}</td>
                        <td colspan="1">Status: ??</td>
                    </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h2>Enrolled Students</h2>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>E-mail</th>
                    <th>Registration Number</th>
                    <th>Department</th>
                </tr>
                </thead>
                <tbody>
                @foreach($course->students()->getResults() as $student)
                    <tr>
                        <td><a href="{{ url($student->id . '/show') }}" target="_blank">{{ $student->name }}</a></td>
                        <td><a href="{{ url('mailto:' . $student->email) }}" target="_blank">{{ $student->email }}</a>
                        </td>
                        <td>{{ $student->reg_num }}</td>
                        <td>{{ $student->department_title }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <a href="{{ url('/courses/' . $course->id . '/add-student') }}"
               class="btn btn-md btn-primary"
               title="Add Students to {{ $course->code }}"
               aria-roledescription="Add Students to {{ $course->code }}">
                Add Students to {{ $course->code }}
            </a>
        </div>
    </div>
@endsection
