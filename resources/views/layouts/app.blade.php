@include('includes.head')

<main class="container" role="main">
    <h1>{{ $title }}</h1>
    @yield('content')
</main>

@include('includes.footer')
