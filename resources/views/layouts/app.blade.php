@include('includes.head')

<div class="container mt-5 pt-5">
    <h1>{{ $title }}</h1>
    @yield('content')
</div>

@include('includes.footer')
