@include('includes.head')

<main class="container text-center p-0" role="main" data-status="{{ $error_number ?? '' }}">
    @switch($error_number)
        @case(404)
        @php include_once public_path('/images/undraw_not_found_60pq.svg') @endphp
        @break
        @default
    @endswitch
    <section class="title px-2">
        @yield('title')
    </section>
        <section class="content px-2 mt-5">
            @yield('content')
    </section>
</main>

@if (session()->has('message'))
    @component('includes.alert')
        @slot('level'){{ session()->get('message')['level'] }}@endslot
        @slot('heading'){{ session()->get('message')['heading'] }}@endslot
        @slot('body'){{ session()->get('message')['body'] ?? '' }}@endslot
    @endcomponent
@endif

@include('includes.footer')
