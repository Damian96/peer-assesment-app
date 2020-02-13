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
                        <i class="material-icons">account_circle</i>Peer Evaluation<br><i>(on a scale of 5)</i>
                    </button>
                    <button id="criteria" type="button" class="btn btn-large btn-info question-type">
                        <i class="material-icons">account_circle</i>Peer Evaluation<br><i>(distribute 100)</i>
                    </button>
                </div>
                <div class="col-sm-12 col-md-12">
                    <hr/>
                    <h4>Form Preview</h4>
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
                @include('forms.card')
                @foreach($form->questions()->getModels() as $q => $question)
                    <!-- Card -->
                        <div class="card col-sm-12 col-md-12 p-0 my-2" data-type="{{ $question->type }}">
                            <input name="question[{{ $q }}][type]" type="hidden" class="d-none"
                                   value="{{ $question->type }}">
                            <input name="question[{{ $q }}][id]" type="hidden" class="d-none"
                                   value="{{ $question->id }}">
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
                                                class="btn btn-sm btn-outline-danger delete-question"><i
                                                class="material-icons">delete</i>
                                        </button>
                                    </div>
                                    <div class="btn-group btn-group-sm" role="toolbar">
                                        <button tabindex="0" type="button"
                                                class="btn btn-sm btn-outline-dark close-question"
                                                data-toggle="collapse"
                                                aria-label="Collapse Question"
                                                width="40"
                                                maxwidth="40"
                                                data-target="#{{ 'question-' . $question->id }}"
                                                aria-expanded="true"
                                                aria-controls="{{ '$question-'. $question->id }}"><i
                                                class="material-icons close-icon">keyboard_arrow_up</i>
                                        </button>
                                    </div>
                                    <div class="btn-group btn-group-sm" role="toolbar">
                                        <button type="button" tabindex="0"
                                                class="btn btn-sm btn-outline-dark moveup-question"
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
                            <div id="{{ 'question-'.$question->id }}" class="card-body collapse show pt-0">
                                <div class="form-group question-title">
                                    <label class="form-control-sm">Title</label>
                                    <input type="text" name="question[{{ $q }}][title]" class="form-control"
                                           required
                                           aria-required="true"
                                           maxlength="100"
                                           aria-errormessage=""
                                           data-rule-required="true"
                                           data-msg-required="The Question should have a title!"
                                           data-rule-minlength="5"
                                           data-msg-minlength="The Question's title should be at least 5 characters long!"
                                           data-rule-maxlength="100"
                                           data-msg-maxlength="The Question's title should be at most 255 characters long!"
                                           value="{{ $question->title }}">
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
                                                        <i class="material-icons text-muted">radio_button_unchecked</i>
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
                                                    <div class="delete-choice col-xs-12 col-sm-1 col-md-1">
                                                        <i class="btn btn-sm btn-danger delete-choice material-icons">close</i>
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
