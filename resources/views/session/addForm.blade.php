@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <?php // @TODO make jquery-ui selectable autocomplete ?>

            <label class="form-control-sm">Select Session:</label>

            <select id="session_id"
                    class="custom-combobox">
                @foreach($sessions as $s)
                    <option value="{{ $s->id }}">{{ $s->title_full }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <form class="row question-editor mt-3" target="{{ url('/sessions/form/store') }}" method="POST">
        @method('POST')
        @csrf

        <div class="col-sm-12 col-md-12">
            <label class="form-control-sm">Add New Question:</label>
            <button id="yes-no" disabled type="button" class="btn btn-large btn-dark question-type">Yes/No Answer
            </button>
            <button id="mark" disabled type="button" class="btn btn-large btn-warning question-type">Mark Answer (0-5)
            </button>
            <button id="text" disabled type="button" class="btn btn-large btn-info question-type">Text Answer</button>
        </div>
        <div class="col-sm-12 col-md-12">
            <hr>
            <h4>Form Preview</h4></div>
        <div class="card col-sm-12 col-md-12 p-0 my-2 d-none">
            <!-- Card Title -->
            <div class="card-title">
                <button class="btn btn-primary btn-block" type="button" data-toggle="collapse"
                        data-target=""
                        data-title=""
                        aria-expanded="false" aria-controls="">
                    QUESTION TYPE TITLE
                </button>
            </div>
            <!-- Card Body -->
            <div class="card-body collapse" id="">
                <div class="form-group question-title">
                    <label class="form-control-sm">Title</label>
                    <input type="text" name="question-title[]" id="" class="form-control" oninput="(function(e) {
                      let button = $(this).closest('.card').find('button');
                      let title = button.data('title') + ' - ' + this.value;
                      button.text(title);
                    }.bind(this, event))();">
                </div>
                <div class="form-group question-mark d-none">
                    <label for="question-mark[]" class="form-control-sm">Mark Range</label>
                    <div class="col-sm-12 col-md-12">
                        <span class="text-monospace d-inline">0</span>
                        <input type="range" name="question-mark[]" id="" step="5"
                               class="form-control-range d-inline w-75"
                               onchange="(function() { $(this).siblings('.val').text(this.value)}.bind(this))();"
                               value="10">
                        <span class="text-monospace d-inline">100</span>
                        Mark: <span class="d-inline val text-monospace font-weight-bold">10</span>
                    </div>
                </div>
            </div>
        </div>
    </form>
    {{--    <form method="POST" action="{{ url('/sessions/form/store') }}">--}}
    {{--        @method('POST')--}}
    {{--        @csrf--}}

    {{--        <input type="hidden" value="" name="questions"/>--}}
    {{--        <input type="hidden" value="" name="session_id"/>--}}
    {{--        <input type="hidden" value="" name="questions"/>--}}

    {{--        --}}{{--    <div class="form-group">--}}
    {{--        --}}{{--        <label class="form-control"></label>--}}
    {{--        --}}{{--    </div>--}}
    {{--    </form>--}}
@endsection

@section('end_footer')
    <script type="text/javascript">
        $(function () {
            // course autocomplete
            $('#session_id').combobox();
            @if ($errors->has('session_id'))
            $('#session_id')
                .addClass('is-invalid');
            @endif
            $('#session_id').next().on('autocompleteselect', function (e, ui) {
                $('input[name=session_id]').val(this.value);
                $('button.question-type').removeAttr('disabled');
                return true;
            });
            $('button.question-type').attr('disabled', true); // reset value for caching
            $('button.question-type').on('click', function (e) {
                let template = $('.card.d-none');
                let clone = template.clone();
                let title = `${this.innerText} #${$('.card').length}`;
                let id = title.replace(/[\/#\s\(\)]/g, '-');
                clone.find('button').text(title)
                    .attr('data-target', '#' + id)
                    .data('title', title)
                    .attr('aria-controls', id);
                clone.find('.collapse').attr('id', id);
                clone.removeClass('d-none');
                switch (this.id) {
                    case 'yes-no':
                        break;
                    case 'mark':
                        clone.find('.question-mark').removeClass('d-none').addClass('d-inline');
                        break;
                    case 'text':
                        break;
                    default:
                        return false;
                }
                $('.card:last-of-type').after(clone);
                return true;
            });
        });
    </script>
@endsection
