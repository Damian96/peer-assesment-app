@extends('layouts.app')

@section('breadcrumbs'){{ Breadcrumbs::render('course.add-student', $user, $course) }}@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form method="POST" action="{{ url( 'courses/' . $course->id . '/store-student') }}" id="import-students">
                @method('POST')
                @csrf

                <input type="hidden" class="hidden" name="form" value="import-students" width="0" height="0">

                <input type="hidden" class="hidden" name="csv-data" id="csv-data" value="none" width="0" height="0">

                <legend>Import from <i>.csv</i></legend>

                <div class="form-group">
                    <label class="control-label" for="csv">CSV File
                        <input type="file" name="csv" id="csv"
                               class="form-control{{ $errors->has('csv') ? ' is-invalid' : '' }}"
                               accept="text/csv">
                    </label>

                    <span class="invalid-feedback {{ $errors->has('csv') ? 'd-inline' : '' }}">
                    @if ($errors->has('csv'))
                            <strong>{{ $errors->first('csv') }}</strong>
                        @endif
                    </span>
                </div>

                <div class="form-group">
                    @component('includes.checkbox', [
                        'name' => 'register',
                        'label' => 'Register new students' . "<span class='ml-2 text-muted'>(will send mail)</span>",
                        'checked' => old('register', false),
                        'value' => old('register', 'off'),
                    ])@endcomponent
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-block btn-primary" role="button"
                            title="Add Students to {{ $course->code }}"
                            aria-roledescription="Add Students to {{ $course->code }}" tabindex="0">Enroll Students
                    </button>
                </div>
            </form>
        </div>

        <div class="col-md-8 offset-md-2 form-separator">
            <hr>
            <h4>or</h4>
            <hr>
        </div>

        <div class="col-md-8 offset-md-2">
            <form method="POST" action="{{ url( 'courses/' . $course->id . '/store-student') }}" id="select-student">
                @method('POST')
                @csrf

                <input type="hidden" class="hidden" name="form" value="select-student" width="0" height="0">

                <legend>Select from existing</legend>

                <div class="form-group">
                    <label class="control-label" for="studentid">Student</label>

                    @if (!$students->isEmpty())
                        <select id="studentid" class="{{ $errors->has('studentid') ? 'is-invalid' : '' }}"
                                name="studentid" required>
                            @foreach($students as $student)
                                <option
                                    value="{{ $student->id }}">{{ $student->name }}</option>
                            @endforeach
                        </select>
                    @else
                        <select name="studentid" class="form-control" disabled readonly>
                            <option value="N/A">N/A</option>
                        </select>
                    @endif

                    <span class="invalid-feedback{{ $errors->has('studentid') ? ' d-block' : '' }}">
                    @if ($errors->has('studentid'))<strong>{{ $errors->first('studentid') }}</strong>@endif
                    </span>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-block btn-primary" role="button"
                            title="Add Student to {{ $course->code }}"
                            aria-roledescription="Add Student to {{ $course->code }}" tabindex="0">Enroll Student
                    </button>
                </div>
            </form>
        </div>

        <div class="col-md-8 offset-md-2 form-separator">
            <hr>
            <h4>or</h4>
            <hr>
        </div>

        <div class="col-md-8 offset-md-2">
            <form method="POST" action="{{ url( 'courses/' . $course->id . '/store-student') }}" id="add-student">
                @method('POST')
                @csrf
                <input type="hidden" class="hidden" name="form" value="add-student" width="0" height="0">

                <legend>Manually register into {{ config('app.name') }}</legend>

                <div class="form-group">
                    <label class="control-label" for="email">Email</label>

                    <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                           tabindex="0"
                           name="email" value="{{ old('email') }}" id="email" required
                           data-rule-required="true"
                           data-rule-email="true"
                           data-rule-pattern="^.+@citycollege\.sheffield\.eu$"
                           data-msg-required="{{ $messages['email.required_if'] }}"
                           data-msg-email="{{ $messages['email.email'] }}"
                           data-msg-pattern="{{ $messages['email.regex'] }}">

                    <span class="invalid-feedback{{ $errors->has('email') ? 'd-block' : '' }}">
                    @if ($errors->has('email'))<strong>{{ $errors->first('email') }}</strong>@endif
                    </span>
                </div>

                <div class="form-group">
                    <label class="control-label" for="fname">First Name</label>

                    <input class="form-control{{ $errors->has('fname') ? ' is-invalid' : '' }}" id="fname"
                           tabindex="0"
                           name="fname" type="text" value="{{ old('fname') }}"
                           required
                           data-rule-required="true"
                           data-rule-minlength="3"
                           data-rule-maxlength="25"
                           data-msg-required="{{ $messages['fname.required_if'] }}"
                           data-msg-minlength="{{ $messages['fname.min'] }}"
                           data-msg-maxlength="{{ $messages['fname.max'] }}">

                    <span class="invalid-feedback{{ $errors->has('fname') ? 'd-block' : '' }}">
                    @if ($errors->has('fname'))
                            <strong>{{ $errors->first('fname') }}</strong>
                        @endif
                    </span>
                </div>

                <div class="form-group" title="{{ $messages['lname.required_if'] }}">
                    <label class="control-label" for="fname">Last Name</label>

                    <input class="form-control{{ $errors->has('lname') ? ' is-invalid' : '' }}" id="lname"
                           tabindex="0"
                           name="lname" type="text" value="{{ old('lname') }}"
                           required
                           data-rule-required="true"
                           data-rule-minlength="3"
                           data-rule-maxlength="25"
                           data-rule-pattern="[a-z]{3,25}"
                           data-msg-pattern="{{ $messages['lname.regex'] }}"
                           data-msg-required="{{ $messages['lname.required_if'] }}"
                           data-msg-minlength="{{ $messages['lname.min'] }}"
                           data-msg-maxlength="{{ $messages['lname.max'] }}">

                    <span class="invalid-feedback">
                    @if ($errors->has('lname'))
                            <strong>{{ $errors->first('lname') }}</strong>
                        @endif
                    </span>
                </div>

                <div class="form-group" title="{{ $messages['reg_num.required_if'] }}">
                    <label class="control-label" for="fname">Registration number</label>

                    <input class="form-control{{ $errors->has('reg_num') ? ' is-invalid' : '' }}" id="reg_num"
                           tabindex="0"
                           name="reg_num" type="text" value="{{ old('reg_num') }}"
                           required
                           data-rule-required="true"
                           data-rule-pattern="^[A-Z]{2}[0-9]{5}$"
                           data-msg-required="{{ $messages['reg_num.required_if'] }}"
                           data-msg-pattern="{{ $messages['reg_num.regex'] }}">

                    <span class="invalid-feedback">
                    @if ($errors->has('reg_num'))<strong>{{ $errors->first('reg_num') }}</strong>@endif
                    </span>
                </div>

                <div class="form-group">
                    <label class="control-label" for="department">Department</label>

                    <select id="department" name="department"
                            class="form-control{{ $errors->has('department') ? ' is-invalid' : '' }}" tabindex="0"
                            data-rule-required="true"
                            data-rule-maxlength="5"
                            data-rule-different="admin"
                            data-msg-required="{{ $messages['department.required_if'] }}"
                            data-msg-maxlength="{{ $messages['department.max'] }}"
                            data-msg-different="{{ $messages['department.different'] }}">
                        <option
                            value="admin"{{ old('department', 'admin') == 'admin' ? ' selected' : '' }}>
                            &nbsp;---
                        </option>
                        <option
                            value="CS"{{ old('department') == 'CS' ? 'selected' : '' }}>
                            Computer Science
                        </option>
                        <option
                            value="ES"{{ old('department') == 'ES' ? 'selected' : '' }}>
                            English Studies
                        </option>
                        <option
                            value="BS"{{ old('department') == 'BS' ? 'selected' : '' }}>
                            Business Administration & Economics
                        </option>
                        <option
                            value="PSY"{{ old('department') == 'PSY' ? 'selected' : '' }}>
                            Psychology Studies
                        </option>
                        <option
                            value="MBA"{{ old('department') == 'MBA' ? 'selected' : '' }}>
                            Executive MBA
                        </option>
                    </select>

                    <span class="invalid-feedback">
                    @if ($errors->has('department'))
                            <strong>{{ $errors->first('department') }}</strong>
                        @endif
                    </span>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-block btn-primary" role="button"
                            title="Add Student {{ $course->code }}"
                            aria-roledescription="Add Student to {{ $course->code }}" tabindex="0">Register Student
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('end_footer')
    <script type="text/javascript" defer>
        $(document).on('focusout change', 'input, select', function () {
            return $(this).valid();
        });
        $(document).on('submit', 'form', function (event) {
            return validateForm($(event.target));
        });

        function validateForm(form) {
            switch (form[0].id) {
                case 'select-student':
                    form.validate({
                        rules: {
                            studentid: {
                                required: true,
                                digits: true,
                                different: '---',
                            },
                        },
                        messages: {
                            studentid: {
                                required: "{!! $messages['studentid.required_if'] !!}",
                                digits: "{!! $messages['studentid.numeric'] !!}",
                                different: "{!! $messages['studentid.different'] !!}"
                            }
                        }
                    });
                    break;
                case 'add-student':
                    form.validate({
                        rules: {
                            fname: {
                                required: true,
                                minlength: 3,
                                maxlength: 25,
                            },
                            lname: {
                                required: true,
                                minlength: 3,
                                maxlength: 25,
                            },
                            department: {
                                required: true,
                                maxlength: 5,
                                different: 'admin'
                            },
                            reg_num: {
                                required: true,
                                maxlength: 6,
                                pattern: /^[A-Z]{2}[0-9]{5}$/im
                            },
                            email: {
                                required: true,
                                email: true,
                                pattern: /^[a-z].+@citycollege\.sheffield\.eu/im
                            }
                        },
                        messages: {
                            email: {
                                required: '{{ $messages['email.required_if'] }}',
                                pattern: '{{ $messages['email.regex'] }}',
                                email: '{{ $messages['email.email'] }}',
                            },
                            fname: {
                                required: '{{ $messages['fname.required_if'] }}',
                                minlength: '{{ $messages['fname.min'] }}',
                                maxlength: '{{ $messages['fname.max'] }}',
                            },
                            lname: {
                                required: '{{ $messages['lname.required_if'] }}',
                                minlength: '{{ $messages['lname.min'] }}',
                                maxlength: '{{ $messages['lname.max'] }}',
                            },
                            reg_num: {
                                required: '{{ $messages['reg_num.required_if'] }}',
                                pattern: '{{ $messages['reg_num.regex'] }}',
                            },
                            department: {
                                required: '{{ $messages['department.required_if'] }}',
                                maxlength: '{{ $messages['department.max'] }}',
                                different: '{{ $messages['department.different'] }}',
                            }
                        }
                    });
                    break;
                default:
                    return true;
            }

            return form.valid();
        }

        $('#csv').on('change', function (event) {
            let el = $(this);
            let fileReader = new FileReader();
            let base64 = '';
            let results = {};
            fileReader.onload = function (e, base64, results) {
                base64 = fileReader.result;  // data in Base64 format
                // console.debug('onload:base64', base64);
                $.get(base64, function (e, text, results) {
                    // console.debug('get:text', text);
                    Papa.parse(text, {
                        delimiter: ',',
                        header: true,
                        preview: 2,
                        skipEmptyLines: true,
                        transformHeader: function (head) {
                            head = head.toLowerCase().trim();
                            switch (head) {
                                case 'first name':
                                case 'name':
                                    return 'fname';
                                case 'last name':
                                case 'surname':
                                    return 'lname';
                                case 'reg num':
                                case 'reg_num':
                                    return 'reg_num';
                                case 'email':
                                case 'e-mail':
                                    return 'email';
                                default:
                                    return head.replace(/\s/g, '_');
                            }
                        },
                        transform: function (value) {
                            return value.trim();
                        },
                        complete: function (e, r, results) {
                            // console.debug('done:r', r);
                            results = r;
                            console.debug('done:results', results);
                            if (results.errors.length > 0) {
                                $('#csv-data').val('');
                                console.error(results.errors[0]);
                            } else {
                                $('#csv-data').val(JSON.stringify(results.data));
                            }
                        }.bind(this, results)
                    });
                }.bind(this, results));
            }.bind(this, base64, results);
            fileReader.readAsDataURL(el.prop('files')[0]);
        });

        $(function () {
            $('#studentid').combobox();
            @if ($errors->has('studentid'))
            $('.custom-combobox')
                .addClass('is-invalid');
            @endif
        });
    </script>
@endsection
