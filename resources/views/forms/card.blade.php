<!-- Template Card -->
<div id="template-card" class="card template d-none col-xs-12 col-sm-12 col-md-12 p-0 my-2">
    <input name="question[#][type]" type="hidden" class="d-none">
    <!-- Card Title -->
    <div class="card-title">
        <div class="input-group">
            <button class="btn btn-primary btn-block" type="button"
                    data-title=""></button>
            <div class="input-group-append float-right">
                <i class="btn btn-sm btn-outline-danger material-icons delete-question">delete</i>
                <i class="btn btn-sm btn-outline-light material-icons close-question"
                   data-toggle="collapse"
                   data-target=""
                   aria-expanded="true"
                   aria-controls="">keyboard_arrow_down</i>
                <i class="btn btn-sm btn-outline-light material-icons moveup-question">arrow_upward</i>
                <i class="btn btn-sm btn-outline-light material-icons movedown-question">arrow_downward</i>
            </div>
        </div>
    </div>
    <!-- Card Body -->
    <div class="card-body collapse show pt-0">
        <div class="form-group question-title">
            <label class="form-control-sm">Title</label>
            <input type="text" name="question[#][title]" class="form-control" oninput="(function(e) {
                      let button = $(this).closest('.card').find('.btn-block[data-title]');
                      // let title = button.data('title') + ' - ' + this.value;
                      let title = this.value;
                      button.text(title);
                    }.bind(this, event))();" required aria-required="true"
                   value="">
        </div>
        <div class="form-group question-title">
            <label class="form-control-sm">Subtitle <span
                    class="text-muted">(leave blank for none)</span></label>
            <input type="text" name="question[#][subtitle]" class="form-control"
                   value="">
        </div>
        <div class="form-group scale d-none">
            <label for="question[#][max]" class="form-control-sm">Maximum</label>
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
            <label for="question[#][minlbl]" class="form-control-sm">1:
                <input type="text" name="question[#][minlbl]"
                       placeholder="Highly Disagree"
                       required
                       aria-readonly="true" class="form-text d-inline"
                       value="Highly Disagree"></label>
            <label for="question[#][maxlbl]" class="form-control-sm"><span
                    class="max-num d-inline">5</span>
                <input type="text" name="question[#][maxlbl]" placeholder="Highly Agree"
                       required
                       aria-required="true" class="form-text d-inline"
                       value="Highly Agree"></label>
        </div>
        <div class="form-group multiple my-3 d-none">
            <div class="col-xs-12 col-sm-12 col-md-12 choice-container">
                <div class="row choice">
                    <div class="col-xs-5 col-sm-5 col-md-5 text-center overflow-hidden">
                        <i class="material-icons text-muted">radio_button_unchecked</i>
                        <label for="question[#][choices][]" class="form-control-sm"
                               style="max-width: 90%; max-height: 20px; overflow: hidden;">
                            Yes</label>
                    </div>
                    <div class="col-xs-5 col-md-5 text-left">
                        <input class="form-control-sm" name="question[#][choices][]"
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
                        <i class="btn btn-sm btn-danger delete-choice material-icons">close</i>
                    </div>
                </div>
                <div class="row choice">
                    <div class="col-xs-5 col-sm-5 col-md-5 text-center overflow-hidden">
                        <i class="material-icons text-muted">radio_button_unchecked</i>
                        <label for="question[#][choices][]" class="form-control-sm"
                               style="max-width: 90%; max-height: 20px; overflow: hidden;">
                            No</label>
                    </div>
                    <div class="col-xs-5 col-md-5 text-left">
                        <input class="form-control-sm" name="question[#][choices][]"
                               type="text"
                               placeholder="choice"
                               aria-placeholder="choice"
                               value="No"
                               maxlength="31"
                               required aria-required="true"
                               oninput="(function(e) {
                                        $(this).closest('div').prev().find('label')[0].lastChild.textContent = this.value;
                                   }.bind(this, event)());">
                    </div>
                    <div class="col-xs-12 col-sm-1 col-md-1">
                        <i class="btn btn-sm btn-danger delete-choice material-icons">close</i>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 offset-sm-3 offset-md-3 col-md-6">
                <div class="btn btn-block btn-secondary add-choice">
                    <i class="material-icons">add_circle_outline</i>Add Option
                </div>
            </div>
        </div>
    </div>
</div>

