@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('sessions') }}
@endsection

@section('content')
    <div class="row">
        @if (Auth::user()->isStudent() && $sessions->isNotEmpty())
            <div class="col-xs-12 col-sm-12 col-md-12">
                <p class="lead">
                    Here are all your active Sessions that you have not yet submitted.
                    To complete a Session you must:<br>
                <ol>
                    <li>Join or Add & Join a Group</li>
                    <li>Fill the Session's Form</li>
                </ol>
                </p>
            </div>
        @endif
        <div class="col-xs-12 col-sm-12 col-md-12">
            @if ($sessions->isNotEmpty())
                <table id="my-sessions" class="table table-striped ts">
                    <caption
                        class="">{{ sprintf("Showing results %s-%s of total %s Sessions", $sessions->firstItem(), $sessions->lastItem(), $sessions->total()) }}</caption>
                    <thead>
                    <tr class="tsTitles">
                        <th>Title</th>
                        <th>Deadline</th>
                        @if (Auth::user()->isStudent())
                            <th>Group</th>
                        @endif
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody class="tsGroup">
                    @foreach($sessions as $session)
                        <tr>
                            <td>{{ $session->title_full }}</td>
                            <td>{{ $session->deadline_uk }}</td>
                            @if (Auth::user()->isStudent())
                                <td class="font-italic">{{ $session->hasJoinedGroup(Auth::user()) ? $session->getUserGroup(Auth::user())->name : 'N/A' }}</td>
                            @endif
                            @if (!\Illuminate\Support\Facades\Auth::user()->isStudent())
                                <td class="action-cell">
                                    <a href="{{ url('/sessions/' . $session->id . '/edit') }}"
                                       class="material-icons text-warning"
                                       title="Edit {{ $session->title }}"
                                       aria-label="Edit {{ $session->title }}">edit</a>
                                    <a href="{{ url('/sessions/' . $session->id . '/mark') }}"
                                       class="material-icons text-info"
                                       title="Mark {{ $session->title }}"
                                       aria-label="Mark {{ $session->title }}">group_work</a>
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
                                        @if ($session->canAddGroup())
                                            <a href="{{ url('#') }}"
                                               title="Add a group"
                                               data-id="{{ $session->id }}"
                                               aria-label="Add a group"
                                               class="material-icons add-group text-success">group_add</a>
                                        @endif
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="3" class="text-muted">Note: All Sessions deadlines are always exactly at midnight
                            (00:00).
                        </td>
                    </tr>
                    </tfoot>
                </table>
                {{ $sessions->links() }}
            @else
                @if (Auth::user()->can('session.create'))
                    <h4 class="text-dark">You do not have any Sessions yet!</h4>
                    <p>
                        <a class="btn btn-primary" href="{{ url('/sessions/create/') }}"
                           title="Add Session"
                           aria-roledescription="Add Session">Create Session</a>
                    </p>
                @elseif (Auth::user()->isStudent())
                    <h4 class="text-success">Congratulations! You have submitted all registered Sessions.</h4>
                    <p class="text-justify">
                        We will notify you when a new Session opens, by sending you an email to:
                        <strong>{{ Auth::user()->email }}</strong>
                    </p>

                    @if (Auth::user()->studentSessions()->exists())
                        <table class="table table-striped">
                            <caption></caption>
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th>Group Mark</th>
                                <th>Individual Mark</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(Auth::user()->studentSessions()->getModels() as $i => $ss)
                                @php
                                    $ss->mark = $ss->student()->first()->calculateMark($ss->session_id);
                                @endphp
                                <tr>
                                    <th scope="col">{{ $i+1 }}</th>
                                    <td>{{ $ss->group()->first() ? $ss->group()->first()->mark : 'N/A'  }}</td>
                                    <td>{{ $ss->mark > 0 ? $ss->mark : 'N/A' }}</td>
                                    {{--                                    <td>{{ $ss->student()->first()->calculateMark($ss->session_id) }}</td>--}}
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>

                            </tfoot>
                        </table>
                    @endif
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
            .attr('minlength', '5')
            .attr('maxlength', '255');

        let session = $('{!! html()->input('hidden', 'session_id') !!}');
        let method = $('@method('POST')');

        group.append(label)
            .append(input)
            .append(session)
            .append(error)
            .append(method)
            .append('@csrf');
        addForm.append(group);
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

            @if ($sessions->isNotEmpty())
            // Initialize table
            {!! "let cols = [{col: 0, order: 'asc'}, {col: 1, order: 'asc'}];" !!}
            $('#my-sessions').tablesorter({
                tablesorterColumns: cols
            });
            @endif

            $('.add-group').confirm({
                title: 'Add & Join Group',
                content: $(addForm[0]),
                escapeKey: 'cancel',
                buttons: {
                    formSubmit: {
                        text: 'Add & Join',
                        btnClass: 'btn-blue',
                        action: function () {
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
                    this.$session = form.find('input[name="session_id"]');
                    this.$error = form.find('.invalid-feedback');

                    this.$session.val(session_id);

                    // Replace appropriate form_id on form's action
                    form[0].setAttribute('action', form[0].getAttribute('action').replace(/#/i, session_id));
                }
            });
            $('.join-group').confirm({
                content: function () {
                    var jc = this;
                    return $.ajax({
                        url: `api/groups/${this.$target[0].dataset.id}/form?api_token={{ Auth::user()->api_token }}`,
                        dataType: 'text',
                        method: 'get',
                        headers: {
                            accept: 'text/plain'
                        }
                    }).done(function (response) {
                        // console.debug(response, this);
                        jc.setTitle('Join Group');
                        jc.setContent(response);

                        jc.$select = jc.$content.find('select');
                        jc.$form = jc.$content.find('form');
                        jc.$error = jc.$content.find('error');
                    }).fail(function () {
                        // console.debug(this, jc);
                        jc.setContent('Something went wrong.');
                    });
                },
                escapeKey: 'cancel',
                buttons: {
                    formSubmit: {
                        text: 'Join Group',
                        btnClass: 'btn-blue ',
                        action: function () {
                            this.$select = this.$content.find('select');
                            this.$form = this.$content.find('form');
                            this.$error = this.$content.find('error');

                            console.debug(this, parseInt(this.$select.value));

                            if (parseInt(this.$select.val()) > 0) {
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
            });
        });
    </script>
@endsection
