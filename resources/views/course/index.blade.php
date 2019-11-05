@extends('layouts.app')

@section('content')
    @if (!Auth::user()->isStudent())
    <div class="row my-2">
        <div class="col-md-12">
            <form method="GET" class="form-inline" onchange="this.submit()">
                <input type="hidden" value="{{ request('page', 1) }}" class="hidden" name="page" id="page">
                <label for="ac_year">Academic Year
                    <select id="ac_year" name="ac_year" class="ml-2 form-control-sm">
                        @foreach(range(intval(date('Y')), config('constants.date.start'), -1) as $year)
                            <option
                                value="{{ $year }}"{{ $year == request('ac_year', $ac_year) ? ' selected' : null }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </label>
            </form>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            @if ($courses->total() || ! Auth::user()->isStudent())
                <table id="my-courses" class="table table-striped ts">
                    <caption
                        class="">{{ sprintf("Showing results %s-%s of total %s Courses", $courses->firstItem(), $courses->lastItem(), $courses->total()) }}</caption>
                    <thead>
                    <tr class="tsTitles">
                        <th>#</th>
                        <th>Title</th>
                        <th>Code</th>
                        @if(Auth::user()->isAdmin())
                            <th>Instructor</th>@endif
                        <th>Links</th>
                    </tr>
                    </thead>
                    <tbody class="tsGroup">
                    @foreach($courses as $i => $course)
                        <tr>
                            <td>{{ ($courses->firstItem()+$i) }}</td>
                            <td>{{ $course->title }}</td>
                            <td>{{ $course->code }}</td>
                            @if(Auth::user()->isAdmin())
                                <td>
                                    <a href="{{ url($course->user_id . '/show') }}"
                                       title="View {{ $course->instructor_name }} Profile">{{ $course->instructor_name }}</a>
                                </td>
                            @endif
                            <td class="action-cell">
                                @if(Auth::user()->can('course.view'))
                                    <a href="{{ url('/courses/' . $course->id . '/view') }}" class="material-icons"
                                       title="View Course {{ $course->code }}"
                                       aria-label="View Course {{ $course->code }}">link</a>
                                @endif
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
                </table>
                {{ $courses->links() }}
            @else
                <h2>{{ 'You do not have any Courses yet!' }}</h2>
            @endif
        </div>
    </div>
@endsection

@section('end_footer')
    <script type="text/javascript">
        $(document).ready(function () {
            // Initialize table
            @if (Auth::user()->isAdmin())
            {!! "let cols = [{col: 0, order: 'asc'}, {col: 3, order: 'asc'}];" !!}
            @elseif (Auth::user()->isInstructor())
            {!! "let cols = [{col: 0, order: 'asc'}, {col: 2, order: 'asc'}];" !!}
            @else
            {!! "let cols = [{col: 0, order: 'asc'}, {col: 2, order: 'asc'}];" !!}
            @endif
            $('#my-courses').tablesorter({tablesorterColumns: cols});
        });
    </script>
@endsection
