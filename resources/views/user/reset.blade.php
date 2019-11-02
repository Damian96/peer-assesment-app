@extends('layouts.app')

@section('content')
    <form role="form" method="POST" action="{{ '/update' }}">
        @method('PUT')
        @csrf

        <input class="hidden" type="hidden" name="action" value="reset">
        <input class="hidden" type="hidden" name="email" value="{{ $email }}">
        <input class="hidden" type="hidden" name="token" value="{{ $token }}">

        <div class="form-group" title="{{ $messages['password.required'] ?? '' }}">
            <label class="control-label" for="password">Password</label>

            <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" tabindex="0"
                   name="password" type="password" value="{{ old('password') }}" required oninvalid="this.setCustomValidity('{{ $messages['password.required'] ?? '' }}')" oninput="this.setCustomValidity('')">

            @if ($errors->has('password'))
                <span class="invalid-feedback">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
            @endif
        </div>

        <div class="form-group" title="{{ $messages['password_confirmation.required'] ?? '' }}">
            <label class="control-label" for="password_confirmation">Confirm Password</label>

            <input class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" id="password_confirmation" tabindex="0"
                   name="password_confirmation" type="password" value="{{ old('password_confirmation') }}" required oninvalid="this.setCustomValidity('{{ $messages['password_confirmation.required'] ?? '' }}')" oninput="this.setCustomValidity('')">

            @if ($errors->has('password_confirmation'))
                <span class="invalid-feedback">
                <strong>{{ $errors->first('password_confirmation') }}</strong>
            </span>
            @endif
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-block btn-primary" role="button" title="Reset Password" aria-roledescription="Reset Password" tabindex="0">Reset</button>
        </div>
    </form>
@endsection
