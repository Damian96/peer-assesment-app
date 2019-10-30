@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if(request()->session()->get('reset_step', false) == 1)
            <form role="form" method="POST" action="{{ url('/password/change/2') }}">
                @method('POST')
                @csrf

                <div class="form-group">
                    Please enter your email, so we can send you a Verification Code.
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" id="email" autofocus tabindex="0" placeholder="your citycollege.sheffield.eu email">

                    @if ($errors->has('email'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>

                @if (request()->ip() != '127.0.0.1')
                <div class="form-group">
                    {!! htmlFormSnippet() !!}
                    @if ($errors->has('g-recaptcha-response'))
                        <span class="invalid-feedback" style="display: block;">
                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                        </span>
                    @endif
                </div>
                @else
                    <input type="hidden" name="localhost" value="1" class="hidden" height="0" width="0" tabindex="-1">
                @endif

                <div class="form-group">
                    <button type="submit" class="btn btn-block btn-primary" role="button" aria-roledescription="Send a reset email" tabindex="0">Send</button>
                </div>

            </form>
            @elseif (request()->session()->get('reset_step', false) == 2)
                <form role="form" method="POST" action="{{ url('/password/change/3') }}">
                    @method('POST')
                    @csrf

                    <div class="form-group">
                        <label for="code">Verification Code
                            <input type="text" required maxlength="6" name="code" role="textbox" autofocus tabindex="0"/>
                        </label>

                        @if ($errors->has('code'))
                            <span class="invalid-feedback">
                            <strong>{{ $errors->first('code') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-primary" role="button" aria-roledescription="Submit Verification Code" tabindex="0">Submit</button>
                    </div>
                </form>
            @elseif (request()->session()->get('reset_step', false) == 3)
                <form role="form" action="{{ url('/users/' . request()->user()->id) }}">
                    @method('PUT')
                    @csrf

                    <div class="form-group">
                        <label for="password">Password
                            <input type="password" required minlength="6" maxlength="50" name="password" role="textbox" autofocus tabindex="0"/>
                        </label>
                        <label for="password">Confirm Password
                            <input type="password" required minlength="6" maxlength="50" name="password_confirmation" role="textbox" tabindex="0"/>
                        </label>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-primary" role="button" aria-roledescription="Submit Verification Code" tabindex="0">Submit</button>
                    </div>
                </form>
            @else

            @endif
        </div>
    </div>
@endsection
