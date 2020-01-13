@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('courses') }}
@endsection

@section('content')
    <div class="row my-2">
        <div class="col-md-12">
            <form method="GET" class="form-inline" onchange="this.submit()">
                <input type="hidden" value="{{ request('page', 1) }}" class="hidden" name="page" id="page">
                <label for="ac_year">Academic Year
                    <select id="ac_year" name="ac_year" class="ml-2 form-control-sm">
                        <option
                            value="current"{{ request('ac_year') == 'current' ? 'selected' : '' }}>{{ date('Y') . '-' . substr(date('Y', strtotime('+1 year')), -2) }}</option>
                        <option value="previous"{{ request('ac_year') == 'previous' ? 'selected' : '' }}>Previous
                        </option>
                    </select>
                </label>
            </form>
        </div>
    </div>
    <div class="row py-2">
        <div class="col-md-12">
            @if ($courses->total() || ! Auth::user()->isStudent())
                <table id="my-courses" class="table table-striped ts">
                    <caption
                        class="">{{ sprintf("Showing results %s-%s of total %s Courses", $courses->firstItem(), $courses->lastItem(), $courses->total()) }}</caption>
                    <thead>
                    <tr class="tsTitles">
                        <th>Code</th>
                        <th>Title</th>
                        @if(Auth::user()->isAdmin())
                            <th>Instructor</th>@endif
                    </tr>
                    </thead>
                    <tbody class="tsGroup">
                    @foreach($courses as $i => $course)
                        <tr>
                            <td>
                                @if(Auth::user()->can('course.view'))
                                    <a href="{{ url('/courses/' . $course->course_id . '/view') }}"
                                       title="View Course {{ $course->code }}"
                                       aria-label="View Course {{ $course->code }}">{{ $course->code }}</a>
                                @else
                                    {{ $course->code }}
                                @endif
                            </td>
                            <td>
                                {{ $course->title }}
                            </td>
                            @if(Auth::user()->isAdmin())
                                <td>
                                    <a href="{{ url($course->instructor_id . '/show') }}"
                                       title="View {{ $course->instructor_name }} Profile">{{ $course->instructor_name }}</a>
                                </td>
                            @endif
                            {{--                                @if(Auth::user()->can('course.edit', ['id'=>$course->course_id]))--}}
                            {{--                                    <a href="{{ url('/courses/' . $course->course_id . '/edit') }}" class="material-icons"--}}
                            {{--                                       title="Update Course {{ $course->code }}"--}}
                            {{--                                       aria-label="Update Course {{ $course->code }}">edit</a>--}}
                            {{--                                @endif--}}
                            {{--                                @if(Auth::user()->can('session.index', ['cid'=>$course->course_id]))--}}
                            {{--                                    <a href="{{ url('/courses/' . $course->course_id . '/sessions') }}"--}}
                            {{--                                       class="material-icons"--}}
                            {{--                                       title="View Sessions of {{ $course->code }}"--}}
                            {{--                                       aria-label="View Sessions of {{ $course->code }}">assignment</a>--}}
                            {{--                                @endif--}}
                            {{--                                <a href="{{ url('/courses/' . $course->course_id . '/delete') }}" class="material-icons">delete_forever</a>--}}
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

@section('end_footer')
    <script type="text/javascript" defer>
        $(document).ready(function () {
            // Initialize table
            @if (Auth::user()->isAdmin()) {{-- Admin --}}
            {!! "let cols = [{col: 0, order: 'asc'}, {col: 1, order: 'asc'}];" !!}
            @elseif (Auth::user()->isInstructor()) {{-- Instructor --}}
            {!! "let cols = [{col: 0, order: 'asc'}, {col: 1, order: 'asc'}];" !!}
            @else {{-- Student --}}
            {!! "let cols = [{col: 0, order: 'asc'}];" !!}
            @endif
            $('#my-courses').tablesorter({tablesorterColumns: cols});
        });
    </script>
@endsection
