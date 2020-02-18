@include('includes.head')

<main class="container text-center" role="main">
    @yield('title')
    @yield('content')
</main>

@if (session()->has('message'))
    @component('includes.alert')
        @slot('level'){{ session()->get('message')['level'] }}@endslot
        @slot('heading'){{ session()->get('message')['heading'] }}@endslot
        @slot('body'){{ session()->get('message')['body'] ?? '' }}@endslot
    @endcomponent
@endif

@include('includes.footer')
