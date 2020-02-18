@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('form.create') }}
@endsection

@section('content')
    <p class="form-note">
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
            @include('forms.formData', [
                'errors' => $errors
            ])
        </div>
        <div id="card-container" class="container-fluid">
            @if ($errors->has('question'))
                <h5 class="text-danger d-block"><strong>{{ $errors->first('question') ?? '' }}</strong></h5>
                <hr>
            @endif
            @include('forms.questionToolbar')
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
