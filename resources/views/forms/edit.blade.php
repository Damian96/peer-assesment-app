@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('form.edit', $form) }}
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <form class="row question-editor mt-3" action="{{ url('/forms/store') }}" method="POST">
                @method('POST')
                @csrf

                <input type="hidden" name="form_id" value="{{ $form->id }}" class="hidden">
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
                <div class="col-sm-12 col-md-12">
                    <hr>
                    <h4>Form Preview</h4></div>
                <div class="col-sm-12 col-md-12">
                    <div class="form-group">
                        <label for="form-title">Title</label>
                        <input type="text" name="title" required aria-required="true" maxlength="255"
                               value="{{ old('title', false) ? old('title') : $form->title }}"
                               placeholder="The form's main title" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="form-subtitle">Subtitle <span
                                class="text-muted">(leave blank for none)</span></label>
                        <input type="text" name="subtitle" maxlength="255" placeholder="The form's main subtitle"
                               value="{{ old('subtitle', false) ? old('subtitle') : $form->subtitle }}"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="form-footnote">Footnote <span
                                class="text-muted">(leave blank for none)</span></label>
                        <input type="text" name="footnote" maxlength="255" placeholder="The form's footnote"
                               value="{{ old('footnote', false) ? old('footnote') : $form->footnote }}"
                               class="form-control">
                    </div>
                </div>
                @foreach($form->questions()->getEager() as $q => $question)
                <!-- Card -->
                    <div class="card col-sm-12 col-md-12 p-0 my-2">
                        <!-- Card Title -->
                        <div class="card-title">
                            <div class="input-group">
                                <button class="btn btn-primary btn-block" type="button"
                                        data-title="{{$question->title}}">{{ $question->title }}</button>
                                <div class="input-group-append float-right">
                                    <i class="btn btn-sm btn-outline-danger material-icons delete-question" onclick="(function() {
                            if ($('.card').length == 2) {
                                $('#session_id').combobox('enable');
                                $('button.question-type').removeAttr('disabled');
                                $('button[type=submit]').attr('disabled', true);
                            }
                            $(this).closest('.card').slideUp('fast', function() {
                              this.remove();
                            }); }.bind(this, event))();">
                                        delete
                                    </i>
                                    <i class="btn btn-sm btn-outline-light material-icons close-question"
                                       data-toggle="collapse"
                                       data-target="#{{ 'question-' . $question->id }}"
                                       aria-expanded="false"
                                       aria-controls="{{ '$question-'.$question->id }}">keyboard_arrow_down</i>
                                    <i class="btn btn-sm btn-outline-light material-icons moveup-question"
                                       onclick="(function(e) {
                           let el = $(this).closest('.card');
                           let prev = el.prev();
                           if (!prev.hasClass('d-none')) {
                               prev.before(el.remove());
                               $(this).closest('.card').effect('highlight', {}, 1000);
                               return true;
                           }
                           $(this).closest('.card').effect('highlight', {}, 1000);
                           return false;
                        }.bind(this, event))();">arrow_upward</i>
                                    <i class="btn btn-sm btn-outline-light material-icons movedown-question"
                                       onclick="(function(e) {
                           let el = $(this).closest('.card');
                           let next = el.next();
                           if (next.hasClass('card')) {
                               next.after(el.remove());
                               $(this).closest('.card').effect('highlight', {}, 1000);
                               return true;
                           }
                           $(this).closest('.card').effect('highlight', {}, 1000);
                           return false;
                        }.bind(this, event))();">arrow_downward</i>
                                </div>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div id="{{ 'question-'.$question->id }}" class="card-body collapse pt-0">
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
                                        <input type="text" name="question[{{ $q }}][maxlbl]" placeholder="Highly Agree"
                                               required
                                               aria-required="true" class="form-text d-inline"
                                               value="{{ $question->maxlbl }}"></label>
                                </div>
                            @elseif ($question->type == 'multiple-choice')
                                <div class="form-group multiple my-3">
                                    @foreach($question->choices as $j => $choice)
                                        <div class="row choice">
                                            <div class="col-md-6 text-center overflow-hidden">
                                                <i class="material-icons text-muted">radio_button_unchecked</i>
                                                <label for="question[{{ $q }}][choices][]" class="form-control-sm"
                                                       style="max-width: 90%; max-height: 20px; overflow: hidden;">
                                                    {{ $choice }}</label>
                                            </div>
                                            <div class="col-md-6 text-left">
                                                <input class="form-control-sm" name="question[{{ $q }}][choices][]"
                                                       type="text"
                                                       placeholder="choice"
                                                       aria-placeholder="choice"
                                                       value="{{ $choice }}"
                                                       maxlength="31"
                                                       required aria-required="true"
                                                       oninput="(function(e) {
                                        $(this).closest('.col-md-6').prev().find('label')[0].lastChild.textContent = this.value;
                                   }.bind(this, event)());">
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="row choice">
                                        <div class="col-sm-6 col-md-6 text-center overflow-hidden">
                                            <i class="material-icons text-muted">radio_button_unchecked</i>
                                            <label for="question[{{ $q }}][choices][]"
                                                   class="form-control-sm"
                                                   style="max-width: 90%; max-height: 20px; overflow: hidden;">{{ $choice }}</label>
                                        </div>
                                        <div class="col-sm-6 col-md-6 text-left">
                                            <input class="form-control-sm" name="question[{{ $q }}][choices][]"
                                                   type="text"
                                                   placeholder="choice"
                                                   aria-placeholder="choice"
                                                   value="{{ $choice }}"
                                                   maxlength="31"
                                                   required aria-required="true"
                                                   oninput="(function(e) {
                                        $(this).closest('.col-md-6').prev().find('label')[0].lastChild.textContent = this.value;
                                   }.bind(this, event)());">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 offset-sm-3 offset-md-3 col-md-6">
                                        <div class="btn btn-block btn-secondary add-multiple-option" onclick="(function(e) {
                            let clone = $(this).closest('.form-group').find('.row').first().clone();
                            clone.find('label')[0].lastChild.textContent = 'OPTION';
                            clone.find('input').val('');
                            $(this).closest('.form-group').find('.row').last().after(clone);
                        }.bind(this, event))();">
                                            <i class="material-icons">add_circle_outline</i>Add Option
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
                <div class="col-sm-12 col-md-12">
                    <button type="submit" class="btn btn-block btn-primary" disabled>Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('end_footer')
@endsection
