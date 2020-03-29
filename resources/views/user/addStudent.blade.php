@extends('layouts.app')

@section('breadcrumbs'){{ Breadcrumbs::render('course.add-student', Auth::user(), $course) }}@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form method="POST" action="{{ url( 'courses/' . $course->id . '/store-student') }}" id="import-students">
                @method('POST')
                @csrf

                <input type="hidden" class="hidden" name="form" value="import-students" width="0" height="0">

                <input type="hidden" class="hidden" name="course_id" value="{{ $course->id }}" width="0" height="0">

                <input type="hidden" class="hidden" name="csv-data" id="csv-data" value="none" width="0" height="0">

                <legend>Import from <i>.csv</i></legend>

                <div class="form-group">
                    <label class="control-label" for="csv">CSV File
                        <input type="file" name="csv" id="csv"
                               class="form-control{{ $errors->has('csv') ? ' is-invalid' : '' }}"
                               accept="text/csv">
                    </label>

                    <div class="form-text font-italic text-muted">Required fields are: First Name, Last Name / Surname,
                        Email / E-mail,
                        Registration Number (case insensitive)<br>
                    </div>

                    <span class="invalid-feedback d-inline">
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

                    <span class="invalid-feedback d-inline">
                    @if ($errors->has('studentid'))<strong>{{ $errors->first('studentid') }}</strong>@endif
                    </span>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-block btn-primary" role="button"
                            {{ $students->isEmpty() ? 'disabled' : null }}
                            title="Add Student to {{ $course->code }}"
                            aria-roledescription="Add Student to {{ $course->code }}" tabindex="0">Enroll Student
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
                        // @FIXME: remove me
                        // preview: 5,
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
                                case 'Registration Number':
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
