@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>Welcome <strong>{{ $user->name }}</strong></h2>
        </div>
    </div>
    @if (!Auth::user()->isStudent())
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div id="statistics" class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <strong class="mr-auto">{{ Auth::user()->getRole() }} Statistics</strong>
                    </div>
                    <div class="toast-body">
                        <ul class="">
                            <li>Total Students: <b>{{ $enrolled }}</b></li>
                            <li>Total Courses: <b>{{ $courses }}</b></li>
                            <li>Total Sessions: <b>{{ $sessions->count() }}</b>
                                <ul>
                                    <li>Opened:
                                        <b>{{ Auth::user()->sessions()->whereDate('sessions.deadline', '>=', date(config('constants.date.stamp')))->count() }}</b>
                                    </li>
                                    <li>Closed:
                                        <b>{{ Auth::user()->sessions()->whereDate('sessions.deadline', '<=', date(config('constants.date.stamp')))->count() }}</b>
                                    </li>
                                </ul>
                            </li>
                            <li>Total Forms: <b>{{ $forms }}</b></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('end_footer')
    <script type="text/javascript" defer>
        $(function () {
            $('.toast').toast();
        });
    </script>
@endsection
