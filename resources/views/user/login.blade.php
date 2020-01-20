@extends('layouts.app')

@section('end_head')
    <style type="text/css">
        body {
            background-color: #fcab0e;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23343a40' fill-opacity='0.4' fill-rule='evenodd'/%3E%3C/svg%3E");
        }

        .login-container {
            background: #fff;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="offset-md-1 col-sm-12 col-md-10 login-container card">
            <h3 class="title">{{ $title ?? '' }}</h3>
            <form role="form" method="POST" action="{{ url('/auth') }}">
                @method('POST')
                @csrf

                <div class="form-group">
                    <label id="email-lbl" class="control-label" for="email">Email</label>

                    <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                           name="email" value="{{ old('email') }}" id="email" autofocus tabindex="0"
                           required aria-labelledby="email-lbl"
                           minlength="3" maxlength="255"
                           aria-required="true"
                           data-rule-required="true"
                           data-msg-required="{!! $messages['email.required'] ?? '' !!}"
                           data-rule-minlegth="3"
                           data-msg-minlegth="{!! $messages['email.min'] ?? '' !!}"
                           data-rule-maxlength="255"
                           data-msg-maxlength="{!! $messages['email.max'] ?? '' !!}">

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
                    <label>Don't have an account? <a href="{{ url('/register') }}" title="Register" tabindex="-1">Register
                            here</a>.</label><br>
                    <label><a href="{{ url('/password/forgot') }}" title="Forgot your password?" tabindex="-1">Forgot
                            your password?</a></label>
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
                            required: "{!! $messages['email.required'] !!}",
                            email: "{!! $messages['email.regex'] !!}",
                            maxlength: "{!! $messages['email.filter'] !!}"
                        },
                        password: {
                            required: "{!! $messages['password.required'] !!}",
                            minlength: "{!! $messages['password.min'] !!}",
                            maxlength: "{!! $messages['password.max'] !!}"
                        },
                    }
                });

                // console.log(form, form.valid());
                // event.preventDefault();
                // return false;
                return form.valid();
            });
        });
    </script>
@endsection
