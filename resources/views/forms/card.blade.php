<div class="{{ $template ? 'template' : null }} card col-xs-12 col-sm-12 col-md-12 p-0 my-2">
    <input name="question[{{ $count ?? '#' }}][type]" type="hidden" class="d-none"
           value="{{ !$template ? $question->type : null }}">
    <!-- Card Title -->
    <div class="col-sm-12 col-md-12 py-2 px-3 card-title-container">
        <h4 class="card-title d-inline"><i class="material-icons">@php
                    /** @var bool $template */
                    /** @var \stdClass $question */
                    if (!$template) {
                        switch ($question->type)
                        {
                            case 'multiple-choice':
                                echo 'radio_button_unchecked';
                                break;
                                case 'linear-scale':
                                    echo 'linear_scale';
                                    break;
                                    case 'paragraph':
                                        echo 'format_align_justify';
                                        break;
                                        case 'eval':
                                            echo 'filter_5';
                                            break;
                                            case 'criteria':
                                                echo 'account_circle';
                                                break;
                        }
                    }
                @endphp</i><span
                class="card-title-content">&nbsp;{{ $question->title ?? '' }}</span></h4>
        <div class="btn-toolbar d-inline float-right">
            <div class="btn-group btn-group-sm tip" role="toolbar" data-tip="Delete Question">
                <button type="button"
                        tabindex="0"
                        aria-label="Delete Question"
                        class="btn btn-sm btn-outline-danger delete-question"><i class="material-icons">delete</i>
                </button>
            </div>
            <div class="btn-group btn-group-sm tip" role="toolbar" data-tip="Collapse Question">
                <button tabindex="0" type="button" class="btn btn-sm btn-outline-dark close-question"
                        aria-label="Collapse Question"
                        data-toggle="collapse"
                        data-target="#collapse-{{ $count ?? '' }}"
                        aria-expanded="true"
                        aria-controls=""><i class="material-icons close-icon">keyboard_arrow_up</i></button>
            </div>
            <div class="btn-group btn-group-sm tip" role="toolbar" data-tip="Move Question Up">
                <button type="button" tabindex="0" class="btn btn-sm btn-outline-dark moveup-question"><i
                        class="material-icons"
                        aria-label="Move Question Up"
                    >arrow_upward</i></button>
            </div>
            <div class="btn-group btn-group-sm tip" role="toolbar" data-tip="Move Question Down">
                <button type="button" tabindex="0" class="btn btn-sm btn-outline-dark movedown-question"
                        aria-label="Move Question Down"><i
                        class="material-icons">arrow_downward</i></button>
            </div>
        </div>
    </div>
    <!-- Card Body -->
    <div class="card-body collapse show pt-0" id="collapse-{{ $count ?? '' }}">
        <div class="form-group question-title">
            <label class="form-control-sm">Title</label>
            <input type="text" name="question[{{ $count ?? '' }}][title]" class="form-control"
                   value="{{ $question->title ?? '' }}"
                   required aria-required="true"
                   maxlength="100"
                   data-rule-required="true"
                   data-msg-required="The Question should have a title!"
                   data-rule-minlength="5"
                   data-msg-minlength="The Question's title should be at least 5 characters long!"
                   data-rule-maxlength="100"
                   data-msg-maxlength="The Question's title should be at most 255 characters long!">
            <span class="invalid-feedback"><strong></strong></span>
        </div>
        <div class="form-group scale {{ (!$template && $question->type != 'linear-scale') ? 'd-none' : '' }}">
            <label for="question[{{ $count ?? '' }}][max]" class="form-control-sm">Maximum</label>
            <input type="number"
                   name="question[{{ $count ?? '' }}][max]"
                   class="form-control-sm"
                   value="{{ $question->max ?? '' }}"
                   min="2" max="10"
                   required
                   aria-required="true"
                   data-rule-required="true"
                   onchange="(function(e) { $(this).closest('.form-group').next().find('.max-num').text(this.value)}.bind(this, event))();">
        </div>
        <div class="form-group scale my-3 {{ (!$template && $question->type != 'linear-scale') ? 'd-none' : '' }}">
            <label for="question[{{ $count ?? '' }}][minlbl]" class="form-control-sm">1:
                <input type="text" name="question[{{ $count ?? '' }}][minlbl]"
                       placeholder="Highly Disagree"
                       value="{{ $question->minlbl ?? '' }}"
                       required
                       aria-readonly="true" class="form-text d-inline"></label>
            <label for="question[{{ $count ?? '' }}][maxlbl]" class="form-control-sm"><span
                    class="max-num d-inline">{{ $question->max ?? '' }}</span>
                <input type="text" name="question[{{ $count ?? '' }}][maxlbl]" placeholder="Highly Agree"
                       required
                       value="{{ $question->maxlbl ?? '' }}"
                       aria-required="true" class="form-text d-inline"></label>
        </div>
        <div class="form-group multiple my-3 {{ (!$template && empty($question->choices)) ? 'd-none' : '' }}">
            <div class="col-xs-12 col-sm-12 col-md-12 choice-container">
                @if (!$template && !empty($question->choices))
                    @foreach($question->choices as $j => $choice)
                        <div class="row choice">
                            <div class="col-xs-5 col-sm-5 col-md-5 text-center overflow-hidden">
                                <i class="material-icons text-muted">radio_button_unchecked</i>
                                <label for="question[{{ $count ?? '' }}][choices][{{ $j }}]" class="form-control-sm">
                                    {{ $choice }}</label>
                            </div>
                            <div class="col-xs-5 col-md-5 text-left">
                                <input class="form-control-sm" name="question[{{ $count ?? '' }}][choices][{{ $j }}]"
                                       type="text"
                                       placeholder="choice"
                                       aria-placeholder="choice"
                                       value="{{ $choice }}"
                                       maxlength="31"
                                       required aria-required="true"
                                       oninput="(function() {$(this).closest('div').prev().find('label')[0].lastChild.textContent = this.value;
                                   }.bind(this, event)());">
                            </div>
                            <div class="col-xs-12 col-sm-1 col-md-1">
                                <i class="btn btn-sm btn-sm btn-danger delete-choice material-icons">close</i>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="row choice">
                        <div class="col-xs-5 col-sm-5 col-md-5 text-center overflow-hidden">
                            <i class="material-icons text-muted">radio_button_unchecked</i>
                            <label for="question[{{ $count ?? '' }}][choices][]" class="form-control-sm"
                                   style="max-width: 90%; max-height: 20px; overflow: hidden;">
                                Yes</label>
                        </div>
                        <div class="col-xs-5 col-md-5 text-left">
                            <input class="form-control-sm" name="question[{{ $count ?? '' }}][choices][]"
                                   type="text"
                                   placeholder="choice"
                                   aria-placeholder="choice"
                                   value="Yes"
                                   maxlength="31"
                                   required aria-required="true"
                                   oninput="(function(e) {
                                        $(this).closest('div').prev().find('label')[0].lastChild.textContent = this.value;
                                   }.bind(this, event)());">
                        </div>
                        <div class="col-xs-12 col-sm-1 col-md-1">
                            <i class="btn btn-sm btn-sm btn-danger delete-choice material-icons">close</i>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-sm-6 offset-sm-3 offset-md-3 col-md-6">
                <div class="btn btn-sm btn-block btn-secondary add-choice">
                    <i class="material-icons">add_circle_outline</i>Add Option
                </div>
            </div>
        </div>
    </div>
</div>
