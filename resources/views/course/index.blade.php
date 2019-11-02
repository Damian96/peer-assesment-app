@extends('layouts.app')

@section('content')
    <div class="row my-2">
        <div class="col-md-12">
            <form method="GET" class="form-inline" onchange="this.submit()">
                <input type="hidden" value="{{ request('page', 1) }}" class="hidden" name="page" id="page">
                <label for="ac_year">Academic Year
                    <select id="ac_year" name="ac_year" class="ml-2 form-control-sm">
                        @foreach(range(intval(date('Y')), config('constants.start'), -1) as $year)
                            <option
                                value="{{ $year }}"{{ $year == request('ac_year', $ac_year) ? ' selected' : null }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </label>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @if (!empty($courses))
                <table class="table table-striped">
                    <caption
                        class="">{{ sprintf("Showing results %s-%s of total %s Courses", $courses->firstItem(), $courses->lastItem(), $courses->total()) }}</caption>
                    <thead>
                    <tr>
                        <th>##</th>
                        <th>Title</th>
                        <th>Code</th>
                        @if(Auth::user()->isAdmin())
                            <th>Instructor</th>@endif
                        <th>Links</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($courses as $i => $course)
                        <tr>
                            <td>{{ ($courses->firstItem()+$i) }}</td>
                            <td>{{ $course->title }}</td>
                            <td>{{ $course->code }}</td>
                            @if(Auth::user()->isAdmin())
                                <td>
                                    <a href="{{ url($course->user_id . '/show') }}"
                                       title="View {{ $course->instructor_name }} Profile"
                                       title="View {{ $course->instructor_name }} Profile">{{ $course->instructor_name }}</a>
                                </td>
                            @endif
                            <td class="action-cell">
                                <a href="{{ url('/courses/' . $course->id . '/view') }}" class="material-icons"
                                   title="View Course {{ $course->code }}" aria-label="View Course {{ $course->code }}">link</a>
                                <a href="{{ url('/courses/' . $course->id . '/edit') }}" class="material-icons"
                                   title="Update Course {{ $course->code }}"
                                   aria-label="Update Course {{ $course->code }}">edit</a>
                                <a href="{{ url('/courses/' . $course->id . '/sessions') }}" class="material-icons"
                                   title="View Sessions of {{ $course->code }}"
                                   aria-label="View Sessions of {{ $course->code }}">assignment</a>
                                {{--                                <a href="{{ url('/courses/' . $course->id . '/delete') }}" class="material-icons">delete_forever</a>--}}
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
                {{ $courses->links() }}
            @else
                <h2>{{ 'You do not have any Courses yet!' }}</h2>
            @endif
        </div>
    </div>
@endsection
