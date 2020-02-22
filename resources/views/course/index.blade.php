@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('courses') }}
@endsection

@section('content')
    @if(!Auth::user()->isStudent())
        <div class="row my-2">
            <div class="col-md-12">
                <form method="GET" class="form-inline" onchange="this.submit()">
                    <input type="hidden" value="{{ request('page', 1) }}" class="hidden" name="page" id="page">
                    <label for="ac_year">Academic Year
                        <select id="ac_year" name="ac_year" class="ml-2 form-control-sm">
                            <option
                                value="current"{{ request('ac_year') == 'current' ? 'selected' : '' }}>{{ \App\Course::toAcademicYearPair(time()) }}</option>
                            <option value="previous"{{ request('ac_year') == 'previous' ? 'selected' : '' }}>Previous
                            </option>
                        </select>
                    </label>
                </form>
            </div>
        </div>
    @endif
    <div class="row py-2">
        <div class="col-md-12">
            @if ($courses->total() || ! Auth::user()->isStudent())
                <table id="my-courses" class="table table-striped ts">
                    <caption
                        class="">{{ sprintf("Showing results %s-%s of total %s Courses", $courses->firstItem(), $courses->lastItem(), $courses->total()) }}</caption>
                    <thead>
                    <tr class="tsTitles">
                        <th scope="col">#</th>
                        <th>Code</th>
                        <th>Title</th>
                        @if(Auth::user()->isAdmin())
                            <th>Instructor</th>@endif
                    </tr>
                    </thead>
                    <tbody class="tsGroup">
                    @foreach($courses as $i => $course)
                        <tr>
                            <th scope="col">{{ $i+1 }}</th>
                            <td>
                                @if(Auth::user()->can('course.view'))
                                    <a href="{{ url('/courses/' . $course->course_id . '/view') }}"
                                       title="View Course {{ $course->code }}"
                                       aria-label="View Course {{ $course->code }}">{{ $course->code }} <i
                                            class="material-icons icon-sm">link</i> </a>
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
            {!! "let cols = [{col: 1, order: 'asc'}, {col: 1, order: 'asc'}];" !!}
            @elseif (Auth::user()->isInstructor()) {{-- Instructor --}}
            {!! "let cols = [{col: 1, order: 'asc'}, {col: 1, order: 'asc'}];" !!}
            @else {{-- Student --}}
            {!! "let cols = [{col: 1, order: 'asc'}];" !!}
            @endif
            $('#my-courses').tablesorter({tablesorterColumns: cols});

            $('a.page-link').each((i, element) => {
                element.href += '&ac_year=' + urlParams.get('ac_year');
            });
        });
    </script>
@endsection
