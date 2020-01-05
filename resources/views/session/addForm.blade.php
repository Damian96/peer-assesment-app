@extends('layouts.app')

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
    <form class="row question-editor mt-3" action="{{ url('/sessions/form/store') }}" method="POST">
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
        <div class="col-sm-12 col-md-12">
            <hr>
            <h4>Form Preview</h4></div>
        <div class="col-sm-12 col-md-12">
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
        <!-- Card -->
        <div class="card col-sm-12 col-md-12 p-0 my-2 d-none">
            <!-- Card Title -->
            <div class="card-title">
                <div class="input-group">
                    <button class="btn btn-primary btn-block" type="button"
                            data-title="">
                        QUESTION TYPE TITLE
                    </button>
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
                        <i class="btn btn-sm btn-outline-light material-icons close-question" data-toggle="collapse"
                           data-target=""
                           aria-expanded="false" aria-controls="">
                            keyboard_arrow_down
                        </i>
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
                        }.bind(this, event))();">
                            arrow_upward
                        </i>
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
                        }.bind(this, event))();">
                            arrow_downward
                        </i>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body collapse pt-0">
                <div class="form-group question-title">
                    <label class="form-control-sm">Title</label>
                    <input type="text" name="question[#][title]" class="form-control" oninput="(function(e) {
                      let button = $(this).closest('.card').find('.btn-block');
                      let title = button.data('title') + ' - ' + this.value;
                      button.text(title);
                    }.bind(this, event))();" required aria-required="true">
                </div>
                <div class="form-group question-title">
                    <label class="form-control-sm">Subtitle <span
                            class="text-muted">(leave blank for none)</span></label>
                    <input type="text" name="question[#][subtitle]" class="form-control">
                </div>
                <div class="form-group scale d-none">
                    <label for="linear-scale_max[]" class="form-control-sm">Maximum</label>
                    <input type="number"
                           name="question[#][max]"
                           value="5"
                           min="2" max="10"
                           required
                           aria-required="true"
                           class="form-control-sm"
                           onchange="(function(e) { $(this).closest('.form-group').next().find('.max-num').text(this.value)}.bind(this, event))();">
                </div>
                <div class="form-group scale my-3 d-none">
                    <label for="linear-scale_minlbl" class="form-control-sm">1:
                        <input type="text" name="question[#][minlbl]" placeholder="Highly Disagree" required
                               aria-readonly="true" class="form-text d-inline"></label>
                    <label for="linear-scale_maxlbl" class="form-control-sm"><span class="max-num d-inline">5</span>
                        <input type="text" name="question[#][maxlbl]" placeholder="Highly Agree" required
                               aria-required="true" class="form-text d-inline"></label>
                </div>
                <div class="form-group multiple my-3 d-none">
                    <span class="row">
                        <span class="col-md-6 text-center overflow-hidden">
                            <i class="material-icons text-muted">radio_button_unchecked</i>
                            <label for="question[#][choices][]" class="form-control-sm"
                                   style="max-width: 90%; max-height: 20px; overflow: hidden;">
                                Yes</label>
                        </span>
                        <span class="col-md-6 text-left">
                            <input class="form-control-sm" name="question[#][choices][]" type="text"
                                   placeholder="choice"
                                   value="Yes"
                                   maxlength="31"
                                   aria-placeholder="choice" required aria-required="true"
                                   oninput="(function(e) {
                                        $(this).closest('.col-md-6').prev().find('label')[0].lastChild.textContent = this.value;
                                   }.bind(this, event)());">
                        </span>
                    </span>
                    <span class="row">
                        <span class="col-sm-6 col-md-6 text-center overflow-hidden">
                            <i class="material-icons text-muted">radio_button_unchecked</i>
                            <label for="multiple-choice_choice_#[]"
                                   class="form-control-sm" style="max-width: 90%; max-height: 20px; overflow: hidden;">No</label>
                        </span>
                        <span class="col-sm-6 col-md-6 text-left">
                            <input class="form-control-sm" name="question[#][choices][]" type="text"
                                   placeholder="choice"
                                   value="No"
                                   maxlength="31"
                                   aria-placeholder="choice" required aria-required="true"
                                   oninput="(function(e) {
                                        $(this).closest('.col-md-6').prev().find('label')[0].lastChild.textContent = this.value;
                                   }.bind(this, event)());">
                        </span>
                    </span>
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
                    {{--                    <span class="row">--}}
                    {{--                        <span class="col-sm-6 col-md-6 text-center">--}}
                    {{--                            <label for="multiple-choice_choice_#[]" class="form-control-sm disabled">--}}
                    {{--                                <i class="material-icons">radio_button_checked</i>Other</label>--}}
                    {{--                        </span>--}}
                    {{--                        <span class="col-sm-6 col-md-6 text-left">--}}
                    {{--                            <input class="form-control-sm multiple-choice_choice_#[]" type="text" placeholder="choice"--}}
                    {{--                               aria-placeholder="choice" required aria-required="true">--}}
                    {{--                        </span>--}}
                    {{--                    </span>--}}
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-12">
            <button type="submit" class="btn btn-block btn-primary" disabled>Submit</button>
        </div>
    </form>
