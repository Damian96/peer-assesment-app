@include('includes.head')

<main class="container" role="main">
    <section class="container-fluid px-md-0">
        @if (session()->has('message'))
            @component('includes.alert')
                @slot('level'){{ session()->get('message')['level'] }}@endslot
                @slot('heading'){{ session()->get('message')['heading'] }}@endslot
                @slot('body'){{ session()->get('message')['body'] ?? '' }}@endslot
            @endcomponent
        @endif
        @yield('breadcrumbs')
        @if (isset($title))
            <h3 class="title">{{ $title ?? '' }}</h3>
        @endif
        @yield('content')
    </section>
</main>

@include('includes.footer')
