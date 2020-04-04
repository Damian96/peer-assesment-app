@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('form.edit', $form) }}
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <form name="{{ 'update-form' . $form->id }}" id="update-form" class="row question-editor mt-3"
                  action="{{ url("/forms/{$form->id}/update") }}" method="POST">
                @method('POST')
                @csrf

                <input type="hidden" name="form_id" value="{{ $form->id }}" class="hidden">
                <input type="hidden" name="session_id" value="{{ $form->session_id }}" class="hidden">

                @include('forms.addQuestion')
                <div class="col-sm-12 col-md-12">
                    <hr/>
                    @include('forms.formData', [
                        'form' => $form,
                        'errors' => $errors
                    ])
                </div>
                @php
                    /**
                     * @var array $questions
                     * @var array $old_questions
                     */
                    if (request()->old('question'))
                        $old_questions = array_slice(request()->old('question'), -(count(request()->old('question'))-count($questions)));
                    else
                        $old_questions = [];
                @endphp
                <div id="card-container" class="container-fluid">
                    @include('forms.card', [
                        'template' => true
                    ])
                    @include('forms.questionToolbar')
                    @foreach($questions as $q => $question)
                        @include('forms.card', [
                            'template' => false,
                            'title' => $question->title,
                            'count' => $question->id,
                            'question' => $question
                        ])
                    @endforeach
                    @foreach($old_questions as $i => $question)
                        @include('forms.card', [
                            'template' => false,
                            'title' => $question['title'],
                            'type' => $question['type'],
                            'count' => count($questions)+$i,
                            'question' => (object)$question
                        ])
                    @endforeach
                </div>
                <div class="col-sm-12 col-md-12 my-2">
                    <button type="submit" class="btn btn-block btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('end_footer')
    <script type="text/javascript" defer>
        @if ($errors->has('session_id'))
        $('#session_id')
            .addClass('is-invalid');
        @endif
    </script>
@endsection
