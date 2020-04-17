<form role="form" method="POST" action="{{ $action }}">
    @method($method)
    @csrf

    <div class="form-group" title="{{ $messages['fname.required'] }}">
        <label class="control-label" for="fname">First Name</label>

        <input class="form-control{{ $errors->has('fname') ? ' is-invalid' : '' }}" id="fname" tabindex="0"
               name="fname" type="text" value="{{ old('fname', isset($user) ? $user->fname : '') }}" autofocus required
               oninvalid="this.setCustomValidity('{{ $messages['fname.required'] }}')"
               oninput="this.setCustomValidity('')">

        <span class="invalid-feedback d-block">
        @if ($errors->has('fname'))
                <strong>{{ $errors->first('fname') }}</strong>
            @endif
            </span>
    </div>

    <div class="form-group" title="{{ $messages['lname.required'] }}">
        <label class="control-label" for="fname">Last Name</label>

        <input class="form-control{{ $errors->has('lname') ? ' is-invalid' : '' }}" id="lname" tabindex="0"
               name="lname" type="text" value="{{ old('lname', isset($user) ? $user->lname : '') }}" required
               oninvalid="this.setCustomValidity('{{ $messages['lname.required'] }}')"
               oninput="this.setCustomValidity('')">

        <span class="invalid-feedback d-block">
        @if ($errors->has('lname'))
                <strong>{{ $errors->first('lname') }}</strong>
            @endif
            </span>
    </div>

    <div class="form-group" title="{{ $messages['email.required'] }}">
        <label id="email-lbl" class="control-label" for="email">Email</label>

        <div class="input-group">
            <input type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                   name="email" value="{{ old('email') }}" id="email" autofocus tabindex="0"
                   required aria-labelledby="email-lbl"
                   minlength="3" maxlength="100"
                   aria-required="true"
                   data-rule-required="true"
                   data-msg-required="{!! $messages['email.required'] ?? '' !!}"
                   data-rule-minlegth="3"
                   data-msg-minlegth="{!! $messages['email.min'] ?? '' !!}"
                   data-rule-maxlength="100"
                   data-msg-maxlength="{!! $messages['email.max'] ?? '' !!}">
            <div class="input-group-append cursor-con" tabindex="-1">
                <button class="btn btn-primary cursor-con" type="button"
                        tabindex="-1">{{ config('app.domain') }}</button>
            </div>
        </div>

        <span class="invalid-feedback d-block">@if ($errors->has('email'))
                <strong>{{ $errors->first('email') }}</strong>@endif</span>
    </div>

    <div class="form-group">
        <label class="control-label" for="reg_num">Registration Number</label>

        <input type="text" class="form-control{{ $errors->has('reg_num') ? ' is-invalid' : '' }}" tabindex="0"
               name="reg_num" value="{{ old('reg_num', isset($user) ? $user->reg_num : '') }}" id="reg_num"
               required aria-required="true"
               data-rule-required="true"
               data-msg-required="{{ $messages['reg_num.required'] }}"
               data-rule-pattern="[A-Z0-9]{4,}"
               data-msg-pattern="{{ $messages['reg_num.regex'] }}">
        <span class="invalid-feedback d-block">
            @if ($errors->has('reg_num'))<strong>{{ $errors->first('reg_num') }}</strong>@endif
        </span>
    </div>

    @if(!isset($user))
        <div class="form-group" title="{{ $messages['password.required'] }}">
            <label class="control-label" for="password">Password</label>

            <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" tabindex="0"
                   name="password" type="password" value="{{ old('password') }}"
                   required="true" aria-required="true"
                   data-rule-required="true" data-msg-required="{{ $messages['password.required'] }}"
                   data-rule-minlength="3" data-msg-minlength="{{ $messages['password.min'] }}"
                   data-rule-maxlength="10" data-msg-maxlength="{{ $messages['password.max'] }}">

            <span class="invalid-feedback d-block">@if ($errors->has('password'))
                    <strong>{{ $errors->first('password') }}</strong>@endif</span>
        </div>

        <div class="form-group">
            <label class="control-label" for="password_confirmation">Confirm Password</label>
            <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" tabindex="0"
                   name="password_confirmation" type="password" value="{{ old('password_confirmation') }}"
                   required="true" aria-required="true"
                   data-rule-required="true" data-msg-required="Please validate your password!"
                   data-rule-equalTo="#password" data-msg-equalTo="Passwords do not match!"
                   data-rule-minlength="3" data-msg-minlength="{{ $messages['password.min'] }}"
                   data-rule-maxlength="10" data-msg-maxlength="{{ $messages['password.max'] }}">
            <span class="invalid-feedback d-block">@if ($errors->has('password_confirmation'))
                    <strong>{{ $errors->first('password_confirmation') }}</strong>@endif</span>
        </div>

    @endif

    <div class="form-group">
        <label class="control-label" for="department">Department</label>

        <select id="department" name="department" class="form-control" tabindex="0">
            <option
                value="admin"{{ old('department', isset($user) ? $user->department : '') == 'admin' ? 'selected' : '' }}>
                &nbsp;---&nbsp;
            </option>
            <option value="CS"{{ old('department', isset($user) ? $user->department : '') == 'CS' ? 'selected' : '' }}>
                Computer Science
            </option>
            <option value="ES"{{ old('department', isset($user) ? $user->department : '') == 'ES' ? 'selected' : '' }}>
                English Studies
            </option>
            <option value="BS"{{ old('department', isset($user) ? $user->department : '') == 'BS' ? 'selected' : '' }}>
                Business Administration & Economics
            </option>
            <option
                value="CPY"{{ old('department', isset($user) ? $user->department : '') == 'CPY' ? 'selected' : '' }}>
                Psychology Studies
            </option>
            <option
                value="MBA"{{ old('department', isset($user) ? $user->department : '') == 'MBA' ? 'selected' : '' }}>
                Executive MBA
            </option>
        </select>

        <span class="invalid-feedback d-block">@if ($errors->has('department'))
                <strong>{{ $errors->first('department') }}</strong>@endif
            </span>
    </div>

    @if(request()->route()->named('*register') && request()->ip() != '127.0.0.1')
        <div class="form-group">
            {!! htmlFormSnippet() !!}

            <span class="invalid-feedback d-block">
            @if ($errors->has('g-recaptcha-response'))
                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                @endif
        </span>
        </div>
    @else
        <input type="hidden" class="hidden" width="0" height="0" name="localhost" value="1"/>
    @endif

    <div class="form-text">
        <label>Already registered? <a href="{{ url('/login') }}" title="Login">Login here</a>.</label>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-block btn-primary" role="button" title="{{ $button['title'] }}"
                aria-roledescription="{{ $button['title'] }}" tabindex="0">{{ $button['label'] }}</button>
    </div>
