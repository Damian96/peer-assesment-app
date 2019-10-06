@extends('layouts.app')

@section('content')
    <?php $atts = [
            'email' => request('email'),
            'password' => request('password')
        ];
    ?>
    <?php $auth_col = config('backpack.base.authentication_column'); ?>
    <?php $auth_col_name = config('backpack.base.authentication_column_name'); ?>
    <div class="row">
        <div class="col-md-12">
            <form role="form" method="POST" action="{{ config('constants.options.student.login') }}">
                {!! csrf_field() !!}

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

                    <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="email"
                           name="password" type="password" value="{{ $atts['password'] }}">

                    @if ($errors->has('password'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

{{--                <div class="form-group">--}}
{{--                    <label><input type="checkbox" name="remember">{{ trans('backpack::base.remember_me') }}</label>--}}
{{--                </div>--}}

                <div class="form-group">
                    <button type="submit" class="btn btn-block btn-primary">{{ trans('backpack::base.login') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
