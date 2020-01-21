@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('form.index') }}
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12">
            @if ($forms->isNotEmpty())
                <table class="table table-striped">
                    <caption
                        class="">{{ sprintf("Showing results %s-%s of total %s Forms", $forms->firstItem(), $forms->lastItem(), $forms->total()) }}</caption>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Session</th>
                        <th>Course</th>
                        <th>Mark</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($forms as $i => $form)
                        <tr data-form-id="{{ $form->id }}" data-session-id="{{ $form->session_id }}">
                            <th scope="row">{{ ($i+1) }}</th>
                            <td>{{ strlen($form->title) > 50 ? substr($form->title, 0, 50) . '...' : $form->title }}</td>
                            <td>
{{--                                <a href="{{ url('/sessions/' . $form->session_id . '/view' ) }}" target="_self">--}}
                                    {{ $form->title_full }}
                                {{--                                </a>--}}
                            </td>
                            <td>
                                <a href="{{ url('/courses/' . $form->course_id . '/view' ) }}" target="_self">
                                    {{ $form->code }}
                                </a>
                            </td>
                            <td class="{{ $form->mark == 0 ? 'text-warning' : '' }}">{{ $form->mark == 0 ? 'Not Filled' : $form->mark }}</td>
                            <td class="action-cell">
                                <a href="#" class="material-icons copy-form"
                                   title="Duplicate Form {{ $form->title }}"
                                   aria-label="Duplicate Form {{ $form->title }}">content_copy</a>
                                <a href="{{ url('/forms/' . $form->id . '/edit') }}" class="material-icons text-warning"
                                   title="Update Form {{ $form->title }}"
                                   aria-label="Update Form {{ $form->title }}">edit</a>
                                <form method="POST" action="{{ url('/forms/' . $form->id . '/delete') }}"
                                      class="d-inline-block">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-lg btn-link material-icons text-danger delete-form"
                                            data-title="Are you sure you want to delete this Form?"
                                            data-content="This action is irreversible."
                                            title="Delete {{ $form->title }}"
                                            aria-label="Delete {{ $form->title }}">delete_forever
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $forms->links() }}
            @else
                <h2>You do not have own any Forms yet!</h2>
                <p>
                    <a class="btn btn-primary" href="{{ url('/forms/create/') }}"
                       title="Add Form"
                       aria-roledescription="Add Form">Create Form</a>
                </p>
            @endif
        </div>
    </div>
@endsection

@section('end_head')
    <script type="text/javascript">
        var form = $(document.createElement('form'));
        $.ajax({
            success: function (data) {
                console.debug(data);
                let sessions = data.data;

                // Create popup form
                form.attr('action', "{{ url('forms/#/duplicate') }}")
                    .attr('method', 'POST');

                form.append(document.createElement('div'));
                form.find('div')
                    .addClass('form-group')
                    .append(document.createElement('label'));
                form.find('label')
                    .addClass('form-control-sm')
                    .text('Session');

                let select = $(document.createElement('select'));
                select.attr('name', 'session_id');

                // Populate Session select with data
                sessions.forEach(function (select, ses, i) {
                    let opt = document.createElement('option');
                    opt.value = ses.id;
                    opt.textContent = ses.title;
                    select.append(opt);
                }.bind(null, select));

                form.find('.form-group')
                    .append(select)
                    .append('@method('POST')')
                    .append('@csrf');

                // console.debug(form, sessions);
            },
            url: "{{ url('/api/sessions/all?api_token=' . Auth::user()->api_token) }}",
            headers: {
                accept: 'application/json',
            },
        });
    </script>
@endsection

@section('end_footer')
    <script type="text/javascript">
        $('.delete-form').confirm({
            title: 'Are you sure you want to delete the form?',
            content: 'This action is irreversible.',
            escapeKey: 'cancel',
            closeIcon: true,
            buttons: {
                delete: {
                    text: 'Delete',
                    btnClass: 'btn-red',
                    action: function (e) {
                        // console.debug(this, e);
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
    </script>
    <script type="text/javascript" defer>
        // console.debug(form);
        $('.copy-form').confirm({
            title: 'Duplicate Form',
            content: $(form[0]),
            escapeKey: 'cancel',
            buttons: {
                formSubmit: {
                    text: 'Duplicate',
                    btnClass: 'btn-blue btn-dup',
                    action: function () {
                        var jc = this;
                        if (parseInt(jc.$content.find('select').val()) > 0) {
                            // jc.$$formSubmit.trigger('click');
                            this.$form.submit();
                            return true;
                        }
                        return false;
                    }
                },
                cancel: function () {
                },
            },
            onContentReady: function () {
                let jc = this;
                let form = this.$content.find('form');
                let session_id = jc.$target.closest('tr').attr('data-session-id');
                let form_id = jc.$target.closest('tr').attr('data-form-id');

                this.$form = form;
                this.$select = form.find('select');

                // Remove current form's session
                this.$select.find(`option[value=${session_id}]`).remove();

                // Initialize combobox
                this.$select.combobox();
                $('.ui-autocomplete').css('z-index', '100000000');

                // Replace appropriate form_id on form's action
                form[0].setAttribute('action', form[0].getAttribute('action').replace(/#/i, form_id));
                // console.debug(jc, form);
            }
        });
    </script>
@endsection
