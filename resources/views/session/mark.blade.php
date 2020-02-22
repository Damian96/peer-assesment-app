@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('session.mark', $session) }}
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <table id="my-groups" class="table table-striped ts">
                <caption>Showing results {{ count($groups) }} of total {{ count($groups) }} Groups</caption>
                <thead>
                <tr class="tsTitles">
                    <th scope="col">#</th>
                    <th>Group Name</th>
                    <th class="text-center">No. of Students</th>
                    <th class="text-center">Mark</th>
                    <th class="text-right">Actions</th>
                </tr>
                </thead>
                <tbody class="tsGroup">
                @foreach($groups as $i => $g)
                    <tr>
                        <th scope="col">{{ $i+1 }}</th>
                        <td>{{ $g->name }}</td>
                        <td class="text-center">{{ $g->students()->count() }}</td>
                        <td class="text-center">
                            @if ($g->mark > 0)
                                Mark: <span class="text-info">{{ $g->mark }}</span> / <span
                                    class="text-muted">100</span>
                            @else<span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td class="action-cell text-right">
                            <a href="#" class="show-students material-icons"
                               data-title="Show Students of {{ $g->name }}"
                               data-students="{{ implode(',', array_column($g->students()->selectRaw(\App\User::RAW_FULL_NAME)->get(['full_name'])->toArray(), 'full_name')) }}"
                               title="Show Students of {{ $g->name }}"
                               aria-label="Show Students of {{ $g->name }}">group</a>
                            @if ($g->mark == 0)
                                <form action="{{ url('groups/' . $g->id . '/store-mark') }}" method="POST">
                                    @method('POST')
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $g->id }}">
                                    <a href="#" title="Mark {{ $g->name }}" aria-label="Mark {{ $g->name }}"
                                       class="mark-group" data-id="{{ $g->id }}" data-name="{{ $g->name }}">
                                        <i class="material-icons text-warning">assignment</i>
                                    </a>
                                </form>
                            @else
                                <a href="#" class="show-marks material-icons"
                                   data-title="Show Marks of {{ $g->name }}"
                                   data-marks="{{ implode(',', $g->marks()) }}"
                                   data-students="{{ implode(',', array_column($g->students()->selectRaw(\App\User::RAW_FULL_NAME)->get(['full_name'])->toArray(), 'full_name')) }}"
                                   title="Show Marks of {{ $g->name }}"
                                   aria-label="Show Marks of {{ $g->name }}">receipt</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('end_footer')
    <script type="text/javascript" defer>
        $(function () {
            let label = $(document.createElement('label'));
            label.attr('for', 'mark-popup')
                .addClass('form-control-sm flex-grow-1');
            let input = $(document.createElement('input'));
            input.attr('name', 'mark')
                .attr('id', 'mark-popup')
                .addClass('form-control flex-grow-1')
                .attr('min', '1')
                .attr('aria-valuemin', '1')
                .attr('max', '100')
                .attr('aria-valuemax', '100')
                .attr('type', 'number');
            let feedback = $(document.createElement('span'));
            feedback.addClass('invalid-feedback d-block my-2');
            $('.mark-group').confirm({
                title: '',
                content: '',
                escapeKey: 'cancel',
                buttons: {
                    formSubmit: {
                        text: 'Mark',
                        btnClass: 'btn-blue btn-dup',
                        action: function () {
                            console.debug(this);
                            if (parseInt(this.$input.val()) > 0 && parseInt(this.$input.val()) <= 100) {
                                this.$form.submit();
                                return true;
                            } else {
                                this.$feedback.text("The Group's mark should be between 1 and 100 (inclusive)!");
                                this.$input.addClass('is-invalid');
                            }
                            return false;
                        }
                    },
                    cancel: function () {
                    },
                },
                $feedback: feedback.clone(),
                $input: input.clone(),
                onContentReady: function () {
                    console.debug(this);
                    this.$form = this.$target.closest('form').clone();
                    this.$form.find('a').remove();
                    label.text('Mark (0-100]');
                    this.$form.append(label.clone())
                        .append(this.$input)
                        .addClass('d-flex my-2');
                    this.setTitle('Mark ' + this.$target[0].dataset.name);
                    this.$content.html('');
                    this.$content.append(this.$form)
                        .append(this.$feedback);
                }
            });
        });
        $('.show-marks').on('click', function () {
            let marks = $(this).attr('data-marks').split(',');
            let names = $(this).attr('data-students').split(',');
            let marksOl = $(document.createElement('ol'));

            for (let i = 0; i < marks.length; i++) {
                let li = document.createElement('li');
                li.textContent = `${names[i]}: ${marks[i]}/100`;
                marksOl.append($(li));
            }

            // console.debug(marksOl, marks, names);
            $.alert({
                escapeKey: 'ok',
                title: $(this).attr('data-title'),
                content: marksOl,
            });
        });
        $('.show-students').on('click', function () {
            let names = $(this).attr('data-students').split(',');
            let marksOl = $(document.createElement('ol'));

            for (let i = 0; i < names.length; i++) {
                let li = document.createElement('li');
                li.textContent = `${names[i]}`;
                marksOl.append($(li));
            }

            $.alert({
                escapeKey: 'ok',
                title: $(this).attr('data-title'),
                content: marksOl,
            });
        });
        @if (!empty($groups))
        // Initialize table
        {!! "let cols = [{col: 1, order: 'asc'}, {col: 2, order: 'asc'}];" !!}
        $('#my-groups').tablesorter({
            tablesorterColumns: cols
        });
        @endif
    </script>
@endsection
