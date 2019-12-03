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
                        <th class="text-center">Department</th>
                        <th class="text-center">Academic Year</th>
                    @else
                        <th>Title</th>
                        <th class="text-center">Department</th>
                        <th class="text-center">Academic Year</th>
                    @endif
                    @if(!Auth::user()->can('session.index', ['cid'=>$course->id]))
                        <th class="text-center">Instructor</th>
                    @else
                        <th class="text-right">Links</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                <tr>
                    @if (Auth::user()->isAdmin())
                        <th scope="row">{{ $course->id }}</th>
                        <td>{{ $course->instructor_name }}</td>
                        <td>{{ $course->title }}</td>
                        <td class="text-center">{{ $course->ac_year_pair }}</td>
                    @else
                        <td>{{ $course->title }}</td>
                        <td class="text-center">{{ $course->department_title }}</td>
                        <td class="text-center">
                            <select id="similarid" name="similarid"
                                    class="form-control-sm{{ $errors->has('similarid') ? ' is-invalid' : '' }}"
                                {{ count($similars) == 0 ? 'readonly' : '' }}>
                                <option
                                    value="---"{{ count($similars) == 0 ? ' selected' : '' }}>{{ $course->ac_year_pair }}</option>
                                @foreach($similars as $similar)
                                    <option
                                        value="{{ $similar->id }}"{{ old('similarid') == $similar->id }}>{{ $similar->ac_year_pair }}</option>
                                @endforeach
                            </select>
                        </td>
                    @endif
                    {{--                            TODO: add user avatar to link --}}
                    @if (Auth::user()->ownsCourse($course->id))
                        <td class="action-cell text-right">
                            <a href="{{ url($course->instructor()->id . '/show') }}"
                               class="material-icons text-info"
                               title="Show Students"
                               aria-label="Show Students">person</a>
                            @if(Auth::user()->can('course.edit', ['id'=>$course->id]) && !$course->copied())
                                <form method="POST" action="{{ url('/courses/' . $course->id . '/duplicate') }}">
                                    @method('POST')
                                    @csrf
                                    <button type="submit"
                                            id="copy-course"
                                            class="btn btn-lg btn-link material-icons text-warning"
                                            title="Copy to current Academic Year"
                                            data-title="Copy to current Academic Year?"
                                            data-content="This will create a duplicate."
                                            aria-label="Copy to current Academic Year">next_week
                                    </button>
                                </form>
                            @endif
                            @if(Auth::user()->can('course.edit', ['id'=>$course->id]))
                                <a href="{{ url('/courses/' . $course->id . '/edit') }}"
                                   class="material-icons text-warning"
                                   title="Update Course {{ $course->code }}"
                                   aria-label="Update Course {{ $course->code }}">edit</a>
                            @endif
                            @if(Auth::user()->can('session.index', ['cid'=>$course->id]))
                                <a href="{{ url('/courses/' . $course->id . '/sessions') }}"
                                   class="material-icons"
                                   title="View Sessions of {{ $course->code }}"
                                   aria-label="View Sessions of {{ $course->code }}">assignment</a>
                            @endif
                            @if(Auth::user()->can('course.delete', ['id'=>$course->id]))
                                <form method="POST" action="{{ url('/courses/' . $course->id . '/delete') }}">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit"
                                            id="delete-course"
                                            class="btn btn-lg btn-link material-icons text-danger"
                                            data-title="Are you sure you want to delete this course?"
                                            data-content="This action is irreversible."
                                            title="Delete {{ $course->code }}"
                                            aria-label="Delete {{ $course->code }}">delete_forever
                                    </button>
                                </form>
                            @endif
                        </td>
                    @else
                        <td class="text-center">
                            <a href="{{ url($course->instructor . '/show/') }}"
                               title="View Instructor's profile"
                               aria-label="View Instructor's profile">
                                {{ $course->instructor_name }}
                            </a>
                        </td>
                    @endif
                </tr>
                </tbody>
                @if (Auth::user()->isAdmin())
                    <tfoot>
                    <tr>
                        <td colspan="2">Created: {{ $course->create_date }}</td>
                        <td colspan="2">Updated: {{ $course->update_date }}</td>
                        <td colspan="1">Status: {{ $course->status_full }}</td>
                    </tr>
                    </tfoot>
                @endif
            </table>
        </div>
        @if (Auth::user()->can('course.edit', ['id' => $course->id]))
            <div class="col-sm-12 col-md-12">
                <a href="{{ url( '/courses/' . $course->id . '/add-student') }}"
                   class="btn btn-success">
                    Add Students to {{ $course->code }}
                </a>
                <a href="{{ url( '/sessions/create/' . $course->id ) }}"
                   class="btn btn-success">
                    Add Sessions to {{ $course->code }}
                </a>
            </div>
        @endif
    </div>
    <div class="row mt-3">
        @if (Auth::user()->ownsCourse($course->id))
            <div class="col-sm-12 col-md-12">
                @if ($course->students()->first())
                    <table class="table table-striped">
                        <caption></caption>
                        <thead>
                        <th>#</th>
                        <th>Name</th>
                        </thead>
                        <tbody>
                        @foreach($course->students()->getModels() as $s)
                            <tr>
                                <td scope="row"></td>
                                <td class="text-left">{{ $s->name }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <h3 class="text-warning my-3">This course does not have any Students</h3>
                @endif
            </div>
        @endif
    </div>
    <div class="row mt-3">
        @if (Auth::user()->ownsCourse($course->id))
            <div class="col-sm-12 col-md-12">
                @if ($course->sessions()->first())
                    <table class="table table-striped">
                        <caption></caption>
                        <thead>
                        <tr>
                            <th>#</th>
                            <th class="text-center">Title</th>
                            <th class="text-center">Deadline</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($course->sessions()->getModels() as $i => $s)
                            <tr>
                                <td scope="row">{{ $i+1 }}</td>
                                <td class="text-center">{{ $s->title }}</td>
                                <td class="text-center">{{ $s->deadline }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <h3 class="text-warning my-3">This course does not have any Sessions</h3>
                @endif
            </div>
        @endif
    </div>
@endsection

@section('end_footer')
    <script type="text/javascript" defer>
        $(function () {
            $('#similarid').on('change', function (e) {
                if (parseInt(this.value)) {
                    window.location.replace('{{ url('courses/') }}/' + this.value + '/view');
                    e.preventDefault();
                    return false;
                }
                return true;
            });
            $('#copy-course').confirm({
                escapeKey: 'close',
                buttons: {
                    copy: {
                        text: 'Copy',
                        btnClass: 'btn-warning',
                        action: function (e) {
                            this.$target.closest('form').submit();
                            // window.location.replace(this.$target.closest('form').attr('action'));
                            return true;
                        }
                    },
                    close: function () {
                    }
                },
                theme: 'material',
                type: 'red',
                typeAnimated: true,
            });
            $('#delete-course').confirm({
                escapeKey: 'close',
                buttons: {
                    delete: {
                        text: 'Delete',
                        btnClass: 'btn-red',
                        action: function (e) {
                            this.$target.closest('form').submit();
                            // window.location.replace(this.$target.closest('form').attr('action'));
                            return true;
                        }
                    },
                    close: function () {
                    }
                },
                theme: 'material',
                type: 'red',
                typeAnimated: true,
            });
        });
    </script>
@endsection
