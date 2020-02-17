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
                    <div class="form-group">
                        <label for="form-title">Title</label>
                        <input type="text" name="title" required aria-required="true" maxlength="255"
                               value="{{ old('title', false) ? old('title') : $form->title }}"
                               placeholder="The form's main title" class="form-control">
                        <span class="invalid-feedback"><strong>{{ $errors->first('title') ?? '' }}</strong></span>
                    </div>
                    <div class="form-group">
                        <label for="form-subtitle">Subtitle <span
                                class="text-muted">(leave blank for none)</span></label>
                        <input type="text" name="subtitle" maxlength="255" placeholder="The form's main subtitle"
                               value="{{ old('subtitle', false) ? old('subtitle') : $form->subtitle }}"
                               class="form-control{{ $errors->first('subtitle') ?? 'is-invalid' }}">
                        <span
                            class="invalid-feedback {{ $errors->first('subtitle') ?? 'd-block' }}"><strong>{{ $errors->first('subtitle') ?? '' }}</strong></span>
                    </div>
                    <div class="form-group">
                        <label for="footnote">Footer Note <span
                                class="text-muted">(leave blank for none)</span></label>
                        <input type="text" name="footnote" maxlength="255" placeholder="The form's Foot Note"
                               value="{{ old('footnote', false) ? old('footnote') : $form->footnote }}"
                               class="form-control">
                        <span class="invalid-feedback"><strong>{{ $errors->first('footnote') ?? '' }}</strong></span>
                    </div>
                </div>
                <div id="card-container" class="container-fluid">
                    @include('forms.card', [
                        'template' => true
                    ])
                    @foreach($form->questions()->getModels() as $q => $question)
                        @include('forms.card', [
                            'template' => false,
                          'title' => $question->title,
                          'count' => $question->id,
                          'question' => $question
                        ])
                    @endforeach
                </div>
                <div class="col-sm-12 col-md-12">
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
