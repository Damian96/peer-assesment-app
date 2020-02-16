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
                        <td><a href="{{ '/' . $course->user_id . '/show' }}" title="Course Instructor"
                               aria-label="Course Instructor">{{ $course->instructor_name }}</a>
                        </td>
                        <td>{{ $course->title }}</td>
                        <td>{{ $course->department_title }}</td>
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
                    @if (Auth::user()->ownsCourse($course->id))
                        <td class="action-cell text-right">
                            @if (!$course->copied())
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
                                <form method="POST" action="{{ url('/courses/' . $course->id . '/delete') }}"
                                      class="d-inline-block">
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
                    <h3 class="my-3">Enrolled Students</h3>
                    <table class="table table-striped">
                        <caption>Enrolled Students to {{ $course->code }}</caption>
                        <thead>
                        <th scope="row">#</th>
                        <th>Name</th>
                        <th></th>
                        </thead>
                        <tbody>
                        @foreach($course->students()->getModels() as $i => $s)
                            <tr>
                                <th scope="row">{{ $i+1 }}</th>
                                <td class="text-left">{{ $s->name }}</td>
                                <td class="action-cell">
                                    <form method="POST"
                                          action="{{ url('/courses/' . $course->id . '/disenroll/') }}"
                                          class="d-inline-block">
                                        @method('DELETE')
                                        @csrf
                                        {{ html()->hidden('user_id', $s->user_id) }}
                                        <button type="submit"
                                                class="btn btn-lg btn-link material-icons text-danger disenroll-student"
                                                data-title="Are you sure you want to disenroll {{ $s->full_name }}?"
                                                data-content="This action is irreversible."
                                                title="Disenroll {{ $s->lname }}"
                                                aria-label="Disenroll {{ $s->lname }}">highlight_off
                                        </button>
                                    </form>
                                </td>
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
                    <h3>Linked Sessions</h3>
                    <table class="table table-striped">
                        <caption>This course has {{ $course->sessions()->count() }} Sessions</caption>
                        <thead>
                        <tr>
                            <th scope="row">ID</th>
                            <th class="text-center">Title</th>
                            <th class="text-center">Deadline</th>
                            <th class="text-center">Open Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($course->sessions()->orderBy('deadline', 'ASC')->getModels() as $i => $s)
                            <tr>
                                <th scope="row">{{ $s->id }}</th>
                                <td class="text-center">{{ $s->title }}</td>
                                <td class="text-center">{{ $s->deadline }}</td>
                                <td class="text-center">{{ $s->open_date }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <td class="text-center text-muted" colspan="4">Note: Sessions' deadline and open date are always
                            at midnight
                        </td>
                        </tfoot>
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
            $('.disenroll-student').confirm({
                escapeKey: 'close',
                buttons: {
                    delete: {
                        text: 'Disenroll',
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
