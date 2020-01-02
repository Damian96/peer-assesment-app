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
    <div class="row question-editor mt-3">
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
            <div class="card-title col-sm-12 col-md-12 p-0">
                <button class="btn btn-primary btn-block" type="button" data-toggle="collapse"
                        data-target=""
                        aria-expanded="false" aria-controls="">
                    QUESTION TYPE TITLE
                </button>
            </div>
            <!-- Card Body -->
            <div class="card-body collapse" id="">
                FORM HERE
            </div>
        </div>
    </div>
    <form method="POST" action="{{ url('/sessions/form/store') }}">
        @method('POST')
        @csrf

        <input type="hidden" value="" name="questions"/>
        <input type="hidden" value="" name="session_id"/>
        <input type="hidden" value="" name="questions"/>

        {{--    <div class="form-group">--}}
        {{--        <label class="form-control"></label>--}}
        {{--    </div>--}}
    </form>
@endsection

@section('end_footer')
    <script type="text/javascript" defer>
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
                    .attr('aria-controls', id);
                clone.find('.collapse').attr('id', id);
                switch (this.id) {
                    case 'yes-no':
                        break;
                    case 'mark':
                        break;
                    case 'text':
                        break;
                    default:
                        return false;
                }
                clone.removeClass('d-none');
                template.after(clone);
                return true;
            });
        });
    </script>
@endsection
