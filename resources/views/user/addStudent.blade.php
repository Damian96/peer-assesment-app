@extends('layouts.app')

@section('breadcrumbs'){{ Breadcrumbs::render('course.add-student', $user, $course) }}@endsection

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form method="POST" action="{{ url( 'courses/' . $course->id . '/store-student') }}" id="add-student">
                @method('POST')
                @csrf

                <div class="form-group" title="{{ $messages['email.required'] }}">
                    <label class="control-label" for="email">Email</label>

                    <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                           tabindex="0"
                           name="email" value="{{ old('email') }}" id="email" required>

                    <span class="invalid-feedback">
                    @if ($errors->has('email'))
                            <strong>{{ $errors->first('email') }}</strong>
                        @endif
                    </span>
                </div>

                <div class="form-group" title="{{ $messages['fname.required'] }}">
                    <label class="control-label" for="fname">First Name</label>

                    <input class="form-control{{ $errors->has('fname') ? ' is-invalid' : '' }}" id="fname"
                           tabindex="0"
                           name="fname" type="text" value="{{ old('fname') }}"
                           autofocus required>

                    <span class="invalid-feedback">
                    @if ($errors->has('fname'))
                            <strong>{{ $errors->first('fname') }}</strong>
                        @endif
                    </span>
                </div>

                <div class="form-group" title="{{ $messages['lname.required'] }}">
                    <label class="control-label" for="fname">Last Name</label>

                    <input class="form-control{{ $errors->has('lname') ? ' is-invalid' : '' }}" id="lname"
                           tabindex="0"
                           name="lname" type="text" value="{{ old('lname') }}"
                           required>

                    <span class="invalid-feedback">
                    @if ($errors->has('lname'))
                            <strong>{{ $errors->first('lname') }}</strong>
                        @endif
                    </span>
                </div>

                <div class="form-group" title="{{ $messages['reg_num.required'] }}">
                    <label class="control-label" for="fname">Registration number</label>

                    <input class="form-control{{ $errors->has('reg_num') ? ' is-invalid' : '' }}" id="reg_num"
                           tabindex="0"
                           name="reg_num" type="text" value="{{ old('reg_num') }}"
                           pattern="/^@[A-Z]{2}[0-9]{4}$/im"
                           maxlength="8"
                           required>

                    <span class="invalid-feedback">
                    @if ($errors->has('reg_num'))<strong>{{ $errors->first('reg_num') }}</strong>@endif
                    </span>
                </div>

                <div class="form-group" title="{{ $messages['department.required'] }}">
                    <label class="control-label" for="department">Department</label>

                    <select id="department" name="department" class="form-control" tabindex="0"
                            oninput="this.value == 'admin' ? this.setCustomValidity('{{ $messages['department.required'] }}') : this.setCustomValidity('')">
                        <option
                            value="admin"{{ old('department', 'admin') == 'admin' ? 'selected' : '' }}>
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
                            aria-roledescription="Add Student to {{ $course->code }}" tabindex="0">Add Student
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('end_footer')
    <script type="text/javascript">
        $(function () {
            $.validator.setDefaults({
                onkeyup: false,
                errorPlacement: function (error, element) {
                    element.siblings('.invalid-feedback')
                        .html("<strong>" + error.text() + "</strong>")
                        .addClass('d-block');
                    return true;
                }
            });
            $.validator.addMethod('pattern', function (value, element, params) {
                return this.optional(element) || params[0].test(value);
            });
            $("#add-student").validate({
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
                        maxlength: 5
                    },
                    reg_num: {
                        required: true,
                        maxlength: 6,
                        pattern: /^@[A-Z]{2}[0-9]{4}$/im
                    },
                    email: {
                        required: true,
                        email: true,
                        pattern: /^[a-z].+@citycollege\.sheffield\.eu/im
                    }
                },
                messages: {
                    fname: {
                        required: '{{ $messages['fname.required'] }}',
                        minlength: '{{ $messages['fname.min'] }}',
                        maxlength: '{{ $messages['fname.max'] }}',
                    },
                    lname: {
                        required: '{{ $messages['lname.required'] }}',
                        minlength: '{{ $messages['lname.min'] }}',
                        maxlength: '{{ $messages['lname.max'] }}',
                    },
                    reg_num: {
                        required: '{{ $messages['reg_num.required'] }}',
                        pattern: '{{ $messages['reg_num.regex'] }}',
                        maxlength: '{{ $messages['reg_num.max'] }}'
                    },
                    email: {
                        required: '{{ $messages['email.required'] }}',
                        pattern: '{{ $messages['email.regex'] }}',
                        email: '{{ $messages['email.email'] }}',
                    }
                }
            });
        });
    </script>
@endsection
