@extends('layouts.app')

@section('content')
    <form role="form" method="POST" action="{{ url('/password/send') }}">
        @method('POST')
        @csrf

        <div class="form-group" title="{{ $messages['email.required'] ?? '' }}">
            <div class="form-text lead">Please insert your email, so we can send you instructions on how to reset your password.</div>
            <label class="control-label" for="password">Email</label>

            <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="password" tabindex="0"
                   name="email" type="email" value="{{ old('email') }}" required oninvalid="this.setCustomValidity('{{ $messages['email.required'] ?? '' }}')" oninput="this.setCustomValidity('')" placeholder="your email@citycollege.sheffield.eu">

            @if ($errors->has('email'))
                <span class="invalid-feedback">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
            @endif
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-block btn-primary" role="button" title="Send Email" aria-roledescription="Send Email" tabindex="0">Send Email</button>
        </div>
    </form>
@endsection