</form>


@section('end_footer')
    <script type="text/javascript" defer>
        $(document).on('focusout change', 'input, select, textarea', function () {
            return $(this).valid();
        });
        $(document).on('submit', 'form', function (event) {
            let form = $(event.target);
            form.validate({
                rules: {
                    fname: {
                        required: true,
                        minlength: 3,
                        maxlength: 25
                    },
                    lname: {
                        required: true,
                        minlength: 3,
                        maxlength: 25,
                    },
                    email: {
                        required: true,
                        pattern: '{{ '^[a-z].+@' . env('ORG_DOMAIN') }}'
                    },
                    reg_num: {
                        required: true,
                        pattern: '[A-Z0-9]{4,}',
                        minlength: 4
                    },
                    password: {
                        required: true,
                        minlength: 3,
                        maxlength: 10
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: '#password',
                        minlength: 3,
                        maxlength: 10,
                    }
                },
                messages: {
                    email: {
                        required: "{!! $messages['email.required'] !!}",
                        pattern: "{!! $messages['email.regex'] !!}",
                    },
                    reg_num: {
                        required: "{!! $messages['reg_num.required'] !!}",
                        pattern: "{!! $messages['reg_num.regex'] !!}",
                        minlength: "{!! $messages['reg_num.min'] !!}",
                    },
                    password: {
                        required: '{!! $messages['password.required'] !!}',
                        minlength: '{!! $messages['password.min'] !!}',
                        maxlength: '{!! $messages['password.max'] !!}',
                    },
                    password_confirmation: {
                        equalTo: 'Passwords do not match!',
                        required: 'Please validate your password!',
                        minlength: '{!! $messages['password.min'] !!}',
                        maxlength: '{!! $messages['password.max'] !!}',
                    }
                }
            });

            return form.valid();
        });
    </script>
@endsection
