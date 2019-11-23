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
                        <td class="text-center">{{ $course->ac_year_pair }}</td>
                    @else
                        <td>{{ $course->title }}</td>
                        <td class="text-center">{{ $course->code }}</td>
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
                    <td class="action-cell text-right">
                        @if(Auth::user()->can('course.edit', ['id'=>$course->id]))
                            <a href="{{ url('/courses/' . $course->id . '/edit') }}" class="material-icons text-warning"
                               title="Update Course {{ $course->code }}"
                               aria-label="Update Course {{ $course->code }}">edit</a>
                        @endif
                        @if(Auth::user()->can('session.index', ['cid'=>$course->id]))
                            <a href="{{ url('/courses/' . $course->id . '/sessions') }}"
                               class="material-icons"
                               title="View Sessions of {{ $course->code }}"
                               aria-label="View Sessions of {{ $course->code }}">assignment</a>
                        @endif
                        @if(Auth::user()->can('session.index', ['cid'=>$course->id]))
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
        <div class="col-sm-12 col-md-12">
            <a href="{{ url( '/courses/' . $course->id . '/add-student') }}"
               class="btn btn-success">
                Add Students to {{ $course->code }}
            </a>
        </div>
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
