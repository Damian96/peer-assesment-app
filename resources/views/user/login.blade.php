@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <form role="form" method="POST" action="{{ url('/auth') }}">
                @method('POST')
                @csrf

                <div class="form-group">
                    <label id="email-lbl" class="control-label" for="email">Email</label>

                    <div class="input-group">
                        <input type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                               name="email" value="{{ old('email') }}" id="email" autofocus tabindex="0"
                               required aria-labelledby="email-lbl"
                               minlength="3" maxlength="255"
                               aria-required="true"
                               {{--                               data-rule-pattern=""--}}
                               {{--                               data-msg-pattern=""--}}
                               data-rule-required="true"
                               data-msg-required="{!! $messages['email.required'] ?? '' !!}"
                               data-rule-minlegth="3"
                               data-msg-minlegth="{!! $messages['email.min'] ?? '' !!}"
                               data-rule-maxlength="255"
                               data-msg-maxlength="{!! $messages['email.max'] ?? '' !!}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button">{{ config('app.domain') }}</button>
                        </div>
                    </div>

                    <span class="invalid-feedback d-block">@if ($errors->has('email'))
                            <strong>{{ $errors->first('email') }}</strong>@endif</span>
                </div>

                <div class="form-group">
                    <label id="password-lbl" class="control-label" for="password">Password</label>

                    <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password"
                           name="password" type="password" value="{{ old('password') }}" tabindex="0"
                           required aria-labelledby="password-lbl"
                           minlength="3" maxlength="255"
                           aria-required="true"
                           data-rule-required="true"
                           data-msg-required="{!! $messages['password.required'] ?? '' !!}"
                           data-rule-minlegth="3"
                           data-msg-minlegth="{!! $messages['password.min'] ?? '' !!}"
                           data-rule-maxlength="255"
                           data-msg-maxlength="{!! $messages['password.max'] ?? '' !!}">

                    <span class="invalid-feedback d-block">@if ($errors->has('password'))
                            <strong>{{ $errors->first('password') }}</strong>@endif</span>
                </div>

                <div class="form-group">
                    @component('includes.checkbox')
                        @slot('label'){{ 'Remember Me' }}@endslot
                        @slot('name'){{ 'remember' }}@endslot
                        @slot('checked'){{ old('remember', '') }}@endslot
                        @slot('value'){{ old('remember', 'off') }}@endslot
                    @endcomponent
                </div>

                @if(env('APP_ENV', false) !== 'local' || ! env('APP_DEBUG', false))
                    <div class="form-group">
                        {!! htmlFormSnippet() !!}

                        <span class="invalid-feedback d-block">
                        @if ($errors->has('g-recaptcha-response'))
                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>@endif
                        </span>
                    </div>
                @else
                    <input type="hidden" class="hidden" width="0" height="0" name="localhost" value="1"/>
                @endif

                <div class="form-text">
                    <span></span>Don't have an account? <a href="{{ url('/register') }}" title="Register"
                                                           aria-label="Register" tabindex="-1">Register
                        here</a>.</span><br>
                    <span><a href="{{ url('/password/forgot') }}" title="Forgot your password?"
                             aria-label="Forgot your password?" tabindex="-1">Forgot
                            your password?</a></span>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-block btn-primary" role="button"
                            aria-roledescription="Login into {{ config('app.name') }}" tabindex="0">Login
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('end_footer')
    <script type="text/javascript" defer>
        $(function () {
            $(document).on('focusout change', 'input, select, textarea', function () {
                return $(this).valid();
            });
            $(document).on('submit', 'form', function (event) {
                let form = $(event.target);
                form.validate({
                    rules: {
                        email: {
                            required: true,
                            email: true,
                            maxlength: 255
                        },
                        password: {
                            required: true,
                            minlength: 3,
                            maxlength: 255
                        }
                    },
                    messages: {
                        email: {
                            required: "{!! $messages['email.required'] ?? '' !!}",
                            pattern: "{!! $messages['email.regex'] ?? '' !!}",
                        },
                        password: {
                            required: "{!! $messages['password.required'] ?? '' !!}",
                            minlength: "{!! $messages['password.min'] ?? '' !!}",
                            maxlength: "{!! $messages['password.max'] ?? '' !!}"
                        },
                    }
                });

                return form.valid();
            });
        });
    </script>
@endsection
