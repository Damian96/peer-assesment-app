@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('sessions') }}
@endsection

@section('content')
    {{--    @if(!Auth::user()->isStudent())--}}
    {{--        <div class="row my-2">--}}
    {{--            <div class="col-md-12">--}}
    {{--                <form method="GET" class="form-inline" onchange="this.submit()">--}}
    {{--                    <input type="hidden" value="{{ request('page', 1) }}" class="hidden" name="page" id="page">--}}
    {{--                    <label for="status">Status--}}
    {{--                        <select id="status" name="status" class="ml-2 form-control-sm">--}}
    {{--                            <option value="enabled"{{ request('status', false) !== 'disabled' ? ' selected' : false }}>--}}
    {{--                                Enabled--}}
    {{--                            </option>--}}
    {{--                            <option value="disabled"{{ request('status', false) === 'disabled' ? ' selected' : false }}>--}}
    {{--                                Disabled--}}
    {{--                            </option>--}}
    {{--                        </select>--}}
    {{--                    </label>--}}
    {{--                </form>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    @endif--}}
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            @if ($sessions->isNotEmpty())
                <table id="my-sessions" class="table table-striped ts">
                    <caption
                        class="">{{ sprintf("Showing results %s-%s of total %s Sessions", $sessions->firstItem(), $sessions->lastItem(), $sessions->total()) }}</caption>
                    <thead>
                    <tr class="tsTitles">
                        <th>Title</th>
                        <th>Deadline</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody class="tsGroup">
                    @foreach($sessions as $session)
                        <tr>
                            <td>{{ $session->title_full }}</td>
                            <td>{{ $session->deadline_full }}</td>
                            @if (!\Illuminate\Support\Facades\Auth::user()->isStudent())
                                <td class="action-cell">
                                    <a href="{{ url('/sessions/' . $session->id . '/edit') }}"
                                       class="material-icons text-warning">edit</a>
                                    <form method="POST" action="{{ url('/sessions/' . $session->id . '/delete') }}"
                                          class="d-inline-block">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-lg btn-link material-icons text-danger delete-session"
                                                data-title="Are you sure you want to delete this Session?"
                                                data-content="This action is irreversible."
                                                title="Delete {{ $session->title }}"
                                                aria-label="Delete {{ $session->title }}">delete_forever
                                        </button>
                                    </form>
                                </td>
                            @else
                                <td class="action-cell">
                                    <a href="{{ url('/sessions/' . $session->id . '/fill') }}"
                                       class="material-icons">assignment</a>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $sessions->links() }}
            @else
                @if (Auth::user()->can('session.create'))
                    <h4 class="text-warning">You do not have any Sessions yet!</h4>
                    <p>
                        <a class="btn btn-primary" href="{{ url('/sessions/create/') }}"
                           title="Add Session"
                           aria-roledescription="Add Session">Add Session</a>
                    </p>
                @elseif (Auth::user()->isStudent())
                    <h4 class="text-success">Congratulations! You have submitted all registered Sessions.</h4>
                    <p class="text-justify">
                        We will notify you when there is a new Session, by sending you an email to:
                        <strong>{{ Auth::user()->email }}</strong>
                    </p>
                @endif
            @endif
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
        $(document).ready(function () {
            // Initialize table
            {!! "let cols = [{col: 0, order: 'asc'}, {col: 1, order: 'asc'}];" !!}
            $('#my-sessions').tablesorter({tablesorterColumns: cols});
        });
    </script>
@endsection
