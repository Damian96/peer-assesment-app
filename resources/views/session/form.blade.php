{{-- globals: $method, $action, $errors --}}
<form role="form" method="{{ $method }}" action="{{ $action }}">
    @method($method)
    @csrf

    @if (isset($form) && !isset($forms))
        <input type="hidden" class="hidden" value="{{ $form->id }}" name="form" id="form">
    @endif
    @if (isset($course))
    <!-- Create Session for CourseX -->
        <input type="hidden" class="hidden" value="{{ $course->id }}" name="course" id="course">
    @elseif (!isset($session))
    <!-- Create Session -->
        @php $course = isset($course) ? $course : ((isset($session) && $session->course()->exists()) ? $session->course()->first() : null); @endphp
        <div class="form-group">
            <label class="form-text" for="course">Course</label>
            @if (!empty($courses->items))
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
            @else
                <div class="form-text text-warning">There are no Courses for this academic year!<br>Maybe <a
                        href="{{ url('courses/create')  }}">Create one?</a></div>
            @endif
            <span
                class="invalid-feedback d-inline-block font-weight-bold">@if ($errors->has('course')){{ $errors->first('course') }}@endif</span>
        </div>
    @endif

    @if (isset($session) && $session->form()->exists())
        <input type="hidden" class="hidden" value="{{ $session->form()->first()->getModel()->id }}" name="form">
    @endif
    @if (!empty($forms) && !isset($session))
        <div class="form-group">
            <label id="form-lbl" class="form-text" for="form">Form</label>

            <select name="form" id="form" class="form-control{{ $errors->has('form') ? ' is-invalid' : '' }}"
                    data-rule-required="true"
                    tabindex="0"
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
    @if (!isset($session) || (isset($session) && !$session->isOpen()))
        <div class="form-group">
            <label for="open_date">
                <span class="mr-2">Opens At</span><br>
                <span class="text-muted">When will the Session open? (Time is always at midnight)</span>
                <input type="text" id="open_date" name="open_date" readonly aria-readonly="true"
                       data-rule-required="true"
                       tabindex="0"
                       data-msg-required="{{ $messages['open_date.required'] }}"
                       data-rule-pattern="[0-9]{2}-[0-9]{2}-[0-9]{4}"
                       data-msg-pattern="{{ $messages['open_date.date_format'] }}"
                       class="form-control{{ $errors->has('open_date') ? ' is-invalid' : '' }}">
            </label>
            <span class="invalid-feedback d-block">
        @if ($errors->has('open_date'))<strong>{{ $errors->first('open_date') }}</strong>@endif
        </span>
        </div>
    @else
        <div class="form-text font-weight-bold">
            <p>
                <span class="text-warning">Warning:</span> This Session is already opened!
            </p>
        </div>
    @endif
    <div class="form-group">
        <label for="deadline">
            <span class="mr-2">Deadline</span><br>
            <span class="text-muted">When will the Session close? (Time is always at midnight)</span>
            <input type="text" id="deadline" name="deadline" readonly aria-readonly="true"
                   tabindex="0"
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
               tabindex="0"
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
                  tabindex="0"
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
    @if (!isset($session) || (isset($session) && !$session->isOpen()))
        <div class="form-group">
            <label class="form-text" for="groups">Maximum Groups</label>
            <input class="form-control{{ $errors->has('groups') ? ' is-invalid' : '' }}"
                   tabindex="0"
                   name="groups"
                   type="number"
                   id="groups"
                   min="1"
                   aria-valuemin="1"
                   max="25"
                   aria-valuemax="25"
                   value="{{ old('groups', isset($session) ? $session->groups : '') }}"
                   data-rule-required="true"
                   data-msg-required="{{ $messages['groups.required'] ?? '' }}"
                   data-rule-min="1"
                   data-msg-min="{{ $messages['groups.min'] ?? '' }}"
                   data-rule-max="25"
                   data-msg-max="{{ $messages['groups.max'] ?? '' }}"
                   aria-invalid="{{ $errors->has('groups') ? 'true' : 'false' }}">
            <span class="invalid-feedback d-block">
        @if ($errors->has('groups'))<strong>{{ $errors->first('groups') }}</strong>@endif
        </span>
        </div>
        <div class="form-group d-flex justify-content-around flex-row flex-nowrap align-items-stretch mb-5">
            <div class="flex-column flex-grow-1">
                <label class="form-text" for="min_group_size">Minimum Group size</label>
                <input class="form-control{{ $errors->has('min_group_size') ? ' is-invalid' : '' }}"
                       tabindex="0"
                       name="min_group_size"
                       type="number"
                       id="min_group_size"
                       min="2"
                       aria-valuemin="2"
                       max="5"
                       aria-valuemax="5"
                       value="{{ old('groups', isset($session) ? $session->min_group_size : '') }}"
                       data-rule-required="true"
                       data-msg-required="{{ $messages['min_group_size.required'] ?? '' }}"
                       data-rule-min="2"
                       data-msg-min="{{ $messages['min_group_size.min'] ?? '' }}"
                       data-rule-max="5"
                       data-msg-max="{{ $messages['min_group_size.max'] ?? '' }}"
                       aria-invalid="{{ $errors->has('min_group_size') ? 'true' : 'false' }}">
                <span class="invalid-feedback d-block">
            @if ($errors->has('min_group_size'))<strong>{{ $errors->first('min_group_size') }}</strong>@endif
            </span>
            </div>
            <div class="flex-column flex-grow-1">
                <label class="form-text" for="max_group_size">Maximum Group size</label>
                <input class="form-control{{ $errors->has('max_group_size') ? ' is-invalid' : '' }}"
                       tabindex="0"
                       name="max_group_size"
                       type="number"
                       id="max_group_size"
                       min="2"
                       aria-valuemin="2"
                       max="6"
                       aria-valuemax="6"
                       value="{{ old('groups', isset($session) ? $session->max_group_size : '') }}"
                       data-rule-required="true"
                       data-msg-required="{{ $messages['max_group_size.required'] ?? '' }}"
                       data-rule-min="2"
                       data-msg-min="{{ $messages['max_group_size.min'] ?? '' }}"
                       data-rule-max="6"
                       data-msg-max="{{ $messages['max_group_size.max'] ?? '' }}"
                       aria-invalid="{{ $errors->has('max_group_size') ? 'true' : 'false' }}">
                <span class="invalid-feedback d-block">
            @if ($errors->has('max_group_size'))<strong>{{ $errors->first('max_group_size') }}</strong>@endif
            </span>
            </div>
        </div>
    @elseif (isset($session))
        <div class="form-text font-weight-bold">
            <p>
                <span class="text-warning">Warning:</span> Already opened Sessions can not change their Group limits!
            </p>
        </div>
    @endif
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
                maxDate: '{!! \App\Session::MAX_SELECT_DATE !!}',
                onSelect: function (dateText) {
                    window.localStorage.setItem(`{{ $action }}-deadline`, dateText);
                }
            });
            $('#deadline').datepicker('setDate', '{!! isset($session) ? sprintf("%s",date('d-m-Y', $session->deadline_int)) : "window.localStorage.getItem('{$action}-deadline')" !!}');
            $("#open_date").datepicker({
                dateFormat: 'dd-mm-yy',
                minDate: 1,
                maxDate: '{!! \App\Session::MAX_SELECT_DATE !!}',
                onSelect: function (dateText) {
                    window.localStorage.setItem(`{{ $action }}-open_date`, dateText);
                }
            });
            $('#open_date').datepicker('setDate', '{!! isset($session) ? sprintf("%s",date('d-m-Y', $session->open_date_int)) : "window.localStorage.getItem('{$action}-open_date')" !!}');
            // Course custom ComboBox jQueryUI
            // $('#course').combobox();
            @if ($errors->has('studentid'))
            $('#course').next()
                .addClass('is-invalid');
            @endif
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
                    groups: {
                        required: true,
                        digits: true,
                        min: 2,
                        max: 25,
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
