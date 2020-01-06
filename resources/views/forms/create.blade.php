@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('form.create') }}
@endsection

<?php // @TODO add Javascript validation ?>

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <label class="form-control-sm">Select Session:</label>

            <select id="session_id"
                    class="custom-combobox">
                @foreach($sessions as $s)
                    <option value="{{ $s->id }}">{{ $s->title_full }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <form id="create-form" class="row question-editor mt-3" action="{{ url('/forms/store') }}" method="POST">
        @method('POST')
        @csrf

        <input type="hidden" name="session_id" value="" class="hidden">

        <div class="col-sm-12 col-md-12">
            <label class="form-control-sm">Add New Question:</label>
            <button id="multiple-choice" disabled type="button" class="btn btn-large btn-info question-type">
                <i class="material-icons">radio_button_checked</i>Multiple Choice
            </button>
            <button id="linear-scale" disabled type="button" class="btn btn-large btn-info question-type">
                <i class="material-icons">linear_scale</i>Linear Scale
            </button>
            <button id="paragraph" disabled type="button" class="btn btn-large btn-info question-type"><i
                    class="material-icons">format_align_justify</i>Paragraph
            </button>
            <button id="eval" disabled type="button" class="btn btn-large btn-info question-type">
                <i class="material-icons">account_circle</i>Peer Evaluation
            </button>
        </div>
        <!-- Main Form Data -->
        <div class="col-sm-12 col-md-12">
            <hr>
            <h4>Form Preview</h4>
            <div class="form-group">
                <label for="form-title">Title</label>
                <input type="text" name="title" required aria-required="true" maxlength="255"
                       placeholder="The form's main title" class="form-control">
            </div>
            <div class="form-group">
                <label for="form-subtitle">Subtitle <span class="text-muted">(leave blank for none)</span></label>
                <input type="text" name="subtitle" maxlength="255" placeholder="The form's main subtitle"
                       class="form-control">
            </div>
            <div class="form-group">
                <label for="form-footnote">Footnote <span class="text-muted">(leave blank for none)</span></label>
                <input type="text" name="footnote" maxlength="255" placeholder="The form's footnote"
                       class="form-control">
            </div>
        </div>
        <div id="card-container" class="container-fluid">
            @include('forms.card')
        </div>
        <div class="col-sm-12 col-md-12">
            <button type="submit" class="btn btn-block btn-primary" disabled>Submit</button>
        </div>
    </form>
@endsection

@section('end_footer')
    <script type="text/javascript">
        $(document).on('submit', 'form', function (event) {
            let form = $(event.target);
            form.validate({
                rules: {
                    title: {
                        required: true,
                        minlength: 5,
                        maxlength: 255,
                    },
                    subtitle: {
                        required: false,
                        minlength: 5,
                        maxlength: 255,
                    },
                    footnote: {
                        required: false,
                        minlength: 5,
                        maxlength: 255,
                    },
                },
                {{--messages: {--}}
                {{--    title: {--}}
                {{--        required: "{!! $messages['title.required'] !!}",--}}
                {{--        minlength: "{!! $messages['title.min'] !!}",--}}
                {{--        maxlength: "{!! $messages['title.max'] !!}"--}}
                {{--    },--}}
                {{--    --}}{{--status: {--}}
                {{--        --}}{{--    optional: true,--}}
                {{--        --}}{{--    required: "{!! $messages['status.required'] !!}",--}}
                {{--        --}}{{--},--}}
                {{--    instructions: {--}}
                {{--        required: "{!! $messages['instructions.required'] !!}",--}}
                {{--        maxlength: "{!! $messages['instructions.max'] !!}"--}}
                {{--    },--}}
                {{--    deadline: {--}}
                {{--        required: "{!! $messages['deadline.required'] !!}",--}}
                {{--        pattern: "{!! $messages['deadline.date_format'] !!}",--}}
                {{--    }--}}
                {{--}--}}
            });
        });
    </script>
@endsection
