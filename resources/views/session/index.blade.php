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
                                    @if ($session->hasJoinedGroup(Auth::user()))
                                        <a href="{{ url('/sessions/' . $session->id . '/fill') }}"
                                           class="material-icons">assignment</a>
                                    @else
                                        @if (!$session->hasGroups())
                                            <a href="{{ url('#') }}"
                                               title="This Session does not have any groups!"
                                               aria-label="This Session does not have any groups!"
                                               class="material-icons text-muted">group</a>

                                        @else
                                            <a href="{{ url('#') }}"
                                               title="Join a group"
                                               data-id="{{ $session->id }}"
                                               aria-label="Join a group"
                                               class="material-icons join-group">group</a>
                                        @endif
                                        <a href="{{ url('#') }}"
                                           title="Add a group"
                                           data-id="{{ $session->id }}"
                                           aria-label="Add a group"
                                           class="material-icons add-group text-success">group_add</a>
                                    @endif
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
        // Create popup form
        var addForm = $(document.createElement('form'));
        addForm.attr('action', "{{ url('sessions/#/add-group') }}")
            .attr('method', 'POST');

        let error = $(document.createElement('span'));
        error.attr('class', 'invalid-feedback d-block');

        let group = $(document.createElement('div'));
        group.attr('class', 'form-group text-center mt-1');

        let label = $(document.createElement('label'));
        label.attr('class', 'form-control-md mr-2')
            .text('Title');

        let input = $(document.createElement('input'));
        input.attr('name', 'title')
            .addClass('form-control-md')
            .attr('required', 'true')
            .attr('aria-required', 'true')
            .attr('minlength', '10')
            .attr('maxlength', '255');

        group.append(label)
            .append(input)
            .append(error)
            .append('@method('POST')')
            .append('@csrf');
        addForm.append(group);

        var joinForm = $(document.createElement('form'));
        joinForm.attr('action', "{{ url('sessions/#/join-group') }}")
            .attr('method', 'POST');

        group = $(document.createElement('div'));
        group.attr('class', 'form-group text-center mt-1');

        label = $(document.createElement('label'));
        label.attr('class', 'form-control-md mr-2')
            .text('Group');

        let select = $(document.createElement('select'));
        select.attr('name', 'id')
            .addClass('form-control-md')
            .attr('required', 'true')
            .attr('aria-required', 'true');

        group.append(label)
            .append(select)
            .append(error.clone())
            .append('@method('POST')')
            .append('@csrf');
        joinForm.append(group);

        $.ajax({
            success: function (data) {
                // console.debug(data);
                window.groups = data.data;

                let o = document.createElement('option');
                for (let group of window.groups) {
                    o.value = group.id;
                    o.innerText = group.name;
                    select.append($(o));
                }
            },
            url: "{{ url('/api/groups/all?api_token=' . Auth::user()->api_token) }}",
            headers: {accept: 'application/json'},
        });
    </script>
    <script type="text/javascript" defer>
        $(function () {
            $('.delete-session').confirm({
                escapeKey: 'cancel',
                buttons: {
                    delete: {
                        text: 'Delete',
                        btnClass: 'btn-red',
                        action: function (e) {
                            this.$target.closest('form').submit();
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

            // Initialize table
            {!! "let cols = [{col: 0, order: 'asc'}, {col: 1, order: 'asc'}];" !!}
            $('#my-sessions').tablesorter({tablesorterColumns: cols});

            $('.add-group').confirm({
                title: 'Add & Join Group',
                content: $(addForm[0]),
                escapeKey: 'cancel',
                buttons: {
                    formSubmit: {
                        text: 'Add & Join',
                        btnClass: 'btn-blue btn-dup',
                        action: function () {
                            // console.debug(this);
                            if (this.$input[0].checkValidity()) {
                                this.$form.submit();
                                return true;
                            } else {
                                this.$input.addClass('is-invalid');
                                this.$error.text('The Group\'s title needs to be at least 10 characters!');
                                return false;
                            }
                        }
                    },
                    cancel: function () {
                    },
                },
                onContentReady: function () {
                    // let jc = this;
                    let form = this.$content.find('form');
                    let session_id = this.$target.attr('data-id');
                    this.$form = form;
                    this.$input = form.find('input[name="title"]');
                    this.$error = form.find('.invalid-feedback');

                    // Replace appropriate form_id on form's action
                    form[0].setAttribute('action', form[0].getAttribute('action').replace(/#/i, session_id));
                }
            });
            $('.join-group').confirm({
                title: 'Join Group',
                content: $(joinForm[0]),
                escapeKey: 'cancel',
                buttons: {
                    formSubmit: {
                        text: 'Add & Join',
                        btnClass: 'btn-blue btn-dup',
                        action: function () {
                            // console.debug(this);

                            if (parseInt(this.$select.value) > 0) {
                                this.$form.submit();
                                return true;
                            } else {
                                this.$select.addClass('is-invalid');
                                this.$error.text('Invalid Session!');
                                return false;
                            }
                        }
                    },
                    cancel: function () {
                    },
                },
                onContentReady: function () {
                    let form = this.$content.find('form');
                    let session_id = this.$target.attr('data-id');
                    this.$form = form;
                    this.$select = form.find('select');
                    this.$error = form.find('.invalid-feedback');

                    // Replace appropriate form_id on form's action
                    form[0].setAttribute('action', form[0].getAttribute('action').replace(/#/i, session_id));
                }
            });
        });
    </script>
@endsection
