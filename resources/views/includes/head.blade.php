<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title }}</title>

    <link rel="stylesheet" href="{{ url('/css/app.css') }}">
    <script charset="utf-8" src="{{ url('/js/app.js') }}"></script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="{{ url('/home') }}">PeerAssess</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainMenu" aria-controls="mainMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2" id="mainMenu">
            @if (Auth::check())
        <ul class="navbar-nav mr-auto">
            @if (strpos(Route::current()->getName(), 'home') !== false)
            <li class="nav-item active">
            @else
            <li class="nav-item">
            @endif
                <a class="nav-link" href="{{ url('/home') }}">Home
                @if (strpos(Route::current()->getName(), 'home') !== false)
                    <span class="sr-only">(current)</span>
                @endif
                </a>
            </li>
        @endif
        </ul>
    </div>
    <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                @if (Auth::check())
                <a class="nav-link dropdown-toggle" href="#" id="user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->fname . ' ' . Auth::user()->lname }}</a>
                <div class="dropdown-menu" aria-labelledby="user-dropdown">
                    <a class="dropdown-item" href="#">Profile</a>
                    <a class="dropdown-item" href="{{ url('/logout') }}">Logout</a>
                </div>
                @else
                    <a class="nav-link dropdown-toggle" href="#" id="user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Guest</a>
                    <div class="dropdown-menu" aria-labelledby="user-dropdown">
                        <a class="dropdown-item" href="{{ url('/login') }}">Login</a>
                        <a class="dropdown-item" href="{{ url('/register') }}">Register</a>
                    </div>
                @endif
            </li>
        </ul>
    </div>
{{--        <form class="form-inline my-2 my-lg-0">--}}
{{--            <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">--}}
{{--            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>--}}
{{--        </form>--}}
{{--    </div>--}}
</nav>
