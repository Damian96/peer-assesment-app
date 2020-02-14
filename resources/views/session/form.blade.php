{{-- method, action, $errors --}}
<form role="form" method="{{ $method }}" action="{{ $action }}">
    @method($method)
    @csrf

    @if (isset($course) && !isset($courses))
        <input type="hidden" class="hidden" value="{{ $course->id }}" name="course" id="course">
    @else
        @php $course = (isset($session) && $session->course()->exists()) ? $session->course()->first() : null; @endphp
        <div class="form-group">
            <label class="form-text" for="course">Course</label>
            @if (!empty($courses))
                <select name="course" id="course" class="form-control{{ $errors->has('course') ? ' is-invalid' : '' }}"
                        readonly="true"
                        data-rule-required="true"
                        data-msg-required="{{ $messages['course.required'] }}"
                        data-rule-min="1"
                        data-msg-min="{{ $messages['course.numeric'] }}">
                    @foreach($courses as $c)
                        <option
                            value="{{ $c->id }}"{{ (isset($course) && $course->id == $c->id) ? ' selected' : null  }}>{{ sprintf("%s - %s",$c->code,$c->ac_year_pair) }}</option>
                    @endforeach
                </select>
                <span class="invalid-feedback d-inline-block">
                <strong>@if ($errors->has('course')){{ $errors->first('course') }}@endif</strong>
                </span>
            @else
                <div class="form-text text-warning">There are no Courses for this academic year!</div>
            @endif
        </div>
    @endif

    @if (isset($session) && $session->form()->exists())
        <input type="hidden" class="hidden" value="{{ $session->form()->first()->getModel()->id }}" name="form">
    @endif
    @if (!empty($forms))
        <div class="form-group">
            <label id="form-lbl" class="form-text" for="form">Form</label>

            <select name="form" id="form" class="form-control{{ $errors->has('form') ? ' is-invalid' : '' }}"
                    data-rule-required="true"
                    data-msg-required="{{ $messages['form.required'] ?? '' }}"
                    aria-labelledby="form-lbl">
                @foreach($forms as $f)
                    <option
                        value="{{ $f->id }}" {{ !$f->session_id ? "data-template='true'" : null }}>{{ sprintf("%s",$f->title) }}</option>
                @endforeach
            </select>
            <span class="invalid-feedback d-inline-block">
                    <strong>{{ $errors->first('form') ?? '' }}</strong></span>
        </div>
    @endif
    <div class="form-group">
        <label for="deadline">
            <span class="mr-2">Opens At</span><br>
            <span class="text-muted">Date always at midnight. When will the Session open?</span>
            <input type="text" id="open_date" name="open_date" readonly aria-readonly="true"
                   data-rule-required="true"
                   data-msg-required="{{ $messages['open_date.required'] }}"
                   data-rule-pattern="[0-9]{2}-[0-9]{2}-[0-9]{4}"
                   data-msg-pattern="{{ $messages['open_date.date_format'] }}"
                   class="form-control{{ $errors->has('open_date') ? ' is-invalid' : '' }}">
        </label>
        <span class="invalid-feedback d-block">
        @if ($errors->has('open_date'))<strong>{{ $errors->first('open_date') }}</strong>@endif
        </span>
    </div>
    <div class="form-group">
        <label for="deadline">
            <span class="mr-2">Deadline</span><br>
            <span class="text-muted">Date always at midnight. When will the Session close?</span>
            <input type="text" id="deadline" name="deadline" readonly aria-readonly="true"
                   data-rule-required="true"
                   data-msg-required="{{ $messages['deadline.required'] }}"
                   data-rule-pattern="[0-9]{2}-[0-9]{2}-[0-9]{4}"
                   data-msg-pattern="{{ $messages['deadline.date_format'] }}"
                   class="form-control{{ $errors->has('deadline') ? ' is-invalid' : '' }}">
        </label>
        <span class="invalid-feedback d-block">
        @if ($errors->has('deadline'))<strong>{{ $errors->first('deadline') }}</strong>@endif
        </span>
    </div>
    <div class="form-group">
        <label class="form-text" for="title">Title</label>
        <input type="text" name="title" id="title"
               value="{{ old('title', isset($session) ? $session->title: null ) }}"
               class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
               placeholder="Session's title"
               aria-placeholder="Session's title"
               data-rule-required="true"
               data-msg-required="{{ $messages['title.required'] }}"
               data-rule-minlength="3"
               data-msg-minlength="{{ $messages['title.min'] }}"
               data-rule-maxlength="50"
               data-msg-maxlength="{{ $messages['title.max'] }}">
        <span class="invalid-feedback d-block">
        @if ($errors->has('title'))<strong>{{ $errors->first('title') }}</strong>@endif
        </span>
    </div>
    <div class="form-group">
        <label class="form-text" for="instructions">Instructions</label>
        <textarea class="form-control{{ $errors->has('instructions') ? ' is-invalid' : '' }}"
                  name="instructions"
                  id="instructions" maxlength="500"
                  data-rule-required="true"
                  data-msg-required="{{ $messages['instructions.required'] }}"
                  data-rule-maxlength="1000"
                  data-msg-maxlength="{{ $messages['instructions.max'] }}"
                  placeholder="a short description of the session"
                  aria-placeholder="a short description of the session"
                  aria-invalid="{{ $errors->has('instructions') ? 'true' : 'false' }}"
                  style="min-height: 100px; max-height: 150px;">{{ old('instructions', isset($session) ? $session->instructions : '') }}</textarea>

        <span class="invalid-feedback d-block">
        @if ($errors->has('instructions'))<strong>{{ $errors->first('instructions') }}</strong>@endif
        </span>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-block btn-primary" role="button" title="{{ $button['title'] }}"
                aria-roledescription="{{ $button['title'] }}" tabindex="0">{{ $button['label'] }}</button>
    </div>
