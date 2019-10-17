@extends('layouts.app')

@section('content')
    <?php
    $attributes = [
        'email' => request('email'),
        'password' => request('password'),
        'fname' => request('fname'),
        'lname' => request('lname'),
        'instructor' => request('instructor')
    ];
    ?>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form role="form" method="POST" action="{{ url('/register') }}">
                @method('POST')
                @csrf

                <div class="form-group">
                    <label class="control-label" for="fname">First Name</label>

                    <input class="form-control{{ $errors->has('fname') ? ' is-invalid' : '' }}" id="fname"
                           name="fname" type="text" value="{{ $attributes['fname'] }}" autofocus>

                    @if ($errors->has('fname'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('fname') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label class="control-label" for="fname">Last Name</label>

                    <input class="form-control{{ $errors->has('lname') ? ' is-invalid' : '' }}" id="lname"
                           name="lname" type="text" value="{{ $attributes['lname'] }}">

                    @if ($errors->has('lname'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('lname') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label class="control-label" for="email">Email</label>

                    <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $attributes['email'] }}" id="email">

                    @if ($errors->has('email'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label class="control-label" for="password">Password</label>

                    <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password"
                           name="password" type="password" value="{{ $attributes['password'] }}">

                    @if ($errors->has('password'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label class="py-0" for="instructor">Instructor<input class="ml-3" id="instructor" name="instructor" type="checkbox" value="{{ $attributes['instructor'] }}" onclick="this.value = this.checked ? '1' : '0';">
                    </label>
                </div>

                <div class="form-group">
                    <label class="control-label" for="department">Department</label>

                    <select id="department" name="department" class="form-control">
                        <option value="admin" selected>&nbsp;---&nbsp;</option>
                        <option value="CS">Computer Science</option>
                        <option value="ES">English Studies</option>
                        <option value="BS">Business Administration & Economics</option>
                        <option value="PSY">Psychology Studies</option>
                    </select>

                    @if ($errors->has('department'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('department') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-block btn-primary">Register</button>
                </div>
            </form>
        </div>
    </div>
@endsection
