@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('form.create') }}
@endsection

@section('content')
    <p class="text-info text-justify">
        Note: Form Templates are not associated to any Session they are only used as
        pre-defined templates that <strong>are associated to each Instructor Account</strong>.
        <strong>They can be duplicated into other Sessions.</strong></p>
    <form name="{{ 'create-form' }}" id="create-form" class="row question-editor mt-3" role="form"
          action="{{ url('/forms/store') }}" method="POST">
        @method('POST')
        @csrf
        @include('forms.addQuestion')


        <div class="col-sm-12 col-md-12">
            <hr>
            <div class="form-group">
                <label id="form-title" for="form-title">Title</label>
                <input type="text" name="title" placeholder="The form's main title"
                       class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                       required aria-required="true" maxlength="255"
                       value="{{ old('title', false) }}"
                       aria-labelledby="form-title"
                       aria-errormessage="{{ $errors->first('title') ?? '' }}"
                       data-rule-required="true"
                       data-msg-required="{{ $messages['title.required'] }}"
                       data-rule-minlength="10"
                       data-msg-minlength="{{ $messages['title.min'] }}"
                       data-rule-maxlength-="255"
                       data-msg-maxlength="{{ $messages['title.max'] }}">
                <span
                    class="invalid-feedback font-weight-bold"><strong>{{ $errors->first('title') ?? '' }}</strong></span>
            </div>
            <div class="form-group">
                <label for="subtitle">Subtitle <span class="text-muted">(leave blank for none)</span></label>
                <input type="text" name="subtitle" placeholder="The form's main subtitle"
                       class="form-control"
                       value="{{ old('subtitle', false) }}"
                       maxlength="255"
                       aria-errormessage="{{ $errors->first('subtitle') ?? '' }}"
                       data-rule-minlength="10"
                       data-msg-minlength="{{ $messages['subtitle.min'] }}"
                       data-rule-maxlength-="255"
                       data-msg-maxlength="{{ $messages['subtitle.max'] }}">
                <span class="invalid-feedback font-weight-bold"><strong>{{ $errors->first('subtitle') ?? '' }}</strong></span>
            </div>
            <div class="form-group">
                <label for="form-footnote">Footnote <span class="text-muted">(leave blank for none)</span></label>
                <input type="text" name="footnote" placeholder="The form's footnote"
                       class="form-control"
                       value="{{ old('footnote', false) }}"
                       maxlength="255"
                       aria-errormessage="{{ $errors->first('footnote') ?? '' }}"
                       data-rule-maxlength-="255"
                       data-msg-maxlength="{{ $messages['footnote.max'] }}">
                <span class="invalid-feedback font-weight-bold"><strong>{{ $errors->first('footnote') ?? '' }}</strong></span>
            </div>
        </div>
        <div id="card-container" class="container-fluid">
            @if ($errors->has('question'))
                <h5 class="text-danger d-block"><strong>{{ $errors->first('question') ?? '' }}</strong></h5>
                <hr>
            @endif
            @include('forms.card', ['template' => true])
            @foreach(old('cards', []) as $q => $question)
                @php
                    $question = (object) $question;
                    $q_errors = isset($errors) ? $errors->get("*question*") : null;
                @endphp
                @include('forms.card', [
                    'template' => false,
                    'title' => $question->title,
                    'count' => $question->id,
                    'question' => $question
                ])
            @endforeach
        </div>
        <div class="col-sm-12 col-md-12">
            <hr>
            <button type="submit" class="btn btn-block btn-primary">Create</button>
        </div>
    </form>
@endsection

@section('end_footer')
    <?php // @TODO FINISH Javascript validation ?>
    <script type="text/javascript">
        $(document).on('focusout change', 'input, select, textarea', function () {
            return $(this).valid();
        });
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
                messages: {
                    title: {
                        required: "{!! $messages['title.required'] !!}",
                        minlength: "{!! $messages['title.min'] !!}",
                        maxlength: "{!! $messages['title.max'] !!}"
                    },
                    subtitle: {
                        required: "{!! $messages['subtitle.required'] !!}",
                        minlength: "{!! $messages['subtitle.min'] !!}",
                        maxlength: "{!! $messages['subtitle.max'] !!}"
                    },
                    footnote: {
                        required: "{!! $messages['footnote.string'] !!}",
                        maxlength: "{!! $messages['footnote.max'] !!}"
                    },
                }
            });
            return form.valid();
        });
    </script>
@endsection