</form>

@section('end_footer')
    <script type="text/javascript" defer>
        $(document).ready(function () {
            // Deadline jQuery UI Datepicker
            $("#deadline").datepicker({
                dateFormat: 'dd-mm-yy',
                minDate: 1,
                maxDate: 6 * 31,
                defaultDate: '{{ old('deadline', isset($session) ? date('d-m-Y', $session->deadline_int) : null) }}',
            });
            $('#deadline').datepicker('setDate', '{{ old('deadline', isset($session) ? date('d-m-Y', $session->deadline_int) : null) }}');
            $("#open_date").datepicker({
                dateFormat: 'dd-mm-yy',
                minDate: 1,
                maxDate: 6 * 31,
                defaultDate: '{{ old('deadline', isset($session) ? date('d-m-Y', $session->open_date_int) : null) }}',
            });
            $('#open_date').datepicker('setDate', '{{ old('deadline', isset($session) ? date('d-m-Y', $session->open_date_int) : null) }}');
            // Course custom ComboBox jQueryUI
            $('#course').combobox();
            @if ($errors->has('studentid'))
            $('#course').next()
                .addClass('is-invalid');
            @endif
            // Forms custom ComboBox jQueryUI
            // $('#form').combobox({
            //     select: function (event, option) {
            //         option = option.item;
            //         console.debug(event, option);
            //         // if (option.hasAttribute('data-template')) {
            //         //     $('#form').closest('.form-group')
            //         //         .attr('title', 'The Form you selected is a template, so it will be duplicated!')
            //         //         .tooltip();
            //         // } else {
            //         //     $('#form').closest('.form-group')
            //         //         .attr('title', '');
            //         // }
            //     }
            // });
            @if ($errors->has('form'))
            $('#form').next()
                .addClass('is-invalid');
            @endif
        });
        $(document).on('focusout change', 'input, select, textarea', function () {
            return $(this).valid();
        });
        $(document).on('submit', 'form', function (event) {
            let form = $(event.target);
            form.validate({
                rules: {
                    course: {
                        required: true,
                        digits: true,
                        min: 1
                    },
                    form: {
                        required: true,
                        digits: true,
                        min: 1
                    },
                    title: {
                        required: true,
                        minlength: 3,
                        maxlength: 50
                    },
                    instructions: {
                        required: true,
                        maxlength: 1000
                    },
                    deadline: {
                        required: true,
                        pattern: '[0-9]{2}-[0-9]{2}-[0-9]{4}'
                    },
                },
                messages: {
                    course: {
                        required: "{!! $messages['course.required'] !!}",
                        min: "{!! $messages['course.numeric'] !!}",
                    },
                    form: {
                        required: "{!! $messages['form.required'] !!}",
                        min: "{!! $messages['form.numeric'] !!}",
                    },
                    title: {
                        required: "{!! $messages['title.required'] !!}",
                        minlength: "{!! $messages['title.min'] !!}",
                        maxlength: "{!! $messages['title.max'] !!}"
                    },
                    instructions: {
                        required: "{!! $messages['instructions.required'] !!}",
                        maxlength: "{!! $messages['instructions.max'] !!}"
                    },
                    deadline: {
                        required: "{!! $messages['deadline.required'] !!}",
                        pattern: "{!! $messages['deadline.date_format'] !!}",
                    }
                }
            });

            // console.log(form, form.valid());
            // event.preventDefault();
            // return false;
            return form.valid();
        });
    </script>
@endsection
