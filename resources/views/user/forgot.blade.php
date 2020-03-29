@extends('layouts.app')

@section('content')
    <form role="form" method="POST" action="{{ url('/password/send') }}">
        @method('POST')
        @csrf

        <div class="form-group" title="{{ $messages['email.required'] ?? '' }}">
            <div class="form-text lead">Please insert your email, so we can send you instructions on how to reset your
                password.
            </div>
            <label class="control-label" for="email" id="email-lbl">Email</label>

            <div class="input-group">
                <input class="form-control{{ $errors->has('email') ? ' is-invalid' : null }}" id="email" tabindex="0"
                       name="email" type="text" value="{{ old('email') }}" placeholder="username"
                       required aria-labelledby="email-lbl"
                       minlength="5" maxlength="50"
                       aria-required="true"
                       data-rule-required="true"
                       data-msg-required="{!! $messages['email.required'] ?? '' !!}"
                       data-rule-minlegth="5"
                       data-msg-minlegth="{!! $messages['email.min'] ?? '' !!}"
                       data-rule-maxlength="50"
                       data-msg-maxlength="{!! $messages['email.max'] ?? '' !!}">
                <div class="input-group-append cursor-con" tabindex="-1">
                    <button class="btn btn-primary cursor-con" type="button"
                            tabindex="-1">{{ config('app.domain') }}</button>
                </div>
            </div>

            <span class="invalid-feedback d-block">
            @if ($errors->has('email'))<strong>{{ $errors->first('email') }}</strong>@endif
            </span>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-block btn-primary" role="button" title="Send Email"
                    aria-roledescription="Send Email" tabindex="0">Send Email
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
                        email: {
                            required: true,
                            email: true,
                            minlength: 5,
                            maxlength: 50,
                            pattern: '^[a-z]+$'
                        },
                    },
                    messages: {
                        email: {
                            required: "{!! $messages['email.required'] ?? '' !!}",
                            email: "{!! $messages['email.email'] ?? '' !!}",
                            minlength: "{!! $messages['email.min'] ?? '' !!}",
                            maxlength: "{!! $messages['email.max'] ?? '' !!}",
                            pattern: "{!! $messages['email.pattern'] ?? '' !!}",
                        },
                    }
                });

                return form.valid();
            });
        });
    </script>
@endsection
