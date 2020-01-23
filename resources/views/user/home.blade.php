@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>Welcome <strong>{{ $user->name }}</strong></h2>
        </div>
    </div>
    <!-- TODO: implement Courses search form -->
    {{--    <div class="row">--}}
    {{--        <div class="col-md-12">--}}
    {{--        </div>--}}
    {{--    </div>--}}
@endsection
