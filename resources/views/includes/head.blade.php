<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    {{--    @TODO fill out SEO tags --}}
    {{--    <meta name="description" content="">--}}
    {{--    <meta name="author" content="">--}}
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ isset($title) ? $title : (isset($error_number) ? 'Error ' . $error_number : '') . ' | ' . config('app.name') }}</title>

    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <script charset="utf-8" src="{{ mix('/js/manifest.js') }}"></script>
    <script charset="utf-8" src="{{ mix('/js/vendor.js') }}"></script>
    <script charset="utf-8" src="{{ mix('/js/app.js') }}"></script>

    {{--    @if (env('APP_ENV') !== 'local')--}}
    {{--    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">--}}
    {{--    @endif--}}

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if ((env('APP_ENV', false) !== 'local' || ! env('APP_DEBUG', false)) && (Route::current()->named('login') || Route::current()->named('register')))
        {!! htmlScriptTagJsApi([
            'action' => 'homepage',
        ]) !!}
    @endif
)
    <script type="text/javascript">
        const urlParams = new URLSearchParams(window.location.search);
        window.urlParams = urlParams;
    </script>

    <script type="text/javascript" defer>
        var isHover = function (e) {
            /**
             * @var HTMLElement e
             */
            return (e.parentElement.querySelector(':hover') === e);
        };
        $.fn.highlight = function (speed = 3000, color = 'lightyellow') {
            this.effect("highlight", {color: color}, speed);
        };
        $.validator.setDefaults({
            onkeyup: false,
            errorPlacement: function (error, element) {
                element.addClass('is-invalid')
                    .closest('.form-group')
                    .find('.invalid-feedback')
                    .html("<strong>" + error.text() + "</strong>")
                    .addClass('d-block');
                return true;
            },
            success: function (label, element) {
                element = $(element);
                element.removeClass('is-invalid')
                    .addClass('is-valid')
                    .closest('.form-group')
                    .find('.invalid-feedback')
                    .html('')
                    .removeClass('d-block');
                return true;
            },
        });
        $.validator.addMethod('in', function (value, element, params) {
            let arr = params.split(',');
            return this.optional(element) || (arr.length && arr.includes(value));
        });
        $.validator.addMethod('checked', function (value, element, params) {
            return this.optional(element) || element.checked || value === 'on' || value === '1';
        });
        $.validator.addMethod('pattern', function (value, element, params) {
            return this.optional(element) || (new RegExp(params, 'm')).test(value);
        });
        $.validator.addMethod('different', function (value, element, params) {
            return this.optional(element) || value !== params;
        });
        $.widget("ui.tooltip", $.ui.tooltip, {
            _open: function () {
                // console.log(this.element.siblings('[class*=toggle]').tooltip('show'));
                return false;
                // throw Error('tooltip:open');
            },
        });
        $(document).ready(function () {
            // Show User's Menu
            $('.navbar-toggler').on('click', function (e) {
                if (!$('#userMenu').hasClass('show')) {
                    $('#userMenu').addClass('show');
                } else {
                    $('#userMenu').removeClass('show');
                }
                return true;
            });
        })
    </script>

    @yield('end_head')
</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name') }}</a>

    @auth
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainMenu"
                aria-controls="mainMenu"
                aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2" id="mainMenu">
            <ul class="navbar-nav mr-auto">
                @if(Auth::user()->can('user.home'))
                    @if (strpos(Route::current()->getName(), 'home') !== false)
                        <li class="nav-item active">
                            <a class="nav-link" href="{{ url('/home') }}">Home</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/home') }}">Home</a>
                        </li>
                    @endif
                @endif
                @if (Auth::user()->isStudent())
                    <li class="nav-item{{ strpos(Route::current()->getName(), 'course.index') !== false ? ' active' : '' }}">
                        <a class="nav-link" href="{{ url('/courses') }}">Courses</a>
                    </li>
                    <li class="nav-item{{ strpos(Route::current()->getName(), 'session.index') !== false ? ' active' : '' }}">
                        <a class="nav-link" href="{{ url('/sessions') }}">Sessions</a>
                    </li>
                @else
                    <li class="nav-item dropdown{{ (strpos(Route::current()->getName(), 'course.') !== false) ? ' active' : '' }}">
                        <a class="nav-link dropdown-toggle" id="course-dropdown" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">Courses</a>
                        <div class="dropdown-menu" aria-labelledby="course-dropdown">
                            <a class="dropdown-item{{ (strpos(Route::current()->getName(), 'course.index') !== false) ? ' active' : '' }}"
                               href="{{ url('/courses') }}">My Courses</a>
                            <a class="dropdown-item{{ (strpos(Route::current()->getName(), 'course.create') !== false) ? ' active' : '' }}"
                               href="{{ url('/courses/create') }}">Create</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown{{ (strpos(Route::current()->getName(), 'session.') !== false) ? ' active' : '' }}">
                        <a class="nav-link dropdown-toggle" id="course-dropdown" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">Sessions</a>
                        <div class="dropdown-menu" aria-labelledby="course-dropdown">
                            <a class="dropdown-item{{ (strpos(Route::current()->getName(), 'session.index') !== false) ? ' active' : '' }}"
                               href="{{ url('/sessions') }}">My Sessions</a>
                            <a class="dropdown-item{{ (strpos(Route::current()->getName(), 'session.create') !== false) ? ' active' : '' }}"
                               href="{{ url('/sessions/create') }}">Create</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown{{ (strpos(Route::current()->getName(), 'form.') !== false) ? ' active' : '' }}">
                        <a class="nav-link dropdown-toggle" id="course-dropdown" data-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">Forms</a>
                        <div class="dropdown-menu" aria-labelledby="course-dropdown">
                            <a class="dropdown-item{{ (strpos(Route::current()->getName(), 'form.index') !== false) ? ' active' : '' }}"
                               href="{{ url('/forms/') }}">My Forms</a>
                            <a class="dropdown-item{{ (strpos(Route::current()->getName(), 'form.create') !== false) ? ' active' : '' }}"
                               href="{{ url('/forms/create') }}">Create</a>
                        </div>
                    </li>
                @endif
            </ul>
        </div>
        <div class="navbar-collapse collapse w-100 order-2 order-md-1 dual-collapse2" id="userMenu">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown{{ (strpos(Route::current()->getName(), 'user.show') !== false || strpos(Route::current()->getName(), 'user.profile') !== false) ? ' active' : '' }}">
                    <a class="nav-link dropdown-toggle" id="user-dropdown" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }}</a>
                    <div class="dropdown-menu" aria-labelledby="user-dropdown">
                        <a class="dropdown-item{{ (strpos(Route::current()->getName(), 'user.show') !== false || strpos(Route::current()->getName(), 'user.profile') !== false) ? ' active' : '' }}"
                           href="{{ url('/profile') }}">Profile</a>
                        <a class="dropdown-item" href="{{ url('/logout') }}">Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    @elseauth
        <div class="navbar-collapse collapse w-100 order-2 dual-collapse2">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="user-dropdown" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">Guest</a>
                    <div class="dropdown-menu" aria-labelledby="user-dropdown">
                        <a class="dropdown-item" href="{{ url('/login') }}">Login</a>
                        <a class="dropdown-item" href="{{ url('/register') }}">Register</a>
                    </div>
                </li>
            </ul>
        </div>
    @endauth
    {{--    @TODO add course search form right menu --}}
    {{--        <form class="form-inline my-2 my-lg-0">--}}
    {{--            <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">--}}
    {{--            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>--}}
    {{--        </form>--}}
    {{--    </div>--}}
</nav>
