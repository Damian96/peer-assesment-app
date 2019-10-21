@include('includes.head')

<main class="container" role="main">
    <section class="container-fluid px-md-0">
        <h1>{{ $title }}</h1>
        @yield('content')
    </section>
</main>

@include('includes.footer')
