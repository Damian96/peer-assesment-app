@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('form.create') }}
@endsection

@section('content')
    <form name="{{ 'create-form' }}" id="create-form" class="row question-editor mt-3" role="form"
          action="{{ url('/forms/store') }}" method="POST">
        @method('POST')
        @csrf

        <div class="col-sm-12 col-md-12">
            <label class="form-control-sm">Add New Question:</label>
            <button id="multiple-choice" type="button" class="btn btn-large btn-info question-type">
                <i class="material-icons">radio_button_checked</i>Multiple Choice
            </button>
            <button id="linear-scale" type="button" class="btn btn-large btn-info question-type">
                <i class="material-icons">linear_scale</i>Linear Scale
            </button>
            <button id="paragraph" type="button" class="btn btn-large btn-info question-type"><i
                    class="material-icons">format_align_justify</i>Paragraph
            </button>
            <button id="eval" type="button" class="btn btn-large btn-info question-type">
                <i class="material-icons">account_circle</i>Peer Evaluation
            </button>
        </div>
        <!-- Main Form Data -->
        <div class="col-sm-12 col-md-12">
            <hr>
            {{--            <h4>Form Preview</h4>--}}
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
            @include('forms.card')
            @foreach(old('cards', []) as $q => $question)
                @php
                    $question = (object) $question;
                    $q_errors = isset($errors) ? $errors->get("*question*") : null;
                @endphp
                <div class="card col-sm-12 col-md-12 p-0 my-2" data-type="{{ $question->type }}">
                    <input name="question[{{ $q }}][type]" type="hidden" class="d-none">
                    <!-- Card Title -->
                    <div class="col-sm-12 col-md-12 bg-info py-2 px-3">
                        <h4 class="card-title d-inline" data-title="">{{ $question->title }}</h4>
                        <div class="btn-toolbar d-inline float-right">
                            <div class="btn-group btn-group-sm" role="toolbar">
                                <button type="button"
                                        tabindex="0"
                                        width="40"
                                        maxwidth="40"
                                        aria-label="Delete Question"
                                        class="btn btn-sm btn-outline-danger delete-question"><i class="material-icons">delete</i>
                                </button>
                            </div>
                            <div class="btn-group btn-group-sm" role="toolbar">
                                <button tabindex="0" type="button" class="btn btn-sm btn-outline-dark close-question"
                                        data-toggle="collapse"
                                        aria-label="Collapse Question"
                                        width="40"
                                        maxwidth="40"
                                        data-target="#{{ 'question-' . $q }}"
                                        aria-expanded="true"
                                        aria-controls="{{ '$question-'. $q }}"><i class="material-icons close-icon">keyboard_arrow_up</i>
                                </button>
                            </div>
                            <div class="btn-group btn-group-sm" role="toolbar">
                                <button type="button" tabindex="0" class="btn btn-sm btn-outline-dark moveup-question"
                                        aria-label="Move Question Up">
                                    <i class="material-icons">arrow_upward</i></button>
                            </div>
                            <div class="btn-group btn-group-sm" role="toolbar">
                                <button type="button" tabindex="0"
                                        aria-label="Move Question Down"
                                        class="btn btn-sm btn-outline-dark movedown-question"><i
                                        class="material-icons">arrow_downward</i></button>
                            </div>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div id="{{ 'question-'.$q }}" class="card-body collapse show pt-0">
                        <div class="form-group question-title">
                            <label class="form-control-sm">Title</label>
                            <input type="text" name="question[{{ $q }}][title]" class="form-control"
                                   value="{{ $question->title }}"
                                   required
                                   aria-required="true"
                                   maxlength="255"
                                   aria-errormessage="{{ $q_errors->get($q)->title }}"
                                   data-rule-required="true"
                                   data-msg-required="The Question should have a title!"
                                   data-rule-minlength="5"
                                   data-msg-minlength="The Question's title should be at least 5 characters long!"
                                   data-rule-maxlength="255"
                                   data-msg-maxlength="The Question's title should be at most 255 characters long!">
                            <span
                                class="invalid-feedback font-weight-bold"><strong>{{ $q_errors->get($q)->title }}</strong></span>
                        </div>
                        {{--                        <div class="form-group question-title">--}}
                        {{--                            <label class="form-control-sm">Subtitle <span--}}
                        {{--                                    class="text-muted">(leave blank for none)</span></label>--}}
                        {{--                            <input type="text" name="question[{{ $q }}][subtitle]" class="form-control"--}}
                        {{--                                   value="{{ $question->subtitle }}">--}}
                        {{--                            <span--}}
                        {{--                                class="invalid-feedback font-weight-bold"><strong>{{ $q_errors->get($q)->subtitle }}</strong></span>--}}
                        {{--                        </div>--}}
                        @if ($question->type == 'linear-scale')
                            <div class="form-group scale">
                                <label for="question[{{ $q }}][max]" class="form-control-sm">Maximum</label>
                                <input type="number"
                                       name="question[{{ $q }}][max]"
                                       value="{{ $question->max }}"
                                       min="2" max="10"
                                       required
                                       aria-required="true"
                                       data-rule-required="true"
                                       data-msg-required="The Question should have a maximum limit!"
                                       data-rule-maxlength="10"
                                       data-msg-maxlength="The Question's limit should be at most 10!"
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
                                                   class="btn btn-sm btn-danger delete-choice material-icons"
                                                   aria-label="Delete Choice">close</i>
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
