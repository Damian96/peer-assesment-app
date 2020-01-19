@extends('layouts.app')

@section('content')
    <form role="form" method="POST" action="{{ '/update' }}">
        @method('PUT')
        @csrf

        <input class="hidden" type="hidden" name="action" value="reset">
        <input class="hidden" type="hidden" name="email" value="{{ $user->email }}">
        <input class="hidden" type="hidden" name="token" value="{{ $user->token }}">

        <div class="form-group" title="{{ $messages['password.required'] ?? '' }}">
            <label id="password-lbl" class="control-label" for="password">Password</label>

            <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" tabindex="0"
                   name="password" type="password" value="{{ old('password') }}" required
                   oninvalid="this.setCustomValidity('{{ $messages['password.required'] ?? '' }}')"
                   oninput="this.setCustomValidity('')"
                   aria-labelledby="password-lbl"
                   data-rule-required="true"
                   data-msg-required="{!! $messages['password.required'] !!}"
                   data-rule-minlength="3"
                   data-msg-minlength="{!! $messages['password.min'] !!}"
                   data-rule-maxlength="50"
                   data-msg-maxlength="{!! $messages['password.max'] !!}">

            <span class="invalid-feedback">@if ($errors->has('password'))
                    <strong>{{ $errors->first('password') }}</strong>@endif</span>
        </div>

        <div class="form-group" title="{{ $messages['password_confirmation.required'] ?? '' }}">
            <label id="password_confirmation-lbl" class="control-label" for="password_confirmation">Confirm
                Password</label>

            <input class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                   id="password_confirmation" tabindex="0"
                   name="password_confirmation" type="password" value="{{ old('password_confirmation') }}" required
                   oninvalid="this.setCustomValidity('{{ $messages['password_confirmation.required'] ?? '' }}')"
                   oninput="this.setCustomValidity('')"
                   aria-labelledby="password_confirmation-lbl"
                   data-rule-required="true"
                   data-msg-required="{!! $messages['password_confirmation.required'] !!}"
                   data-rule-minlength="3"
                   data-msg-minlength="{!! $messages['password_confirmation.min'] !!}"
                   data-rule-maxlength="50"
                   data-msg-maxlength="{!! $messages['password_confirmation.max'] !!}"
                   data-rule-equalTo="#password"
                   data-msg-equalTo="{!! $messages['password_confirmation.confirmed'] !!}">

            <span class="invalid-feedback">
            @if ($errors->has('password_confirmation'))
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                @endif
            </span>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-block btn-primary" role="button" title="Reset Password"
                    aria-roledescription="Reset Password" tabindex="0">Reset
            </button>
        </div>
    </form>
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
                        password: {
                            required: true,
                            minlength: 3,
                            maxlength: 50,
                        },
                        password_confirmation: {
                            required: true,
                            minlength: 3,
                            maxlength: 50,
                            equalTo: '#password',
                        },
                    },
                    messages: {
                        password: {
                            required: "{!! $messages['password.required'] !!}",
                            minlength: "{!! $messages['password.min'] !!}",
                            maxlength: "{!! $messages['password.max'] !!}"
                        },
                        password_confirmation: {
                            required: "{!! $messages['password_confirmation.required'] !!}",
                            minlength: "{!! $messages['password_confirmation.min'] !!}",
                            maxlength: "{!! $messages['password_confirmation.max'] !!}",
                            equalTo: "{!! $messages['password_confirmation.confirmed'] !!}",
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
