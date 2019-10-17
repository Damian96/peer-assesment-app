@extends('layouts.app')

@section('content')
    <?php $atts = [
            'email' => request('email'),
            'password' => request('password')
        ];
    ?>
    <div class="row">
        <div class="col-md-12">
            @if (session()->has('message'))
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">Well Done!</h4>
                {{ session()->get('message') }}
            </div>
            @endif

            <form role="form" method="POST" action="{{ url('/login') }}">
                @method('POST')
                @csrf

                <div class="form-group">
                    <label class="control-label" for="email">Email</label>

                    <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $atts['email'] }}" id="email" autofocus>

                    @if ($errors->has('email'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label class="control-label" for="password">Password</label>

                    <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password"
                           name="password" type="password" value="{{ $atts['password'] }}">

                    @if ($errors->has('password'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label><input type="checkbox" name="remember">Remember Me</label>
                </div>

                <div class="form-text">
                    <label>Don't have an account? <a href="{{ url('/register') }}" title="Register">Register here</a>.</label>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-block btn-primary">Login</button>
                </div>
            </form>
        </div>
    </div>
@endsection
