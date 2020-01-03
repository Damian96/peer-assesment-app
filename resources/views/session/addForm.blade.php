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
    <form class="row question-editor mt-3" action="{{ url('/sessions/form/store') }}" method="POST">
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
            <input type="hidden" value="" name="question-type[]" class="hidden">
            <!-- Card Title -->
            <div class="card-title">
                <div class="input-group">
                    <button class="btn btn-primary btn-block" type="button"
                            data-title="">
                        QUESTION TYPE TITLE
                    </button>
                    <div class="input-group-append float-right">
                        <i class="btn btn-sm btn-outline-danger material-icons delete-question" onclick="(function() {
                            $(this).closest('.card').slideUp('slow', function() {
                              this.remove();
                            }); }.bind(this, event))();">
                            delete
                        </i>
                        <i class="btn btn-sm btn-outline-light material-icons close-question" data-toggle="collapse"
                           data-target=""
                           aria-expanded="false" aria-controls="">
                            keyboard_arrow_down
                        </i>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body collapse pt-0" id="">
                <div class="form-group question-title">
                    <label class="form-control-sm">Title</label>
                    <input type="text" name="question-title[]" id="" class="form-control" oninput="(function(e) {
                      let button = $(this).closest('.card').find('.btn-block');
                      let title = button.data('title') + ' - ' + this.value;
                      button.text(title);
                    }.bind(this, event))();">
                </div>
                <div class="form-group question-mark d-none">
                    <label for="question-mark[]" class="form-control-sm">Max Mark</label>
                    <div class="col-sm-12 col-md-12">
                        <span class="text-monospace d-inline">5</span>
                        <input type="range" name="question-mark[]" id="" min="5" step="1" max="10"
                               class="form-control-range d-inline w-75"
                               onchange="(function() { $(this).siblings('.val').text(this.value)}.bind(this))();"
                               value="10">
                        Max Mark: <span class="d-inline val text-monospace font-weight-bold">10</span>
                    </div>
                </div>
                <div class="form-group question-mark-type d-none">
                    <div class="form-control-sm">Question Type</div>
                    <label for="question-mark-type[]" class="form-control-sm">
                    <span class="d-inline">Opinion <input class="form-control-sm" type="radio" value="opinion"
                                                          name="question-mark-type[]"></span>
                        <span class="d-inline"> Review <input class="form-control-sm" type="radio" value="review"
                                                              name="question-mark-type[]"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-12">
            <button type="submit" class="btn btn-block btn-primary">Submit</button>
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
                clone.find('.btn-block')
                    .data('title', title)
                    .text(title);
                clone.find('.close-question')
                    .attr('data-target', '#' + id)
                    .attr('aria-controls', id);
                clone.find('.collapse').attr('id', id);
                clone.removeClass('d-none');
                clone.find('input[type=hidden]').val(this.id);
                switch (this.id) {
                    case 'mark':
                        clone.find('.d-none').each(function () {
                            $(this).removeClass('d-none').addClass('d-block');
                        });
                        break;
                    case 'yes-no':
                    // break;
                    case 'text':
                        clone.find('.d-none').each(function () {
                            $(this).remove();
                        });
                        break;
                    default:
                        return false;
                }
                debugger;
                $('.card').last().after(clone);
                $('.card-body').on('shown.bs.collapse', function (e) {
                    $(this).closest('.card').find('.close-question').text('keyboard_arrow_up');
                    return true;
                });
                $('.card-body').on('hidden.bs.collapse', function (e) {
                    $(this).closest('.card').find('.close-question').text('keyboard_arrow_down');
                    return true;
                });
                return true;
            });
            $('button[type=submit]').on('click', function (e) {
                let template = $('.card.d-none');
                template.remove();
                return true;
            });
        });
    </script>
@endsection
