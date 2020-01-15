@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <form role="form" method="POST" action="{{ url('/auth') }}">
                @method('POST')
                @csrf

                <div class="form-group">
                    <label class="control-label" for="email">Email</label>

                    <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                           name="email" value="{{ old('email') }}" id="email" autofocus tabindex="0">

                    @if ($errors->has('email'))
                        <span class="invalid-feedback d-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label class="control-label" for="password">Password</label>

                    <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password"
                           name="password" type="password" value="{{ old('password') }}" tabindex="0">

                    @if ($errors->has('password'))
                        <span class="invalid-feedback d-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    @component('includes.checkbox')
                        @slot('label'){{ 'Remember Me' }}@endslot
                        @slot('name'){{ 'remember' }}@endslot
                        @slot('checked'){{ old('remember', '') }}@endslot
                        @slot('value'){{ old('remember', 'off') }}@endslot
                    @endcomponent
                </div>

                @if(env('APP_ENV', false) != 'local' || ! env('APP_DEBUG', false))
                    <div class="form-group">
                        {!! htmlFormSnippet() !!}

                        @if ($errors->has('g-recaptcha-response'))
                            <span
                                class="invalid-feedback d-block"><strong>{{ $errors->first('g-recaptcha-response') }}</strong></span>
                        @endif
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
