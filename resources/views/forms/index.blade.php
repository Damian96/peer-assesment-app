@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('form.index') }}
@endsection

@php
    $csrf = csrf_field();
@endphp

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12">
            @if ($merged->isNotEmpty())
                <table id="my-forms" class="table table-striped table-responsive-sm ts">
                    <caption
                        class="">{{ sprintf("Showing results %s-%s of total %s Forms", $first, $last, $total) }}</caption>
                    <thead>
                    <tr class="tsTitles">
                        <th>#</th>
                        <th class="">Title</th>
                        <th class="">Session</th>
                        <th>Course</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody class="tsGroup">
                    @foreach($merged->all() as $i => $form)
                        <tr data-form-id="{{ $form->id }}" data-session-id="{{ $form->session_id > 0 ?? '0' }}">
                            <th scope="row">{{ ($i+1) }}</th>
                            <td class="">{{ strlen($form->title) > 50 ? substr($form->title, 0, 50) . '...' : $form->title }}</td>
                            <td class="">
                                @if ($form->session_id > 0)
                                    <a href="{{ url('/sessions/' . $form->session_id . '/view') }}"
                                       title="{{ $form->session_title }}"
                                       aria-label="{{ $form->session_title }}">{{ $form->session_title }} <i
                                            class="material-icons icon-sm">link</i></a>
                                @else<span class="text-muted">{{ 'N/A' }}</span>@endif
                            </td>
                            <td>
                                @if ($form->code)
                                    <a href="{{ url('/courses/' . $form->course_id . '/view' ) }}" target="_self">
                                        {{ $form->code }} <i class="material-icons icon-sm">link</i>
                                    </a>
                                @else<span class="text-muted">{{ 'N/A' }}</span>@endif
                            </td>
                            <td class="action-cell">
                                <a href="#" class="material-icons copy-form"
                                   title="Duplicate Form {{ $form->id }}"
                                   aria-label="Duplicate Form {{ $form->id }}">content_copy</a>
                                @if ($form->session_id > 0)
                                    <a href="{{ url('/forms/' . $form->id . '/edit') }}"
                                       class="material-icons text-warning"
                                       title="Update Form {{ $form->id }}"
                                       aria-label="Update Form {{ $form->id }}">edit</a>
                                    <form method="POST" action="{{ url('/forms/' . $form->id . '/delete') }}">
                                        @method('DELETE')
                                        {{ $csrf }}
                                        <button type="submit"
                                                class="btn btn-lg btn-link material-icons text-danger delete-form"
                                                data-title="Are you sure you want to delete this Form?"
                                                data-content="This action is irreversible."
                                                title="Delete {{ $form->id }}"
                                                aria-label="Delete {{ $form->id }}">delete_forever
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <h2>You do not have any Forms yet!</h2>
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

                if (data.length == 0) {
                    return false;
                }

                let sessions = data;

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
                    .append('{{ method_field('POST') }}')
                    .append('{{ $csrf }}');

                // console.debug(form, sessions);
            },
            url: "{{ url('/api/sessions/all?api_token=' . Auth::user()->api_token) }}&except={{ implode(',', $except) }}",
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
                let form = this.$content.find('form');
                this.$form = form;
                this.$select = form.find('select');
                let session_id = this.$target.closest('tr').attr('data-session-id');

                if (!this.$select[0].childElementCount) {
                    this.setTitle('Warning!');
                    this.setContent('You have not created any Sessions!');
                }

                if (parseInt(session_id) > 0) {
                    // Remove current form's session
                    this.$select.find(`option[value=${session_id}]`).remove();
                }

                // Replace appropriate form_id on form's action
                let form_id = this.$target.closest('tr').attr('data-form-id');
                form[0].setAttribute('action', form[0].getAttribute('action').replace(/#/i, form_id));

                // Initialize combobox
                this.$select.combobox();
                $('.ui-autocomplete').css('z-index', '100000000');
            }
        });
        @if ($merged->isNotEmpty())
        // Initialize table
        {!! "let cols = [{col: 1, order: 'asc'}, {col: 2, order: 'asc'}];" !!}
        $('#my-forms').tablesorter({
            tablesorterColumns: cols
        });
        @endif
    </script>
@endsection
