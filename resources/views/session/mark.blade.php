@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <table class="table table-striped ts">
                <caption></caption>
                <thead class="">
                <th scope="col">#</th>
                <th>Group Name</th>
                <th class="text-center">No. of Students</th>
                <th class="text-right">Actions</th>
                </thead>
                @foreach($groups as $i => $g)
                    <tbody class="">
                    <th scope="col">{{ $i }}</th>
                    <td>{{ $g->name }}</td>
                    <td class="text-center">{{ $g->students()->count() }}</td>
                    <td class="action-cell text-right">
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
                            <i class="material-icons text-success"
                               title="This Group has already been marked: [{{ $g->mark }}]">verified_user</i>
                        @endif
                    </td>
                    </tbody>
                @endforeach
            </table>
        </div>
    </div>
@endsection

@section('end_footer')
    <script type="text/javascript" defer>
        @if ($g->mark == 0)
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
        @endif
    </script>
@endsection
