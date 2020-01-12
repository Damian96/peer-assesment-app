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
    <form name="{{ 'create-form' }}" id="create-form" class="row question-editor mt-3"
          action="{{ url('/forms/store') }}" method="POST">
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
            @foreach(old('cards', []) as $q => $question)
                @php
                    $question = (object) $question;
                @endphp
                <div class="card col-sm-12 col-md-12 p-0 my-2" data-type="{{ $question->type }}">
                    <input name="question[{{ $q }}][type]" type="hidden" class="d-none">
                    <!-- Card Title -->
                    <div class="col-sm-12 col-md-12 bg-info py-2 px-3">
                        <h4 class="card-title d-inline" data-title="">{{ $question->title }}</h4>
                        <div class="btn-toolbar d-inline float-right">
                            <div class="btn-group btn-group-sm" role="toolbar" aria-label="Delete Question">
                                <button type="button"
                                        tabindex="0"
                                        class="btn btn-sm btn-outline-danger delete-question"><i class="material-icons">delete</i>
                                </button>
                            </div>
                            <div class="btn-group btn-group-sm" role="toolbar" aria-label="Collapse Question">
                                <button tabindex="0" type="button" class="btn btn-sm btn-outline-dark close-question"
                                        data-toggle="collapse"
                                        data-target="#{{ 'question-' . $q }}"
                                        aria-expanded="true"
                                        aria-controls="{{ '$question-'. $q }}"><i class="material-icons close-icon">keyboard_arrow_up</i>
                                </button>
                            </div>
                            <div class="btn-group btn-group-sm" role="toolbar" aria-label="Move Question Up">
                                <button type="button" tabindex="0" class="btn btn-sm btn-outline-dark moveup-question">
                                    <i class="material-icons">arrow_upward</i></button>
                            </div>
                            <div class="btn-group btn-group-sm" role="toolbar" aria-label="Move Question Down">
                                <button type="button" tabindex="0"
                                        class="btn btn-sm btn-outline-dark movedown-question"><i
                                        class="material-icons">arrow_downward</i></button>
                            </div>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div id="{{ 'question-'.$q }}" class="card-body collapse show pt-0">
                        <div class="form-group question-title">
                            <label class="form-control-sm">Title</label>
                            <input type="text" name="question[{{ $q }}][title]" class="form-control" oninput="(function(e) {
                      let button = $(this).closest('.card').find('.btn-block[data-title]');
                      let title = button.data('title') + ' - ' + this.value;
                      button.text(title);
                    }.bind(this, event))();" required aria-required="true"
                                   value="{{ $question->title }}">
                        </div>
                        <div class="form-group question-title">
                            <label class="form-control-sm">Subtitle <span
                                    class="text-muted">(leave blank for none)</span></label>
                            <input type="text" name="question[{{ $q }}][subtitle]" class="form-control"
                                   value="{{ $question->subtitle }}">
                        </div>
                        @if ($question->type == 'linear-scale')
                            <div class="form-group scale">
                                <label for="question[{{ $q }}][max]" class="form-control-sm">Maximum</label>
                                <input type="number"
                                       name="question[{{ $q }}][max]"
                                       value="{{ $question->max }}"
                                       min="2" max="10"
                                       required
                                       aria-required="true"
                                       class="form-control-sm"
                                       onchange="(function(e) { $(this).closest('.form-group').next().find('.max-num').text(this.value)}.bind(this, event))();">
                            </div>
                            <div class="form-group scale my-3">
                                <label for="question[{{ $q }}][minlbl]" class="form-control-sm">1:
                                    <input type="text" name="question[{{ $q }}][minlbl]"
                                           placeholder="Highly Disagree"
                                           required
                                           aria-readonly="true" class="form-text d-inline"
                                           value="{{ $question->minlbl }}"></label>
                                <label for="question[{{ $q }}][maxlbl]" class="form-control-sm"><span
                                        class="max-num d-inline">{{ $question->max }}</span>
                                    <input type="text" name="question[{{ $q }}][maxlbl]"
                                           placeholder="Highly Agree"
                                           required
                                           aria-required="true" class="form-text d-inline"
                                           value="{{ $question->maxlbl }}"></label>
                            </div>
                        @elseif ($question->type == 'multiple-choice')
                            <div class="form-group multiple my-3">
                                <div class="col-xs-12 col-sm-12 col-md-12 choice-container">
                                    @foreach($question->choices as $j => $choice)
                                        <div class="row choice">
                                            <div class="col-xs-5 col-sm-5 col-md-5 text-center overflow-hidden">
                                                <i tabindex="0" class="text-muted">radio_button_unchecked</i>
                                                <label for="question[{{ $q }}][choices][]"
                                                       class="form-control-sm"
                                                       style="max-width: 90%; max-height: 20px; overflow: hidden;">
                                                    {{ $choice }}</label>
                                            </div>
                                            <div class="col-xs-5 col-md-5 text-left">
                                                <input class="form-control-sm"
                                                       name="question[{{ $q }}][choices][]"
                                                       type="text"
                                                       placeholder="choice"
                                                       aria-placeholder="choice"
                                                       value="{{ $choice }}"
                                                       maxlength="31"
                                                       required aria-required="true"
                                                       oninput="(function(e) {
                                        $(this).closest('div').prev().find('label')[0].lastChild.textContent = this.value;
                                   }.bind(this, event)());">
                                            </div>
                                            <div class="col-xs-12 col-sm-1 col-md-1">
                                                <i tabindex="0"
                                                   class="btn btn-sm btn-danger delete-choice material-icons">close</i>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-sm-6 offset-sm-3 offset-md-3 col-md-6">
                                    <div class="btn btn-block btn-secondary add-choice">
                                        <i class="material-icons">add_circle_outline</i>Add Option
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="col-sm-12 col-md-12">
            <button type="submit" class="btn btn-block btn-primary" disabled>Create</button>
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
                }
            });
        });
    </script>
@endsection