@endsection

@section('end_footer')
    <script type="text/javascript">
        $(function () {
            // course autocomplete
            $('#session_id').combobox();
            @if ($errors->has('session_id'))
            $('#session_id')
                .addClass('is-invalid');
            @endif
            $('#session_id').next().on('autocompleteselect', function (e, ui) {
                $('input[name=session_id]').val(ui.item.option.getAttribute('value'));
                $('button.question-type').removeAttr('disabled');
                $('form').find('.col-md-12').first().effect('highlight', {}, 2000);
                return true;
            });
            $('button.question-type').attr('disabled', true); // reset value for caching
            $('button.question-type').on('click', function (e) {
                $('#session_id').combobox('disable');

                // Initialise variables
                let count = $('.card').length;
                let template = $('.card.d-none');
                let clone = template.clone();
                let title = `${this.lastChild.textContent} #${count}`;
                let id = title.replace(/[\/#\s\(\)]/g, '-');

                // Add card title
                clone.find('.btn-block')
                    .first()
                    .data('title', title)
                    .text(title);
                // Add bs-collapse data
                clone.find('.close-question')
                    .attr('data-target', '#' + id)
                    .attr('aria-controls', id);
                clone.find('.collapse').attr('id', id);
                // Show
                clone.removeClass('d-none');
                // Replace session_id
                clone.find('input[type=hidden]').val(this.id);
                // Replace #s
                clone.find('input[name],label[name]').each(function () {
                    $(this).attr('name', this.getAttribute('name').replace(/#/i, count));
                });
                switch (this.id) {
                    case 'linear-scale':
                        clone.find('.scale.d-none').removeClass('d-none').addClass('d-block');
                        clone.find('.multiple').remove();
                        break;
                    case 'multiple-choice':
                        clone.find('.multiple.d-none').removeClass('d-none').addClass('d-block');
                        clone.find('.scale').remove();
                        break;
                    case 'eval':
                    case 'paragraph':
                        clone.find('.d-none').remove();
                        break;
                    default:
                        return false;
                }
                clone.find('.card-body').on('shown.bs.collapse', function () {
                    $(this).closest('.card').find('.close-question').text('keyboard_arrow_up');
                    return true;
                });
                clone.find('.card-body').on('hidden.bs.collapse', function () {
                    $(this).closest('.card').find('.close-question').text('keyboard_arrow_down');
                    return true;
                });
                $('form').find('button[type=submit]')[0].removeAttribute('disabled');
                // Append to end of cards
                $('.card').last().after(clone);
                // Blink Effect
                clone.effect('highlight', {}, 1000);
                // clone.find('.close-question')[0].click();
                // console.debug(clone);
                return true;
            });
            $('button[type=submit]').on('click', function () {
                // let template = $('.card.d-none');
                $('.card.d-none').remove();
                return true;
            });
        });
    </script>
@endsection
