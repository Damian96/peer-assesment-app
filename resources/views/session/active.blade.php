@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('session.active', $sessions) }}
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12">
            {{--            <legend>There are {{ count($sessions) }} sessions active.</legend>--}}
            <table class="table table-striped">
                <caption
                    class="">{{ sprintf("Showing results %s-%s of total %s Sessions", $sessions->firstItem(), $sessions->lastItem(), $sessions->total()) }}</caption>
                <thead>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Course</th>
                    <th>Deadline</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($sessions as $k => $s)
                    <tr>
                        <th scope="row">{{ ($sessions->firstItem()+$k) }}</th>
                        @if($s->course)
                            <td>
                                <a href="{{ url('courses/' . $s->course->id . '/view') }}"
                                   title="{{ $s->course->code }}">
                                    {{ $s->course->code }}
                                </a>
                            </td>
                        @else
                            <td class="text-muted">N/A</td>
                        @endif
                        <td>{{ $s->deadline_full }}</td>
                        <td class="action-cell">
                            @if(Auth::user()->can('session.edit', ['id'=>$s->course_id]))
                                <a href="{{ url('/sessions/' . $s->id . '/edit') }}"
                                   class="material-icons text-warning"
                                   title="Update Session {{ $s->title }}"
                                   aria-label="Update Session {{ $s->title }}">edit</a>
                                <form method="POST" action="{{ url('/sessions/' . $s->id . '/delete') }}"
                                      class="d-inline-block">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-lg btn-link material-icons text-danger delete-session"
                                            data-title="Are you sure you want to delete this Session?"
                                            data-content="This action is irreversible."
                                            title="Delete {{ $s->title }}"
                                            aria-label="Delete {{ $s->title }}">delete_forever
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $sessions->links() }}
        </div>
    </div>
@endsection


@section('end_footer')
    <script type="text/javascript">
        $(function () {
            $('.delete-session').confirm({
                escapeKey: 'cancel',
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
                    cancel: function () {
                    }
                },
                theme: 'material',
                type: 'red',
                typeAnimated: true,
            });
        });
    </script>
@endsection
