<form role="form" method="POST" action="{{ $action }}">
    @method($method)
    @csrf

    <div class="form-group" title="{{ $messages['fname.required'] }}">
        <label class="control-label" for="fname">First Name</label>

        <input class="form-control{{ $errors->has('fname') ? ' is-invalid' : '' }}" id="fname" tabindex="0"
               name="fname" type="text" value="{{ old('fname', isset($user) ? $user->fname : '') }}" autofocus required
               oninvalid="this.setCustomValidity('{{ $messages['fname.required'] }}')"
               oninput="this.setCustomValidity('')">

        @if ($errors->has('fname'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('fname') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group" title="{{ $messages['lname.required'] }}">
        <label class="control-label" for="fname">Last Name</label>

        <input class="form-control{{ $errors->has('lname') ? ' is-invalid' : '' }}" id="lname" tabindex="0"
               name="lname" type="text" value="{{ old('lname', isset($user) ? $user->lname : '') }}" required
               oninvalid="this.setCustomValidity('{{ $messages['lname.required'] }}')"
               oninput="this.setCustomValidity('')">

        @if ($errors->has('lname'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('lname') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group" title="{{ $messages['email.required'] }}">
        <label class="control-label" for="email">Email</label>

        <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" tabindex="0"
               name="email" value="{{ old('email', isset($user) ? $user->email : '') }}" id="email" required
               oninvalid="this.validity.patternMismatch ? this.setCustomValidity('{{ $messages['email.regex'] }}') : this.setCustomValidity('{{ $messages['email.required'] }}')"
               oninput="this.setCustomValidity('')" pattern="^[a-z].+@citycollege\.sheffield\.eu">

        @if ($errors->has('email'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>

    @if(!isset($user))
        <div class="form-group" title="{{ $messages['password.required'] }}">
            <label class="control-label" for="password">Password</label>

            <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" tabindex="0"
                   name="password" type="password" value="{{ old('password') }}" required minlength="3" maxlength="50"
                   oninvalid="if(this.validity.tooShort) this.setCustomValidity('{{ $messages['password.min'] }}'); else if(this.validity.tooLong) this.setCustomValidity('{{ $messages['password.max'] }}');"
                   oninput="this.setCustomValidity('')">

            @if ($errors->has('password'))
                <span class="invalid-feedback">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
            @endif
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

        @if ($errors->has('department'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('department') }}</strong>
            </span>
        @endif
    </div>

    {{--    <div class="form-group">--}}
    {{--        <label class="py-0" for="terms" title="{{ $messages['terms.required'] }}">Do you agree--}}
    {{--            with {{ config('app.name') }} <b>Terms and Conditions</b>?--}}
    {{--            <input class="ml-3" type="checkbox" required id="terms" name="terms"--}}
    {{--                   oninvalid="this.setCustomValidity('{{ $messages['terms.required'] }}');"--}}
    {{--                   oninput="this.setCustomValidity('');" tabindex="0">--}}
    {{--        </label>--}}
    {{--    </div>--}}

    @if(request()->route()->named('*register') && request()->ip() != '127.0.0.1')
        <div class="form-group">
            {!! htmlFormSnippet() !!}

            @if ($errors->has('g-recaptcha-response'))
                <span class="invalid-feedback">
            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
        </span>
            @endif
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
